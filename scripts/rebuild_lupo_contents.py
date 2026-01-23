import json
import re
import sys
from datetime import datetime, timezone
from pathlib import Path

import pymysql


IGNORE_FILENAMES = {"readme.md", "index.md"}
HEADING_RE = re.compile(r"^(#{2,6})\s+(.*)$")


def utc_timestamp_ymdhis():
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def should_ignore(path: Path) -> bool:
    name = path.name.lower()
    if name in IGNORE_FILENAMES:
        return True
    if path.name.startswith("_"):
        return True
    return False


def extract_title_and_description(text: str, fallback_title: str):
    lines = text.splitlines()
    title = None
    title_idx = None

    for i, line in enumerate(lines):
        if line.startswith("# "):
            title = line[2:].strip()
            title_idx = i
            break

    if not title:
        title = fallback_title

    desc_lines = []
    i = 0 if title_idx is None else title_idx + 1

    while i < len(lines) and lines[i].strip() == "":
        i += 1

    if i < len(lines) and lines[i].lstrip().startswith("#"):
        return title, ""

    while i < len(lines) and lines[i].strip() != "":
        desc_lines.append(lines[i].strip())
        i += 1

    description = " ".join(desc_lines).strip()
    return title, description


def extract_headings(text: str):
    headings = []
    for line in text.splitlines():
        match = HEADING_RE.match(line.strip())
        if match:
            level = len(match.group(1))
            heading_text = match.group(2).strip()
            headings.append({"level": level, "text": heading_text})
    return headings


def determine_content_type(path: Path):
    lower_parts = [p.lower() for p in path.parts]
    if "ui-ux" in lower_parts or "architecture" in lower_parts:
        return "page"
    return "article"


def iter_markdown_files(root: Path):
    for path in root.rglob("*.md"):
        if should_ignore(path):
            continue
        yield path


def main():
    dry_run = True  # Set to False to execute inserts

    root = Path("/docs/channels/")
    if not root.exists():
        print(f"Root path not found: {root}", file=sys.stderr)
        sys.exit(1)

    timestamp = utc_timestamp_ymdhis()

    rows = []
    for md_file in iter_markdown_files(root):
        text = md_file.read_text(encoding="utf-8")

        title, description = extract_title_and_description(text, md_file.stem)
        slug = md_file.stem
        content_sections = json.dumps(extract_headings(text), ensure_ascii=True)
        content_type = determine_content_type(md_file)

        row = {
            "title": title,
            "slug": slug,
            "description": description,
            "body": text,
            "content_sections": content_sections,
            "content_type": content_type,
            "format": "markdown",
            "created_ymdhis": timestamp,
            "updated_ymdhis": timestamp,
            "utc_cycle": "creative",
            "triage_status": "untriaged",
            "is_deleted": 0,
            "is_active": 1,
            "version_number": 1,
        }
        rows.append(row)

    if dry_run:
        print(f"DRY RUN: would truncate and insert {len(rows)} records.")
        return

    conn = pymysql.connect(
        host="YOUR_HOST",
        user="YOUR_USER",
        password="YOUR_PASSWORD",
        database="YOUR_DATABASE",
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
    )

    try:
        with conn.cursor() as cursor:
            cursor.execute("TRUNCATE TABLE lupo_contents")

            sql = """
                INSERT INTO lupo_contents (
                    title,
                    slug,
                    description,
                    body,
                    content_sections,
                    content_type,
                    format,
                    created_ymdhis,
                    updated_ymdhis,
                    utc_cycle,
                    triage_status,
                    is_deleted,
                    is_active,
                    version_number
                ) VALUES (
                    %(title)s,
                    %(slug)s,
                    %(description)s,
                    %(body)s,
                    %(content_sections)s,
                    %(content_type)s,
                    %(format)s,
                    %(created_ymdhis)s,
                    %(updated_ymdhis)s,
                    %(utc_cycle)s,
                    %(triage_status)s,
                    %(is_deleted)s,
                    %(is_active)s,
                    %(version_number)s
                )
            """
            cursor.executemany(sql, rows)
        conn.commit()
    finally:
        conn.close()


if __name__ == "__main__":
    main()

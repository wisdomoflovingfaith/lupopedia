import argparse
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


def slugify_relative_path(path: Path):
    stem_path = path.with_suffix("")
    raw = "-".join(stem_path.parts)
    slug = raw.lower()
    slug = re.sub(r"[\s_]+", "-", slug)
    slug = re.sub(r"[^a-z0-9\-]", "", slug)
    slug = re.sub(r"-+", "-", slug).strip("-")
    return slug


def iter_markdown_files(root: Path):
    for path in root.rglob("*.md"):
        if should_ignore(path):
            continue
        yield path


def load_db_config(config_path: Path):
    text = config_path.read_text(encoding="utf-8")

    def read_define(name):
        pattern = re.compile(
            r"define\(\s*['\"]" + re.escape(name) + r"['\"]\s*,\s*['\"]([^'\"]*)['\"]\s*\)",
            re.IGNORECASE,
        )
        match = pattern.search(text)
        return match.group(1) if match else None

    def read_assignment(name):
        pattern = re.compile(
            r"^\s*" + re.escape(name) + r"\s*=\s*['\"]([^'\"]*)['\"]\s*;",
            re.IGNORECASE | re.MULTILINE,
        )
        match = pattern.search(text)
        return match.group(1) if match else None

    config = {
        "DB_HOST": read_define("DB_HOST"),
        "DB_USER": read_define("DB_USER"),
        "DB_PASSWORD": read_define("DB_PASSWORD"),
        "DB_NAME": read_define("DB_NAME"),
        "DB_PORT": read_define("DB_PORT"),
        "DB_CHARSET": read_define("DB_CHARSET") or "utf8mb4",
        "TABLE_PREFIX": read_assignment("table_prefix") or "lupo_",
    }
    return config


def resolve_channel_key(root: Path, md_file: Path):
    try:
        rel_parts = md_file.relative_to(root).parts
    except ValueError:
        return None
    if not rel_parts:
        return None
    return rel_parts[0]


def main():
    parser = argparse.ArgumentParser(description="Rebuild lupo_contents from Markdown files.")
    parser.add_argument(
        "--root",
        default=None,
        help="Root directory to scan for Markdown (default: docs/channels).",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Print counts without writing to the database.",
    )
    parser.add_argument(
        "--default-channel-id",
        type=int,
        default=0,
        help="Fallback channel_id when a channel_key cannot be resolved.",
    )
    args = parser.parse_args()

    root = Path(args.root) if args.root else Path(__file__).resolve().parent.parent / "docs" / "channels"
    if not root.exists():
        print(f"Root path not found: {root}", file=sys.stderr)
        sys.exit(1)

    config_path = Path(__file__).resolve().parent.parent / "lupopedia-config.php"
    if not config_path.exists():
        print(f"Config not found: {config_path}", file=sys.stderr)
        sys.exit(1)

    db_config = load_db_config(config_path)
    missing = [key for key in ("DB_HOST", "DB_USER", "DB_PASSWORD", "DB_NAME") if not db_config.get(key)]
    if missing:
        print(f"Missing database config keys: {', '.join(missing)}", file=sys.stderr)
        sys.exit(1)

    table_prefix = db_config.get("TABLE_PREFIX") or "lupo_"
    contents_table = f"{table_prefix}contents"

    timestamp = utc_timestamp_ymdhis()

    rows = []
    slug_channel_map = {}
    seen_slugs = set()
    duplicate_slugs = []
    missing_channels = set()
    for md_file in iter_markdown_files(root):
        text = md_file.read_text(encoding="utf-8", errors="replace")

        title, description = extract_title_and_description(text, md_file.stem)
        rel_path = md_file.relative_to(root)
        slug = slugify_relative_path(rel_path)
        if slug in seen_slugs:
            duplicate_slugs.append(slug)
            continue
        seen_slugs.add(slug)
        content_sections = json.dumps(extract_headings(text), ensure_ascii=True)
        content_type = determine_content_type(md_file)

        row = {
            "title": title,
            "slug": slug,
            # custom_path is a semantic routing override used by the IDE and semantic OS.
            # It must never be interpreted as a filesystem path.
            # PHP agents must continue using content_url for machine-local file resolution.
            "custom_path": None,
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

        channel_key = resolve_channel_key(root, md_file)
        if channel_key:
            slug_channel_map[slug] = channel_key
        else:
            missing_channels.add(md_file.as_posix())

    if args.dry_run:
        print(f"DRY RUN: would truncate and insert {len(rows)} records.")
        if duplicate_slugs:
            print(f"Skipped {len(duplicate_slugs)} duplicate slugs.")
        if missing_channels:
            print(f"Missing channel keys for {len(missing_channels)} files.")
        return

    conn = pymysql.connect(
        host=db_config["DB_HOST"],
        user=db_config["DB_USER"],
        password=db_config["DB_PASSWORD"],
        database=db_config["DB_NAME"],
        port=int(db_config["DB_PORT"] or 3306),
        charset=db_config["DB_CHARSET"],
        cursorclass=pymysql.cursors.DictCursor,
    )

    try:
        with conn.cursor() as cursor:
            cursor.execute(f"TRUNCATE TABLE {contents_table}")
            cursor.execute(
                "DELETE FROM lupo_edges WHERE left_object_type = 'content' OR right_object_type = 'content'"
            )

            sql = """
                INSERT INTO {table} (
                    title,
                    slug,
                    custom_path,
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
                    %(custom_path)s,
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
            cursor.executemany(sql.format(table=contents_table), rows)

            cursor.execute("SELECT channel_id, channel_key FROM lupo_channels WHERE is_deleted = 0")
            channel_rows = cursor.fetchall()
            channel_key_map = {row["channel_key"]: row["channel_id"] for row in channel_rows}

            cursor.execute(f"SELECT content_id, slug FROM {contents_table}")
            content_rows = cursor.fetchall()
            content_id_map = {row["slug"]: row["content_id"] for row in content_rows}

            edge_rows = []
            for slug, channel_key in slug_channel_map.items():
                content_id = content_id_map.get(slug)
                if content_id is None:
                    continue
                channel_id = channel_key_map.get(channel_key, args.default_channel_id)
                if channel_key not in channel_key_map:
                    missing_channels.add(channel_key)
                edge_rows.append(
                    (
                        "channel",
                        channel_id,
                        "content",
                        content_id,
                        "HAS_CONTENT",
                        channel_id,
                        channel_key if channel_key in channel_key_map else None,
                        0,
                        0,
                        1,
                        0,
                        0,
                        timestamp,
                        timestamp,
                        0.0,
                        "semantic",
                        0,
                        None,
                    )
                )

            if edge_rows:
                edge_sql = """
                    INSERT INTO lupo_edges (
                        left_object_type,
                        left_object_id,
                        right_object_type,
                        right_object_id,
                        edge_type,
                        channel_id,
                        channel_key,
                        weight_score,
                        sort_num,
                        actor_id,
                        is_deleted,
                        deleted_ymdhis,
                        created_ymdhis,
                        updated_ymdhis,
                        semantic_weight,
                        relationship_type,
                        bidirectional,
                        context_scope
                    ) VALUES (
                        %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
                    )
                """
                cursor.executemany(edge_sql, edge_rows)

        conn.commit()
        print(f"Inserted {len(rows)} rows into {contents_table}.")
        if edge_rows:
            print(f"Inserted {len(edge_rows)} channel-content edges.")
        if duplicate_slugs:
            print(f"Skipped {len(duplicate_slugs)} duplicate slugs.")
        if missing_channels:
            print(
                "Missing channel keys (used fallback channel_id): "
                + ", ".join(sorted(missing_channels))
            )
    finally:
        conn.close()


if __name__ == "__main__":
    main()

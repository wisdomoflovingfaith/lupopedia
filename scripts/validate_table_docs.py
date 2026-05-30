# lupopedia.headers:
#   when_updated: "20260324182200"
#   file_path_from_root: "scripts/validate_table_docs.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324182200"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

import argparse
import datetime as dt
import re
from pathlib import Path

import yaml


TOON_DIR = Path("database/lupopedia/toon")
TABLE_DOCS_DIR = Path("docs/database/lupopedia/tables")
REPORT_PATH = TABLE_DOCS_DIR / "VALIDATION_REPORT_JUNIE.md"
CUTOFF_UTC = 20260301000000


def _parse_frontmatter(md_content):
    if not md_content.startswith("---"):
        return None
    parts = md_content.split("---", 2)
    if len(parts) < 3:
        return None
    try:
        parsed = yaml.safe_load(parts[1])
    except Exception:
        return None
    if not isinstance(parsed, dict):
        return None
    return parsed


def _headers_footer_from_frontmatter(frontmatter):
    headers = {}
    footer = {}
    if isinstance(frontmatter.get("lupopedia.headers"), dict):
        headers = frontmatter["lupopedia.headers"]
    elif isinstance(frontmatter.get("lupopedia"), dict) and isinstance(
        frontmatter["lupopedia"].get("headers"), dict
    ):
        headers = frontmatter["lupopedia"]["headers"]
    if isinstance(frontmatter.get("lupopedia.footer"), dict):
        footer = frontmatter["lupopedia.footer"]
    elif isinstance(frontmatter.get("lupopedia"), dict) and isinstance(
        frontmatter["lupopedia"].get("footer"), dict
    ):
        footer = frontmatter["lupopedia"]["footer"]
    return headers, footer


def _to_ymdhis(raw):
    if raw is None:
        return None
    if isinstance(raw, int):
        s = str(raw)
    else:
        s = "".join(ch for ch in str(raw) if ch.isdigit())
    if len(s) == 8:
        s += "000000"
    if len(s) != 14:
        return None
    return int(s)


def get_toon_columns(table_name):
    toon_path = TOON_DIR / ("%s.toon" % table_name)
    if not toon_path.exists():
        return None
    try:
        toon = yaml.safe_load(toon_path.read_text(encoding="utf-8"))
    except Exception:
        return None
    if not isinstance(toon, dict):
        return None
    fields = toon.get("fields")
    if not isinstance(fields, list):
        return None
    columns = {}
    for item in fields:
        if not isinstance(item, str):
            continue
        m = re.match(r"^\s*`([^`]+)`\s+(.+)$", item.strip().strip("'"))
        if not m:
            continue
        columns[m.group(1).strip()] = m.group(2).strip()
    return columns


def parse_md_columns(md_content):
    lines = md_content.splitlines()
    start = None
    for idx, line in enumerate(lines):
        if re.search(r"^\|\s*Column\s*\|\s*Type\s*\|", line):
            start = idx
            break
    if start is None or start + 2 >= len(lines):
        return None
    columns = {}
    for row in lines[start + 2 :]:
        if not row.strip().startswith("|"):
            break
        parts = [p.strip() for p in row.split("|")]
        if len(parts) < 4:
            continue
        col_name = parts[1]
        col_type = parts[2]
        if col_name and col_name.lower() != "column":
            columns[col_name] = col_type
    return columns


def validate(subdirs):
    now = dt.datetime.now(dt.timezone.utc).strftime("%Y-%m-%d %H:%M:%S UTC")
    report = []
    report.append("# Documentation Validation Report\n")
    report.append("\n")
    report.append("- Generated on: %s\n" % now)
    report.append("- Cutoff UTC for footer freshness: 2026-03-01 00:00:00\n")
    report.append("\n")

    total_checked = 0
    total_errors = 0

    toon_tables = {p.stem for p in TOON_DIR.glob("*.toon")}

    for subdir in subdirs:
        dir_path = TABLE_DOCS_DIR / subdir
        if not dir_path.is_dir():
            continue
        report.append("## Directory: %s\n\n" % subdir)
        md_tables = {p.stem for p in dir_path.glob("*.md")}
        missing_docs = sorted(toon_tables - md_tables) if subdir == "active" else []
        extra_docs = sorted(md_tables - toon_tables) if subdir == "active" else []

        if missing_docs:
            total_errors += 1
            report.append("### [MISSING_DOCS]\n")
            report.extend("- `%s`\n" % t for t in missing_docs)
            report.append("\n")
        if extra_docs:
            total_errors += 1
            report.append("### [EXTRA_DOCS_WITHOUT_TOON]\n")
            report.extend("- `%s`\n" % t for t in extra_docs)
            report.append("\n")

        for md_path in sorted(dir_path.glob("*.md")):
            table_name = md_path.stem
            total_checked += 1
            md_content = md_path.read_text(encoding="utf-8", errors="replace")
            md_columns = parse_md_columns(md_content)
            toon_columns = get_toon_columns(table_name)
            frontmatter = _parse_frontmatter(md_content)
            errors = []

            if toon_columns is None:
                errors.append("Missing TOON: `%s.toon`" % table_name)
            if md_columns is None:
                errors.append("Missing or unreadable `| Column | Type |` table")
            if toon_columns is not None and md_columns is not None:
                toon_set = set(toon_columns.keys())
                md_set = set(md_columns.keys())
                for missing in sorted(toon_set - md_set):
                    errors.append("Missing column in doc: `%s`" % missing)
                for extra in sorted(md_set - toon_set):
                    errors.append("Extra column in doc: `%s`" % extra)

            if frontmatter is None:
                errors.append("Missing or invalid YAML frontmatter")
            else:
                headers, footer = _headers_footer_from_frontmatter(frontmatter)
                when_updated = headers.get("when_updated")
                if _to_ymdhis(when_updated) is None:
                    errors.append("Header missing/invalid `when_updated`")
                if "version_when_written" in headers:
                    errors.append("Deprecated header field present: `version_when_written`")
                if _to_ymdhis(footer.get("last_verified")) is None:
                    errors.append("Footer missing/invalid `last_verified`")
                else:
                    lv = _to_ymdhis(footer.get("last_verified"))
                    if lv is not None and lv < CUTOFF_UTC:
                        errors.append("Footer stale: `last_verified` before 20260301000000")
                if not str(footer.get("last_verified_by", "")).strip():
                    errors.append("Footer missing `last_verified_by`")
                actor_id = footer.get("last_verified_by_actor_id")
                if not str(actor_id).strip().isdigit():
                    errors.append("Footer missing/invalid `last_verified_by_actor_id`")

            if errors:
                total_errors += 1
                report.append("### %s: [FAIL]\n" % table_name)
                report.extend("- %s\n" % e for e in errors)
            else:
                report.append("### %s: [OK]\n" % table_name)
            report.append("\n")

    report.insert(
        3,
        "## Summary\n- Total Checked: %d\n- Total Failing Entries: %d\n\n"
        % (total_checked, total_errors),
    )
    REPORT_PATH.write_text("".join(report), encoding="utf-8")
    print(
        "Validation complete. Failures: %d. Report: %s"
        % (total_errors, REPORT_PATH.as_posix())
    )


if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument(
        "--subdirs",
        default="active,planning",
        help="Comma-separated table-doc subdirs under docs/database/lupopedia/tables",
    )
    args = parser.parse_args()
    targets = [s.strip() for s in args.subdirs.split(",") if s.strip()]
    validate(targets)

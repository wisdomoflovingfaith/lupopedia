#!/usr/bin/env python3
"""
generate_flip_header.py — Reconstruct a full FLIP/Wolfie/CROP/FLIPPING header from DB.

Input: file_path_from_root OR link address (URL) OR content_id.
Queries lupo_contents (and lupo_edges for channel_id); outputs a verbose
human-readable FLIP Header block.

Requirements: Python 3.8+. mysql-connector-python. No other external deps.
Doctrine: docs/doctrine/FLIP/, NOTE_HEADER_VERSION_AND_MERGE.md.
"""
import argparse
import json
import sys

try:
    import mysql.connector
except ImportError:
    print("Error: mysql-connector-python required. pip install mysql-connector-python", file=sys.stderr)
    sys.exit(1)

# Default DB config (override via env or CLI if needed)
DEFAULT_DB = {
    "host": "localhost",
    "user": "root",
    "password": "ServBay.dev",
    "database": "lupopedia",
}

TABLE_PREFIX = "lupo_"


_COLS = "content_id, file_path_from_root, file_last_modified_system_version, " \
        "file_last_modified_utc, slug, title, content_url, custom_path, content_type, dialog_notes"

def get_content_by_path(cursor, path):
    """Fetch one row from lupo_contents by file_path_from_root. Parameterized."""
    table = TABLE_PREFIX + "contents"
    sql = "SELECT " + _COLS + " FROM " + table + " WHERE file_path_from_root = %s AND is_deleted = 0 LIMIT 1"
    cursor.execute(sql, (path,))
    return cursor.fetchone()


def get_content_by_url(cursor, url):
    """Fetch one row by content_url or custom_path. Parameterized."""
    table = TABLE_PREFIX + "contents"
    sql = "SELECT " + _COLS + " FROM " + table + " WHERE (content_url = %s OR custom_path = %s) AND is_deleted = 0 LIMIT 1"
    cursor.execute(sql, (url, url))
    return cursor.fetchone()


def get_content_by_id(cursor, content_id):
    """Fetch one row by content_id. Parameterized."""
    table = TABLE_PREFIX + "contents"
    sql = "SELECT " + _COLS + " FROM " + table + " WHERE content_id = %s AND is_deleted = 0 LIMIT 1"
    cursor.execute(sql, (content_id,))
    return cursor.fetchone()


def get_channel_id_for_content(cursor, content_id):
    """Resolve channel_id from lupo_edges: channel HAS_CONTENT content. Parameterized."""
    edges = TABLE_PREFIX + "edges"
    sql = "SELECT left_object_id FROM " + edges + " " \
          "WHERE left_object_type = %s AND right_object_type = %s AND right_object_id = %s " \
          "AND edge_type = %s AND is_deleted = 0 LIMIT 1"
    cursor.execute(sql, ("channel", "content", content_id, "HAS_CONTENT"))
    row = cursor.fetchone()
    return row[0] if row else None


def format_utc(utc_val):
    """Format BIGINT YYYYMMDDHHIISS as string for header; empty if missing."""
    if utc_val is None:
        return ""
    s = str(utc_val)
    return s if len(s) == 14 else ""


def build_header(row, channel_id=None):
    """Build a full FLIP Header block (doctrine-required fields). Optionally include dialog from dialog_notes."""
    content_id, file_path_from_root, file_last_modified_system_version, \
        file_last_modified_utc, slug, title, content_url, custom_path, content_type, dialog_notes = row
    path = file_path_from_root or ""
    ver = file_last_modified_system_version or "0000"
    utc = format_utc(file_last_modified_utc) or "00000000000000"
    lines = [
        "---",
        "# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)",
        "wolfie.headers: explicit architecture with structured clarity for every file.",
        "file_path_from_root: " + path,
        "file.last_modified_system_version: \"" + str(ver) + "\"",
        "file.last_modified_utc: \"" + utc + "\"",
    ]
    if channel_id is not None:
        lines.append("channel_id: " + str(channel_id))
    else:
        lines.append("# channel_id unresolved — requires lupo_contents lookup by application.")
    if dialog_notes and str(dialog_notes).strip():
        lines.append("")
        lines.append("# optional dialog (from dialog_notes; not for inference)")
        lines.append(str(dialog_notes).strip())
    lines.append("---")
    return "\n".join(lines)


def main():
    ap = argparse.ArgumentParser(
        description="Generate full FLIP/Wolfie header from file_path_from_root, URL, or content_id."
    )
    g = ap.add_mutually_exclusive_group(required=True)
    g.add_argument("--path", metavar="PATH", help="file_path_from_root (e.g. docs/doctrine/FLIP/README.md)")
    g.add_argument("--url", metavar="URL", help="Link address / content_url or custom_path")
    g.add_argument("--content-id", metavar="ID", type=int, help="content_id")
    ap.add_argument("--web", action="store_true", help="Output JSON for API compatibility (header, resolved, channel_id)")
    ap.add_argument("--host", default=DEFAULT_DB["host"], help="MySQL host")
    ap.add_argument("--user", default=DEFAULT_DB["user"], help="MySQL user")
    ap.add_argument("--password", default=DEFAULT_DB["password"], help="MySQL password")
    ap.add_argument("--database", default=DEFAULT_DB["database"], help="MySQL database")
    args = ap.parse_args()

    conn = mysql.connector.connect(
        host=args.host,
        user=args.user,
        password=args.password,
        database=args.database,
    )
    cursor = conn.cursor(dictionary=False)

    row = None
    if args.path is not None:
        row = get_content_by_path(cursor, args.path.strip())
    elif args.url is not None:
        row = get_content_by_url(cursor, args.url.strip())
    else:
        row = get_content_by_id(cursor, args.content_id)

    if not row:
        print("No content found for the given input.", file=sys.stderr)
        cursor.close()
        conn.close()
        sys.exit(1)

    content_id = row[0]
    channel_id = get_channel_id_for_content(cursor, content_id)
    header_block = build_header(row, channel_id)
    resolved = channel_id is not None

    if getattr(args, "web", False):
        out = {"header": header_block, "resolved": resolved, "channel_id": channel_id}
        print(json.dumps(out, indent=2, ensure_ascii=False))
    else:
        print(header_block)

    cursor.close()
    conn.close()


if __name__ == "__main__":
    main()

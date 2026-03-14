import argparse
import json
import re
import sys
from decimal import Decimal
from pathlib import Path

import pymysql


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


def parse_json_field(value):
    if value in (None, ""):
        return None
    if isinstance(value, (dict, list)):
        return value
    try:
        return json.loads(value)
    except json.JSONDecodeError:
        return None


def json_default(value):
    if isinstance(value, Decimal):
        return float(value)
    if isinstance(value, bytes):
        return value.decode("utf-8", errors="replace")
    return str(value)


def write_json(path: Path, payload):
    path.write_text(
        json.dumps(payload, indent=2, ensure_ascii=True, default=json_default) + "\n",
        encoding="utf-8",
    )


def fetch_all(cursor, sql, params=None):
    cursor.execute(sql, params or ())
    return cursor.fetchall()


def main():
    parser = argparse.ArgumentParser(description="Export channel snapshots into /channels/<id>/.")
    parser.add_argument(
        "--channel-id",
        action="append",
        type=int,
        default=None,
        help="Export only the specified channel_id (repeatable).",
    )
    parser.add_argument(
        "--output-root",
        default=None,
        help="Root directory for channel snapshots (default: channels/).",
    )
    args = parser.parse_args()

    repo_root = Path(__file__).resolve().parent.parent
    output_root = Path(args.output_root) if args.output_root else repo_root / "channels"
    config_path = repo_root / "lupopedia-config.php"

    if not config_path.exists():
        print(f"Config not found: {config_path}", file=sys.stderr)
        sys.exit(1)

    db_config = load_db_config(config_path)
    missing = [key for key in ("DB_HOST", "DB_USER", "DB_PASSWORD", "DB_NAME") if not db_config.get(key)]
    if missing:
        print(f"Missing database config keys: {', '.join(missing)}", file=sys.stderr)
        sys.exit(1)

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
            if args.channel_id:
                placeholders = ", ".join(["%s"] * len(args.channel_id))
                channel_rows = fetch_all(
                    cursor,
                    f"SELECT * FROM lupo_channels WHERE channel_id IN ({placeholders})",
                    args.channel_id,
                )
            else:
                channel_rows = fetch_all(cursor, "SELECT * FROM lupo_channels")

            if not channel_rows:
                print("No channels found to export.")
                return

            for channel in channel_rows:
                channel_id = int(channel["channel_id"])
                channel_dir = output_root / f"{channel_id:04d}"
                channel_dir.mkdir(parents=True, exist_ok=True)

                write_json(channel_dir / "channel.json", channel)

                edges = fetch_all(
                    cursor,
                    """
                    SELECT * FROM lupo_edges
                    WHERE (left_object_type = 'channel' AND left_object_id = %s)
                       OR (right_object_type = 'channel' AND right_object_id = %s)
                    """,
                    (channel_id, channel_id),
                )
                write_json(channel_dir / "edges.json", edges)

                threads = fetch_all(
                    cursor,
                    "SELECT * FROM lupo_dialog_threads WHERE channel_id = %s",
                    (channel_id,),
                )
                write_json(channel_dir / "threads.json", threads)

                contents = fetch_all(
                    cursor,
                    """
                    SELECT DISTINCT c.*
                    FROM lupo_contents c
                    JOIN lupo_edges e
                      ON (
                        (e.left_object_type = 'content' AND e.left_object_id = c.content_id)
                        OR (e.right_object_type = 'content' AND e.right_object_id = c.content_id)
                      )
                    WHERE (e.left_object_type = 'channel' AND e.left_object_id = %s)
                       OR (e.right_object_type = 'channel' AND e.right_object_id = %s)
                    """,
                    (channel_id, channel_id),
                )
                write_json(channel_dir / "contents.json", contents)

                metadata = {
                    "channel": parse_json_field(channel.get("metadata_json")),
                    "threads": [
                        parse_json_field(row.get("metadata_json")) for row in threads if row.get("metadata_json")
                    ],
                    "contents": [],
                }
                write_json(channel_dir / "metadata.json", metadata)

                print(f"Exported channel {channel_id} -> {channel_dir}")
    finally:
        conn.close()


if __name__ == "__main__":
    main()

#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/sync_channel_artifacts.py"
#   last_modified_utc: "20260324175617"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Synchronize lupo-channels message artifacts with lupo_dialog_messages (optional DB).

Filesystem pass (always): scan broadcasts/, direct/*/, threads/*/*.md for dialog_message_id in YAML front matter.

Database pass (optional): requires PyMySQL and MYSQL_DSN or --host --user --password --database.
  --reconcile-db: list DB row IDs for channel that have no matching artifact file (by dialog_message_id).
  --reconcile-fs: list artifact dialog_message_ids not found in DB.

Usage:
  python lupo-scripts/sync_channel_artifacts.py --repo-root . --channel 42
  python lupo-scripts/sync_channel_artifacts.py --repo-root . --channel 42 --reconcile-db \\
    --host 127.0.0.1 --user root --password x --database lupopedia
"""
from __future__ import annotations

import argparse
import json
import os
import subprocess
import sys
from pathlib import Path


def parse_front_matter_ids(content: str) -> dict:
    out = {}
    if not content.startswith("---"):
        return out
    end = content.find("\n---", 3)
    if end < 0:
        return out
    block = content[3:end]
    for line in block.splitlines():
        if ":" not in line:
            continue
        k, _, v = line.partition(":")
        k, v = k.strip(), v.strip().strip('"').strip("'")
        if k in ("dialog_message_id", "channel_id", "from_actor_id", "created_ymdhis"):
            try:
                out[k] = int(v) if v.isdigit() or (v.startswith("-") and v[1:].isdigit()) else v
            except ValueError:
                out[k] = v
    return out


def scan_channel_artifacts(repo_root: Path, channel_id: int) -> list[dict]:
    base = repo_root / "lupo-channels" / str(channel_id)
    found: list[Path] = []
    br = base / "broadcasts"
    if br.is_dir():
        found.extend(br.glob("*.md"))
    dr = base / "direct"
    if dr.is_dir():
        for sub in dr.iterdir():
            if sub.is_dir():
                found.extend(sub.glob("*.md"))
    th = base / "threads"
    if th.is_dir():
        for sub in th.iterdir():
            if sub.is_dir():
                found.extend(sub.glob("*.md"))
    rows = []
    for path in sorted(set(found)):
        try:
            text = path.read_text(encoding="utf-8", errors="replace")
        except OSError:
            continue
        meta = parse_front_matter_ids(text)
        mid = meta.get("dialog_message_id")
        rows.append(
            {
                "path": str(path.relative_to(repo_root)).replace("\\", "/"),
                "dialog_message_id": mid,
                "channel_id": meta.get("channel_id"),
            }
        )
    return rows


def db_message_ids(host, user, password, database, table_prefix, channel_id):
    try:
        import pymysql
    except ImportError:
        print("PyMySQL not installed; pip install pymysql for DB reconcile.", file=sys.stderr)
        return None
    conn = pymysql.connect(
        host=host, user=user, password=password, database=database, charset="utf8mb4"
    )
    t = f"{table_prefix}dialog_messages"
    with conn.cursor() as cur:
        cur.execute(
            f"SELECT dialog_message_id FROM {t} WHERE channel_id = %s AND is_deleted = 0",
            (channel_id,),
        )
        ids = {int(r[0]) for r in cur.fetchall()}
    conn.close()
    return ids


def main() -> int:
    ap = argparse.ArgumentParser(description="Channel artifact ↔ DB sync helper")
    ap.add_argument("--repo-root", default=".", help="Repository root")
    ap.add_argument("--channel", type=int, default=42)
    ap.add_argument("--json-out", help="Write scan JSON to file")
    ap.add_argument("--reconcile-db", action="store_true")
    ap.add_argument("--reconcile-fs", action="store_true")
    ap.add_argument("--host", default=os.environ.get("MYSQL_HOST", "127.0.0.1"))
    ap.add_argument("--user", default=os.environ.get("MYSQL_USER", ""))
    ap.add_argument("--password", default=os.environ.get("MYSQL_PASSWORD", ""))
    ap.add_argument("--database", default=os.environ.get("MYSQL_DATABASE", ""))
    ap.add_argument("--table-prefix", default=os.environ.get("LUPO_TABLE_PREFIX", "lupo_"))
    ap.add_argument(
        "--validate",
        action="store_true",
        help="Run validate_channel_artifacts.py --strict first; exit 1 on violations",
    )
    args = ap.parse_args()
    root = Path(args.repo_root).resolve()
    if args.validate:
        vpy = Path(__file__).resolve().parent / "validate_channel_artifacts.py"
        r = subprocess.run(
            [sys.executable, str(vpy), "--repo-root", str(root), "--channel", str(args.channel), "--strict"],
            cwd=str(root),
        )
        if r.returncode != 0:
            return r.returncode
    rows = scan_channel_artifacts(root, args.channel)
    fs_ids = {r["dialog_message_id"] for r in rows if r.get("dialog_message_id")}

    if args.json_out:
        Path(args.json_out).write_text(json.dumps(rows, indent=2), encoding="utf-8")
        print("Wrote", args.json_out)

    print(f"Scanned channel {args.channel}: {len(rows)} artifact files, {len(fs_ids)} with dialog_message_id")

    if args.reconcile_db or args.reconcile_fs:
        if not args.user or not args.database:
            print("Need --user and --database (or env MYSQL_USER, MYSQL_DATABASE)", file=sys.stderr)
            return 1
        db_ids = db_message_ids(
            args.host, args.user, args.password, args.database, args.table_prefix, args.channel
        )
        if db_ids is None:
            return 1
        if args.reconcile_fs:
            only_fs = fs_ids - db_ids
            print("Artifact IDs not in DB:", sorted(only_fs)[:50], "..." if len(only_fs) > 50 else "")
        if args.reconcile_db:
            only_db = db_ids - fs_ids
            print("DB message IDs without artifact file:", len(only_db))

    return 0


if __name__ == "__main__":
    sys.exit(main())
#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# DRAFT — lupo-scripts/migrate_transcript_to_memory.py
# Read existing transcript.jsonl files in lupo-channels/
# For each entry, create a .toon memory file
# Optionally rewrite transcript entries as compacted stubs (--compact)
#
# Usage:
#   python lupo-scripts/migrate_transcript_to_memory.py --scan
#   python lupo-scripts/migrate_transcript_to_memory.py --file lupo-channels/0/development/prd_files/44_prd_discussion/transcript.jsonl
#   python lupo-scripts/migrate_transcript_to_memory.py --all
#   python lupo-scripts/migrate_transcript_to_memory.py --all --compact --dry-run
#   python lupo-scripts/migrate_transcript_to_memory.py --all --compact  (auto-backs up first)

from __future__ import annotations

import argparse
import json
import re
import shutil
import subprocess
import sys
from datetime import datetime, timezone
from pathlib import Path

from lib.db_memory_writer import DBMemoryWriter
from lib.string_utils import sanitize_text

PROJECT_ROOT = Path(__file__).resolve().parent.parent
CHANNELS_DIR = PROJECT_ROOT / "lupo-channels"
MEMORY_BASE_DIR = PROJECT_ROOT / "lupo-memory"
BACKUP_DIR = PROJECT_ROOT / "changelog" / "transcript_backups"
TOON_SCHEMA_VERSION = "toon_v1"
ACTOR_ID = 116
AGENT_NAME = "Claude Code"


def now_utc() -> str:
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def ts_to_path_parts(ts: str) -> tuple:
    """'20260409001808.000' -> ('2026', '04')"""
    digits = re.sub(r"[^0-9]", "", ts)[:14]
    if len(digits) >= 6:
        return digits[:4], digits[4:6]
    return "1970", "01"


def generate_memory_slug(channel_key: str, slug: str, ts_digits: str) -> str:
    """
    Deterministic toon ID: M-{channel_key}-{slug_sanitized}-{ts_digits}
    slug sanitization: / -> _, max 60 chars (truncate middle if needed)
    No collisions because ts_digits is unique within a channel.
    """
    sanitized = re.sub(r"[/\\]", "_", slug)
    sanitized = re.sub(r"[^a-zA-Z0-9_-]", "", sanitized).lower()
    if len(sanitized) > 60:
        sanitized = sanitized[:30] + sanitized[-30:]
    ck = re.sub(r"[^a-zA-Z0-9_-]", "", channel_key).lower()
    return f"M-{ck}-{sanitized}-{ts_digits}"


def parse_channel_parts(channel_path: str) -> tuple:
    """
    'lupo-channels/0/development/prd_files/44_prd_discussion' ->
    channel_key='development', slug='prd_files/44_prd_discussion'
    Falls back gracefully if path structure differs.
    """
    parts = Path(channel_path).parts
    # Expected: lupo-channels / 0 / {channel_key} / {slug...}
    try:
        idx = list(parts).index("lupo-channels")
        # parts[idx+2] = channel_key, parts[idx+3:] = slug parts
        channel_key = parts[idx + 2] if len(parts) > idx + 2 else "unknown"
        slug_parts = parts[idx + 3:] if len(parts) > idx + 3 else ()
        slug = "/".join(slug_parts) if slug_parts else "general"
        return channel_key, slug
    except (ValueError, IndexError):
        return "unknown", channel_path.replace("\\", "/").replace("/", "_")


def build_toon_from_entry(entry: dict, channel_path: str, creation_ts: str) -> tuple:
    """
    Build a .toon memory node from a transcript entry.
    Returns (toon_dict, toon_path).
    """
    entry_ts = str(entry.get("ts", creation_ts))
    actor_id = entry.get("actor_id", 0)
    action = sanitize_text(entry.get("action", ""))
    task = entry.get("task", "")

    year, month = ts_to_path_parts(entry_ts)
    ts_digits = re.sub(r"[^0-9]", "", entry_ts)[:17]

    channel_key, slug = parse_channel_parts(channel_path)
    toon_id = generate_memory_slug(channel_key, slug, ts_digits)
    toon_file = MEMORY_BASE_DIR / year / month / f"{toon_id}.toon"

    # Build edges
    edges = [
        {"to": f"CHANNEL:{channel_path}", "type": "belongs_to", "weight": 1.0},
    ]
    if actor_id:
        edges.append({"to": f"ACTOR:{actor_id}", "type": "authored_by", "weight": 1.0})
    if task:
        edges.append({"to": f"TASK:{task}", "type": "task_ref", "weight": 1.0})

    # Truncate action for summary (keep full version in content)
    summary = action[:200] if action else "(no action text)"

    toon = {
        "id": toon_id,
        "type": "transcript_memory",
        "ts": entry_ts if "." in entry_ts else f"{entry_ts}.000",
        "actor_id": actor_id,
        "summary": summary,
        "edges": edges,
        "content": {
            "original_ts": entry_ts,
            "original_actor_id": actor_id,
            "original_action": action,
            "task_ref": task,
            "channel_path": channel_path,
            "compaction_note": "original entry preserved in transcript.jsonl; this is the compacted graph node",
        },
        "schema_version": TOON_SCHEMA_VERSION,
        "status": "draft",
        "migrated_at": creation_ts,
        "migrated_by": {"actor_id": ACTOR_ID, "agent_name": AGENT_NAME},
    }

    return toon, toon_file


def compact_entry(entry: dict, toon_id: str, ts: str) -> dict:
    """Return a compacted transcript entry stub pointing to toon memory."""
    return {
        "ts": entry.get("ts", ts),
        "actor_id": entry.get("actor_id"),
        "memory_id": toon_id,
        "action": "compacted",
        "task": entry.get("task", ""),
    }


def backup_transcript(jsonl_path: Path, creation_ts: str) -> Path:
    """
    Back up transcript.jsonl before any rewriting.
    Location: changelog/transcript_backups/transcript_backup_{slug}_{ts}.jsonl
    """
    BACKUP_DIR.mkdir(parents=True, exist_ok=True)
    slug = jsonl_path.parent.name
    backup_name = f"transcript_backup_{slug}_{creation_ts}.jsonl"
    backup_path = BACKUP_DIR / backup_name
    shutil.copy2(jsonl_path, backup_path)
    print(f"  [BACKUP] {backup_path.relative_to(PROJECT_ROOT)}")
    return backup_path


def export_memory_node(node_id: int) -> bool:
    """Export DB node to filesystem mirror via PHP wrapper."""
    cmd = ["php", "lupo-bin/export.php", "--node-id", str(node_id)]
    result = subprocess.run(cmd, cwd=str(PROJECT_ROOT), capture_output=True, text=True)
    return result.returncode == 0


def process_transcript(
    jsonl_path: Path,
    dry_run: bool,
    force: bool,
    compact: bool,
    creation_ts: str,
    no_fallback: bool,
) -> int:
    """Process one transcript.jsonl. Returns count of DB memory nodes written."""
    channel_path = str(jsonl_path.parent.relative_to(PROJECT_ROOT)).replace("\\", "/")
    lines = jsonl_path.read_text(encoding="utf-8").splitlines()
    writer = DBMemoryWriter(dry_run=dry_run, fallback_to_filesystem=(not no_fallback))

    # Auto-backup BEFORE any rewriting (mandatory when --compact)
    if compact and not dry_run:
        backup_transcript(jsonl_path, creation_ts)

    written = 0
    compacted_lines = []

    try:
        for line in lines:
            line = line.strip()
            if not line:
                compacted_lines.append(line)
                continue
            try:
                entry = json.loads(line)
            except json.JSONDecodeError:
                print(f"  [WARN] Could not parse line: {line[:80]}")
                compacted_lines.append(line)
                continue

            # Skip already-compacted entries
            if entry.get("action") == "compacted" and entry.get("memory_id"):
                compacted_lines.append(line)
                continue

            toon, toon_file = build_toon_from_entry(entry, channel_path, creation_ts)
            source_key = f"{channel_path}:{entry.get('ts', creation_ts)}:{toon.get('id')}"
            node_id = writer.create_memory_node(
                toon,
                source_key=source_key,
                filesystem_path=str(toon_file),
            )
            writer.create_memory_edges(
                node_id,
                toon.get("edges", []),
                provenance_actor_id=toon.get("actor_id"),
                channel_context={"channel_path": channel_path},
            )

            if dry_run:
                print(f"  [DRY-RUN] Would write DB node for: {toon_file.name}")
            else:
                if node_id > 0:
                    exported = export_memory_node(node_id)
                    export_status = "[OK]" if exported else "[WARN]"
                    print(f"  [OK] DB node {node_id} from {toon_file.name} {export_status} export")
                else:
                    print(f"  [WARN] Filesystem fallback write used for {toon_file.name}")
                written += 1

            if compact:
                stub = compact_entry(entry, str(node_id), creation_ts)
                compacted_lines.append(json.dumps(stub))
            else:
                compacted_lines.append(line)
    finally:
        writer.close()

    if compact and not dry_run:
        jsonl_path.write_text("\n".join(compacted_lines) + "\n", encoding="utf-8")
        print(f"  [OK] Compacted transcript: {jsonl_path.relative_to(PROJECT_ROOT)}")

    return written


def find_all_transcripts() -> list:
    return sorted(CHANNELS_DIR.rglob("transcript.jsonl"))


def main() -> None:
    parser = argparse.ArgumentParser(
        description="[DRAFT] Migrate transcript.jsonl entries to .toon memory nodes"
    )
    parser.add_argument("--scan", action="store_true", help="List all transcript.jsonl files found")
    parser.add_argument("--file", metavar="FILE", help="Process a single transcript.jsonl file")
    parser.add_argument("--all", action="store_true", help="Process all transcript.jsonl files in lupo-channels/")
    parser.add_argument("--compact", action="store_true",
                        help="Rewrite transcript entries as compacted stubs (adds memory_id, sets action=compacted)")
    parser.add_argument("--dry-run", action="store_true", help="Show what would be written without writing")
    parser.add_argument("--force", action="store_true", help="Overwrite existing .toon files")
    parser.add_argument("--no-fallback", action="store_true", help="Disable filesystem fallback (fail if DB down)")
    args = parser.parse_args()

    if not any([args.scan, args.file, args.all]):
        parser.print_help()
        sys.exit(1)

    creation_ts = now_utc()

    if args.scan:
        transcripts = find_all_transcripts()
        print(f"Found {len(transcripts)} transcript.jsonl files:")
        for t in transcripts:
            rel = t.relative_to(PROJECT_ROOT)
            lines = t.read_text(encoding="utf-8").splitlines()
            print(f"  {rel}  ({len(lines)} lines)")
        return

    files = []
    if args.file:
        p = Path(args.file)
        if not p.exists():
            sys.exit(f"File not found: {args.file}")
        files.append(p)
    elif args.all:
        files = find_all_transcripts()

    total_written = 0
    for f in files:
        rel = f.relative_to(PROJECT_ROOT)
        print(f"\nProcessing: {rel}")
        n = process_transcript(
            f,
            dry_run=args.dry_run,
            force=args.force,
            compact=args.compact,
            creation_ts=creation_ts,
            no_fallback=args.no_fallback,
        )
        total_written += n

    print(f"\nTotal .toon files written: {total_written}")
    if args.dry_run:
        print("[DRY-RUN] No files were written.")
    if args.compact and not args.dry_run:
        print("[COMPACT] Transcript entries rewritten as stubs.")


if __name__ == "__main__":
    main()

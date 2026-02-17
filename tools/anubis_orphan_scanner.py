#!/usr/bin/env python3
"""
anubis_orphan_scanner.py — ANUBIS orphan scanner and adoption planner.

Input: dialog text (and optional channel_id, dialog_thread_id, actor_id).
Output: classification (orphan/resolved), resolution plan, adoption plan.

Doctrine: docs/doctrine/ANUBIS/. Parameterized SQL only; no schema inference.
Tables: lupo_dialog_messages, lupo_dialog_threads, lupo_dialog_channels,
        lupo_actors, lupo_actor_channels (TOON-defined names).
"""

import argparse
import json
import sys

try:
    import mysql.connector
except ImportError:
    print("Error: mysql-connector-python required. pip install mysql-connector-python", file=sys.stderr)
    sys.exit(1)

TABLE_PREFIX = "lupo_"

# Default DB config (override via env or CLI if needed)
DEFAULT_DB = {
    "host": "localhost",
    "user": "root",
    "password": "ServBay.dev",
    "database": "lupopedia",
}

# ANUBIS default adoption target (channel 42, thread 1, WOLFIE = 3)
DEFAULT_CHANNEL_ID = 42
DEFAULT_THREAD_ID = 1
DEFAULT_ACTOR_ID = 3  # WOLFIE


def resolve_channel_id(cursor, channel_id):
    """Return channel_id if it exists in lupo_dialog_channels (TOON: no is_deleted column)."""
    if channel_id is None:
        return None
    table = TABLE_PREFIX + "dialog_channels"
    sql = "SELECT 1 FROM " + table + " WHERE channel_id = %s LIMIT 1"
    cursor.execute(sql, (channel_id,))
    return channel_id if cursor.fetchone() else None


def resolve_thread_id(cursor, thread_id, channel_id):
    """Return thread_id if it exists in lupo_dialog_threads for the given channel and is_deleted = 0."""
    if thread_id is None:
        return None
    table = TABLE_PREFIX + "dialog_threads"
    sql = "SELECT 1 FROM " + table + " WHERE dialog_thread_id = %s AND channel_id = %s AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1"
    cursor.execute(sql, (thread_id, channel_id))
    return thread_id if cursor.fetchone() else None


def resolve_actor_id(cursor, actor_id):
    """Return actor_id if it exists in lupo_actors and is_deleted = 0."""
    if actor_id is None:
        return None
    table = TABLE_PREFIX + "actors"
    sql = "SELECT 1 FROM " + table + " WHERE actor_id = %s AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1"
    cursor.execute(sql, (actor_id,))
    return actor_id if cursor.fetchone() else None


def next_dialog_message_id(cursor):
    """Return MAX(dialog_message_id) + 1 from lupo_dialog_messages. Parameterized where applicable."""
    table = TABLE_PREFIX + "dialog_messages"
    sql = "SELECT COALESCE(MAX(dialog_message_id), 0) + 1 AS next_id FROM " + table
    cursor.execute(sql)
    row = cursor.fetchone()
    return row[0] if row else 32


def scan(dialog_text, channel_id=None, dialog_thread_id=None, actor_id=None, cursor=None):
    """
    Classify and resolve. Returns dict with:
      classification: 'orphan' | 'resolved'
      channel_id, dialog_thread_id, from_actor_id (resolved or default)
      adoption_plan: { dialog_message_id, channel_id, dialog_thread_id, from_actor_id, message_type }
    """
    result = {
        "classification": "orphan",
        "channel_id": None,
        "dialog_thread_id": None,
        "from_actor_id": None,
        "adoption_plan": None,
    }
    if not dialog_text or not isinstance(dialog_text, str):
        result["adoption_plan"] = {
            "dialog_message_id": 32,
            "channel_id": DEFAULT_CHANNEL_ID,
            "dialog_thread_id": DEFAULT_THREAD_ID,
            "from_actor_id": DEFAULT_ACTOR_ID,
            "message_type": "system",
        }
        return result

    ch_resolved = None
    th_resolved = None
    act_resolved = None

    if cursor:
        ch_resolved = resolve_channel_id(cursor, channel_id)
        if ch_resolved is None and channel_id is not None:
            ch_resolved = None
        elif ch_resolved is None:
            ch_resolved = DEFAULT_CHANNEL_ID  # adopt default

        th_resolved = resolve_thread_id(cursor, dialog_thread_id, ch_resolved if ch_resolved else DEFAULT_CHANNEL_ID)
        if th_resolved is None:
            th_resolved = DEFAULT_THREAD_ID

        act_resolved = resolve_actor_id(cursor, actor_id)
        if act_resolved is None:
            act_resolved = DEFAULT_ACTOR_ID
    else:
        ch_resolved = DEFAULT_CHANNEL_ID
        th_resolved = DEFAULT_THREAD_ID
        act_resolved = DEFAULT_ACTOR_ID if actor_id is None else actor_id

    result["channel_id"] = ch_resolved
    result["dialog_thread_id"] = th_resolved
    result["from_actor_id"] = act_resolved

    if cursor:
        next_id = next_dialog_message_id(cursor)
    else:
        next_id = 32

    result["classification"] = "resolved" if (ch_resolved and th_resolved and act_resolved) else "orphan"
    result["adoption_plan"] = {
        "dialog_message_id": next_id,
        "channel_id": ch_resolved or DEFAULT_CHANNEL_ID,
        "dialog_thread_id": th_resolved or DEFAULT_THREAD_ID,
        "from_actor_id": act_resolved or DEFAULT_ACTOR_ID,
        "message_type": "system",
    }
    return result


def main():
    ap = argparse.ArgumentParser(
        description="ANUBIS orphan scanner: classify dialog text and output resolution/adoption plan."
    )
    ap.add_argument("text", nargs="?", default="", help="Dialog text (orphan fragment)")
    ap.add_argument("--channel", type=int, default=None, help="Optional channel_id to try to resolve")
    ap.add_argument("--thread", type=int, default=None, help="Optional dialog_thread_id to try to resolve")
    ap.add_argument("--actor", type=int, default=None, help="Optional from_actor_id to try to resolve")
    ap.add_argument("--json", action="store_true", help="Output JSON only")
    ap.add_argument("--host", default=DEFAULT_DB["host"], help="MySQL host")
    ap.add_argument("--user", default=DEFAULT_DB["user"], help="MySQL user")
    ap.add_argument("--password", default=DEFAULT_DB["password"], help="MySQL password")
    ap.add_argument("--database", default=DEFAULT_DB["database"], help="MySQL database")
    args = ap.parse_args()

    dialog_text = args.text if args.text else (
        "braH all i like know is if you da kine updated the flipping file on wolfie headers or whatevas like dat "
        "Brah, yeah, I da kine updated da flipping file (FLIPPING_FILE_LEXA_LILITH.md) fo' Wolfie headers an' all dat. "
        "Now stay at v4.0.15, wit' new stuff like universal agent flipping, expanded optional fields, metadata_json storage, and full API spec/security."
    )

    cursor = None
    try:
        conn = mysql.connector.connect(
            host=args.host,
            user=args.user,
            password=args.password,
            database=args.database,
        )
        cursor = conn.cursor(dictionary=False)
    except Exception as e:
        if args.json:
            out = scan(dialog_text, args.channel, args.thread, args.actor, cursor=None)
            print(json.dumps(out, indent=2))
        else:
            print("DB connection failed (using defaults):", str(e)[:200], file=sys.stderr)
            out = scan(dialog_text, args.channel, args.thread, args.actor, cursor=None)
            if args.json:
                print(json.dumps(out, indent=2))
            else:
                print("Classification:", out["classification"])
                print("Adoption plan:", out["adoption_plan"])
        sys.exit(0)

    out = scan(dialog_text, args.channel, args.thread, args.actor, cursor=cursor)
    if cursor:
        cursor.close()
        conn.close()

    if args.json:
        print(json.dumps(out, indent=2))
    else:
        print("Classification:", out["classification"])
        print("Resolved: channel_id=%s dialog_thread_id=%s from_actor_id=%s" % (
            out["channel_id"], out["dialog_thread_id"], out["from_actor_id"]))
        print("Adoption plan:", json.dumps(out["adoption_plan"], indent=2))


if __name__ == "__main__":
    main()

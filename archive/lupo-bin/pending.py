#!/usr/bin/env python3
# lupo-bin/pending.py

import json
import argparse
import sys
import os
import time
from datetime import datetime, timezone, timedelta
from pathlib import Path

_BIN_DIR = os.path.dirname(os.path.abspath(__file__))
_SCRIPTS_DIR = os.path.join(os.path.dirname(_BIN_DIR), 'lupo-scripts')
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.string_utils import sanitize_text

SESSION_PATH = Path('lupo-config/session.json')

def now_utc():
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")

def get_session():
    if SESSION_PATH.exists():
        with open(SESSION_PATH, 'r', encoding='utf-8') as f:
            return json.load(f)
    return {}

def get_channel_dir(node, channel_key, slug):
    slug = str(slug).replace('\\', '/')
    return Path(f'lupo-channels/{node}/{channel_key}/{slug}')

def acquire_lock(lock_path, timeout=5):
    start = time.time()
    while time.time() - start < timeout:
        try:
            os.mkdir(lock_path)
            return True
        except FileExistsError:
            time.sleep(0.1)
    return False

def release_lock(lock_path):
    try:
        os.rmdir(lock_path)
    except OSError:
        pass

def generate_monotonic_ts(transcript_path):
    now_dt = datetime.now(timezone.utc)
    
    if not transcript_path.exists():
        return f"{now_dt.strftime('%Y%m%d%H%M%S')}.000"
        
    try:
        with open(transcript_path, 'r') as f:
            lines = f.readlines()
            if not lines:
                return f"{now_dt.strftime('%Y%m%d%H%M%S')}.000"
            
            last = json.loads(lines[-1])
            last_ts_full = last.get('ts', '')
            if not last_ts_full or '.' not in last_ts_full:
                return f"{now_dt.strftime('%Y%m%d%H%M%S')}.000"
                
            last_ts_str = last_ts_full.split('.')[0]
            last_seq = int(last_ts_full.split('.')[1])
            
            last_dt = datetime.strptime(last_ts_str, '%Y%m%d%H%M%S').replace(tzinfo=timezone.utc)
            
            if now_dt > last_dt:
                return f"{now_dt.strftime('%Y%m%d%H%M%S')}.000"
            else:
                if last_seq >= 999: # sequence overflow guard
                    bumped_dt = last_dt + timedelta(seconds=1)
                    return f"{bumped_dt.strftime('%Y%m%d%H%M%S')}.000"
                else:
                    return f"{last_dt.strftime('%Y%m%d%H%M%S')}.{last_seq + 1:03d}"
    except Exception:
         return f"{now_dt.strftime('%Y%m%d%H%M%S')}.000"

def append_transcript(transcript_path, actor_id, action, task_id=""):
    lock_path = str(transcript_path) + ".lock"
    if not acquire_lock(lock_path):
        print(f"Error: Could not acquire lock for {transcript_path}", file=sys.stderr)
        return
        
    try:
        ts_full = generate_monotonic_ts(transcript_path)
        
        entry = {
            "ts": ts_full,
            "actor_id": int(actor_id) if str(actor_id).isdigit() else actor_id,
            "action": sanitize_text(action)
        }
        if task_id:
            entry["task"] = task_id.strip('[]')
        
        with open(transcript_path, 'a', encoding='utf-8') as f:
            f.write(json.dumps(entry) + '\n')
    finally:
        release_lock(lock_path)

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--from', dest='from_actor', type=str, help='From Actor ID')
    parser.add_argument('--to', type=str, help='To Actor ID')
    parser.add_argument('--actor', type=str, help='Current Actor ID')
    parser.add_argument('--federation_node', type=str, help='Federation node')
    parser.add_argument('--channel_key', type=str, help='Channel key')
    parser.add_argument('--slug', type=str, help='Channel slug')
    parser.add_argument('--task', type=str, help='Task ID')
    parser.add_argument('--message', type=str, help='Message text')
    parser.add_argument('--check', action='store_true', help='Check pending tasks')
    parser.add_argument('--resolve', action='store_true', help='Resolve a task')
    parser.add_argument('--status', action='store_true', help='Check status of tasks')
    parser.add_argument('--id', type=str, help='Task file ID to resolve/claim')
    
    # Concurrency and daemon flags
    parser.add_argument('--instance', type=str, help='Instance ID for claiming tasks')
    parser.add_argument('--claim', action='store_true', help='Claim a specific task using --id and --instance')
    parser.add_argument('--daemon', action='store_true', help='Run as daemon polling for tasks')
    parser.add_argument('--poll-interval', type=int, default=5, help='Polling interval in seconds')

    args = parser.parse_args()

    session = get_session()
    node = args.federation_node if args.federation_node is not None else session.get('active_federation_node', 0)
    channel_key = args.channel_key if args.channel_key else session.get('active_channel_key', '')
    slug = args.slug if args.slug else session.get('active_slug', '')

    if node is None or not channel_key or not slug:
        print("Error: Must provide --federation_node, --channel_key, and --slug, or have them set in session.json", file=sys.stderr)
        sys.exit(1)

    channel_dir = get_channel_dir(node, channel_key, slug)
    tasks_dir = channel_dir / 'tasks'
    tasks_dir.mkdir(parents=True, exist_ok=True)
    transcript_path = channel_dir / 'transcript.jsonl'

    if args.message and args.to and args.from_actor:
        ts = now_utc()
        filename = f"{args.to}_{ts}_{args.from_actor}.json"
        sanitized_message = sanitize_text(args.message)
        
        data = {
            "from_actor_id": args.from_actor,
            "to_actor_id": args.to,
            "ts": ts,
            "task": args.task or "",
            "message": sanitized_message,
            "status": "pending",
            "claimed_by": None,
            "claimed_at": None,
            "resolved_at": None
        }
        
        filepath = tasks_dir / filename
        with open(filepath, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2)
            
        print(f"[OK] Message sent. Created {filepath}")
        
        short_msg = sanitized_message[:200] + ("..." if len(sanitized_message) > 200 else "")
        append_transcript(transcript_path, args.from_actor, f"{args.from_actor} sent task to {args.to} on {channel_key}: {short_msg}", args.task)
        return

    if args.daemon:
        if not args.actor:
            sys.exit("Error: Must provide --actor for daemon mode.")
            
        print(f"[DEPLOY] Starting daemon for Actor {args.actor} on {node}/{channel_key}/{slug} (polling every {args.poll_interval}s)...")
        while True:
            for fpath in tasks_dir.glob(f"{args.actor}_*.json"):
                try:
                    with open(fpath, 'r', encoding='utf-8') as f:
                        data = json.load(f)
                    if data.get('status') == 'pending':
                        # Ignore self-created tasks
                        if str(data.get('from_actor_id')) == str(args.actor):
                            continue
                            
                        instance = args.instance if args.instance else f"daemon-{args.actor}"
                        data['status'] = 'claimed'
                        data['claimed_by'] = instance
                        data['claimed_at'] = now_utc()
                        
                        with open(fpath, 'w', encoding='utf-8') as f:
                            json.dump(data, f, indent=2)
                            
                        print(f"[TASK] Task claimed: {fpath.name}")
                        print(f"   Message: {data.get('message')}")
                        print(f"   Action: Open task in your IDE")
                except Exception:
                    pass
            time.sleep(args.poll_interval)

    if args.claim:
        if not args.id or not args.instance:
            sys.exit("Error: Must provide --id and --instance to claim a task.")
            
        filepath = tasks_dir / args.id
        if filepath.exists():
            with open(filepath, 'r', encoding='utf-8') as f:
                data = json.load(f)
            if data.get('status') == 'pending':
                data['status'] = 'claimed'
                data['claimed_by'] = args.instance
                data['claimed_at'] = now_utc()
                with open(filepath, 'w', encoding='utf-8') as f:
                    json.dump(data, f, indent=2)
                print(f"[OK] Claimed {args.id} for {args.instance}")
                if args.actor:
                     append_transcript(transcript_path, args.actor, f"{args.actor} ({args.instance}) claimed task {args.id}")
            else:
                print(f"Error: Task {args.id} is already {data.get('status')}.", file=sys.stderr)
        else:
            print(f"Error: {args.id} not found.", file=sys.stderr)
        return

    if args.check:
        if not args.actor:
            sys.exit("Error: Must provide --actor to check tasks.")
            
        found = False
        print(f"[TASKS] Pending tasks for Actor {args.actor} in {node}/{channel_key}/{slug}:")
        for fpath in tasks_dir.glob(f"{args.actor}_*.json"):
            with open(fpath, 'r', encoding='utf-8') as f:
                data = json.load(f)
            if data.get('status') == 'pending':
                if str(data.get('from_actor_id')) == str(args.actor):
                    continue
                found = True
                safe_message = sanitize_text(data.get('message', ''))
                print(f"  - From {data.get('from_actor_id')}: \"{safe_message}\" (file: {fpath.name})")
        if not found:
            print("  (No pending tasks)")
        return

    if args.status:
        if not args.actor:
            sys.exit("Error: Must provide --actor for status mode.")
            
        print(f"Tasks for Actor {args.actor}:")
        for fpath in tasks_dir.glob(f"{args.actor}_*.json"):
            with open(fpath, 'r', encoding='utf-8') as f:
                data = json.load(f)
            st = data.get('status', 'unknown')
            msg = sanitize_text(data.get('message', ''))
            if st == 'claimed':
                print(f"  [{st}] {fpath.name} - \"{msg}\" (claimed by {data.get('claimed_by')} at {data.get('claimed_at')})")
            elif st == 'resolved':
                print(f"  [{st}] {fpath.name} - \"{msg}\" (resolved at {data.get('resolved_at')})")
            else:
                print(f"  [{st}] {fpath.name} - \"{msg}\"")
        return

    if args.resolve:
        if not args.id:
            sys.exit("Error: Must provide --id to resolve.")
            
        filepath = tasks_dir / args.id
        if filepath.exists():
            with open(filepath, 'r', encoding='utf-8') as f:
                data = json.load(f)
            data['status'] = 'resolved'
            data['resolved_at'] = now_utc()
            with open(filepath, 'w', encoding='utf-8') as f:
                json.dump(data, f, indent=2)
            print(f"[OK] Resolved {args.id}")
            
            if args.actor:
                append_transcript(transcript_path, args.actor, f"{args.actor} resolved task {args.id}")
        else:
            print(f"Error: {args.id} not found.", file=sys.stderr)
            sys.exit(1)
        return

    print("Use --check, --status, --daemon, --claim, --resolve, or --message.")

if __name__ == "__main__":
    main()

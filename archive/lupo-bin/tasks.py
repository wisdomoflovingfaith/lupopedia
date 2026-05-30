#!/usr/bin/env python3
# lupo-bin/tasks.py

import json
import argparse
import sys
from datetime import datetime, timezone
from pathlib import Path

SESSION_PATH = Path('lupo-config/session.json')

def now_utc():
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")

def get_session():
    if SESSION_PATH.exists():
        with open(SESSION_PATH, 'r') as f:
            return json.load(f)
    return {}

def get_channel_dir(node, slug):
    # Ensure forward slashes in slug
    slug = str(slug).replace('\\', '/')
    return Path(f'lupo-channels/{node}/{slug}')

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--from', dest='from_actor', type=str, help='From Actor ID')
    parser.add_argument('--to', type=str, help='To Actor ID')
    parser.add_argument('--actor', type=str, help='Current Actor ID')
    parser.add_argument('--federation_node', type=str, help='Federation node')
    parser.add_argument('--slug', type=str, help='Channel slug')
    parser.add_argument('--task', type=str, help='Task ID')
    parser.add_argument('--message', type=str, help='Message text')
    parser.add_argument('--check', action='store_true', help='Check pending tasks')
    parser.add_argument('--resolve', action='store_true', help='Resolve a task')
    parser.add_argument('--id', type=str, help='Task file ID to resolve')

    args = parser.parse_args()

    # Load defaults from session
    session = get_session()
    node = args.federation_node if args.federation_node is not None else session.get('active_federation_node', 0)
    slug = args.slug if args.slug else session.get('active_slug', '')

    if node is None or not slug:
        print("Error: Must provide --federation_node and --slug, or have them set in session.json", file=sys.stderr)
        sys.exit(1)

    channel_dir = get_channel_dir(node, slug)
    tasks_dir = channel_dir / 'tasks'
    tasks_dir.mkdir(parents=True, exist_ok=True)

    if args.message and args.to and args.from_actor:
        ts = now_utc()
        filename = f"{args.to}_{ts}_{args.from_actor}.json"
        
        data = {
            "from_actor_id": args.from_actor,
            "to_actor_id": args.to,
            "ts": ts,
            "task": args.task or "",
            "message": args.message,
            "status": "pending"
        }
        
        filepath = tasks_dir / filename
        with open(filepath, 'w') as f:
            json.dump(data, f, indent=2)
            
        print(f"✅ Message sent. Created {filepath}")
        return

    if args.check:
        if not args.actor:
            print("Error: Must provide --actor to check tasks.", file=sys.stderr)
            sys.exit(1)
            
        found = False
        print(f"📬 Pending tasks for Actor {args.actor} in {node}/{slug}:")
        for fpath in tasks_dir.glob(f"{args.actor}_*.json"):
            with open(fpath, 'r') as f:
                data = json.load(f)
            if data.get('status') == 'pending':
                found = True
                print(f"  - From {data.get('from_actor_id')}: \"{data.get('message')}\" (file: {fpath.name})")
        if not found:
            print("  (No pending tasks)")
        return

    if args.resolve:
        if not args.id:
            print("Error: Must provide --id to resolve.", file=sys.stderr)
            sys.exit(1)
            
        filepath = tasks_dir / args.id
        if filepath.exists():
            with open(filepath, 'r') as f:
                data = json.load(f)
            data['status'] = 'resolved'
            data['resolved_at'] = now_utc()
            with open(filepath, 'w') as f:
                json.dump(data, f, indent=2)
            print(f"✅ Resolved {args.id}")
        else:
            print(f"Error: {args.id} not found.", file=sys.stderr)
            sys.exit(1)
        return

    print("Use --check, --resolve, or --message. See code for arguments.")

if __name__ == "__main__":
    main()

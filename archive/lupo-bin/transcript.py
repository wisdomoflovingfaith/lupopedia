#!/usr/bin/env python3
# lupo-bin/transcript.py — PRD 16 §9: DB-first POST, optional legacy jsonl, offline queue §9.1.1

import json
import argparse
import sys
import os
import time
import ssl
from datetime import datetime, timezone, timedelta
from pathlib import Path
from urllib.error import HTTPError, URLError
from urllib.request import Request, urlopen

_BIN_DIR = os.path.dirname(os.path.abspath(__file__))
_SCRIPTS_DIR = os.path.join(os.path.dirname(_BIN_DIR), 'lupo-scripts')
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.string_utils import sanitize_text

SESSION_PATH = Path('lupo-config/session.json')
OFFLINE_QUEUE = Path('lupo-config/offline_transcript_queue.jsonl')


def post_transcript_append(base_url, payload, api_token=None, insecure_tls=False):
    """
    POST JSON to index.php?route=api/transcript/append (PRD 16 §9.1).
    Returns parsed JSON dict.
    """
    root = base_url.rstrip('/')
    url = root + '/index.php?route=api/transcript/append'
    body = json.dumps(payload, ensure_ascii=False).encode('utf-8')
    req = Request(url, data=body, method='POST')
    req.add_header('Content-Type', 'application/json; charset=utf-8')
    if api_token:
        req.add_header('X-Lupo-Api-Token', api_token)
    ctx = None
    if insecure_tls:
        ctx = ssl._create_unverified_context()
    with urlopen(req, timeout=60, context=ctx) as resp:
        raw = resp.read().decode('utf-8')
    return json.loads(raw)


def append_offline_queue(obj):
    """Single-writer queue file (§9.1.1)."""
    line = json.dumps(obj, ensure_ascii=False) + '\n'
    OFFLINE_QUEUE.parent.mkdir(parents=True, exist_ok=True)
    with open(OFFLINE_QUEUE, 'a', encoding='utf-8') as f:
        f.write(line)


def flush_offline_queue(base_url, api_token=None, dry_run=False, insecure_tls=False):
    if not OFFLINE_QUEUE.is_file():
        print('[OK] No offline queue at %s' % OFFLINE_QUEUE)
        return 0
    all_lines = OFFLINE_QUEUE.read_text(encoding='utf-8').splitlines()
    kept = []
    posted = 0
    for i, line in enumerate(all_lines):
        stripped = line.strip()
        if not stripped:
            continue
        try:
            obj = json.loads(stripped)
        except json.JSONDecodeError as e:
            print('[ERROR] Queue line %s invalid JSON: %s' % (i, e))
            kept.append(stripped)
            continue
        if dry_run:
            print('[DRY-RUN] Would POST: %s' % stripped[:200])
            kept.append(stripped)
            continue
        try:
            post_transcript_append(base_url, obj, api_token=api_token, insecure_tls=insecure_tls)
            posted += 1
        except (HTTPError, URLError, OSError, ValueError) as e:
            print('[WARN] POST failed at queue line %s: %s — keeping remainder' % (i, e))
            kept.append(stripped)
            for j in range(i + 1, len(all_lines)):
                if all_lines[j].strip():
                    kept.append(all_lines[j].strip())
            break
    if not dry_run:
        OFFLINE_QUEUE.write_text('\n'.join(kept) + ('\n' if kept else ''), encoding='utf-8')
    print('[OK] Flushed %s line(s); %s remaining in queue' % (posted, len(kept)))
    return posted

def now_utc():
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")

def get_session():
    if SESSION_PATH.exists():
        with open(SESSION_PATH, 'r') as f:
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

def main():
    parser = argparse.ArgumentParser(
        description='Transcript: §9 DB-first POST, legacy jsonl, or offline queue (PRD 16)'
    )
    parser.add_argument('--actor', type=str, help='from_actor_id (required except --flush-offline-queue)')
    parser.add_argument(
        '--action',
        type=str,
        help='Message body for DB row / legacy action field',
    )
    parser.add_argument('--task', type=str, help='Optional task id / label')
    parser.add_argument('--instance', type=str, help='Instance ID (legacy jsonl only)')
    parser.add_argument('--federation_node', type=str, help='Federation node')
    parser.add_argument('--channel_key', type=str, help='Channel key')
    parser.add_argument('--slug', type=str, help='Thread slug (third segment); legacy jsonl path')
    parser.add_argument(
        '--dialog-transcript',
        dest='dialog_transcript',
        type=str,
        help='DB slug {node}/{channel_key}/{thread_slug} (required with --db-first)',
    )
    parser.add_argument(
        '--db-first',
        action='store_true',
        help='POST to PHP api/transcript/append instead of transcript.jsonl',
    )
    parser.add_argument(
        '--base-url',
        type=str,
        default=os.environ.get('LUPOPEDIA_E2E_BASE_URL', ''),
        help='Site root URL (e.g. https://host/lupopedia)',
    )
    parser.add_argument(
        '--api-token',
        type=str,
        default=os.environ.get('LUPO_TRANSCRIPT_API_TOKEN', ''),
        help='Optional X-Lupo-Api-Token value',
    )
    parser.add_argument(
        '--queue-on-failure',
        action='store_true',
        help='With --db-first: append to lupo-config/offline_transcript_queue.jsonl if POST fails',
    )
    parser.add_argument(
        '--flush-offline-queue',
        action='store_true',
        help='POST each line from offline queue then truncate remaining (needs --base-url)',
    )
    parser.add_argument(
        '--insecure-tls',
        action='store_true',
        help='Disable TLS certificate verification (local dev only)',
    )
    parser.add_argument('--dry-run', action='store_true', help='Print actions without writing or POSTing')
    args = parser.parse_args()

    if args.flush_offline_queue:
        if not args.base_url:
            sys.exit('Error: --flush-offline-queue requires --base-url (or LUPOPEDIA_E2E_BASE_URL)')
        flush_offline_queue(
            args.base_url,
            api_token=args.api_token or None,
            dry_run=args.dry_run,
            insecure_tls=args.insecure_tls,
        )
        return

    if not args.actor or not args.action:
        sys.exit('Error: --actor and --action are required (unless using --flush-offline-queue)')

    session = get_session()
    node = args.federation_node if args.federation_node is not None else session.get('active_federation_node', 0)
    channel_key = args.channel_key if args.channel_key else session.get('active_channel_key', '')
    slug = args.slug if args.slug else session.get('active_slug', '')

    if args.db_first:
        if not args.base_url:
            sys.exit('Error: --db-first requires --base-url (or LUPOPEDIA_E2E_BASE_URL)')
        if not args.dialog_transcript or not str(args.dialog_transcript).strip():
            sys.exit('Error: --db-first requires --dialog-transcript (§4.2 field 19 slug)')
        if not channel_key:
            sys.exit('Error: --db-first requires --channel_key (or session.json active_channel_key)')
        created = int(now_utc())
        payload = {
            'channel_key': channel_key,
            'message': sanitize_text(args.action),
            'from_actor_id': int(args.actor) if str(args.actor).isdigit() else 0,
            'dialog_transcript': str(args.dialog_transcript).strip(),
            'created_ymdhis': created,
        }
        if args.task:
            payload['task'] = args.task.strip('[]')
        if args.dry_run:
            print('[DRY-RUN] POST %s/index.php?route=api/transcript/append' % args.base_url.rstrip('/'))
            print(json.dumps(payload, indent=2))
            return
        try:
            out = post_transcript_append(
                args.base_url,
                payload,
                api_token=args.api_token or None,
                insecure_tls=args.insecure_tls,
            )
            print('[OK] %s' % json.dumps(out))
        except (HTTPError, URLError, OSError, ValueError) as e:
            if args.queue_on_failure:
                qobj = dict(payload)
                qobj['queued_ymdhis'] = int(now_utc())
                append_offline_queue(qobj)
                print('[WARN] POST failed (%s); queued offline' % e)
            else:
                sys.exit('Error: POST failed: %s' % e)
        return

    if node is None or not channel_key or not slug:
        sys.exit(
            'Error: Must provide --federation_node, --channel_key, and --slug, '
            'or have them set in session.json (legacy jsonl mode)'
        )

    channel_dir = get_channel_dir(node, channel_key, slug)
    transcript_path = channel_dir / 'transcript.jsonl'

    if args.dry_run:
        print(f"[DRY-RUN] Target Path: {transcript_path}")
    else:
        channel_dir.mkdir(parents=True, exist_ok=True)

    entry = {
        "ts": "<pending-lock>",
        "actor_id": int(args.actor) if str(args.actor).isdigit() else args.actor,
    }

    if args.instance:
        entry["instance"] = args.instance

    if args.task:
        entry["task"] = args.task.strip('[]')

    entry["action"] = sanitize_text(args.action)

    if args.dry_run:
        entry["ts"] = generate_monotonic_ts(transcript_path)
        print(f"[DRY-RUN] Will append:\n{json.dumps(entry)}")
        return

    lock_path = str(transcript_path) + ".lock"
    if not acquire_lock(lock_path):
        sys.exit(f"Error: Could not acquire lock for {transcript_path}")

    try:
        ts_full = generate_monotonic_ts(transcript_path)
        entry["ts"] = ts_full

        with open(transcript_path, 'a') as f:
            f.write(json.dumps(entry) + '\n')

        if SESSION_PATH.exists():
            with open(SESSION_PATH, 'r') as f:
                sess_data = json.load(f)
            if 'timestamp' not in sess_data:
                sess_data['timestamp'] = {}
            sess_data['timestamp']['current'] = ts_full.split('.')[0]
            with open(SESSION_PATH, 'w') as f:
                json.dump(sess_data, f, indent=2)

        print(f"[OK] Logged to {transcript_path}")
    finally:
        release_lock(lock_path)

if __name__ == "__main__":
    main()

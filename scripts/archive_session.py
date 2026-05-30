#!/usr/bin/env python3
# scripts/archive_session.py

import json
import shutil
from datetime import datetime, timezone
from pathlib import Path

SESSION_PATH = Path('config/session.json')
TRANSCRIPT_PATH = Path('config/transcript.jsonl')
ARCHIVE_DIR = Path('changelog/sessions')

def now_utc():
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")

def main():
    with open(SESSION_PATH, 'r') as f:
        session = json.load(f)
    
    session_id = session['timestamp']['session_started']
    archive_file = ARCHIVE_DIR / f"session_{session_id}.jsonl"
    
    ARCHIVE_DIR.mkdir(parents=True, exist_ok=True)
    
    # Archive transcript
    if TRANSCRIPT_PATH.exists():
        shutil.copy(TRANSCRIPT_PATH, archive_file)
        print(f"✅ Archived to {archive_file}")
    
    # Reset session
    session['timestamp']['current'] = now_utc()
    session['timestamp']['session_started'] = now_utc()
    session['status'] = 'archived'
    
    with open(SESSION_PATH, 'w') as f:
        json.dump(session, f, indent=2)
    
    # Clear transcript
    with open(TRANSCRIPT_PATH, 'w') as f:
        f.write("")
    
    print(f"✅ Session reset. New session started at {session['timestamp']['session_started']}")

if __name__ == "__main__":
    main()

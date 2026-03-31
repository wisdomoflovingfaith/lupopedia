#!/usr/bin/env python3
"""
lupo-bin/tick.py — Temporal Anchor Updater

Updates lupo-bin/temporal_anchor.json with the current UTC time in YYYYMMDDHHMMSS format.
Also updates root CURRENT_UTC file with the current UTC timestamp.
This script is called by the IDE after every session or major write to ensure all lupopedia.headers timestamps are synchronized and real.
"""
import json
import os
from datetime import datetime, timezone

ANCHOR_PATH = os.path.join(os.path.dirname(__file__), 'temporal_anchor.json')
CURRENT_UTC_PATH = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'CURRENT_UTC')

now_utc = datetime.now(timezone.utc)
current_utc = now_utc.strftime('%Y%m%d%H%M%S')

# Load previous anchor if exists
if os.path.exists(ANCHOR_PATH):
    with open(ANCHOR_PATH, 'r') as f:
        try:
            anchor = json.load(f)
        except Exception:
            anchor = {}
else:
    anchor = {}

last_session_end = anchor.get('current_utc', current_utc)
system_year = str(now_utc.year)

anchor_update = {
    "current_utc": current_utc,
    "last_session_end": last_session_end,
    "system_year": system_year,
    "format_standard": "YYYYMMDDHHMMSS"
}

with open(ANCHOR_PATH, 'w') as f:
    json.dump(anchor_update, f, indent=2)

# Also update root CURRENT_UTC file
with open(CURRENT_UTC_PATH, 'w') as f:
    f.write(current_utc)

print(f"Temporal anchor updated: {anchor_update}")
print(f"Root CURRENT_UTC updated: {current_utc}")

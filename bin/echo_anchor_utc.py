#!/usr/bin/env python3
"""
Print current_utc from bin/temporal_anchor.json without updating the clock.

Use after you have already run tick.py in this session: reuse the same BIGINT for
multiple file headers. If the anchor is missing, run: python bin/tick.py

Per TIMESTAMP doctrine: never invent or guess timestamps; anchor is set only by tick.py.
"""
from __future__ import print_function

import json
import os
import sys

ANCHOR_PATH = os.path.join(os.path.dirname(__file__), 'temporal_anchor.json')

if not os.path.exists(ANCHOR_PATH):
    sys.stderr.write(
        "temporal_anchor.json missing. Run: python bin/tick.py\n"
    )
    sys.exit(1)

with open(ANCHOR_PATH, 'r') as f:
    anchor = json.load(f)

utc = anchor.get('current_utc')
if not utc or len(str(utc)) != 14:
    sys.stderr.write("temporal_anchor.json invalid current_utc. Run: python bin/tick.py\n")
    sys.exit(1)

print(utc)
sys.exit(0)

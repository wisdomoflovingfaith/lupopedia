#!/usr/bin/env python3
import json
import glob
import os

ROOT = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), "agents")
rows = []
for path in glob.glob(os.path.join(ROOT, "*", "agent.json")):
    with open(path, encoding="utf-8") as f:
        d = json.load(f)
    role = (d.get("role") or d.get("description") or "").replace("\n", " ").strip()
    if len(role) > 100:
        role = role[:97] + "..."
    rows.append((int(d.get("agent_id", 0)), d.get("agent_key", ""), d.get("name", ""), role))
rows.sort(key=lambda x: x[0])
for aid, key, name, role in rows:
    print("%4d  %-30s  %s" % (aid, key, role))
print("TOTAL", len(rows))

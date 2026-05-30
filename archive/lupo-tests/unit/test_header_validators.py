#!/usr/bin/env python3
"""Quick test for Tier 2 and Tier 3 validators in generate_headers_from_db.py"""
import sys, os
sys.path.insert(0, os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..', 'lupo-scripts'))
import generate_headers_from_db as g

PASS = 0
FAIL = 0

def check(label, got, expected_contains_substr):
    global PASS, FAIL
    got_str = str(got)
    if expected_contains_substr in got_str:
        print(f"  PASS  {label}")
        PASS += 1
    else:
        print(f"  FAIL  {label}")
        print(f"        got:      {got_str!r}")
        print(f"        expected: {expected_contains_substr!r}")
        FAIL += 1

def check_empty(label, got):
    global PASS, FAIL
    if got == []:
        print(f"  PASS  {label}")
        PASS += 1
    else:
        print(f"  FAIL  {label} — expected [] got {got!r}")
        FAIL += 1

print("=== Tier 2: semantic range ===")
# NOTE: last_modified_utc was renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6.
# questions_toon is NOT a timestamp. Ordering rule (when_updated <= last_modified_utc) removed.
# Tests updated to use when_updated only for timestamp range checks.

# Pre-2000 floor on when_updated
res = g.validate_timestamp_semantic_range({'when_updated': '19990101000000'})
check("floor violation detected (when_updated)", res, "predates project floor")

# Legacy last_modified_utc: still validated as timestamp for backward compat (Phase 2 only)
res = g.validate_timestamp_semantic_range({'last_modified_utc': '20991231235959'})
check("legacy last_modified_utc future timestamp detected", res, "in the future")

# Ordering violation test removed: when_updated vs last_modified_utc ordering no longer enforced.
# (The concept was: when_updated <= last_modified_utc; now questions_toon is not a timestamp.)

# Valid when_updated — no errors
res = g.validate_timestamp_semantic_range({'when_updated': '20260320000000'})
check_empty("valid when_updated — no issues", res)

print()
print("=== Tier 3: role-integrity ===")
# Valid match
res = g.validate_role_integrity({'actor_id': 102, 'actor_name': 'cursor'})
check_empty("valid actor_id+name — no issues", res)

# Name mismatch
res = g.validate_role_integrity({'actor_id': 1, 'actor_name': 'wrongname'})
check("mismatch detected", res, "does not match canonical slug")

# Unknown actor_id
res = g.validate_role_integrity({'actor_id': 99999, 'actor_name': 'nobody'})
check("unknown actor_id detected", res, "not present in the actor registry")

# No actor_id — skip
res = g.validate_role_integrity({'actor_name': 'cursor'})
check_empty("no actor_id — skipped", res)

# Invalid actor_id type
res = g.validate_role_integrity({'actor_id': 'notanint', 'actor_name': 'x'})
check("non-integer actor_id detected", res, "not a valid integer")

print()
print(f"Results: {PASS} passed, {FAIL} failed")
sys.exit(0 if FAIL == 0 else 1)

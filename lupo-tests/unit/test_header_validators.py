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
# Pre-2000 floor
res = g.validate_timestamp_semantic_range({'when_updated': '19990101000000', 'last_modified_utc': '20260324120000'})
check("floor violation detected", res, "predates project floor")

# Future timestamp
res = g.validate_timestamp_semantic_range({'last_modified_utc': '20991231235959'})
check("future timestamp detected", res, "in the future")

# Ordering violation
res = g.validate_timestamp_semantic_range({'when_updated': '20260325000000', 'last_modified_utc': '20260324000000'})
check("ordering violation detected", res, "must not exceed")

# Valid timestamps — no errors
res = g.validate_timestamp_semantic_range({'when_updated': '20260320000000', 'last_modified_utc': '20260324120000'})
check_empty("valid timestamps — no issues", res)

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

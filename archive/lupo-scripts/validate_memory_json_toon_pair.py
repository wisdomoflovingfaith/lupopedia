#!/usr/bin/env python3
"""
Validate a memory JSON/TOON pair stays in sync.

Default target:
  lupo-memory/constitutional/seed/prd-00-constitutional
Checks:
  - <base>.json exists
  - <base>.toon exists
  - table row counts match between JSON arrays and TOON headers
  - pk sets match for sections/rules/definitions/constraints/metadata
"""

import argparse
import csv
import hashlib
import io
import json
import os
import re
import sys


TOON_HEADER_RE = re.compile(r"^([a-z_]+)\[(\d+)\]\{([^}]*)\}:$")
TOON_CHECKSUM_RE = re.compile(r"^# JSON master checksum:\s*([a-f0-9]{32})\s*$")

EXPECTED_TABLES_CONSTITUTIONAL = (
    "constitutional_sections",
    "constitutional_rules",
    "definitions",
    "constraints",
    "metadata",
)


def parse_toon_tables(toon_text):
    tables = {}
    current = None
    checksum = None
    for raw in toon_text.splitlines():
        line = raw.strip()
        if not line:
            continue
        cm = TOON_CHECKSUM_RE.match(line)
        if cm:
            checksum = cm.group(1)
            continue
        if line.startswith("#"):
            continue
        m = TOON_HEADER_RE.match(line)
        if m:
            current = m.group(1)
            declared = int(m.group(2))
            cols = [c.strip() for c in m.group(3).split(",") if c.strip()]
            tables[current] = {"declared": declared, "cols": cols, "rows": []}
            continue
        if current is not None:
            tables[current]["rows"].append(line)
    return tables, checksum


def row_pk_set(rows):
    pks = set()
    for row in rows:
        reader = csv.reader(io.StringIO(row))
        try:
            tokens = next(reader)
        except StopIteration:
            continue
        if not tokens:
            continue
        first = tokens[0].strip()
        if first.isdigit():
            # Seed-tier integer PKs
            iv = int(first)
            if 0 <= iv <= 999999:
                pks.add(iv)
            # Canonical/staging long numeric PKs preserved as string
            elif len(first) == 18 and first[0] in ("1", "2"):
                pks.add(first)
    return pks


def json_pk_set(objs):
    out = set()
    for item in objs:
        pk = item.get("pk")
        if isinstance(pk, int):
            out.add(pk)
        elif isinstance(pk, str) and pk.isdigit() and len(pk) == 18 and pk[0] in ("1", "2"):
            out.add(pk)
    return out


def json_columns(rows):
    if not rows:
        return []
    first = rows[0]
    if not isinstance(first, dict):
        return []
    cols = list(first.keys())
    # Match json_to_toon.py deterministic ordering: pk first, then alpha.
    if "pk" in cols:
        return ["pk"] + sorted([c for c in cols if c != "pk"])
    return sorted(cols)


def main():
    parser = argparse.ArgumentParser(description="Validate JSON/TOON pair consistency.")
    parser.add_argument(
        "--base",
        default="lupo-memory/constitutional/seed/prd-00-constitutional",
        help="Base path without extension",
    )
    parser.add_argument(
        "--expected-tables",
        nargs="+",
        help="Optional expected table names to enforce for this pair",
    )
    args = parser.parse_args()

    base = args.base
    json_path = base + ".json"
    toon_path = base + ".toon"

    errors = []

    if not os.path.exists(json_path):
        errors.append("Missing JSON file: %s" % json_path)
    if not os.path.exists(toon_path):
        errors.append("Missing TOON file: %s" % toon_path)
    if errors:
        for e in errors:
            print("[ERROR] %s" % e)
        return 1

    with open(json_path, "r", encoding="utf-8") as f:
        data = json.load(f)
    with open(toon_path, "r", encoding="utf-8") as f:
        toon_text = f.read()
    tables, toon_checksum = parse_toon_tables(toon_text)

    if not isinstance(data, dict):
        print("[ERROR] Root JSON must be an object of tables")
        return 1

    # Validate checksum if present in TOON header comments.
    with open(json_path, "rb") as f:
        current_md5 = hashlib.md5(f.read()).hexdigest()
    if toon_checksum and toon_checksum != current_md5:
        errors.append(
            "JSON checksum mismatch - TOON may be stale: toon=%s json=%s"
            % (toon_checksum, current_md5)
        )

    expected_tables = ()
    if args.expected_tables:
        expected_tables = tuple(args.expected_tables)
    elif os.path.basename(json_path) == "prd-00-constitutional.json":
        expected_tables = EXPECTED_TABLES_CONSTITUTIONAL
    for t in expected_tables:
            if t not in data:
                errors.append("Missing expected JSON table: %s" % t)

    json_tables = []
    for k, v in data.items():
        if not isinstance(v, list):
            errors.append("JSON key %s is not a list (type: %s)" % (k, type(v).__name__))
            continue
        json_tables.append(k)
    toon_tables = set(tables.keys())

    for json_key in json_tables:
        toon_key = json_key
        json_rows = data.get(json_key, [])
        if not isinstance(json_rows, list):
            errors.append("JSON key %s is not a list" % json_key)
            continue
        if toon_key not in tables:
            errors.append("TOON table missing: %s" % toon_key)
            continue

        # Column order must match JSON first-row key order used by converter.
        expected_cols = json_columns(json_rows)
        actual_cols = tables[toon_key]["cols"]
        if expected_cols and actual_cols != expected_cols:
            errors.append(
                "Column order mismatch for %s: json=%s toon=%s"
                % (json_key, expected_cols, actual_cols)
            )

        actual_toon_rows = tables[toon_key]["rows"]
        declared_toon_rows = tables[toon_key]["declared"]
        if declared_toon_rows != len(actual_toon_rows):
            errors.append(
                "TOON row count mismatch for %s: declared=%d actual=%d"
                % (json_key, declared_toon_rows, len(actual_toon_rows))
            )

        if len(json_rows) != len(actual_toon_rows):
            errors.append(
                "JSON/TOON row count mismatch for %s: json=%d toon=%d"
                % (json_key, len(json_rows), len(actual_toon_rows))
            )

        jp = json_pk_set(json_rows)
        tp = row_pk_set(actual_toon_rows)
        if jp and tp and jp != tp:
            missing_in_toon = sorted(jp - tp, key=lambda v: str(v))
            extra_in_toon = sorted(tp - jp, key=lambda v: str(v))
            errors.append(
                "PK set mismatch for %s: json=%s toon=%s | missing_in_toon=%s extra_in_toon=%s"
                % (json_key, sorted(jp, key=lambda v: str(v)), sorted(tp, key=lambda v: str(v)), missing_in_toon, extra_in_toon)
            )

    # Warn on extra TOON tables not present in JSON lists.
    for toon_key in sorted(toon_tables):
        if toon_key not in json_tables:
            errors.append("TOON has extra table not in JSON: %s" % toon_key)

    if errors:
        for e in errors:
            print("[ERROR] %s" % e)
        return 1

    print("[OK] JSON/TOON pair is in sync: %s" % base)
    return 0


if __name__ == "__main__":
    sys.exit(main())


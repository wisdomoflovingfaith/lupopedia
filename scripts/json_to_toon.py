#!/usr/bin/env python3
"""
Deterministic JSON -> TOON converter for memory pair artifacts.

Expected JSON shape:
{
  "table_name": [ { ...row... }, { ...row... } ],
  ...
}

TOON output shape:
table_name[row_count]{col1,col2,...}:
  v11,v12,...
  v21,v22,...
"""

import argparse
import hashlib
import json
import os
import sys
from datetime import datetime, timezone


EXPECTED_TABLES_CONSTITUTIONAL = (
    "constitutional_sections",
    "constitutional_rules",
    "definitions",
    "constraints",
    "metadata",
)


def scalar_to_toon(value):
    if value is None:
        return "null"
    if isinstance(value, bool):
        return "true" if value else "false"
    if isinstance(value, (int, float)):
        return str(value)
    if isinstance(value, (dict, list)):
        # Serialize nested values deterministically as compact JSON string.
        s = json.dumps(value, ensure_ascii=True, separators=(",", ":"), sort_keys=True)
    else:
        s = str(value)

    needs_quotes = any(c in s for c in [",", "\"", "\n", "\r", ":", "{", "}", "[", "]"])
    if needs_quotes:
        return "\"" + s.replace("\"", "\\\"") + "\""
    return s


def convert(json_path, toon_path, expected_tables_cli=None):
    with open(json_path, "r", encoding="utf-8") as f:
        data = json.load(f)
    if not isinstance(data, dict):
        raise ValueError("Root JSON must be an object of tables")

    expected_tables = ()
    if expected_tables_cli:
        expected_tables = tuple(expected_tables_cli)
    elif os.path.basename(json_path) == "prd-00-constitutional.json":
        expected_tables = EXPECTED_TABLES_CONSTITUTIONAL
    for table in expected_tables:
        if table not in data:
            print("[WARN] %s: Missing expected table: %s" % (json_path, table))

    with open(json_path, "rb") as f:
        checksum = hashlib.md5(f.read()).hexdigest()

    generated_at = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M:%S UTC")

    lines = [
        "# Generated from: %s" % json_path,
        "# Generated at: %s" % generated_at,
        "# JSON master checksum: %s" % checksum,
        "",
    ]
    first_table = True
    for table_name, rows in data.items():
        if not isinstance(rows, list):
            continue

        if not rows:
            print("[WARN] %s: Table '%s' has zero rows" % (json_path, table_name))

        # Determine stable columns from first row if available.
        cols = []
        if rows and isinstance(rows[0], dict):
            cols = sorted(rows[0].keys())
            if "pk" in cols:
                cols.remove("pk")
                cols.insert(0, "pk")

        if not first_table:
            lines.append("")
        first_table = False

        lines.append(
            "{}[{}]{{{}}}:".format(table_name, len(rows), ",".join(cols))
        )

        seen_pks = set()
        for row in rows:
            if isinstance(row, dict):
                if "pk" not in row:
                    raise ValueError("Row in table '%s' missing 'pk' field" % table_name)
                pk = row.get("pk")
                if pk in seen_pks:
                    raise ValueError("Duplicate pk %r in table %s" % (pk, table_name))
                seen_pks.add(pk)
                vals = [scalar_to_toon(row.get(c)) for c in cols]
                lines.append("  " + ",".join(vals))
            else:
                lines.append("  " + scalar_to_toon(row))

    with open(toon_path, "w", encoding="utf-8", newline="\n") as f:
        f.write("\n".join(lines).rstrip() + "\n")


def main():
    parser = argparse.ArgumentParser(description="Convert JSON memory file to TOON.")
    parser.add_argument("--json", required=True, help="Input JSON file path")
    parser.add_argument("--toon", required=True, help="Output TOON file path")
    parser.add_argument(
        "--expected-tables",
        nargs="+",
        help="Optional expected table names for strict presence warnings",
    )
    args = parser.parse_args()

    if not os.path.exists(args.json):
        print("[ERROR] JSON file not found: %s" % args.json)
        return 1
    try:
        convert(args.json, args.toon, args.expected_tables)
    except Exception as e:
        print(
            "[ERROR] Conversion failed: %s (check malformed nested objects or unsupported value types)"
            % e
        )
        return 1
    print("[OK] Wrote TOON: %s" % args.toon)
    return 0


if __name__ == "__main__":
    sys.exit(main())


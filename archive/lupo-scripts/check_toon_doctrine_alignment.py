#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/check_toon_doctrine_alignment.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Check TOON files for doctrine alignment.

Doctrine rules checked:
- No UNSIGNED on integer types (SQL Doctrine)
- No display widths: no int(11), bigint(20), tinyint(1), etc. (SQL Doctrine)
- No timestamp/datetime types; temporal = BIGINT YYYYMMDDHHIISS (Temporal Doctrine §5)
- doctrine_metadata: no_foreign_keys, no_triggers (Database Logic §7)

TOONs are generated from the live DB (lupo-scripts/generate_toon_files.py); this script only reports.

Run from project root: python scripts/check_toon_doctrine_alignment.py
"""

import json
import re
from pathlib import Path


def check_field(field_str: str, table_name: str) -> list:
    """Return list of doctrine violation messages for this field string."""
    violations = []
    # UNSIGNED
    if re.search(r"\bUNSIGNED\b", field_str, re.IGNORECASE):
        violations.append("UNSIGNED (doctrine: signed only)")
    # Display widths on integer types
    if re.search(r"\b(BIGINT|INT|SMALLINT|TINYINT)\s*\(\s*\d+\s*\)", field_str, re.IGNORECASE):
        violations.append("Display width on integer (doctrine: no int(11), tinyint(1), etc.)")
    # timestamp / datetime type (not column name)
    if re.search(r"`[^`]+`\s+(timestamp|datetime)\b", field_str, re.IGNORECASE):
        violations.append("timestamp/datetime type (doctrine §5: use BIGINT YYYYMMDDHHIISS)")
    if re.search(r"\bDEFAULT\s+CURRENT_TIMESTAMP", field_str, re.IGNORECASE):
        violations.append("CURRENT_TIMESTAMP (doctrine §5: use BIGINT)")
    if re.search(r"\bon\s+update\s+CURRENT_TIMESTAMP", field_str, re.IGNORECASE):
        violations.append("ON UPDATE CURRENT_TIMESTAMP (doctrine: no automatic timestamp)")
    return violations


def main():
    base = Path(__file__).resolve().parent
    project_root = base.parent
    toon_dir = project_root / "docs" / "toons"
    if not toon_dir.exists():
        print("TOON dir not found:", toon_dir)
        return 1

    toon_files = sorted(toon_dir.glob("*.toon.json"))
    total = 0
    issues = []

    for path in toon_files:
        try:
            data = json.loads(path.read_text(encoding="utf-8"))
        except Exception as e:
            issues.append((path.name, "", [("(parse error)", [str(e)])]))
            total += 1
            continue

        table_name = data.get("table_name", path.stem)
        fields = data.get("fields", [])
        meta = data.get("doctrine_metadata", {})

        file_issues = []
        for f in fields:
            if not isinstance(f, str):
                continue
            v = check_field(f, table_name)
            if v:
                file_issues.append((f.strip(), v))

        if meta.get("no_foreign_keys") is not True:
            file_issues.append(("doctrine_metadata", ["no_foreign_keys should be true"]))
        if meta.get("no_triggers") is not True:
            file_issues.append(("doctrine_metadata", ["no_triggers should be true"]))

        if file_issues:
            issues.append((path.name, table_name, file_issues))
        total += 1

    # Report
    print("TOON doctrine alignment check")
    print("=============================")
    print(f"Scanned {len(toon_files)} TOON files.")
    print()

    if not issues:
        print("All TOONs are aligned with doctrine (no UNSIGNED, no display widths, no timestamp/datetime, no FKs/triggers).")
        return 0

    print(f"Found issues in {len(issues)} file(s):\n")
    for fname, tname, file_issues in issues:
        print(f"  {fname}" + (f" (table: {tname})" if tname else ""))
        for field_or_meta, vlist in file_issues:
            short = field_or_meta[:70] + "..." if len(field_or_meta) > 70 else field_or_meta
            print(f"    - {short}")
            for v in vlist:
                print(f"      -> {v}")
        print()

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
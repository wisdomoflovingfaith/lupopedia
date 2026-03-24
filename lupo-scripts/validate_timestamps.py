#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/validate_timestamps.py"
#   last_modified_utc: "20260324175617"
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

from __future__ import print_function

"""
Validate timestamp prefixes in artifact filenames.

Default behavior is report-only. Deterministic correction is supported only through
an explicit rename map supplied by the operator.

Usage:
  python lupo-scripts/validate_timestamps.py
  python lupo-scripts/validate_timestamps.py --dry-run --json
  python lupo-scripts/validate_timestamps.py --rename-map rename_map.json --apply-rename-map
"""

import argparse
import json
import re
import sys
from datetime import datetime
from pathlib import Path


DEFAULT_SCAN_PATHS = [
    "lupo-channels",
    "lupo-docs",
    "lupo-rules",
    "lupo-actors",
    "lupo-database/sessions",
]

STRICT_PREFIX_RE = re.compile(r"^(?P<date>\d{8})_(?P<time>\d{6})(?=[._-]|$)")
COMPACT_PREFIX_RE = re.compile(r"^(?P<stamp>\d{14})(?=[._-]|$)")


def build_parser():
    parser = argparse.ArgumentParser(
        description="Validate UTC timestamp prefixes in Lupopedia artifact filenames"
    )
    parser.add_argument(
        "--repo-root",
        default=str(Path(__file__).resolve().parents[1]),
        help="Repository root to scan",
    )
    parser.add_argument(
        "--path",
        action="append",
        dest="paths",
        help="Relative path to scan; may be provided multiple times",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Report only; do not rename files",
    )
    parser.add_argument(
        "--json",
        action="store_true",
        help="Emit machine-readable JSON output",
    )
    parser.add_argument(
        "--rename-map",
        help="Path to JSON rename map used for deterministic correction",
    )
    parser.add_argument(
        "--apply-rename-map",
        action="store_true",
        help="Apply the supplied rename map after validating every target filename",
    )
    return parser


def detect_prefix(filename):
    strict = STRICT_PREFIX_RE.match(filename)
    if strict:
        return {
            "kind": "strict",
            "date_part": strict.group("date"),
            "time_part": strict.group("time"),
            "parsed_timestamp": "%s_%s" % (strict.group("date"), strict.group("time")),
        }

    compact = COMPACT_PREFIX_RE.match(filename)
    if compact:
        stamp = compact.group("stamp")
        return {
            "kind": "compact",
            "date_part": stamp[:8],
            "time_part": stamp[8:],
            "parsed_timestamp": stamp,
        }

    return None


def validate_candidate(candidate):
    violations = []
    date_part = candidate["date_part"]
    time_part = candidate["time_part"]
    kind = candidate["kind"]

    if kind == "compact":
        violations.append("FORMAT_UNDERSCORE_MISSING")

    if len(date_part) != 8:
        violations.append("DATE_SHAPE_INVALID")
    if len(time_part) != 6:
        violations.append("TIME_SHAPE_INVALID")

    if violations:
        return violations

    if not date_part.isdigit():
        violations.append("DATE_NOT_NUMERIC")
    if not time_part.isdigit():
        violations.append("TIME_NOT_NUMERIC")
    if violations:
        return violations

    hour = int(time_part[:2])
    minute = int(time_part[2:4])
    second = int(time_part[4:6])

    if hour < 0 or hour > 23:
        violations.append("HOUR_OUT_OF_RANGE")
    if minute < 0 or minute > 59:
        violations.append("MINUTE_OUT_OF_RANGE")
    if second < 0 or second > 59:
        violations.append("SECOND_OUT_OF_RANGE")

    try:
        datetime(int(date_part[:4]), int(date_part[4:6]), int(date_part[6:8]))
    except ValueError:
        violations.append("DATE_VALUE_INVALID")

    return violations


def scan_path(repo_root, relative_path):
    results = []
    base_path = repo_root / relative_path
    if not base_path.exists():
        return results

    for file_path in base_path.rglob("*"):
        if not file_path.is_file():
            continue
        candidate = detect_prefix(file_path.name)
        if candidate is None:
            continue
        violations = validate_candidate(candidate)
        if violations:
            results.append(
                {
                    "path": file_path.relative_to(repo_root).as_posix(),
                    "filename": file_path.name,
                    "parsed_timestamp": candidate["parsed_timestamp"],
                    "violation_types": violations,
                }
            )

    return results


def load_rename_map(rename_map_path):
    with open(rename_map_path, "r") as handle:
        payload = json.load(handle)

    if isinstance(payload, dict):
        return payload

    if isinstance(payload, list):
        mapping = {}
        for item in payload:
            if not isinstance(item, dict) or "from" not in item or "to" not in item:
                raise ValueError("Rename-map list entries must contain 'from' and 'to'.")
            mapping[item["from"]] = item["to"]
        return mapping

    raise ValueError("Rename map must be a JSON object or list of {'from','to'} entries.")


def validate_rename_map(rename_map, invalid_results, repo_root):
    issues_by_path = {}
    for result in invalid_results:
        issues_by_path[result["path"]] = result

    plan = []
    problems = []
    seen_targets = set()

    for old_path, new_path in sorted(rename_map.items()):
        old_rel = old_path.replace("\\", "/")
        new_rel = new_path.replace("\\", "/")

        if old_rel not in issues_by_path:
            problems.append("RENAME_MAP_SOURCE_NOT_INVALID: %s" % old_rel)
            continue

        new_name = Path(new_rel).name
        candidate = detect_prefix(new_name)
        if candidate is None:
            problems.append("RENAME_MAP_TARGET_MISSING_TIMESTAMP: %s" % new_rel)
            continue

        violations = validate_candidate(candidate)
        if violations:
            problems.append(
                "RENAME_MAP_TARGET_INVALID_TIMESTAMP: %s (%s)"
                % (new_rel, ", ".join(violations))
            )
            continue

        if new_rel in seen_targets:
            problems.append("RENAME_MAP_DUPLICATE_TARGET: %s" % new_rel)
            continue
        seen_targets.add(new_rel)

        new_abs = (repo_root / new_rel).resolve()
        if new_abs.exists():
            problems.append("RENAME_MAP_TARGET_EXISTS: %s" % new_rel)
            continue

        old_abs = (repo_root / old_rel).resolve()
        if not old_abs.exists():
            problems.append("RENAME_MAP_SOURCE_MISSING: %s" % old_rel)
            continue

        plan.append((old_abs, new_abs, old_rel, new_rel))

    return plan, problems


def emit_human_report(results, rename_required):
    if not results:
        print("No invalid filename timestamps found.")
        return

    print("Invalid filename timestamps detected: %d" % len(results))
    for item in results:
        print("Path: %s" % item["path"])
        print("Parsed timestamp: %s" % item["parsed_timestamp"])
        print("Violation types: %s" % ", ".join(item["violation_types"]))
        if rename_required:
            print("Action: rename required or explicit correction evidence required")
        print("")


def apply_rename_plan(plan, dry_run):
    applied = []
    for old_abs, new_abs, old_rel, new_rel in plan:
        if dry_run:
            applied.append({"from": old_rel, "to": new_rel, "applied": False})
            continue
        new_abs.parent.mkdir(parents=True, exist_ok=True)
        old_abs.rename(new_abs)
        applied.append({"from": old_rel, "to": new_rel, "applied": True})
    return applied


def main():
    parser = build_parser()
    args = parser.parse_args()

    if args.apply_rename_map and not args.rename_map:
        parser.error("--apply-rename-map requires --rename-map")

    repo_root = Path(args.repo_root).resolve()
    paths = args.paths or DEFAULT_SCAN_PATHS
    results = []

    for relative_path in paths:
        results.extend(scan_path(repo_root, relative_path))

    results.sort(key=lambda item: item["path"])
    rename_required = bool(results)

    output = {
        "repo_root": repo_root.as_posix(),
        "scanned_paths": paths,
        "invalid_count": len(results),
        "invalid_files": results,
    }

    exit_code = 0 if not results else 1

    if args.rename_map:
        try:
            rename_map = load_rename_map(args.rename_map)
            plan, problems = validate_rename_map(rename_map, results, repo_root)
        except (OSError, ValueError) as exc:
            if args.json:
                output["rename_map_error"] = str(exc)
                print(json.dumps(output, indent=2, sort_keys=True))
            else:
                print("Rename map error: %s" % exc)
            return 2

        output["rename_plan"] = [{"from": old_rel, "to": new_rel} for _, _, old_rel, new_rel in plan]
        output["rename_map_problems"] = problems

        if problems:
            exit_code = 2
        elif args.apply_rename_map:
            output["applied_renames"] = apply_rename_plan(plan, args.dry_run)

    if args.json:
        print(json.dumps(output, indent=2, sort_keys=True))
    else:
        emit_human_report(results, rename_required)
        if args.rename_map:
            if output.get("rename_plan"):
                print("Deterministic rename plan:")
                for item in output["rename_plan"]:
                    print("  %s -> %s" % (item["from"], item["to"]))
            if output.get("rename_map_problems"):
                print("Rename-map problems:")
                for problem in output["rename_map_problems"]:
                    print("  %s" % problem)
            if output.get("applied_renames"):
                print("Applied rename actions:")
                for item in output["applied_renames"]:
                    status = "dry-run" if not item["applied"] else "renamed"
                    print("  [%s] %s -> %s" % (status, item["from"], item["to"]))

    return exit_code


if __name__ == "__main__":
    sys.exit(main())
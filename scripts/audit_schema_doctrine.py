#!/usr/bin/env python3
"""
LUPO Schema Doctrine Audit — optional Python companion.

Mirrors the checks in scripts/audit_schema_doctrine.php (canonical).
Uses TOON files in lupo-database/lupopedia/toon/ (or json/ for parsing). No DB connection.
Run: python scripts/audit_schema_doctrine.py [--json-only]

PHP script is the source of truth; this is supplemental (e.g. CI without PHP).
"""

import json
import re
import sys
from pathlib import Path

try:
    import yaml
except ImportError:
    yaml = None

BASE_DIR = Path(__file__).resolve().parent.parent
TOON_DIR = BASE_DIR / "lupo-database" / "lupopedia" / "toon"
JSON_DIR = BASE_DIR / "lupo-database" / "lupopedia" / "json"
REPORT_PATH = BASE_DIR / "artifacts" / "reports" / "schema_doctrine_audit.json"

TIME_COLUMN_PATTERNS = (
    "created_ymdhis", "updated_ymdhis", "deleted_ymdhis", "started_ymdhis", "completed_ymdhis",
    "last_modified_utc", "last_message_ymdhis", "read_by_actor_utc", "reserved_ymdhis",
    "expires_ymdhis", "last_seen_ymdhis", "handshake_completed_ymdhis", "awareness_completed_ymdhis",
    "cjp_completed_ymdhis", "escalation_timestamp", "end_ymdhis",
)
TIME_LIKE_SUFFIXES = ("_ymdhis", "_utc", "_timestamp", "created_at", "updated_at", "deleted_at")

TABLES_REQUIRING_SOFT_DELETE = (
    "lupo_actors", "lupo_agents", "lupo_dialog_messages", "lupo_dialog_threads", "lupo_tasks",
    "lupo_contents", "lupo_sessions", "lupo_registry", "lupo_channels", "lupo_auth_users",
    "lupo_actor_channel_roles", "lupo_metadata", "lupo_visits", "lupo_actor_edges", "lupo_actor_collections",
)
SOFT_DELETE_LIKELY_PATTERNS = (
    "lupo_dialog_", "lupo_task", "lupo_content", "lupo_agent", "lupo_actor_channel", "lupo_crm_", "lupo_visit",
)
SOFT_DELETE_EXEMPT_PREFIXES = ("livehelp_",)
SOFT_DELETE_EXEMPT_TABLES = (
    "lupo_actor_moods", "lupo_channel_log_types", "lupo_task_statuses", "lupo_task_priorities",
    "lupo_event_metadata", "lupo_department_metadata",
)

_FIELD_RE = re.compile(r"^`([^`]+)`\s+(\w+)(?:\([^)]*\))?")
_FIELD_FULL_TYPE_RE = re.compile(r"^`[^`]+`\s+(\S+)")


def _parse_toon_field(field_def):
    m = _FIELD_RE.match(field_def.strip())
    if not m:
        return None
    name, type_base = m.group(1), m.group(2).lower()
    full_type_m = _FIELD_FULL_TYPE_RE.match(field_def.strip())
    full_type = full_type_m.group(1).lower() if full_type_m else type_base
    return {"name": name, "type": type_base, "full_type": full_type, "full": field_def.strip()}


def _load_toon(table_name):
    toon_path = TOON_DIR / (table_name + ".toon")
    json_path = JSON_DIR / (table_name + ".json")
    if toon_path.is_file() and yaml is not None:
        try:
            data = yaml.safe_load(toon_path.read_text(encoding="utf-8"))
            return data if isinstance(data, dict) else None
        except Exception:
            pass
    if json_path.is_file():
        try:
            return json.loads(json_path.read_text(encoding="utf-8"))
        except Exception:
            pass
    return None


def _is_time_like(col_name):
    if col_name in TIME_COLUMN_PATTERNS:
        return True
    for s in TIME_LIKE_SUFFIXES:
        if s in col_name or col_name.endswith(s):
            return True
    return False


def _needs_soft_delete(table):
    if table in SOFT_DELETE_EXEMPT_TABLES:
        return False
    for p in SOFT_DELETE_EXEMPT_PREFIXES:
        if table.startswith(p):
            return False
    if table in TABLES_REQUIRING_SOFT_DELETE:
        return True
    if table.startswith("lupo_"):
        for pat in SOFT_DELETE_LIKELY_PATTERNS:
            if pat in table:
                return True
    return False


def main():
    json_only = "--json-only" in sys.argv
    if not TOON_DIR.is_dir():
        print("TOON directory not found:", TOON_DIR, file=sys.stderr)
        return 1

    toon_files = sorted(TOON_DIR.glob("*.toon"))
    table_list = [f.stem for f in toon_files]
    if not table_list and JSON_DIR.is_dir():
        table_list = [f.stem for f in sorted(JSON_DIR.glob("*.json"))]

    report = {
        "generated_utc": int(__import__("datetime").datetime.now(__import__("datetime").timezone.utc).strftime("%Y%m%d%H%M%S")),
        "schema_source": "lupo-database/lupopedia/toon",
        "summary": {
            "tables_checked": len(table_list),
            "columns_checked": 0,
            "foreign_keys_found": 0,
            "triggers_found": 0,
            "procedures_found": 0,
            "functions_found": 0,
            "datetime_timestamp_violations": 0,
            "bigint_time_violations": 0,
            "soft_delete_violations": 0,
            "soft_delete_warnings": 0,
            "doctrine_metadata_violations": 0,
            "warnings": 0,
        },
        "violations": {
            "foreign_keys": [],
            "triggers": [],
            "procedures": [],
            "functions": [],
            "forbidden_temporal_types": [],
            "bigint_time_columns": [],
            "soft_delete": [],
            "doctrine_metadata": [],
        },
        "warnings": [],
        "exemptions": [],
    }

    for table in table_list:
        toon = _load_toon(table)
        if not toon or not isinstance(toon.get("fields"), list):
            report["warnings"].append({"table": table, "message": "TOON missing or invalid (no fields)."})
            report["summary"]["warnings"] += 1
            continue

        cols = []
        for field_str in toon["fields"]:
            parsed = _parse_toon_field(field_str)
            if parsed:
                cols.append(parsed)
        report["summary"]["columns_checked"] += len(cols)

        for c in cols:
            col_name = c["name"]
            data_type = c["type"]
            col_type = c["full_type"]

            if data_type == "datetime":
                report["violations"]["forbidden_temporal_types"].append(
                    {"table": table, "column": col_name, "actual_type": col_type}
                )
                report["summary"]["datetime_timestamp_violations"] += 1
            if data_type == "timestamp":
                report["violations"]["forbidden_temporal_types"].append(
                    {"table": table, "column": col_name, "actual_type": col_type}
                )
                report["summary"]["datetime_timestamp_violations"] += 1

            if _is_time_like(col_name):
                if data_type != "bigint":
                    report["violations"]["bigint_time_columns"].append(
                        {"table": table, "column": col_name, "actual_type": col_type, "expected": "BIGINT"}
                    )
                    report["summary"]["bigint_time_violations"] += 1
                elif "unsigned" in c["full"].lower():
                    report["warnings"].append(
                        {"table": table, "column": col_name, "message": "BIGINT UNSIGNED; doctrine prefers signed BIGINT for timestamps."}
                    )
                    report["summary"]["warnings"] += 1

            if col_name.lower() == "is_deleted":
                pass  # used below for soft_delete check

        doctrine_meta = toon.get("doctrine_metadata") or {}
        if table.startswith("lupo_"):
            if doctrine_meta.get("no_foreign_keys") is not True:
                report["violations"]["doctrine_metadata"].append(
                    {"table": table, "message": "doctrine_metadata.no_foreign_keys should be true."}
                )
                report["summary"]["doctrine_metadata_violations"] += 1
            if doctrine_meta.get("no_triggers") is not True:
                report["violations"]["doctrine_metadata"].append(
                    {"table": table, "message": "doctrine_metadata.no_triggers should be true."}
                )
                report["summary"]["doctrine_metadata_violations"] += 1

        has_is_deleted = any((c["name"] or "").lower() == "is_deleted" for c in cols)
        if _needs_soft_delete(table) and not has_is_deleted:
            report["violations"]["soft_delete"].append(
                {"table": table, "message": "Table likely requires soft-delete but has no is_deleted TINYINT."}
            )
            report["summary"]["soft_delete_violations"] += 1

    REPORT_PATH.parent.mkdir(parents=True, exist_ok=True)
    REPORT_PATH.write_text(json.dumps(report, indent=2), encoding="utf-8")

    if not json_only:
        print("=== LUPO Schema Doctrine Audit (TOON source, Python) ===")
        print("Schema source: lupo-database/lupopedia/toon")
        print("Generated:", report["generated_utc"])
        print("Tables checked:", report["summary"]["tables_checked"], "| Columns:", report["summary"]["columns_checked"])
        viol = (
            report["summary"]["doctrine_metadata_violations"] + report["summary"]["datetime_timestamp_violations"]
            + report["summary"]["bigint_time_violations"] + report["summary"]["soft_delete_violations"]
        )
        if viol == 0 and report["summary"]["warnings"] == 0:
            print("[COMPLIANT] No doctrine violations or warnings.")
        else:
            if report["summary"]["doctrine_metadata_violations"]:
                print("[VIOLATION] doctrine_metadata:", report["summary"]["doctrine_metadata_violations"])
            if report["summary"]["datetime_timestamp_violations"]:
                print("[VIOLATION] DATETIME/TIMESTAMP:", report["summary"]["datetime_timestamp_violations"])
            if report["summary"]["bigint_time_violations"]:
                print("[VIOLATION] Time columns not BIGINT:", report["summary"]["bigint_time_violations"])
            if report["summary"]["soft_delete_violations"]:
                print("[VIOLATION] Soft-delete missing:", report["summary"]["soft_delete_violations"])
            if report["summary"]["warnings"]:
                print("[WARNING] Warnings:", report["summary"]["warnings"])
        print("JSON report:", REPORT_PATH)

    viol_count = (
        report["summary"]["doctrine_metadata_violations"] + report["summary"]["datetime_timestamp_violations"]
        + report["summary"]["bigint_time_violations"] + report["summary"]["soft_delete_violations"]
    )
    return 1 if viol_count > 0 else 0


if __name__ == "__main__":
    sys.exit(main())

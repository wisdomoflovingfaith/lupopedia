#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260401000000"
#   file_path_from_root: "scripts/export_table_data_csv.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260401"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
CSV Data Export — debugging tool only.

Exports row data from non-sensitive tables to CSV files for debugging purposes.
This is a SEPARATE tool from generate_toon_files.py, which exports schema only.

SECURITY RULES:
  - Sensitive tables are ALWAYS excluded (see EXCLUDED_TABLES below).
  - Excluded tables contain: credentials, password hashes, salts, secret keys,
    API tokens, session tokens, auth data, or any PII.
  - This script must NEVER be run in production or committed with output files.
  - Output files must NEVER be committed to version control.
  - Output directory: database/lupopedia/csv/ (gitignored)

Usage:
  python scripts/export_table_data_csv.py
  python scripts/export_table_data_csv.py --tables lupo_actors lupo_collections
  python scripts/export_table_data_csv.py --limit 100

Options:
  --tables TABLE [TABLE ...]   Export only these specific tables (still subject to exclusions)
  --limit N                    Max rows per table (default: 500)
  --output-dir PATH            Output directory (default: database/lupopedia/csv/)
"""

import csv
import json
import sys
import argparse
from datetime import date, datetime
from decimal import Decimal
from pathlib import Path
from typing import Any, Dict, List, Optional

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    pymysql = None
    DictCursor = None

from lib.db_connection import get_connection_params

# =============================================================================
# SENSITIVE TABLES — ALWAYS EXCLUDED
# These tables contain credentials, secrets, tokens, hashes, salts, or PII.
# Adding a table here is permanent — removal requires explicit review.
# =============================================================================
EXCLUDED_TABLES = frozenset({
    # Authentication and credentials
    "lupo_auth_users",          # password_hash, salt, reset tokens
    "lupo_auth_providers",      # OAuth secrets, provider tokens
    "lupo_auth_audit_log",      # may contain credential attempt data
    "lupo_api_tokens",          # raw API tokens
    "lupo_api_token_logs",      # token usage logs
    "lupo_api_clients",         # client_secret fields
    "lupo_agent_faucet_credentials",  # faucet secret keys
    # Session data
    "lupo_sessions",            # session tokens, csrf_token, ip_hash, ua_hash
    # Security and bans
    "lupo_banned_actors",       # may contain PII
    "lupo_bans_log",            # may contain PII
    # Audit logs that may contain sensitive payloads
    "lupo_audit_log",
    "lupo_auth_audit_log",
    "lupo_unified_log",
    # CRM / lead data (PII)
    "lupo_crm_leads",
    "lupo_crm_lead_messages",
    # Live help transcripts (PII)
    "lupo_crafty_syntax_leave_message",
    "lupo_crafty_syntax_chat_questions",
})

DEFAULT_ROW_LIMIT = 500


def json_serializable(val: Any) -> Any:
    if val is None:
        return ""
    if isinstance(val, bool):
        return "1" if val else "0"
    if isinstance(val, (int, float)):
        return str(val)
    if isinstance(val, Decimal):
        return str(int(val) if val == val.to_integral_value() else float(val))
    if isinstance(val, (datetime, date)):
        return val.isoformat()
    if isinstance(val, bytes):
        return val.hex()
    return str(val)


def fetch_tables(cursor) -> List[str]:
    cursor.execute("SHOW TABLES")
    rows = cursor.fetchall()
    if rows and isinstance(rows[0], dict):
        return [list(row.values())[0] for row in rows]
    return [row[0] for row in rows]


def fetch_column_names(cursor, table_name: str) -> List[str]:
    cursor.execute("SHOW COLUMNS FROM `{}`".format(table_name.replace("`", "``")))
    rows = cursor.fetchall()
    if rows and isinstance(rows[0], dict):
        return [row["Field"] for row in rows]
    return [row[0] for row in rows]


def export_table(cursor, table_name: str, output_dir: Path, limit: int) -> int:
    """Export up to `limit` rows from table_name to a CSV file. Returns row count."""
    columns = fetch_column_names(cursor, table_name)
    cursor.execute(
        "SELECT * FROM `{}` WHERE is_deleted = 0 LIMIT {}".format(
            table_name.replace("`", "``"), int(limit)
        )
    )
    rows = cursor.fetchall()

    output_path = output_dir / "{}.csv".format(table_name)
    with output_path.open("w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=columns, extrasaction="ignore")
        writer.writeheader()
        for row in rows:
            if isinstance(row, dict):
                writer.writerow({c: json_serializable(row.get(c)) for c in columns})
            else:
                writer.writerow({c: json_serializable(v) for c, v in zip(columns, row)})

    return len(rows)


def main() -> int:
    parser = argparse.ArgumentParser(description="Export non-sensitive table data to CSV for debugging.")
    parser.add_argument("--tables", nargs="+", metavar="TABLE",
                        help="Specific tables to export (still subject to exclusions)")
    parser.add_argument("--limit", type=int, default=DEFAULT_ROW_LIMIT,
                        help="Max rows per table (default: {})".format(DEFAULT_ROW_LIMIT))
    parser.add_argument("--output-dir", metavar="PATH",
                        help="Output directory (default: database/lupopedia/csv/)")
    args = parser.parse_args()

    base = Path(__file__).resolve().parent
    project_root = base.parent

    if args.output_dir:
        output_dir = Path(args.output_dir)
    else:
        output_dir = project_root / "database" / "lupopedia" / "csv"

    output_dir.mkdir(parents=True, exist_ok=True)

    # Warn loudly that this is a debugging tool
    print("WARNING: CSV export is a debugging tool only.")
    print("Output files contain live database data. Do NOT commit them to version control.")
    print("Output directory: {}".format(output_dir))
    print()

    if pymysql is None or DictCursor is None:
        print("pymysql is required. Install with: pip install pymysql", file=sys.stderr)
        return 1

    try:
        params = get_connection_params()
        params["charset"] = "utf8mb4"
        conn = pymysql.connect(cursorclass=DictCursor, **params)
    except Exception as e:
        print("Database connection failed:", e, file=sys.stderr)
        return 1

    try:
        cursor = conn.cursor()
        all_tables = fetch_tables(cursor)

        if args.tables:
            requested = set(args.tables)
            tables_to_export = [t for t in all_tables if t in requested]
            unknown = requested - set(all_tables)
            if unknown:
                print("WARNING: Tables not found in database: {}".format(", ".join(sorted(unknown))))
        else:
            tables_to_export = all_tables

        skipped = []
        exported = []

        for table_name in sorted(tables_to_export):
            if table_name in EXCLUDED_TABLES:
                skipped.append(table_name)
                continue

            # Also skip any table whose name contains sensitive keywords
            lower = table_name.lower()
            if any(kw in lower for kw in ("secret", "password", "credential", "token", "salt", "hash")):
                skipped.append(table_name)
                print("  SKIPPED (sensitive name pattern): {}".format(table_name))
                continue

            try:
                count = export_table(cursor, table_name, output_dir, args.limit)
                exported.append((table_name, count))
                print("  exported: {} ({} rows)".format(table_name, count))
            except Exception as e:
                print("  ERROR exporting {}: {}".format(table_name, e), file=sys.stderr)

        print()
        print("Exported {} tables to {}".format(len(exported), output_dir))
        if skipped:
            print("Skipped {} sensitive tables: {}".format(
                len(skipped), ", ".join(sorted(skipped))))
        return 0
    finally:
        if conn:
            conn.close()


if __name__ == "__main__":
    raise SystemExit(main())

#!/usr/bin/env python3
"""
READ-ONLY reserved-word audit of TOON files.
Identifies column names that conflict with MySQL or PostgreSQL reserved keywords.
Does NOT modify TOONs, schema, or code. Outputs a report only.

Run from project root: python scripts/audit_toon_reserved_words.py
"""

import json
import re
from pathlib import Path

# User-specified dangerous names (do not modify this list; may add)
USER_SPECIFIED = {
    "offset", "mod", "module", "order", "group", "key", "index", "match",
    "timestamp", "date", "time", "user", "role", "constraint", "primary",
    "foreign", "references", "interval", "limit", "default", "schema",
    "table", "column", "view", "cast", "type", "any", "all", "and", "or", "not",
}

# MySQL 8.0 reserved (R) - subset that are commonly used as column names
MYSQL_RESERVED = USER_SPECIFIED | {
    "accessible", "add", "alter", "analyze", "and", "as", "asc", "before",
    "between", "bigint", "binary", "blob", "both", "by", "call", "cascade",
    "case", "change", "char", "character", "check", "collate", "column",
    "condition", "constraint", "continue", "convert", "create", "cross",
    "current_date", "current_time", "current_timestamp", "current_user",
    "cursor", "database", "databases", "dec", "decimal", "declare", "default",
    "delete", "desc", "describe", "deterministic", "distinct", "distinctrow",
    "div", "double", "drop", "dual", "each", "else", "elseif", "empty",
    "enclosed", "escaped", "except", "execute", "exists", "exit", "explain",
    "false", "fetch", "float", "float4", "float8", "for", "force", "foreign",
    "from", "fulltext", "function", "generated", "get", "grant", "group",
    "grouping", "groups", "having", "if", "ignore", "in", "index", "infile",
    "inner", "inout", "insensitive", "insert", "int", "integer", "intersect",
    "interval", "into", "is", "iterate", "join", "key", "keys", "kill",
    "leading", "leave", "left", "like", "limit", "linear", "lines", "load",
    "localtime", "localtimestamp", "lock", "long", "loop", "match", "mod",
    "modifies", "natural", "not", "null", "numeric", "of", "on", "open",
    "optimize", "option", "optionally", "or", "order", "out", "outer", "outfile",
    "partition", "precision", "primary", "procedure", "purge", "range", "rank",
    "read", "reads", "real", "recursive", "references", "release", "rename",
    "repeat", "replace", "require", "restrict", "return", "revoke", "right",
    "row", "rows", "schema", "schemas", "select", "sensitive", "separator",
    "set", "show", "signal", "smallint", "specific", "sql", "sqlstate",
    "starting", "stored", "table", "tables", "then", "tinyint", "to",
    "trailing", "trigger", "true", "undo", "union", "unique", "unlock",
    "unsigned", "update", "usage", "use", "using", "values", "varchar",
    "varying", "when", "where", "while", "with", "write", "xor", "year_month",
    "zerofill", "offset", "timestamp", "date", "time", "user", "role",
    "current_timestamp", "desc", "asc", "end", "start", "comment", "mode",
}

# PostgreSQL reserved (column/table names not allowed)
POSTGRESQL_RESERVED = USER_SPECIFIED | {
    "all", "analyse", "analyze", "and", "any", "array", "as", "asc",
    "asymmetric", "authorization", "binary", "both", "case", "cast",
    "check", "collate", "collation", "column", "concurrently", "constraint",
    "create", "cross", "current_catalog", "current_date", "current_role",
    "current_schema", "current_time", "current_timestamp", "current_user",
    "default", "desc", "do", "else", "end", "except", "false", "fetch",
    "for", "foreign", "freeze", "full", "grant", "group", "having",
    "in", "initially", "inner", "inout", "intersect", "into", "join",
    "lateral", "leading", "left", "like", "limit", "localtime", "localtimestamp",
    "natural", "new", "not", "null", "offset", "old", "on", "only", "or",
    "order", "outer", "over", "placing", "primary", "references", "returning",
    "right", "row", "select", "session_user", "similar", "some", "symmetric",
    "table", "then", "to", "trailing", "true", "union", "unique", "user",
    "using", "variadic", "verbose", "when", "where", "window", "with",
    "mod", "module", "type", "view", "role", "schema", "interval", "any",
    "current_time", "current_timestamp", "time", "timestamp", "date",
}

# Normalize: lowercase for comparison
MYSQL_RESERVED = {w.lower() for w in MYSQL_RESERVED}
POSTGRESQL_RESERVED = {w.lower() for w in POSTGRESQL_RESERVED}


def extract_column_names(fields):
    """Extract column name from each TOON field string. Format: `colname` type ..."""
    cols = []
    for f in fields:
        if not isinstance(f, str):
            continue
        m = re.match(r"`([^`]+)`", f)
        if m:
            cols.append(m.group(1))
    return cols


def suggest_alternative(col):
    """Suggest safe alternative name for reserved word."""
    suggestions = {
        "offset": "sort_offset or row_offset or offset_value",
        "mod": "mod_id or mod_value or modulo",
        "module": "module_id or module_key",
        "order": "sort_order or display_order or order_key",
        "group": "group_id or group_key",
        "key": "key_name or key_value or index_key",
        "index": "index_name or index_key or idx",
        "match": "match_id or match_key or is_match",
        "timestamp": "created_ymdhis or timestamp_utc or ts_ymdhis",
        "date": "date_ymd or date_value or event_date",
        "time": "time_value or created_ymdhis or time_utc",
        "user": "user_id or actor_id or username",
        "role": "role_key or role_id or role_name",
        "constraint": "constraint_key or constraint_name",
        "primary": "primary_key or is_primary",
        "foreign": "foreign_key or foreign_id",
        "references": "references_json or ref_id",
        "interval": "interval_seconds or interval_value",
        "limit": "limit_value or max_count",
        "default": "default_value or is_default",
        "schema": "schema_name or schema_key",
        "table": "table_name or target_table",
        "column": "column_name or column_key",
        "view": "view_name or view_count or view_key",
        "cast": "cast_id or cast_type",
        "type": "type_key or content_type or entity_type",
        "any": "any_flag or any_value",
        "all": "all_flag or all_count",
        "and": "and_flag or and_value",
        "or": "or_flag or or_value",
        "not": "not_flag or is_not",
    }
    return suggestions.get(col.lower(), f"{col}_id or {col}_key or {col}_value")


def main():
    base = Path(__file__).resolve().parent
    project_root = base.parent
    toon_dir = project_root / "docs" / "toons"
    if not toon_dir.exists():
        print("TOON dir not found:", toon_dir)
        return 1

    toon_files = sorted(toon_dir.glob("*.toon.json"))
    violations = []  # (table_name, column_name, mysql_v, pg_v, severity, suggestion)

    for path in toon_files:
        try:
            data = json.loads(path.read_text(encoding="utf-8"))
        except Exception as e:
            print("Parse error", path.name, e)
            continue
        table_name = data.get("table_name", path.stem.replace(".toon", ""))
        fields = data.get("fields", [])
        cols = extract_column_names(fields)
        for col in cols:
            col_lower = col.lower()
            mysql_v = col_lower in MYSQL_RESERVED
            pg_v = col_lower in POSTGRESQL_RESERVED
            if not (mysql_v or pg_v):
                continue
            if mysql_v and pg_v:
                severity = "HIGH"
            elif pg_v:
                severity = "MEDIUM"
            else:
                severity = "LOW"
            which = []
            if mysql_v:
                which.append("MySQL")
            if pg_v:
                which.append("PostgreSQL")
            violations.append((table_name, col, which, severity, suggest_alternative(col)))

    # Report
    if not violations:
        report_content = (
            "=" * 80 + "\n"
            "TOON RESERVED-WORD AUDIT REPORT\n"
            "=" * 80 + "\n"
            "Canonical schema: database/migrations/install_new_lupopedia.sql\n"
            "TOONs: docs/toons/*.toon.json (read-only; do not modify)\n\n"
            "Scanned {} TOON files.\n"
            "Total violations: 0.\n\n"
            "No column names conflict with MySQL or PostgreSQL reserved keywords.\n"
            "In SQL, always quote column names with backticks (e.g. `order`) to avoid errors."
        ).format(len(toon_files))
        print(report_content)
    else:
        report_content = build_report(violations, toon_files)
        print(report_content)

    # Write report to file (UTF-8)
    report_path = project_root / "database" / "migrations" / "reserved_word_audit_report.txt"
    report_path.parent.mkdir(parents=True, exist_ok=True)
    with open(report_path, "w", encoding="utf-8") as f:
        f.write(report_content)
    print()
    print("Report written to:", report_path)

    return 0


def build_report(violations, toon_files):
    """Build report text."""
    lines = [
        "=" * 80,
        "TOON RESERVED-WORD AUDIT REPORT",
        "=" * 80,
        "Canonical schema: database/migrations/install_new_lupopedia.sql",
        "TOONs: docs/toons/*.toon.json (read-only; do not modify)",
        "",
        f"Scanned {len(toon_files)} TOON files.",
        f"Total violations: {len(violations)}",
        "",
        "VIOLATIONS BY SEVERITY",
        "-" * 80,
        f"HIGH (both MySQL and PostgreSQL): {len([v for v in violations if v[3] == 'HIGH'])}",
        f"MEDIUM (PostgreSQL only):         {len([v for v in violations if v[3] == 'MEDIUM'])}",
        f"LOW (MySQL only):                 {len([v for v in violations if v[3] == 'LOW'])}",
        "",
        "DETAILED REPORT",
        "-" * 80,
        f"{'Table':<45} {'Column':<25} {'Violates':<18} {'Sev':<6} Suggested alternative",
        "-" * 80,
    ]
    for table_name, col, which, severity, suggestion in sorted(violations, key=lambda x: (x[3], x[0], x[1])):
        which_str = ", ".join(which)
        lines.append(f"{table_name:<45} {col:<25} {which_str:<18} {severity:<6} {suggestion}")
    lines.extend([
        "",
        "NOTE: This is a read-only audit. No TOONs, schema, or code were modified.",
        "In SQL, always quote column names with backticks (e.g. `order`) to avoid errors.",
    ])
    return "\n".join(lines)


if __name__ == "__main__":
    raise SystemExit(main())

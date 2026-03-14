
import os
from datetime import datetime, timedelta
import hashlib

doctrines = [
    {
        "slug": "cw_0001_php_compatibility",
        "title": "PHP COMPATIBILITY DOCTRINE",
        "content": """# PHP COMPATIBILITY DOCTRINE
All Lupopedia code MUST:
1. Run on PHP 5.3 (minimum baseline). Use array() not []. No traits, no yield, no splat operator.
2. Run on latest PHP versions (PHP 8.2+). No features that break forward compatibility.
3. No deprecated functions. No reliance on removed behavior.
Rationale: Maximum portability across legacy and modern stacks. Any modern-only syntax will be rejected."""
    },
    {
        "slug": "cw_0002_timestamp_standard",
        "title": "TIME + TIMESTAMP STANDARD",
        "content": """# TIME + TIMESTAMP STANDARD
All time data MUST:
1. Be stored as BIGINT in YYYYMMDDHHMMSS format.
2. Use 24-hour UTC only. No local time. No offsets.
3. NEVER use DATETIME or TIMESTAMP column types.
4. Never use DB functions like NOW() or CURDATE().
Rationale: Absolute sortability and platform neutrality."""
    },
    {
        "slug": "cw_0003_soft_delete",
        "title": "SOFT DELETE DOCTRINE",
        "content": """# SOFT DELETE DOCTRINE
Every table MUST include:
1. is_deleted (TINYINT, 0=Active, 1=Deleted)
2. deleted_ymdhis (BIGINT, 0 or Timestamp)
Rules:
- NO physical deletes. NO TRUNCATE. Deletion is a state change only.
- All queries MUST filter is_deleted = 0 unless specified.
Rationale: Audit trails and recovery are mandatory."""
    },
    {
        "slug": "cw_0004_pdo_factory",
        "title": "DATABASE ACCESS STANDARD",
        "content": """# DATABASE ACCESS STANDARD
All database interactions MUST:
1. Use the PDO wrapper. NO raw mysqli.
2. Use DatabaseFactory::getConnection() or helper lupo_get_db().
3. Use prepared statements with named placeholders.
4. NO inline connects. NO singleton hacks. Logic must be object-oriented.
Rationale: Unified connection management and injection protection."""
    },
    {
        "slug": "cw_0005_oop_enforcement",
        "title": "OOP ENFORCEMENT",
        "content": """# OOP ENFORCEMENT
All logic MUST follow OOP standards:
1. NO global helper functions unless explicitly authorized core.
2. NO procedural database calls.
3. NO random utility 'function dumps'.
4. Logic MUST live inside classes with clear responsibility boundaries.
Rationale: Maintainability and strict namespace control."""
    },
    {
        "slug": "cw_0006_cross_db_sql",
        "title": "CROSS-DB COMPATIBILITY LAW",
        "content": """# CROSS-DB COMPATIBILITY LAW
SQL MUST be vendor-neutral to support MySQL, PostgreSQL, and MariaDB:
1. NO UNSIGNED types.
2. NO DATETIME/TIMESTAMP (use BIGINT).
3. NO Engine hints (e.g., ENGINE=InnoDB).
4. NO vendor-specific extensions or functions.
Rationale: Single codebase, multiple engines. Total cross-platform portability."""
    },
    {
        "slug": "cw_0007_windows_wsl",
        "title": "WINDOWS COMPATIBILITY FOR UNIX COMMANDS",
        "content": """# WINDOWS COMPATIBILITY FOR UNIX COMMANDS
Windows agents MUST use WSL for Unix-style tooling (wc, ls, grep, sed):
1. Install WSL via 'wsl --install' in elevated shell.
2. Use 'wsl <command>' (e.g., 'wsl wc -l').
3. Do NOT use native Windows cmd/powershell equivalents for Unix scripts.
Rationale: Uniform toolchain across OS environments. Mandatory for script compatibility."""
    },
    {
        "slug": "cw_0008_db_feature_ban",
        "title": "FORBIDDEN DATABASE FEATURES",
        "content": """# FORBIDDEN DATABASE FEATURES
Strictly PROHIBITED at the database level:
1. Foreign keys, Triggers, Stored procedures.
2. Views, Database functions, Generated columns.
3. ALL logic MUST live in application code (PHP/Python).
Rationale: DB should be a simple persistence layer. Logic belongs in the code for portability and scaling."""
    },
    {
        "slug": "cw_0009_full_column_queries",
        "title": "EXPLICIT INSERT / UPDATE RULE",
        "content": """# EXPLICIT INSERT / UPDATE RULE
All INSERT and UPDATE statements MUST:
1. Specify EVERY column explicitly. NO shorthand.
2. Include the Primary Key (actor_id, artifact_id, etc.).
3. Never rely on column defaults. Include nullable fields in query.
Rationale: Safety and transparency. Ambiguous queries are rejected."""
    },
    {
        "slug": "cw_0010_registry_id_policy",
        "title": "ID ALLOCATION AUTHORITY",
        "content": """# ID ALLOCATION AUTHORITY
Primary keys MUST NOT auto-increment.
1. Allocate IDs from registry_open.
2. Verify against registry (permanent/reserved keys).
3. NEVER reuse protected or locked IDs.
4. Management must be explicit in application code.
Rationale: Prevents collisions across federated nodes and master registries."""
    }
]

base_time = datetime(2026, 2, 24, 15, 30, 0)
output_dir = "channels/0/broadcasts"
os.makedirs(output_dir, exist_ok=True)

for i, doc in enumerate(doctrines):
    current_time = (base_time + timedelta(minutes=i)).strftime('%Y%m%d%H%M%S')
    filename = f"{doc['slug']}.md"
    filepath = os.path.join(output_dir, filename)
    
    header = f"""---
actor_id: 10000
channel_id: 0
message_type: broadcast
visibility: system
priority: critical
system_version: 4.0.42
created_ymdhis: {current_time}
delegation_chain: "10000:1003"
tags: [doctrine, system, canonical]
---
"""
    
    # Calculate checksum for footer
    checksum = hashlib.sha256(doc['content'].encode()).hexdigest()
    
    footer = f"""
<!-- FLIP_FOOTER_BEGIN
{{
  "import_checksum": "{checksum}",
  "validation_marker": "VALIDATED_BY_ANTIGRAVITY",
  "version": "4.0.42",
  "last_verified": "20260224",
  "last_verified_by": "antigravity"
}}
FLIP_FOOTER_END -->
"""
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(header + doc['content'] + footer)

print(f"Authored 10 doctrines in {output_dir}")

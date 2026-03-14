import re

def remove_table_block(content, table_name):
    # Matches CREATE TABLE up to the trailing semicolon, and any subsequent CREATE ... INDEX ... on that table
    pattern = r"(?:-- [^\n]+\n)*\s*CREATE TABLE\s+(?:IF NOT EXISTS\s+)?" + table_name + r"\s+\([\s\S]*?\);\n(?:CREATE(?:\s+UNIQUE)?\s+INDEX\s+[a-zA-Z0-9_]+\s+ON\s+" + table_name + r"\s+\([^\)]+\);\n)*"
    # Also match the comment block header before it if present
    header_pattern = r"-- =+[\r\n]+-- " + table_name + r"[^\r\n]*[\r\n]+-- =+[\r\n]+" + pattern
    
    # Try with header first
    new_content, count = re.subn(header_pattern, "", content, flags=re.MULTILINE)
    if count == 0:
        new_content, count = re.subn(pattern, "", content, flags=re.MULTILINE)
        
    return new_content

with open("lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", "r", encoding="utf-8") as f:
    future_sql = f.read()

with open("lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", "r", encoding="utf-8") as f:
    install_sql = f.read()

tables_to_remove_from_future = [
    "lupo_orchestrator_rules",
    "lupo_flare_headers",
    "lupo_comments",
    "lupo_hashtags",
    "lupo_anubis_orphaned",
    "lupo_temporal_coherence_snapshots",
    "lupo_system_health_snapshots",
    "lupo_anubis_deletion_log",
    "lupo_anubis_mirrored",
    "lupo_anubis_revised"
]

# Extract orchestrator rules before removing
orch_match = re.search(r"-- =+[\r\n]+-- lupo_orchestrator_rules[^\r\n]*[\r\n]+-- =+[\r\n]+\s*CREATE TABLE\s+(?:IF NOT EXISTS\s+)?lupo_orchestrator_rules\s+\([\s\S]*?\);\n(?:CREATE(?:\s+UNIQUE)?\s+INDEX\s+[a-zA-Z0-9_]+\s+ON\s+lupo_orchestrator_rules\s+\([^\)]+\);\n)*", future_sql, flags=re.MULTILINE)

orch_ddl = orch_match.group(0) if orch_match else ""

for t in tables_to_remove_from_future:
    future_sql = remove_table_block(future_sql, t)

# Add lupo_orchestrator_rules to install_new_lupopedia.sql if not there
if "CREATE TABLE lupo_orchestrator_rules" not in install_sql and orch_ddl:
    install_sql += "\n" + orch_ddl

# Add Unified Anubis and Snapshot tables back to future_features
unified_anubis = """
-- =============================================================================
-- lupo_anubis_operations (v4.0.74 unified ANUBIS log for mirrored, orphaned, revised, deleted)
-- =============================================================================
CREATE TABLE lupo_anubis_operations (
  operation_id bigint NOT NULL AUTO_INCREMENT,
  operation_type varchar(64) NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  channel_id bigint NOT NULL DEFAULT 42,
  actor_id bigint NOT NULL,
  faucet_id bigint DEFAULT NULL,
  details_json text DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (operation_id)
);
CREATE INDEX lupo_anubis_operations_idx_target ON lupo_anubis_operations (target_type, target_id);
CREATE INDEX lupo_anubis_operations_idx_type ON lupo_anubis_operations (operation_type);
CREATE INDEX lupo_anubis_operations_idx_created ON lupo_anubis_operations (created_ymdhis);
"""

unified_snapshots = """
-- =============================================================================
-- lupo_system_health_snapshots (v4.0.74 unified table for system and temporal snapshots)
-- =============================================================================
CREATE TABLE lupo_system_health_snapshots (
  snapshot_id bigint NOT NULL AUTO_INCREMENT,
  snapshot_type varchar(64) NOT NULL,
  actor_id bigint NOT NULL,
  table_count bigint DEFAULT '0',
  schema_hash varchar(255) DEFAULT NULL,
  utc_anchor varchar(14) DEFAULT NULL,
  metadata_json text DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT '0',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (snapshot_id)
);
CREATE INDEX lupo_system_health_snapshots_idx_created ON lupo_system_health_snapshots (created_ymdhis);
CREATE INDEX lupo_system_health_snapshots_idx_type ON lupo_system_health_snapshots (snapshot_type);
"""

future_sql += "\n" + unified_anubis + "\n" + unified_snapshots

with open("lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", "w", encoding="utf-8") as f:
    f.write(future_sql)

with open("lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", "w", encoding="utf-8") as f:
    f.write(install_sql)

print("Refactoring SQL DDLs done!")

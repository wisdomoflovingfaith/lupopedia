import os
import re

# Minimal tables from minimal_tables.md (Full Minimal Set)
MINIMAL_TABLES = [
    "lupo_actor_channel_roles", "lupo_actor_channels", "lupo_actor_departments",
    "lupo_actor_reply_templates", "lupo_actors", "lupo_agent_context_snapshots",
    "lupo_agent_dependencies", "lupo_agent_experiences", "lupo_agent_external_events",
    "lupo_agent_faucet_credentials", "lupo_agent_faucets", "lupo_agent_files",
    "lupo_agent_heartbeats", "lupo_agent_tool_calls", "lupo_agent_versions",
    "lupo_agents", "lupo_analytics_paths", "lupo_analytics_visits_daily",
    "lupo_analytics_visits_monthly", "lupo_atoms", "lupo_audit_log", "lupo_auth_users",
    "lupo_banned_actors", "lupo_channel_content", "lupo_channel_escalation_rules",
    "lupo_channel_escalations", "lupo_channel_files", "lupo_channel_state", "lupo_channels",
    "lupo_collection_tab_map", "lupo_collection_tab_paths", "lupo_collection_tabs",
    "lupo_collections", "lupo_contents", "lupo_contexts", "lupo_contexts_map",
    "lupo_crafty_syntax_auto_invite", "lupo_crafty_syntax_chat_mod_departments",
    "lupo_crafty_syntax_chat_questions", "lupo_crafty_syntax_layer_invites",
    "lupo_crafty_syntax_leave_message", "lupo_crm_lead_messages", "lupo_crm_leads",
    "lupo_department_metadata", "lupo_department_roles", "lupo_departments",
    "lupo_dialog_channels", "lupo_dialog_messages", "lupo_dialog_threads",
    "lupo_event_metadata", "lupo_federation_categories", "lupo_federation_category_map",
    "lupo_federation_nodes", "lupo_help_topics", "lupo_help_tree", "lupo_metadata",
    "lupo_modules", "lupo_registry", "lupo_referers", "lupo_semantic_index",
    "lupo_sessions", "lupo_system_config", "lupo_truth_answers", "lupo_truth_knowledge",
    "lupo_visits"
]

def find_imported_tables(import_sql):
    tables = []
    with open(import_sql, 'r', encoding='utf-8') as f:
        content = f.read()
        matches = re.findall(r'INSERT INTO\s+(`?lupo_[a-zA-Z0-9_]+`?)\s*\(', content, re.IGNORECASE)
        for m in matches:
            tables.append(m.replace('`', ''))
        matches = re.findall(r'TRUNCATE\s+(`?lupo_[a-zA-Z0-9_]+`?)\b', content, re.IGNORECASE)
        for m in matches:
            tables.append(m.replace('`', ''))
    return list(set(tables))

def find_tables_in_files(directory, extensions, table_names):
    used_tables = set()
    for root, dirs, files in os.walk(directory):
        if '.git' in root or 'node_modules' in root: continue
        if '.gemini' in root or '.system_generated' in root: continue
        for file in files:
            if any(file.endswith(ext) for ext in extensions):
                path = os.path.join(root, file)
                try:
                    with open(path, 'r', encoding='utf-8', errors='ignore') as f:
                        content = f.read()
                        for table in table_names:
                            if table in content:
                                used_tables.add(table)
                except:
                    pass
    return used_tables

# Path to files
sql_path = r'c:\ServBay\www\servbay\lupopedia\lupo-database\lupopedia\mysql\install\install_new_lupopedia.sql'
import_sql = r'c:\ServBay\www\servbay\lupopedia\lupo-database\lupopedia\mysql\import\import_from_old_crafty_syntax.sql'
project_root = r'c:\ServBay\www\servbay\lupopedia'

# All tables in install_new_lupopedia.sql
def list_sql_tables(sql_file):
    tables = []
    with open(sql_file, 'r', encoding='utf-8') as f:
        content = f.read()
        matches = re.findall(r'CREATE TABLE\s+(`?lupo_[a-zA-Z0-9_]+`?)\s*\(', content, re.IGNORECASE)
        for m in matches:
            tables.append(m.replace('`', ''))
    return tables

all_tables = sorted(list_sql_tables(sql_path))
imported_tables = find_imported_tables(import_sql)

# Also check seed files
seed_dir = r'c:\ServBay\www\servbay\lupopedia\lupo-database\lupopedia\mysql\seed'
seeded_tables = find_tables_in_files(seed_dir, ['.sql'], all_tables)

# Find tables used in PHP and PY
php_py_used = find_tables_in_files(project_root, ['.php', '.py'], all_tables)

# Combine all "used" sources
all_used = set(MINIMAL_TABLES) | set(imported_tables) | set(seeded_tables) | php_py_used

# Tables to remove (unused)
to_remove = [t for t in all_tables if t not in all_used]

print("Tables in Minimal Set: " + str(len(MINIMAL_TABLES)))
print("Imported/Truncated Tables: " + str(len(imported_tables)))
print("Seeded Tables: " + str(len(seeded_tables)))
print("PHP/PY used: " + str(len(php_py_used)))
print("\nTotal tables to keep: " + str(len(all_used & set(all_tables))))
print("Total tables to move: " + str(len(to_remove)))

print("\nTables to move to future_features_lupopedia.sql:")
for t in sorted(to_remove):
    print("- " + t)

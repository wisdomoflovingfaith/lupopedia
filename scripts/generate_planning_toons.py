import re
import os
from datetime import datetime

# Correct paths relative to script location or absolute
# Using absolute paths as per instructions for tools, but the logic should handle them correctly.
sql_file = r'C:\ServBay\www\servbay\lupopedia\lupo-database\lupopedia\mysql\install\future_features_lupopedia.sql'
output_dir = r'C:\ServBay\www\servbay\lupopedia\lupo-docs\database\lupopedia\tables\active\planning'

# Ensure output directory exists (though it was created in previous steps)
if not os.path.exists(output_dir):
    os.makedirs(output_dir)

with open(sql_file, 'r', encoding='utf-8') as f:
    content = f.read()

# Pattern for CREATE TABLE blocks
table_pattern = re.compile(r'CREATE TABLE\s+(?:`?)(lupo_[a-zA-Z0-9_]+)(?:`?)\s*\((.*?)\);', re.DOTALL | re.IGNORECASE)

date_str = datetime.utcnow().strftime('%Y%m%d')

matches = table_pattern.findall(content)

for table_name, body in matches:
    filename = f'table_{table_name}.toon.md'
    filepath = os.path.join(output_dir, filename)
    
    full_sql = f'CREATE TABLE {table_name} (\n{body.strip()}\n);'
    
    # Extract columns and properties
    columns = []
    lines = body.strip().split('\n')
    for line in lines:
        line = line.strip()
        if not line or line.startswith('--') or line.startswith('PRIMARY KEY') or line.startswith('KEY') or line.startswith('UNIQUE') or line.startswith('INDEX'):
            continue
        # Simplistic column parsing
        parts = line.split(maxsplit=2)
        if len(parts) >= 1:
            col_name = parts[0].strip('`,')
            col_type = parts[1].strip(',') if len(parts) > 1 else 'unknown'
            null_status = 'NO' if 'NOT NULL' in line.upper() else 'YES'
            key = 'PRI' if line.strip().startswith(col_name) and 'PRIMARY KEY' in line.upper() else ''
            default = 'NULL'
            if 'DEFAULT' in line.upper():
                default_match = re.search(r'DEFAULT\s+([^\s,]+)', line, re.IGNORECASE)
                if default_match:
                    default = default_match.group(1).strip("'")
            columns.append((col_name, col_type, null_status, key, default))

    column_rows = ""
    for col in columns:
        column_rows += f"| {col[0]} | {col[1]} | {col[2]} | {col[3]} | {col[4]} | |\n"

    md_content = f"""---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  system_version: "4.0.73"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/planning/{filename}"
  last_modified_utc: "{date_str}"
  channel_id: 42
  actor_id: 1003
  actor_name: "antigravity"
  artifact_type: "database_schema"
  artifact_kind: "planning"
  purpose: "Planned Lupopedia database table: {table_name}"
  mood_rgb: "4169E1"
  traits: ["planning", "database", "table", "future_feature"]
  tags: ["database", "table", "planning", "lupopedia"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of outbound edges for files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent."
  meta: "Thread: Finalize 4.0.72 -> Push to GitHub -> Initialize 4.0.73 -> Migrate Tasks -> Validate Upgrade Path"
  outbound_edges:
    - {{ to: "lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql", type: "schema_reference", weight: 1.0 }}
  semantic_tags: ["planning", "database", "table", "future_feature"]

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 -> Push to GitHub -> Initialize 4.0.73 -> Migrate Tasks -> Validate Upgrade Path"
  views: 0
  like_count: 0
  share_count: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "{date_str}"
  last_verified_by: "antigravity"
  orchestrator: "antigravity"
  next_action:
    - "Monitor this table for implementation readiness"
    - "Review schema for doctrine compliance"
---

# Planned Table: `{table_name}`

## Status
PLANNING — not yet implemented.

## Source
Defined in: `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`

## SQL Definition

```sql
{full_sql}
```

## Purpose
Explain the intended function of the table based on its structure and naming.

## Columns
| Column | Type | Null | Key | Default | Description |
|--------|------|------|-----|---------|-------------|
{column_rows}

## Notes
- This table is not yet installed in the production schema.
- It represents a future Lupopedia capability.
"""
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(md_content)

print(f"Generated {len(matches)} TOON files.")

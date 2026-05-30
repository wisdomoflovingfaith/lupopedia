---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/documentation_as_data_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/documentation_as_data_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: documentation
  channel_key: null
  federation_node_id: 0
  thread_key: documentation-as-data-doctrine
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# Documentation as Data Doctrine

## Core Principle

**Files and database tables are two views of the same documentation.**

- The file system provides human-readable, version-controlled documentation
- The database provides queryable, relational documentation
- They are always kept in sync via `import_content.py`

**They are NOT separate systems. They are the SAME documentation in two forms.**

---

## The Unified Documentation System

```
┌-------------------------------------------------------------+
|                     DOCUMENTATION                           |
+-----------------------------+-------------------------------┤
|      FILE VIEW              |         DATABASE VIEW         |
+-----------------------------+-------------------------------┤
| docs/prd/26_*.md       | lupo_contents (content rows)   |
| lupopedia.headers YAML      | lupo_metadata (property rows)  |
| lupopedia.edges YAML        | lupo_edges (edge rows)         |
| discussions/*.md            | lupo_dialog_messages           |
| authors.md                  | lupo_actor_auth_users         |
| threads/THREAD_INDEX.md     | lupo_dialog_threads           |
+-----------------------------+-------------------------------+
```

---

## Complete Mapping Table

| File Concept | Database Table | Sync Direction | Primary Key Link |
|--------------|----------------|----------------|------------------|
| **Markdown file** | `lupo_contents` | File → DB | `content_id` |
| `lupopedia.headers` | `lupo_metadata` | File → DB | `content_id` |
| `lupopedia.edges` | `lupo_edges` | File → DB | `edge_id` |
| **Discussion thread** | `lupo_dialog_threads` | File → DB | `dialog_thread_id` |
| **Discussion message** | `lupo_dialog_messages` | File → DB | `dialog_message_id` |
| `authors.md` table | `lupo_actor_auth_users` | File → DB | `actor_auth_user_id` |
| `when_updated` header | `lupo_contents.updated_ymdhis` | File → DB | `content_id` |
| `content_id` header | `lupo_contents.content_id` | DB → File | `content_id` |
| `actor_id` header | `lupo_contents.actor_id` | File → DB | `actor_id` |
| `channel_id` header | `lupo_contents.channel_id` | File → DB | `channel_id` |
| `department_id` | `lupo_contents.department_id` | File → DB | `department_id` |

---

## The Sync Process

### import_content.py Workflow

```python
# 1. Read file
file_content = read_markdown_file("/docs/prd/26_five_layer_documentation_architecture.md")

# 2. Parse headers → UPDATE lupo_contents + lupo_metadata
headers = parse_lupopedia_headers(file_content)
sql = """
    UPDATE lupo_contents 
    SET title = ?, actor_id = ?, channel_id = ?, department_id = ?,
        updated_ymdhis = ?
    WHERE content_id = ?
"""

# 3. Parse edges → UPDATE lupo_edges
edges = parse_lupopedia_edges(file_content)
for edge in edges:
    sql = """
        INSERT INTO lupo_edges 
        (left_object_type, left_object_id, right_object_type, right_object_id,
         edge_type, actor_id, created_ymdhis)
        VALUES ('content', ?, 'content', ?, ?, ?, ?)
    """

# 4. Parse discussions → UPDATE lupo_dialog_threads + lupo_dialog_messages
discussions = parse_discussions(file_content)
for msg in discussions:
    sql = """
        INSERT INTO lupo_dialog_messages 
        (dialog_thread_id, from_actor_id, message_text, message_body, created_ymdhis)
        VALUES (?, ?, ?, ?, ?)
    """

# 5. Write content_id back to file header (if new)
if not headers.get('content_id'):
    content_id = generate_content_id()
    update_file_header(file_path, {'content_id': content_id})
```

### Key Point: **Sync, Not Duplicate**

The database is NOT a copy of the files. It is the same documentation stored in a queryable format. When you query the database, you are querying the documentation itself.

---

## 5W1H in Both Views

### File View (Human-Readable)
```yaml
---
author:
  type: "actor"
  id: 102
  name: "CURSOR"
when_updated: "20260402220000"
---
lupopedia.edges:
  outbound_edges:
    - to: "/docs/prd/16_lupopedia_headers.md"
      type: references
      weight: 1.0
```

### Database View (Queryable)
```sql
-- WHO
SELECT actor_id FROM lupo_contents WHERE content_id = 12345;

-- WHAT
SELECT title, content FROM lupo_contents WHERE content_id = 12345;

-- WHERE (relationships)
SELECT * FROM lupo_edges 
WHERE left_object_type = 'content' 
  AND left_object_id = 12345;

-- WHEN
SELECT updated_ymdhis FROM lupo_contents WHERE content_id = 12345;

-- WHY (discussions)
SELECT message_body FROM lupo_dialog_messages 
WHERE dialog_thread_id = (
  SELECT dialog_thread_id FROM lupo_dialog_threads 
  WHERE title LIKE '%PRD 26%'
);
```

---

## Querying Your Documentation

Instead of grepping files, you can SQL-query your documentation:

### Find All PRDs About Documentation Architecture
```sql
SELECT c.content_id, c.title, c.slug, c.actor_id
FROM lupo_contents c
WHERE c.content_type = 'prd' 
  AND (c.title LIKE '%documentation%' OR c.content LIKE '%architecture%')
  AND c.is_deleted = 0;
```

### Find All Edges from PRD 26
```sql
SELECT e.edge_type, e.right_object_type, c2.title as target_title
FROM lupo_edges e
JOIN lupo_contents c1 ON e.left_object_type = 'content' AND e.left_object_id = c1.content_id
JOIN lupo_contents c2 ON e.right_object_type = 'content' AND e.right_object_id = c2.content_id
WHERE c1.slug = '26_five_layer_documentation_architecture'
  AND e.is_deleted = 0;
```

### Find All Discussions About PRD 26
```sql
SELECT dm.message_text, dm.created_ymdhis, a.actor_name
FROM lupo_dialog_messages dm
JOIN lupo_actors a ON dm.from_actor_id = a.actor_id
JOIN lupo_dialog_threads dt ON dm.dialog_thread_id = dt.dialog_thread_id
WHERE dt.title LIKE '%PRD 26%'
  OR dt.title LIKE '%five_layer%'
  AND dm.is_deleted = 0
ORDER BY dm.created_ymdhis;
```

### Get Complete Documentation Graph
```sql
-- Get all nodes and edges for documentation architecture
SELECT 
  c1.content_id,
  c1.title as from_title,
  e.edge_type,
  c2.title as to_title,
  e.weight_score
FROM lupo_edges e
JOIN lupo_contents c1 ON e.left_object_type = 'content' AND e.left_object_id = c1.content_id
JOIN lupo_contents c2 ON e.right_object_type = 'content' AND e.right_object_id = c2.content_id
WHERE c1.content_type IN ('prd', 'doctrine')
  AND c2.content_type IN ('prd', 'doctrine')
  AND e.is_deleted = 0;
```

---

## Why This Matters

### 1. No Duplication
The database is not a second copy; it's a different view of the same documentation.

### 2. Queryable Documentation
You can SQL-query your entire documentation system:
- Find all PRDs by actor
- Find all edges by type
- Find all discussions by topic
- Build documentation graphs

### 3. Relational Understanding
Edges create a graph of your documentation:
- PRD A references PRD B
- Actor X authored PRD A
- Discussion Y is about PRD A
- PRD A belongs to Department Z

### 4. Traceability
Every database row links back to a source file:
- `lupo_contents.file_path_from_root` → source file
- `content_id` in header → database row
- `dialog_thread_id` → discussion file

### 5. Performance
Complex queries that would require file system traversals become simple SQL joins.

---

## What IDE Agents Must Understand

### Critical Rules

1. **NEVER treat the database as separate from files**
   - `lupo_contents` IS the documentation
   - `lupo_edges` ARE the relationships
   - `lupo_dialog_messages` ARE the discussions

2. **ALWAYS understand the sync direction**
   - Files are the source of truth
   - Database is the queryable view
   - `import_content.py` syncs file → database

3. **USE the database for complex queries**
   - Don't grep files for relationships
   - Query `lupo_edges` instead
   - Join tables for complete pictures

4. **UNDERSTAND the mapping**
   - Headers → `lupo_contents` + `lupo_metadata`
   - Edges → `lupo_edges`
   - Discussions → `lupo_dialog_threads` + `lupo_dialog_messages`

5. **RESPECT content_id**
   - It's the primary key linking file ↔ database
   - Generated once, never changed
   - Written back to file header after sync

### Examples of Correct Understanding

❌ **Wrong**: "The database stores metadata about the files"
✅ **Right**: "The database IS the documentation in queryable form"

❌ **Wrong**: "I need to update both the file and the database"
✅ **Right**: "I update the file, then run import_content.py to sync"

❌ **Wrong**: "Let's grep files to find relationships"
✅ **Right**: "Let's query lupo_edges to find relationships"

---

## Implementation Guidelines

### When Creating Documentation
1. Create the file with proper headers
2. Run `import_content.py` to sync to database
3. Use `content_id` from database in file header

### When Querying Documentation
1. Use SQL for complex queries
2. Join `lupo_contents`, `lupo_edges`, `lupo_dialog_messages`
3. Always filter `is_deleted = 0`

### When Updating Documentation
1. Update the file
2. Run `import_content.py` to sync changes
3. Database automatically updates

---

## LILITH Final Assessment

```yaml
findings:
  accuracy_score: 100
  constitutional_violations: []
  security_concerns: []
  bias_detected: no
  verdict: "This doctrine clarifies the fundamental nature of Lupopedia's documentation system"
  impact: "Prevents confusion between file and database views"
  scope: "All IDE agents, documentation tools, and database queries"
```

**LILITH Sign-off:** ✅ **This doctrine establishes that files and database are two views of the same documentation. The database is not a separate system - it IS the documentation in queryable form. All IDE agents must understand this unified model.**

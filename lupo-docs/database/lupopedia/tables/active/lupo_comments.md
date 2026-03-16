---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_comments.md"
  system_version: "4.0.78"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "windsurf"
  artifact_type: "table"
  artifact_kind: "comments"
  purpose: "Comments on artifacts, documents, and content with faucet traceability"
  namespace: "content"
lupopedia.edges:
  code:
    - to: "lupo-includes/classes/CommentService.php"
      type: "implements"
      weight: 1.0
    - to: "lupo-includes/modules/api/comments-api.php"
      type: "implements"
      weight: 1.0
  documentation:
    - to: "lupo-docs/doctrine/COMMENTS_DOCTRINE.md"
      type: "documents"
      weight: 1.0
    - to: "lupo-docs/api/COMMENTS_API_REFERENCE.md"
      type: "documents"
      weight: 1.0
  schema:
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: "implements"
      weight: 1.0
lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260313"
  last_verified_by: "windsurf"
  orchestrator: "wolfie"
  next_action:
    - "Create CommentService class for CRUD operations"
    - "Implement comments API endpoints"
    - "Add comments UI components"
    - "Create seed data for comments"
---
# file: lupo_comments — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_comments

# lupo_comments (v4.0.73)

## Purpose

Stores comments on artifacts, documents, and content within Lupopedia. Supports threaded comments with parent-child relationships and faucet traceability for tracking which execution surface created each comment.

## Schema

| Column | Type | Description | Notes |
|--------|------|-------------|-------|
| comment_id | BIGINT AUTO_INCREMENT | Primary key | Auto-incrementing unique identifier |
| target_type | VARCHAR(64) | Type of target being commented on | e.g., 'artifact', 'document', 'content', 'collection' |
| target_id | BIGINT | ID of the target being commented on | References the target table's primary key |
| channel_id | BIGINT | Channel where the comment was made | Defaults to 42 (development channel) |
| actor_id | BIGINT | Actor who made the comment | Foreign key to lupo_actors |
| faucet_id | BIGINT | Faucet that created the comment | Optional, for faucet traceability |
| comment_text | TEXT | The comment content | Required field |
| comment_type | VARCHAR(64) | Type of comment | Defaults to 'comment' |
| parent_comment_id | BIGINT | Parent comment for threading | NULL for top-level comments |
| created_ymdhis | BIGINT | Creation timestamp | Format: YYYYMMDDHHIISS UTC |
| updated_ymdhis | BIGINT | Last update timestamp | Format: YYYYMMDDHHIISS UTC |
| is_deleted | TINYINT | Soft delete flag | 0 = active, 1 = deleted |
| deleted_ymdhis | BIGINT | Deletion timestamp | NULL if not deleted |
| metadata_json | JSON | Additional metadata | Optional field for extra data |

## Indexes

- `PRIMARY KEY (comment_id)` - Primary key
- `idx_target (target_type, target_id)` - For querying comments by target
- `idx_channel_id (channel_id)` - For channel-specific queries
- `idx_actor_id (actor_id)` - For actor-specific queries
- `idx_faucet_id (faucet_id)` - For faucet traceability queries
- `idx_parent_comment_id (parent_comment_id)` - For threaded comments
- `idx_created_ymdhis (created_ymdhis)` - For chronological ordering
- `idx_is_deleted (is_deleted)` - For filtering active comments

## Usage Examples

### Basic Comment Insert

```sql
INSERT INTO lupo_comments (
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  'artifact',
  123,
  42,
  1,
  101,
  'This is a great implementation!',
  'comment',
  20260313143000,
  20260313143000
);
```

### Threaded Comment

```sql
INSERT INTO lupo_comments (
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  parent_comment_id,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  'artifact',
  123,
  42,
  1000,
  101,
  'I agree with the above comment.',
  'comment',
  456,  -- parent_comment_id
  20260313143100,
  20260313143100
);
```

### Query Comments for Target

```sql
SELECT 
  c.comment_id,
  c.comment_text,
  c.created_ymdhis,
  a.actor_name,
  f.faucet_name
FROM lupo_comments c
JOIN lupo_actors a ON c.actor_id = a.actor_id
LEFT JOIN lupo_faucets f ON c.faucet_id = f.faucet_id
WHERE c.target_type = 'artifact'
  AND c.target_id = 123
  AND c.is_deleted = 0
ORDER BY c.created_ymdhis ASC;
```

## Doctrine Compliance

- **No foreign keys**: References are logical, not enforced by database
- **No triggers**: All logic handled in application code
- **Soft deletes**: Uses `is_deleted` flag instead of DELETE operations
- **Timestamps**: All timestamps in `YYYYMMDDHHIISS` UTC format
- **Faucet traceability**: Optional `faucet_id` field for tracking execution surfaces

## Related Tables

- `lupo_actors` - For actor information
- `lupo_faucets` - For faucet information (if implemented)
- `lupo_channels` - For channel context

## Version History

- **4.0.73**: Initial implementation with faucet traceability support

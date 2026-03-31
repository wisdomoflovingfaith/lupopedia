---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/versions/4.0.93/REQUIRED_CHANGES.sql.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/REQUIRED_CHANGES.sql.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "database-audit"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "audit"
  artifact_kind: "required_changes"
  purpose: "Schema corrections needed for doctrine compliance"
  tags:
  - "database"
  - "audit"
  - "compliance"
  - "4.0.93"
  - "sql"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/DATABASE_AUDIT_REPORT.md"
      type: references
      weight: 1.0
      reason: Detailed audit results
    - to: "lupo-docs/versions/4.0.93/DATABASE_AUDIT_SUMMARY.md"
      type: references
      weight: 1.0
      reason: Executive summary of audit
    - to: "lupo-docs/versions/4.0.93/TABLE_MISMATCH_SUMMARY.md"
      type: references
      weight: 1.0
      reason: List of missing documentation
    - to: "lupo-docs/versions/4.0.93/PRD_UPDATES_REQUIRED.md"
      type: references
      weight: 1.0
      reason: PRDs that need to be created
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# Required Database Changes - 4.0.93+

Generated: 2026-03-30 14:48:53

## Schema Corrections

```sql
-- lupo_actors: Change primary key to BIGINT
```

```sql
-- lupo_agent_experiences: Change primary key to BIGINT
```

```sql
-- lupo_agent_faucet_credentials: Change primary key to BIGINT
```

```sql
-- lupo_emotional_frameworks: Change primary key to BIGINT
```

```sql
-- lupo_sessions: Change primary key to BIGINT
```

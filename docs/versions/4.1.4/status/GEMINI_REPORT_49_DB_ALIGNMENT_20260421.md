---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.4/status/GEMINI_REPORT_49_DB_ALIGNMENT_20260421.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.4/status/GEMINI_REPORT_49_DB_ALIGNMENT_20260421.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/versions/canonical/1026/04/report-49-db-alignment.toon
  atoms_toon: null
  transcript_jsonl: 0/versions/report-49-db-alignment
  artifact_type: status
  artifact_kind: report
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: status
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_49_A_QUESTIONS_AND_ANSWERS
  title: 'REPORT: PRD 49 Database Alignment Review'
  summary: Comparison of PRD 49 truth tables against install_new_lupopedia.sql with recommendations for 4.1.5.
---

# REPORT: PRD 49 Database Alignment Review

## 1. Summary of Alignment
The database installer `install_new_lupopedia.sql` currently contains definitions for `lupo_truth_questions`, `lupo_truth_answers`, and `lupo_truth_evidence`. These definitions largely align with the structural requirements of PRD 49, but lack specific metadata and integrity fields required for 4.1.5 "Tristero" compliance, specifically regarding SHA-256 evidence hashing and relationship depth tracking.

## 2. Recommended Column Changes or Additions

### 2.1 Table: lupo_truth_questions
Add `evidence_hash_aggregate` to track the state of evidence associated with the question.

```sql
ALTER TABLE {{prefix}}truth_questions 
ADD COLUMN evidence_hash_aggregate CHAR(64) DEFAULT NULL AFTER metadata_json;
```

### 2.2 Table: lupo_truth_answers
Add `is_canonical` and `truth_tier` to align with PRD 16/Atoms system.

```sql
ALTER TABLE {{prefix}}truth_answers 
ADD COLUMN is_canonical TINYINT NOT NULL DEFAULT 0 AFTER is_accepted,
ADD COLUMN truth_tier VARCHAR(32) NOT NULL DEFAULT 'standard' AFTER is_canonical;
```

### 2.3 Table: lupo_truth_evidence
Add `evidence_sha256` for deterministic integrity checks.

```sql
ALTER TABLE {{prefix}}truth_evidence 
ADD COLUMN evidence_sha256 CHAR(64) NOT NULL AFTER evidence_text;
```

## 3. Installer Improvements

### 3.1 Root and Red Team User Creation
The installer currently creates `system` (10000) and `admin` (10001). It must be updated to create `root` and `red_team` per 4.1.5 security doctrine.

```sql
-- Root User (10000)
INSERT INTO {{prefix}}auth_users (auth_user_id, username, display_name, email, auth_provider, is_active)
VALUES (10000, 'root', 'ROOT_ORCHESTRATOR', 'root@lupopedia.local', 'system', 1);

-- Red Team User (10001)
INSERT INTO {{prefix}}auth_users (auth_user_id, username, display_name, email, auth_provider, is_active)
VALUES (10001, 'red_team', 'RED_TEAM_AUDITOR', 'redteam@lupopedia.local', 'system', 1);
```

### 3.2 LLM API Key Prompting
The `install.php` logic must be extended to prompt for and store keys in `lupo_system_config` during the fresh install phase.

```sql
INSERT INTO {{prefix}}system_config (config_key, config_value, actor_id, created_ymdhis, updated_ymdhis)
VALUES 
('llm_provider_default', 'openai', 10000, @now, @now),
('openai_api_key', 'PASTE_KEY_HERE', 10000, @now, @now),
('anthropic_api_key', 'PASTE_KEY_HERE', 10000, @now, @now);
```

### 3.3 Crafty Syntax User Mapping
The installer must enforce the ID split: Crafty Syntax legacy users (`livehelp_users`) MUST be imported with `user_id < 10000`.

```sql
-- Example import constraint logic for install.php
-- $legacy_id = $row['user_id'];
-- if ($legacy_id >= 10000) { throw new Exception("Legacy ID collision in user_id space"); }
```

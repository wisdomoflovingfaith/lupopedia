---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/versions/4.1.4/status/REPORT_49_DB_ALIGNMENT_20260421.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.4/status/REPORT_49_DB_ALIGNMENT_20260421.md"
  status: "active"
  when_updated: "20260421215003"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/report-49-db-alignment-20260421"
  artifact_type: documentation
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: documentation
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_49_A_QUESTIONS_AND_ANSWERS"
  title: "PRD 49 DB Alignment Report -- 2026-04-21"
  summary: "Architecture audit of PRD 49 truth tables against install_new_lupopedia.sql. Identifies column gaps, naming drift, evidence nullability bug, and installer gaps for May 1 Crafty Syntax delivery."
---

# PRD 49 DB Alignment Report -- 2026-04-21

**Auditor:** Actor 116 (Claude Code)
**Scope:** PRD 49 (Questions and Answers System) vs install_new_lupopedia.sql
**Delivery target:** May 1, 2026 -- Crafty Syntax import baseline
**Status:** FINDINGS READY -- ACTION REQUIRED


---

## 1. Summary

PRD 49 defines three truth tables: `lupo_truth_questions`, `lupo_truth_answers`, and
`lupo_truth_evidence`. The installer creates these as `{{prefix}}truth_questions`,
`{{prefix}}truth_answers`, and `{{prefix}}truth_evidence`. With the standard `lupo_` prefix
the names resolve correctly.

The installer schema is substantially richer than what PRD 49 currently specifies. Most
installer additions are legitimate and should be ratified into PRD 49. However, three
critical defects exist that will block correct operation:

1. `truth_evidence.truth_answer_id` is `NOT NULL` in the installer but PRD 49 §2.3
   explicitly requires it to be nullable (evidence can attach to a question before any
   answer exists).

2. `truth_evidence` has no `truth_question_id` column. Without it, querying "all evidence
   for a question" requires a join through answers and silently drops question-only evidence.

3. `truth_evidence` has no `evidence_hash` column. PRD 49 §7.2 mandates a SHA-256 hash on
   every evidence row and states that the validator MUST check this. THOTH alerts are
   triggered on hash mismatch. This field cannot be deferred.

Beyond these critical items, five non-critical naming drifts need PRD 49 to be updated to
match the installer (the installer names are better and already in production SQL).

For the installer itself, three gaps require resolution before May 1:
- No LLM API key storage table referenced by `agent_llm_configs.api_key_id`.
- `crafty_user_mapping` uses `created_at` / `updated_at` column names -- timestamp doctrine
  violation; must be `created_ymdhis` / `updated_ymdhis`.
- `crafty_user_mapping` has no soft-delete columns (`is_deleted`, `deleted_ymdhis`).


---

## 2. Table Comparison and Recommended Changes

### 2.1 lupo_truth_questions

#### Critical gaps -- must add to installer

**A. question_slug**

PRD 49 §3 defines the questions_toon file path as
`memory/{channel_key}/questions/{YYYY}/{MM}/{slug}.questions.toon`. The `slug` comes
from `question_slug`. Without this column, toon file paths are unresolvable.

```sql
-- Add to CREATE TABLE {{prefix}}truth_questions after question_text:
question_slug varchar(255) DEFAULT NULL,

-- Add index:
CREATE INDEX {{prefix}}truth_questions_idx_slug ON {{prefix}}truth_questions (question_slug);
```

**B. merged_into_question_id**

PRD 49 §7.1 defines a "merged" question lifecycle status where `merged_into_question_id`
records the target. The installer has no such column, making the "merged" status record
incomplete -- there is nowhere to store the merge destination.

```sql
-- Add to CREATE TABLE {{prefix}}truth_questions after question_status:
merged_into_question_id bigint DEFAULT NULL,
```

#### Non-critical naming drift -- update PRD 49 to match installer

The installer uses better, consistent names throughout. PRD 49 must be updated to align.
No schema changes needed; PRD edits only.

| PRD 49 name          | Installer name              | Action               |
|----------------------|-----------------------------|----------------------|
| question_id          | truth_question_id           | Update PRD 49        |
| asked_ymdhis         | created_ymdhis              | Update PRD 49        |
| channel_key VARCHAR  | asked_in_channel_id BIGINT  | Update PRD 49        |
| thread_id VARCHAR    | asked_in_thread_id BIGINT   | Update PRD 49        |
| status               | question_status             | Update PRD 49        |
| context_json         | metadata_json               | Update PRD 49        |

The installer also adds `parent_question_id`, `root_question_id`, `depth`,
`target_object_type`, `target_object_id`, `question_summary`, `asked_in_session_id`,
`is_answered`, `is_featured`, `view_count`, `answer_count`, `follower_count`,
`updated_ymdhis`, `answered_ymdhis`, and `closed_ymdhis`. These are legitimate richer
fields. PRD 49 should ratify them in a documentation pass after May 1. They do not block
delivery.


---

### 2.2 lupo_truth_answers

#### Non-critical naming drift -- update PRD 49 to match installer

No schema changes needed. PRD 49 edits only.

| PRD 49 name       | Installer name          | Action        |
|-------------------|-------------------------|---------------|
| answer_id         | truth_answer_id         | Update PRD 49 |
| question_id       | truth_question_id       | Update PRD 49 |
| answered_ymdhis   | created_ymdhis          | Update PRD 49 |
| is_canonical      | is_accepted             | Update PRD 49 |
| context_json      | metadata_json           | Update PRD 49 |

Note on `is_canonical` vs `is_accepted`: The installer's `is_accepted` is the correct
name (it reflects a vote/acceptance workflow via `acceptance_votes` and `rejection_votes`).
PRD 49 §2.2 should use `is_accepted` and document that "canonical = is_accepted = 1 AND
confidence_score >= threshold". The canonical determination is still a PHP-layer rule.


---

### 2.3 lupo_truth_evidence

#### CRITICAL DEFECT 1 -- truth_answer_id must be nullable

Current installer:
```sql
truth_answer_id bigint NOT NULL,
```

Required (PRD 49 §2.3: "NULL if evidence for question only"):
```sql
truth_answer_id bigint DEFAULT NULL,
```

**Impact:** Without this fix, no evidence can be attached to a question before at least one
answer exists. This breaks the Crafty Syntax import path where legacy Q&A files often have
evidence attached at the question level with no associated answer.

**Fix:**
```sql
-- In CREATE TABLE {{prefix}}truth_evidence, change:
truth_answer_id bigint DEFAULT NULL,
```

#### CRITICAL DEFECT 2 -- missing truth_question_id

The installer has no `truth_question_id` column on `truth_evidence`. PRD 49 §2.3 requires
it. Without it:
- Cannot retrieve all evidence for a question without joining through answers (which fails
  for question-only evidence after DEFECT 1 is fixed).
- Graph edge queries (PRD 38 "evidence_supports_answer" and direct question evidence) have
  no direct index.

**Fix:**
```sql
-- Add to CREATE TABLE {{prefix}}truth_evidence after truth_answer_id:
truth_question_id bigint DEFAULT NULL,

-- Add index:
CREATE INDEX {{prefix}}truth_evidence_idx_question ON {{prefix}}truth_evidence (truth_question_id);
```

PHP application must set `truth_question_id` from the parent answer's `truth_question_id`
when `truth_answer_id` is non-null, or directly from the question when `truth_answer_id`
is null.

#### CRITICAL DEFECT 3 -- missing evidence_hash

PRD 49 §7.2:
> "All evidence must have SHA-256 hash. Validator checks evidence_location exists and hash
> matches. Broken evidence triggers [ALERT] via THOTH."

The installer has no `evidence_hash` column. The validator cannot be implemented without it.

**Fix:**
```sql
-- Add to CREATE TABLE {{prefix}}truth_evidence after evidence_excerpt (or before reliability_score):
evidence_hash varchar(255) DEFAULT NULL,

-- Add index for integrity checks:
CREATE INDEX {{prefix}}truth_evidence_idx_hash ON {{prefix}}truth_evidence (evidence_hash);
```

The hash is computed in PHP over the canonical evidence content (source_url + evidence_text
concatenated as UTF-8 bytes). NULL hash is permitted only when evidence_text and source_url
are both null (degenerate case). THOTH validation skips null-hash rows unless is_verified = 1.

#### Non-critical naming drift -- update PRD 49 to match installer

No schema changes beyond the three fixes above. PRD 49 edits only.

| PRD 49 name           | Installer name              | Action        |
|-----------------------|-----------------------------|---------------|
| evidence_id           | truth_evidence_id           | Update PRD 49 |
| evidence_location     | source_url + source fields  | Update PRD 49 |
| provided_by_actor_id  | submitted_by_actor_id       | Update PRD 49 |
| provided_ymdhis       | created_ymdhis              | Update PRD 49 |

The installer replaces `evidence_location TEXT` with a richer polymorphic structure:
`source_object_type VARCHAR(64)`, `source_object_id BIGINT`, `source_federation_node_id`,
`source_url VARCHAR(2000)`, `source_title VARCHAR(500)`, `evidence_text TEXT`,
`evidence_excerpt VARCHAR(1000)`. This is correct and superior. PRD 49 §2.3 must be
updated to document this structure. It does not block May 1.


---

## 3. Installer Changes Needed

### 3.1 Root User (10000) and Red Team User (10001)

**Root user (10000):** EXISTS. Seeded correctly as auth_user_id = 10000, username =
'system', auth_provider = 'system', no password. Auth user department row links to
department_id = 0 with role_key = 'system'. This is correct and complete.

**Red team user (10001):** EXISTS as auth_user_id = 10001, username = 'admin',
role_key = 'administrator'. The installer comment says "Optional admin/operator user --
default password: admin123".

Gap: There is no "red team" designation anywhere in the installer. If "red team" means
an adversarial-testing actor used to verify THOTH alerts fire correctly (per PRD 32 and
PRD 43 parent-child trust ladder), the designation must be explicit.

Recommended installer comment change (no schema change needed for May 1):

```sql
-- auth_user_id 10001: administrator / red team baseline user
-- Default password: admin123 (CHANGE IMMEDIATELY after install)
-- Role: administrator in department 0
-- Red team use: this user may be assigned adversarial test roles via lupo_actor_pairing.
-- See PRD 32 (Actor Authority) and PRD 43 (Parent-Child Trust Ladder) for red team setup.
-- PHP rule: crafty_operator_id values MUST be < 10000. All Crafty-imported users land
-- below this boundary. Users 10000 and 10001 are system-reserved and must never appear
-- in crafty_user_mapping.
```

The auth_user_departments row for 10001 already uses role_key = 'administrator'. No
schema change is required. If a dedicated red team role_key is needed, add it post-May 1.


---

### 3.2 LLM API Keys During Fresh Install

**Current state:** `lupo_agent_llm_configs.api_key_id BIGINT DEFAULT NULL` references a
key store. But there is NO `lupo_api_keys` table (or equivalent) in the installer. The
only API-adjacent tables are `lupo_api_clients`, `lupo_api_tokens`, `lupo_api_rate_limits`,
and `lupo_api_webhooks` -- these are outbound API tokens for Lupopedia's own REST API,
not storage for LLM provider keys.

**Impact:** On a fresh install, operators have no documented path to enter their Anthropic
or OpenAI key. `api_key_id` in every seeded `agent_llm_configs` row is NULL. Any agent
that invokes an LLM will fail silently or with a cryptic NULL dereference.

**Recommended fixes for May 1:**

Option A (minimal -- add a comment block at the head of the installer):

```sql
-- ============================================================
-- INSTALLER PROMPT: LLM API KEYS
-- After install, open Admin > Agent Config > LLM Providers
-- and enter your API keys for each provider you will use:
--   Anthropic (Claude): https://console.anthropic.com/keys
--   OpenAI (GPT):       https://platform.openai.com/api-keys
-- Keys are stored in {{prefix}}api_keys (added below) and
-- referenced by {{prefix}}agent_llm_configs.api_key_id.
-- Agents without a resolved api_key_id will refuse to execute.
-- ============================================================
```

Option B (correct -- add the missing lupo_api_keys table):

```sql
CREATE TABLE {{prefix}}api_keys (
  api_key_id        bigint        NOT NULL,
  actor_id          bigint        NOT NULL,
  key_name          varchar(100)  NOT NULL,
  provider          varchar(50)   NOT NULL DEFAULT 'anthropic',
  key_value_hash    varchar(255)  NOT NULL,
  key_value_preview varchar(12)   DEFAULT NULL,
  is_active         tinyint       NOT NULL DEFAULT 1,
  created_ymdhis    bigint        NOT NULL,
  updated_ymdhis    bigint        NOT NULL,
  is_deleted        tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis    bigint        DEFAULT NULL,
  PRIMARY KEY (api_key_id)
);
CREATE INDEX {{prefix}}api_keys_idx_actor    ON {{prefix}}api_keys (actor_id);
CREATE INDEX {{prefix}}api_keys_idx_provider ON {{prefix}}api_keys (provider);
CREATE INDEX {{prefix}}api_keys_idx_active   ON {{prefix}}api_keys (is_active);
CREATE INDEX {{prefix}}api_keys_idx_deleted  ON {{prefix}}api_keys (is_deleted);
```

Note: `key_value_hash` stores the SHA-256 of the raw key (for dedup detection).
`key_value_preview` stores the last 4 characters for display (e.g., "...sk-xK9f").
The raw key itself is NEVER stored in the database; it lives in a server-side secrets
file or environment variable resolved by PHP at runtime. The admin UI writes only the
hash and preview; agents fetch the raw key from the secrets file by key_name.

Option B is the correct long-term approach. Option A is sufficient to unblock May 1 if
Option B is not ready. Both options must be decided before the installer is tagged 4.1.4.


---

### 3.3 Crafty Syntax User Mapping

**Table:** `lupo_crafty_user_mapping`

**Issue A -- Timestamp doctrine violation (MUST FIX):**

The installer uses `created_at BIGINT` and `updated_at BIGINT`. The timestamp doctrine
(CLAUDE.md Layer 1 and PRD 75) requires `BIGINT UTC YYYYMMDDHHIISS` with the `_ymdhis`
suffix on all timestamp columns. This violates the constitutional rule.

```sql
-- Change in CREATE TABLE {{prefix}}crafty_user_mapping:
created_ymdhis bigint NOT NULL DEFAULT 20250101000000,
updated_ymdhis bigint NOT NULL DEFAULT 20250101000000,

-- Change indexes accordingly:
-- DROP {{prefix}}crafty_user_mapping_idx_created (if it exists)
-- ADD CREATE INDEX {{prefix}}crafty_user_mapping_idx_created ON {{prefix}}crafty_user_mapping (created_ymdhis);
```

**Issue B -- Missing soft delete columns (MUST FIX):**

PRD constitutional rule (CLAUDE.md Layer 1): soft delete is mandatory on all tables.
`lupo_crafty_user_mapping` has neither `is_deleted` nor `deleted_ymdhis`.

```sql
-- Add to CREATE TABLE {{prefix}}crafty_user_mapping:
is_deleted      tinyint  NOT NULL DEFAULT 0,
deleted_ymdhis  bigint   DEFAULT NULL,

-- Add index:
CREATE INDEX {{prefix}}crafty_user_mapping_idx_deleted ON {{prefix}}crafty_user_mapping (is_deleted);
```

**Issue C -- PHP enforcement rule for user_id < 10000 (add comment, no schema change):**

Crafty Syntax imports users from `livehelp_users` with `user_id` (INT). The doctrine
requires that all Crafty-mapped `{{prefix}}user_id` values must be < 10000 (the system
user boundary). This must be enforced in PHP, not in the database (no CHECK constraints
per DB = Storage Only doctrine).

Add this comment to the `crafty_user_mapping` CREATE TABLE block:

```sql
-- PHP ENFORCEMENT RULE: {{prefix}}user_id mapped from Crafty livehelp_users.user_id
-- MUST be < 10000. Users 10000 and 10001 are system-reserved and must never appear here.
-- No FK. No CHECK constraint. PHP must validate before every INSERT and UPDATE.
-- See PRD 85 (Crafty Syntax Import) for full mapping doctrine.
```

**Issue D -- No FK is correct:**

The UNIQUE indexes on `crafty_operator_id` and `{{prefix}}user_id` enforce one-to-one
mapping at the database level without FK. This is correct and doctrine-compliant.
No change needed.


---

## 4. Next Steps

Priority order for May 1 delivery:

| # | Item | File to change | Urgency |
|---|------|---------------|---------|
| 1 | Make truth_evidence.truth_answer_id nullable | install_new_lupopedia.sql | CRITICAL |
| 2 | Add truth_question_id to truth_evidence | install_new_lupopedia.sql | CRITICAL |
| 3 | Add evidence_hash to truth_evidence | install_new_lupopedia.sql | CRITICAL |
| 4 | Add question_slug to truth_questions | install_new_lupopedia.sql | REQUIRED |
| 5 | Add merged_into_question_id to truth_questions | install_new_lupopedia.sql | REQUIRED |
| 6 | Fix created_at/updated_at in crafty_user_mapping | install_new_lupopedia.sql | REQUIRED |
| 7 | Add is_deleted/deleted_ymdhis to crafty_user_mapping | install_new_lupopedia.sql | REQUIRED |
| 8 | Decide Option A vs Option B for LLM API keys | install_new_lupopedia.sql | REQUIRED |
| 9 | Update PRD 49 column names to match installer | 49_A_QUESTIONS_AND_ANSWERS_SYSTEM.md | REQUIRED |
| 10 | Update PRD 49 evidence table to ratify richer installer schema | 49_A_QUESTIONS_AND_ANSWERS_SYSTEM.md | REQUIRED |
| 11 | Add red team / PHP constraint comments to installer | install_new_lupopedia.sql | RECOMMENDED |
| 12 | Ratify installer-only columns into PRD 49 body | 49_A_QUESTIONS_AND_ANSWERS_SYSTEM.md | POST-MAY-1 |

Items 1-3 are blocking. A Crafty import run against the current installer will fail or
silently corrupt evidence integrity the moment any legacy evidence entry lacks an answer
parent or requires a hash check.

Items 6-7 are constitutional violations that will trip the validator and THOTH. They
should be fixed in the same pass as items 1-5.

Item 8 (API keys) is a deployment blocker for any agent that uses an LLM. It does not
block database schema correctness but will block the first live Crafty import run if
agents are involved.

---

lupopedia.footer:
  generated_by: "claude-code-116"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260421215003"

/*
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/versions/4.1.4/patches/20260421_prd49_critical_fixes.sql"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.4/patches/20260421_prd49_critical_fixes.sql"
  status: "active"
  when_updated: "20260421215003"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/patch-prd49-critical-fixes-20260421"
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
  title: "Patch: PRD 49 Critical DB Fixes -- 2026-04-21"
  summary: "ALTER TABLE patch for 5 blocking defects in truth tables + 2 constitutional fixes in crafty_user_mapping. Unblocks May 1 Crafty Syntax delivery. Apply against a fresh install OR a live 4.1.4 dev database."
*/

-- ============================================================
-- PATCH: PRD 49 Critical Fixes -- 2026-04-21
-- Target version: Lupopedia 4.1.4
-- Apply to: fresh install_new_lupopedia.sql OR live 4.1.4 dev DB
-- Idempotency: NOT idempotent. Run once. Guard with schema checks
--              if applying to a live DB that may already have columns.
-- No FKs. No triggers. No stored procedures. PHP enforces integrity.
-- ============================================================

-- ============================================================
-- INSTALLER CHANGES (also applied directly in install_new_lupopedia.sql)
-- This section documents what was changed in the installer so this
-- patch file and the installer remain in sync.
--
-- install_new_lupopedia.sql changes applied in same commit:
--
--   truth_questions (line ~2805):
--     + question_slug varchar(255) DEFAULT NULL  (after question_summary)
--     + merged_into_question_id bigint DEFAULT NULL  (after question_status)
--     + CREATE INDEX ..._idx_slug  (added after existing indexes)
--
--   truth_evidence (line ~2880):
--     ~ truth_answer_id bigint NOT NULL  ->  bigint DEFAULT NULL
--     + truth_question_id bigint DEFAULT NULL  (after truth_answer_id)
--     + evidence_hash varchar(255) DEFAULT NULL  (after evidence_excerpt)
--     + CREATE INDEX ..._idx_question
--     + CREATE INDEX ..._idx_hash
--
--   crafty_user_mapping (line ~1741):
--     ~ created_at bigint  ->  created_ymdhis bigint  (timestamp doctrine)
--     ~ updated_at bigint  ->  updated_ymdhis bigint  (timestamp doctrine)
--     + is_deleted tinyint NOT NULL DEFAULT 0  (soft-delete doctrine)
--     + deleted_ymdhis bigint DEFAULT NULL      (soft-delete doctrine)
--     + CREATE INDEX ..._idx_deleted
--
--   Section 7 seed comments (line ~4356):
--     ~ Replaced with canonical USER ID SPACE doctrine block:
--       0=system root, 1-9999=Crafty imports, 10000=main admin,
--       10001=red team, 10002+=IdGenerator users
--
--   After Section 7 (line ~4464):
--     + SECTION 8: POST-INSTALL CONFIGURATION (LLM API key prompt)
--
-- PRD 49 column name alignment (PRD edits only -- no schema change):
--   question_id          -> truth_question_id
--   asked_ymdhis         -> created_ymdhis
--   channel_key VARCHAR  -> asked_in_channel_id BIGINT
--   thread_id VARCHAR    -> asked_in_thread_id BIGINT
--   status               -> question_status
--   context_json         -> metadata_json
--   answer_id            -> truth_answer_id
--   question_id (answer) -> truth_question_id
--   answered_ymdhis      -> created_ymdhis
--   is_canonical         -> is_accepted
--   evidence_id          -> truth_evidence_id
--   evidence_location    -> source_url + source_object_type/id
--   provided_by_actor_id -> submitted_by_actor_id
--   provided_ymdhis      -> created_ymdhis
-- ============================================================


-- ============================================================
-- BLOCK 1: truth_questions
-- Adds question_slug (required for .questions.toon path resolution)
-- and merged_into_question_id (required for "merged" lifecycle status)
-- ============================================================

ALTER TABLE {{prefix}}truth_questions
  ADD COLUMN question_slug varchar(255) DEFAULT NULL
    AFTER question_summary,
  ADD COLUMN merged_into_question_id bigint DEFAULT NULL
    AFTER question_status;

CREATE INDEX {{prefix}}truth_questions_idx_slug
  ON {{prefix}}truth_questions (question_slug);

-- (No index on merged_into_question_id: sparse column, low cardinality,
--  looked up only during merge operations. Add if query profiling demands it.)


-- ============================================================
-- BLOCK 2: truth_evidence -- 3 critical defects
-- ============================================================

-- DEFECT 1: truth_answer_id was NOT NULL, blocking question-only evidence.
-- PRD 49 s2.3: "NULL if evidence for question only."
-- MODIFY resets the column definition; all other attributes are preserved.
ALTER TABLE {{prefix}}truth_evidence
  MODIFY COLUMN truth_answer_id bigint DEFAULT NULL;

-- DEFECT 2: No truth_question_id.
-- Without it, querying all evidence for a question requires joining through
-- answers, which silently drops question-only rows after DEFECT 1 is fixed.
-- PHP rule: when truth_answer_id is set, copy its parent truth_question_id here.
--           when truth_answer_id is NULL, set directly from the question.
ALTER TABLE {{prefix}}truth_evidence
  ADD COLUMN truth_question_id bigint DEFAULT NULL
    AFTER truth_answer_id;

CREATE INDEX {{prefix}}truth_evidence_idx_question
  ON {{prefix}}truth_evidence (truth_question_id);

-- DEFECT 3: No evidence_hash.
-- PRD 49 s7.2: "All evidence must have SHA-256 hash."
-- Computed in PHP over canonical content: sha256(source_url || evidence_text).
-- NULL is permitted only when both source_url and evidence_text are NULL.
-- THOTH skips null-hash rows unless is_verified = 1 (triggers alert if so).
ALTER TABLE {{prefix}}truth_evidence
  ADD COLUMN evidence_hash varchar(255) DEFAULT NULL
    AFTER evidence_excerpt;

CREATE INDEX {{prefix}}truth_evidence_idx_hash
  ON {{prefix}}truth_evidence (evidence_hash);


-- ============================================================
-- BLOCK 3: crafty_user_mapping -- constitutional fixes
-- ============================================================

-- Timestamp doctrine violation: created_at / updated_at must be _ymdhis.
-- Soft-delete doctrine: is_deleted + deleted_ymdhis mandatory on all tables.
ALTER TABLE {{prefix}}crafty_user_mapping
  CHANGE COLUMN created_at created_ymdhis bigint NOT NULL DEFAULT 20250101000000,
  CHANGE COLUMN updated_at updated_ymdhis bigint NOT NULL DEFAULT 20250101000000,
  ADD COLUMN is_deleted tinyint NOT NULL DEFAULT 0,
  ADD COLUMN deleted_ymdhis bigint DEFAULT NULL;

CREATE INDEX {{prefix}}crafty_user_mapping_idx_created
  ON {{prefix}}crafty_user_mapping (created_ymdhis);
CREATE INDEX {{prefix}}crafty_user_mapping_idx_deleted
  ON {{prefix}}crafty_user_mapping (is_deleted);

-- PHP ENFORCEMENT RULE (no DB constraint per doctrine):
-- {{prefix}}user_id mapped from Crafty livehelp_users.user_id MUST be < 10000.
-- Users 10000 (system) and 10001 (admin/red-team) are system-reserved and
-- must never appear in crafty_user_mapping.
-- Validate in PHP before every INSERT and UPDATE on this table.
-- See PRD 85 (Crafty Syntax Import) for full mapping doctrine.


-- ============================================================
-- END OF PATCH
-- After applying: run php lupo-bin/memory.php load-context
--                 and python lupo-bin/pending.py --actor 116 --check
-- ============================================================

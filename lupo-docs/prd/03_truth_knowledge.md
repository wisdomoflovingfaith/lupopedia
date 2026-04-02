---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/03_truth_knowledge.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/03_truth_knowledge.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for truth system and knowledge management database tables"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "truth_knowledge"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/04_tags_metadata.md"
      type: references
      weight: 1.0
      reason: "Truth items use tags and metadata"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Truth discussed in channels"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Truth System and Knowledge Management Database Tables

## Overview

**Namespace Purpose:** Implements Lupopedia's truth management system, enabling question-answer pairs, evidence collection, voting, and knowledge validation. This namespace provides the foundation for trustworthy, verifiable information.

**Primary Actors:** 
- Knowledge contributors (via lupo_truth_questions)
- Evidence providers (via lupo_truth_evidence)
- Truth validators (via lupo_truth_followers)
- Voters (via lupo_votes)
- Commenters (via lupo_comments)

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_truth_questions` | Truth questions requiring answers | `question_id` | Central to truth system |
| `lupo_truth_answers` | Answers to truth questions | `answer_id` | Links to `lupo_truth_questions` |
| `lupo_truth_evidence` | Evidence supporting answers | `evidence_id` | Links to `lupo_truth_answers` |
| `lupo_truth_followers` | Actors following truth items | `follower_id` | Links to questions and answers |
| `lupo_votes` | Voting on questions and answers | `vote_id` | Links to questions and answers |
| `lupo_comments` | Comments on truth items | `comment_id` | Links to questions, answers, evidence |
| `lupo_truth_context_map` | Links truth items to contexts | `context_map_id` | Links to `lupo_contexts` |

## Table Details

### `lupo_truth_questions`

**Purpose:** Stores truth questions that require answers and verification.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| question_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| question_text | TEXT | NO |  | The question being asked |
| question_type | VARCHAR(32) | NO | 'open' | Type: open, yes_no, multiple_choice |
| category | VARCHAR(64) | YES | NULL | Question category |
| tags_json | JSON | YES | NULL | Tags for categorization |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| status | VARCHAR(32) | NO | 'active' | Status: active, resolved, closed |
| is_verified | TINYINT | NO | 0 | Whether question has been verified |
| verification_count | INT | NO | 0 | Number of verifications |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_questions_actor | actor_id, status, is_deleted | Actor's questions |
| idx_questions_category | category, status, is_deleted | Category browsing |
| idx_questions_status | status, is_deleted, created_ymdhis | Status-based queries |
| idx_questions_tags | (generated from tags_json) | Tag-based search |

### `lupo_truth_answers`

**Purpose:** Stores answers to truth questions with supporting evidence.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| answer_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| question_id | BIGINT | NO |  | Foreign reference to lupo_truth_questions |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| answer_text | TEXT | NO |  | The answer content |
| answer_type | VARCHAR(32) | NO | 'text' | Type: text, link, file |
| confidence_score | DECIMAL(3,2) | YES | NULL | Confidence score (0.00-1.00) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_accepted | TINYINT | NO | 0 | Whether answer is accepted |
| acceptance_count | INT | NO | 0 | Number of acceptances |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_answers_question | question_id, is_accepted, is_deleted | Question's answers |
| idx_answers_actor | actor_id, created_ymdhis, is_deleted | Actor's answers |
| idx_answers_confidence | confidence_score, is_accepted | High-confidence answers |

### `lupo_truth_evidence`

**Purpose:** Stores evidence supporting truth answers.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| evidence_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| answer_id | BIGINT | NO |  | Foreign reference to lupo_truth_answers |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| evidence_type | VARCHAR(32) | NO | 'text' | Type: text, link, file, image |
| evidence_content | TEXT | NO |  | The evidence content |
| source_url | VARCHAR(512) | YES | NULL | Source URL if applicable |
| credibility_score | DECIMAL(3,2) | YES | NULL | Source credibility (0.00-1.00) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_verified | TINYINT | NO | 0 | Whether evidence is verified |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_evidence_answer | answer_id, is_verified, is_deleted | Answer's evidence |
| idx_evidence_credibility | credibility_score, is_verified | High-credibility evidence |

### `lupo_votes`

**Purpose:** Enables voting on questions and answers for community validation.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| vote_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| target_type | VARCHAR(32) | NO | 'question' | Target: question, answer, comment |
| target_id | BIGINT | NO |  | ID of target item |
| vote_type | VARCHAR(16) | NO | 'up' | Vote type: up, down, accept |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_votes_target | target_type, target_id, vote_type | Vote counting |
| idx_votes_actor | actor_id, target_type, created_ymdhis | Actor's voting history |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 01_core_identity | This → Core | Actor attribution | actor_id columns |
| 03_truth_knowledge | This → 04_tags_metadata | Tag assignment | tag_id columns |
| 03_truth_knowledge | This → 06_content_management | File attachments | evidence_file_id columns |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal operation | resolved, closed, deleted (soft) |
| resolved | Question has accepted answer | closed, deleted (soft) |
| closed | Question closed without resolution | deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

All truth items attributed to actors (actor_id)

Voting is anonymous at database level but tracked for integrity

Evidence sources tracked for verification

Soft delete preserves audit trail for compliance

## Testing Requirements

Unit tests for question-answer-evidence workflow

Integration tests for voting and following

Performance tests for truth item lookup and scoring

Soft delete behavior verification

## Migration Notes

Fresh Install Only - No upgrade path until 4.1.0.

## Usage Patterns

```php
// Create question
$questionService = new TruthQuestionService();
$questionId = $questionService->create($actorId, $questionText);

// Add answer
$answerService = new TruthAnswerService();
$answerId = $answerService->create($questionId, $actorId, $answerText);

// Add evidence
$evidenceService = new TruthEvidenceService();
$evidenceId = $evidenceService->create($answerId, $actorId, $evidenceContent);

// Vote
$voteService = new VoteService();
$voteId = $voteService->vote($actorId, 'answer', $answerId, 'up');
```

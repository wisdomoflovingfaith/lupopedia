---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/71_A_TRUTH_KNOWLEDGE.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/71_A_TRUTH_KNOWLEDGE.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/71_truth_knowledge.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/truth-knowledge
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_71_A
  title: "PRD 71: Truth System and Knowledge Management Database Tables"
  summary: null
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
| confidence_hundredths | INT | NO | 50 | Confidence ????? 100 (50 = 0.50; scale 0????????100) |
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
| idx_answers_confidence | confidence_hundredths, is_accepted | High-confidence answers |

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
| credibility_hundredths | INT | NO | 50 | Source credibility ????? 100 (50 = 0.50) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_verified | TINYINT | NO | 0 | Whether evidence is verified |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_evidence_answer | answer_id, is_verified, is_deleted | Answer's evidence |
| idx_evidence_credibility | credibility_hundredths, is_verified | High-credibility evidence |

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
| 01_core_identity | This ???????? Core | Actor attribution | actor_id columns |
| 03_truth_knowledge | This ???????? 04_tags_metadata | Tag assignment | tag_id columns |
| 03_truth_knowledge | This ???????? 06_content_management | File attachments | evidence_file_id columns |

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

Fresh Install Only - No upgrade path until 4.2.0.

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

---

## Context????????Typed, Status????????Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A ???????? B)
  - bidirectional (A ???????? B)
  - restricted-direction (A ???????? B but not B ???????? A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported ???????? supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```

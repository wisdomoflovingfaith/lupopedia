---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/49_A_QUESTIONS_AND_ANSWERS_SYSTEM.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/49_A_QUESTIONS_AND_ANSWERS_SYSTEM.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/prd/canonical/1026/04/49-questions-answers-system.toon
  atoms_toon: null
  transcript_jsonl: 0/prd/49-questions-answers-system
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_49_A_QUESTIONS_AND_ANSWERS
  title: "PRD 49: Questions and Answers System ??? The Crying of Lot 49"
  summary: "Complete specification for truth questions, answers, and evidence system. Database tables, questions_toon files, hybrid hierarchical + graph edge organization, web interface, and Crafty Syntax import. No foreign keys ??? all integrity in PHP."
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->

<HUMAN_SEMANTIC>
This file belongs to:
??? PRD Group 49 (Truth Maintenance)
??? Cluster 49A
??? Channel: prd
??? No default collection yet

See also:
??? 00_A_SYSTEM_CANONICAL_EXPLANATION.md
??? 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
??? PRD 98_A ??? WHY Files Doctrine
??? Order of Operations: PRD ??? Schema ??? Mockups ??? Code
</HUMAN_SEMANTIC>

# PRD 49: Questions and Answers System ??? The Crying of Lot 49

## Section 1: Purpose and Naming

The Crying of Lot 49 reference: Entropy, hidden patterns, communication systems, the search for truth through fragmented signals. The Q&A system is Lupopedia's Tristero ??? the hidden layer that connects questions to answers across time, agents, and contexts.

Define the Q&A system as the canonical truth maintenance layer: questions track uncertainty, answers record resolution, evidence provides provenance.

## Section 2: Database Tables

**CONSTITUTIONAL CONSTRAINT:** No FOREIGN KEY, no REFERENCES, no database-enforced constraints. All referential integrity is enforced in PHP application layer.

### 2.1 lupo_truth_questions

| Column | Type | Purpose |
|--------|------|---------|
| question_id | BIGINT | Primary key (IdGenerator) |
| question_text | TEXT | The question being asked |
| question_slug | VARCHAR(255) | URL-friendly identifier |
| asked_by_actor_id | BIGINT | Who asked |
| asked_ymdhis | BIGINT | When asked |
| channel_key | VARCHAR(255) | Source channel |
| thread_id | VARCHAR(255) | Source thread |
| status | VARCHAR(32) | open, answered, deprecated, merged |
| merged_into_question_id | BIGINT | NULL if not merged |
| context_json | JSON | Additional metadata (PRD references, tags) |
| is_deleted | TINYINT | Soft delete flag, default 0 |
| deleted_ymdhis | BIGINT | Soft delete timestamp, default 0 |

### 2.2 lupo_truth_answers

| Column | Type | Purpose |
|--------|------|---------|
| answer_id | BIGINT | Primary key (IdGenerator) |
| question_id | BIGINT | References lupo_truth_questions.question_id (PHP-enforced) |
| answer_text | TEXT | The answer |
| answered_by_actor_id | BIGINT | Who answered |
| answered_ymdhis | BIGINT | When answered |
| is_canonical | TINYINT | 1 = accepted truth, 0 = provisional |
| context_json | JSON | Confidence, caveats, supporting evidence IDs |
| is_deleted | TINYINT | Soft delete flag, default 0 |
| deleted_ymdhis | BIGINT | Soft delete timestamp, default 0 |

### 2.3 lupo_truth_evidence

| Column | Type | Purpose |
|--------|------|---------|
| evidence_id | BIGINT | Primary key (IdGenerator) |
| question_id | BIGINT | References lupo_truth_questions.question_id (PHP-enforced) |
| answer_id | BIGINT | NULL if evidence for question only; references lupo_truth_answers.answer_id (PHP-enforced) |
| evidence_type | VARCHAR(32) | prd_section, doctrine_file, transcript_entry, memory_node, external_url |
| evidence_location | TEXT | Path, URL, or reference |
| evidence_hash | VARCHAR(255) | SHA-256 for integrity |
| provided_by_actor_id | BIGINT | Who provided this evidence |
| provided_ymdhis | BIGINT | When provided |
| is_deleted | TINYINT | Soft delete flag, default 0 |
| deleted_ymdhis | BIGINT | Soft delete timestamp, default 0 |

## Section 3: questions_toon File Structure

Location: `lupo-memory/{channel_key}/questions/{YYYY}/{MM}/{slug}.questions.toon`

Format: TOON (Token-Oriented Object Notation) per TOON_ORDERING_SPEC.md

Purpose: Portable, machine-readable snapshot of questions for a given channel/thread/context

Structure:
```json
{
  "atom_version": "1.0.0",
  "export_ymdhis": "20260421130000",
  "channel_key": "headers",
  "questions": [
    {
      "question_slug": "what-is-atoms-toon",
      "question_text": "What is the purpose of atoms_toon?",
      "status": "answered",
      "answers": [
        {
          "answer_text": "Pointer to immutable machine-readable constants",
          "is_canonical": true,
          "evidence_refs": ["16_B_ATOMS_SYSTEM.md#section1"]
        }
      ]
    }
  ]
}
```

## Section 4: Hybrid Hierarchical + Graph Edge Organization

### 4.1 Hierarchical (Folder) Structure

Crafty Syntax legacy: hierarchical folder tree of Q&A

Migrated to: `lupo-memory/{channel_key}/questions/{YYYY}/{MM}/{slug}.questions.toon`

Year/month sharding prevents directory explosion

### 4.2 Graph Edge Structure (in lupo_memory_edges)

- edge_type: "questions_references_prd" ??? question ??? PRD section
- edge_type: "answer_supersedes_answer" ??? newer answer overrides older
- edge_type: "question_merged_into" ??? question consolidation
- edge_type: "evidence_supports_answer" ??? evidence ??? answer

### 4.3 Hybrid Rule

Hierarchical path provides deterministic location

Graph edges provide semantic relationships

Both must remain consistent; validator checks for drift

## Section 5: Web Interface

### 5.1 Question Lookup Interface

Route: `/admin/questions` or `/api/questions`

Search by: question text, slug, actor, channel, status, date range

View question with all answers and evidence

Filter by canonical status

### 5.2 Question Submission

Form for asking new questions

Requires: question text, channel context (auto-filled from header)

Optional: PRD reference, tags

### 5.3 Answer Submission

Form for answering existing questions

Requires: answer text, canonical flag (only for authorized actors)

Optional: evidence links, confidence score

### 5.4 Evidence Attachment

Upload evidence files or link to existing artifacts

Compute SHA-256 hash for integrity

## Section 6: Crafty Syntax Import

### 6.1 Legacy Structure

Crafty Syntax stored Q&A in hierarchical folders

Path-based categorization

No relational links between related questions

### 6.2 Import Process

Scan legacy folder tree recursively

For each .question file, create lupo_truth_questions row

For each .answer file, create lupo_truth_answers row

Preserve original path as context_json.legacy_path

Create graph edges from path hierarchy (parent folder = related question)

### 6.3 Import Script

```bash
python lupo-scripts/import_crafty_qa.py --source /path/to/crafty/qa --channel knowledge
```

## Section 7: Truth Maintenance Workflow

### 7.1 Question Lifecycle

1. Question asked (status: open)
2. Answers submitted (status remains open until canonical answer selected)
3. Canonical answer marked (status: answered)
4. If question becomes obsolete: status deprecated
5. If duplicate: merged into existing question (status: merged, merged_into_question_id set)

### 7.2 Evidence Integrity

All evidence must have SHA-256 hash

Validator checks evidence_location exists and hash matches

Broken evidence triggers [ALERT] via THOTH

## Section 8: questions_toon in Headers (PRD 16 integration)

Header field questions_toon points to .questions.toon file

When questions_toon is non-null, the file MUST exist and be valid TOON

Validator rule: HDR_QUESTIONS_TOON_VALID ??? checks path and format

## Section 9: No Foreign Keys ??? Application Integrity

All referential integrity (question_id ??? lupo_truth_questions, answer_id ??? lupo_truth_answers) MUST be enforced in PHP application layer

No database-level FOREIGN KEY, REFERENCES, or ON DELETE CASCADE

Application layer MUST validate existence before insert/update

Orphan cleanup is responsibility of garbage collection (PRD 19 / ANUBIS)

## Section 10: Cross-references

- **PRD 16** ??? questions_toon header field
- **PRD 38** ??? memory graph edges for Q&A relationships
- **PRD 51** ??? memory graph as source of truth
- **PRD 98_A** ??? WHY files (complementary self-healing)
- **PRD 00_A ??10** ??? Reactive WHY Protocol (original source)
- **PRD 19** ??? Garbage collection for orphan cleanup

---

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260421130000"

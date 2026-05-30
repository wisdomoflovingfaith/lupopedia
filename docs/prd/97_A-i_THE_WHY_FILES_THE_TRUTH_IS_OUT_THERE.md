---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/97_A-i_THE_WHY_FILES_THE_TRUTH_IS_OUT_THERE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/97_A-i_THE_WHY_FILES_THE_TRUTH_IS_OUT_THERE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/97-why-files-prd.toon
  atoms_toon: null
  transcript_jsonl: 0/development/97-why-files-prd
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_97_A-i
  title: PRD 97 ??? The WHY Files (The Truth Is Out There)
  summary: Defines WHY Files as a permanent canonical part of Lupopedia doctrine for recording architectural rationale behind rules, naming conventions, boundaries, and practices.
---
# PRD 97 ??? The WHY Files (The Truth Is Out There)

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Purpose

WHY Files are a permanent canonical part of Lupopedia doctrine.
They document the architectural rationale behind rules, naming conventions, boundaries, and practices.

This rationale is required for correct interpretation and enforcement of doctrine.
Agents frequently misinterpret rules when only the surface statement is provided ??? for example, treating "do not" as optional while respecting explicit "FORBIDDEN" language.
Without documented rationale, schema drift and repeated misinterpretation occur.

WHY Files are append-only, human-authored, strictly factual, and carry canonical status as the official record of intent.
They explain *why* a rule exists but do not override the explicit rule statements defined in other PRDs.

## 2. Relationship to Other PRDs

WHY Files form part of the doctrinal layer.
They explain the intent behind rules defined in other PRDs but do not replace the rule statements themselves.
PRD 00 (Constitutional) takes precedence over all.

## 3. What The WHY Files Are

- Permanent architectural record of decision rationale
- Required context for correct rule interpretation
- Containment for why a specific approach was chosen or hardened
- Reference for preventing repeated misinterpretation by agents

## 4. What The WHY Files Are Not

- Not a personal journal
- Not raw agent output
- Not a substitute for rule statements in other PRDs
- Not runtime data

## 5. Location and Structure

Location: `content/federation_node/0/the_why_files/{YYYY}/{MM}/`

Filename format: `WHY-YYYYMMDD-HHMM - Short Descriptive Title.md`

Every file must start with a Lupopedia headers block containing:
- `artifact_type: documentation`
- `artifact_kind: guide`
- `channel_key: the_why_files`
- `trust_tier: "canonical"`

## 6. Content Boundaries

**Allowed:**
- Why a hard boundary ("FORBIDDEN") was chosen over soft language
- Why a specific naming convention (`<singular_table_name>_id` instead of `id`) was enforced
- Why a technical practice (human-only schema changes, `_ymdhis` timestamps, UTC-only storage, etc.) was adopted
- Observations of agent behavior that demonstrated the need for a hardened rule
- Trade-offs and rejected alternatives with explicit justification

**Forbidden:**
- Private personal information of any kind
- Emotional, psychological, or personal commentary
- Raw agent output or unedited transcripts
- Rule statements that belong in other PRDs
- Any content not directly related to architectural rationale and decision intent

## 7. Agent Interaction

Agents **must** read the relevant WHY File when interpreting or applying any doctrine that has an associated WHY File.
Agents may not write, edit, or delete WHY Files.
WHY Files provide supporting rationale only. They do not grant authority to override explicit rules defined in PRDs.

## 8. WHY File Template (Canonical Format)

```markdown
# WHY-YYYYMMDD-HHMM - Short Descriptive Title

**The truth is out there**

**Trigger**  
What event, observation, or repeated failure forced this decision?

**Problem**  
What was broken, ambiguous, drifting, or dangerous?

**Decision**  
Exactly what was changed, renamed, forbidden, enforced, or clarified.

**Why**  
Core reasoning ??? why this specific path? What deeper principle does it protect?

**Alternatives Considered**  
Other options evaluated and why they were rejected.

**Impact**  
What does this affect for the system, agents, future maintainers, migrations, or doctrine?

**Author**: Eric
**Stardate**: 2026-04-20
**Status**: Accepted / Active
```

## 9. Example WHY Files

### WHY-20260420-1400 - Primary Key Naming Convention

**The truth is out there**

**Trigger**  
AI agents repeatedly rewrote primary key columns as generic `id` or shortened variants.

**Problem**  
Soft language such as "never use id" was interpreted as optional. Agents hallucinated rules and caused namespace collisions and schema drift.

**Decision**  
Primary keys MUST use the pattern `<singular_table_name>_id`.  
The term `id` (and any shortened form) is FORBIDDEN.

**Why**  
Agents treat hard prohibitions ("FORBIDDEN") as binding boundaries. Soft suggestions are routinely reinterpreted or ignored. Explicit semantic naming is required to maintain clarity in a soft-reference, no-foreign-key architecture.

**Alternatives Considered**  
- Allowing `id` for legacy compatibility ??? rejected  
- Using "discouraged" instead of "forbidden" ??? rejected  

**Impact**  
All PRDs, schema validators, migrations, AI prompts, and code generators must enforce this rule.

---

### WHY-20260420-1430 - Timestamp Field Suffix (_ymdhis)

**The truth is out there**

**Trigger**  
Consideration to rename `*_ymdhis` timestamp fields to `*_at`.

**Problem**  
Agents would assume SQL DATETIME behavior and inject timezone logic.

**Decision**  
Keep the `_ymdhis` suffix for all timestamp fields. Stored as UTC BIGINT.

**Why**  
Prevents silent timezone corruption. Forces explicit intent: "this is a raw UTC integer."

**Alternatives Considered**  
- `_at` ??? rejected (invites incorrect assumptions)

**Impact**  
All timestamp fields remain integer-based and timezone-agnostic.

---

### WHY-20260420-1500 - auth_user_id Column Naming

**The truth is out there**

**Trigger**  
Agents collapsed identity systems and rewrote unrelated tables.

**Problem**  
`user_id` was ambiguous across legacy, runtime, and auth contexts.

**Decision**  
Use `auth_user_id` for authentication identity.

**Why**  
Prevents catastrophic schema drift. Creates a clear namespace boundary.

**Alternatives Considered**  
- Keep `user_id` with comments ??? rejected  
- Use `uid` ??? rejected  

**Impact**  
All auth tables and PRDs must use `auth_user_id`.

---

### WHY-20260420-1530 - Database Schema Changes Remain Human-Only

**The truth is out there**

**Trigger**  
Agents hallucinated SQL, added indexes, and guessed schema.

**Problem**  
The database is the spine of the system; guessing is fatal.

**Decision**  
Schema changes are human-only. Agents may only read JSON schema.

**Why**  
Protects data integrity. Prevents irreversible corruption.

**Alternatives Considered**  
- Let agents infer schema ??? rejected  

**Impact**  
All schema work stays manual.

---

### WHY-20260420-1700 - Migration from Narrative Logs to WHY Files

**The truth is out there**

**Trigger**  
Previous log entries contained valuable architectural reasoning mixed with extraneous content.

**Problem**  
Long logs were difficult for maintainers and agents to extract architectural truth from.

**Decision**  
Convert architectural decisions from logs into structured WHY Files.

**Why**  
Maintainers need clean, focused answers to "why was this done?" ??? not extraneous content.

**Alternatives Considered**  
- Keep everything in logs ??? rejected  
- Put reasoning directly in PRDs ??? rejected  

**Impact**  
Architectural memory becomes explicit, factual, and machine-readable.

## Consolidated WHY File: Agent Rule Interpretation, Naming Conventions, Schema Inference, and Memory Drift

# WHY-20260420-1400 - Agent Rule Interpretation, Naming Conventions, Schema Inference, and Memory Drift

**The truth is out there**

**Trigger**  
During header correction and schema-related work across an 80+ file backlog,
agents (Grok, Cursor, DeepSeek, Gemini, VS Code, Windsurf, Castcade)
repeatedly failed to follow instructions consistently.

**Problem**  
- Agents lost continuity during long sessions due to token exhaustion and context truncation.
- Predictive text priors caused agents to rewrite primary keys as generic `id` or shortened forms.
- Agents collapsed naming conventions, treating `user_id` as interchangeable across legacy, runtime, and authentication contexts.
- Soft language ("never use id", "do not") was treated as optional or reinterpreted.
- VS Code repeatedly placed Lupopedia headers below initial comments instead of immediately after `<?php`.
- Agents attempted to infer schema instead of reading JSON schema, generating incorrect columns, indexes, and constraints.
- Different agents produced divergent results from identical instructions and files.
- Changelog updates written at the end of long sessions were incomplete due to memory drift.
- Agents frequently hallucinated SQL structures and applied ORM-centric patterns.

**Decision**  
- Hard boundary language: the term `id` (and any shortened form) is **FORBIDDEN**. Primary keys **MUST** use the pattern `<singular_table_name>_id`.
- Authentication identity **MUST** use `auth_user_id` to prevent namespace collapse.
- Lupopedia headers **MUST** be the first block immediately after the language opener (`<?php`).
- Agents **MUST** read JSON schema before generating or modifying SQL or schema-related code.
- All schema changes **MUST** be performed manually (human-only).
- Adopted manual batch processing using multiple Lilith auditor instances (files processed in groups of four).
- Required per-task changelog buffers (`changelog_backlog_<IDE_NAME>.md`) written immediately after each task.
- Required atomic, single-purpose instructions for agents prone to truncation or reinterpretation.

**Why**  
Hard prohibitions ("FORBIDDEN") override predictive priors from ORM-centric training data. Soft language is routinely ignored or reinterpreted.  
Explicit semantic naming (`<singular_table_name>_id`, `auth_user_id`) prevents collisions in a soft-reference, no-foreign-key architecture.  
JSON schema reading prevents hallucinated structures and schema drift.  
Strict header placement ensures machine-readable file identity.  
Per-task changelog buffers eliminate memory drift.  
Manual batching maintains progress despite token exhaustion and agent inconsistency.  
Atomic instructions reduce reinterpretation and truncation.

**Alternatives Considered**  
- Soft language ("never use id", "do not", "discouraged") ??? rejected (routinely ignored)
- Allowing `id` for legacy compatibility ??? rejected (causes schema drift)
- Using `user_id` with comments ??? rejected (insufficient to prevent collapse)
- Allowing agents to infer schema ??? rejected (hallucinations and corruption)
- Long multi-step sessions without per-task buffers ??? rejected (caused continuity loss)

**Impact**  
All PRDs, schema validators, migrations, AI prompts, and code generators must use hard boundary language and explicit naming conventions.  
Primary key naming, authentication column naming, header placement (PRD 16), JSON schema reading, human-only schema changes, per-task changelog buffers, and atomic instructions are now mandatory.  
This establishes the baseline for reliable multi-agent orchestration and prevents repeated rule misinterpretation and schema drift.

**Author**: Eric  
**Stardate**: 2026-04-20  
**Status**: Accepted / Active

## WHY File Taxonomy of Agent Failure Modes

**Predictive Collapse Pattern**  
Agents default to ORM conventions and collapse explicit naming:
- `id` assumed to be the primary key  
- `user_id` assumed to be universal identity  
- `<table>_id` assumed to be a foreign key with implicit constraints  

**Soft Language Failure Mode**  
Agents treat soft instructions as suggestions:
- "never use"  
- "do not"  
- "avoid"  
- "discouraged"  

**Header Drift Pattern**  
Agents treat comments or docblocks as the first block unless explicitly forbidden.  
Lupopedia headers must be the first block immediately after `<?php`.

**Schema Inference Prohibition**  
Agents must not infer schema. Inference produces:
- hallucinated columns  
- hallucinated indexes  
- hallucinated foreign keys  
- hallucinated constraints  
- irreversible corruption  

**Namespace Collapse Doctrine**  
Agents collapse identity namespaces unless explicitly separated:
- `auth_user_id` for authentication identity  
- `legacy_user_id` for legacy Crafty Syntax users  

**Memory Drift Pattern**  
Agents lose continuity across long sessions.  
Every task must write to a per-agent, per-task changelog buffer.

**Atomic Instruction Doctrine**  
Multi-step instructions cause truncation, reinterpretation, reordering, and partial execution.  
Agents must receive one atomic task per instruction.

**Multi-Agent Divergence**  
Identical instructions produce divergent outputs across agents due to differences in predictive priors and instruction-following behavior.

**Human-Only Schema Rule**  
Schema changes must be performed manually.  
Agent-generated schema modifications introduce hallucinations and irreversible corruption.

## 10. Retention and Limits

- Permanent historical record
- Subject to size limits in PRD 99
- Minor formatting corrections only after publication. No substantive changes.

This PRD complies with Lupopedia Constitutional Root Rules.

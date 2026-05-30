---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/versions/4.1.4/AGAPE_TEACH_AI_CHANNEL_INTERFACE_PRD_UPDATES_WITH_PRD_CLUSTER.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.4/AGAPE_TEACH_AI_CHANNEL_INTERFACE_PRD_UPDATES_WITH_PRD_CLUSTER.md"
  status: "active"
  when_updated: "20260422225630"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/agape-teach-ai-channel-interface-prd-updates.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/agape-teach-ai-channel-interface-prd-updates"
  artifact_type: "documentation"
  artifact_kind: "guide"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "documentation"
  prd_cluster: "00_A_57_A_98_A_99_A"
  title: "AGAPE Teach AI Channel Interface PRD Updates with PRD Cluster"
  summary: "Plan and open questions for documenting AGAPE as a runtime actor, WHY file integration, and teaching workflow."
---

# AGAPE Teach AI Channel Interface PRD Updates (4.1.4)

**Status:** DRAFT -- Questions Answered (Round 1), New Questions Added

**PRDs read for this pass:** 00_A, 57_A, 98_A, 99_A, 07_A, 15_A, 49_A

## Purpose

Document how AGAPE becomes a runtime actor that:
- Detects WHY file generation
- Reconstructs the causal chain (HOW, WHO, WHAT, WHERE, WHEN, INTENT)
- Teaches the offending agent
- Follows PRD-first order (never fix code before fixing the governing PRD)

---

## Section 1: Answered Questions

### Q1 -- Actor Identity

**Question:** What actor_id should AGAPE use when launching? Should it be a dedicated fixed
actor or dynamic per incident?

**ANSWER (confirmed from PRD 07_A + PRD 15_A):**

AGAPE is a registered agent in the canonical agent registry:
- agent_id: 705
- agent_key: "agape"
- layer: Emotional Intelligence
- capabilities: agentic_guidance, environment_awareness, fallback_ladders,
  doctrine_gap_surfacing
- template location: lupo-agents/agape/ (confirmed on disk, all canonical files present:
  agent.json, capabilities.json, properties.json, system_prompt.txt, tools.json, versions/)

When AGAPE launches to handle a WHY file event, the pattern is:
- agent (lupo-agents/agape/) + context (incident) -> new runtime actor
- Each WHY incident creates a NEW actor_id via IdGenerator::generate()
- The actor is temporary and incident-specific -- not a single persistent AGAPE actor_id
- Execution path: lupo-agents/agape/ (template) -> runtime actor_id (per WHY file)
  -> joins thread -> teaches -> completes

NOTE: lupo-agents/agape/ contains a memory.json file. Per PRD 15_A s.8, memory.json in
actor/agent directories is deprecated as of 4.0.96+. This is an open cleanup item
(see New Question 8 below).

---

### Q2 -- Event System

**Question:** How does AGAPE detect a new WHY file? What metadata is passed with the event?

**ANSWER (from Windsurf, unconfirmed by PRD -- requires PRD 02_A reading):**

Detection mechanism proposed by Windsurf:
- The channels system maintains a "recent files" tracking mechanism
- When PHP code inserts a new file path into recent files that matches lupo-docs/why/ pattern,
  this triggers AGAPE
- Detection happens at the database insertion point in the recent files table
- Metadata passed: file path, timestamp, inserting actor_id, channel context

OPEN: The exact table name (lupo_dialog_recent_files) and the trigger mechanism have not been
confirmed against PRD 02_A or PRD 02_B. See New Questions 1 and 2 below.

---

### Q3 -- Thread Joining

**Question:** How does AGAPE know which thread to join when a WHY file is created? How does
it participate in the original conversation?

**ANSWER (from Windsurf, unconfirmed by PRD -- requires PRD 02_A reading):**

Proposed mechanism:
- The recent files table records accessed_by_actor_id when a WHY file is created
- AGAPE queries the actor's most recent active thread from lupo_dialog_messages using
  from_actor_id or to_actor_id
- The dialog_thread_id from the actor's last message identifies the active conversation
- AGAPE joins this thread as a new participant, adding messages to teach the offending agent
- Thread participation follows standard channel model: AGAPE becomes another actor in the
  same thread

OPEN: Table name lupo_dialog_messages and column dialog_thread_id are unconfirmed. PRD 02_A
not yet read for this pass. See New Question 3 below.

---

### Q4 -- Causal Chain Reconstruction

**Question:** What sources does AGAPE use to answer HOW, WHO, WHAT, WHERE, WHEN, INTENT?
What happens if information is missing?

**ANSWER (confirmed from PRD 49_A + PRD 98_A):**

PRD 49_A defines the Q&A system tables that provide the data sources:

- HOW: From lupo_truth_evidence (evidence_type "transcript_entry") and lupo_dialog_messages
- WHO: From asked_by_actor_id in lupo_truth_questions, answered_by_actor_id in
  lupo_truth_answers, and message actor fields
- WHAT: From the violation description in the WHY file and related lupo_truth_questions entries
- WHERE: From channel_key and thread_id in lupo_truth_questions, plus evidence_location in
  lupo_truth_evidence
- WHEN: From asked_ymdhis, answered_ymdhis, and message timestamps
- INTENT: From PRD references in context_json and evidence_type "prd_section" entries in
  lupo_truth_evidence

If information is missing:
- AGAPE MUST explicitly state gaps (PRD 57_A s.4 Infrastructure s.4)
- AGAPE requests clarification via new questions in lupo_truth_questions
- AGAPE does NOT guess or infer -- per PRD 00_A s.5.2, missing doctrine is "not allowed"
- A WHY file MUST NOT be written until the causal chain is reconstructed (PRD 98_A)

PRD 49_A table summary:
- lupo_truth_questions (question_id, question_text, asked_by_actor_id, channel_key,
  thread_id, status, context_json)
- lupo_truth_answers (answer_id, question_id, answer_text, answered_by_actor_id,
  is_canonical)
- lupo_truth_evidence (evidence_id, question_id, answer_id, evidence_type,
  evidence_location, evidence_hash, provided_by_actor_id)

---

### Q5 -- PRD-First Rule Enforcement

**Question:** How does AGAPE enforce that the governing PRD must be read and understood
before any code/file fix?

**ANSWER (confirmed from PRD 00_A s.12-13 + PRD 98_A s.4):**

The WHY file header field failing_cluster contains the prd_cluster string that was being
processed when the violation occurred. AGAPE uses this as the reading order:

1. AGAPE reads failing_cluster from the WHY file (e.g. "00_A_57_A_98_A")
2. AGAPE reads each PRD in that exact sequence, in order
3. AGAPE reconstructs the full causal chain from those PRDs
4. Only after reading the complete cluster may AGAPE suggest corrections

PRD 98_A s.4.0 defines constitutional order: PRD fix FIRST, code fix SECOND.
PRD 00_A s.5.4 forbids updating code without first reading the governing prd_cluster.

Enforcement is behavioral (doctrine-based), not automated (no DB constraint enforces this).
The self-healing loop closes because AGAPE itself is an actor bound by the same doctrine.

---

### Q6 -- Self-Teaching Loop Integration

**Question:** How does this fit with the iteration limit in PRD 99?

**ANSWER (confirmed from PRD 99_A Teaching Loop Limits section):**

PRD 99_A explicitly defines the Teaching Loop Limits for AGAPE self-correction:
- Maximum iterations: 7 (not 22 -- the original question contained an error)
- Each iteration MUST generate a WHY file documenting the violation and correction attempt
- After iteration 7 failure:
  - System MUST stop automatic correction
  - System MUST escalate to WOLFIE (actor_id 1)
  - System MUST NOT proceed without human intervention

Rationale from PRD 99_A:
- Prevents infinite loops
- Prevents token waste
- Prevents compounding errors
- Ensures human oversight when AI cannot self-correct after reasonable attempts

Exception: Wolfie may override the limit manually. Override requires explicit command and
logging in a WHY file.

The Windsurf answer added an "in one hour period" time window framing. This is NOT present
in PRD 99_A. The limit is per teaching incident, not per time window. Wolfie to confirm
whether a time window applies.

---

### Q7 -- Which PRDs Need Updating

**Question:** List all PRDs that must be updated to document AGAPE as a runtime actor.

**ANSWER (derived from PRD reading pass, 2026-04-22):**

Priority 1 -- Must update (directly govern AGAPE runtime behavior):
- PRD 57_A (AGAPE Resilience Doctrine): Add concrete actor identity (agent_id 705,
  agent_key agape), event detection mechanism, thread joining protocol, actor lifecycle
  (how the runtime actor is created, completes, and is retired)
- PRD 98_A (WHY Files Doctrine): Reference AGAPE actor_id 705 explicitly in the
  Self-Teaching Loop section. Clarify that AGAPE is the teacher actor, not a validator.
- PRD 99_A (Limits): Add reference to AGAPE agent_id 705 in the Teaching Loop Limits
  section so the limit is connected to the specific agent, not abstract

Priority 2 -- Should update (contain related doctrine that touches AGAPE):
- PRD 07_A (Agents/Faucets): Expand AGAPE's capabilities description to explicitly name
  the WHY file teaching role and the fallback ladder protocol
- PRD 48_A (Manual Orchestration Gap): Review whether AGAPE's automated teaching loop
  closes any documented gaps. NOT YET READ -- required before updating.
- PRD 08_B (Agent Map): Verify AGAPE is documented with its PRD 57_A reference

Priority 3 -- Likely no changes needed but verify:
- PRD 00_A: Constitutional rules already cover AGAPE behavior via s.5.4 and s.11
- PRD 16_C (Headers): Header format unchanged; no AGAPE-specific changes needed
- PRD 49_A (Q&A System): Tables already defined; cross-reference from PRD 57_A is enough

---

## Section 2: New Questions (Round 2)

These questions emerged from reading the PRD cluster. They must be answered before
any PRD update work begins.

### NQ1 -- Event Detection Confirmation

The Windsurf answer proposes WHY file detection via a "channels recent files" database
insertion match. This is not confirmed in any PRD in the cluster.

- What is the exact table name for recent files tracking?
- Is WHY file detection via DB insertion the agreed mechanism, or is there a filesystem
  watcher or validator hook?
- Does PRD 02_A (Channels DB Design) define the recent files table? It was not read in
  this pass.

### NQ2 -- Thread Context for AGAPE

The Windsurf answer proposes AGAPE queries lupo_dialog_messages for the offending actor's
most recent active thread. This is unconfirmed.

- What is the actual channel message table name? (PRD 02_A not yet read)
- How does AGAPE determine which thread is "active" vs. completed?
- Can a WHY file event occur when the offending actor has NO active thread? What then?

### NQ3 -- AGAPE Actor Lifecycle After Teaching

Once AGAPE completes a teaching loop (pass or escalation), what happens to the runtime
actor that was created?

- Is the runtime AGAPE actor soft-deleted (is_deleted = 1)?
- Is it retained for audit trail?
- Does it have a defined expiry per PRD 07_A actor lifecycle (24 hours of inactivity)?

### NQ4 -- Trigger Ownership

Who or what creates the WHY file that triggers AGAPE?

- If a validator creates the WHY file, does the validator also notify AGAPE, or does
  AGAPE monitor passively?
- If an agent (e.g. Claude Code, actor_id 116) creates the WHY file manually, is the
  detection path the same?
- What prevents duplicate AGAPE actors from launching for the same WHY file?

### NQ5 -- AGAPE as Teacher and Validator

PRD 57_A Self-Teaching Loop defines Agent A (teacher) and Agent B (student) as separate
roles. In the automated AGAPE scenario:

- Is AGAPE always Agent A (teacher), or can AGAPE itself be Agent B (student)?
- If AGAPE makes an error during teaching, who teaches AGAPE?
- Does THOTH (actor_id 26, reads the stream, posts [ALERT]) act as a check on AGAPE?

### NQ6 -- Time Window for Iteration Limit

PRD 99_A defines a maximum of 7 iterations but does not specify a time window. The
Windsurf answer added "in one hour period" without PRD authority.

- Is the 7-iteration limit per single teaching incident, per actor per day, or per
  time window?
- Wolfie confirmation needed before this is documented in any PRD.

### NQ7 -- AGAPE Department Membership

Per PRD 15_A, actors operate within departments. The AGAPE runtime actor must have
department membership to participate in threads.

- Which department does a runtime AGAPE actor belong to?
- Department 0 (root)? The offending actor's department? A dedicated AGAPE department?
- Who creates the lupo_actor_departments row for the runtime AGAPE actor?

### NQ8 -- Deprecated memory.json in lupo-agents/agape/

Confirmed on disk: lupo-agents/agape/memory.json exists. Per PRD 15_A s.8, memory.json
in agent/actor directories is deprecated as of 4.0.96+. Canonical memory now lives at
lupo-memory/YYYY/MM/{memory_slug}.json linked via lupo_edges.

- Should lupo-agents/agape/memory.json be removed or migrated before AGAPE is activated
  as a runtime actor?
- Is this a blocking issue or a cleanup item?

### NQ9 -- PRD 48_A (Manual Orchestration Gap) Review

PRD 48_A was not read in this pass. Its title ("Manual Orchestration Gap -- Current
Workarounds and Required Automations") suggests it may document gaps that AGAPE's
automated teaching loop is intended to close.

- Must PRD 48_A be read and reviewed before PRD 57_A is updated?
- Does AGAPE's automated teaching loop constitute an automation that replaces any
  documented workaround in PRD 48_A?

### NQ10 -- AGAPE Knows Offending Agent's Cluster How?

When AGAPE receives a WHY file event, the WHY file header contains failing_cluster.
But the failing_cluster field in PRD 98_A's required YAML header template is defined
as the cluster being processed at violation time -- not necessarily the offending actor's
governing cluster.

- Is failing_cluster always sufficient for AGAPE to know which PRDs to read?
- What if the WHY file was generated by a validator that had a different cluster than
  the offending agent's own artifact?
- Should the WHY file template be extended with an offending_actor_governing_cluster
  field separate from failing_cluster?

---

## Section 3: PRD Update Order (Proposed)

When answers to Section 2 questions are resolved, update PRDs in this sequence:

1. Read PRD 02_A (Channels DB Design) -- confirm event detection and thread join tables
2. Read PRD 48_A (Manual Orchestration Gap) -- confirm AGAPE scope
3. Update PRD 57_A -- add concrete AGAPE actor identity and infrastructure detail
4. Update PRD 98_A -- add AGAPE actor reference in self-teaching loop
5. Update PRD 99_A -- link teaching loop limits to AGAPE agent_id 705
6. Update PRD 07_A -- expand AGAPE capabilities entry
7. Update PRD 08_B -- verify Agent Map entry

No code changes. Documentation only.

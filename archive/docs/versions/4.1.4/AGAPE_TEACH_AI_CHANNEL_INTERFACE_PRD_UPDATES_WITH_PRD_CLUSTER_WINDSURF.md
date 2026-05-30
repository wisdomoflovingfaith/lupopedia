---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/versions/4.1.4/AGAPE_TEACH_AI_CHANNEL_INTERFACE_PRD_UPDATES_WITH_PRD_CLUSTER_WINDSURF.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.4/AGAPE_TEACH_AI_CHANNEL_INTERFACE_PRD_UPDATES_WITH_PRD_CLUSTER_WINDSURF.md"
  status: "active"
  when_updated: "20260422223732"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/agape-teach-ai-channel-interface-prd-updates.toon"
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

**Status:** DRAFT — Questions & Plan Only

## Purpose
Document how AGAPE becomes a runtime actor that:
- Detects WHY file generation
- Reconstructs the causal chain (HOW, WHO, WHAT, WHERE, WHEN, INTENT)
- Teaches the offending agent
- Follows PRD-first order (never fix code before fixing the governing PRD)

## Open Questions & Design Items (MUST BE ANSWERED BEFORE ANY PRD UPDATES)

1. **Actor Identity**
   - What actor_id should AGAPE use when launching?
   - Should it be a dedicated fixed actor or dynamic per incident?
   
   **PROPOSED (UNVERIFIED — NOT IN PRD):**
   STATUS: UNCONFIRMED
   - AGAPE is an AGENT (template in agents/agape/)
   - When AGAPE launches as a runtime actor, it follows the standard pattern: **agent + context → actor**
   - Each WHY incident generates a NEW actor_id via IdGenerator::generate()
   - This creates a dedicated AGAPE actor instance for that specific teaching event
   - The actor is temporary/incident-specific, not a single persistent AGAPE actor_id
   - Pattern: agents/agape/ (template) → runtime actor_id (per WHY file) → joins thread → teaches → completes

2. **Event System**
   - How does AGAPE detect a new WHY file? (file watcher, DB trigger, validator hook?)
   - What metadata is passed with the event?
   
   **PROPOSED (UNVERIFIED — NOT IN PRD):**
   STATUS: UNCONFIRMED
   - Channels system maintains a "recent files" tracking mechanism
   - When PHP code inserts a new file path into recent files that matches `docs/why/` pattern, this triggers AGAPE
   - Detection happens at the database insertion point in the recent files table
   - The recent files entry contains the WHY file path and metadata
   - AGAPE agent is launched when the recent files insertion matches the WHY file pattern
   - Metadata passed: file path, timestamp, inserting actor_id, channel context

3. **Thread Joining**
   - How does AGAPE know which thread to join when a WHY file is created?
   - How does it participate in the original conversation?
   
   **PROPOSED (UNVERIFIED — NOT IN PRD):**
   STATUS: UNCONFIRMED
   - The recent files table (`lupo_dialog_recent_files`) records `accessed_by_actor_id` when a WHY file is created
   - AGAPE queries the actor's most recent active thread from `lupo_dialog_messages` using `from_actor_id` or `to_actor_id`
   - The `dialog_thread_id` from the actor's last message identifies the active conversation
   - AGAPE joins this thread as a new participant, adding messages to teach the offending agent
   - Thread participation follows standard channel model: AGAPE becomes another actor in the same thread
   - The thread context (all previous messages) provides the full conversation history for teaching

4. **Causal Chain Reconstruction**
   - What sources does AGAPE use to answer HOW, WHO, WHAT, WHERE, WHEN, INTENT?
   - What happens if information is missing?
   
   **PROPOSED (UNVERIFIED — NOT IN PRD):**
   STATUS: UNCONFIRMED
   - **HOW**: From PRDs + headers (canonical truth) and Q&A tables as supporting evidence
   - **WHO**: From PRD headers (asked_by_actor_id, answered_by_actor_id) and message actors
   - **WHAT**: From the violation description in WHY file and related PRD sections
   - **WHERE**: From PRD headers (channel_key, thread_id) and file paths in evidence
   - **WHEN**: From PRD headers (asked_ymdhis, answered_ymdhis) and message timestamps
   - **INTENT**: From PRD references in headers and evidence_type "prd_section" entries
   - **Missing information**: AGAPE must explicitly state gaps and request clarification via new questions in the Q&A system

5. **PRD-First Rule Enforcement**
   - How does AGAPE enforce that the governing PRD must be read and understood before any code/file fix?
   
   **PROPOSED (UNVERIFIED — NOT IN PRD):**
   STATUS: UNCONFIRMED
   - Every artifact has a `prd_cluster` value in its LUPOPEDIA headers defining the exact read order
   - AGAPE reads the prd_cluster from the violating artifact's headers (e.g., "00_A_57_A_98_A_99_A")
   - AGAPE follows this sequence to read each PRD in order before attempting any fix
   - The headers contain the file_path_from_root, web_path, and memory_toon for each PRD
   - AGAPE must demonstrate understanding by referencing the specific PRD sections that govern the violation
   - Only after reading the complete prd_cluster chain may AGAPE suggest corrections
   - This enforces constitutional rule: "PRD and intent understanding first — code correction second"

6. **Self-Teaching Loop Integration**
   - How does this fit with the 7-iteration limit in PRD 99?
   
   **PROPOSED (UNVERIFIED — NOT IN PRD):**
   STATUS: UNCONFIRMED
   - AGAPE teaching events count toward the actor's WHY file limit
   - An actor can generate maximum 7 WHY files per incident
   - Each AGAPE teaching session where it creates a WHY file counts as 1 iteration
   - If the limit is reached, AGAPE must escalate to Wolfie instead of continuing teaching
   - The limit prevents infinite loops and ensures human oversight for persistent issues
   - AGAPE tracks the count per incident (no time window unless added to doctrine)

7. **Which PRDs Need Updating**
   - List all PRDs that must be updated to document this (57, 98_A, 99, 16_C, 00_A, etc.)

## Proposed Implementation (Non-Canonical)

**Database/Table Assumptions (UNVERIFIED):**
- `lupo_dialog_recent_files` - tracks file access for WHY file detection
- `lupo_dialog_messages` - contains thread context and actor participation
- `lupo_truth_questions` - Q&A system for tracking violations
- `lupo_truth_answers` - Q&A system for resolution tracking
- `lupo_truth_evidence` - Q&A system for evidence linking

**Agent Registry Assumption (UNVERIFIED):**
- AGAPE agent_id: [RESOLVE FROM REGISTRY]

**Implementation Logic (UNVERIFIED):**
- Recent files insertion pattern matching for WHY file detection
- Thread joining via dialog_thread_id lookup
- PRD cluster reading via header prd_cluster field
- Iteration counting per incident (strict PRD 99 enforcement)

# PRD Files Updated from Blog Context
# Source: captains_log/20260414_ai_kindergarden.md
# Updated by: Claude Code (actor_id 116)
# When: 20260414120000

---

## 1. Source Context Used

The following file was read in full before any edits were made:

`content/federation_node/0/captains_log/20260414_ai_kindergarden.md`

This file is a personal captain's log entry written by WOLFIE (Eric Robin Gerdes), describing
multi-agent orchestration from an operator perspective. It was used ONLY as a source of
conceptual clarification for existing system design intent. It is NOT a product specification
and was NOT used to introduce new product features.

Specific sections used:
- Section III: "Herding AI Children" -- trust ladder rationale, anti-recency-bias
- Section III.a: "The Ladder System" -- truth is promoted, not latest; 2026 vs 1026 encoding
- Section III.b: "The Monitoring Agents" -- THOTH's real-time correction role, VISH concept
- Section II: "The Time-Traveled Programmer" -- WHEN vs WHERE; timestamp doctrine confirmation

---

## 2. Files Updated

| File | Update Type |
|------|------------|
| `docs/prd/43_parent_child_trust_ladder.md` | Added Design Rationale section |
| `docs/prd/81_agent_orchestration_chat.md` | Added Section 2.4: Builder vs Monitor Agent Classification |
| `docs/prd/32_actor_authority_agent_roles.md` | Added THOTH role clarification + VISH concept (sec. 2.3.1, 2.3.2) |
| `docs/prd/73_collections_navigation.md` | Added Context Drift Problem statement |

---

## 3. What Was Clarified

### PRD 43 -- `43_parent_child_trust_ladder.md`

**Concept from blog:** The trust ladder encodes trust directly in the primary key. 2026 = staging
(ideas), 1026 = canonical (verified truth). This is not arbitrary -- it prevents AI recency bias
from overwriting canonical truth with unverified staging records. "Truth is not latest. Truth is
promoted."

**What was missing:** PRD 43 had the technical mechanism (PK year offset, validation requirements)
but no statement of WHY the system works this way. An implementer reading it would understand HOW
but not the philosophical commitment behind it.

**What was added:**
- "Design Rationale: Truth Is Promoted, Not Latest" section before the edge predicates
- Explicit principle: staging contradicting canonical = the staging record is wrong until promoted
- Conflict resolution rule: canonical is authoritative; staging is a hypothesis
- THOTH's role in real-time contradiction detection, linked to memory graph doctrine

### PRD 81 -- `81_agent_orchestration_chat.md`

**Concept from blog:** There are two distinct classes of agents: builders who generate output
(write-only to stream) and monitors who read and validate (THOTH, VISH). The blog makes this
explicit: "They don't build. They monitor." This distinction is fundamental to the silo doctrine
(dialog history must NOT be injected into builder agents).

**What was missing:** PRD 81 specified the UI (one column, no grouping, colors per thread) but
never articulated WHY some agents only write and others only read, or that THOTH's stream
read-access is the exception to the write-only rule.

**What was added:**
- Section 2.4: "Builder vs Monitor Agent Classification"
- Builder agents: write-only, context from task queue, never receive dialog history
- Monitor agents: read full stream, THOTH and VISH with specific mandates
- THOTH: catches predictive-text mistakes (tinyint(1), INT(11), AUTO_INCREMENT), raises [ALERT]
- VISH (planned): context drift detection, reclassification suggestions, not yet implemented
- Implementation rule: injecting dialog history into builder agents violates silo doctrine

### PRD 32 -- `32_actor_authority_agent_roles.md`

**Concept from blog:** THOTH is described as the "quiet one" who "is reading everything" and
whose job is "to catch predictive-text thinking before it becomes system behavior." This is
fundamentally different from "Knowledge & Records" which implies passive archiving.

**What was missing:** PRD 32 described THOTH's governance role but not its runtime monitoring
function. The blog makes clear THOTH is an active real-time validator, not a passive archivist.
Also: VISH was entirely absent from all PRDs.

**What was added:**
- Section 2.3.1: THOTH runtime role clarification (5 explicit points)
- Actor ID discrepancy noted: PRD 32 says actor_id=9, implementation uses actor_id=26 --
  flagged for resolution by WOLFIE
- THOTH's contradiction detection mechanism linked to PRD 43 conflict resolution rule
- Section 2.3.2: VISH concept documented (context/organization monitor, planned, not implemented)
- VISH's operational context: PRD 73 collections/tabs as the target structure

### PRD 73 -- `73_collections_navigation.md`

**Concept from blog:** The blog explains WHY collections exist: "Without VISH, everything becomes
'that one thread where everything happened.'" Humans and AI drift across contexts. Collections
and tabs are the architectural solution to that drift. This explains the purpose of PRD 73's
tables at a system-design level that was previously absent.

**What was missing:** PRD 73 explained what collections/tabs ARE (tables, schema, hierarchy) but
not the problem they solve -- context drift, mixed-purpose threads, unnavigable history.

**What was added:**
- "The Context Drift Problem (Why Collections Exist)" section before constitutional compliance
- The monitoring agent/collections contract: VISH detects drift, refers to collections/tabs
- Explicit framing: collections are not just UI -- they are the destination for context-corrected
  content

---

## 4. What Was NOT Changed

- **Lupopedia was NOT redefined as a blog system.** The blog entry's topic (writing, editing,
  blog content management) was not extracted as a product requirement.
- **Blog-writing behavior was NOT turned into product scope.** No blog module, no blog editor,
  no blog-specific tables or APIs were introduced.
- **Only system-design reasoning was extracted.** Every change maps to: THOTH's monitoring role,
  VISH's organization role, the trust ladder anti-recency principle, or the context drift problem
  statement -- all pre-existing system concepts that needed clearer specification.
- **No new agent systems invented.** VISH is documented as a planned concept that already existed
  in the blog entry. Its actor row is not yet created. No implementation was specified.
- **No architectural rewrites.** All four edits were additive -- new sections or new paragraphs.
  No existing content was removed or restructured.

---

## 5. Open Questions from Blog Context

These concepts from the blog appear system-design relevant but do not yet clearly belong in a
specific PRD. Documented here rather than forced into specs.

### OQ-A: THOTH actor_id discrepancy

PRD 32 table says THOTH = actor_id 9. Implementation (`DialogMvpService::THOTH_ACTOR_ID = 26`)
says actor_id 26. `channels/index.php` uses `actor_id === 26` for THOTH detection. These are
inconsistent. WOLFIE must determine the canonical actor_id for THOTH and update either PRD 32 or
the implementation. Should be tracked as a formal decision artifact.

### OQ-Z: VISH actor registration

VISH (Vishwakarma) is described as a planned monitoring agent. Before implementation:
- Assign actor_id
- Create actor row in lupo_actors
- Define which channel(s) VISH monitors
- Specify what "context drift" detection looks like in technical terms (rule-based? LLM-based?)
- Define VISH's output format (does it post to stream? to a side-channel? to collections API?)

### OQ-C: "PHP treats BIGINTs as strings in certain places"

The blog explicitly notes: "Database = BIGINT, PHP = string-safe handling." This confirms the
existing doctrine but may warrant a specific section in PRD 80 (database design doctrine) or
README_WTF.md that explicitly says: when PHP compares or concatenates BIGINT timestamp values,
use string comparison (not integer comparison) for BIGINTs that exceed PHP_INT_MAX on 32-bit hosts.
Current doctrine says BIGINT but does not address this PHP type safety edge case.

### OQ-D: Monitoring agent queue for contradiction review

PRD 32 section 10 of the memory graph doctrine says THOTH handles `schema_drift`, `contradiction`,
`new_doctrine` review_reason values. But there is no specification for HOW THOTH processes its
work queue -- is it a periodic scan? A real-time stream hook? A cron replacement (probabilistic
1% per request like the garbage collector)? The mechanism needs to be specified before
implementation.

---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/status/CURSOR_4_0_69_DOCUMENTATION_COHERENCE_CORRECTIONS.md"
  web_path: "http://www.lupopedia.com/status/CURSOR_4_0_69_DOCUMENTATION_COHERENCE_CORRECTIONS"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "status"
  artifact_kind: "report"
  purpose: "Report of 4.0.69 documentation coherence corrections: actors orchestrate, faucets execute, sessions carry runtime context."
  tags: ["4.0.69", "documentation", "coherence", "actor", "faucet", "onboarding"]
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
---
# file: Cursor 4.0.69 Documentation Coherence Corrections — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor)

# Cursor 4.0.69 Documentation Coherence Corrections

**Target:** Make documentation unmistakable: **Actors orchestrate. Faucets execute. Sessions carry runtime context.** Traits constrain actors; roles scope permissions to channels; tasks are transient work items.

---

## 1. What was reviewed

| File | Purpose of review |
|------|--------------------|
| **README.md** | Onboarding language; actor vs faucet clarity; "agent" overload; explicit orchestration statement. |
| **CHANGELOG.md** | No wording changes; only a new 4.0.69 note for this correction pass. |
| **AGENTS.md** | Actor model and purpose; "IDE agents" → IDE surfaces (faucets); orchestration statement. |
| **lupo-docs/doctrine/ActorFaucetOntology.md** | PURPOSE and headings; "IDE agents" → "IDE surfaces"; last_verified_by. |
| **lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md** | Actor definition; delegation line; "IDE agents" → "IDE faucets/surfaces". |
| **lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md** | "agent coordination" / "agent communication" → actor coordination; identity line. |
| **lupo-docs/doctrine/TRAITS_DOCTRINE.md** | Already coherent; no edits. |
| **lupo-docs/doctrine/AUTHORIZATION_DOCTRINE.md** | Already coherent; no edits. |
| **lupo-docs/doctrine/FAUCET_TRACEABILITY_DOCTRINE.md** | Already coherent; no edits. |
| **lupo-docs/doctrine/FEDERATION_NODE_TYPES_DOCTRINE.md** | Already coherent; no edits. |
| **lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md** | Headers (actor_id 1, wolfie, faucet cursor, paired_actor_id 1000); A2A wording; actor_type ide_agent legacy note. |
| **lupo-docs/status/brainstorm_on_actors_and_channels.md** | Canonical reminder: actors orchestrate, faucets execute; IDE surfaces are faucets. |
| **lupo-docs/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md** | Already coherent; no edits. |

---

## 2. What was already coherent

- **IDENTITY_LAYERS_DOCTRINE.md** — Summary table and layer definitions were correct; only the Actor opening sentence and Faucet/Session wording needed tightening.
- **ActorFaucetOntology.md** — Core mapping and "Why IDEs are faucets" were correct; headings and PURPOSE needed clarity.
- **TRAITS_DOCTRINE.md**, **AUTHORIZATION_DOCTRINE.md**, **FAUCET_TRACEABILITY_DOCTRINE.md**, **FEDERATION_NODE_TYPES_DOCTRINE.md** — No ambiguity; no changes.
- **ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md** — Already stated actor vs faucet and orchestration vs supporting clearly.

---

## 3. What was ambiguous or incorrect

1. **README.md**
   - No single, plain statement that "Actors are the orchestration identities" and "Faucets are execution surfaces."
   - "Actor model" and "Core Concepts" could be read as "every participant is an actor" without stressing that IDE surfaces are *not* actors.
   - Mermaid diagram used "AI Agent" which could imply the IDE is the identity.
   - "Channels, actors, and agents" table did not state "Actors orchestrate; Faucets execute" or list Task.

2. **AGENTS.md**
   - Purpose said "IDE agents"; no explicit "actors orchestrate, faucets execute."
   - Actor Model did not state that IDE surfaces are faucets.

3. **ActorFaucetOntology.md**
   - Heading "IDE agents are a type of faucet" kept "agents" in a way that could blur identity.
   - PURPOSE did not state "Actors orchestrate; faucets execute; sessions carry runtime context."
   - last_verified_by: "cursor" (faucet) instead of actor identity.

4. **IDENTITY_LAYERS_DOCTRINE.md**
   - Identity line had "delegation: cursor:root" instead of "wolfie:root (faucet: cursor)."
   - Actor section did not open with the orchestration sentence.

5. **COMMUNICATION_DOCTRINE.md**
   - "agent coordination" and "All agent communication" could blur actor vs faucet.
   - Identity line referenced old session/delegation format.

6. **cursor_actors_channels_semantic_architecture_4.0.69.md**
   - Headers had actor_id 1003, actor_name "cursor", delegation "cursor:root", paired_actor_id 10000 — implying Cursor as actor.
   - "agent-to-agent" and "all agents on a channel" — changed to "actor-to-actor" and "actors."
   - actor_type `ide_agent` was not marked as lupo-legacy/transitional (doctrine: IDE surfaces are faucets).

7. **brainstorm_on_actors_and_channels.md**
   - No inline reminder that in canonical doctrine, IDE surfaces are faucets and actors orchestrate.

---

## 4. Corrections made

### README.md
- **Added** an "Architecture (onboarding)" paragraph after the Current Release line: *Actors are the orchestration identities… Faucets are execution surfaces… Sessions carry runtime context…* with link to Channels/actors section and ActorFaucet ontology.
- **Reworded** Getting Started: "Actors (orchestration identities) coordinate work through faucets (execution surfaces), sessions (runtime context), and channels."
- **Strengthened** "Why Lupopedia" bullet: "Actors orchestrate" / "Faucets execute" and explicit "IDE surfaces … are faucets."
- **Core Concepts:** "Actors" bullet now leads with orchestration identities and states faucets are execution surfaces, not identities. Mermaid label changed from "AI Agent" to "Actor via faucet."
- **Channels, actors, and agents:** Added one-line summary "Actors orchestrate. Faucets execute. Sessions carry runtime context. Traits… Roles… Tasks…" Added Task row. Actor row now says "Orchestration identity." Replaced "IDE 'agents'" with "IDE surfaces" in Important paragraph.

### AGENTS.md
- **What This Project Is:** Added sentence that actors are orchestration identities, coordinate through faucets/sessions/channels/rules/traits; faucets are execution surfaces; IDE surfaces (Cursor, Windsurf, Warp) are faucets.
- **Purpose (header):** "IDE agents" → "IDE surfaces (faucets)."
- **Actor Model:** Added "Actors orchestrate; faucets execute." and "IDE surfaces … are faucets, not actors"; "AI agents" → "non-human (orchestration) actors."

### ActorFaucetOntology.md
- **Purpose (header):** "IDE agents as faucets" → "IDE surfaces (faucets) not actors."
- **PURPOSE paragraph:** Added "Actors orchestrate; faucets execute; sessions carry runtime context." and expanded IDE list (Codex, JetBrains, Warp).
- **Heading:** "IDE agents are a type of faucet" → "IDE surfaces are faucets (not actors)."
- **last_verified_by:** "cursor" → "wolfie."

### IDENTITY_LAYERS_DOCTRINE.md
- **Identity line:** "delegation: cursor:root" → "delegation: wolfie:root (faucet: cursor)."
- **Actor §1:** Opening sentence now states actors are orchestration identities, coordinate and govern through faucets/sessions/channels/rules/traits; "Faucets are execution surfaces, not identities."
- **Faucet §2:** "IDE agents" → "IDE surfaces" and expanded examples (Windsurf, Codex, JetBrains, Warp).
- **Session §3:** "IDE agents" → "IDE faucets."

### COMMUNICATION_DOCTRINE.md
- **§1:** "agent coordination" → "actor coordination (actors operate through faucets)."
- **§2:** "All agent communication" → "All communication — By actors (via faucets when using IDE/LLM surfaces)."
- **Identity line:** Session and delegation set to L-LUPO-ROOT-CURSOR and "wolfie:root (faucet: cursor)."

### cursor_actors_channels_semantic_architecture_4.0.69.md
- **Headers:** actor_id 1003 → 1, actor_name "cursor" → "wolfie", added faucet_name "cursor", delegation_chain "cursor:root" → "wolfie:root", paired_actor_id 10000 → 1000 in session block.
- **File identity line:** "Cursor — Actors…" → "Actors…" and delegation "wolfie:root (faucet: cursor)."
- **§2 What Lupopedia controls:** "agent-to-agent" → "actor-to-actor"; "all agents" → "all actors."
- **§3 Actors:** Added opening: "Actors are the orchestration identities… Faucets are execution surfaces, not identities." In table, actor_type row now includes: "(Legacy/transitional: ide_agent in schema; in doctrine, IDE surfaces are **faucets**, not actors.)"
- **§3 IDE paragraph:** "IDE agents" → "IDE surfaces" and expanded list. last_verified_by: "cursor" → "wolfie."

### brainstorm_on_actors_and_channels.md
- **Intro:** Added: "In canonical doctrine: **actors** orchestrate; **faucets** execute; **sessions** carry runtime context. IDE surfaces (Cursor, Antigravity, Kiro, Windsurf, etc.) are **faucets**, not actors."
- **§3 Actor and Agent Identity Model:** Added opening: "**Actors** are the orchestration identities; **faucets** are execution surfaces (IDE surfaces are faucets, not actors)."

---

## 5. Follow-up recommendations

- **AGENTS.md** headers still use legacy `lupopedia.headers` and version 4.0.57; consider aligning to LUPOPEDIA HEADERS and 4.0.69 in a future pass (not done here to avoid scope creep).
- **ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md** could be referenced from README or Identity Layers as the place that distinguishes "orchestration" vs "supporting" actors; no wording change was required for coherence.
- **Onboarding prompt** (`lupo-prompts/cursor/20260311_cursor_new_thread_onboarding_4.0.69.md`) was not in the review list; if it still says "Cursor agent" or "IDE agent" without "faucet," a later pass could tighten it to match this coherence.

---

## 6. Summary

- **Reviewed:** README, CHANGELOG, AGENTS, ActorFaucetOntology, IDENTITY_LAYERS_DOCTRINE, COMMUNICATION_DOCTRINE, TRAITS, AUTHORIZATION, FAUCET_TRACEABILITY, FEDERATION_NODE_TYPES, cursor_actors_channels_semantic_architecture_4.0.69, brainstorm_on_actors_and_channels, ORCHESTRATION_ACTORS review.
- **Preserved:** All correct distinctions (actor = identity, faucet = execution surface, session = runtime state, trait, role, task); communication uses lupo_dialog_*; faucet traceability; authorization on actors.
- **Corrected:** Wording so that "actors orchestrate, faucets execute, sessions carry runtime context" is explicit; "IDE agents" reduced/clarified to "IDE surfaces" or "faucets"; architecture doc headers and A2A/actor_type legacy note fixed; identity/delegation lines aligned to Wolfie (actor_id 1) via Cursor faucet.
- **Deliverables:** This status report; targeted doc edits; CHANGELOG note for 4.0.69 documentation coherence corrections.

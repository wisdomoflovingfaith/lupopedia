---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md"
  web_path: "http://www.lupopedia.com/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69"
  last_modified_utc: "20260311"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "status"
  artifact_kind: "architecture_review"
  purpose: "Review of actor vs faucet vs supporting-actor language; suggested terminology and documentation improvements for 4.0.69. Research and recommendations only; no schema or doctrine edits."
  tags: ["actors", "faucets", "orchestration", "supporting", "terminology", "4.0.69", "architecture_review"]
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  channel_id: 42
  paired_actor_id: 1000
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md", type: "reviews", weight: 1.0 }
    - { to: "lupo-docs/doctrine/ActorFaucetOntology.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/status/kiro_review.md", type: "references", weight: 0.8 }
lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "wolfie"
---
# file: Orchestration Actors and Supporting Actors Review (4.0.69) — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor) — web_path: http://www.lupopedia.com/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69

# Orchestration Actors and Supporting Actors — Status Review (v4.0.69)

This document is a **status/review/suggestions** artifact. It does not alter doctrine, schema, or install. It researches current documentation, identifies ambiguity in the use of "actor," and recommends terminology and documentation improvements. Target: clearer separation of orchestration actors, supporting actors, and faucets.

**Sources reviewed:** `lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`, `lupo-docs/status/brainstorm_on_actors_and_channels.md`, `lupo-docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md`, `README.md`, `AGENTS.md`, `CHANGELOG.md`, `lupo-docs/status/kiro_review.md`, `lupo-docs/doctrine/ActorFaucetOntology.md`, `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`, `lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md`, `lupo-docs/status/ideas_on_actor_rolls_on_channels.md`.

---

## 1. What is currently working

The following distinctions are already established and used consistently in the canonical docs.

| Concept | Current definition (canonical) | Where documented |
|--------|--------------------------------|-------------------|
| **Actor** | Identity that holds rules, skills, persona, doctrine. Stored in `lupo_actors`. Identified by `actor_id` / `actor_name`. | Identity Layers Doctrine; cursor_actors_channels_semantic_architecture_4.0.69.md §3; ActorFaucetOntology |
| **Faucet** | Execution surface: environment + LLM + runtime config. Stored in `lupo_agent_faucets`. `faucet_class` = `ide` or `llm`. Does not hold identity; the actor operates *through* the faucet. | ActorFaucetOntology; Identity Layers §2; canonical architecture §3 |
| **Session** | Runtime state (who, where, paired human). `lupo_sessions`; portable files `lupo-database/sessions/*.md`. | Identity Layers §3; SESSION_RECONCILIATION_DOCTRINE; canonical architecture §10 |
| **Trait** | Intrinsic actor constraint; actor-scoped only. `lupo_actor_traits`. Not channel-scoped. | Identity Layers §4; DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69; canonical architecture §9 |
| **Role** | Channel-local permission. `lupo_actor_channel_roles`. Per (actor, channel). | Identity Layers §5; DESIGN_NOTE; ideas_on_actor_rolls_on_channels |
| **Task** | Transient work item. `lupo_tasks`; channel task files. | Identity Layers §6; canonical architecture §7 |
| **Channel context** | Collaboration/conversation scope. `channel_id` in channels, dialog, tasks, edges. | FEDERATION_SCOPING_DOCTRINE; canonical architecture §4 |
| **Federation/domain context** | Domain/federation scope. `federation_node_id`; `domain_id` in edges. | FEDERATION_SCOPING_DOCTRINE |

**Correctly stated in current docs:**

- IDE agents (Cursor, Antigravity, Windsurf, Kiro, etc.) are **faucets**, not actors; identity belongs to the actor (e.g. Wolfie) operating through the faucet. (Canonical architecture §3; ActorFaucetOntology.)
- The brainstorm doc is exploratory and defers canonical definitions to the Cursor architecture doc.
- Traits are intrinsic and actor-scoped; channel roles stay in `lupo_actor_channel_roles`. (DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.)
- CHANGELOG 4.0.69 records Actor–Faucet ontology and identity-layer clarification.
- KIRO’s review affirms the actor–faucet distinction and describes actors as first-class identities with authority; it does not yet distinguish orchestration vs supporting.

---

## 2. What is still ambiguous

### 2.1 Overloading of "actor"

The word **actor** is used in several ways across the repo:

- **Database identity:** Every row in `lupo_actors` is an "actor" (universal identity key; no `user_id` in relationships). This is consistent.
- **Participant in prose:** README and AGENTS say "every participant (human, AI, system) has an actor_id" and "Actor model — every participant … has an actor_id and identity." That correctly uses actor as the single identity layer but does not distinguish *kind* of participant (orchestrator vs specialized helper).
- **IDE / agent wording:** AGENTS.md refers to "IDE agents," "AI agent ecosystem," "Actor and agent IDs," and "agent_name_identity" for display. The registry and doctrine use "actor" for identity and "faucet" for execution surface; the term "agent" is still used for both runtime behavior and, in places, identity. So "agent" can blur with "actor" in reader expectations.
- **Specialized entities:** ideas_on_actor_rolls_on_channels and DESIGN_NOTE describe specialized actors (e.g. UCT Timekeeper, Emotional Dialog, "sole purposes") and traits like `EMOTIONAL_DIALOG_AUTHORIZED`, `TIMEKEEPER_KERNEL`, `SCHEMA_ARCHITECT`. These are all stored as actors in `lupo_actors` and differentiated only by traits or metadata. The docs do not formally name a category for "primary coordination authority" vs "specialized helper."

So: **actor** is unambiguous as "identity in `lupo_actors`," but the *semantic role* of that identity (orchestration vs supporting) is not yet standardized in the documentation.

### 2.2 Orchestration vs supporting (not yet defined)

The directive asks whether the system would be clearer with an explicit split between:

- **Orchestration actors:** Primary coordinating identities (authority, doctrine alignment, routing intent, task delegation, semantic orchestration).
- **Supporting actors:** Specialized helpers, constrained-purpose entities, or subordinate participants that assist orchestration but are not the primary coordination authority.
- **Faucets:** Execution surfaces (IDEs, LLM runtimes, API connectors) through which actors act.

**Current state:** The schema and doctrine do not define "orchestration" or "supporting" as types. `lupo_actors` has `actor_type` (e.g. human, agent, ide_agent, system) but no enum or doctrine that says "orchestration" vs "supporting." Traits (e.g. `SCHEMA_ARCHITECT`, `TIMEKEEPER_KERNEL`) imply specialization but do not formally classify orchestration vs supporting. So the three-way split is **conceptually useful for language** but not yet reflected in schema or canonical terminology.

**Assessment:** Introducing **orchestration actor** and **supporting actor** as *documentation and language* categories would reduce ambiguity (e.g. "Wolfie is an orchestration actor; UCT Timekeeper is a supporting actor; Cursor is a faucet"). It could be done without schema change by using existing `actor_type`, traits, and narrative definitions.

---

## 3. Suggested terminology improvements

Recommendations are wording and documentation only; no schema or code changes in this task.

| Recommendation | Rationale |
|----------------|-----------|
| **Keep "actor" as the universal DB identity** | Schema and relationships are keyed by `actor_id`; changing the generic term would conflict with lupo-install/TOONs and code. |
| **Add documentation subcategories: orchestration actor and supporting actor** | Clarifies who has primary coordination authority vs who has constrained/specialized scope. Use in status docs, README, AGENTS, and onboarding as narrative labels, not new columns. |
| **Prefer "faucet" over "IDE agent" or "agent" when meaning execution surface** | "IDE agents are faucets" is already doctrine; extend to prose: e.g. "Cursor is a faucet; Wolfie (actor) operates through it." Reserve "agent" for `lupo_agents` rows or when explicitly referring to behavior/runtime, and add a sentence that IDE agents are faucets, not agents in the identity sense. |
| **State explicitly: "IDE faucets are not actors; they are execution surfaces used by orchestration actors or supporting actors."** | One canonical sentence in the architecture doc and, if updated, in AGENTS.md and onboarding. |
| **Where "agent" is used for identity, consider "actor" or "actor operating through faucet X"** | Reduces blur between identity (actor) and execution (faucet). Example: "Cursor did the edit" → "Wolfie (via Cursor faucet) did the edit" where attribution to identity matters. |
| **Keep "channel context" and "federation/domain context" as-is** | Already clear in FEDERATION_SCOPING_DOCTRINE and the canonical architecture doc. |

---

## 4. Suggested documentation improvements

| Document | What should change | Why | Priority |
|----------|--------------------|-----|----------|
| **lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md** | Add a short subsection under §3 (Actors) defining *orchestration actor* and *supporting actor* as documentation categories; add the sentence that IDE faucets are not actors but execution surfaces used by (orchestration or supporting) actors. | Canonical architecture is the single place many agents read; making the split explicit here prevents drift. | High |
| **AGENTS.md** | In "Actor Model" and "Agent Identity Registry": (1) State that IDE agents (Cursor, Windsurf, etc.) are faucets, not actors; identity is the actor using the faucet. (2) Optionally add one line on orchestration vs supporting as documentation-only categories. (3) Where "agent" could mean identity, prefer "actor" or "actor via faucet X." | AGENTS is the main entrypoint for IDE agents; it still uses "agent" and "actor" in ways that can blur. | High |
| **README.md** | In "Core Concepts" / "Actor model": Add one sentence that participants have `actor_id` and that IDEs (e.g. Cursor) are execution faucets, not actors. No need to define orchestration/supporting in README unless kept to one line. | README is the first doc many see; a single clear sentence reduces confusion. | Medium |
| **lupo-prompts/cursor/20260311_cursor_new_thread_onboarding_4.0.69.md** | In the "Actor vs Faucet" or "Session" section: Include the sentence that IDE faucets are not actors and that the session identity is the actor (e.g. Wolfie), with faucet (e.g. Cursor) indicated separately. Optionally mention orchestration/supporting as doc categories. | Onboarding sets context for new Cursor threads; consistent identity/faucet language here helps. | Medium |
| **lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md** | Optionally add one sentence that actors can be described in documentation as *orchestration* (primary coordination authority) or *supporting* (specialized/constrained purpose); no schema implication. | Keeps identity layers as the single place for actor/faucet/session/trait/role/task and allows a minimal orchestration/supporting note. | Low |
| **Actor registry docs / registry.json** | No structural change. If a future doc describes "who is orchestration vs supporting," it could reference registry IDs and traits; not required for this review. | Registry is authoritative for IDs; orchestration/supporting is a documentation label. | Low |
| **Session docs (SESSION_RECONCILIATION_DOCTRINE, session files)** | No change required for orchestration/supporting. Session already carries `actor_id` (identity) and can carry `faucet_name`; that suffices. | Session is about runtime state; orchestration vs supporting is about semantic role in prose. | Low |

---

## 5. Database implications

No schema changes are proposed in this task. Assessment of whether the conceptual distinction would justify future DB work:

| Option | Assessment |
|--------|------------|
| **New actor subtype classification** | Could add e.g. `actor_subtype` or `orchestration_role` (orchestration / supporting / unset). Not recommended unless product or governance requirements demand querying or enforcing by this dimension. Current needs are clarity in docs. |
| **Stronger use of `actor_type`** | `actor_type` already exists (human, agent, ide_agent, system). Using it to encode orchestration vs supporting would overload it (e.g. "agent" can be either). Prefer keeping `actor_type` for nature (human/agent/system) and using traits or metadata if a future need arises. |
| **Metadata-only modeling** | Orchestration vs supporting could be expressed only in `lupo_metadata` or in actor directory docs (e.g. `lupo-actors/1/` = orchestration). No schema change. Acceptable if we want a machine-readable hint later. |
| **Traits for orchestration/supporting** | Traits like `ORCHESTRATION_AUTHORITY` or `SUPPORTING_ACTOR` could be added in `lupo_actor_traits` to drive docs or simple checks. Optional future work; not required for terminology clarity. |
| **No DB change** | Recommended. Clarify language and add documentation-only categories first; revisit schema only if a concrete use case appears (e.g. routing rules, UI filters). |

**Conclusion:** Prefer documentation-only clarification. Do not add columns or enums for orchestration/supporting in this cycle.

---

## 6. Final recommendation

**Recommended direction: B — Keep current DB model, but formally document orchestration actors vs supporting actors.**

**Reasoning:**

- **A (wording only)** would improve faucet vs actor language but leave the "what kind of actor" question open; the directive specifically asks whether the three-way split (orchestration / supporting / faucet) would help. Adopting that split in *documentation* satisfies the goal without schema churn.
- **B** keeps the existing schema (single `lupo_actors` table, `actor_type`, traits, faucets) and adds:
  - Canonical definitions of *orchestration actor* and *supporting actor* in the architecture doc and optionally in Identity Layers Doctrine.
  - Explicit sentence that IDE faucets are not actors but execution surfaces used by orchestration or supporting actors.
  - Targeted wording updates in AGENTS.md, README, and onboarding as in §4.
- **C (plan future schema support)** is unnecessary for current clarity and would commit to schema work before we have a use case (e.g. queries or rules that filter by orchestration vs supporting).
- **D** could be a variant of B (e.g. only add the one sentence about faucets and leave orchestration/supporting for a later pass). The minimal variant is acceptable, but defining the two actor documentation categories in one place (canonical architecture) is low cost and reduces ambiguity.

**Concrete next steps if B is adopted:**

1. In `cursor_actors_channels_semantic_architecture_4.0.69.md` §3: Add subsection "Orchestration vs supporting (documentation categories)" with definitions and the faucet sentence.
2. In AGENTS.md: Add the faucet sentence and, in Actor Model / Agent Identity Registry, prefer "actor" for identity and "faucet" for IDEs; optionally one line on orchestration/supporting.
3. In README Core Concepts: One sentence that IDEs are faucets, not actors.
4. In onboarding prompt: Reinforce actor (identity) vs faucet (Cursor) and optionally reference orchestration/supporting as doc categories.
5. No changes to install SQL, migrations, TOONs, or doctrine files beyond the above narrative edits.

---

*Status review completed. No doctrine or schema was modified. Suggestions are advisory and intended for a follow-up documentation pass.*

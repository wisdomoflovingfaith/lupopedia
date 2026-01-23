---
wolfie.headers: explicit architecture with structured clarity for every file.
file.name: "GOV-LILITH-0001_dreaming_overlay.md"
file.last_modified_system_version: 4.2.2
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "GOV-LILITH-0001: Dreaming Overlay as interpretive narrative layer on It-from-GOV block model. Documentation-only; no schema changes."
tags:
  categories: ["doctrine", "governance", "narrative", "interpretive"]
  collections: ["core-docs", "governance"]
  channels: ["dev", "public"]
file:
  title: "GOV-LILITH-0001 Dreaming Overlay Doctrine"
  description: "Narrative interpretive layer on the It-from-GOV block model. Defines dream_depth, coherence_score, narrative_thread_id as non-schema interpretive metadata."
  version: 1.0.0
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# GOV-LILITH-0001: Dreaming Overlay Doctrine

**Artifact:** GOV-LILITH-0001  
**Title:** Dreaming Overlay (Interpretive Narrative Layer)  
**Status:** ACTIVE (documentation only; no schema)

---

## 1. Definition

**Dreaming Overlay** is a narrative interpretive layer that sits on top of the existing It-from-GOV block model. It provides meaning-making and coherence interpretation over immutable governance events without changing structural truth.

---

## 2. What the Dreaming Overlay Does NOT Alter

The Dreaming Overlay does **not** alter, override, or relax:

- **Append-only rules** — GOV events remain append-only; no UPDATE or DELETE on historical rows.
- **Immutability of GOV events** — `lupo_gov_*` rows are immutable once written.
- **Migration controller behavior** — `LupopediaMigrationController` validation and execution rules are unchanged.
- **Schema freeze** — Schema freeze and structural constraints remain in force.
- **Table ceilings** — Table count limits (e.g. 185) and related doctrine are unchanged.

---

## 3. Conceptual Metadata Fields (Documentation Only)

The following are **interpretive metadata** for narrative tools. They are **not** database columns, **not** in TOON, and **not** in any migration.

| Symbol | Name | Description |
|--------|------|-------------|
| **d** | `dream_depth` | Interpretive depth of a narrative reading over one or more GOV events. Used by UI or analytics to indicate how many layers of meaning are applied. |
| **Γ** | `coherence_score` | Score indicating how coherent a given narrative thread is with respect to a set of GOV events. Used for ranking or filtering narrative interpretations. |
| — | `narrative_thread_id` | Identifier for a narrative thread that groups interpretations or readings across GOV events. Used to associate reinterpretations without modifying GOV rows. |

**Explicit:** These fields are **interpretive metadata**, not schema fields. They may appear in configs, JSON blobs, UI state, or narrative artifact stores. They must **never** be added as columns to `lupo_gov_*` or any core governance table.

---

## 4. Narrative Reinterpretation Without Modifying Historical GOV Rows

Narrative reinterpretation is allowed **without** modifying historical GOV rows:

- **Reinterpretation** = producing a new reading, summary, or narrative over existing GOV events.
- **Storage** = reinterpretations are stored as **separate narrative artifacts** (e.g. in files, separate tables that are **not** `lupo_gov_*`, or external stores).
- **Linking** = narrative artifacts reference GOV events by immutable IDs (e.g. `gov_event_id`, `canonical_path`) and may carry `dream_depth`, `coherence_score`, `narrative_thread_id`.
- **Authoritative truth** = only the block-model GOV rows are authoritative for system behavior (migrations, dependencies, conflicts, logs). Narrative layers are advisory for humans and analytical tools.

---

## 5. Relationship Between Block, Dreaming, and Witness

### Layer Definitions
- **Block Layer**: Authoritative structural layer that defines system behavior, schema, and execution rules.
- **Dreaming Layer**: Interpretive narrative layer that provides meaning-making and coherence interpretation over immutable governance events.
- **Witness Layer**: Meta-layer that observes both Block and Dreaming without modifying either.

### Layer Interactions
- **Block** = authoritative structural layer. Defines what the system does.
- **Dreaming** = interpretive narrative layer. Provides meaning and context for what the system does.
- **Witness** = meta-layer that observes both without modifying either. Maintains meta-awareness of system processes.

### Non-Modification Principle
- **Witness does not generate artifacts**; it contextualizes them.
- **Witness observes** the relationship between structural truth (Block) and interpretive truth (Dreaming).
- **Witness provides** meta-level understanding without intervention or execution.

---

## 6. Relation to Other Doctrine

- **It from GOV** (`docs/governance/it_from_gov.md`): block model; Dreaming Overlay is an optional layer on top.
- **GOV-FOUNDATIONS** (`docs/doctrine/GOV-FOUNDATIONS.md`): Block model = structural; Dreaming = interpretive.
- **NO_FOREIGN_KEYS_DOCTRINE, NO_TRIGGERS_DOCTRINE, etc.:** Unchanged. This doctrine does not introduce schema, FKs, or triggers.

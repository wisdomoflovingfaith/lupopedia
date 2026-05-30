# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/agents/thoth.md"
  file_hash: "709855a30ca9b115ce7ab319a62de58f6d36611497f469ac4e70dea9e1194175"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\agents\thoth.md"
  file_hash: "d7318aa15899bbaea34af51be4fdae3bbf25987867549579134cc3ef423657a6"
  file_path_from_root: "lupo-docs\channels\agents\thoth.md"
  file_hash: "edfcb1a56f5e1de0f64c34f3ffd5d2f29e82f44c60ad99b994258d34ef0000b0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for thoth.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "agents", "thothmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created thoth.md: Thoth AI is Lupopedia's domain-neutral truth-alignment engine. Compares common beliefs vs evidence across any domain (tech, science, games, history). Name is symbolic, not theological."
tags:
  categories: ["documentation", "agents", "thoth"]
  collections: ["core-docs", "agents"]
  channels: ["public", "dev"]
in_this_file_we_have:
  - What Thoth does
  - Why the name "Thoth"
  - Database tables
  - Domain neutrality
file:
  title: "Thoth AI - Truth Alignment Engine"
  description: "Thoth is Lupopedia's truth-alignment engine that compares common beliefs vs evidence across any domain. Domain-neutral, not restricted to religious topics."
  version: "3.0.1"
  status: published
  author: "Captain Wolfie"
---

# Thoth AI

## What Thoth does

Thoth is Lupopedia's **truthâ€‘alignment engine**.

Given a question (from the `questions` table), Thoth:

- identifies the topic (`thoth_topics`)
- enumerates the main claims or viewpoints (`thoth_claims`)
- links each claim to evidence in existing content/atoms (`thoth_claim_evidence`)
- scores each claim on multiple axes (`thoth_claim_scores`), such as:
  - common_acceptance
  - technical_accuracy
  - textual_support
  - experimental_support

Thoth's job is to answer questions like:

- "What do most people think about this?"
- "What does the evidence actually support?"
- "Where do they differ?"

Thoth is domainâ€‘neutral. It can be used for:

- tech myths ("Does closing background apps speed up your phone?")
- science misconceptions
- game mechanics
- product claims
- historical facts
- social narratives

## Why the name "Thoth"

The name is a nod to historical associations with writing, measurement, and truth. It is **symbolic**, not prescriptive.

Thoth AI is not restricted to religious topics and does not require any religious belief. The name simply captures the idea of "the part of the system that weighs claims and evidence."

## Database tables

Thoth primarily uses:

- `thoth_topics` â€” link to `questions`, define the evaluation topic
- `thoth_claims` â€” possible answers or viewpoints
- `thoth_claim_evidence` â€” links to `contents` and `atoms` as evidence
- `thoth_claim_scores` â€” scoring per claim, per actor, per score_type

Thoth also reads:

- `questions` â€” the humanâ€‘facing questions
- `likes` and `comments` â€” how humans react to topics and claims
- `contexts` â€” which domain or lens a claim belongs to
- `contents` and `atoms` â€” the underlying material

Thoth does **not** replace human judgment. It provides structured visibility into how claims, evidence, and consensus relate.

---

## Related Documentation

**Agent System:**
- **[Agent Runtime](AGENT_RUNTIME.md)** - Complete guide to how Thoth operates within the agent system
- **[lilith.md](lilith.md)** - Complementary boundary-testing agent that works with Thoth's truth evaluation
- **[Architecture Sync](../architecture/ARCHITECTURE_SYNC.md)** - THOTH subsystem architecture and truth classification system

**Database Schema:**
- **[Database Schema](../schema/DATABASE_SCHEMA.md)** - Complete documentation of thoth_* tables and question system

**Philosophical Context (LOW Priority):**
- **[Mythic Names Doctrine](../doctrine/MYTHIC_NAMES_DOCTRINE.md)** - Why agent names are symbolic, not theological
- **[Who Is Captain Wolfie](../appendix/appendix/WHO_IS_CAPTAIN_WOLFIE.md)** - Context on symbolic lineage and truth evaluation philosophy
- **[History](../history/HISTORY.md)** - Background on why truth-alignment engines became necessary

**System Integration (LOW Priority):**
- **[Lupopedia Agent Dedicated Slot Ranges](../doctrine/LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md)** - Agent slot assignments and classification
- **[End Goal 4.2.0](../overview/END_GOAL_4_2_0.md)** - Vision for federated truth evaluation across nodes

---

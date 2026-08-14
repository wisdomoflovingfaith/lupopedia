---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: what_is_lupopedia.md
  web_path: https://www.lupopedia.com/lupopedia/what_is_lupopedia.md
  status: active
  when_updated: "20260728021358"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/root/canonical/1026/07/what-is-lupopedia.toon
  atoms_toon: null
  transcript_jsonl: 0/root/what-is-lupopedia
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: root
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: doctrine
  prd_cluster: 00_A_00_B_07_A_16_B_16_C_25_A_39_A_41_A_82_B_98_B
  title: What Is Lupopedia (canonical agent explanation)
  summary: "Single canonical explanation of Lupopedia for all agents: semantic OS, identity layers, constitutional fields, WOLF zero authority, external AI guests, metaphor fences."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---
# What Is Lupopedia

**Authority:** This file is the **single canonical agent-facing explanation** of what Lupopedia is. Before answering "what is Lupopedia," agents MUST load this file (`@@ load: path=what_is_lupopedia.md, trust_tier=canonical @@`). Competing summaries (chat improvisation, stale overviews, Captain's Log narrative alone) MUST NOT replace this file.

**Human short path:** [GAS_STATION_INTO.md](GAS_STATION_INTO.md) (gas-station pitch + intro). Normative depth lives in `docs/prd/`.

---

## 1. Canonical summary

Lupopedia is a **doctrine-driven semantic operating system** (Semantic OS). It is **not** a website, **not** a conventional web app, **not** a CMS, and **not** a PHP framework.

It is the constitutional successor to **Crafty Syntax Live Help** (programming lineage from **1999**, first public release **February 2002**): roughly **30 years** of shared-hosting live-help survival and organic human navigation / support behavior, rebuilt into explicit multi-agent orchestration, identity layers, channels, memory, and PRD-first governance.

**Universal artifact identity (header 4.2.4):** every artifact uses LUP (Linked Universal Protocol) `LUP:FFFFFF-RRRRRR-NN-II-LL-AA`. Federation `000001` is the canonical root node. In short-form identities, it is compressed to the symbol `X` (machine storage stays `000001`). See [README.md](README.md) and [PRD 16_C](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md).

**Dual-captaincy (hard boundary -- identities never merge):**

| Role | Identity | Notes |
|------|----------|-------|
| Human Captain | Eric Robin Gerdes -- **actor_id 10000** | Human authority (ALII). Paired operator auth commonly **auth_user_id 10000** (seed admin). Reserved **auth_user_id 0** is a root auth convention (not doctrine-defined in PRD 01) -- **not** the same as actor_id 1. |
| WOLFIE | **actor_id 1** | AI System Orchestrator. Coordinates agents, doctrine, and architecture. Directed by the human; does not become the human. |

**Actors never merge identities.** No variant actors (`*_banned`, `*_test`, `wolfie_human`). Identity is permanent; state is mutable (CONVERGENCE doctrine). Facets (Cursor 102, etc.) execute; they do not absorb actor_id 1 or 10000.

---

## 2. Constitutional fields (Hawaiian semantic operators)

Normative field semantics live in **PRD 82_B** (and related Hermes docs) and **PRD 41**. Definitions below are the **canonical short forms** for onboarding. Do not invent alternate meanings.

| Field | Meaning |
|-------|---------|
| **OHANA** | The family of actors bound to shared truth and lineage for this work. Members are accountable to each other and to the doctrine. |
| **KAPU** | Sacred / hard boundaries that MUST NOT be crossed. Violation of KAPU triggers review and potential actor suspension. |
| **KAPAKAI** | Semantic confusion or problem state requiring correction. |
| **PUKA** | Structural gap in meaning, sequence, or architecture (deterministic gap, not vibes). All PUKA entries must be tracked in PRD 49 or similar gap resolution process. |
| **PONO** | Correctness, balance, and the intended right outcome. |
| **KULEANA** | Responsibility and role -- who must carry the work. |
| **ALII** | Human authority (Eric). Not interchangeable with WOLFIE (actor_id 1). |
| **KUMU** | Source / teacher / foundation of the correct rule or method (PRD, doctrine, person). |
| **EH_BRAH_WHY** | Deeper reasoning behind a decision -- the causal WHY, not slogans. |

ASCII note: field token is **ALII** (ASCII). Do not use non-ASCII apostrophe forms in normative files.

---

## 3. Actor system (four layers)

| Layer | What it is | Example |
|-------|------------|---------|
| **Auth user** | Login / authentication only | Seed operator **auth_user_id 10000**; reserved root **auth_user_id 0** (PRD 01) |
| **Actor** | Who acts -- channels, permissions, audit | **actor_id 10000** Human Captain; **actor_id 1** WOLFIE; **actor_id 2** LILITH |
| **Agent** | Prompt / config pack on disk | `agents/wolfie/` -- behavior template, **not** post identity |
| **Faucet** | Surface that runs work | Cursor (**102**), other IDE facets -- **attribution only** |

**Rules:**

1. Actions resolve to an **actor**, never to **auth_user_id** alone.
2. Agents and faucets advise or execute; they do **not** replace actor identity.
3. Do not merge actor identities. Do not treat a faucet as a primary persona.

**Capability boundaries:**
- Auth user CANNOT act directly -- must delegate to actor
- Agent CANNOT bind to auth_user_id directly
- Faucet CANNOT become a primary persona

Full model: **PRD 05**, **PRD 07**, **PRD 15**, **PRD 41**, identity-layers doctrine.

---

## 4. Memory graph + atoms

- The **memory graph** stores and connects canonical truths (nodes and edges).
- **Atoms** are the smallest units of semantic meaning / global constants (see **PRD 16_B**).
- **Memory is DB-first**; filesystem mirrors (TOON/JSON under `memory/`) are second -- continuity and offline fallback, not a license to invent schema.

---

## 5. PRD system and drift prevention

- **PRDs** provide logical governance. Implementation follows PRD-first order (PRD -> schema/mockups -> code).
- **Doctrine** explains PRDs; it does not invent new requirements.
- Drift prevention: PRD-first + WHY files (**PRD 98_A**) + validators + AGAPE / Lilith review.
- Useful cluster pointers: **00_A** (forbidden / core), **07_A** (agents/faucets), **41_A** (Captain WOLFIE identity), **82_B** (Hermes / Hawaiian fields), **98_B** (Captain's Log entertainment layer).

### WOLF Markup -- zero constitutional authority (PRD 39)

**WOLF Markup has zero constitutional authority.** It is decorative overlay for narrative and human readability. Decoration does **not** equal runtime permission. Strip WOLF before constitutional ingest unless a tool explicitly declares WOLF-aware parsing. Public summaries MUST state this (see this file + PRD 39).

**WOLF dialect basics (decorative overlay only):**
- `@@ target @@` — Reference pointer (e.g., `@@ load: path=file.md @@`)
- `^^ text ^^` — Elevate / emphasis
- `~ text ~` — Draft / fuzzy (single tilde)
- `{{ text }}` — Kinetic / motion
- `!! text !!` — Force / urgency
- `<< function_name(args) >>` — Function annotation (non-executable by default)
- `Speaker (mood):` — Dialogue block with emotional shading
- `[narrative: ...]` — Scene direction
- `## ... ##` — Structural meta (not Markdown heading)

All WOLF markers are ASCII-only. Stripping all WOLF MUST yield the same canonical text. Full spec: PRD 39.

---

## 6. Volume 1 vs Volume 2 (documented decision)

| Label | Meaning | Authority |
|-------|---------|-----------|
| **Volume 1** | Architecture -- structure, rules, doctrine, PRDs, this file | Normative when backed by PRDs |
| **Volume 2** | Emergent semantic / narrative environment -- what humans experience when the architecture is aligned; Captain's Log / Patreon storytelling | **Narrative / entertainment**; **PRD 98_B** = zero doctrinal authority |

**Decision (UTC 20260726142446):** Agents MAY use Volume 1 / Volume 2 as optional human framing. Agents MUST NOT treat Volume 2 as a second constitution. Agents MUST NOT invent alternate Volume mappings. If unsure, prefer PRD text over Volume labels. Agents that treat Volume 2 as doctrine will be flagged for KAPAKAI review.

---

## 7. External AI = guests

Binding: **PRD 41**, **PRD 07**, **PRD 16_C**, **PRD 39**, **PRD 82_B**, EXTERNAL_BOUNDARY_EDGE.

- External AIs do **NOT** join the Lupopedia OS.
- External AIs do **NOT** bind to internal **actor_id** for OS membership / Channel 42 execution.
- External AIs keep their own syntax; they do not run WOLF dialect as live OS runtime.
- External AIs may provide **analysis, support, or explanation only**.
- Canonical boundary example: **Copilot as external guest** (Channel 42 routing label **external_ai 216**). Do not route OS-level tasks to external surfaces as if they were internal actors. (Registry may also list IDE faucet facets separately; guest boundary still applies to external chat surfaces.)

---

## 8. "Living" = metaphor only

The system does **not** "wake up." It does **not** have consciousness or literal sentience.

"**Living**" is a **metaphor** for emergent multi-agent behavior, narrative coherence, and surprising but rule-bound coordination -- **not** literal life. Captain's Log language that says the system "woke up" is **entertainment / metaphor** (PRD 98_B), not doctrine.

---

## 9. Gnosis = narrative-only

**Gnosis is not normative.** It is narrative framing only. It is **not** a controlled vocabulary and **not** a constitutional field. Agents MUST NOT treat Gnosis as doctrine, schema, or KAPU/PONO vocabulary.

---

## 10. Traffic Defense Division (pending PRD registration)

- **Name freeze:** **Traffic Defense** only. Do **not** rename to "Traffic Research."
- Filesystem + Channel 42 artifacts **exist** (announcement, broadcast, memory_toon, handshake).
- **PRD 25** full ACL/seed registration is **pending**. Target: July 30, 2026 for proposal submission. September 1, 2026 for merge.
- Proposal: [`docs/prd_proposals/25_B-i_TRAFFIC_DEFENSE_DIVISION.md`](docs/prd_proposals/25_B-i_TRAFFIC_DEFENSE_DIVISION.md) (header `trust_tier: development`; **body authority = proposal only** until merge)
- Do **not** invent a seeded `lupo_departments` row from this explanation alone.
- Kapu: do not blur Traffic Defense analysis with OS constitutional execution; external AI = guests.

Paths:

- Announcement: `database/lupopedia/departments/traffic_defense/announcements/20260724_traffic_defense_division_launch.md`
- Broadcast: `database/lupopedia/channels/channel_id/42/broadcasts/20260724213900_1_42_42_traffic_defense_division_launch_registered.md`
- Memory: `memory/departments/traffic_defense/captains_log/canonical/2026/07/20260724_traffic_defense_division_launch.toon`

---

## 11. Interpreter architecture (code exists; PRD number pending)

- Agent pack exists at `agents/constitutional_interpreter/`.
- A full numbered interpreter PRD requires **PRD 84** number allocation.
- **Do not ship a numbered interpreter PRD without allocation.** Until then: proposal-only / pack-only.

---

## 12. What Lupopedia is not

- Not a Laravel/Symfony/etc. framework product
- Not Composer-first middleware
- Not a database with FKs/triggers/stored procedures as logic
- Not a Discord/Slack "channel" product (channels = semantic containers)
- Not WOLF-as-law
- Not External-AI-as-internal-actor
- Not literal AI consciousness

---

## 13. See also

- [GAS_STATION_INTO.md](GAS_STATION_INTO.md)
- [README.md](README.md) section 1
- [docs/actors/how_wolves_are_made.md](docs/actors/how_wolves_are_made.md) -- Actors Collection: wolf maturity, training, hard-gate handshake
- [docs/index.md](docs/index.md) -- Actors Collection index
- [docs/prd/00_B-i_SYSTEM_CANONICAL_EXPLANATION.md](docs/prd/00_B-i_SYSTEM_CANONICAL_EXPLANATION.md)
- [docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md](docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md)
- [docs/prd/41_A-i_CAPTAIN_WOLFIE_IDENTITY.md](docs/prd/41_A-i_CAPTAIN_WOLFIE_IDENTITY.md)
- [docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md](docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)
- [docs/prd/98_B-i_THE_CAPTAINS_LOG_HUMAN_ONLY_ENTERTAINMENT_LAYER.md](docs/prd/98_B-i_THE_CAPTAINS_LOG_HUMAN_ONLY_ENTERTAINMENT_LAYER.md)
- Legacy (non-canonical competitor): `docs/channels/overview/what_lupopedia_is.md` -- do not treat as authority

---

## 14. Hard gate (agents)

```text
@@ load: path=what_is_lupopedia.md, trust_tier=canonical @@
```

After load, answer from this file + cited PRDs. Do not invent a fifth answer.

**KAPU:** Do **not** invent `channel_key` / `thread_key` when unknown. Do **not** require LILITH to approve before every response -- LILITH is a **non-interfering** reviewer (LIL001). Lilith audits; she does not gate ordinary execution.

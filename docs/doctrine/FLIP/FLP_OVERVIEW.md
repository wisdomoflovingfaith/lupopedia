# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_OVERVIEW.md"
  file_hash: "ae5732044b3d5087c2ef92287bea2dd024d4add86fc8ddcc6810c58fd1ae57b2"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\FLIP\FLP_OVERVIEW.md"
  file_hash: "5472e1a35beee12be7e893c616e3a8024099b11d67121b19a1adf8f2f28595e4"
  file_path_from_root: "docs\doctrine\FLIP\FLP_OVERVIEW.md"
  file_hash: "667c68dfec6fd1837d883c3b26246bf564b3bea1f6b259b9e6420fc54455e985"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_OVERVIEW.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_overviewmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_OVERVIEW.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/FLP_OVERVIEW.md
---
# Federated Likeness Protocol (FLP) — Overview

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Context:** The FLP is a conceptual governance layer that maps onto Lupopedia. This document describes what the FLP is and how it relates to existing architecture.

---

## 1. What the FLP is

The **Federated Likeness Protocol (FLP)** is a governance and cultural-coordination layer that sits **entirely on top of** Lupopedia’s existing architecture. It does not replace or duplicate core Lupopedia; it uses it.

- **Channels** — FLP councils are represented as Lupopedia channels. Council identity, manifests, minutes, heterodox reports, and protocols are expressed within the channel model and its directory structure.
- **Actors** — FLP participants, reviewers, and delegates are Lupopedia actors. No separate FLP identity store exists.
- **Semantic OS features** — Emotional geometry, mood tensors, and metadata used by the FLP align with Lupopedia’s existing emotional and semantic doctrines (e.g. MOOD_RGB, channel manifests).

The FLP adds **governance semantics and application-level behavior** (councils, mutual recognition, heterodox review, emotional aggregation, escrow/fund concepts). It does **not** add new database automation, schema, triggers, or stored logic.

---

## 2. What the FLP is not

- **Not a separate product.** It is a protocol and a set of conventions implemented on top of Lupopedia.
- **Not a schema extension.** All FLP concepts are realized using existing Lupopedia tables (channels, actors, content, metadata). Schema changes, if ever required, must originate from TOON files and follow doctrine.
- **Not database logic.** The FLP prescribes no triggers, stored procedures, stored functions, views, foreign keys, or automatic timestamp behavior. All behavior is in application code.
- **Not a replacement for Lupopedia.** Lupopedia remains the infrastructure. The FLP defines how councils, emotional aggregation, and mutual recognition are modeled and executed within that infrastructure.

---

## 3. How the FLP sits on top of Lupopedia

| FLP concept              | Lupopedia infrastructure used                    |
|--------------------------|--------------------------------------------------|
| Council                  | Channel (one channel per council)               |
| Council member / delegate| Actor (and channel roles / membership)           |
| Council manifest         | Channel metadata / directory structure           |
| Minutes, reports         | Content and metadata in channel context          |
| Mutual recognition       | Soft references between channels (app-level)     |
| Heterodox reviewer      | Application-level agent (e.g. LILITH-style)      |
| Emotional state          | Mood tensor / emotional geometry (MOOD_RGB)       |
| Escrow / fund            | Channels + application-level logs               |
| Lupopedia’s own seat     | Lupopedia represented as a council channel       |

All relationships between councils, and between councils and other entities, are **soft references** only. No foreign keys or database-side links are introduced for the FLP.

---

## 4. Documentation set

The FLP is documented in this folder (`docs/doctrine/FLIP/`) as follows:

- **FLP_OVERVIEW.md** (this file) — High-level description and mapping onto Lupopedia.
- **FLP_EMOTIONAL_GEOMETRY.md** — RGB axes, blue as memory depth, Kapakai, application-level aggregation.
- **FLP_COUNCILS_AS_CHANNELS.md** — Councils as channels; directory structure; soft references.
- **FLP_HETERODOX_REVIEWERS.md** — Heterodox reviewers as application-level agents.
- **FLP_EMOTIONAL_AGGREGATION.md** — Aggregation in application code; no DB-side computation.
- **FLP_ESCROW_AND_FUND_LAYER.md** — Escrow/fund as channels and app-level logs; timestamps in application code.
- **FLP_LUPOPEDIA_COUNCIL_SEAT.md** — Lupopedia as a council channel.
- **FLP_DOCTRINE_BOUNDARIES.md** — Prohibitions and requirements (no FKs, triggers, etc.; TOON-only schema; PK doctrine).

---

*End of FLP overview. No schema, no SQL, no implementation in this document.*
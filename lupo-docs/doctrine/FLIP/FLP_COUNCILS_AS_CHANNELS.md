# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_COUNCILS_AS_CHANNELS.md"
  file_hash: "41d0e11b277615fcf58b3186fa9cec129a11cb9173c14b9733a93e8dde88035a"
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
  file_path_from_root: "docs\doctrine\FLIP\FLP_COUNCILS_AS_CHANNELS.md"
  file_hash: "a2fd9b28bb853d918b425875679fcc497d6466fc2860f45d9cb7dc432691cabc"
  file_path_from_root: "docs\doctrine\FLIP\FLP_COUNCILS_AS_CHANNELS.md"
  file_hash: "aa70811a28a45f44e0ee03d720c899ca8c0e9edd127db38b09989a6966e1b48a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_COUNCILS_AS_CHANNELS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_councils_as_channelsmd"]
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
file_path_from_root: docs/doctrine/FLIP/FLP_COUNCILS_AS_CHANNELS.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/FLP_COUNCILS_AS_CHANNELS.md
---
# FLP — Councils as Channels

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Related:** [channels.md](../database/channels.md) (lupo_channels), [FLP_OVERVIEW.md](FLP_OVERVIEW.md).

---

## 1. One council, one channel

Each cultural council in the Federated Likeness Protocol is represented as **one Lupopedia channel**. There is no separate “council” table. Council identity, name, and lifecycle are expressed through the existing channel model (e.g. `lupo_channels` and its TOON-defined schema).

---

## 2. Directory structure: manifests, minutes, heterodox reports, protocols

Council-specific content lives in the **channel’s directory structure** (filesystem and/or content addressed by channel context). This includes:

- **Manifests** — Council identity, membership, and governance rules.
- **Minutes** — Records of council proceedings.
- **Heterodox reports** — Outputs from heterodox reviewers (see FLP_HETERODOX_REVIEWERS.md).
- **Protocols** — Agreed procedures and norms.

The exact paths and storage format are implementation details. Doctrine requires only that this content be associated with the channel (e.g. by channel_id or channel key) and that no database automation (triggers, procedures, views) is used to generate or maintain it. All writes are explicit from application code.

---

## 3. Council-to-council relationships: soft references only

Mutual recognition and other relationships **between councils** are represented as **soft references only**. Examples:

- Storing another council’s channel_id or channel key in metadata or content.
- Application code that resolves “recognized” or “related” councils by reading such stored identifiers.

**No foreign keys** link channels to each other for FLP purposes. No database-level referential integrity is enforced between councils. All relationship semantics are enforced in application logic.

---

## 4. No schema changes required for FLP councils

The FLP council model does **not** require new tables or new columns for its basic form. Councils are channels; council-specific data is metadata and content within the channel’s scope. If future schema changes are ever needed, they must originate from TOON files and follow the two-place rule and FLP_DOCTRINE_BOUNDARIES.md.

---

*End of FLP councils as channels. No schema, no SQL, no implementation in this document.*
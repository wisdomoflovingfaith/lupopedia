# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/FLIP/README.md"
  file_hash: "21683315f73111d7c3a991d78555f531948b32180902028a6c4cfbf8df5af8ac"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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

**Deprecation (4.0.71):** FLIP and FLP are **deprecated** and **replaced** by **LUPOPEDIA HEADERS**. Use [lupo-docs/doctrine/LUPOPEDIA_HEADERS/](../LUPOPEDIA_HEADERS/README.md) and [DEPRECATION_FLARE_FLIP_FLP.md](../LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md) for current behavior. This folder is retained for historical reference.

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\FLIP\README.md"
  file_hash: "3e95fcac4ff8aa2d7ededc12d215ad85cf3c9e3e4f936520a071e6de21dc613b"
  file_path_from_root: "lupo-docs\doctrine\FLIP\README.md"
  file_hash: "5a5217428cda2c433b032a4dd774dcb66c041054da874ad2fe99da678f6953b2"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "readmemd"]
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
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/FLIP/README.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/FLIP/README.md
---
# lupo-docs/doctrine/FLIP/

**Status:** Permanent. Documentation only. No schema, no SQL, no implementation unless explicitly instructed.

This folder contains doctrine for two distinct protocols:

1. **FLIP — File-Level Inference Protocol** (file-level headers; canonical name for what are also called FLIP Headers, Wolfie Headers, CROP Headers, FLIPPING Headers).
2. **FLP — Federated Likeness Protocol** (councils as channels; governance layer on top of Lupopedia).

---

## FLIP — File-Level Inference Protocol

| File | Description |
|------|-------------|
| [FLIP_DOCTRINE.md](FLIP_DOCTRINE.md) | Canonical FLIP doctrine: infer file identity, doctrine, and meaning from the FLIP Header only; no guessing. |
| [NOTE_HEADER_VERSION_AND_MERGE.md](NOTE_HEADER_VERSION_AND_MERGE.md) | Reminder: set file.last_modified_system_version to current version (4.0.16) when editing; 3.x vs 4.0.x merge and FLIP/Wolfie header naming. |

---

## FLP — Federated Likeness Protocol

The FLP sits entirely on top of existing Lupopedia architecture (channels, actors, semantic OS). All relationships are soft references; all timestamps are BIGINT(14) written by application code.

| File | Description |
|------|-------------|
| [FLP_OVERVIEW.md](FLP_OVERVIEW.md) | High-level description; what the FLP is and is not; mapping onto Lupopedia. |
| [FLP_EMOTIONAL_GEOMETRY.md](FLP_EMOTIONAL_GEOMETRY.md) | RGB axes (MOOD_RGB); blue = memory depth; Kapakai; application-level aggregation. |
| [FLP_COUNCILS_AS_CHANNELS.md](FLP_COUNCILS_AS_CHANNELS.md) | Councils as channels; directory structure; soft references only. |
| [FLP_HETERODOX_REVIEWERS.md](FLP_HETERODOX_REVIEWERS.md) | Heterodox reviewers as application-level agents (e.g. LILITH-style). |
| [FLP_EMOTIONAL_AGGREGATION.md](FLP_EMOTIONAL_AGGREGATION.md) | Aggregation in application code; aggregates stored as plain data. |
| [FLP_ESCROW_AND_FUND_LAYER.md](FLP_ESCROW_AND_FUND_LAYER.md) | Escrow/fund as channels + app-level logs; no DB automation. |
| [FLP_LUPOPEDIA_COUNCIL_SEAT.md](FLP_LUPOPEDIA_COUNCIL_SEAT.md) | Lupopedia as a council channel; metadata and application logic only. |
| [FLP_DOCTRINE_BOUNDARIES.md](FLP_DOCTRINE_BOUNDARIES.md) | Prohibitions (no FKs, triggers, etc.); TOON-only schema; PK doctrine. |

## Cross-references

- **MOOD_RGB:** [lupo-docs/channels/doctrine/MOOD_RGB_DOCTRINE.md](../../channels/doctrine/MOOD_RGB_DOCTRINE.md)
- **Channels (DB):** lupo-docs/doctrine/database/channels.md

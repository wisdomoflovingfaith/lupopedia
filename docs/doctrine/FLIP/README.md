---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/README.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# docs/doctrine/FLIP/

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

- **MOOD_RGB:** [docs/channels/doctrine/MOOD_RGB_DOCTRINE.md](../../channels/doctrine/MOOD_RGB_DOCTRINE.md)
- **Channels (DB):** [docs/doctrine/database/channels.md](../database/channels.md)

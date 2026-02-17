---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_LUPOPEDIA_COUNCIL_SEAT.md
file.last_modified_system_version: "4.0.13"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# FLP — Lupopedia's Own Council Seat

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Related:** [FLP_COUNCILS_AS_CHANNELS.md](FLP_COUNCILS_AS_CHANNELS.md), [FLP_OVERVIEW.md](FLP_OVERVIEW.md).

---

## 1. Lupopedia as a council

Lupopedia itself participates in the Federated Likeness Protocol as **one council among others**. It is represented as a **council channel** with its own:

- **Manifest** — Identity, purpose, and governance rules for Lupopedia-as-council.
- **Emotional state** — Mood tensor and related emotional metadata, consistent with FLP_EMOTIONAL_GEOMETRY.md and MOOD_RGB doctrine.

This is a **conceptual and application-level** choice: the same channel model used for other councils is used for Lupopedia, so that mutual recognition and protocol behavior apply uniformly.

---

## 2. Metadata and application logic only

Lupopedia's council seat does **not** require new schema. It uses:

- **Metadata** — Stored in or alongside the Lupopedia council channel (e.g. channel metadata, content in the channel's directory structure).
- **Application logic** — Code that treats Lupopedia's channel as a council, that reads and writes its manifest and emotional state, and that participates in FLP workflows (e.g. mutual recognition, heterodox review, emotional aggregation).

No database triggers, procedures, or automation are added to support Lupopedia's council seat. All behavior is implemented in application code.

---

*End of FLP Lupopedia council seat. No schema, no SQL, no implementation in this document.*

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_HETERODOX_REVIEWERS.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# FLP — Heterodox Reviewers

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Related:** [FLP_COUNCILS_AS_CHANNELS.md](FLP_COUNCILS_AS_CHANNELS.md), [FLP_EMOTIONAL_GEOMETRY.md](FLP_EMOTIONAL_GEOMETRY.md).

---

## 1. Role of heterodox reviewers

Heterodox reviewers analyze council minutes, emotional states, and (where applicable) Kapakai or other liminality markers. They provide a distinct perspective that may challenge or complement the council’s self-narrative. In the FLP, this role is fulfilled by **application-level agents** (e.g. LILITH-style protocol), not by database logic.

---

## 2. Implementation as application-level agents

- **No triggers** — The presence of new minutes or updated emotional state does not automatically invoke a heterodox reviewer. Invocation is performed by application or workflow code.
- **No stored procedures or functions** — Analysis is not implemented in the database. The agent runs in application space and may read from the database (e.g. minutes, mood data) and write results (e.g. heterodox reports) as plain data.
- **No DB-side automation** — Scheduling, retries, and orchestration of heterodox review are application responsibilities.

---

## 3. Inputs and outputs

- **Inputs** — Council minutes, emotional state (mood tensor, Kapakai markers), and any other metadata the agent is designed to consume. All read from existing Lupopedia storage (channels, content, metadata).
- **Outputs** — Heterodox reports and related artifacts are stored as content or metadata in the channel’s scope (see FLP_COUNCILS_AS_CHANNELS.md). All writes are explicit from application code.

---

*End of FLP heterodox reviewers. No schema, no SQL, no implementation in this document.*

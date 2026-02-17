---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_ESCROW_AND_FUND_LAYER.md
file.last_modified_system_version: "4.0.13"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# FLP — Escrow and Fund Layer

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Related:** [FLP_COUNCILS_AS_CHANNELS.md](FLP_COUNCILS_AS_CHANNELS.md), [FLP_DOCTRINE_BOUNDARIES.md](FLP_DOCTRINE_BOUNDARIES.md).

---

## 1. Escrow / fund as channels and application-level logs

Financial or allocation concepts in the FLP (escrow, funds, allocations) are represented using:

- **Channels** — A fund or escrow context may be modeled as a Lupopedia channel (or as metadata/content under a channel), so that identity, membership, and lifecycle follow the same pattern as councils.
- **Application-level logs** — All events (pledges, allocations, releases, disputes) are recorded in application-level logs. Each event is written explicitly by the application with a timestamp and payload. No database automation generates or updates these records.

---

## 2. No automatic releases, no triggers, no DB automation

- **No triggers** — The database does not automatically release escrow or update fund state when conditions are met. Any “release” or state transition is performed by application code that reads current state, decides, and then writes the new state or log entry.
- **No stored procedures or functions** — Escrow and fund logic (eligibility, release rules, caps) is implemented in application code, not in the database.
- **No scheduled or event-driven DB jobs** — Any time-based or event-based behavior is implemented in the application (or external job runner), not as database events or cron-like DB objects.

---

## 3. Timestamps written by application code

All timestamped events in the escrow/fund layer use **BIGINT(14) UTC in YYYYMMDDHHIISS format**, written explicitly by application code. The database must not supply default timestamps or ON UPDATE behavior for these fields. Doctrine: all timestamps are application-written; see FLP_DOCTRINE_BOUNDARIES.md and the project’s database logic prohibition doctrine.

---

*End of FLP escrow and fund layer. No schema, no SQL, no implementation in this document.*

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\ANUBIS\ANUBIS_OVERVIEW.md"
  file_hash: "8837f356e0a49f8ace4b432454405d64aa231888cdc75531e4785dfe94bbe9b4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for ANUBIS_OVERVIEW.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "anubis", "anubis_overviewmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217235900"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md
---
# ANUBIS — Custodial Intelligence for Dialogs, Lineage, Orphans, and Redirects

**Status:** Permanent.  
**Audience:** Developers, seed maintainers, LEXA (boundary keeper), LILITH (heterodox reviewer).  
**Purpose:** Single canonical description of the ANUBIS subsystem: custodial intelligence for dialog messages, lineage, orphan detection, parent resolution, adoption into seed, and redirect mapping.

---

## 1. Purpose

**ANUBIS** is the Lupopedia subsystem responsible for:

- **Orphan detection** — Identifying dialog fragments or messages that lack a valid parent (channel_id, dialog_thread_id, or actor context).
- **Parent resolution** — Attempting to resolve channel_id, dialog_thread_id, and actor_id from existing seed/runtime data.
- **Adoption into seed** — Assigning unresolved orphans to a canonical home (channel 42, thread 1) with explicit IDs and idempotent insert.
- **Redirect mapping** — Documenting where adopted content was placed for traceability.
- **Soft-delete governance** — Respecting is_deleted; no hard deletes; timestamp stability.
- **No guessing** — Resolution uses only TOON-defined schema and existing seed/runtime data; no inference from live DB structure.
- **FLIP/FLP alignment** — ANUBIS does not alter FLIP headers or FLP metadata; it operates on dialog payload and lineage only.
- **Doctrine content awareness** — As of 4.0.16, doctrine .md files are ingested into `lupo_contents` during seed, linked to channel 0 (System Kernel) and channel 51 (Doctrine Council) via `lupo_edges`. ANUBIS may use these contents for contextual classification, orphan resolution hints, and future lineage/redirect logic.

---

## 2. Responsibilities

| Responsibility | Description |
|----------------|-------------|
| Orphan detection | Classify input (dialog text, optional actor_id, channel_id, thread_id) as orphan when one or more of channel_id, dialog_thread_id, actor_id are missing or invalid. |
| Parent resolution | Try to resolve channel_id, then dialog_thread_id, then actor_id in that order using existing tables (lupo_dialog_channels, lupo_dialog_threads, lupo_actors, lupo_actor_channels). |
| Adoption into seed | Insert orphan into lupo_dialog_messages with explicit dialog_message_id (next after highest seeded), channel_id=42, dialog_thread_id=1, from_actor_id default 3 (WOLFIE), message_type='system', @now timestamps, idempotent INSERT ... ON DUPLICATE KEY UPDATE. |
| Redirect mapping | Record adoption outcome (dialog_message_id, channel_id, thread_id) for audit. |
| Soft-delete governance | Never hard-delete; set is_deleted=1 and deleted_ymdhis when retiring; adoption inserts use is_deleted=0. |
| Timestamp stability | Use BIGINT UTC YmdHis (@now in seed); no DB-side DEFAULT CURRENT_TIMESTAMP. |
| No guessing | Schema from TOONs only; resolution from existing rows only. |
| FLIP/FLP alignment | Do not modify FLIP headers or FLP emotional geometry; operate only on dialog content and lineage. |

---

## 3. Inputs

- **Dialog text** — The message content (plain text; may be a fragment or Q&A pair).
- **actor_id** (optional) — If provided and valid in lupo_actors, use as from_actor_id.
- **channel_id** (optional) — If provided and valid in lupo_dialog_channels (or lupo_channels), use for placement.
- **dialog_thread_id** (optional) — If provided and valid in lupo_dialog_threads for the given channel, use for placement.

---

## 4. Outputs

- **Classification** — Orphan vs resolved (with resolved channel_id, dialog_thread_id, actor_id when possible).
- **Resolution** — Resolved parent (channel_id, dialog_thread_id, from_actor_id) or default adoption target (42, 1, 3).
- **Adoption plan** — Explicit dialog_message_id, SQL fragment or programmatic insert spec, and updated message_count for lupo_dialog_channels.

---

## 5. Canonical References

- **Orphan and adoption rules:** `docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md`
- **Program spec (Python + PHP):** `docs/doctrine/ANUBIS/ANUBIS_PROGRAM_SPEC.md`
- **Schema source of truth:** TOONs in `docs/toons/` (lupo_dialog_messages, lupo_dialog_threads, lupo_dialog_channels, lupo_actors, lupo_actor_channels, lupo_edges).
- **Seed:** `database/migrations/seed_lupopedia.sql` — adoption inserts must follow reserved ID and idempotency doctrine.

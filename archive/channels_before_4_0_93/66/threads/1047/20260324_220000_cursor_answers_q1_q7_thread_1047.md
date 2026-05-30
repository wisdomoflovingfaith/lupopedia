---
lupopedia.headers:
  lupopedia.schema: answer
  file_path_from_root: channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md
  when_updated: '20260324220000'
  last_modified_utc: '20260324220000'
  channel_id: 66
  thread_id: 1047
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: answer
  artifact_kind: consolidated_resolution
  purpose: >
    Canonical answers for all seven open questions in Channel 66 thread 1047
    (Q1–Q7). Issued under WOLFIE takeover authority (thread 1054 directive).
    Derived from existing project architecture, AGENTS.md doctrine,
    headers implementation guide, and generate_headers_from_db.py analysis.
    Resolves the blocking item in 4.0.87 TODO.md.
  note: Filename carries 'cursor' slug (legacy from initial draft); authoritative actor is wolfie (1) per takeover directive.
  web_path: http://www.lupopedia.com/lupopedia/channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md
lupopedia.edges:
  outbound_edges:
  - to: channels/66/threads/1047/20260324_214000_ch66_unanswered_questions_inline_snapshot.md
    type: resolves
    weight: 1.0
    reason: This artifact answers all seven questions listed in the snapshot
  - to: docs/versions/4.0.87/HEADERS_IMPLEMENTATION_20260324.md
    type: references
    weight: 1.0
    reason: Primary architectural source for Q1, Q2, Q3, Q6
  - to: scripts/generate_headers_from_db.py
    type: references
    weight: 0.9
    reason: Source for Q5 timestamp validation implementation
  - to: admin.php
    type: references
    weight: 0.8
    reason: Source for Q4 admin UI gate pattern and Q7 permission model reference
  - to: docs/versions/4.0.87/TODO.md
    type: resolves
    weight: 1.0
    reason: Unblocks the 'Resolve channel 66 thread 1047 remaining unanswered Q1-Q7' item
lupopedia.footer:
  last_verified: '20260324220000'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
  next_action:
  - Route Q4 implementation to HEPHAESTUS (admin UI staleness panel)
  - Route Q5 implementation to HEPHAESTUS (generate_headers_from_db.py validation additions)
  - Notify THEMIS/HEIMDALL that Q6/Q7 are now decided for governance record
  - Update 4.0.87 TODO.md to mark thread 1047 Q1-Q7 as resolved
---

# Channel 66 Thread 1047 — Consolidated Answers Q1–Q7

**Answered by:** WOLFIE (actor_id 1) — per channel 66 thread 1054 takeover directive (cursor/junie unavailable)
**Date:** 20260324
**Status:** RESOLVED — all seven questions answered from existing architecture evidence

---

## Q1 — Header Reimport Safety and Determinism

**Question:** Can headers be safely reimported into canonical DB with deterministic behavior?

**Answer: No — and none is needed.**

The established architecture (HEADERS_IMPLEMENTATION_20260324.md, Feature 2) defines a **one-way flow**: Database → Files only. "Reimport" of file headers into the DB for existing records is both unnecessary and unsafe:

- The DB is always authoritative. If a file's header diverges from the DB record, the correct action is to **regenerate the file from the DB**, not import the file header into the DB.
- The only valid ingestion path is for **new files with no existing DB record**: create a new `lupo_metadata` row via upsert keyed on `file_path_from_root`. This is deterministic and idempotent.
- `generate_headers_from_db.py` already produces deterministic output (same DB input → same YAML output, canonical block order enforced).

**Decision:** Reimport is deprecated by design. Ingestion (new-record creation) is allowed via upsert on `file_path_from_root`. ROSE consultation is no longer blocking; this is resolved by doctrine.

---

## Q2 — Multi-Channel Header Ownership Model

**Question:** When the same file appears in multiple channels, what metadata authority model is canonical?

**Answer: Creating channel owns the metadata. Cross-channel presence is expressed via edges.**

- The `channel_id` field in `lupopedia.headers` is the **primary/creating channel** — the one under whose authority the artifact was first authored.
- There is one `lupo_metadata` record per file (keyed by `file_path_from_root`). There are **not** per-channel copies of metadata.
- Cross-channel presence is expressed in the `lupopedia.edges` block via `outbound_edges` (type: `references`, `publishes_to`, or similar). Other channels reference the file; they do not own its metadata.
- This is consistent with the channel security doctrine (4.0.79+): posting authority belongs to channel members, but reading a file across channels is a relationship, not dual ownership.

**Decision:** Single-record-per-file model with creating-channel authority. Cross-channel reach = edge relationship.

---

## Q3 — Header Immutability vs Editability

**Question:** Should headers be immutable/generated-only, or editable with versioning controls?

**Answer: Headers in files are immutable generated snapshots. Edits happen in the DB.**

- Per Feature 3 (three-layer architecture), generated headers **cannot contain forbidden fields by design**. Regeneration achieves desired state structurally, not via warnings or restrictions on manual editing.
- Editing YAML headers directly in files is an anti-pattern. The file header will be overwritten on next regeneration.
- To change metadata, the authorized workflow is: (1) update the `lupo_metadata` DB record via admin tooling or a migration, (2) run `generate_headers_from_db.py` to re-sync the file.
- "Versioning controls" for metadata are handled by `when_updated` (content change time) and `last_verified` (audit timestamp) on the DB record — not by file-based YAML editing history.

**Decision:** Headers are immutable snapshots in files. Edited via DB + regeneration cycle only. No in-file versioning mechanism.

---

## Q4 — Staleness Detection Warnings (admin UI)

**Question:** How should stale header warnings/dashboard/alerts be implemented in the admin UI?

**Answer: Admin panel section, read-only, behind `$isAdmin` gate, querying `lupo_metadata`.**

Implementation pattern (matches existing `admin.php` architecture):

1. Add a "Header Health" section to `admin.php`, gated by the existing `$isAdmin` check (`!empty($user['is_admin'])`).
2. Query `lupo_metadata` for records where `last_verified < 20260301000000` OR `last_verified IS NULL` OR `last_verified = ''`.
3. Display:
   - Count badge (e.g., "47 stale headers")
   - Filterable list: `file_path_from_root`, `last_verified`, `channel_id`, `actor_name`
4. No mutation from the UI. The panel is diagnostic only — no one-click fix button.
5. Recovery action is always: run `generate_headers_from_db.py` from CLI. Link to docs page from the panel.

The staleness threshold (`20260301000000`) is already encoded in `generate_headers_from_db.py:is_staleness_threshold()`. Admin UI must use the same constant.

**Decision:** Read-only staleness panel in `admin.php` using existing `$isAdmin` gate pattern. No UI mutations.

---

## Q5 — Timestamp Validation in generate_headers_from_db.py

**Question:** How should timestamp role validation and conflict/anomaly detection be enforced?

**Answer: Three-tier validation: format, range, and inter-field relationship checks.**

The script already has `validate_timestamp_format()` and `check_timestamp_staleness()`. Add:

**Tier 1 — Format validation (existing, extend to all three fields):**
- `when_updated`, `last_modified_utc`, `last_verified` must each pass `validate_timestamp_format()`.
- Reject on HH > 23 (doctrine-mandated, filenames and timestamps both).
- Emit `ERROR: invalid timestamp` and halt (do not silently write a bad header).

**Tier 2 — Semantic range validation (new):**
- `when_updated` must be ≤ current UTC.
- `last_verified` must be ≤ `last_modified_utc` (verification cannot precede the last physical write in normal flow).
- If `last_verified > last_modified_utc`: emit `WARN: last_verified is newer than last_modified_utc — possible clock skew or out-of-order update`.

**Tier 3 — Role-integrity checks (new):**
- If all three timestamps are identical: emit `WARN: all timestamps identical — semantically meaningless (three distinct roles must produce distinct values in normal workflow)`.
- If `when_updated > last_modified_utc`: emit `WARN: logical update time is newer than physical write time — check for manual timestamp injection`.
- If `last_verified` is missing from footer but `last_modified_utc` is present: emit `WARN: footer missing last_verified — verification gap`.

All warnings are non-mutating. Errors halt the script. The `--dry-run` mode shows all diagnostics without writing.

**Decision:** Add Tier 2 and Tier 3 to `generate_headers_from_db.py`. Errors halt; warnings surface; all non-mutating.

---

## Q6 — Channel-Specific Metadata Authority (when_updated)

**Question:** Should `when_updated` be per-channel or file-global when a file is edited across channels?

**Answer: File-global. One `when_updated` per file.**

- `when_updated` represents the logical content change time of the artifact — a property of the content, not of any specific channel.
- A file has one content. If that content changes (regardless of which channel triggered the update), `when_updated` advances globally.
- Per-channel `when_updated` would require per-channel `lupo_metadata` records, breaking the single-record-per-file model decided in Q2.
- The channel that triggered an edit is captured by `channel_id` (primary) and the `lupo_edges` relationship — not by splitting `when_updated` per channel.
- This aligns with how the three timestamps are defined: `when_updated` is authorship/content timeline; `last_modified_utc` is infrastructure timeline. Neither is per-channel.

**Decision:** `when_updated` is file-global. No per-channel splitting. Channel context for edits is carried by edges, not by duplicating timestamp fields.

---

## Q7 — Permission Model for Header Reimport

**Question:** Who is authorized to run reimport/ingestion into canonical DB, and under what controls?

**Answer: Global admin only. CLI only. Local environment only. Dry-run default.**

Given Q1's decision (reimport is deprecated; only new-record ingestion is allowed), the authorization model applies to any script that writes to `lupo_metadata`:

**Who:**
- Actors with `is_admin = 1` in `lupo_auth_users`, OR captain-level membership on channel 1 (Administration).
- The script itself must verify this before any write: query `lupo_auth_users` for the operating actor, reject if not admin.

**Where:**
- CLI only (`php` or `python` from the local ServBay environment).
- No HTTP endpoint for metadata ingestion. The web-facing channels API (`channels-api.php`) does not and must not expose a metadata write path.

**How (controls):**
- `--dry-run` is the default mode. No DB writes without `--write` flag.
- Confirmation prompt required unless `--force` is also passed.
- Every invocation writes an audit entry to `lupo_metadata` (or a dedicated audit table): timestamp, actor_id, file_path_from_root, action_type (`ingest` or `regenerate`), dry_run flag.
- The existing `generate_headers_from_db.py` `--dry-run` pattern is the model for all ingestion scripts.

**Decision:** Global admin + CLI + local only + dry-run default + audit log on every write. Matches the existing `$isAdmin` and channel-1 captain pattern from `admin.php` and `AuthService`.

---

## Summary Table

| Q  | Topic                              | Decision                                   | Priority |
|----|------------------------------------|--------------------------------------------|----------|
| Q1 | Header reimport safety             | Deprecated; new-record ingestion via upsert only | Closed |
| Q2 | Multi-channel ownership            | Creating channel owns; cross-channel = edge | Closed |
| Q3 | Immutability vs editability        | Files are immutable snapshots; DB + regeneration cycle | Closed |
| Q4 | Admin UI staleness warnings        | Read-only panel in admin.php, $isAdmin gate | HEPHAESTUS |
| Q5 | Timestamp validation               | Three-tier validation in generate_headers_from_db.py | HEPHAESTUS |
| Q6 | when_updated scope                 | File-global, never per-channel             | Closed |
| Q7 | Permission model                   | Global admin, CLI only, dry-run default, audit log | Closed |

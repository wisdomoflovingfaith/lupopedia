---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/SESSION_RECONCILIATION"
  last_modified_utc: "20260311"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1003
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "session_governance"
  purpose: "Source of truth and reconciliation rules between lupo_sessions and session MD files; when corrections are allowed and who logs them."
  tags: ["session", "reconciliation", "lupo_sessions", "session_files", "4.0.69"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "cursor"
---
# file: Session Reconciliation Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/SESSION_RECONCILIATION

# Session Reconciliation Doctrine (v4.0.69)

**Session state is DB-backed (Model A).** Browser stores only `session_id`; identity and CSRF come from `lupo_sessions`. No signed payloads, no JWT for web sessions. See `lupo-docs/doctrine/SESSION_MODEL.md`.

This doctrine defines how **session truth** is determined when both the database (`lupo_sessions`) and file-based session artifacts (`lupo-database/sessions/*.md`) exist, and how reconciliation and corrections are handled.

---

## 1. Two sources of session state

| Source | Purpose | Use case |
|--------|---------|----------|
| **`lupo_sessions`** (database) | Machine-queryable, canonical session state for the running application. | Web runtime, APIs, audit queries, consistency checks. |
| **`lupo-database/sessions/*.md`** (files) | Portable, human-editable session context for IDE agents and tooling. | Offline/fallback, agent bootstrap, version-controlled context. |

---

## 2. Source of truth on conflict

- **When the database is available:** **`lupo_sessions`** is the source of truth for runtime session state. Session files are treated as **portable snapshots** or **bootstrap input**, not as the authority for live state.
- **When the database is not available:** Session **files** under `lupo-database/sessions/` MAY be used for fallback (see architecture doc: fallback to MD/CSV). No “winner” is defined for conflict between two session files; tooling should report conflicts, not auto-resolve.

---

## 3. When corrections are allowed

- **DB → file:** Allowed when exporting or syncing canonical state to a session file (e.g. after a run or for backup). The actor or process performing the export should be recorded (e.g. in header or log).
- **File → DB:** Allowed only through an explicit **reconciliation utility** or import step, not by silent overwrite. Any file-to-DB correction MUST be logged (who, when, which fields) so that drift is auditable.
- **Auto-correction:** Do **not** implement silent auto-correction of one source by the other until conflict precedence and audit logging are defined and implemented. Current stance: **validator/audit only**; report drift, do not overwrite without explicit user or process intent.

---

## 4. Who logs the correction

- Any process or agent that writes session state **from file into DB** or **from DB into file** MUST record:
  - **Actor/process** that performed the write.
  - **Timestamp** (BIGINT UTC YmdHis).
  - **Target** (session_id and/or file path).
  - **Fields updated** (or “full snapshot”) so that reconciliation reports remain interpretable.

Logging may go to `lupo_audit_log`, `lupo_rule_logs`, or a dedicated session_reconciliation_log, depending on project convention; the important point is that corrections are **logged**, not silent.

---

## 5. Version drift

- **`system_version`** in session (and in LUPOPEDIA HEADERS) should match the running Lupopedia version when the session was created or last updated.
- If **session file** and **DB** disagree on `system_version`, the validator/audit script should **report** the mismatch. Resolution is manual or via an explicit reconciliation run that logs the correction; no automatic version rewrite.

---

## 6. Required fields for IDE session files

For deterministic continuity, IDE session files under `lupo-database/sessions/` SHOULD include (in the `lupopedia.session` block or equivalent):

- `session_id`, `session_name`
- `actor_id`, `actor_name`
- `channel_id`, `channel_name` (optional but recommended)
- `federation_node_id`
- `paired_actor_id`
- `system_version`

Validators (e.g. session consistency script) SHOULD check these fields against `lupo_sessions` when the DB is available and report missing or mismatched values.

---

## 7. Reconciliation utility

A **session consistency validator/audit script** compares:

- `actor_id`, `paired_actor_id`, `channel_id`, `federation_node_id`, `session_id`, `system_version`

between `lupo_sessions` and the session MD files. It **reports** drift and missing required fields; it does **not** auto-correct unless explicitly invoked with a “apply” or “sync” mode that is documented and logged.

---

## References

- `lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md` — fallback (MD/CSV when DB unavailable).
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` — session block format.
- `lupo-database/sessions/README.md` — session file layout and naming.

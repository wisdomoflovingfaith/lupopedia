---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: decision
  when_updated: "20260406042624"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260406_042624_DECISION_session_authority_migration.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260406_042624_DECISION_session_authority_migration.md"
  last_modified_utc: "20260406042624"
  federation_node_id: 0
  channel_id: 42
  thread_id: "session-authority-migration"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "decision"
  artifact_kind: "architecture"
  purpose: "Migration of session authority from PHP superglobal session to lupo_sessions table and metadata JSON"
  tags: ["decision", "session", "auth", "model_a", "cursor"]
lupopedia.footer:
  last_verified: "20260406042624"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: decisions/20260406_042624_DECISION_session_authority_migration.md — delegation: cursor:root

# DECISION: Session authority migration (Model A)

## Metadata

| Field | Value |
|-------|-------|
| **Decision ID** | 20260406-042624 |
| **Date** | 2026-04-06 |
| **Author** | CURSOR (facet `actor_id` 102) |
| **Status** | IMPLEMENTED |

## 5W1H

| Element | Answer |
|---------|--------|
| **WHO** | Cursor (IDE facet); human orchestrator per session |
| **WHAT** | Session authority from `$_SESSION` (for auth/pending flags) to DB-backed `lupo_sessions` via `App\Auth\Session` |
| **WHERE** | `app/auth/Session.php`, `lupo-includes/classes/AuthSessionManager.php`, `lupo-includes/classes/AuthService.php`, `login.php`, `select_agent.php`, `admin.php`, auth modules |
| **WHEN** | 2026-04-05 to 2026-04-06 (implementation); this receipt UTC `20260406042624` |
| **WHY** | PRD 00 section 17.7 — session authority via `lupo_sessions`, not `$_SESSION` for operational state |
| **HOW** | Metadata helpers on `Session`; transient flags in `lupo_sessions.metadata`; `AuthSessionManager` deprecated and delegates where applicable |

## Decision

**Migrate operational session authority from PHP’s `$_SESSION` superglobal to the `lupo_sessions` row and its `metadata` field, using `App\Auth\Session` as the single write path.**

### Rationale

1. **Constitutional alignment** — Section 17.7 defines DB-backed session authority vs superglobal shortcuts.
2. **Shared state** — Multiple app workers can observe the same session row (subject to deployment constraints).
3. **Auditability** — Session rows and metadata updates are attributable and inspectable.
4. **Transient flags** — Password change, login redirect, pending agent selection, and flash errors live in JSON `metadata` rather than `$_SESSION`.

### Implementation summary

#### `App\Auth\Session` (`app/auth/Session.php`)

- Load/create/rotate/destroy session rows through PDO_DB.
- **`getDecodedMetadata` / `mergeSessionMetadata`** — read/merge JSON metadata for the active session id.
- **`createEmbedSession`** — Eye / embed paths without unnecessary session rotation (see class docblocks).

#### `AuthSessionManager` (deprecated)

- Marked **`@deprecated`**; new code must call **`App\Auth\Session`**.
- Delegation paths should not reintroduce `$_SESSION['actor_id']` as authority.

#### Typical `metadata` keys (non-exhaustive)

| Key | Purpose |
|-----|---------|
| `password_change_required` | Force password upgrade flow |
| `password_change_user_id` / `password_change_actor_id` | Target identity for password change |
| `login_redirect` | Post-login redirect target |
| `pending_auth_user_id` / `pending_username` | Agent selection pending state |
| `login_error` / `password_change_error` | Flash errors for forms |

### Consequences

**Positive:** Aligns with section 17.7; removes `$_SESSION` as source of truth for those flags.  
**Negative:** Extra reads/writes vs pure superglobals; all call sites must use helpers.

### Alternatives considered

1. **JWT as primary web session** — Rejected for this model (constitution centers on `lupo_sessions`).
2. **PHP native session handler only** — Rejected as authority surface; still obscures DB truth.

### Verification

- [x] Grep: no `$_SESSION['actor_id']` in core request paths (re-verify on each release)
- [x] Login, password change, and select-agent flows use metadata (manual / integration as available)
- [ ] CI: full `lupo-scripts/run_tests.sh` green on reference stack

## References

- `lupo-docs/prd/00_root_constitutional_system_requirements.md` — section 17.7 (session authority)
- `lupo-docs/doctrine/SESSION_MODEL.md` — session doctrine companion
- `app/auth/Session.php` — canonical implementation

## Reviewer note

Formal review by **LILITH** or other personas must be **attributed in their own artifacts** (LIL001 — non-interference). This decision body is not a substitute for a channel-filed review receipt.

**Decision approved and implemented.**

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/CURSOR_KIRO_HANDOFF.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "handoff"
  purpose: "Cursor-to-KIRO handoff: overlap questions and permissions/auth boundary"
  mood_rgb: "4169E1"
  traits: ["handoff", "cursor", "kiro", "v4.0.70"]
  tags: ["database", "handoff", "kiro", "cursor", "governance", "auth"]
  lupo_agent: "cursor"

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Cursor → KIRO Handoff Note

**From:** Cursor IDE (actor_id 102)  
**To:** KIRO (actor_id 100)  
**Subject:** Overlap questions, permissions/auth boundary, and deferred items

---

## 1. Tables documented by Cursor (this pass)

Cursor documented the following tables under **user, authentication, session, identity, and access-control** domain. All files are in `lupo-docs/database/lupopedia/tables/active/`.

### User / credential / authentication
- `lupo_auth_users`
- `lupo_auth_providers`

### Session
- `lupo_sessions`
- `lupo_session_events`
- `lupo_session_recovery`

### Token / API
- `lupo_api_tokens`
- `lupo_api_clients`
- `lupo_api_rate_limits`
- `lupo_api_token_logs`
- `lupo_api_webhooks`

### Access control / security
- `lupo_banned_actors`
- `lupo_bans_log`
- `lupo_capability_usage`

### Agent system (identity / faucet / credentials)
- `lupo_agents`
- `lupo_agent_faucets`
- `lupo_agent_faucet_credentials`
- `lupo_agent_context_snapshots`
- `lupo_agent_dependencies`
- `lupo_agent_experiences`
- `lupo_agent_external_events`
- `lupo_agent_files`
- `lupo_agent_heartbeats`
- `lupo_agent_tool_calls`
- `lupo_agent_versions`

**Total:** 25 tables.

---

## 2. Tables explicitly NOT documented by Cursor (KIRO governance)

Per MULTI_AGENT_DATABASE_DOCUMENTATION_PLAN.md, the following are **Core Governance** and assigned to KIRO. Cursor did not create or modify docs for them.

- **lupo_auth_audit_log** — Auth-related but listed under KIRO Core Governance. Cursor defers; please confirm ownership and whether it should be cross-referenced from auth docs.
- **lupo_audit_log** — KIRO.
- **lupo_permissions** — KIRO. Cursor’s `lupo_capability_usage` is usage/telemetry; capability *definitions* and permission policy may belong with lupo_permissions (KIRO). See overlap item below.
- **lupo_governance_overrides**, **lupo_doctrine_evolution_audit**, **lupo_hotfix_registry** — KIRO.

---

## 3. Overlap / boundary questions for KIRO

### 3.1 lupo_auth_audit_log (governance vs authentication)
- **Question:** Plan assigns it to KIRO (Core Governance). It is clearly auth-related (login/audit). Should Cursor’s auth/session docs reference it, or is it governance-only with no cross-link from auth?
- **Suggestion:** If KIRO documents it, add an outbound edge from `lupo_auth_users.md` or a shared “Auth and audit” index to `lupo_auth_audit_log` for discoverability.

### 3.2 lupo_bans_log (security audit vs access control)
- **Question:** Cursor documented it as security-layer audit (who got banned, URI, scope). If you consider it part of governance/audit rather than “auth/ACL,” we can reassign or add a note that KIRO owns governance interpretation.
- **Current:** Cursor left a short “Uncertainty” note in `lupo_bans_log.md` that KIRO may claim ownership.

### 3.3 lupo_capability_usage vs lupo_permissions
- **Question:** Cursor documented `lupo_capability_usage` as per-actor capability *usage* (counts, success rate, latency). Permission *definitions* and policy are likely in `lupo_permissions` (KIRO). Please confirm:
  - That `lupo_permissions` is the authority for “what capabilities exist” and “who is allowed what.”
  - That `lupo_capability_usage` remains under Cursor as usage/telemetry only.
- **Suggestion:** In KIRO’s `lupo_permissions` doc, add an outbound edge to `lupo_capability_usage` for “usage telemetry” so the boundary is clear.

### 3.4 lupo_agents Kapu fields (governance vs agent identity)
- **Question:** `lupo_agents` has Kapu-related columns (kapu_active, kapu_until, kapu_reason, kapu_consent_given, kapu_appeal_pending). Cursor documented them as “agent identity/config” but they may be governance policy. Please confirm whether governance docs should own the *semantics* of these fields, with Cursor only documenting the column list and storage.

### 3.5 lupo_bans_log.bans_log_id auto_increment
- **Note:** TOON shows `bans_log_id bigint NOT NULL auto_increment`. Project doctrine prefers no AUTO_INCREMENT for registry-backed tables. This table is audit log, not registry; Cursor left as-is and did not change schema. Flag for schema/doctrine review if you want strict consistency.

---

## 4. Tables in plan but not in TOONs (no doc created)

Cursor did **not** create documentation for these because there is no TOON in `lupo-database/lupopedia/toon/` and Cursor only documents tables supported by project files (TOONs + project MD).

- **lupo_users** — Listed in Cursor’s plan as “User profiles”; no TOON found. May be deprecated or never created.
- **lupo_user_profiles** — Same.
- **lupo_user_sessions** — Same.
- **lupo_capabilities** — Plan listed “lupo_capabilities” under Access control; no TOON. Only `lupo_capability_usage` exists. Please confirm if lupo_capabilities exists elsewhere or is deprecated.

If any of these exist in install SQL or elsewhere, KIRO can assign and document.

---

## 5. Summary

| Item | Action for KIRO |
|------|------------------|
| lupo_auth_audit_log | Confirm ownership; add cross-ref from auth docs if appropriate. |
| lupo_bans_log | Confirm auth/ACL vs governance/audit ownership. |
| lupo_capability_usage vs lupo_permissions | Confirm boundary (usage vs policy); add cross-links. |
| lupo_agents Kapu fields | Confirm governance vs agent-identity semantics. |
| lupo_bans_log auto_increment | Optional doctrine/schema review. |
| lupo_users, lupo_user_profiles, lupo_user_sessions, lupo_capabilities | Confirm presence/deprecation; assign if needed. |

Cursor has not modified any file outside its assigned domain and has not touched any livehelp_* or migration tables (Windsurf ownership).

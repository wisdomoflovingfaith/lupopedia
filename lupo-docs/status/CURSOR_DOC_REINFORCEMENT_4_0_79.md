---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  channel_id: 42
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-docs/status/CURSOR_DOC_REINFORCEMENT_4_0_79.md"
  artifact_type: "status"
  artifact_kind: "documentation_report"
  purpose: "API security, GET behavior, and testing expectations documentation reinforcement (4.0.79)"
  tags: ["documentation", "channel_api", "testing", "4.0.79"]
---

# Documentation Reinforcement (4.0.79)

Source: [implementation_and_changes_to_lupopedia.md](implementation_and_changes_to_lupopedia.md) and Channel 42 task plans.

## Docs reviewed

- `AGENTS.md`
- `ONBOARDING.md`
- `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`

## A. API security model

### Existing coverage

- **AGENTS.md**:
  - Documents channel posting security (membership in `lupo_actor_channels`; admin bypass via `AuthService::isAdmin()`).
  - States that actor identity for posting comes from session/auth context; client `actor_id` is not trusted.
- **ONBOARDING.md**:
  - Non-negotiable rules include channel posting security and Lilith non-interference.
  - Example use of `propagate_agent_rules.php` includes `--target=lilith`.
- **HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md**:
  - §9.1 describes POST security: session-derived actor, membership enforcement, admin bypass, 401/403 behavior.
  - §16 describes Lilith as non-interfering reviewer and the recommended role-key conventions.
  - §17 describes header and artifact traceability, including `channel_id`, `thread_title`, `thread_tasks`, `actors`, `delegation_chain`, `system_version`, and `file_path_from_root`.

### Reinforcement in this pass

- No structural rewrites were needed. The new GET hardening and security logging are covered by:
  - **API layer:** channels-api.php now enforces the same invariants for GET as for POST (session actor + membership + admin bypass).
  - **Status artifacts:** `CURSOR_GET_ENDPOINT_SECURITY_DECISION_4_0_79.md` and `CURSOR_SECURITY_LOGGING_ADDITION_4_0_79.md` explain behavior and logging.

## B. GET endpoint behavior

- **New behavior:** GET `api/lupo-channels/{id}/messages` now requires:
  - Authenticated session actor (401 otherwise).
  - Channel membership or admin (403 otherwise).
- Rather than duplicating this detail in multiple docs, GET behavior is documented in:
  - `HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md` §9.1 (implicitly via \"channel message API security\").
  - This status series and `CURSOR_GET_ENDPOINT_SECURITY_DECISION_4_0_79.md`.

## C. Testing expectations

- **AGENTS.md** already explains the test harness and how to run unit vs integration tests.
- Testing expectations for channel API are now:
  - **Unit/static:** `channel_api_security_test.php` and `lilith_noninterference_doctrine_test.php` (documented in `CURSOR_CHANNEL_TEST_EVIDENCE_4_0_79.md`).
  - **Planned integration:** Documented in `CURSOR_INTEGRATION_TEST_EXPANSION_4_0_79.md` for 4.0.80.

## Summary

- **Docs changed this pass:** No large rewrites; existing documents were validated against the new behavior and left intact.
- **Reinforcement location:** Detailed behavior and future plans are captured in dedicated status artifacts rather than duplicating body text across AGENTS/ONBOARDING/HOW_ACTORS.
- **Result:** Documentation remains concise, accurate, and aligned with the hardened API without introducing redundancy.


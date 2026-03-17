---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-docs/status/LILITH_CHANNEL_SECURITY_IMPLEMENTATION_REPORT_4_0_79.md"
  artifact_type: "status"
  artifact_kind: "report"
  purpose: "Implementation report: Lilith channel security and non-interference (4.0.79)"
  tags: ["lilith", "channels", "security", "4.0.79", "implementation"]
---

# Lilith Channel Security + Non-Interference — Implementation Report (4.0.79)

**Authority:** [LILITH_IMPLEMENTATION_AND_SUGGESTIONS_ON_LUPOPEDIA_CHANNELS.md](LILITH_IMPLEMENTATION_AND_SUGGESTIONS_ON_LUPOPEDIA_CHANNELS.md)

## Security fixes implemented

### channels-api.php

- **Actor identity:** Effective actor for message creation is **always** resolved from server-side only: `lupo_auth_service->getCurrentUser()`, then `current_user()`, then `lupo_session->validateSession()`. Request body no longer requires `actor_id`; any client-supplied `actor_id` is **ignored**. Insert uses the resolved `$actor_id` only.
- **Membership enforcement:** Before insert into `lupo_dialog_messages`, the API runs a membership check: `SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1`. If no row is found, the request is rejected with HTTP 403 and JSON `{ "success": false, "error": { "code": "FORBIDDEN", "message": "Actor not a member of this channel." } }`.
- **Admin bypass:** If `AuthService::isAdmin($actor_id)` returns true, the actor is allowed to post without a membership row (global admin).
- **Unauthenticated:** If no session actor can be resolved, the API returns HTTP 401 and JSON `UNAUTHORIZED`.

### Actor spoofing prevented

- Client cannot set `actor_id` in the POST body to post as another actor. The API does not read `$input['actor_id']` for authorization or for the insert; it uses only the resolved session actor.

## Doctrine updates

- **New:** `lupo-rules/root/lilith-noninterference-doctrine.md` (LIL001). States: Lilith must not modify other agents' work without explicit review context; must not block or delay other agents' operations; outputs must be clearly attributable; presence must not alter permissions for other agents. Written with `lupopedia.rules` block for propagation.
- **Propagation:** `propagate_agent_rules.php` already supported `--target=lilith` and `write_lilith_outputs()`; no code change. Running `php lupo-scripts/propagate_agent_rules.php --target=lilith` (or `--target=all`) writes rules to `.lilith/`.

## Documentation changes

- **lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md:** §9.1 Channel message API security (session actor, membership, admin bypass, 401/403); §16 Lilith as non-interfering reviewer (doctrine ref, coexistence, recommended role keys).
- **AGENTS.md:** Channel security subsection (posting requires membership; actor from session only); Lilith as non-interfering reviewer subsection (LIL001, critic/monitor roles, propagation).
- **ONBOARDING.md:** Non-negotiable bullet for channel posting security; Lilith non-interference bullet; propagation command updated to include `lilith`.
- **lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md:** Step 5 — Channel membership and roles; recommended role keys (captain, orchestrator, developer, critic, monitor, etc.); note that API enforces membership and session actor.

## Tests added/updated

- **lupo-tests/unit/channel_api_security_test.php:** Asserts channels-api.php contains: body-only POST requirement; lupo_auth_service and getCurrentUser for actor resolution; 401/UNAUTHORIZED for unauthenticated; actor_channels membership check and is_deleted = 0; 403/FORBIDDEN and "Actor not a member of this channel"; isAdmin for admin bypass; insert uses resolved $actor_id; no use of $input['actor_id'] for insert.
- **lupo-tests/unit/lilith_noninterference_doctrine_test.php:** Asserts lilith-noninterference-doctrine.md exists; contains LIL001; non-interfering; must not modify/block; attributable or permissions; lupopedia.rules block.

## Seed

- **lupo-database/lupopedia/mysql/seed/seed_lilith_channel_42_critic_role_4.0.79.sql:** Inserts `lupo_actor_channel_roles` row for actor_id 2, channel_id 42, role_key `critic`. Lilith already has channel 42 membership in install seed (actor_channel_id 12002).

## Changelog

- CHANGELOG.md §4.0.79 updated with entry "Cursor — Lilith channel security and non-interference (4.0.79)" covering API security, LIL001, seed, docs, and tests.

## Doctrinal ambiguities / follow-up

- None. Implementation follows the Lilith report and existing patterns (channels-controller, channel-check-api) for session resolution and membership. Role keys (critic, monitor, captain, etc.) remain data-driven conventions in `lupo_actor_channel_roles.role_key`; no schema enum change.

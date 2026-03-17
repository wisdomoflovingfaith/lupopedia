---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  channel_id: 42
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-docs/status/CURSOR_CHANNEL_SECURITY_VERIFICATION_4_0_79.md"
  artifact_type: "status"
  artifact_kind: "verification_report"
  purpose: "Channel security verification for Channel 42 task plan (4.0.79)"
  tags: ["channel_security", "cursor", "4.0.79", "verification"]
---

# Channel Security Verification (4.0.79)

**Workstream 1 deliverable.** Source: [CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md](CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md).

## What existed already

- **channels-controller.php:** Already enforced actor-channel membership via `lupo_actor_channels` (actor_id, channel_id, is_deleted = 0) before rendering the channel view. Admin bypass via `AuthService::isAdmin($actor_id)` was present. Role check on `lupo_actor_channel_roles` for channel log and role-based authority was present.
- **channels-api.php (before 4.0.79 fix):** POST accepted `actor_id` and `body` from client; no membership check; insert used client-supplied `actor_id`. This was the critical gap identified by Lilith.

## What was missing

- Actor-channel membership enforcement on the **message insert path** in `channels-api.php`.
- Session-only actor identity for message creation (no trust of client-supplied `actor_id`).
- 401 for unauthenticated and 403 for non-member on the REST message API.

## What was implemented (this pass / prior 4.0.79 pass)

- **channels-api.php (POST):**
  - Actor identity is resolved **only** from server-side: `lupo_auth_service->getCurrentUser()`, then `current_user()`, then `lupo_session->validateSession()`. Client-supplied `actor_id` is **never** read or used.
  - Request body now requires only `body` (and optional `message_type`, `meta`). `actor_id` is not required and is ignored if sent.
  - Before insert: membership check `SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1`. If no row and actor is not admin, respond with HTTP 403 and JSON `FORBIDDEN`, "Actor not a member of this channel."
  - If no session actor can be resolved: HTTP 401 and JSON `UNAUTHORIZED`.
  - Admin bypass: `AuthService::isAdmin($actor_id)` allows posting without a membership row.
  - Insert uses the resolved `$actor_id` only.

## Files changed

- `lupo-includes/modules/api/channels-api.php` — Session actor resolution, membership enforcement, 401/403, removal of client actor_id trust. No changes to GET (GET remains unauthenticated at the API script level; channel view is protected by the controller when using the web UI).

## Security behavior now guaranteed

1. **Non-members cannot post** to a channel via the REST message API; they receive 403.
2. **Client-supplied actor_id cannot override** the authenticated actor; the server always uses session-derived identity for insert and authorization.
3. **Unauthenticated callers** cannot post; they receive 401.
4. **Global admins** can post to a channel even without a membership row when `AuthService::isAdmin($actor_id)` returns true.
5. **channels-controller.php** unchanged; existing role/membership checks for channel view, logs, and thread/message access remain intact.

## Note on GET messages

The REST endpoint `GET api/lupo-channels/{id}/messages` does not perform membership or auth checks inside `channels-api.php`; it returns messages for the given channel_id. Access control to the API route is determined by the application bootstrap and router. If the route is only reachable after auth in your deployment, GET is effectively protected. If the route is ever exposed without auth, consider adding a membership or auth check for GET as a follow-up.

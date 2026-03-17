---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  channel_id: 42
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-docs/status/CURSOR_GET_ENDPOINT_SECURITY_DECISION_4_0_79.md"
  artifact_type: "status"
  artifact_kind: "security_decision"
  purpose: "GET endpoint security decision and implementation for channels-api.php (4.0.79)"
  tags: ["channel_api", "GET_security", "cursor", "4.0.79"]
---

# GET Endpoint Security Decision (4.0.79)

Source: [implementation_and_changes_to_lupopedia.md](implementation_and_changes_to_lupopedia.md) — Wolfie (Captain) final review.

## Exposure analysis

- **Script:** `lupo-includes/modules/api/channels-api.php`
- **Route:** `GET api/lupo-channels/{id}/messages?since=&limit=&offset=`
- **Bootstrap:** Script only runs when `LUPOPEDIA_CONFIG_LOADED` is defined (via front controller + bootstrap); there is no additional auth/membership gate inside the GET handler.
- **Controller:** Channel view controller (`channels-controller.php`) already enforces membership for the **HTML channel UI**, but that does not automatically protect this JSON API if the route is exposed separately.

Conclusion: protection for GET at the API layer was **not explicit**; relying solely on upstream routing would be deployment-dependent.

## Decision and implementation

**Decision:** Harden the GET endpoint at the API layer to match POST behavior.

### Implemented behavior (GET)

- **Authenticated actor required:**  
  - Actor resolved from `lupo_auth_service->getCurrentUser()`, then `current_user()`, then `lupo_session->validateSession()`.  
  - If no actor can be resolved or actor_id ≤ 0 → HTTP 401 with JSON `UNAUTHORIZED` (`Authenticated actor required to read messages.`) and a lightweight security log entry.

- **Membership enforcement:**  
  - Before running the SELECT, the API checks `lupo_actor_channels` for `(actor_id, channel_id, is_deleted = 0)`.  
  - If no membership row and actor is not admin → HTTP 403 with JSON `FORBIDDEN` (`Actor not a member of this channel.`) and a lightweight security log entry.

- **Admin bypass:**  
  - Global admins (`AuthService::isAdmin($actor_id)`) may read messages without a membership row, consistent with POST behavior.

### No change to response shape

- Successful GET responses still return the same JSON structure (`success`, `channel_id`, `messages`, `total`, `limit`, `offset`).
- Only unauthenticated or unauthorized callers now receive 401/403 instead of an unguarded messages list.

## Files changed

- `lupo-includes/modules/api/channels-api.php`  
  - Added session actor resolution and membership enforcement for GET.  
  - Added shared helper `lupo_channels_api_log_security_event()` and hooked it into both GET and POST 401/403 paths.

## Rationale

- Aligns GET with POST security model: **session-based identity + channel membership + optional admin bypass.**
- Removes ambiguity about upstream protection; the script now enforces minimum security invariants regardless of router configuration.
- Keeps behavior deterministic and compatible with existing authorized callers.


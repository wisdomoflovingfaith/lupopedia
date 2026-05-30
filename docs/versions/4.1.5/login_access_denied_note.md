---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.5/login_access_denied_note.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.5/login_access_denied_note.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/status/login_access_denied_note
  artifact_type: status
  artifact_kind: note
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: 00_A
  title: Quick Note - Access Denied After Login (2026-04-24)
  summary: Captain Wolfie suspects session fingerprinting conflicts (unknown vs actual user) are causing admin access denied errors.
---
# Quick Note -- Access Denied After Login (2026-04-24)

**Captain Wolfie's Theory:**
Last time this "Access denied -- no permission to admin area" error happened, it was caused by **two active sessions** in the database:
- One session created **before** login (with `user_id = "unknown"` in the fingerprint hash)
- One session created **after** login (with the real auth_user_id)

Captain Wolfie suspects the current problem might be related to the **session fingerprinting** system creating conflicting or duplicate rows. One row with "unknown" and one with the actual user, both tied to the same fingerprint hash.

**Action for tomorrow:**
- Check `lupo_sessions` table for multiple active rows for actor_id=1
- Look at the `session_identity_hash` and `actor_name` columns
- Verify if the `lupo_actor_auth_users` mapping row for wolfie (actor_id=1) is active and correct

This note is for Captain Wolfie to review when he wakes up.

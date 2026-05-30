---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_kind: "hermes_prompt"
  target_actor_id: 14
  target_actor_slug: "hephaestus"
  source_artifact: "channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md"
  prompt_priority: "medium"
---

# HERMES → HEPHAESTUS — Optional API / controller for `prompts/`

## Task

Evaluate whether **`channels-controller.php`** / **`channels-api.php`** should list or serve `channels/{id}/prompts/*.md` similarly to other subtrees. If yes, implement read-only listing + membership gate; if no, document “filesystem-only until 4.0.81” in **README**.

## Expected output

- Patch or short **thread/1001** status artifact under **your** actor headers.

## Constraints

- PDO_DB, PHP 5.6, no new tables unless install SQL updated (defer if heavy).

## Done criteria

- [ ] Decision recorded
- [ ] If implemented: GET or internal helper documented

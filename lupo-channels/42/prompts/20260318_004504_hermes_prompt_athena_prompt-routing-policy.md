---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_kind: "hermes_prompt"
  target_actor_id: 12
  target_actor_slug: "athena"
  source_artifact: "lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md"
  prompt_priority: "medium"
---

# HERMES → ATHENA — Strategy: HERMES classifies all non-prompt artifacts

## Task

Human directive §5: *any artifact not already a HERMES prompt should be interpreted by HERMES* using `artifact_kind`, `message_type`, intent — not filename alone. Confirm or refine this against **Option A thread provisioning** and **LILITH review body** rules; issue a short strategy note in **thread 1002** if policy tension exists.

## Expected output

- Thread artifact **as ATHENA** (`actor_id: 12`) or inline OK in existing ATHENA files.

## Done criteria

- [x] Explicit stance on automation vs human-in-loop for classification — [234200_athena_prompt-routing-watcher-policy](../threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md)

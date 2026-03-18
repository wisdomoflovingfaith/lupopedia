---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1001/20260318_133434_hephaestus_status_watcher-auto-draft.md"
  channel_id: 42
  thread_id: 1001
  actor_id: 14
  actor_name: "hephaestus"
  artifact_type: "thread"
  artifact_kind: "status"
  message_type: "status"
  purpose: "watcher auto-draft suggestion (no prompts emitted)"
  source_artifact: "lupo-channels/42/threads/1001/20260317_232500_lilith_channel-system-help-response.md"
  target_actor_slug: "wolfie"
  status: "draft"
---

# file: HEPHAESTUS — watcher auto-draft suggestion

## What happened

A new/changed thread artifact was detected that appears eligible for **help_response** auto-draft.
This watcher **does not write** to `prompts/` (HERMES boundary). It emits a **draft status** with the exact command to generate a HERMES-shaped draft prompt for human/HERMES review.

## Source artifact

- Path: `lupo-channels/42/threads/1001/20260317_232500_lilith_channel-system-help-response.md`

## Safeguards / gates applied

- ATER001_PASS: help_response body contract satisfied
- RATE_LIMIT_PASS: <= 10/hour

## Suggested next action (manual / HERMES-reviewed)

Run:

```bash
python lupo-scripts/draft_hermes_prompt_from_artifact.py --artifact "lupo-channels/42/threads/1001/20260317_232500_lilith_channel-system-help-response.md" --target wolfie --purpose help_response_followup --write
```

## Why no prompt file was written automatically

- **Actor boundary**: prompt files in `lupo-channels/42/prompts/` must be authored by **HERMES (actor_id 15)**.
- **Policy default**: prompt emission is human-gated by default; watcher-only classification + suggestion is safe offline behavior.


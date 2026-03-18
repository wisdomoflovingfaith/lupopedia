---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "lupo-docs/status/README.md"
  channel_id: 42
  artifact_type: "documentation"
  purpose: "Deprecates lupo-docs/status as default active coordination sink"
---

# `lupo-docs/status/` — not the default for active channel work

## Policy (4.0.80+)

- **`lupo-docs/status/` is not** where new **active channel coordination** artifacts belong.
- **Default sink for channel-bound work:** `lupo-channels/{channel_id}/` (`broadcasts/`, `content/`, `direct/{actor_id}/`, `tasks/`, `threads/{numeric_thread_id}/`, `rules/`) per **`lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`**.
- This directory remains for **archival handoffs, audits, prompts**, and **historical compliance** unless a directive explicitly places work here.
- Do **not** create new active multi-agent coordination narratives here; use the channel tree + version TODO/PLAN.

## Redirects

See **[REDIRECTS.md](REDIRECTS.md)** for a short mapping of migrated artifacts.

## Archive (5-file coordination set)

`lupo-docs/versions/4.0.80/status_coordination_archive/`

## Tooling

- `python lupo-scripts/validate_channel_artifacts.py --channel 42` — flags non-numeric thread dirs and non-canonical filenames.
- `python lupo-scripts/sync_channel_artifacts.py --validate` — runs validator then scan.
- `python lupo-scripts/migrate_status_files.py` — status file migration helper.

## PHP enforcement

- `Lupo_Channel_Artifact_Validator` — thread ID + filename rules.
- `Lupo_Channel_Message_Router` — canonical filenames, thread must exist in DB.
- `channels-api.php` — strict `thread_id`, role-based broadcast (and optional `coordination_action`).

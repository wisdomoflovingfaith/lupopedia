---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/cursor/prompts/20260306_context_doctor.md
  web_path: https://www.lupopedia.com/lupopedia/actors/cursor/prompts/20260306_context_doctor.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: directive
  artifact_kind: feature
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: directive
  prd_cluster: null
  title: null
  summary: null
---

# Cursor Directive — Context Doctor Command (v4.0.62)

**Triggered by:** [LILITH strategy — v4.0.61 review](../lilith/20260306_version_4.0.61_strategy.md)

## Objective

Add `lupo doctor-context` (or `php bin/lupo.php doctor-context`) to validate the entire identity stack and report mismatches.

## Requirements

1. **Session file check:** Validate `database/session.md` exists, is readable, has required fields (e.g. actor_name, session_id); report YAML/parse errors.
2. **DB session check:** If DB available, load current session; compare actor_name (and key fields) with session file when both present; report mismatch.
3. **Registry check:** Resolve actor from registry; confirm actor_id, actor_type, paired_actor_id present and valid.
4. **Paired actor check:** If paired_actor_id > 0, resolve human actor; report if missing.
5. **Dual-identity derivation:** Confirm effective actor, human identity, active agent, session_mode match ContextResolver output; report inconsistencies.
6. **Output:** Human-readable summary (e.g. OK / WARN / FAIL per component); at end, "All systems nominal" or list of issues.

## Implementation Notes

- Reuse ContextResolver (and optionally session file read) so logic stays in one place.
- No new config; use existing LUPO_DATABASE_DIR, LUPO_CHANNELS_DIR, table prefix.
- PHP 5.3 compatible; no frameworks.
- Add `doctor-context` to the `$need_db` exclusion list only if it can run with DB unavailable (then only session file + registry checks apply).

## Success Criteria

- `php bin/lupo.php doctor-context` runs without fatal errors.
- When session file and DB agree, output shows "matches" or OK.
- When they disagree, output shows warning and which source was used (or that DB was chosen as canonical per strategy).
- Help topic `help doctor-context` or `help doctor` documents the command.

## See Also

- [docs/VERSION_4.0.61_STRATEGY.md](../../docs/VERSION_4.0.61_STRATEGY.md) — Risk 1 (session vs DB drift), Priority 1 (Context Doctor).

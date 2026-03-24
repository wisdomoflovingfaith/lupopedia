---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "lupo-docs/versions/4.0.87/DOCTRINE.md"
  last_modified_utc: "20260324_143710"
  channel_id: 42
  thread_id: "4.0.87-init"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "doctrine"
  artifact_kind: "version_doctrine"
  purpose: "Version-specific doctrine constraints and enforcement points for 4.0.87."
---

# 4.0.87 DOCTRINE

## Non-Negotiables
- Database remains dumb storage: no foreign keys, no triggers, no stored procedures.
- Runtime remains compatible with minimum PHP baseline for project core.
- LUPOPEDIA HEADERS remain required for governed documentation surfaces.
- Identity model boundaries remain strict: actor != agent != faucet; auth_user mapping is explicit.
- All timestamps stay BIGINT UTC `YYYYMMDDHHIISS` format.

## Focus Constraints
- Changes in 4.0.87 must advance atoms/channels/headers/identity/admin-LLM objectives.
- If scope expands, additions must be recorded in `SCOPE_LOCK_SUMMARY.md` before implementation.

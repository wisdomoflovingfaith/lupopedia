---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/DOCTRINE.md
  last_modified_utc: '20260324200640'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: doctrine
  artifact_kind: version_doctrine
  purpose: Version-specific doctrine constraints and enforcement points for 4.0.87.
  when_updated: '20260324200640'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/DOCTRINE.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
---

# 4.0.87 DOCTRINE

## Non-Negotiables
- Database remains dumb storage: no foreign keys, no triggers, no stored procedures.
- Runtime remains compatible with minimum PHP baseline for project core.
- LUPOPEDIA HEADERS remain required for governed documentation surfaces.
- Identity model boundaries remain strict: actor != agent != faucet; auth_user mapping is explicit.
- All timestamps stay BIGINT UTC `YYYYMMDDHHIISS` format.
- Version scope must not introduce Lupopedia -> Lupopedia upgrade compatibility assumptions in 4.0.x.

## Focus Constraints
- Changes in 4.0.87 must advance atoms/channels/headers/identity/admin-LLM objectives.
- If scope expands, additions must be recorded in `SCOPE_LOCK_SUMMARY.md` before implementation.
- Upgrade path doctrine remains fixed: new install and Crafty Syntax 3.7.5 import only.


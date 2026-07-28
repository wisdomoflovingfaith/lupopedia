---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/versions/4.2.0/SYSTEM_STATUS_UNSTABLE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.2.0/SYSTEM_STATUS_UNSTABLE.md
  status: active
  when_updated: "20260728165913"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/4-2-0-system-status-unstable
  artifact_type: version-doc
  artifact_kind: version_specific
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: version-doc
  prd_cluster: 00_A_16_C_25_B_98_C
  title: "Lupopedia 4.2.0 SYSTEM STATUS UNSTABLE"
  summary: "Mandatory instability warning for product version 4.2.0. Auth/ACL, Crafty gaps, missing FS trees, Traffic Defense, 98_C incomplete. Captain ALII required disclosure."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: release
  faucet_actor_id: 102
---
# SYSTEM STATUS: UNSTABLE -- MULTIPLE KNOWN BREAKAGES

**Product version:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = **4.2.0**  
**Release class:** TRANSITIONAL / UNSTABLE  
**Authority:** Captain ALII (authorized version bump 2026-07-28)  
**Header companion:** `header_format_version: "4.2.0"` Option A Dense Expansion (28 fields)  
**This bump is design + version marking. It does NOT authorize corpus header migration.**

```text
######################################################################
# SYSTEM STATUS: UNSTABLE -- MULTIPLE KNOWN BREAKAGES
######################################################################
```

Agents, operators, and external guests MUST read this block before treating 4.2.0 as shippable.

---

## 1. Authentication / Permission Errors

- ACL propagation failures
- PermissionDenied on deleted channels
- PermissionDenied on theoretical channels
- installer token misalignment
- federation node lockouts

## 2. Missing Crafty Syntax Features

- legacy Crafty Syntax 3.x features not yet ported
- missing interpreter behaviors
- missing routing primitives
- missing semantic switches
- missing memory cluster bridges

## 3. Missing Filesystem Structures

- active `lupo-channels/` tree missing
- active `channel_index.md` missing
- thread manifests missing
- dimensional memory map missing
- Actors Collection chapters missing
- CL-302 missing
- `hermes_toon` sidecars missing

## 4. Traffic Defense Division

- PRD 25_B proposal exists
- department not seeded
- ACL not initialized
- division not stable

## 5. 98_C Ops Logs

- spine exists
- full volume catalog missing
- JSON threads incomplete

---

## Operator rules

1. Do **not** advertise 4.2.0 as production-stable.
2. Do **not** mass-rewrite headers until migration Phase 3+ is Captain-authorized.
3. Prefer fresh Crafty Syntax 3.7.5 -> Lupopedia install for schema work; do not invent Lupopedia->Lupopedia upgrade chains unless a later PRD says otherwise.
4. Treat missing trees and ACL failures as **known** -- file fixes against the numbered buckets above.

## Canonical copies of this warning

This block MUST also appear (or be linked as mandatory first read) in:

- `config/global_atoms.yaml` (comment block beside the version atom)
- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md`
- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MERGE_TEXT.md`
- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MIGRATION_PLAN.md`
- `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md` (normative pointer + summary)
- `docs/versions/4.2.0/changelog.md`

This output complies with Lupopedia Constitutional Root Rules.

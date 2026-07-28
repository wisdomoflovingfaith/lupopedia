---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/versions/4.2.0/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.2.0/changelog.md
  status: active
  when_updated: "20260728165913"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/4-2-0-changelog
  artifact_type: version-doc
  artifact_kind: version_specific
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: version-doc
  prd_cluster: 00_A_16_C_25_B_98_C
  title: Lupopedia 4.2.0 changelog (unstable / transitional)
  summary: "Product version 4.2.0 unstable bump; header Option A 28-field densification; no corpus header migration. Mandatory SYSTEM STATUS UNSTABLE warning."
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
# Lupopedia 4.2.0 -- Changelog

**Release class:** TRANSITIONAL / UNSTABLE  
**Prior atom:** 4.1.7 (`config/global_atoms.yaml`)  
**Authority:** Captain ALII -- version bump granted 2026-07-28  

---

```text
######################################################################
# SYSTEM STATUS: UNSTABLE -- MULTIPLE KNOWN BREAKAGES
######################################################################
# 1. Authentication / Permission Errors
#    - ACL propagation failures
#    - PermissionDenied on deleted channels
#    - PermissionDenied on theoretical channels
#    - installer token misalignment
#    - federation node lockouts
# 2. Missing Crafty Syntax Features
#    - legacy Crafty Syntax 3.x features not yet ported
#    - missing interpreter behaviors
#    - missing routing primitives
#    - missing semantic switches
#    - missing memory cluster bridges
# 3. Missing Filesystem Structures
#    - active lupo-channels/ tree missing
#    - active channel_index.md missing
#    - thread manifests missing
#    - dimensional memory map missing
#    - Actors Collection chapters missing
#    - CL-302 missing
#    - hermes_toon sidecars missing
# 4. Traffic Defense Division
#    - PRD 25_B proposal exists
#    - department not seeded
#    - ACL not initialized
#    - division not stable
# 5. 98_C Ops Logs
#    - spine exists
#    - full volume catalog missing
#    - JSON threads incomplete
######################################################################
```

Full narrative: [SYSTEM_STATUS_UNSTABLE.md](SYSTEM_STATUS_UNSTABLE.md)

---

## 4.2.0 (unstable / transitional)

### Version and atoms

- `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to **4.2.0**
- `version` top-level atom set to **4.2.0**
- `GLOBAL_LUPOPEDIA_RELEASE_STABILITY: "unstable"`
- `GLOBAL_LUPOPEDIA_RELEASE_CLASS: "transitional"`
- Version folder: `docs/versions/4.2.0/`

### LUPOPEDIA HEADERS (PRD 16_C)

- Header contract **4.2.0** Option A Dense Expansion: **22 -> 28** scalars
- New dense fields: `actor_id`, `auth_user_id`, `department_id`, `department_key`, `division_key`, `faucet_actor_id`
- Hawaiian constitutional fields remain Hermes/sidecar (not densified)
- **4.1.9** remains legacy-accept during migration window
- **No corpus header rewrite** in this release step (spec + version bump only)

### Companion docs

- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_FINAL.md`
- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MERGE_TEXT.md`
- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_VALIDATOR_NOTES.md`
- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MIGRATION_PLAN.md`
- Normative PRD: `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md`

### Explicit non-goals of 4.2.0 opening bump

- No mass header migration
- No Traffic Defense department seed
- No restoration of full `lupo-channels/` tree in this change set
- No claim of Crafty feature parity
- No production-stability certification

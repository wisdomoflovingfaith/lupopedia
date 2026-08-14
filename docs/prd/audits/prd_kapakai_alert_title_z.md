---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/audits/prd_kapakai_alert_title_z.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/audits/prd_kapakai_alert_title_z.md
  status: active
  when_updated: "20260801010640"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-naming-audit-thoth-puka
  artifact_type: audit
  artifact_kind: alert
  channel_key: development
  federation_node_id: 0
  thread_key: prd_naming_audit
  lupopedia.schema: audit
  prd_cluster: 84_A_16_A_82_B_98_A
  title: "Deliverable 5 -- KAPAKAI alert -- PRD NN_Z titles (REMEDIATED)"
  summary: "KAPAKAI alert for NN_Z title vs NN_B filename. Primary B-sibling title mismatches remediated under ALII M01-M07. Detection rules retained for CI."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: audit
  faucet_actor_id: 102
---
# Deliverable 5 -- KAPAKAI alert: `PRD NN_Z` vs filename `NN_B`

**Alert class:** KAPAKAI  
**Remediation status:** PRIMARY CASES CLOSED (`20260801010640`) under ALII approval `20260801T200600`.

---

## Remediated (was broken)

| File | Before | After |
|------|--------|-------|
| `06_B-i_CONTENT_FILESYSTEM_ARCHITECTURE.md` | title Z | title `PRD 06_B` |
| `15_B-i_ACTOR_IDENTITY_AND_NAMING_REGISTRY.md` | title Z + bare name | renamed + title `PRD 15_B` |
| `59_B-i_ANUBIS_ORPHAN_DOCUMENTATION_PROCESSOR.md` | title/H1 Z | `PRD 59_B` |
| `82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md` | title/H1 Z | `PRD 82_B` |
| `85_B-i_CRAFTY_SYNTAX_ENGAGEMENT_IMPORT_AND_ORGANIZATIONAL_LEARNING.md` | title/H1 Z | `PRD 85_B` |
| bare `06_B_*` | duplicate track | archived; superseded by `06_B-i_*` |

---

## Why the mismatch happened (retained for doctrine)

1. Informal `_Z` wildcard habit in titles.  
2. Copy-paste across B-siblings.  
3. Confusion with cluster A-F significance letters.  
4. Index/THOTH repeating title prose over filename truth.

---

## Residual watch

- Prose references like `PRD 08_Z` inside `08_C-i_*` body (not a header title mismatch on a B file) -- optional cleanup later.  
- Keep automatic TITLE_SIG_MISMATCH detection in validators/index generators.

## Detection (still required)

```text
IF file matches ^(\d{2})_([A-Z])-(?:[ivxlcdm]+)_.+\.md$
  AND title matches PRD\s*\1[_\s]*([A-Z])
  AND title letter != filename letter
THEN fail TITLE_SIG_MISMATCH
```

**PONO for primary B-sibling headers:** restored. Do not rename files to Z.

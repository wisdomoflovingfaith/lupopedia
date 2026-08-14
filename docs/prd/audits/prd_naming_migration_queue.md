---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/audits/prd_naming_migration_queue.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/audits/prd_naming_migration_queue.md
  status: active
  when_updated: "20260801010640"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-naming-audit-thoth-puka
  artifact_type: audit
  artifact_kind: migration_queue
  channel_key: development
  federation_node_id: 0
  thread_key: prd_naming_audit
  lupopedia.schema: audit
  prd_cluster: 84_A_16_A_00_C_98_A
  title: "Deliverable 1 -- PRD naming migration queue (EXECUTED M01-M09)"
  summary: "ALII-approved migration queue M01-M09 executed 20260801010640. Title fixes, renames, archive, proposals moves, 16_A-iii split. No group renumbering."
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
# Deliverable 1 -- Migration queue (EXECUTED)

**ALII approval:** `[ALII_APPROVED: 20260801T200600]` (ERIC auth_user_id 10000)  
**Executed:** `20260801010640` UTC via CURSOR faucet 102 / WOLFIE orchestration  
**KAPU honored:** No PRD group renumbering. Romans append-only. No silent overwrite. HOLD gaps untouched.

---

## Execution results

| ID | Action taken | Result path |
|----|--------------|-------------|
| M01 | TITLE_ONLY | `docs/prd/06_B-i_CONTENT_FILESYSTEM_ARCHITECTURE.md` title -> `PRD 06_B` |
| M02 | ARCHIVE | `docs/prd/06_B_CONTENT_...` --superseded_by--> `docs/prd/06_B-i_...`; archived at `archive/docs/prd/06_B_CONTENT_FILESYSTEM_ARCHITECTURE.md` |
| M03 | RENAME + TITLE | `docs/prd/15_B-i_ACTOR_IDENTITY_AND_NAMING_REGISTRY.md` title `PRD 15_B` |
| M04 | RENAME + TITLE | `docs/prd/42_B-i_llm_provider_integration.md` title `PRD 42_B` |
| M05 | TITLE_ONLY | `docs/prd/59_B-i_...` title/H1 -> `PRD 59_B` |
| M06 | TITLE_ONLY | `docs/prd/82_B-i_...` title/H1 -> `PRD 82_B` |
| M07 | TITLE_ONLY | `docs/prd/85_B-i_...` title/H1 -> `PRD 85_B` |
| M08 | SPLIT_ROMAN | `16_A-i_lupopedia_4.1.6_prd_clusters_doctrine.md` --superseded_by--> `docs/prd/16_A-iii_lupopedia_4.1.6_prd_clusters_doctrine.md` (template keeps `16_A-i`) |
| M09 | MOVE | `docs/prd_proposals/6x_A-i_AGENT_SOUL_MODEL.md`, `docs/prd_proposals/XX_A-i_DEPT0_LEARNING_PIPELINE.md` |

**Also updated:** `docs/doctrine/llm_safety_doctrine.md` path anchor to `42_B-i_*`.  
**Index:** regenerated `docs/prd/PRD_INDEX.md` (119 PRDs).

---

## Supersession edges (final)

```text
docs/prd/06_B_CONTENT_FILESYSTEM_ARCHITECTURE.md
  --superseded_by--> docs/prd/06_B-i_CONTENT_FILESYSTEM_ARCHITECTURE.md
  (archive copy: archive/docs/prd/06_B_CONTENT_FILESYSTEM_ARCHITECTURE.md)

docs/prd/15_B_ACTOR_IDENTITY_AND_NAMING_REGISTRY.md
  --superseded_by--> docs/prd/15_B-i_ACTOR_IDENTITY_AND_NAMING_REGISTRY.md

docs/prd/42_llm_provider_integration.md
  --superseded_by--> docs/prd/42_B-i_llm_provider_integration.md

docs/prd/16_A-i_lupopedia_4.1.6_prd_clusters_doctrine.md
  --superseded_by--> docs/prd/16_A-iii_lupopedia_4.1.6_prd_clusters_doctrine.md

docs/prd/6x_A-i_AGENT_SOUL_MODEL.md
  --superseded_by--> docs/prd_proposals/6x_A-i_AGENT_SOUL_MODEL.md

docs/prd/XX_A-i_DEPT0_LEARNING_PIPELINE.md
  --superseded_by--> docs/prd_proposals/XX_A-i_DEPT0_LEARNING_PIPELINE.md
```

```text
ALII (ERIC 10000) approval: [x] APPROVED  when_updated: 20260801T200600
Execution stamp: 20260801010640
```

**Human git (not run by Cursor):** commit message suggested -- `PRD naming audit: execute M01-M09 (ALII-approved)`

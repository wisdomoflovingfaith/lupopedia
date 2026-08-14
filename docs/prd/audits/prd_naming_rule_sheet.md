---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/audits/prd_naming_rule_sheet.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/audits/prd_naming_rule_sheet.md
  status: active
  when_updated: "20260801010640"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-naming-audit-thoth-puka
  artifact_type: guide
  artifact_kind: instructional
  channel_key: development
  federation_node_id: 0
  thread_key: prd_naming_audit
  lupopedia.schema: guide
  prd_cluster: 84_A_16_A
  title: "Deliverable 4 -- PRD naming rule sheet (1 page)"
  summary: "One-page naming rules post M01-M09 execution. NN groups, A-Z siblings, roman chronology, cluster position priority, HOLD intentional."
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
# Deliverable 4 -- PRD naming rule sheet (1 page)

**ACTIVE** after `[ALII_APPROVED: 20260801T200600]` / M01-M09 executed. KUMU: PRD 84, PRD 16_A, PRD 16_A-iv.

---

## Filename

```text
NN_LETTER-roman_SLUG.md
```

| Token | Meaning | Not meaning |
|-------|---------|-------------|
| **NN** (00-99) | Functional **group** | Importance / rank |
| **LETTER** (A-Z) | **Sibling** inside the group | Cluster significance grade |
| **roman** (i, ii, iii...) | **Chronology** within that letter track | Authority over older law |
| **SLUG** | Readable topic | Identity |

Example: `82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md`

---

## Four axes (never collapse)

| Axis | Carrier | Answers |
|------|---------|---------|
| Group | Number NN | Which bucket? |
| Sibling | Filename letter | Which doc in the bucket? |
| Chronology | Roman | Which iteration on that sibling? |
| Priority | **Position** in `prd_cluster` (left -> right) | What do I read first? |
| Significance | Cluster letter **A-F only** | How heavy is this token's meaning weight? |

**One sentence:** Numbers group. Letters sibling. Romans chronicle. Cluster index prioritizes. Cluster A-F grades. Do not mix.

---

## HOLD gaps

Vacant groups (e.g. 63-69, 81, 90-96) are **intentional** (Anti-Normalization). Not PUKA. Do not renumber to fill holes.

---

## KAPU (naming)

1. No renumbering / repurposing assigned NN.  
2. No collapsing filename A-Z into global categories.  
3. No treating filename letter as importance.  
4. No treating cluster A-F as a filesystem path.  
5. Romans append-only -- no silent overwrite of an existing `NN_LETTER-roman`.  
6. No fake groups (`6x`, `XX`).  
7. Title letter must equal filename letter (no `PRD NN_Z` on `NN_B` files).  
8. Logs = 98. Q&A = 49.

---

## Roles

| Who | Owns |
|-----|------|
| LILITH | Audit, flag, propose sibling glossaries + migration queue |
| WOLFIE | Orchestration |
| ALII (ERIC 10000) | Approve migrations |
| CURSOR / builders | Execute only after ALII |

Migrations: [prd_naming_migration_queue.md](prd_naming_migration_queue.md)

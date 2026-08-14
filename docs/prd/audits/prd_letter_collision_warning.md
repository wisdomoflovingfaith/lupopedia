---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/audits/prd_letter_collision_warning.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/audits/prd_letter_collision_warning.md
  status: active
  when_updated: "20260801010640"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-naming-audit-thoth-puka
  artifact_type: audit
  artifact_kind: warning
  channel_key: development
  federation_node_id: 0
  thread_key: prd_naming_audit
  lupopedia.schema: audit
  prd_cluster: 84_A_16_A_98_A
  title: "Deliverable 3 -- Letter collision warning (filename vs cluster)"
  summary: "Filename A-Z siblings are not cluster A-F significance. Primary NN_Z title leaks remediated under ALII M01-M09. Prevention rules retained."
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
# Deliverable 3 -- Letter collision warning

**POST-MIGRATION** after `[ALII_APPROVED: 20260801T200600]`. Primary `NN_Z` title leaks closed via M01-M09.  
**KAPAKAI name:** LETTER_SYSTEM_COLLISION

---

## Two systems (not the same)

| System | Where | Alphabet | Meaning |
|--------|-------|----------|---------|
| **Filename letter** | `NN_LETTER-roman_SLUG.md` | A-Z as needed | Sibling document **inside** group NN |
| **Cluster significance letter** | `prd_cluster` positional grammar (PRD 16_A) | **A-F only** (grade) | Meaning weight / enforcement strength |

**One sentence:** Filename letter picks the sibling file. Cluster A-F grades significance. Cluster **position** (left-to-right) sets read priority. Group **number** only groups.

---

## Why THOTH leaked

THOTH (knowledge / records) surfaces truth from headers, titles, indexes, and cluster strings. When those disagree, THOTH can **amplify the wrong letter**:

1. Filename says `82_B-i` (sibling B).  
2. Title / H1 says `PRD 82_Z` (informal wildcard habit).  
3. Cluster doctrine says letter A-F = **significance**.  
4. An agent (or THOTH-assisted summary) merges all three into one token soup: "Z-grade B-sibling significance."  
5. Downstream actors treat Z as real, rename proposals appear, or audits mark the wrong PUKA.

That is not THOTH inventing malice -- it is **unresolved KAPAKAI in the corpus** leaking through a records actor that faithfully repeats conflicting fields.

Related leak: English polysemy (`SET` / `FIX`) plus letter collision = double hallucination risk.

---

## How to prevent future collisions

1. **Title signature must match filename letter:** `PRD NN_LETTER:` == file `NN_LETTER-roman`.  
2. **Ban title wildcard `NN_Z`** unless the filename letter is actually Z.  
3. **Say the axis when speaking:** "sibling 82_B" vs "significance grade A in cluster".  
4. **Validators:** fail TITLE_SIG_MISMATCH when title letter != filename letter (see KAPAKAI alert).  
5. **THOTH / index generators:** prefer filename parse over title prose when they conflict; emit KAPAKAI row instead of picking Z.  
6. **Do not** invent a third global A-Z topic taxonomy on top of 00-99.

---

## Quick test

| Claim | Pass? |
|-------|-------|
| "`82_B` is less important than `82_A`" | FAIL -- siblings, not grades |
| "Cluster grade B means open `82_B`" | FAIL -- grade is not a path |
| "Title `PRD 82_B` on file `82_B-i`" | PASS |
| "Title `PRD 82_Z` on file `82_B-i`" | FAIL -- TITLE_SIG_MISMATCH |

See also: [prd_kapakai_alert_title_z.md](prd_kapakai_alert_title_z.md), [prd_naming_rule_sheet.md](prd_naming_rule_sheet.md).

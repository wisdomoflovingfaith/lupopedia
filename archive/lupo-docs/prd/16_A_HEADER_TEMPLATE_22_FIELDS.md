---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/16_A_HEADER_TEMPLATE_22_FIELDS.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/16_A_HEADER_TEMPLATE_22_FIELDS.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/headers/canonical/1026/04/header-template.toon
  atoms_toon: lupo-memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/header-template
  artifact_type: prd
  artifact_kind: template
  channel_key: headers
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_16_B_16_C_16_A_16_D
  title: "PRD 16: Header Template (22 Fields)"
  summary: "Standard 22-field header template for PRD files at version 4.1.4. Use as base for new documents."
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->

<HUMAN_SEMANTIC>
This file belongs to:
??? PRD Group 16 (Identity Layer ??? Headers, Atoms, Migration)
??? Cluster 16ABCDE
??? Channel: headers
??? No default collection yet

See also:
??? 00_A_FORBIDDEN_AND_WHY.md
??? 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
??? PRD 86 ??? Immune system (no drift, no entropy)
??? Order of Operations: PRD ??? Schema ??? Mockups ??? Code
</HUMAN_SEMANTIC>

# PRD 16: Header Template (22 Fields)

## Usage Instructions

Copy this template and replace the following placeholders:

- `FILENAME.md` ??? Your actual filename
- `YYYYMMDDHHIISS` ??? Current timestamp (run `python lupo-bin/tick.py` to generate)
- `CHANNEL` ??? Your channel (e.g., "prd", "development")
- `MM` ??? Current month (two digits)
- `slug` ??? Short identifier for your file
- `PRD_CLUSTER_HERE` ??? Your PRD cluster string
- `TITLE` ??? Your document title
- `SUMMARY` ??? Brief description

## Template Header (22 Fields)

```yaml
---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/FILENAME.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/FILENAME.md"
  status: "active"
  when_updated: "YYYYMMDDHHIISS"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/CHANNEL/canonical/1026/MM/slug.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/CHANNEL/slug"
  artifact_type: prd
  artifact_kind: template
  channel_key: "CHANNEL"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "PRD_CLUSTER_HERE"
  title: "TITLE"
  summary: "SUMMARY"
---
```

---

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  generated_by: "cascade"
  validation_status: "pending"
  ascii_compliance: "confirmed"
  last_validated: "20260421133500"

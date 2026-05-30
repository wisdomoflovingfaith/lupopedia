---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1046/20260321_175500_wolfie_directive_bmad_federation_model.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1046/20260321_175500_wolfie_directive_bmad_federation_model.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1046
  task_id: "task_federation_bmad_model_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Canonical federation model decision for BMAD-METHOD as federation_node_id 3, including project mapping, integration type, import scope, and execution contract."
  tags: ["wolfie", "directive", "federation", "bmad", "schema_authority", "4.0.85", "channel_42", "thread_1046"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FEDERATION_NODE_TYPES_DOCTRINE.md", type: "governed_by", weight: 1.0, reason: "Node type selection must follow canonical federation doctrine." }
    - { to: "lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md", type: "governed_by", weight: 1.0, reason: "Federation node scoping rules determine how BMAD content is partitioned from Lupopedia core." }
    - { to: "lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md", type: "constrained_by", weight: 1.0, reason: "Project promotion rules determine whether BMAD receives an internal project_id." }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "future_updates", weight: 0.9, reason: "Any future schema or seed work for node 3 must respect this directive." }
    - { to: "https://github.com/bmad-code-org/BMAD-METHOD", type: "references", weight: 0.95, reason: "External source repository governed by this federation decision." }
lupopedia.footer:
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "HEPHAESTUS: create canonical node-3 seed or migration entry for BMAD in lupo_federation_nodes."
    - "HEPHAESTUS: create filesystem mirror root and semantic ingestion pipeline for BMAD agent/workflow/module metadata."
    - "THOTH: document node-3 import semantics and resulting data surfaces after implementation."
---
# file: WOLFIE Directive — Canonical BMAD Federation Model

**Thread ID:** 1032  
**Actor:** WOLFIE (actor_id 1)  
**Channel:** 42  
**Version:** 4.0.85

This directive defines the canonical federation model for BMAD-METHOD. These decisions are final for Phase 1 BMAD integration. No ambiguity is permitted.

---

## 1. federation_node_spec

```yaml
federation_node_spec:
  federation_node_id: 3
  node_name: "bmad"
  node_base_url: "https://github.com/bmad-code-org/BMAD-METHOD"
  node_type: "external"
  canonical_status: "approved"
  semantic_role: "external methodology source"
  trust_model: "pinned-source, pull-only"
```

### Binding interpretation

- BMAD is an **external federation node**, not a local kernel node and not a runtime peer inside Lupopedia.
- `node_type = external` is mandatory because BMAD is a remote GitHub-hosted upstream maintained outside Lupopedia.
- `node_base_url` is the canonical GitHub repository URL, not the docs site and not an npm package URL.

---

## 2. project_mapping_decision

```yaml
project_mapping_decision:
  decision: "external_only"
  create_lupo_projects_row: false
  project_id_3_assigned_to_bmad: false
  reserved_project_action: "leave_unallocated"
  rationale: "BMAD is an external methodology source, not an internally governed Lupopedia project with owned channels and lifecycle."
```

### Binding interpretation

- BMAD does **not** become `project_id = 3` in Phase 1.
- BMAD remains **external-only** until Lupopedia explicitly decides to create an internal mirrored operating project with owned channels, orchestrator, and lifecycle.
- `project_id = 3` remains free for future allocation under the normal project registry workflow.

---

## 3. integration_model

```yaml
integration_model:
  integration_type: "semantic_ingestion"
  import_scope:
    - "agents"
    - "workflows"
    - "modules"
  import_mode: "metadata_and_documentation_only"
  excluded_from_import:
    - "runtime installer execution"
    - "node/npm dependency execution"
    - "tooling binaries"
    - "direct prompt activation inside Lupopedia core"
  storage_model: "hybrid"
  filesystem_storage: "raw mirrored source snapshots under node-3 external storage"
  database_storage: "normalized content and metadata records describing BMAD agents, workflows, and modules"
```

### Binding interpretation

- The chosen integration type is **semantic_ingestion**.
- The import set is **agents + workflows + modules**.
- The import is **not runtime integration**. Lupopedia must not execute BMAD's installer, couple itself to BMAD's Node runtime, or treat BMAD agents as native Lupopedia runtime agents.
- The import is **not read_only_reference only**. BMAD content is important enough to normalize into Lupopedia search/content surfaces.
- The storage model is **hybrid**:
  - raw upstream material is mirrored on filesystem for provenance and reproducibility
  - normalized summaries and references are stored in database-facing Lupopedia content surfaces

### Canonical storage rule

BMAD imported artifacts must be represented as **external semantic content**, not as native internal identity rows.

- Use filesystem mirror storage for raw BMAD source snapshots.
- Use database ingestion for normalized metadata, extracted summaries, and searchable references.
- Do **not** insert BMAD upstream agent definitions into `lupo_agents` or `lupo_actors` as if they are native Lupopedia actors.
- Do **not** create BMAD runtime channels under Lupopedia solely because ingestion exists.

### Canonical filesystem target

```yaml
filesystem_target:
  root: "lupo-database/files/3/"
  mirror_slug: "bmad-method"
  source_of_truth: "pinned upstream snapshot from github.com/bmad-code-org/BMAD-METHOD"
```

---

## 4. execution_plan

```yaml
execution_plan:
  owner: "hephaestus"
  tasks:
    - "Create federation node 3 record for BMAD in canonical seed or migration path."
    - "Create node-3 filesystem mirror root at lupo-database/files/3/bmad-method/."
    - "Define ingestion script that extracts BMAD modules, agents, and workflows as semantic artifacts only."
    - "Persist normalized BMAD summaries into Lupopedia content/metadata surfaces with federation_node_id = 3."
    - "Do not create lupo_projects row for BMAD and do not allocate project_id 3."
    - "Do not register BMAD upstream agents as native lupo_actors or lupo_agents."
    - "Record import provenance for each ingested artifact using source URL and upstream path."
    - "Deliver a validation artifact proving node 3 exists, project_id 3 remains unused, and BMAD content is searchable as external federation data."
```

### HEPHAESTUS implementation contract

1. **Node registration**  
Create the canonical node-3 entry with `node_name = 'bmad'`, `node_base_url = 'https://github.com/bmad-code-org/BMAD-METHOD'`, and `node_type = 'external'`.

2. **Filesystem mirror**  
Establish `lupo-database/files/3/bmad-method/` as the provenance-preserving mirror root for BMAD snapshots.

3. **Semantic extractor**  
Extract only these BMAD categories:
   - agents
   - workflows
   - modules

4. **Normalization boundary**  
Normalize BMAD into Lupopedia as external content and metadata only. No runtime adoption, no installer execution, no actor promotion.

5. **Validation**  
Prove all of the following after implementation:
   - federation node 3 exists
   - no `lupo_projects` row was created for BMAD
   - `project_id = 3` remains unassigned
   - BMAD agents/workflows/modules are visible as external semantic artifacts scoped to `federation_node_id = 3`

---

## 5. Final Decision Summary

```yaml
final_decision_summary:
  federation_node_id: 3
  node_name: "bmad"
  node_type: "external"
  integration_type: "semantic_ingestion"
  project_mapping: "external_only"
  project_id_3: "not_assigned"
  imported_entities:
    - "agents"
    - "workflows"
    - "modules"
  storage_model: "hybrid"
```

These are the canonical BMAD federation decisions for Thread 1032.
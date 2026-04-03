---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403110451"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/edges.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-edges"
  actor_id: 102
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "implementation"
  artifact_kind: "edges"
  purpose: "Cross-links for PRD 33 implementation workspace"
  tags:
    - "implementation"
    - "edges"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: implements
      weight: 1.0
      reason: "Parent PRD"
    - to: "lupo-docs/versions/4.0.94/TODO.md"
      type: references
      weight: 0.95
      reason: "Section 12 backlog"
    - to: "craftysyntax-reference/"
      type: references
      weight: 1.0
      reason: "Crafty parity reference tree"
    - to: "lupo-docs/doctrine/DYNAPI_DOCTRINE.md"
      type: documents
      weight: 1.0
      reason: "DynAPI library doctrine; PRD 33 section 8 and PRD 28 semantic layer"
    - to: "lupo-docs/doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md"
      type: documents
      weight: 1.0
      reason: "Shared hosting / PHP floor aligned with certification gate"
    - to: "lupo-docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md"
      type: documents
      weight: 1.0
      reason: "Minimal hosting requirements doctrine"
    - to: "lupo-docs/doctrine/INSTALLATION_PATH_DOCTRINE.md"
      type: documents
      weight: 1.0
      reason: "Subdirectory install doctrine"
    - to: "lupo-docs/doctrine/SAFE_MIGRATION_DOCTRINE.md"
      type: documents
      weight: 0.95
      reason: "Safe migration doctrine (tooling policy)"
    - to: "lupo-docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md"
      type: documents
      weight: 0.95
      reason: "Schema / TOON alignment under hosting constraints"
    - to: "lupo-docs/doctrine/TABLE_CEILING_DEFENSE_PLAN.md"
      type: documents
      weight: 0.9
      reason: "Table ceiling defense (hosting limits)"
    - to: "lupo-docs/doctrine/TABLE_CONSOLIDATION_PLAN.md"
      type: documents
      weight: 0.9
      reason: "Table consolidation plan"
    - to: "lupo-docs/doctrine/CASCADE_TABLE_CEILING_PROTOCOL.md"
      type: documents
      weight: 0.9
      reason: "Cascade table ceiling protocol"
    - to: "lupo-docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md"
      type: documents
      weight: 0.95
      reason: "Crafty import troubleshooting (upgrade path context)"
    - to: "lupo-docs/doctrine/LEXA_GATEWAY_INTEGRATION.md"
      type: documents
      weight: 0.85
      reason: "Gateway integration notes (deployment surface)"
---

# Edges — PRD 33 implementation

| From | To | Type |
|------|-----|------|
| This workspace | [PRD 33](../../prd/33_softaculous_certification_4_1_0_gate.md) | implements |
| This workspace | [4.0.94 TODO](../../versions/4.0.94/TODO.md) | traceability |
| PRD 33 | `craftysyntax-reference/` | parity reference |

## Outbound Edges — Doctrine (documents)

| Target | Type | Weight | Reason |
|--------|------|--------|--------|
| [`../../doctrine/DYNAPI_DOCTRINE.md`](../../doctrine/DYNAPI_DOCTRINE.md) | documents | 1.0 | DynAPI in-tree library doctrine (PRD 33 section 8; ties to PRD 28) |
| [`../../doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md`](../../doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md) | documents | 1.0 | PHP floor / shared hosting |
| [`../../doctrine/MINIMAL_HOSTING_REQUIREMENTS.md`](../../doctrine/MINIMAL_HOSTING_REQUIREMENTS.md) | documents | 1.0 | Minimal hosting doctrine |
| [`../../doctrine/INSTALLATION_PATH_DOCTRINE.md`](../../doctrine/INSTALLATION_PATH_DOCTRINE.md) | documents | 1.0 | Subdirectory install |
| [`../../doctrine/SAFE_MIGRATION_DOCTRINE.md`](../../doctrine/SAFE_MIGRATION_DOCTRINE.md) | documents | 0.95 | Safe migration runner doctrine |
| [`../../doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md`](../../doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md) | documents | 0.95 | Schema / TOON alignment |
| [`../../doctrine/TABLE_CEILING_DEFENSE_PLAN.md`](../../doctrine/TABLE_CEILING_DEFENSE_PLAN.md) | documents | 0.9 | Table ceiling defense |
| [`../../doctrine/TABLE_CONSOLIDATION_PLAN.md`](../../doctrine/TABLE_CONSOLIDATION_PLAN.md) | documents | 0.9 | Table consolidation |
| [`../../doctrine/CASCADE_TABLE_CEILING_PROTOCOL.md`](../../doctrine/CASCADE_TABLE_CEILING_PROTOCOL.md) | documents | 0.9 | Cascade table ceiling |
| [`../../doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md`](../../doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md) | documents | 0.95 | Crafty import troubleshooting |
| [`../../doctrine/LEXA_GATEWAY_INTEGRATION.md`](../../doctrine/LEXA_GATEWAY_INTEGRATION.md) | documents | 0.85 | Gateway integration |

---

This file complies with Lupopedia Constitutional Root Rules.

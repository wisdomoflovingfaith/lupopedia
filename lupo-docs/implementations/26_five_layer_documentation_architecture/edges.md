---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "lupo-docs/implementations/26_five_layer_documentation_architecture/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/26_five_layer_documentation_architecture/edges.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "26-five-layer-edges"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "implementation"
  artifact_kind: "edges"
  purpose: "System edges and relationships for five-layer documentation architecture"
  parent_prd: "26_five_layer_documentation_architecture"
  tags:
  - "implementation"
  - "documentation"
  - "edges"
  - "where"
---

# System Edges & Relationships

## Database Edges

### Tables
- **lupo_actors**: Actor registry for provenance tracking
- **lupo_contents**: Content storage for documentation
- **lupo_metadata**: Metadata for all documentation files

### Columns
- **lupo_actors.actor_id**: BIGINT - Primary identifier
- **lupo_actors.actor_type**: VARCHAR - actor|agent|user
- **lupo_metadata.parent_prd**: VARCHAR - Link to parent PRD

### Relationships
- **lupo_metadata.parent_prd** → **lupo_contents.path**: Documentation relationship - Application-managed
- **lupo_metadata.actor_id** → **lupo_actors.actor_id**: Attribution relationship - Application-managed

## Code Edges

### PHP Classes
- **ImplementationValidator**: lupo-scripts/validate_implementation.py - Main validation logic
- **DocumentationFactory**: lupo-includes/classes/DocumentationFactory.php - Document generation

### Scripts
- **validate_implementation.py**: lupo-scripts/ - Validation enforcement
- **pre_commit_validate.py**: lupo-scripts/ - CI integration

### Services
- **ValidationService**: lupo-includes/services/ValidationService.php - Validation API

## Documentation Edges

### PRD Links
- **Parent PRD**: 26_five_layer_documentation_architecture.md - Defines architecture
- **Related PRDs**: 00_root_constitutional_system_requirements.md - Constitutional anchor
- **Related PRDs**: 25_departments_system.md - Example implementation

### Implementation Links
- **Related Implementations**: 25_departments_systems/ - First compliant implementation
- **Shared Components**: lupo-scripts/validate_implementation.py - Validation script
- **Templates**: _template/ - Implementation templates

### Discussion References
- **Design Threads**: discussions/ - Architecture design decisions
- **Validation Threads**: discussions/validation/ - Validation approach

## UI Edges

### Templates
- **documentation_index.php**: lupo-views/admin/ - Documentation management
- **validation_report.php**: lupo-views/admin/ - Validation results

### JavaScript
- **documentation_manager.js**: lupo-ui/js/ - Documentation UI interactions
- **validation_status.js**: lupo-ui/js/ - Real-time validation status

### CSS
- **documentation.css**: lupo-ui/css/ - Documentation styling

## External Edges

### APIs
- **Validation API**: /api/validation/validate - Validation endpoint
- **Documentation API**: /api/docs/ - Documentation access

### Third-Party Libraries
- **PyYAML**: Python - YAML parsing for validation
- **JSON Schema**: composer - Schema validation

## Impact Analysis

### Upstream Dependencies
- **Constitutional Requirements**: High impact - Must comply with PRD 00
- **Channel System**: Medium impact - Discussions link to channels

### Downstream Dependencies
- **All Implementations**: High impact - All must comply
- **CI Pipeline**: High impact - Validation enforced on commit
- **Documentation Generation**: Medium impact - Templates used for new implementations

### Potential Conflicts
- **Legacy Implementations**: Medium risk - 90-day migration window
- **Schema Changes**: Low risk - Versioned with doc_arch_version

---
*This file maps all relationships and dependencies for the five-layer documentation architecture implementation.*

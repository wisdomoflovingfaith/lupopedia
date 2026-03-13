# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\HEADERS\FLIP_FOOTER_DOCTRINE_4_0_31.md"
  file_hash: "65c69b6d325d81b25f061a650bdce705d199f53621edd3b5a775bb711ae21846"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\doctrine\HEADERS\FLIP_FOOTER_DOCTRINE_4_0_31.md"
  file_hash: "762caf8adab638cc43d83793031c199133f6f0005da3b31ae13d46515e238ee5"
  file_path_from_root: "docs\doctrine\HEADERS\FLIP_FOOTER_DOCTRINE_4_0_31.md"
  file_hash: "a2e53d3a311fc832f7f3cd34af0effd1fe868e70e78dd84ab33ea1f8362a029d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIP_FOOTER_DOCTRINE_4_0_31.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "headers", "flip_footer_doctrine_4_0_31md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "docs/doctrine/HEADERS/FLIP_FOOTER_DOCTRINE_4_0_31.md"
file.last_modified_system_version: "4.0.31"
file.last_modified_utc: "20260223144700"
channel_id: 42
mood_rgb: "4B0082"
---

# FLIP Footer Doctrine 4.0.31

## Purpose

FLIP Footers provide **reverse-edge metadata** - they describe what references point INTO this file, creating bidirectional semantic relationships. While FLIP Headers describe outbound relationships, FLIP Footers describe inbound relationships.

## Core Principle

Every file in Lupopedia 4.0.31+ must have both:
- **FLIP Header** (outbound metadata)
- **FLIP Footer** (inbound metadata)

This creates a complete bidirectional semantic graph where every edge can be traversed in both directions.

## Footer Structure

```yaml
flip.footer:
  referenced_by_files:
    - "path/to/file1.php"
    - "path/to/file2.md"
  referenced_by_channels:
    - channel_id: 42
      channel_name: "development"
    - channel_id: 1
      channel_name: "main"
  referenced_by_threads:
    - channel_id: 42
      thread_id: 105
      description: "Initial design discussion"
    - channel_id: 1
      thread_id: 502
      description: "Security audit feedback"
  referenced_by_actors:
    - actor_id: 10000
      actor_name: "human_user"
      role: "developer"
    - actor_id: 1000
      actor_name: "CAPTAIN_WOLFIE"
      role: "ai_partner"
  inbound_edges:
    - edge_type: "semantic_dependency"
      source: "semantic_graph_builder.php"
      relationship: "consumes"
    - edge_type: "migration_dependency"
      source: "database/migrations/install_new_lupopedia.sql"
      relationship: "creates_tables_for"
  inbound_lupo_headers:
    - file: "app/Services/SemanticSecurityEngine.php"
      fields: ["file_path_from_root", "channel_id"]
  inbound_lupo_footers:
    - file: "docs/doctrine/HEADERS/FLIP_HEADER_DOCTRINE.md"
      fields: ["referenced_by_files"]
  footnotes:
    - "This file is part of the semantic security framework"
    - "Maintained by the development team on Channel 42"
  graph_render:
    - node_color: "mood_rgb"
    - cluster_by: "channel_id"
    - rank_dir: "LR"
  fair_compliance:
    findable: true
    accessible: 42
    interoperable: "TOON:lupo_registry"
    reusable: "v4.0.40"
  embedded_query: "SELECT inbound_edges WHERE relationship='consumes' LIMIT 5"
```

## Field Definitions

### referenced_by_files
Array of file paths that reference this file. This includes:
- PHP files that `require` or `include` this file
- MD files that link to this file
- SQL files that reference this file's tables
- Configuration files that load this file

### referenced_by_channels
Array of channels that use this file:
- `channel_id`: Numeric channel identifier
- `channel_name`: Human-readable channel name
- `purpose`: How the channel uses this file

### referenced_by_threads
Array of dialog threads where this file is discussed:
- `channel_id`: Numeric channel identifier where the thread exists.
- `thread_id`: Numeric identifier for the `lupo_dialog_threads` entry.
- `description`: Context of the discussion (e.g., "Architecture Review").

### referenced_by_actors
Array of actors that work with this file:
- `actor_id`: Unique actor identifier
- `actor_name`: Actor display name
- `role`: Actor's role relative to this file
- `last_modified`: When actor last modified this file

### inbound_edges
Array of semantic edges pointing to this file:
- `edge_type`: Type of semantic relationship
- `source`: File or component creating the edge
- `relationship`: Nature of the relationship (see `docs/doctrine/RELATION_REGISTRY.md`)
- `strength`: Edge strength (0-1, optional)
- `target_block`: Link to a specific `#heading` or anchor within the file.

### inbound_lupo_headers
Array of FLIP headers that reference this file:
- `file`: Source file containing the header
- `fields`: Specific header fields that reference this file

### inbound_lupo_footers
Array of FLIP footers that reference this file:
- `file`: Source file containing the footer
- `fields`: Specific footer fields that reference this file

### footnotes
Array of contextual notes about this file:
- Development notes
- Historical context
- Important warnings or considerations
- Future plans

### graph_render (Optional)
Array of visualization hints for the semantic graph renderer:
- `node_color`: Field to use for node color (e.g., "mood_rgb").
- `cluster_by`: Field to use for grouping nodes.
- `rank_dir`: Direction of the layout (LR, TB, etc.).

### fair_compliance (Optional)
FAIR data benchmarking for the artifact:
- `findable`: Boolean or search index status.
- `accessible`: access level or channel ID.
- `interoperable`: Alignment with TOON schema or external ontologies.
- `reusable`: License or version locking status.

### embedded_query (Optional)
A **FLIPQL** query string whose results are dynamically rendered into the footer by the system loader or IDE extension.

## Footer Placement

FLIP Footers are placed at the **end of every file**, after all content:

```yaml
---

flip.footer:
  # Footer content here
```

## Integration with Semantic Graph

### Reverse Edge Traversal
The semantic graph builder uses FLIP Footers to:
- Build reverse dependency graphs
- Calculate impact analysis for changes
- Identify circular dependencies
- Optimize build order

### Actor Relationship Mapping
Footers help map:
- Which actors work on which files
- Actor collaboration patterns
- Expertise distribution
- Responsibility boundaries

### Channel Usage Tracking
Footers track:
- Which channels use which files
- Cross-channel dependencies
- Channel-specific modifications
- Development workflow optimization

## Automated Footer Maintenance

### Footer Generation
The system can auto-generate initial footers by:
- Scanning file imports and includes
- Analyzing database references
- Tracking actor modifications
- Monitoring channel usage

### Footer Updates
Footers are updated when:
- New files reference this file
- Actors modify the file
- Channels start using the file
- Dependencies change

### Footer Validation
The system validates footers by:
- Checking referenced files exist
- Verifying actor IDs are valid
- Confirming channel assignments
- Ensuring edge consistency

## Footer Examples

### PHP Class Footer
```yaml
---

flip.footer:
  referenced_by_files:
    - "app/Controllers/OAuthController.php"
    - "app/Services/ActorService.php"
  referenced_by_channels:
    - channel_id: 42
      channel_name: "development"
  referenced_by_actors:
    - actor_id: 10000
      actor_name: "human_developer"
      role: "maintainer"
  inbound_edges:
    - edge_type: "class_dependency"
      source: "OAuthController.php"
      relationship: "uses"
  footnotes:
    - "Core authentication service"
    - "Handles OAuth provider integration"
```

### Documentation Footer
```yaml
---

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/versions/4.0.31/ROADMAP.md"
  referenced_by_channels:
    - channel_id: 42
      channel_name: "development"
    - channel_id: 1
      channel_name: "main"
  referenced_by_actors:
    - actor_id: 10000
      actor_name: "human_developer"
      role: "author"
    - actor_id: 1000
      actor_name: "CAPTAIN_WOLFIE"
      role: "reviewer"
  inbound_edges:
    - edge_type: "documentation_reference"
      source: "CHANGELOG.md"
      relationship: "cites"
  footnotes:
    - "Authoritative source for FLIP Footer rules"
    - "Updated for 4.0.31 with reverse-edge metadata"
```

## Migration from Headers Only

### Phase 1: Footer Addition
- Add footers to all existing files
- Initialize with basic reverse references
- Validate footer syntax

### Phase 2: Footer Population
- Auto-generate comprehensive footers
- Populate referenced_by_files from code analysis
- Map actor relationships from git history

### Phase 3: Footer Integration
- Integrate with semantic graph builder
- Enable reverse dependency tracking
- Implement impact analysis

## Footer Compliance Rules

### Required Fields
All footers must include:
- `referenced_by_files` (can be empty array)
- `referenced_by_channels` (at least one channel)
- `referenced_by_actors` (at least one actor)
- `footnotes` (can be empty array)

### Optional Fields
- `inbound_edges` (for semantic relationships)
- `inbound_lupo_headers` (for header references)
- `inbound_lupo_footers` (for footer references)

### Validation Rules
- All referenced files must exist
- All actor IDs must be valid
- All channel IDs must be registered
- All edges must have valid sources

## Footer Security

### Access Control
Footers respect the same security rules as headers:
- Only authorized actors can modify footers
- Channel-specific footer modifications are tracked
- Footer changes are audited

### Semantic Security
Footers enhance semantic security by:
- Providing complete relationship visibility
- Enabling comprehensive impact analysis
- Supporting dependency validation
- Preventing unauthorized modifications

## Future Enhancements

### Dynamic Footer Updates
- Real-time footer synchronization
- Automatic dependency tracking
- Live relationship updates

### Footer Analytics
- Relationship pattern analysis
- Collaboration metrics
- Dependency health monitoring

### Footer Optimization
- Intelligent footer compression
- Relationship deduplication
- Performance optimization

---

## Implementation Status

- **Version**: 4.0.31
- **Status**: Active Doctrine
- **Channel**: 42 (Development)
- **Required**: All files in 4.0.31+
- **Integration**: Semantic Graph Builder v2.0

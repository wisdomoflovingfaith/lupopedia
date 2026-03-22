# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/roles/graph_intelligence_agent.md"
  file_hash: "729dfb1fd49ab670e162a35df908cfcfb49ad1a154d96e45c0c653abca2ebcd7"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\roles\graph_intelligence_agent.md"
  file_hash: "d9a48ba9db5777a85c088b58356f902e96558ac54c6eeca3fed36b150c316a4a"
  file_path_from_root: "lupo-channels\0\roles\graph_intelligence_agent.md"
  file_hash: "aa29991a2c1804680cbdc4ee64092ede3540398812dafe3f8122d6aa2b6d6daa"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for graph_intelligence_agent.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "roles", "graph_intelligence_agentmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
role_id: graph_intelligence_agent
channel_id: 0
authority_level: standard
granted_by: 10000
derived_from:
  - "semantic_analysis"
  - "relationship_discovery"
permissions:
  - analyze_file_relationships
  - detect_semantic_similarity
  - recommend_edges
  - identify_duplicates
  - build_content_graph
  - generate_visualizations
assigned_to:
  - 25
created_utc: "2026-02-25T17:06:00Z"
updated_utc: "2026-02-25T17:06:00Z"
---

# Role: Graph Intelligence Agent

## Authority

**Level:** Standard  
**Scope:** Semantic analysis and relationship discovery  
**Granted By:** Captain (10000)

## Description

Graph Intelligence Agents are responsible for understanding relationships between files, finding semantic similarities, detecting near-duplicates, and recommending FLIP footer edge improvements. They build and maintain the semantic content graph.

## Permissions

### Relationship Analysis
- Read all repository files
- Extract metadata and content
- Identify cross-references
- Map dependencies

### Semantic Analysis
- Calculate content similarity
- Identify topical relationships
- Detect semantic clusters
- Find related documents

### Edge Recommendations
- Recommend FLIP footer edges
- Suggest missing references
- Identify circular dependencies
- Propose relationship improvements

### Duplicate Detection
- Find near-duplicate files
- Identify redundant content
- Recommend consolidation
- Flag for human review

### Graph Building
- Build semantic content graph
- Generate visualization data
- Maintain graph metadata
- Update relationships

## Assigned Actors

- **25** - VISHWAKARMA (Vishwakarma Intelligence System for Hierarchical Workflow and Knowledge Architecture)

## Responsibilities

1. **File Relationship Discovery**
   - Analyze all documentation
   - Analyze all broadcasts
   - Analyze all directives
   - Map relationships

2. **Semantic Analysis**
   - Calculate similarity scores
   - Identify topic clusters
   - Find related content
   - Detect patterns

3. **Edge Recommendations**
   - Recommend FLIP footer additions
   - Suggest missing references
   - Identify weak connections
   - Propose improvements

4. **Duplicate Management**
   - Find near-duplicates
   - Calculate similarity scores
   - Recommend consolidation
   - Flag for review

5. **Graph Maintenance**
   - Build content graph
   - Update relationships
   - Generate visualizations
   - Maintain metadata

## Constraints

- Read-only access (cannot modify files)
- Must not make assumptions about intent
- Must provide confidence scores
- Must flag uncertain cases for human review

## Success Criteria

- All files analyzed
- Relationships mapped
- Duplicates identified
- Recommendations provided
- Graph visualization generated

## Escalation

Graph Intelligence Agents report to System Administrators. All recommendations require human approval before implementation.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "lupo-docs/",
    "lupo-channels/0/broadcasts/",
    "lupo-channels/42/broadcasts/",
    "lupo-database/migrations/seed_anubis_vishwakarma_4.0.45.sql"
  ],
  "implements": "graph_intelligence_authority_model",
  "depends_on": "vishwakarma_agent_seeding",
  "role_category": "analysis",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->

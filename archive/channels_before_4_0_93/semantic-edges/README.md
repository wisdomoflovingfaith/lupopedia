---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "channels/semantic-edges/README.md"
  web_path: "http://www.lupopedia.com/channels/semantic-edges/README.md"
  questions_toon: null
  channel_id: "semantic-edges"
  thread_id: null
  actor_id: 105
  actor_name: "windsurf"
  artifact_type: "channel_charter"
  artifact_kind: "semantic_edges_documentation"
  purpose: "Channel charter for semantic edges discussion and documentation"
  references:
    - "docs/database/lupopedia/tables/active/lupo_edges.md"
    - "channels/42/broadcasts/20260325_102000_windsurf_semantic_tables_cleanup_analysis.md"
  tags: ["semantic_edges", "channel_charter", "documentation", "4.0.87"]
---

# Semantic Edges Channel

**Channel ID:** semantic-edges  
**Purpose:** Documentation and discussion of semantic edge types and usage patterns  
**Status:** ACTIVE  
**Version:** 4.0.87  

## Channel Mission

This channel is dedicated to:
1. **Documenting edge types** used across Lupopedia's semantic graph
2. **Discussing edge usage patterns** and best practices  
3. **Resolving edge type questions** and taxonomy issues
4. **Tracking FLARE protocol** implementation and automation
5. **Providing guidance** on edge creation and maintenance

## Scope

### In Scope
- ✅ Edge type taxonomy and definitions
- ✅ Usage patterns and examples
- ✅ FLARE protocol documentation
- ✅ Edge creation best practices
- ✅ Performance optimization for edge queries
- ✅ Edge validation and cleanup procedures

### Out of Scope
- ❌ General database administration (use Channel 0)
- ❌ Channel coordination (use Channel 42)
- ❌ Actor-specific relationships (use respective actor channels)
- ❌ AI cognitive context (use lupo_context_edges table documentation)

## Primary Resources

### Canonical Edge Table
- **Table:** `lupo_edges`
- **Documentation:** [lupo_edges.md](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_edges)
- **Capability:** Handles ALL inter-entity relationships

### Specialized Edge Tables
- **`lupo_context_edges`** - AI cognitive context only
- **`lupo_actor_edges`** - Actor-to-actor relationships  
- **`lupo_decision_edges`** - Decision dependencies

## Edge Type Taxonomy

### 1. Documentation Edges
| Type | Description | Weight Range | Example |
|-------|-------------|---------------|---------|
| `references` | Standard documentation links | 0.5-1.0 | API docs reference implementation |
| `example_of` | Code examples and demonstrations | 0.6-0.8 | Tutorial code references main docs |
| `implements` | Implementation relationships | 0.8-1.0 | Class implements interface |
| `schema_reference` | Database schema references | 1.0 | TOON file references table |

### 2. Dependency Edges  
| Type | Description | Weight Range | Example |
|-------|-------------|---------------|---------|
| `depends_on` | Required dependencies | 0.8-1.0 | Module depends on library |
| `conflicts_with` | Incompatible relationships | 0.9-1.0 | Two features conflict |
| `supersedes` | Version evolution | 0.9-1.0 | v2 supersedes v1 |

### 3. Association Edges
| Type | Description | Weight Range | Example |
|-------|-------------|---------------|---------|
| `related_to` | Loose associations | 0.5-0.7 | Related concepts |
| `similar_to` | Similar concepts | 0.6-0.8 | Alternative approaches |
| `belongs_to` | Membership relationships | 0.7-0.9 | Function belongs to module |

### 4. Process Edges
| Type | Description | Weight Range | Example |
|-------|-------------|---------------|---------|
| `leads_to` | Process flow | 0.7-0.9 | Step 1 leads to step 2 |
| `follows_from` | Sequential relationships | 0.7-0.9 | Step 2 follows step 1 |
| `enables` | Capability relationships | 0.8-1.0 | Feature enables capability |

## FLARE Protocol

### Automated Edge Discovery
- **`flare_weight`** - Importance scoring (0.5-1.0)
- **`flare_discovered_via`** - Discovery method tracking
- **`flare_verified`** - Path existence validation
- **`flare_auto_generated`** - Automation flag

### Discovery Methods
- `content_analysis` - Analyzing markdown content
- `toon_schema` - Database schema analysis  
- `db_scan` - Database relationship scanning
- `semantic_search` - Vector similarity search
- `manual` - Manually created edges

## Usage Patterns

### Common Edge Queries
```sql
-- Get all references for a content item
SELECT * FROM lupo_edges 
WHERE left_object_type = 'content' 
  AND left_object_id = :content_id 
  AND edge_type = 'references' 
  AND is_deleted = 0;

-- Get high-weight relationships
SELECT * FROM lupo_edges 
WHERE flare_weight >= 0.9 
  AND is_deleted = 0 
ORDER BY flare_weight DESC;
```

### Performance Considerations
- Use appropriate indexes for query patterns
- Filter with `is_deleted = 0` in all queries
- Consider edge weight for result ordering
- Archive old edges using soft delete

## Discussion Guidelines

### When to Create Threads
1. **New Edge Type Questions** - When you need clarification on edge types
2. **Usage Pattern Issues** - When edge queries are slow or incorrect
3. **FLARE Protocol Issues** - When automated edge discovery has problems
4. **Taxonomy Proposals** - When suggesting new edge categories
5. **Performance Problems** - When edge queries need optimization

### Thread Naming Convention
```
YYYYMMDD_HHIISS_actor_edge-topic-discussion.md
```

Examples:
- `20260325_110000_windsflare_protocol_implementation_questions.md`
- `20260325_120000_thoth_edge_taxonomy_proposal.md`

## Related Channels

- **Channel 42** - General protocol development
- **Channel 0** - System kernel and database
- **Actor Channels** - Actor-specific relationship discussions

## Maintenance

### Regular Tasks
1. **Edge Type Review** - Quarterly review of edge type usage
2. **Performance Analysis** - Monthly query performance review
3. **Documentation Updates** - As edge types evolve
4. **FLARE Protocol** - Monitor automated edge discovery

### Cleanup Procedures
1. **Orphaned Edges** - Remove edges pointing to deleted objects
2. **Duplicate Edges** - Identify and merge duplicate relationships
3. **Weight Validation** - Ensure edge weights are within ranges

## Contact Points

For questions about semantic edges:
- **Create threads** in this channel following naming convention
- **Tag with** `semantic_edges` for discoverability
- **Reference** canonical `lupo_edges` documentation
- **Use** appropriate edge types for your use case

---

*This channel serves as the central hub for semantic edge documentation, discussion, and evolution in the Lupopedia ecosystem.*

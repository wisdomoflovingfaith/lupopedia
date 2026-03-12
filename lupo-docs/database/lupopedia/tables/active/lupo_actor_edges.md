# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_actor_edges.md"
  file_hash: "f232016988acc4bf29d0349e11fded2179da28936840b1126e9c86da6367770b"
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
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_edges.md"
  file_hash: "f722700515722beeb70fadea215a306f8b3bba0695156efec23e61983662c7e9"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_edges.md"
  file_hash: "563c4a0ccd03b02e203274780a8b3cd7e6ceaf90ef18e24e13a4d54f1664d436"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_actor_edges.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_actor_edgesmd"]
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
wolfie.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/lupo_actor_edges.md",
  system_version: "4.0.48",
  channel_id: 1,
  actor_id: 1003,
  created_ymdhis: 20260227000000,
  updated_ymdhis: 20260227000000,
  message_type: "table_documentation",
  visibility: "public",
  priority: "high",
  mood_rgb: "4B0082",
  artifact_kind: "table",
  traits: ["canonical", "graph_relationships", "actor_network"],
  tags: ["database", "edges", "relationships", "graph", "actors"]
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-database/lupopedia/toon/lupo_actor_edges.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_actor_relationship_rules.md", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["relationship_graph", "influence_mapping", "collaboration_network"]
}
---

# 🕸️ Table: lupo_actor_edges

**Purpose:** Stores the directional relationships and connections between actors in a graph-like structure.  
**Type:** Relationship Graph Table  
**Status:** ✅ Production Ready  
**Volume:** High (multiple edges per actor)

---

## 🎯 **Overview**

The `lupo_actor_edges` table implements a semantic graph for actors. While `lupo_actors` stores the nodes, this table stores the edges (connections). This allows the system to map complex social, technical, and hierarchical networks, such as "Actor A supervises Actor B" or "Actor C frequently collaborates with Actor D".

### **Key Responsibilities**
- **Relationship Mapping:** Tracks bidirectional and directional links between actors.
- **Influence Weighting:** Provides a `weight` field to measure the strength or trust level of a connection.
- **Domain Segmentation:** Allows relationships to be grouped into domains (e.g., 'technical', 'social', 'governance').
- **Graph Traversal:** Enables complex queries to find influencers, bottlenecks, or isolated actors.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`actor_edge_id`** (BIGINT) - Unique graph edge identifier.

### **Core Edge Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `source_actor_id` | BIGINT | The starting actor node | |
| `target_actor_id` | BIGINT | The ending actor node | |
| `edge_type` | VARCHAR(100) | Semantic type of relationship | e.g., 'delegates_to', 'friend_of' |
| `domain_id` | BIGINT | Grouping for relationship types | |

### **Metrics & Properties**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `weight` | FLOAT | 1.0 | Strength/Trust level (0.0 to 1.0) |
| `properties` | TEXT | NULL | JSON-string of additional edge attributes |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Nodes:** Both `source_actor_id` and `target_actor_id` → `lupo_actors.actor_id`.
- **Rules:** Validated against `lupo_actor_relationship_rules` to ensure the relationship type is allowed for the given actor types.

---

## 🚀 **Usage Patterns**

### **Finding Collaborators**
Retrieving all actors with a strong collaboration edge from a specific actor.

```sql
SELECT target_actor_id, weight 
FROM lupo_actor_edges
WHERE source_actor_id = 10000 
  AND edge_type = 'collaborates_with' 
  AND weight > 0.8
  AND is_deleted = 0;
```

### **Hierarchy Discovery**
Mapping the reporting structure or delegation chain.

```sql
SELECT o.name as supervisor, a.name as subordinate
FROM lupo_actor_edges e
JOIN lupo_actors a ON e.target_actor_id = a.actor_id
JOIN lupo_actors o ON e.source_actor_id = o.actor_id
WHERE e.edge_type = 'supervises' AND e.is_deleted = 0;
```

---

## 🛡️ **Security & Privacy**

- **Privacy Thresholds:** Some edge types (e.g., 'friend_of') are restricted by default in public-facing actor profiles (`www/` directory).
- **IP Context:** Relationship creation is logged with the initiating IP address for fraud/spam detection in the social graph.
- **Data Sovereignty:** Cross-node edges are managed via the `lupo_federated_trust` system to prevent unauthorized disclosure of a local node's social graph.

---

*This documentation is part of the v4.0.48 Semantic Relationship framework.*
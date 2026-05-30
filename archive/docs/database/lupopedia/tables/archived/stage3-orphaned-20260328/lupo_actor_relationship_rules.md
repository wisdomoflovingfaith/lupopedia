# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_relationship_rules.md"
  file_hash: "5c4847326289f5807c542f14146bb2d227abb318935382dd36c4901730328091"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "core"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_relationship_rules.md"
  file_hash: "431eb16470ad12d9c48a74327ed70937f11dc9b2767c4930068dc892ecaa2f7c"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_actor_relationship_rules.md"
  file_hash: "f16319d7c0caeb0eb27ea232e05a271fac184e309b0ab1898c4de041cef2d919"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_actor_relationship_rules.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_actor_relationship_rulesmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/lupo_actor_relationship_rules.md",
  system_version: "4.0.48",
  channel_id: 1,
  actor_id: 1003,
  created_ymdhis: 20260227000000,
  updated_ymdhis: 20260227000000,
  message_type: "table_documentation",
  visibility: "public",
  priority: "high",
  mood_vector: "4B0082",
  artifact_kind: "table",
  traits: ["canonical", "governance", "interaction_logic"],
  tags: ["database", "rules", "relationships", "actors", "governance"]
}
flip.footer: {
  outbound_edges: [
    { to: "database/lupopedia/toon/lupo_actor_relationship_rules.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_actor_edges.md", type: "governs", weight: 1.0 }
  ],
  semantic_tags: ["interaction_policy", "authorization_rules", "delegation_logic"]
}
---

# âš–ï¸ Table: lupo_actor_relationship_rules

**Purpose:** Defines governance and interaction policies between specific actors or actor groups.  
**Type:** Governance Table  
**Status:** âœ… Production Ready  
**Volume:** Low (complex rule sets)

---

## ðŸŽ¯ **Overview**

The `lupo_actor_relationship_rules` table codifies the "laws" of interaction within the Lupopedia Semantic OS. While `lupo_actor_edges` stores the *existence* of a relationship, this table stores the *logic* governing that relationship (e.g., "Windsurf can only edit files if Kiro has verified the environment").

### **Key Responsibilities**
- **Governance Enforcement:** Defines what actions an actor can perform on another.
- **Delegation Logic:** Sets rules for when one actor can act on behalf of another.
- **Conflict Resolution:** Provides conditions to be checked during multi-agent overlaps.
- **Trust Calibration:** Stores the weights and conditions for bidirectional trust.

---

## ðŸ—ƒï¸ **Schema Reference**

### **Primary Key**
- **`rule_id`** (BIGINT) - Unique governance rule identifier.

### **Core Rule Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `source_actor_id` | BIGINT | The actor initiating the action | |
| `target_actor_id` | BIGINT | The actor receiving the action | |
| `relationship_type` | VARCHAR(100) | Type of interaction | e.g., 'supervises', 'audits' |
| `rule_type` | VARCHAR(50) | Domain of the rule | e.g., 'file_access', 'broadcast' |

### **Logic & Conditions**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `conditions` | JSON | NULL | Evaluative conditions (e.g., `{"min_trust": 0.8}`) |
| `actions` | JSON | NULL | Allowed/Forbidden actions |
| `weight` | FLOAT | 1.0 | Signal strength of the rule |

---

## ðŸ”— **Relationships & Dependencies**

### **Primary Relationships**
- **Source/Target:** `source_actor_id` & `target_actor_id` â†’ `lupo_actors.actor_id`
- **Edges:** This table provides the logical validation layer for `lupo_actor_edges`.

### **Integration**
- **Kernel Checks:** The Lupopedia kernel queries this table during sensitive operations to verify actor authority.

---

## ðŸš€ **Usage Patterns**

### **Verifying Authority**
Checks if an agent is authorized to modify another agent's tasks.

```sql
SELECT actions, conditions
FROM lupo_actor_relationship_rules
WHERE source_actor_id = 1005 
  AND target_actor_id = 1000 
  AND relationship_type = 'coordination' 
  AND is_deleted = 0;
```

### **Global Governance Audit**
Retrieving all active rules for a specific relationship type.

```sql
SELECT source_actor_id, target_actor_id, rule_type, conditions
FROM lupo_actor_relationship_rules
WHERE relationship_type = 'supervises' 
  AND is_deleted = 0;
```

---

## ðŸ›¡ï¸ **Security & Privacy**

- **Rule Immutability:** Sensitive governance rules should be flagged as `is_immutable` (in `conditions`).
- **Audit Logging:** Every modification to this table is recorded with the ID and IP address of the authorizing admin.
- **Zero Trust:** If no rule exists, the system defaults to "deny" for sensitive cross-actor operations.

---

*This documentation is part of the v4.0.48 Actor Governance framework.*


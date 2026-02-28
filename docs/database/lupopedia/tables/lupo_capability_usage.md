# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\lupo_capability_usage.md"
  file_hash: "91a6e2d1f88f058a4142c713b17c14868c8a7e34cfc7255ec4e9ddac4048ef6d"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_capability_usage.md"
  file_hash: "b1c35a12cbe8c696daa95c503a65dda4cd172a4c751d48fe231ae085dbc359ec"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_capability_usage.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_capability_usagemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/lupo_capability_usage.md",
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
  traits: ["canonical", "performance_tracking", "capability_auditing"],
  tags: ["database", "capabilities", "metrics", "analytics", "actors"]
}
flip.footer: {
  outbound_edges: [
    { to: "docs/toons/lupo_capability_usage.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_agents.md", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["usage_analytics", "efficiency_tracking", "capability_evolution"]
}
---

# 📈 Table: lupo_capability_usage

**Purpose:** Tracks the frequency, success rate, and performance and specific actor capabilities.  
**Type:** Analytics & Performance Table  
**Status:** ✅ Production Ready  
**Volume:** High (updates with every major capability exercise)

---

## 🎯 **Overview**

The `lupo_capability_usage` table provides the empirical data behind an actor's "expertise." While `identity.json` lists *what* an actor is capable of, this table records *how* often they use those skills and how effective they are. This information mirrors the detailed capability snapshots in Lilith's (2038) recommendations.

### **Key Responsibilities**
- **Skill Proficiency Tracking:** Measures success rates for tasks like 'database_migration' or 'file_edit'.
- **Latency Monitoring:** Records the average response time for specific actions.
- **Usage Auditing:** Tracks when a capability was last used.
- **Capability Evolution:** Provides data for determining when a capability is "mastered" or "deprecated".

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`usage_id`** (BIGINT) - Unique usage tracking identifier.

### **Core Usage Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_id` | BIGINT | The actor exercising the skill | |
| `capability` | VARCHAR(100) | The specific skill name | e.g., 'git_commit' |
| `usage_count` | BIGINT | Total invocations | Increments on use |
| `success_rate` | FLOAT | Percentage of successful outcomes | 0.0 to 1.0 |

### **Performance Metrics**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `avg_response_time_ms` | INT | 0 | Average latency |
| `last_used_ymdhis` | BIGINT | 0 | YYYYMMDDHHIISS of last use |
| `performance_metrics` | JSON | NULL | Granular data (e.g., token efficiency) |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Actor:** `actor_id` → `lupo_actors.actor_id`
- **Agents:** For agent-specific capabilities, this links to `lupo_agents.agent_id`.

---

## 🚀 **Usage Patterns**

### **Identifying Experts**
Finding the most successful actor for a specific task.

```sql
SELECT actor_id, success_rate, usage_count
FROM lupo_capability_usage
WHERE capability = 'security_audit' 
  AND success_rate > 0.95 
  AND is_deleted = 0
ORDER BY usage_count DESC;
```

### **Latency Monitoring**
Detecting degradation in core system capabilities.

```sql
SELECT capability, avg_response_time_ms
FROM lupo_capability_usage
WHERE actor_id = 1000 -- Kiro
  AND avg_response_time_ms > 5000;
```

---

## 🛡️ **Security & Privacy**

- **Capability Limiting:** If an actor's `success_rate` falls below a threshold, the governor may temporarily revoke the capability.
- **Verification:** Only authorized tool calls contribute to these metrics.
- **IP Context:** The initiating IP for the most recent use is stored in `performance_metrics` for geographic or network-based anomaly detection.

---

*This documentation is part of the v4.0.48 Capability Evolution framework.*
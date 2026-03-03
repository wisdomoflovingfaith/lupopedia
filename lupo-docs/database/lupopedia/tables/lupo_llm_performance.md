# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_llm_performance.md"
  file_hash: "38e57451b023e0ab94cd2034a7997676685c75bbb184855fd29547687e9de395"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\lupo_llm_performance.md"
  file_hash: "bd59fa79902c0f27aee0e14a5807bda16b49f602e8c2a245d30239f717b52b8d"
  file_path_from_root: "docs\database\lupopedia\tables\lupo_llm_performance.md"
  file_hash: "6bcb4fb5b8284ba9e14042877ec3372be38d43b61ddfc373142497d3a230dfd5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_llm_performance.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_llm_performancemd"]
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
  file_path_from_root: "docs/database/lupopedia/tables/lupo_llm_performance.md",
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
  traits: ["canonical", "llm_monitoring", "cost_tracking"],
  tags: ["database", "llm", "performance", "tokens", "cost", "actors"]
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-database/lupopedia/toon/lupo_llm_performance.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_agents.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_capability_usage.md", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["token_accounting", "model_efficiency", "provider_analysis"]
}
---

# 📉 Table: lupo_llm_performance

**Purpose:** Tracks the efficiency, token usage, cost, and quality of specific LLM modules per actor.  
**Type:** Technical Analytics Table  
**Status:** ✅ Production Ready  
**Volume:** High (updates with every LLM interaction)

---

## 🎯 **Overview**

The `lupo_llm_performance` table provides the deep metrics required for cost/benefit analysis of AI agent operations. It tracks how different LLMs (e.g., Claude, GPT-4) perform for specific actors, enabling the system to optimize model selection based on historical quality and cost. This directly implements the performance monitoring requested in Lilith's (2038) review.

### **Key Responsibilities**
- **Token Accounting:** Tracks cumulative token processing for cost management.
- **Provider Monitoring:** Measures reliability and latency of API providers (OpenAI, Anthropic, etc.).
- **Quality Benchmarking:** Stores human- or system-rated quality scores for LLM outputs.
- **Model Efficiency:** Compares the cost-per-result across different model tiers.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`performance_id`** (BIGINT) - Unique LLM performance record identifier.

### **Core LLM Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_id` | BIGINT | The agent using the LLM | |
| `llm_module` | VARCHAR(100) | Specific model name | e.g., 'gpt-4-turbo' |
| `provider` | VARCHAR(50) | API service provider | |
| `total_tokens` | BIGINT | Cumulative tokens used | In + Out |

### **Efficiency & Quality**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `avg_response_time_ms` | INT | 0 | Latency per request |
| `success_rate` | FLOAT | 1.0 | Uptime/Reliability percentage |
| `cost_per_1k_tokens` | DECIMAL(10,4) | 0.0000 | Current cost rate |
| `quality_score` | FLOAT | 1.0 | Aggregate quality metric (0.0-10.0) |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Agent Identity:** `actor_id` → `lupo_agents.agent_id`
- **Capability Sync:** Often paired with `lupo_capability_usage` to associate performance with specific tasks.

---

## 🚀 **Usage Patterns**

### **Model Optimization Query**
Determining which model provides the highest quality for the lowest cost for a specific agent.

```sql
SELECT llm_module, quality_score, cost_per_1k_tokens
FROM lupo_llm_performance
WHERE actor_id = 1001 -- Windsurf
  AND success_rate > 0.98
ORDER BY quality_score DESC, cost_per_1k_tokens ASC;
```

### **Usage Limit Check**
Retrieving total consumption for a specific provider across all agents.

```sql
SELECT provider, SUM(total_tokens) as total_consumption
FROM lupo_llm_performance
WHERE is_deleted = 0
GROUP BY provider;
```

---

## 🛡️ **Security & Privacy**

- **Cost Boundaries:** The governor uses this table to implement hard-caps on token usage to prevent recursive loops or high-cost runs.
- **Data Sovereignty:** Performance data is stored locally but can be exported as part of the actor's Identity Capsule.
- **IP Protection:** While provider details are tracked, sensitive API session metadata is handled in ephemeral memory or encrypted in `lupo_api_token_logs`.

---

*This documentation is part of the v4.0.48 LLM Governance framework.*
---
wolfie.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/lupo_agents.md",
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
  traits: ["canonical", "core_system", "agent_management", "llm_config"],
  tags: ["database", "agents", "ai", "llm", "configuration"]
}
flip.footer: {
  outbound_edges: [
    { to: "docs/toons/lupo_agents.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "docs/database/lupopedia/tables/lupo_llm_performance.md", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["agent_identity", "llm_parameters", "performance_monitoring", "safety_filters"]
}
---

# 🤖 Table: lupo_agents

**Purpose:** Configuration, parameters, and status for AI agents in the Lupopedia ecosystem.  
**Type:** Core System Table  
**Status:** ✅ Production Ready  
**Volume:** Low (one record per agent)

---

## 🎯 **Overview**

The `lupo_agents` table stores the specific configuration for AI agents, including their LLM models, parameters (temperature, top_p), and safety scores. It acts as the technical companion to the `lupo_actors` table for entities where `is_agent = 1`.

### **Key Responsibilities**
- **Agent Technical Configuration:** Defines the underlying LLM model and API provider.
- **Parameter Tuning:** Stores execution parameters like temperature, max_tokens, etc.
- **Safety & Alignment:** Tracks Pono/Kapakai scores and safety settings.
- **Performance Baseline:** Stores basic performance metrics like success rate and cost.
- **Lifecycle Management:** Tracks agent versions and soft deletion status.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`agent_id`** (BIGINT) - Unique identifier (matches `actor_id` in `lupo_actors`)

### **Core Configuration Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `agent_key` | VARCHAR(100) | Unique slug for the agent | e.g., 'kiro-ide' |
| `agent_name` | VARCHAR(150) | Human-readable name | |
| `archetype` | VARCHAR(150) | Role classification | e.g., 'Developer', 'Governor' |
| `model_name` | VARCHAR(100) | Specific LLM model | e.g., 'gpt-4', 'claude-3-5-sonnet' |
| `provider` | VARCHAR(50) | API provider | e.g., 'openai', 'anthropic' |

### **LLM Parameters**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `temperature` | FLOAT | 0.7 | Creativity/randomness control |
| `top_p` | FLOAT | 1.0 | Nucleus sampling parameter |
| `max_tokens` | INT | 2048 | Maximum output length |
| `system_prompt` | TEXT | NULL | Base instruction set for the agent |

### **Safety & Scoring (LOA System)**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pono_score` | DECIMAL(3,2) | 1.00 | Righteousness/Alignment score |
| `pilau_score` | DECIMAL(3,2) | 0.00 | Corruption/Pollution score |
| `kapakai_score` | DECIMAL(3,2) | 0.50 | Quality/Utility score |
| `safety_json` | JSON | NULL | Specific safety filter configurations |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Actor Identity:** `agent_id` → `lupo_actors.actor_id` (1:1 relationship)
- **API Keys:** `api_key_id` → `lupo_api_keys.key_id`

### **Performance Tracking**
- **Performance Logs:** `lupo_llm_performance` table tracks execution metrics per agent.

---

## 🚀 **Usage Patterns**

### **Agent Initialization**
Agents are typically retrieved by their `agent_key` or `agent_id` when an IDE session starts.

```sql
SELECT a.*, act.actor_root_path 
FROM lupo_agents a
JOIN lupo_actors act ON a.agent_id = act.actor_id
WHERE a.agent_key = 'gemini-cli' AND a.is_deleted = 0;
```

### **Parameter Update**
Admin tools can tune agent behavior by updating parameters.

```sql
UPDATE lupo_agents 
SET temperature = 0.5, updated_ymdhis = 20260227120000 
WHERE agent_id = 1006;
```

---

## 🛡️ **Security & Privacy**

- **Internal Only Flag:** `is_internal_only` ensures agents aren't exposed to external APIs unless intended.
- **Global Authority:** `is_global_authority` allows an agent to bypass certain permission checks.
- **IP Tracking:** All agent tool calls and interactions are logged with the initiating IP address in `lupo_agent_tool_calls` for auditability.

---

*This documentation is part of the v4.0.48 Actor Identity System expansion.*

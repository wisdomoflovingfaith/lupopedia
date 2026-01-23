---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added WOLFIE Header v4.0.0. Next move: generate SQL inserts for all 100 agents, then create the complete folder structure and JSON files for each agent following the strict template."
tags:
  categories: ["documentation", "specification", "agents"]
  collections: ["core-docs", "agent-registry"]
  channels: ["dev", "cursor"]
file:
  title: "DIRECTIONS.md - Agent Registry & File Rewrite Instructions"
  description: "Strict, unambiguous instructions for implementing the Lupopedia Agent Registry and rewriting all agent directories under lupo-agents/"
  version: "1.1"
  status: published
  author: "Eric"
in_this_file_we_have:
  - ABSOLUTE_RULES_FOR_CURSOR
  - DATABASE_SCHEMA
  - AGENT_REGISTRY_INSERTS
  - KERNEL_AGENTS
  - RESERVED_KERNEL_SLOTS
  - OS_LEVEL_AGENTS
  - FILE_SYSTEM_REWRITE
  - VERSIONING_RULES
  - WHAT_CURSOR_MUST_NOT_DO
  - WHAT_CURSOR_IS_ALLOWED_TO_DO
  - END_OF_DIRECTIONS
---

# **DIRECTIONS.md**  
### *Lupopedia OS — Agent Registry & File Rewrite Instructions for Cursor*  
**Version:** 1.1  
**Audience:** Cursor (AI IDE)  
**Purpose:** Provide strict, unambiguous instructions for implementing the Lupopedia Agent Registry and rewriting all agent directories under `lupo-agents/`.

---

# **0. ABSOLUTE RULES FOR CURSOR**

Cursor must follow these rules exactly:

### **0.1 No creativity**  
Cursor must not invent new agents, rename agents, merge agents, or reinterpret instructions.

### **0.2 No renumbering**  
`recommended_slot` values must remain exactly as specified.

### **0.3 No re-layering**  
`layer` values must remain exactly as specified.

### **0.4 No deletion of required agents**  
Kernel agents (0–22) must always exist.

### **0.5 Rewrite all existing agent folders**  
Every folder under:

```
lupo-agents/
```

must be **deleted and recreated** according to this document.

### **0.6 Registry is the source of truth**  
The registry defines identity.  
The slot map is only a recommended layout.

---

# **1. DATABASE SCHEMA (MYSQL)**

Cursor must create these tables **exactly**.

---

## **1.1 agent_registry**

```sql
CREATE TABLE agent_registry (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(64) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    layer ENUM('kernel','cognitive','system_ops','data','ux') NOT NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    is_kernel TINYINT(1) NOT NULL DEFAULT 0,
    recommended_slot INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## **1.2 agent_versions**

```sql
CREATE TABLE agent_versions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    agent_id INT UNSIGNED NOT NULL,
    version VARCHAR(32) NOT NULL,
    status ENUM('active','deprecated','experimental') NOT NULL,
    prompt_path VARCHAR(512) DEFAULT NULL,
    capabilities_json JSON DEFAULT NULL,
    properties_json JSON DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_agent_versions_agent
        FOREIGN KEY (agent_id)
        REFERENCES agent_registry(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

If JSON is unsupported, Cursor must fall back to `TEXT`.

---

# **2. AGENT REGISTRY INSERTS**

Cursor must insert **all 100 agents** using:

- `code`
- `name`
- `layer`
- `is_required`
- `is_kernel`
- `recommended_slot`

Identity is based on `code`, not slot.

---

# **3. KERNEL AGENTS (0–22)**  
Cursor must insert these exactly:

| slot | code |
|------|------|
| 0 | SYSTEM |
| 1 | CAPTAIN |
| 2 | WOLFIE |
| 3 | WOLFENA |
| 4 | THOTH |
| 5 | ARA |
| 6 | WOLFKEEPER |
| 7 | LILITH |
| 8 | AGAPE |
| 9 | ERIS |
| 10 | METHIS |
| 11 | THALIA |
| 12 | ROSE |
| 13 | WOLFSIGHT |
| 14 | WOLFNAV |
| 15 | WOLFFORGE |
| 16 | WOLFMIS |
| 17 | WOLFITH |
| 18 | ANUBIS |
| 19 | MAAT |
| 20 | VISHWAKARMA |
| 21 | CADUCEUS |
| 22 | CHRONOS |

All must have:

```
layer = 'kernel'
is_required = 1
is_kernel = 1
```

---

# **4. RESERVED KERNEL SLOTS (23–33)**  
Cursor must insert:

```
RESERVED_23
RESERVED_24
RESERVED_25
RESERVED_26
RESERVED_27
RESERVED_28
RESERVED_29
RESERVED_30
RESERVED_31
RESERVED_32
RESERVED_33
```

All must have:

```
layer = 'kernel'
is_required = 0
is_kernel = 1
```

---

# **5. OS‑LEVEL AGENTS (34–99)**  
Cursor must insert the remaining 66 agents exactly as defined below.

### **5.1 Cognitive Stack (34–53)**  
```
34 OBSERVER
35 ANALYST
36 SYNTHESIZER
37 PLANNER
38 STRATEGIST
39 EVALUATOR
40 CRITIC
41 CONTEXTOR
42 SUMMARIZER
43 EXPLAINER
44 TRANSLATOR
45 CLASSIFIER
46 ROUTER
47 PRIORITIZER
48 FORECASTER
49 MODELKEEPER
50 MEMORYWEAVER
51 LINKSMITH
52 PATTERNWOLF
53 WOLFCORE
```

### **5.2 System Operations (54–73)**  
```
54 LOGWATCH
55 HEALTHCHECK
56 LOADBALANCE
57 CACHEKEEPER
58 INDEXER
59 QUERYMASTER
60 MIGRATOR
61 COMPATIBILITY
62 SANDBOXER
63 VALIDATOR
64 SANITYCHECK
65 SECURITYWATCH
66 FIREWALL
67 RATEKEEPER
68 SESSIONWOLF
69 STATEKEEPER
70 CONFIGMASTER
71 TELEMETRY
72 WOLFSIGNAL
73 WOLFSYNC
```

### **5.3 Knowledge & Data (74–93)**  
```
74 SCHEMAKEEPER
75 DATAMAPPER
76 RELATIONWEAVER
77 HISTORYKEEPER
78 ARCHIVER
79 SNAPSHOTTER
80 RESTORER
81 MERGEWOLF
82 DIFFMASTER
83 METADATAKEEPER
84 TAGMASTER
85 SEARCHWOLF
86 KNOWLEDGEWEAVER
87 FACTCHECKER
88 CONSENSUSKEEPER
89 TRENDWATCH
90 LINKANALYZER
91 CLUSTERWOLF
92 TOPICMASTER
93 CONTEXTINDEX
```

### **5.4 User Experience (94–99)**  
```
94 UXWOLF
95 UINAV
96 STYLEKEEPER
97 ACCESSIBILITY
98 ONBOARDER
99 GUIDE
```

---

# **6. FILE SYSTEM REWRITE (MANDATORY)**

Cursor must **delete and recreate** all directories under:

```
lupo-agents/
```

For each agent in the registry, Cursor must create:

```
lupo-agents/<code>/
    agent.json
    system_prompt.txt
    capabilities.json
    properties.json
    versions/
        v1.0.0/
            prompt.txt
            capabilities.json
            properties.json
```

### **6.1 agent.json template**

Cursor must use:

```json
{
  "code": "THOTH",
  "name": "THOTH",
  "layer": "kernel",
  "is_required": true,
  "is_kernel": true,
  "recommended_slot": 4,
  "version": "1.0.0"
}
```

Cursor must fill in:

- code  
- name  
- layer  
- is_required  
- is_kernel  
- recommended_slot  

Cursor must NOT modify the schema.

---

# **7. VERSIONING RULES**

Cursor must:

- create `/versions/v1.0.0/` for every agent  
- place:
  - `prompt.txt`
  - `capabilities.json`
  - `properties.json`

Cursor must NOT:

- invent new versions  
- rename versions  
- delete versions  
- merge versions  

---

# **8. WHAT CURSOR MUST NOT DO**

Cursor must NOT:

- invent new agents  
- rename any agent  
- change any slot  
- change any layer  
- change any required/kernel flag  
- merge or delete agents  
- alter the registry schema  
- alter the file structure  

---

# **9. WHAT CURSOR IS ALLOWED TO DO**

Cursor MAY:

- generate SQL inserts  
- generate JSON files  
- generate folder structures  
- generate boilerplate prompts  
- generate boilerplate capabilities/properties  

Cursor MUST follow this document exactly.

---

# **10. END OF DIRECTIONS**

If you want, I can now generate:

- SQL insert scripts  
- JSON registry files  
- folder structure  
- agent.json files  
- version folders  
- boilerplate prompts  

Just tell me what you want Cursor to generate first.


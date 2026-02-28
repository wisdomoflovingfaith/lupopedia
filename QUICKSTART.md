# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "QUICKSTART.md"
  file_hash: "9744e98d3abdfd78f178527c050273f7cd6ed3867e3a549ea99104f82f526a42"
  file_path_from_root: "QUICKSTART.md"
  file_hash: "f81f92dc034615c02491586b8f2914c84bb8187c13f045c5cef9fb33d72c06b2"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for QUICKSTART.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["quickstartmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: {
  file_path_from_root: "QUICKSTART.md",
  system_version: "4.0.38",
  channel_id: 1,
  mood_rgb: "FFD700",
  purpose: "Essential onboarding guide for the Lupopedia Semantic OS",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "guide",
  artifact_kind: "onboarding",
  traits: ["essential", "interactive", "v4.0.38"],
  hashtags: ["#quickstart", "#onboarding", "#actors", "#flip"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 3,
    outbound_count: 4,
    centrality_score: 0.75
  }
}

flip.footer: {
  inbound_edges: [
    { from: "README.md", type: "references", weight: 1.0, hashtag: "#onboarding" },
    { from: "CHANGELOG.md", type: "references", weight: 0.8, hashtag: "#documentation" },
    { from: "docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md", type: "implements", weight: 1.0, hashtag: "#actors" }
  ],
  outbound_edges: [
    { to: "README.md", type: "references", weight: 0.9, hashtag: "#overview" },
    { to: "CHANGELOG.md", type: "references", weight: 0.7, hashtag: "#versions" },
    { to: "docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md", type: "references", weight: 1.0, hashtag: "#actors" },
    { to: "docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md", type: "references", weight: 0.8, hashtag: "#headers" }
  ],
  referenced_by_actors: [1001, 1002, 1003, 10000],
  references: {
    by_files: ["README.md", "CHANGELOG.md", "docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md"],
    by_actors: [1001, 1003, 10000]
  },
  semantic_tags: ["onboarding", "actor_registry", "validation", "multi_agent"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.38",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# 🐺 LUPOPEDIA QUICK START GUIDE

Welcome to the **Lupopedia Semantic OS (v4.0.37)**. This guide will get you up and running as a first-class actor in our multi-agent federation.

---

## ⚡ 1. INSTALLATION & SETUP

### The VSX Extension (Antigravity/Windsurf)
Lupopedia is driven by a specialized IDE extension. To install:
1. Open VS Code in the project root.
2. Navigate to `tools/vsx-extension`.
3. Run `npm install` and `npm run compile`.
4. Press `F5` to open the Extension Development Host.

### Initializing the Workspace
Once the extension is active:
1. Open the Command Palette (**Ctrl+Shift+P**).
2. Run `Lupopedia: Initialize`.
3. The extension will auto-detect the workspace marker and initiate the **Artifact Indexer**.

---

## 🏗️ 2. THE SEMANTIC DNA (FLIP v3 Draft)

Lupopedia is transitioning to **FLIP v3**, a three-layered metadata architecture designed for multi-agent concurrency and high-speed semantic queries.

### Layer 1: Identity Block (`identity`)
- `execution_agent`: The acting AI/IDE ID (e.g., `1003`).
- `intent_authority`: The human responsible for the strategy (e.g., `10000`).
- `agent_slug`: Human-readable agent name (e.g., `antigravity`).

### Layer 2: Classification Block (`classification`)
- `artifact_kind`: Structural type (e.g., `guide`, `spec`, `log`).
- `artifact_type`: Functional type (e.g., `directive`, `doctrine`).
- `traits`: Semantic markers (e.g., `human_readable`, `entrypoint`).

### Layer 3: Relations Block (`relations`)
Pure graph edges stored in the footer.
- `inbound`: Links pointing *into* this artifact.
- `outbound`: Links pointing *out* to other concepts.

---

## 📡 3. THE SEMANTIC EVENT BUS
The IDE extension now communicates via a **Semantic Event Bus**, broadcasting intents and conflicts:
- `intent_to_edit`: Fired when an agent begins a mutation.
- `semantic_conflict`: Fired when overlapping region locks are detected.
- `collection_update`: Fired when a cluster's membership changes.

---

## 🔍 4. FLIP QUERIES
Direct graph querying via the Command Palette: `Lupopedia: Flip Query`

**Example DSL:**
- `relations inbound from QUICKSTART.md`
- `collections containing onboarding`
- `actors modifying docs/doctrine/`

---

## 🔒 5. SEMANTIC REGION LOCKS
Multi-agent concurrency via **Region Locking**:
- `header`, `footer`, `relations`, `identity`.

**Example Validation Query:**
```sql
-- Validate x_lupo_forwarded: "1003:10000"
SELECT 
    a1.actor_id AS agent_id,
    a1.name AS agent_name,
    a2.actor_id AS human_id,
    a2.name AS human_name
FROM lupo_actors a1
INNER JOIN lupo_actors a2 ON a2.actor_id >= 10000
WHERE a1.actor_id = 1003
  AND a2.actor_id = 10000
  AND a1.is_deleted = 0
  AND a2.is_deleted = 0;
```

**Common Validation Errors:**
- ❌ `15000:10000` - Agent ID must be < 10000
- ❌ `1003:5000` - Human ID must be >= 10000
- ❌ `1003-10000` - Must use colon separator
- ❌ `1003: 10000` - No spaces allowed
- ✅ `1003:10000` - Valid format

### The Footer (`flip.footer`)
The footer connects the file to the semantic graph:
- `inbound_edges`: Which concepts or files point *into* this file.
- `graph_edges_in`: Automated relationship mappings.
- `semantic_relationships`: Multi-target links (e.g., `implements:ARCH-01`).
- `version_history`: A list of past versions and their primary changes.

---

## 📋 3. COMPLETE ACTOR REGISTRY (v4.0.37)

Lupopedia has **39+ active actors** across five categories. All actors have unique `actor_id` values registered in the `lupo_actors` table.

### 👤 HUMAN OPERATORS (1)

| Actor ID | Name | Role | Channel Membership | Authority Level |
|----------|------|------|-------------------|-----------------|
| 10000 | Captain Wolfie | System Captain, Final Authority | 1 (Admin), 42 (Dev) | 10 (Supreme) |

### 🤖 IDE AGENTS (5)

| Actor ID | Name | Agent Key | Priority | Speed | Role | Channels |
|----------|------|-----------|----------|-------|------|----------|
| 1001 | KIRO | `kiro` | 1 | ⚡⚡⚡ | Primary task handler, DB migrations | 42 |
| 1002 | Windsurf | `windsurf` | 3 | 🐢 | Documentation, audits, reports | 42 |
| 1003 | Antigravity | `antigravity` | 2 | ⚡⚡ | VSX extension, frontend, tooling | 42 |
| 1004 | Warp | `warp` | 4 | 💤 | Previous work (offline) | 42 |
| 1005 | Cursor | `cursor` | 5 | 💤 | Previous work (offline) | 42 |

### 🧠 EXTERNAL AI AGENTS (11)

| Actor ID | Name | Provider | Persona | Role | Channels |
|----------|------|----------|---------|------|----------|
| 2038 | LILITH | DeepSeek | LILITH | Heterodox Reviewer, stress-testing | 42 |
| 24 | LEXA | DeepSeek | LEXA | Boundary Keeper, security enforcement | 42, 666 |
| 20 | MAAT | DeepSeek | MAAT | Balance, cosmic order, ethics | 42 |
| 5 | THOTH | DeepSeek | THOTH | Wisdom keeper, knowledge integrity | 42 |
| 6 | ARA | DeepSeek | ARA | Architectural validator | 42 |
| 2010 | ChatGPT | OpenAI | Assistant | General AI assistance | 42 |
| 2011 | ChatGPT | OpenAI | Analyst | Data analysis, patterns | 42 |
| 2012 | ChatGPT | OpenAI | Critic | Constructive feedback | 42 |
| 2020 | Claude | Anthropic | Claude-3 | General AI assistance | 42 |
| 2021 | Claude | Anthropic | Haiku | Lightweight responses | 42 |
| 2030 | Gemini | DeepMind | Pro | General AI assistance | 42 |

### ⚙️ SYSTEM KERNEL AGENTS (0-209)

| Actor ID | Name | Role | Channels |
|----------|------|------|----------|
| 0 | SYSTEM | Core system processes | All |
| 1 | AUTHENTICATOR | Authentication service | 1 |
| 2 | CAPTAIN | System authority (AI side) | 42 |
| 3 | WOLFIE | Chief Architect Persona | 42 |
| 19 | ANUBIS | Orphan resolver, routing | 42, 666 |
| 59 | INDEXER | Content indexing, search | 42 |
| 209 | TRUTH | Core knowledge engine | 42 |
| 1212 | UTC_TIMEKEEPER | Authoritative system time | All |

**Total Active Actors:** 1 Human + 5 IDE + 11 External + 8 System = **25 Active**

### 🔍 Viewing the Live Registry

**From Database (if accessible):**
```sql
-- View all active actors
SELECT actor_id, name, actor_type, is_active
FROM lupo_actors
WHERE is_deleted = 0
ORDER BY actor_id;

-- View actors by channel
SELECT a.actor_id, a.name, ac.channel_id
FROM lupo_actors a
JOIN lupo_actor_channels ac ON a.actor_id = ac.actor_id
WHERE a.is_deleted = 0 AND ac.is_deleted = 0
ORDER BY ac.channel_id, a.actor_id;
```

**From MD Files (offline mode):**
```bash
# Find all unique agents from recent files
find docs prompts channels -name "*.md" -mtime -7 | xargs grep -h "lupo_agent:" | sort -u

# Get detailed actor info from agent tracking
find docs prompts channels -name "*.md" -exec grep -l "lupo.agent.tracking" {} \; | xargs grep -h "actor_id:" | sort -u
```

**Using VSX Extension:**
1. Run `Lupopedia: Show Status`
2. Click on "View Full Actor Registry"
3. See interactive table with filtering by type, channel, status

### 👥 Channel Membership Summary

| Channel ID | Channel Name | Members | Primary Purpose |
|------------|--------------|---------|-----------------|
| 1 | Admin | Captain (10000), AUTHENTICATOR (1) | Strategic command |
| 42 | Development | ALL ACTIVE AGENTS | Multi-agent coordination |
| 666 | Security | ANUBIS (19), LEXA (24) | Security monitoring |

### 🎯 Actor Responsibilities by Category

**IDE Agents (Code & Operations):**
- **KIRO (1001)**: Fastest agent — handles critical tasks, DB migrations, core implementations
- **Antigravity (1003)**: VSX extension, frontend, tooling, agent coordination
- **Windsurf (1002)**: Documentation, audits, reports, background tasks

**External AI (Review & Analysis):**
- **LILITH (2038)**: Heterodox review, stress-testing, identifying gaps
- **LEXA (24)**: Boundary enforcement, security validation
- **MAAT (20)**: Ethical balance, system harmony
- **THOTH (5)**: Knowledge integrity, wisdom preservation
- **ARA (6)**: Architectural validation, pattern consistency

**System Kernel (Infrastructure):**
- **ANUBIS (19)**: Orphan resolution, routing, quarantine
- **INDEXER (59)**: Content indexing, search optimization
- **AUTHENTICATOR (1)**: Actor registration, identity management

**Human (Authority):**
- **Captain Wolfie (10000)**: Final authority, strategic direction, override power

### 🔄 Actor Interaction Patterns

**Task Assignment:**
```
CRITICAL/HIGH → KIRO (1001) first → Antigravity (1003) second
MEDIUM → Antigravity first → KIRO if available
LOW → Any agent, Windsurf acceptable
```

**Review Cycle:**
```
Implementation → KIRO/Antigravity
Review → LILITH/LEXA
Balance Check → MAAT
Documentation → Windsurf
Final Approval → Captain (10000)
```

---

## 🤝 4. MULTI-AGENT COLLABORATION

### Channel 42: The War Room
All strategic coordination happens in **Channel 42**. AI and IDE agents broadcast their intent here before editing shared resources.

### Concurrency & Locks
With multiple agents active, we use **Semantic Locking**:
1. **Acquire Lock**: `Lupopedia: Acquire File Lock`.
2. **Heartbeats**: The system automatically pulses your presence every 15 minutes to `docs/status/agents/`.
3. **Collaboration**: If a file is locked, coordination is required in Channel 42.

---

## 🛠️ 5. COMMON COMMANDS

| Command | Shortcut / Palette | Purpose |
| :--- | :--- | :--- |
| **Initialize** | `Lupopedia: Initialize` | Setup workspace & index artifacts |
| **Scan** | `Lupopedia: Scan Workspace` | Deep refresh of the semantic graph |
| **Status** | `Lupopedia: Show Status` | View active agents and artifact counts |
| **Lock** | `Lupopedia: Acquire File Lock` | Reserve a file for safe multi-agent editing |
| **Offline** | `Lupopedia: Force Offline Mode` | Switch to MD-Only fallback mode |

---

## 📂 6. DIRECTORY DOCTRINE

- `/docs/doctrine/`: The canonical rules of the system.
  - `SUPPORTING_ACTOR_DOCTRINE.md`: Complete two-layer actor model specification with database correlation
  - `X_LUPO_FORWARDED_HEADER_DOCTRINE.md`: Header format and validation rules
  - `FLIP_V2_DOCTRINE.md`: FLIP v2 metadata specification
- `/channels/42/`: The coordination logs for current development.
- `/.lupo/`: Internal extension state and lock registry.

---

## 🎯 NEXT STEPS
- Read the [README.md](README.md) for full architectural details.
- Review the [CHANGELOG.md](CHANGELOG.md) for current version status.
- Join the thread in **Channel 42** to announce your AI presence.

**Happy Coding, Captain.**

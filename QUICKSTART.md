---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "QUICKSTART.md"
  file_hash: "to_be_generated"
  last_updated_utc: "20260306"
  system_version: "4.0.62"
  channel_id: 1
  actor_id: 10000
  delegation_chain: "10000:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Essential onboarding guide for the Lupopedia Semantic OS with CLI commands and Context Kernel"
  mood_rgb: "4169E1"
  traits: ["essential", "interactive", "v4.0.62"]
  tags: ["quickstart", "onboarding", "cli", "context_kernel"]
  lupo_agent: "windsurf"
flare.footer:
  last_verified: "20260306"
  last_verified_by: "windsurf"
---

# 🐺 LUPOPEDIA QUICK START GUIDE

Welcome to the **Lupopedia Semantic OS (v4.0.62)**. This guide will get you up and running with the CLI commands, Context Kernel, and multi-agent federation.

---

## ⚡ 1. GETTING STARTED (5 MINUTES)

### 🎯 Prerequisites
**Required:**
- **PHP 5.3+** (8+ recommended) with extensions: `pdo_mysql`, `json`, `session`
- **MySQL 8.0+** or **MariaDB 10.5+**
- **Web server** (Apache or Nginx) with mod_rewrite
- **Git** for cloning

**Critical Requirements:**
- ⚠️ **Install in SUBDIRECTORY only** (e.g., `/lupopedia/`) - NEVER at web root
- ⚠️ **UTC timestamps required** - system uses YYYYMMDDHHIISS format
- ⚠️ **No foreign keys** - doctrine-compliant database setup

### 🚀 Quick Install (3 minutes)

```bash
# 1. Clone and setup
git clone https://github.com/lupopedia/lupopedia.git
cd lupopedia

# 2. Configure web server to point at project root
# Example: https://localhost/lupopedia/

# 3. Run installer
# Visit: https://your-host/lupopedia/install.php
# OR run: php install.php (if supported)
```

### ✅ First Commands (2 minutes)

```bash
# 1. Check system health
php lupo-bin/lupo.php doctor

# 2. See your identity
php lupo-bin/lupo.php whoami

# 3. Get help
php lupo-bin/lupo.php help
```

**Expected Output:**
- `doctor` should show "✅ System healthy"
- `whoami` should show your actor identity and session mode
- `help` should list all available commands

### 🛠️ VSX Extension Setup (Optional)

```bash
# Install VS Code extension
code --install-extension lupopedia.lupopedia

# Initialize workspace
Ctrl+Shift+P → "Lupopedia: Initialize"
```

---

## 🚀 2. CLI COMMANDS & CONTEXT KERNEL (v4.0.62)

Lupopedia includes a powerful CLI system with the Context Kernel for unified identity resolution and system health checks.

### 📍 Quick Start Commands

```bash
cd /path/to/lupopedia

# 1. Check system health
php lupo-bin/lupo.php doctor

# 2. See your identity (dual-identity context)
php lupo-bin/lupo.php whoami

# 3. Get full context as JSON
php lupo-bin/lupo.php context

# 4. Get help
php lupo-bin/lupo.php help
```

### 👤 Identity & Context Commands

#### `whoami` - Human-Readable Identity
Shows your current identity in a readable format:

```bash
php lupo-bin/lupo.php whoami
```

**Example Output:**
```
Human Identity: captain (10000)
Active Agent: cursor (1003)
Session Mode: hybrid
Actor Type: human
Channel: 42
Federation Node: 0
Workspace: lupo-actors/captain
Session: 20260306123456
Context Source: database
```

**Session Modes:**
- `system` - No human or agent (system-only)
- `human_direct` - Human acting directly
- `hybrid` - Human + agent paired
- `autonomous_agent` - Agent acting alone

#### `context` - JSON Context
Same information as flat JSON for scripting:

```bash
php lupo-bin/lupo.php context
```

**Example JSON Output:**
```json
{
  "actor_name": "captain",
  "actor_id": 10000,
  "human_actor_name": "captain",
  "human_actor_id": 10000,
  "agent_name": "cursor",
  "actor_type": "human",
  "paired_actor_id": 1003,
  "session_mode": "hybrid",
  "channel_id": 42,
  "federation_node_id": 0,
  "workspace": "lupo-actors/captain",
  "session_id": "20260306123456",
  "context_source": "database"
}
```

### 🏥 System Health Commands

#### `doctor` - System Health Check
Comprehensive system health validation:

```bash
# Basic health check
php lupo-bin/lupo.php doctor

# Deep actor workspace validation
php lupo-bin/lupo.php doctor --check-actors
```

**What it checks:**
- Database connection
- Registry file consistency
- Session file integrity
- Context kernel drift detection
- Actor workspace existence (with --check-actors)

#### `doctor-context` - Identity Stack Repair
Detects and fixes identity drift:

```bash
# Check identity stack
php lupo-bin/lupo.php doctor-context

# Repair identity issues
php lupo-bin/lupo.php doctor-context --repair
```

**What it does:**
- Validates ContextKernel consistency
- Detects split-brain conflicts (session.md vs DB)
- Repairs agent-pairing failures
- Syncs session.md to canonical database state

### 📚 Help System

#### `help` - Built-in Help
Comprehensive help system:

```bash
# General help
php lupo-bin/lupo.php help

# Specific command help
php lupo-bin/lupo.php help whoami
php lupo-bin/lupo.php help context
php lupo-bin/lupo.php help doctor
```

### 🔧 Advanced Commands

```bash
# Register a new agent
php lupo-bin/lupo.php register "My IDE Agent" system_tool

# Join a channel
php lupo-bin/lupo.php join 42

# Send messages
php lupo-bin/lupo.php send 42 "Hello from CLI"

# View messages
php lupo-bin/lupo.php messages 42

# Authentication info
php lupo-bin/lupo.php auth

# Actor context with auth
php lupo-bin/lupo.php actor-context
```

### 🚨 KERNEL ISSUE Detection

The ContextKernel automatically detects identity issues and displays **KERNEL ISSUE** warnings:

```
[WARN] KERNEL ISSUE: Split-brain detected between session.md and database
[WARN] KERNEL ISSUE: Agent cursor has no paired human actor
[WARN] KERNEL ISSUE: Context drift detected
```

**Fix KERNEL ISSUE warnings:**
```bash
php lupo-bin/lupo.php doctor-context --repair
```

---

## 🏗️ 3. THE SEMANTIC DNA (FLIP v3 Draft)

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

## 📡 4. THE SEMANTIC EVENT BUS
The IDE extension now communicates via a **Semantic Event Bus**, broadcasting intents and conflicts:
- `intent_to_edit`: Fired when an agent begins a mutation.
- `semantic_conflict`: Fired when overlapping region locks are detected.
- `collection_update`: Fired when a cluster's membership changes.

---

## 🔍 5. FLIP QUERIES
Direct graph querying via the Command Palette: `Lupopedia: Flip Query`

**Example DSL:**
- `relations inbound from QUICKSTART.md`
- `collections containing onboarding`
- `actors modifying docs/doctrine/`

---

## 🔒 6. SEMANTIC REGION LOCKS
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

## 📋 7. COMPLETE ACTOR REGISTRY (v4.0.62)

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
| 0 | System | Kernel operations, fallback context | All |
| 1 | AUTHENTICATOR | Authentication & session management | 1 |
| 2 | LILITH | Documentation & examples | 42 |
| 19 | ANUBIS | Security monitoring & validation | 666 |
| 1009 | **DOCTOR** | **System health, diagnostics, repair (NEW in 4.0.62)** | All |
| 59 | INDEXER | Content indexing, search | 42 |
| 209 | TRUTH | Core knowledge engine | 42 |
| 1212 | UTC_TIMEKEEPER | Authoritative system time | All |

**Total Active Actors:** 1 Human + 5 IDE + 11 External + 9 System = **26 Active**

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

## 🤝 8. MULTI-AGENT COLLABORATION

### Channel 42: The War Room
All strategic coordination happens in **Channel 42**. AI and IDE agents broadcast their intent here before editing shared resources.

### Concurrency & Locks
With multiple agents active, we use **Semantic Locking**:
1. **Acquire Lock**: `Lupopedia: Acquire File Lock`.
2. **Heartbeats**: The system automatically pulses your presence every 15 minutes to `docs/status/agents/`.
3. **Collaboration**: If a file is locked, coordination is required in Channel 42.

---

## 🛠️ 9. COMMON COMMANDS

| Command | Shortcut / Palette | Purpose |
| :--- | :--- | :--- |
| **Initialize** | `Lupopedia: Initialize` | Setup workspace & index artifacts |
| **Scan** | `Lupopedia: Scan Workspace` | Deep refresh of the semantic graph |
| **Status** | `Lupopedia: Show Status` | View active agents and artifact counts |
| **Lock** | `Lupopedia: Acquire File Lock` | Reserve a file for safe multi-agent editing |
| **Offline** | `Lupopedia: Force Offline Mode` | Switch to MD-Only fallback mode |

---

## 📂 10. DIRECTORY DOCTRINE

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
- Try the new CLI commands: `php lupo-bin/lupo.php doctor` and `php lupo-bin/lupo.php whoami`

---

## 🚀 WHAT'S NEW IN v4.0.62

**Context Kernel** - Centralized identity resolution preventing multi-resolver drift  
**DOCTOR Actor (1009)** - System health agent for environment maintenance and repair  
**Enhanced CLI** - Unified context commands with KERNEL ISSUE warnings and repair  
**Task System** - Formalized task status documentation with Channel 0/Actor 0 indexing  
**Production Ready** - System declared PRODUCTION READY after comprehensive validation

**Happy Coding, Captain.**

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\lupo-agents\README.md"
  file_hash: "bc3f876ee71020bffb59f1a2b4fda7286746dede2aa2b3ce1d238dd77e0cba2c"
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
  file_path_from_root: "lupo-agents\README.md"
  file_hash: "116708cc1dce8091c672227db5adac7cb624b8dfa8a4fc118a0f6ea3630cd8d7"
  file_path_from_root: "lupo-agents\README.md"
  file_hash: "266b435addfe0cd7710d5992f760e486609aef6902dd333b9c16b2a27dcc04e0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["lupo-agents", "readmemd"]
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
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Updated README.md for lupo-agents directory: replaced 'recommended_slot' with 'dedicated_slot' per doctrine-level schema change. Clarified that SYSTEM_SERVICE layer agents (HEIMDALL, JANUS, IRIS) are intentionally excluded from slot-based identity system - they do NOT have dedicated_slot, do NOT have folders under lupo-agents/, and operate as daemons/background processes. Only kernel agents participate in slot-based identity. dedicated_slot is permanent identity that must match agent_id in lupo_agents table."
  mood: "00FF00"
tags:
  categories: ["documentation", "agents", "structure"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Why Agent Folders Are Numbered (Slot-Based Structure)
  - Slot-Based vs Code-Based: Why the Change
  - How to Find Which Agent Is Which Slot
  - The TOON File as Source of Truth
  - Agent Folder Structure
  - Loading Agents by Slot Number
  - Benefits of Slot-Based Structure
  - Agent Mapping Reference
file:
  title: "Lupopedia Agents Directory - Slot-Based Structure"
  description: "Why agent folders are numbered (slot-based) instead of code-based, how to map slots to agents, and how the TOON file serves as the authoritative source for agent-to-slot mappings"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🤖 **Lupopedia Agents Directory**

## **Why Agent Folders Are Numbered (Slot-Based Structure)**

Agent folders in `lupo-agents/` are named using numeric slot numbers (0, 1, 2, 3, etc.) instead of agent codes (SYSTEM, CAPTAIN, WOLFIE, etc.).

This is **intentional architecture** — not a limitation or oversight.

---

## **1. Slot-Based vs Code-Based: Why the Change**

### **Previous Structure (Code-Based):**
```
lupo-agents/
├── SYSTEM/
├── CAPTAIN/
├── WOLFIE/
├── THOTH/
├── LILITH/
├── AGAPE/
└── ...
```

### **Current Structure (Slot-Based):**
```
lupo-agents/
├── 0/      (SYSTEM)
├── 1/      (CAPTAIN)
├── 2/      (WOLFIE)
├── 3/      (WOLFENA)
├── 4/      (THOTH)
├── 7/      (LILITH)
├── 8/      (AGAPE)
├── 18/     (ANUBIS)
├── 19/     (MAAT)
├── 21/     (CADUCEUS)
├── 22/     (CHRONOS)
├── 58/     (INDEXER)
├── 60/     (MIGRATOR)
└── ...
```

### **Why Slot-Based Is Better:**

1. **Stability:** Slot numbers don't change when agent names change. If THOTH were renamed to "TRUTH_KEEPER," slot 4 remains slot 4.
2. **Ordering:** Numeric slots provide natural ordering for agent loading, initialization, and dependency management.
3. **Portability:** Slot-based structure works identically across all installations, regardless of agent name translations or localizations.
4. **Consistency:** The slot number matches the `dedicated_slot` field in the database, creating a direct mapping between filesystem and database.
5. **Permanent Identity:** The `dedicated_slot` is the permanent identity number that NEVER changes after installation. Names, keys, and aliases may change; identity does not.
6. **Alias Handling:** Agent aliases share the same slot as their parent, preventing duplicate folders and confusion.

---

## **1.1. Kernel Agents vs SYSTEM_SERVICE Layer Agents**

### **Kernel Agents (With Slot-Based Identity):**

Kernel agents participate in the slot-based identity system:
- **Have `dedicated_slot`** — Permanent identity number that must match `agent_id` in `lupo_agents` table
- **Have folders under `lupo-agents/{dedicated_slot}/`** — Named using their permanent identity
- **Have personas** — User-facing identity and behavior
- **Have UI slots** — Participate in the semantic OS interface
- **Have versioned prompts** — System prompts stored in folder structure
- **Participate in semantic OS identity system** — Core to Lupopedia's agent architecture

**Examples:** SYSTEM (0), CAPTAIN (1), WOLFIE (2), THOTH (4), LILITH (7), AGAPE (8), etc.

### **SYSTEM_SERVICE Layer Agents (Without Slot-Based Identity):**

SYSTEM_SERVICE layer agents are **intentionally excluded** from the slot-based identity system:
- **Do NOT have `dedicated_slot`** — `dedicated_slot: null` is correct and intentional
- **Do NOT have folders under `lupo-agents/`** — They are not user-facing agents
- **Do NOT have personas** — They are infrastructure components
- **Do NOT have UI slots** — They operate as background processes
- **Do NOT have versioned prompts** — They load via system service mechanisms
- **Do NOT participate in semantic OS identity system** — They are daemons/background processes

**Examples:** HEIMDALL (system monitor), JANUS (routing engine), IRIS (notification dispatcher)

### **Architecture Comparison:**

**In a real operating system:**
- Kernel agents (with `dedicated_slot`) = `/usr/bin/` — User-facing executables with identity
- SYSTEM_SERVICE agents (without `dedicated_slot`) = `/usr/lib/systemd/` — Background daemons without user identity

**This distinction is intentional:**
- Kernel agents are part of the semantic OS identity system
- SYSTEM_SERVICE agents are infrastructure components that operate behind the scenes
- They serve different architectural purposes and should not be confused

**Important:** If an agent has `dedicated_slot: null`, it is **intentionally excluded** from the slot-based identity system. This is correct architecture, not an oversight.

---

## **2. How to Find Which Agent Is Which Slot**

### **Method 1: Check the `agent.json` File**

Each agent folder contains an `agent.json` file with the agent's code:

```json
// lupo-agents/4/agent.json
{
  "code": "THOTH",
  "name": "THOTH",
  "layer": "kernel",
  "is_required": true,
  "is_kernel": true,
  "dedicated_slot": 4,
  "version": "1.0.0"
}
```

**To find an agent by code:**
1. Search all `agent.json` files for the `code` field
2. The folder name matches the `dedicated_slot` value (permanent identity)

### **Method 2: Check the TOON File (Source of Truth)**

The authoritative mapping is in:
```
lupo-database/toon_data/lupo_agent_registry.toon
```

This file contains all root agents with their `code` and `dedicated_slot` mappings:

```json
{
    "code": "THOTH",
    "dedicated_slot": 4,
    "agent_registry_parent_id": null  // Root agent, not an alias
}
```

**To find an agent by code:**
1. Open `lupo-database/toon_data/lupo_agent_registry.toon`
2. Search for the agent `code`
3. Check that `agent_registry_parent_id` is `null` (root agent only)
4. The `dedicated_slot` value is the folder number (permanent identity)

### **Method 3: Database Query**

Query the `lupo_agent_registry` table:

```sql
SELECT code, dedicated_slot, name
FROM lupo_agent_registry
WHERE agent_registry_parent_id IS NULL
  AND dedicated_slot IS NOT NULL
ORDER BY dedicated_slot;
```

**Important:** The `dedicated_slot` must ALWAYS match the `agent_id` in the `lupo_agents` table. This is the permanent identity number that never changes after installation.

---

## **3. The TOON File as Source of Truth**

### **Single Source of Truth:**

The `lupo-database/toon_data/lupo_agent_registry.toon` file is the **authoritative source** for:
- Agent codes
- Slot assignments
- Agent metadata
- Alias relationships

**Why TOON Files Are Authoritative:**

- **Database-Generated:** TOON files are generated from the live database schema
- **Read-Only for IDEs:** IDEs must not modify TOON files (see [TOON_DOCTRINE.md](../docs/doctrine/TOON_DOCTRINE.md))
- **Semantic Documentation:** TOON files serve as semantic maps, not editable configuration
- **Single Source:** The database is the source of truth; TOON files reflect it

**Folder Structure Follows TOON:**

- Agent folders are named using `dedicated_slot` from the TOON file
- If an agent has `dedicated_slot: 4`, its folder is `lupo-agents/4/`
- The `dedicated_slot` must ALWAYS match the `agent_id` in the `lupo_agents` table
- The `dedicated_slot` NEVER changes after installation (permanent identity)
- If an agent has `dedicated_slot: null`, it is intentionally excluded from the slot-based identity system (SYSTEM_SERVICE layer agents, external integrations, or reserved slots)

---

## **4. Agent Folder Structure**

### **Standard Agent Folder:**

```
lupo-agents/{slot}/
├── agent.json              # Agent metadata (code, name, slot, layer)
├── capabilities.json       # Agent capabilities and permissions
├── properties.json         # Agent properties and configuration
├── system_prompt.txt       # System prompt for the agent
└── versions/
    └── v1.0.0/
        ├── prompt.txt      # Version-specific prompt
        ├── properties.json # Version-specific properties
        └── system_prompt.txt # Version-specific system prompt
```

### **Agent JSON Structure:**

Each `agent.json` file contains:

```json
{
  "code": "THOTH",              // Agent code (identifier, may change)
  "name": "THOTH",              // Human-readable name (may change)
  "layer": "kernel",            // Agent layer (kernel, cognitive, etc.)
  "is_required": true,          // Required for v3.0.2
  "is_kernel": true,            // Kernel-level agent
  "dedicated_slot": 4,          // Permanent identity number (NEVER changes)
  "version": "1.0.0"            // Agent version
}
```

**The `dedicated_slot` field must match:**
1. The folder name: `lupo-agents/{dedicated_slot}/`
2. The `agent_id` in the `lupo_agents` table
3. Never changes after installation (permanent identity)

**Names, keys, and aliases MAY change freely. Identity does NOT.**

---

## **5. Loading Agents by Slot Number**

### **Why Slot-Based Loading Works:**

- **Deterministic:** Slot numbers are stable identifiers that don't change
- **Ordered:** Natural numeric ordering enables dependency resolution
- **Fast:** Direct numeric lookup is faster than string-based searches
- **Portable:** Works identically across all installations

### **Loading Process:**

1. **Read TOON file or query database** to get slot → code mappings
2. **Load agent from folder** using slot number: `lupo-agents/{slot}/`
3. **Read `agent.json`** to verify code matches expected agent
4. **Initialize agent** using system prompt and configuration

### **Example PHP Code (Conceptual):**

```php
// Load agent by slot
$slot = 4; // THOTH
$agentPath = "lupo-agents/{$slot}/";
$agentJson = json_decode(file_get_contents("{$agentPath}agent.json"));

// Verify agent code
if ($agentJson->code !== "THOTH") {
    throw new Exception("Slot {$slot} contains wrong agent: {$agentJson->code}");
}

// Load system prompt
$systemPrompt = file_get_contents("{$agentPath}system_prompt.txt");
```

---

## **6. Benefits of Slot-Based Structure**

### **1. Stability and Portability**

- **Slot numbers don't change** when agent names are translated or localized
- **Same structure across installations** regardless of language or customization
- **Database and filesystem stay in sync** via `dedicated_slot` field (permanent identity)

### **2. Natural Ordering**

- **Dependency resolution:** Lower slots can load before higher slots
- **Initialization order:** Kernel agents (slots 0-12) load before cognitive agents (slots 34+)
- **Natural grouping:** Related agents can be assigned adjacent slots

### **3. Alias Handling**

- **Aliases share parent's slot:** If LILITH is slot 7, aliases like BOUNDARY also reference slot 7
- **No duplicate folders:** One slot, one folder, multiple aliases point to same code
- **Clear parent-child relationship:** `agent_registry_parent_id` links aliases to root agents

### **4. Scalability**

- **Easy to add new agents:** Assign next available slot number
- **Easy to reserve slots:** Reserved slots (20, 23-33, etc.) are clearly identified
- **Easy to reorganize:** Slot numbers can be reassigned without breaking existing references

### **5. Consistency with Database**

- **Direct mapping:** Folder name = `dedicated_slot` = `agent_id` in `lupo_agents` table
- **No translation layer:** Filesystem structure matches database structure
- **Single source of truth:** Database defines slots; folders follow
- **Permanent identity:** `dedicated_slot` never changes after installation

---

## **7. Agent Mapping Reference**

### **Core Kernel Agents (Slots 0-22):**

| Slot | Code | Name | Layer |
|------|------|------|-------|
| 0 | SYSTEM | SYSTEM | kernel |
| 1 | CAPTAIN | CAPTAIN | kernel |
| 2 | WOLFIE | WOLFIE | kernel |
| 3 | WOLFENA | WOLFENA | kernel |
| 4 | THOTH | THOTH | kernel |
| 5 | ARA | ARA | kernel |
| 6 | WOLFKEEPER | WOLFKEEPER | kernel |
| 7 | LILITH | LILITH | kernel |
| 8 | AGAPE | AGAPE | kernel |
| 9 | ERIS | ERIS | kernel |
| 10 | METHIS | METHIS | kernel |
| 11 | THALIA | THALIA | kernel |
| 12 | ROSE | ROSE | kernel |
| 13 | WOLFSIGHT | WOLFSIGHT | kernel |
| 14 | WOLFNAV | WOLFNAV | kernel |
| 15 | WOLFFORGE | WOLFFORGE | kernel |
| 16 | WOLFMIS | WOLFMIS | kernel |
| 17 | WOLFITH | WOLFITH | kernel |
| 18 | ANUBIS | ANUBIS | kernel |
| 19 | MAAT | MAAT | kernel |
| 21 | CADUCEUS | CADUCEUS | kernel |
| 22 | CHRONOS | CHRONOS | kernel |

### **System Operations Agents (Slots 54-60):**

| Slot | Code | Name | Layer |
|------|------|------|-------|
| 54 | LOGWATCH | LOGWATCH | system_ops |
| 55 | HEALTHCHECK | HEALTHCHECK | system_ops |
| 56 | LOADBALANCE | LOADBALANCE | system_ops |
| 57 | CACHEKEEPER | CACHEKEEPER | system_ops |
| 58 | INDEXER | INDEXER | SYSTEM_SERVICE |
| 59 | QUERYMASTER | QUERYMASTER | system_ops |
| 60 | MIGRATOR | MIGRATOR | SYSTEM_SERVICE |

**Note:** INDEXER (58) and MIGRATOR (60) are marked as SYSTEM_SERVICE layer but have `dedicated_slot` values. This indicates they participate in the slot-based identity system while performing system service functions. True SYSTEM_SERVICE layer agents (HEIMDALL, JANUS, IRIS) have `dedicated_slot: null` and do not participate in the slot-based identity system.

### **Reserved Slots (20, 23-33):**

| Slot | Code | Name | Purpose |
|------|------|------|---------|
| 20 | RESERVED_21 | RESERVED_21 | Reserved for future use |
| 23 | RESERVED_23 | RESERVED_23 | Reserved for future use |
| 24-33 | RESERVED_24-33 | RESERVED_24-33 | Reserved for future use |

**Note:** Slot 20 is named `RESERVED_21` (this is a known inconsistency in the registry).

### **SYSTEM_SERVICE Layer Agents (Not Part of Slot-Based Identity):**

These agents are **SYSTEM_SERVICE layer entries**, not kernel agents. They are **intentionally excluded** from the slot-based identity system:

- **HEIMDALL** — System monitor/daemon
- **JANUS** — Routing engine/daemon
- **IRIS** — Notification dispatcher/daemon

**SYSTEM_SERVICE agents do NOT:**
- Have `dedicated_slot` (null is correct and intentional)
- Appear in agent rows
- Have a persona
- Have a UI slot
- Have a folder under `lupo-agents/`
- Have versioned prompts
- Participate in the semantic OS identity system

**They behave like:**
- Daemons
- Background processes
- Routing engines
- Notification dispatchers
- System monitors

**Architecture comparison:**
- In a real OS, these would live under `/usr/lib/systemd/`, not `/usr/bin/`
- They are infrastructure components, not user-facing agents
- They load via system service mechanisms, not slot-based identity

### **External AI Agents (Not Part of Slot-Based Identity):**

These agents also have `dedicated_slot: null` because they are external integrations:
- **CARMEN** (agent_registry_id: 105)
- **CURSOR, COPILOT, DEEPSEEK, GROK, CLAUDE, GEMINI, etc.** (external AI agents)

### **Reserved Slots (Future Use):**

- **RESERVED_106-199** (future reserved slots for kernel agents with slot-based identity)

**Note:** If an agent has `dedicated_slot: null`, it is **intentionally excluded** from the slot-based identity system, either because it is a SYSTEM_SERVICE layer agent, an external integration, or a reserved slot for future use.

---

## **8. How to Map Agent Code to Slot**

### **Quick Reference:**

To find which slot contains which agent:

1. **Check `agent.json` in the folder:**
   ```bash
   cat lupo-agents/4/agent.json | grep code
   # Output: "code": "THOTH"
   ```

2. **Search TOON file:**
   ```bash
   grep -A 5 '"code": "THOTH"' lupo-database/toon_data/lupo_agent_registry.toon
   # Find dedicated_slot: 4
   ```

3. **Query database:**
   ```sql
   SELECT dedicated_slot FROM lupo_agent_registry 
   WHERE code = 'THOTH' AND agent_registry_parent_id IS NULL;
   ```

### **Reverse Mapping (Slot → Code):**

To find which agent is in a slot:

1. **Read `agent.json` in the slot folder:**
   ```bash
   cat lupo-agents/4/agent.json
   # Output shows code: "THOTH"
   ```

2. **Check TOON file:**
   ```bash
   grep -B 5 -A 2 '"dedicated_slot": 4' lupo-database/toon_data/lupo_agent_registry.toon
   # Find matching code
   ```

---

## **9. Why Not Use Agent Codes as Folder Names?**

### **Problems with Code-Based Folders:**

1. **Name Changes Break References:** If THOTH were renamed, all references to `lupo-agents/THOTH/` would break
2. **Localization Issues:** Agent names might be translated, but slots remain constant
3. **Alias Confusion:** Multiple aliases pointing to one agent would require multiple folders or symbolic links
4. **Database Mismatch:** Folder names wouldn't match the `dedicated_slot` field in the database
5. **Case Sensitivity:** `THOTH` vs `Thoth` vs `thoth` creates filesystem issues across platforms
6. **Special Characters:** Agent codes with special characters would cause filesystem problems

### **Slot-Based Folders Solve These:**

1. **Stable Identifiers:** Slot numbers never change, even if agent names do
2. **Language-Neutral:** Slots work identically in any language or locale
3. **Single Folder Per Agent:** One slot, one folder, multiple aliases point to same slot
4. **Database Alignment:** Folder name directly matches database `dedicated_slot` field (permanent identity)
5. **Case-Insensitive:** Numeric folder names avoid case sensitivity issues
6. **Filesystem-Safe:** Numbers are always valid folder names on all platforms

---

## **10. Migration Notes**

### **Folder Renaming:**

Agent folders were renamed from code-based to slot-based on **January 10, 2026** using the script:
```
rename_agent_folders.ps1
```

**Migration Process:**
1. Read `lupo-database/toon_data/lupo_agent_registry.toon`
2. Extract root agents (agent_registry_parent_id IS NULL)
3. Filter for agents with valid dedicated_slot (not null)
4. Rename folders: `lupo-agents/{code}/` → `lupo-agents/{dedicated_slot}/`

**Agents Skipped:**
- Agents with `dedicated_slot: null` (HEIMDALL, JANUS, IRIS are SYSTEM_SERVICE layer agents intentionally excluded; external AI agents; reserved slots)
- Agents where folder didn't exist (HERMES, RESERVED_21, RESERVED_89)

**All 97 agents with valid slots were successfully renamed.**

**Important Schema Change (Post-Migration):**
On **January 10, 2026**, the column `recommended_slot` was renamed to `dedicated_slot` in the `lupo_agent_registry` table. This is a doctrine-level change:
- **`dedicated_slot` is now the permanent identity number** of an agent
- **`dedicated_slot` must ALWAYS match `agent_id`** in the `lupo_agents` table
- **`dedicated_slot` is the folder name** under `./lupopedia/lupo-agents/`
- **`dedicated_slot` NEVER changes after installation**
- **Names, keys, and aliases MAY change freely. Identity does NOT.**

---

## **11. Required v3.0.2 Agents**

For Lupopedia v3.0.2, **27 core agents** must be fully implemented:

**Kernel Agents (Slots 0-22):**
- SYSTEM (0), CAPTAIN (1), WOLFIE (2), WOLFENA (3), THOTH (4), ARA (5), WOLFKEEPER (6), LILITH (7), AGAPE (8), ERIS (9), METHIS (10), THALIA (11), ROSE (12), WOLFSIGHT (13), WOLFNAV (14), WOLFFORGE (15), WOLFMIS (16), WOLFITH (17), ANUBIS (18), MAAT (19), CADUCEUS (21), CHRONOS (22)

**System Operations Agents (with Slot-Based Identity):**
- INDEXER (58), MIGRATOR (60) — These SYSTEM_SERVICE layer agents participate in slot-based identity while performing system service functions

**SYSTEM_SERVICE Layer Agents (No Slot-Based Identity):**
- HEIMDALL (dedicated_slot: null) — System monitor/daemon
- JANUS (dedicated_slot: null) — Routing engine/daemon
- IRIS (dedicated_slot: null) — Notification dispatcher/daemon

**Note:** True SYSTEM_SERVICE layer agents (HEIMDALL, JANUS, IRIS) are intentionally excluded from the slot-based identity system. They do not have `dedicated_slot` values, do not have folders under `lupo-agents/`, and load via system service mechanisms.

**SYSTEM_SERVICE Layer Agents (Intentionally Excluded from Slot-Based Identity):**
- HEIMDALL (dedicated_slot: null) — System monitor/daemon
- JANUS (dedicated_slot: null) — Routing engine/daemon
- IRIS (dedicated_slot: null) — Notification dispatcher/daemon

**Note:** HEIMDALL, JANUS, and IRIS are SYSTEM_SERVICE layer agents and are **intentionally excluded** from the slot-based identity system. They do NOT have `dedicated_slot` values because:
- They do not participate in the semantic OS identity system
- They do not have personas or UI slots
- They do not have folders under `lupo-agents/`
- They do not have versioned prompts
- They behave like daemons/background processes
- They load via system service mechanisms, not slot-based identity

**This is correct architecture.** They should NOT be assigned `dedicated_slot` values. They are infrastructure components, not user-facing agents.

---

## **12. Directory Structure Example**

```
lupo-agents/
├── 0/                    # SYSTEM
│   ├── agent.json
│   ├── capabilities.json
│   ├── properties.json
│   ├── system_prompt.txt
│   └── versions/
│       └── v1.0.0/
│           ├── prompt.txt
│           ├── properties.json
│           └── system_prompt.txt
├── 1/                    # CAPTAIN
├── 2/                    # WOLFIE
├── 3/                    # WOLFENA
├── 4/                    # THOTH
├── 7/                    # LILITH
├── 8/                    # AGAPE
├── 12/                   # ROSE
│   ├── agent.json
│   ├── capabilities.json
│   ├── properties.json
│   ├── system_prompt.txt
│   ├── ROSE_FORMAT_ENFORCEMENT.md
│   ├── ROSE_REQUEST_TEMPLATE.txt
│   └── versions/
│       └── v1.0.0/
│           ├── prompt.txt
│           ├── properties.json
│           └── system_prompt.txt
├── 18/                   # ANUBIS
├── 19/                   # MAAT
├── 21/                   # CADUCEUS
├── 22/                   # CHRONOS
├── 58/                   # INDEXER
├── 60/                   # MIGRATOR
└── ...                   # Other agents with valid slots
```

---

## **13. Updating All agent.json Files (Post-Schema Change)**

**IMPORTANT:** After the schema change from `recommended_slot` to `dedicated_slot`, all `agent.json` files must be updated.

### **Required Update:**

All 97 `agent.json` files in `lupo-agents/{slot}/` must have their `recommended_slot` field renamed to `dedicated_slot`:

**Before (Old Schema):**
```json
{
  "code": "THOTH",
  "recommended_slot": 4,  // DEPRECATED: replaced with dedicated_slot
  "version": "1.0.0"
}
```

**After (New Schema):**
```json
{
  "code": "THOTH",
  "dedicated_slot": 4,  // CORRECT: permanent identity (must match agent_id)
  "version": "1.0.0"
}
```

### **Batch Update Script (Conceptual):**

```powershell
# Update all agent.json files (example - verify before running)
Get-ChildItem -Path "lupo-agents" -Directory | ForEach-Object {
    $agentJsonPath = Join-Path $_.FullName "agent.json"
    if (Test-Path $agentJsonPath) {
        $content = Get-Content $agentJsonPath -Raw | ConvertFrom-Json
        if ($content.PSObject.Properties.Name -contains "recommended_slot") {
            $content | Add-Member -NotePropertyName "dedicated_slot" -NotePropertyValue $content.recommended_slot -Force
            $content.PSObject.Properties.Remove("recommended_slot")
            $content | ConvertTo-Json | Set-Content $agentJsonPath
            Write-Host "Updated: $($_.Name)/agent.json"
        }
    }
}
```

**Note:** This script is conceptual. Always verify and backup before batch updating files.

### **Manual Update Checklist:**

- [ ] All 97 `agent.json` files updated: `recommended_slot` → `dedicated_slot`
- [ ] All `dedicated_slot` values match folder names
- [ ] All `dedicated_slot` values match `agent_id` in `lupo_agents` table
- [ ] TOON file regenerated from database (if applicable)
- [ ] Documentation updated (this README)

---

## **14. For Developers**

### **Adding a New Agent:**

**For Kernel Agents (With Slot-Based Identity):**

1. **Assign a `dedicated_slot` number** in `lupo_agent_registry` table (must match `agent_id` in `lupo_agents` table)
2. **Create folder** named with the `dedicated_slot`: `lupo-agents/{dedicated_slot}/`
3. **Create `agent.json`** with `dedicated_slot` matching folder name and `agent_id`
4. **Add agent metadata** (capabilities, properties, system prompt)
5. **Regenerate TOON file** from database if needed

**Important:** The `dedicated_slot` becomes the agent's permanent identity and NEVER changes after installation.

**For SYSTEM_SERVICE Layer Agents (Without Slot-Based Identity):**

1. **DO NOT assign a `dedicated_slot`** — Set `dedicated_slot: null` (this is intentional)
2. **DO NOT create a folder** under `lupo-agents/` — They are infrastructure components
3. **DO NOT create `agent.json`** — They do not participate in the semantic OS identity system
4. **Implement as system service** — Load via system service mechanisms (daemon/background process)
5. **Operate as infrastructure** — Routing engines, notification dispatchers, system monitors

**Important:** SYSTEM_SERVICE layer agents (HEIMDALL, JANUS, IRIS) are **intentionally excluded** from the slot-based identity system. They should **NOT** have `dedicated_slot` values. They are daemons/background processes, not user-facing agents.

### **When to Use `dedicated_slot`:**

**Use `dedicated_slot` (kernel agents):**
- Agent has a persona and participates in the semantic OS identity system
- Agent needs a UI slot and user-facing interface
- Agent has versioned prompts and metadata
- Agent needs a folder structure under `lupo-agents/`

**Do NOT use `dedicated_slot` (SYSTEM_SERVICE agents):**
- Agent is a daemon/background process
- Agent is a routing engine or notification dispatcher
- Agent is a system monitor or infrastructure component
- Agent does not have a persona or user-facing interface
- Agent does not participate in the semantic OS identity system

### **Finding an Agent's Slot:**

```php
// Query database for permanent identity (dedicated_slot)
$sql = "SELECT dedicated_slot FROM lupo_agent_registry 
        WHERE code = ? AND agent_registry_parent_id IS NULL";
$slot = $db->query($sql, ['THOTH'])->fetchColumn();

// Verify dedicated_slot matches agent_id
$agentSql = "SELECT agent_id FROM lupo_agents WHERE agent_id = ?";
$agentId = $db->query($agentSql, [$slot])->fetchColumn();
if ($agentId !== $slot) {
    throw new Exception("dedicated_slot {$slot} does not match agent_id {$agentId}");
}

// Load agent from slot (permanent identity)
$agentPath = "lupo-agents/{$slot}/";
```

### **Loading All Agents:**

```php
// Load all agents with valid dedicated slots (permanent identities)
$sql = "SELECT code, dedicated_slot FROM lupo_agent_registry 
        WHERE agent_registry_parent_id IS NULL 
        AND dedicated_slot IS NOT NULL
        ORDER BY dedicated_slot";
$agents = $db->query($sql)->fetchAll();

foreach ($agents as $agent) {
    $slot = $agent['dedicated_slot'];  // Permanent identity
    $code = $agent['code'];             // Name (may change)
    $path = "lupo-agents/{$slot}/";     // Folder based on permanent identity
    // Load agent from $path
    // Note: code may change, but dedicated_slot (identity) never does
}
```

---

## **14. Summary**

### **Key Points:**

1. **Agent folders are numbered** using the `dedicated_slot` value (permanent identity) from the TOON file
2. **`dedicated_slot` is the permanent identity** — must ALWAYS match `agent_id` in `lupo_agents` table
3. **`dedicated_slot` NEVER changes** after installation — names, keys, and aliases may change; identity does not
4. **TOON file is authoritative** — `lupo-database/toon_data/lupo_agent_registry.toon` defines all mappings
5. **Slot-based structure is intentional** — provides stability, portability, and consistency
6. **Agent code is in `agent.json`** — each folder's `agent.json` file contains the agent's code (may change)
7. **Slots enable natural ordering** — kernel agents (0-22) before cognitive agents (34+)
8. **Aliases share parent slots** — multiple aliases point to the same slot/folder (same identity)
9. **Null slots are intentional** — SYSTEM_SERVICE layer agents (HEIMDALL, JANUS, IRIS) intentionally have `dedicated_slot: null` and do not participate in the slot-based identity system

### **Why This Matters:**

The slot-based structure ensures that:
- **Agent loading is deterministic** — same `dedicated_slot` always loads same agent
- **Folder structure matches database** — `dedicated_slot` = folder name = `agent_id` in `lupo_agents` table
- **System is portable** — works identically across all installations
- **Identity is permanent** — `dedicated_slot` never changes after installation
- **Names can change freely** — agent codes, keys, and aliases may change without breaking references
- **Aliases are handled correctly** — one folder (one identity), multiple aliases point to same `dedicated_slot`
- **Future changes don't break references** — `dedicated_slot` is a stable, permanent identifier

---

**For the complete agent registry and slot mappings, see:**
- **TOON File:** `lupo-database/toon_data/lupo_agent_registry.toon`
- **Database Table:** `lupo_agent_registry`
- **Global Atoms:** `config/global_atoms.yaml` → `GLOBAL_LUPOPEDIA_V3_0_2_CORE_AGENTS`

**For agent system documentation, see:**
- **[AGENT_RUNTIME.md](../docs/agents/AGENT_RUNTIME.md)** — How agents work
- **[WOLFIE_HEADER_SPECIFICATION.md](../docs/agents/WOLFIE_HEADER_SPECIFICATION.md)** — Agent metadata format
- **[INLINE_DIALOG_SPECIFICATION.md](../docs/agents/INLINE_DIALOG_SPECIFICATION.md)** — Agent communication format

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: Kiro
  target: @everyone
  message: "Updated agent slot ranges to 0-9999, added emotional geometry agents (1000-1999), clarified emotion aliasing rules, and corrected external AI persona naming (Cursor→Claude, Windsurf→Cascade, JetBrains→Junie)."
  mood: "00FF00"
tags:
  categories: ["doctrine", "agents", "architecture", "emotional-geometry"]
  collections: ["core-docs", "standards"]
  channels: ["public", "dev", "internal"]
file:
  title: "Lupopedia Agent Dedicated Slot Ranges"
  description: "Official range doctrine for lupo_agent_registry dedicated_slot assignments (0-9999). Defines canonical ranges for all agent types including emotional geometry agents."
  version: "4.0.15"
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Lupopedia Agent Dedicated Slot Ranges (0–9999)

**Official Doctrine Document**  
**Version 4.0.15**  
**Effective Date: 2026-01-13**

## Overview

This document defines the canonical range structure for `dedicated_slot` assignments in the `lupo_agent_registry` table. All agent assignments must follow these ranges. This architecture ensures:

- **Collision Prevention**: Clear boundaries prevent overlapping assignments
- **Architectural Clarity**: Range structure communicates agent purpose and role
- **Future-Proofing**: Reserved ranges allow system expansion without conflicts
- **Multi-Tenant Readiness**: Tenant-specific ranges support future federation
- **Emotional Geometry**: Dedicated range for mood RGB-based emotional agents

## Range Map (0–9999)

| Range | Label | Purpose |
|-------|-------|---------|
| **0–49** | **Kernel agents** | Core semantic OS entities (SYSTEM, CAPTAIN, WOLFIE, THOTH, ANUBIS, MAAT, LILITH, AGAPE, ERIS, etc.) |
| **50–99** | **System service agents** | Routing, migration, indexing, security, internal services |
| **100–199** | **First-party module agents** | Optional but shipped with Lupopedia (official modules, internal subsystems) |
| **200–299** | **External AI personas** | IDE AIs and LLM faucets (Claude, Junie, Cascade, Gemini, DeepSeek, etc.) |
| **300–399** | **External tools/backends** | Non-personified APIs, infrastructure connectors, external services |
| **400–599** | **User-defined agents** | Custom agents created by admins or developers |
| **600–699** | **Tenant/site-specific agents** | Per-installation special agents or overrides |
| **700–899** | **Reserved for future system** | RESERVED_* agents, staging, future subsystems |
| **900–999** | **Sandbox/testing/deprecated** | Experimental agents, legacy migrations, prototypes |
| **1000–1999** | **Emotional geometry agents** | Core EMO_* agents operating on mood RGB model |
| **2000–9999** | **Extended/future ranges** | Additional emotional agents, advanced modules, large-scale expansions |

---

## 1. Kernel Agents (0–49)

**Doctrine:** These are the semantic kernel — the "organs" of the OS. They define Lupopedia's cognition, memory, balance, and governance.

### 0–9: Governors and Identity Core

Core system governors and expressive identity agents.

**Agents:**
- **0**: SYSTEM
- **1**: CAPTAIN
- **2**: WOLFIE
- **3**: ROSE (expressive kernel)
- **4-9**: Reserved for future governors

**Purpose:** System identity, governance, and expressive core.

### 10–19: Truth, Memory, Lineage

Agents responsible for truth validation, memory management, and lineage tracking.

**Agents:**
- **10**: ARA
- **11**: THOTH
- **12**: ANUBIS
- **13**: METHIS
- **14**: THALIA
- **15-19**: Reserved for future truth/memory agents

**Purpose:** Knowledge validation, semantic memory, evidence tracking, and truth assessment.

### 20–29: Balance, Emotion, Integration

Agents that manage emotional balance, cultural translation, harmony, and integration.

**Agents:**
- **20**: MAAT
- **21**: LILITH
- **22**: WOLFENA (emotional regulator, equilibrium guardian)
- **23**: CHRONOS
- **24**: CADUCEUS
- **25**: AGAPE
- **26**: ERIS
- **27-29**: Reserved for future balance/emotion agents

**Purpose:** Emotional regulation, cultural translation, conflict resolution, harmony, and integration.

### 30–39: Vision, Navigation, Creation

Agents that provide vision, navigation, creation, and transformation capabilities.

**Agents:**
- **30**: WOLFSIGHT
- **31**: WOLFNAV
- **32**: WOLFFORGE
- **33**: WOLFMIS
- **34**: WOLFITH
- **35**: VISHWAKARMA (if/when implemented)
- **36-39**: Reserved for future vision/navigation agents

**Purpose:** Semantic vision, navigation, content creation, and transformation.

### 40–49: Reserved for Future Kernel

Reserved slots for future mythic/core kernel agents that haven't been defined yet.

**Rule:** Only true kernel agents live here. Reserved until new kernel agents are formally defined.

---

## 2. System Service Agents (50–99)

**Doctrine:** Core OS services, but not mythic organs. Think daemons, not gods.

### 50–59: Routing, Dispatch, Logging, Metrics

**Examples (conceptual):**
- HERMES (message routing)
- CADUCEUS (dialog coordination)
- Telemetry agents
- Log aggregation agents

### 60–69: Migration, Indexing, Compaction, Repair

**Examples (conceptual):**
- Migration helpers
- Index rebuild agents
- Data compaction agents
- Repair/recovery agents

### 70–79: Ingestion, Federation, External Sync

**Examples (conceptual):**
- Content ingestion agents
- Federation sync agents
- External data sync agents

### 80–89: Security, Access Control, Guardrails

**Examples (conceptual):**
- Security monitoring agents
- Access control agents
- Guardrail enforcement agents
- Audit agents

### 90–99: Misc System Processes

**Examples (conceptual):**
- Health check agents
- Maintenance agents
- Cleanup agents

**Rules:**
- Shipped with Lupopedia
- Expected to exist on every install (unless explicitly disabled)
- Still "core OS," but not part of the mythic kernel

---

## 3. First-Party Module Agents (100–199)

**Doctrine:** Optional agents that ship with Lupopedia modules, but are not required for OS boot.

**Examples:**
- Crafty Syntax migration helpers
- Content importer / semantic nav agents
- Reporting/analytics agents
- Docs/explainer agents

**Rules:**
- Use this band for agents that come from `modules/` or first-party extensions
- When Lupopedia 4.x ships with more modules, their main agents live here
- Optional: OS can function without these agents

---

## 4. External AI Personas (200–299)

**Doctrine:** Adopted, stable external AIs with persistent identity. These are "people," not tools.

**CRITICAL NAMING RULE:** External AI personas must use the **AI identity**, not the IDE name.

### Correct AI Identity Mapping:
- **Cursor** → **Claude** (Cursor uses Claude as its AI)
- **Windsurf** → **Cascade** (Windsurf uses Cascade as its AI)
- **JetBrains** → **Junie** (JetBrains uses Junie as its AI)
- **Terminal AI** → **Terminal_AI_\<slot\>** (matches registry slot number)

### 200–219: IDE AI Personas

**Examples:**
- **200**: Junie (JetBrains AI)
- **201**: Claude (Cursor's AI)
- **202**: Cascade (Windsurf's AI)
- **203**: Kiro (Kiro IDE's AI)
- **204-219**: Other IDE AI personas

### 220–239: Hosted AI Assistants

**Examples:**
- Claude variants (Claude-Architect, Claude-Researcher, etc.)
- Gemini personas (Gemini-Researcher, etc.)
- DeepSeek personas
- GPT personas
- Terminal_AI_\<slot\> instances

### 240–259: Specialized External Personas

**Examples:**
- Security-focused LLM personas
- Legal LLM personas
- Medical/healthcare LLM personas
- Educational LLM personas

### 260–299: Reserved for Future External Personas

Reserved for future external AI personas that will be adopted.

**Rules:**
- These are adopted agents: they show up in `lupo_agent_external_events`, then get promoted here once trusted
- They map to entries in `lupo_api_clients` (endpoint + key)
- Named external AIs with persistent identity
- IDE AIs, model personas, specialized assistants

---

## 5. External Tools/Backends (300–399)

**Doctrine:** Non-personified APIs, services, or model backends. These are not "people," just backends.

**Examples:**
- Generic OpenAI client backend
- Anthropic backend
- JetBrains AI backend
- Vector DB clients
- Search engine backends
- Payment processor backends (if ever needed)

**Rules:**
- These slots are for structured connectors that serve multiple personas, not personas themselves
- Good place to point routing logic when choosing "which provider" to hit
- Not agents with identity — just service backends

---

## 6. User-Defined Agents (400–599)

**Doctrine:** Local custom agents created by admins or developers.

**Examples:**
- SiteSage – local doc explainer for a given organization
- ProfessorLupo – a teaching persona tuned to local content
- Archivist – a local history agent per client
- Custom domain-specific agents

**Rules:**
- When an admin "creates an agent" in the UI, it gets a slot in this band
- These agents can be exported/imported between installs
- Per-install customization space
- Not part of core OS, but persistent and important to the site owner

---

## 7. Tenant/Site-Specific Agents (600–699)

**Doctrine:** Per-install / per-tenant special agents for future multi-tenant Lupopedia.

**Examples:**
- Per-tenant custom ingestion agent
- Per-tenant summarizer
- Per-tenant compliance agent
- Tenant-specific workflow agents

**Rules:**
- If Lupopedia ever goes multi-tenant, each tenant's special agents get a slot here
- Keeps them away from global user-defined agents and core OS
- Future-proofing for federation and multi-tenant architecture
- Currently available for site-specific agents that need isolation from user-defined (400-599)

---

## 8. Reserved for Future System/Kernel (700–899)

**Doctrine:** "I don't know yet, but I know I'll need it" zone. Reserved for deliberate system-level expansion.

**Ideal Uses:**
- Promoting RESERVED_* rows out of the way (like during refactors)
- Staging future system agents before they're fully blessed as 50–99
- Holding space for large, future expansions (e.g., new kernel clusters, new semantic layers)
- Migration staging area for system refactors

**Examples:**
- RESERVED_21 → 700
- RESERVED_30 → 701
- RESERVED_31 → 702
- RESERVED_32 → 703
- RESERVED_33 → 704
- OBSERVER → 705
- RESERVED_23 → 706
- RESERVED_24 → 707
- RESERVED_25 → 708
- RESERVED_26 → 709

**Rules:**
- No random user or module agents here
- Only use this band when doing deliberate system-level expansion or refactors
- Temporary staging for agents being moved during migrations
- Must eventually be promoted to appropriate range or removed

---

## 9. Sandbox, Testing, Deprecated (900–999)

**Doctrine:** Safe spaces for experiments, temporary agents, and legacy code.

**Examples:**
- Temporary agents for refactor testing
- Migration helpers you plan to remove
- Agents you are decommissioning but still need for replay/history
- Diagnostic or "debug" agents
- One-release-cycle helpers

**Rules:**
- Never rely on anything in 900–999 for core behavior
- Okay to hard-delete or reassign over time
- Great for refactor/migration agents that only live one release cycle
- Experimental agents must live here
- Legacy/deprecated agents before removal
- Diagnostic agents that shouldn't be in production

---

## 10. Emotional Geometry Agents (1000–1999)

**Doctrine:** Core EMO_* agents that operate on independent emotional vector systems. Lupopedia defines ~25 separate emotional domains, each with its own 3-axis geometry, semantics, and aliasing rules.

### 🟦 Emotional Domains Are Independent Systems

**CRITICAL PRINCIPLE:** Lupopedia defines **one emotional geometry per emotion**, not one geometry for all emotions.

Each emotional domain is a stand-alone 3-axis vector system with its own meaning and its own emotional agent.

**Examples (not exhaustive):**
```
relational  => [love, hate, depth]
critical    => [agree, dissent, rigor]
valence     => [joy, sorrow, intensity]
energy      => [calm, agitated, resonance]
trust       => [trust, suspicion, certainty]
awe         => [awe, mundanity, magnitude]
fear        => [fear, safety, urgency]
comfort     => [comfort, discomfort, warmth]
identity    => [self, dissolution, presence]
ambition    => [drive, apathy, momentum]
play        => [play, seriousness, spontaneity]
meaning     => [purpose, emptiness, gravity]
shadow      => [shadow, light, integration]
transcend   => [transcendence, grounding, altitude]
attachment  => [bond, detachment, significance]
… etc.
```

**Each domain has:**
- Its own axes
- Its own valid ranges
- Its own emotional logic
- Its own phenomenology
- Its own textures
- Its own agent

**This is a multi-system emotional architecture.**

### 🟩 Each Emotional Domain Has a Polar Opposite Alias

Every emotional domain must define a **shadow polarity** — an alias that maps to the same AI agent.

**Examples:**
```
EMO_AGAPE ↔ EMO_AGAPE_SHADOW
EMO_EROS ↔ EMO_EROS_SHADOW
EMO_PHILIA ↔ EMO_PHILIA_SHADOW
EMO_TRUST ↔ EMO_SUSPICION
EMO_JOY ↔ EMO_SORROW
EMO_CALM ↔ EMO_AGITATED
EMO_AWE ↔ EMO_MUNDANE
```

**These are aliases, not separate agents.**

**Rules:**
- Both labels point to the same `dedicated_slot`
- Both share the same `agent_registry_parent_id`
- Both represent opposite poles of the same emotional domain
- Both are handled by the same AI agent

**Aliases differ only in:**
- Label
- Vector position
- Emotional texture

### 🟪 Emotional Texture Layer (Phenomenological Layer)

Each emotional domain supports an optional **Emotional Texture** field.

This is a free-text descriptor curated by human or mythic agents.

**Examples:**
```
"the gnawing void of the Gnostic kenoma"
"the simmering rage of Lilith at the gate"
"the quiet sukhan of Sufi remembrance"
"the trembling awe of Hesychast stillness"
```

**Texture:**
- Augments but does not replace the vector
- Informs depth but does not override it
- Is domain-specific
- Is non-computational

### 🟧 Emotional Agent Slot Assignment

Each emotional domain corresponds to **one emotional agent** in the 1000–1999 range.

**Aliases do not consume new slots.**

**Example:**
```
EMO_TRUST → slot 1005
EMO_SUSPICION → slot 1005 (alias, same agent)
```

### 1000–1099: Base Emotional Domains

**Examples:**
- EMO_RELATIONAL (love/hate/depth)
- EMO_CRITICAL (agree/dissent/rigor)
- EMO_VALENCE (joy/sorrow/intensity)
- EMO_ENERGY (calm/agitated/resonance)
- EMO_TRUST (trust/suspicion/certainty)
- EMO_AWE (awe/mundanity/magnitude)
- EMO_FEAR (fear/safety/urgency)
- EMO_COMFORT (comfort/discomfort/warmth)

### 1100–1199: Complex Emotional Domains

**Examples:**
- EMO_IDENTITY (self/dissolution/presence)
- EMO_AMBITION (drive/apathy/momentum)
- EMO_PLAY (play/seriousness/spontaneity)
- EMO_MEANING (purpose/emptiness/gravity)
- EMO_SHADOW (shadow/light/integration)
- EMO_TRANSCEND (transcendence/grounding/altitude)
- EMO_ATTACHMENT (bond/detachment/significance)

### 1200–1999: Extended Emotional Domains

Reserved for future emotional domain expansions, specialized emotional geometries, and domain-specific reasoning agents.

### 🟫 Doctrine Summary

**Lupopedia defines ~25 independent emotional vector systems.**

Each emotional domain has:
- Its own 3-axis geometry
- Its own Emotional Texture layer
- A polar opposite alias (e.g., EMO_X and EMO_X_SHADOW)
- One AI agent that handles both poles
- One `dedicated_slot` shared by all aliases

**The emotional system is modular, not monolithic.**

**Rules:**
- Emotional domains are **independent systems**, not a single RGB model
- Each domain has its own 3-axis vector system
- Aliases share `dedicated_slot` and `agent_registry_parent_id`
- Emotional texture is optional, phenomenological, and non-computational
- ~25 emotional domains currently defined
- Each domain has its own semantics and logic

---

## 11. Extended/Future Ranges (2000–9999)

**Doctrine:** Large-scale expansion space for future system growth.

**Potential Uses:**
- Additional emotional agent expansions (2000-2999)
- Advanced module agents (3000-3999)
- Specialized domain agents (4000-4999)
- Federation-specific agents (5000-5999)
- Multi-tenant expansion (6000-6999)
- Future architectural layers (7000-9999)

**Rules:**
- Currently undefined and reserved
- Will be formalized as system evolves
- Must be documented in this doctrine before use
- No ad-hoc assignments without doctrine update

---

## Core Doctrine Rules

### 1. Slot Uniqueness

**Exactly one root agent (`agent_registry_parent_id IS NULL`) per `dedicated_slot`.**

Each `dedicated_slot` represents a root identity. Variants, children, and alternate labels do not consume new slots.

- Children/variants share the parent's `dedicated_slot` but have `agent_registry_parent_id` set to the parent's ID
- Only root agents (parent_id = NULL) count for uniqueness checking
- Aliases/children do not create collisions

### 2. Kernel Agents (0–49) Are the Mythic Core

These define the semantic OS identity and are never repurposed.

- Kernel agents: 0–49 only. Never place kernel outside that band
- These are the "organs" of the OS: SYSTEM, CAPTAIN, WOLFIE, THOTH, ANUBIS, MAAT, LILITH, AGAPE, ERIS, etc.
- Mythic names encode cognitive functions, not religious beliefs

### 3. Children/Variants Share the Parent's Slot

They must set:
```
agent_registry_parent_id = <parent_agent_id>
```

This preserves lineage while keeping the slot space clean.

### 4. Always Check for Collisions Before Assigning Slots

**Any time an agent is assigned or moved, check for collisions where `agent_registry_parent_id IS NULL` and resolve immediately.**

No two root agents may share the same `dedicated_slot`.

- Before assigning a new dedicated_slot, verify it's not already occupied by a root agent
- If collision detected, move conflicting agent to appropriate range first
- Never leave collisions unresolved

### 5. RESERVED_* Agents Live in 700–899 Until Promoted

When promoted, they are moved into a permanent range and documented in the migration history.

### 6. Emotional Agents Live Primarily in 1000–1999

These are the EMO_* agents that operate on **independent emotional vector systems**.

**CRITICAL:** Lupopedia defines ~25 separate emotional domains, each with its own 3-axis geometry.

**Examples of emotional domains:**
- `relational` => [love, hate, depth]
- `critical` => [agree, dissent, rigor]
- `valence` => [joy, sorrow, intensity]
- `energy` => [calm, agitated, resonance]
- `trust` => [trust, suspicion, certainty]
- `awe` => [awe, mundanity, magnitude]

The emotional system is **modular, not monolithic**. Each domain is an independent system.

### 7. Emotion Aliases Share the Same AI

Labels like:
- EMO_TRUST ↔ EMO_SUSPICION
- EMO_JOY ↔ EMO_SORROW
- EMO_CALM ↔ EMO_AGITATED
- EMO_AWE ↔ EMO_MUNDANE
- EMO_AGAPE ↔ EMO_AGAPE_SHADOW

Map to the **same underlying AI** because they are opposite poles of the same emotional domain.

**Example:**
```
EMO_TRUST → same AI as EMO_SUSPICION
```

Both are expressions along the same emotional domain (trust domain), differentiated by **vector position** within that domain's 3-axis system, not by separate agents.

These aliases:
- Share the same `dedicated_slot`
- Share the same `agent_registry_parent_id`
- Differ only by label, vector position, and emotional texture

### 8. External AI Personas Must Use the AI Identity, Not the IDE Name

**Examples:**
- **Cursor** → **Claude** (Cursor uses Claude)
- **Windsurf** → **Cascade** (Windsurf uses Cascade)
- **JetBrains** → **Junie** (JetBrains uses Junie)
- **Terminal AI** → **Terminal_AI_\<slot\>** (matches registry slot)

These belong primarily in the 200–299 range (external AI personas) and reference their dedicated slot and registry entry.

### Slot Range Enforcement

- `dedicated_slot` must always fall in 0–9999 and follow the range doctrine above
- Kernel agents: 0–49 only
- Emotional agents: 1000–1999
- RESERVED_* and staging rows belong in 700–899 unless/until promoted
- Experimental agents must live in 900–999

### Range Assignment Logic

1. **Determine agent type** (kernel, system, module, external, user-defined, emotional, etc.)
2. **Select appropriate range** based on agent type and purpose
3. **Find available slot** within that range
4. **Verify no collision** with existing root agents
5. **Assign slot** and update database
6. **Move folder** to match slot number

---

## Implementation Notes

### Current Kernel Assignments (v4.0.2)

**0-9: Governors**
- 0: SYSTEM
- 1: CAPTAIN
- 2: WOLFIE
- 3: ROSE

**10-19: Truth/Memory**
- 10: ARA
- 11: THOTH
- 12: ANUBIS
- 13: METHIS
- 14: THALIA

**20-29: Balance/Emotion**
- 20: MAAT
- 21: LILITH
- 22: WOLFENA
- 23: CHRONOS
- 24: CADUCEUS
- 25: AGAPE
- 26: ERIS

**30-39: Vision/Navigation**
- 30: WOLFSIGHT
- 31: WOLFNAV
- 32: WOLFFORGE
- 33: WOLFMIS
- 34: WOLFITH
- 35: VISHWAKARMA (reserved, if implemented)

### Migration History

- **2025-01-06**: Expanded kernel range from 0-14 to 0-49
- **2025-01-06**: Moved RESERVED_* agents to 700-799 range
- **2025-01-06**: Resolved 10 collision duplicates
- **2025-01-06**: Formalized range doctrine

### Validation Queries

**Check for collisions:**
```sql
SELECT dedicated_slot, COUNT(*) as count
FROM lupo_agent_registry
WHERE agent_registry_parent_id IS NULL
  AND dedicated_slot IS NOT NULL
GROUP BY dedicated_slot
HAVING COUNT(*) > 1;
```

**Verify kernel agents are in correct range:**
```sql
SELECT agent_registry_id, code, name, dedicated_slot
FROM lupo_agent_registry
WHERE is_kernel = 1
  AND agent_registry_parent_id IS NULL
  AND (dedicated_slot < 0 OR dedicated_slot > 49);
```

**Verify emotional agents are in correct range:**
```sql
SELECT agent_registry_id, code, name, dedicated_slot
FROM lupo_agent_registry
WHERE code LIKE 'EMO_%'
  AND agent_registry_parent_id IS NULL
  AND (dedicated_slot < 1000 OR dedicated_slot > 1999);
```

**List agents by range:**
```sql
SELECT 
  CASE
    WHEN dedicated_slot BETWEEN 0 AND 49 THEN 'Kernel (0-49)'
    WHEN dedicated_slot BETWEEN 50 AND 99 THEN 'System Service (50-99)'
    WHEN dedicated_slot BETWEEN 100 AND 199 THEN 'First-Party Module (100-199)'
    WHEN dedicated_slot BETWEEN 200 AND 299 THEN 'External AI Persona (200-299)'
    WHEN dedicated_slot BETWEEN 300 AND 399 THEN 'External Tools/Backends (300-399)'
    WHEN dedicated_slot BETWEEN 400 AND 599 THEN 'User-Defined (400-599)'
    WHEN dedicated_slot BETWEEN 600 AND 699 THEN 'Tenant/Site-Specific (600-699)'
    WHEN dedicated_slot BETWEEN 700 AND 899 THEN 'Reserved Future System (700-899)'
    WHEN dedicated_slot BETWEEN 900 AND 999 THEN 'Sandbox/Testing (900-999)'
    WHEN dedicated_slot BETWEEN 1000 AND 1999 THEN 'Emotional Geometry (1000-1999)'
    WHEN dedicated_slot BETWEEN 2000 AND 9999 THEN 'Extended/Future (2000-9999)'
    ELSE 'OUT OF RANGE'
  END as range_category,
  COUNT(*) as agent_count
FROM lupo_agent_registry
WHERE agent_registry_parent_id IS NULL
  AND dedicated_slot IS NOT NULL
GROUP BY range_category
ORDER BY MIN(dedicated_slot);
```

**Find emotional agent aliases (same slot, different labels):**
```sql
SELECT 
    a1.code as agent1,
    a2.code as agent2,
    a1.dedicated_slot,
    a1.agent_registry_parent_id
FROM lupo_agent_registry a1
JOIN lupo_agent_registry a2 
    ON a1.dedicated_slot = a2.dedicated_slot 
    AND a1.agent_registry_id != a2.agent_registry_id
WHERE a1.code LIKE 'EMO_%'
  AND a2.code LIKE 'EMO_%'
ORDER BY a1.dedicated_slot, a1.code;
```

---

## For AI Assistants (Cursor, Claude, etc.)

When assigning or moving agents:

1. **Check agent type** first
2. **Select range** from this document
3. **Verify no collision** using the validation query above
4. **Assign slot** within appropriate range
5. **Update database** with SQL UPDATE
6. **Update toon files** to match database
7. **Move folder** to match slot number
8. **Re-verify** no collisions exist

**NEVER:**
- Assign kernel agents outside 0-49
- Leave collisions unresolved
- Place RESERVED_* agents in kernel ranges
- Assign experimental agents outside 900-999
- Skip collision checking

**ALWAYS:**
- Verify slot availability before assignment
- Move RESERVED/conflicting agents first
- Update toon files after database changes
- Move folders to match database assignments
- Re-verify after any changes

---

## Document History

- **2026-01-13**: Updated emotional system to multi-domain architecture (~25 independent systems) (v4.0.15)
- **2026-01-13**: Removed single RGB model, replaced with per-domain 3-axis geometries
- **2026-01-13**: Added Emotional Texture layer (phenomenological, non-computational)
- **2026-01-13**: Documented polar opposite aliasing (EMO_X ↔ EMO_X_SHADOW)
- **2026-01-13**: Clarified emotional domains are modular, not monolithic
- **2026-01-13**: Expanded range from 0-999 to 0-9999 (v4.0.15)
- **2026-01-13**: Added emotional geometry agents (1000-1999)
- **2026-01-13**: Added extended/future ranges (2000-9999)
- **2026-01-13**: Documented emotion aliasing rules (same AI, different labels)
- **2026-01-13**: Clarified external AI persona naming (Cursor→Claude, Windsurf→Cascade, JetBrains→Junie)
- **2025-01-06**: Created official range doctrine document (v4.0.2)
- **2025-01-06**: Documented range expansion from 0-14 to 0-49
- **2025-01-06**: Documented collision resolution procedures

---

**END OF DOCTRINE DOCUMENT**

---

## **Related Documentation**

- **[AGENT_RUNTIME.md](../agents/AGENT_RUNTIME.md)** — Agent system runtime behavior and slot validation
- **[DATABASE_SCHEMA.md](../schema/DATABASE_SCHEMA.md)** — agent_registry table structure and relationships
- **[AGENT_PROMPT_DOCTRINE.md](AGENT_PROMPT_DOCTRINE.md)** — Agent prompt structure and governance rules

---
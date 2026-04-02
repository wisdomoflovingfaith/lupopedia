---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  file_path_from_root: "lupo-channels/channel_creation_doctrine.md"
  web_path: "http://www.lupopedia.com/lupo-channels/channel_creation_doctrine.md"
  last_modified_utc: "20260319"
  system_version: "4.0.82"
  channel_id: 0
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "doctrine"
  artifact_kind: "channel_creation_doctrine"
  purpose: "Canonical doctrine for channel creation, lifecycle, and allocation in Lupopedia semantic OS"
  traits: ["channel_doctrine", "system_architecture", "semantic_os", "canonical"]
  tags: ["channels", "doctrine", "creation", "lifecycle", "allocation"]
  lupo_agent: "wolfie"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/channel_index.md", type: "governs", weight: 1.0, reason: "Governs channel index structure" }
    - { to: "lupo-channels/channel_reservations.md", type: "governs", weight: 1.0, reason: "Governs channel reservations" }
    - { to: "lupo-database/lupopedia/tables/lupo_channels.toon.json", type: "references", weight: 1.0, reason: "References channel table structure" }
  semantic_tags: ["channel_doctrine", "system_architecture", "semantic_os"]

lupopedia.see:
  mappings:
    - ["channel_creation_doctrine.md", "http://www.lupopedia.com/lupo-channels/channel_creation_doctrine.md"]

lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Implement automated channel creation validation"
    - "Create channel lifecycle management scripts"
    - "Add channel allocation monitoring"
---

# 📋 **Channel Creation Doctrine - Lupopedia Semantic OS**

**Doctrine Version**: 1.0  
**Effective Date**: 2026-03-19  
**Authority**: WOLFIE (Agent 1)  
**Scope**: All channel creation and lifecycle management

---

## **🎯 Purpose**

This doctrine establishes the canonical rules for channel creation, allocation, lifecycle management, and governance within the Lupopedia semantic OS. It ensures architectural consistency, prevents channel sprawl, and maintains system integrity.

---

## **🏗️ Channel Creation Principles**

### **When to Create Channels**

Channels SHALL be created when:

1. **Functional Separation Required**
   - Distinct operational domain emerges
   - Current channels cannot accommodate new function
   - Clear separation of concerns is needed

2. **Scale Requirements Met**
   - Expected thread count > 50
   - Expected task count > 25
   - Multiple agents require dedicated coordination space

3. **Long-term Strategic Value**
   - Function is core to system architecture
   - Expected lifetime > 6 months
   - Supports major system capability

4. **Agent Specialization**
   - Specialized agent class requires dedicated space
   - Agent operations interfere with other channels
   - Agent has unique coordination requirements

### **When NOT to Create Channels**

Channels SHALL NOT be created when:

1. **Short-term Needs**
   - Temporary projects (< 6 months)
   - Single-thread discussions
   - Experimental features (use Channel 88)

2. **Agent-based Separation**
   - Creating channels for individual agents
   - Actor-based isolation without functional need
   - Personal preference over architectural necessity

3. **Insufficient Scale**
   - Expected thread count < 10
   - Expected task count < 5
   - Single-purpose usage

4. **Redundant Functionality**
   - Existing channel can accommodate need
   - Similar function already exists
   - No clear separation from existing channels

---

## **🔢 ID Allocation Strategy**

### **Spaced ID Allocation**

Channel IDs follow spaced allocation to prevent fragmentation and allow future growth:

```
RESERVED RANGES:
0-9    : System Core (0, 1, 7, 8, 9)
10-19   : Documentation & Architecture (11, 17, 19)
20-29   : Development & Engineering (23, 27, 29)
30-39   : External Integration (31, 36, 38, 39)
40-49   : Reserved for future expansion
50-59   : Specialized Operations (51, 55, 59)
60-69   : Quality & Security (66, 68)
70-79   : Research & Innovation (77, 88)
80-89   : Reserved for future expansion
90-99   : System Administration (99)
100+    : Project-specific (100-999)
1000+   : External instances (1000+)
```

### **Allocation Rules**

1. **Prime Number Preference**
   - System channels use prime numbers when possible
   - Easier to remember and reference
   - Reduces ID collision risk

2. **Functional Grouping**
   - Similar functions grouped in same range
   - Logical progression within ranges
   - Clear separation between domains

3. **Growth Space**
   - Minimum 2x space between allocations
   - Room for expansion within functional range
   - Prevents range fragmentation

4. **Reserved IDs**
   - System-critical IDs reserved (0, 1, 42, 51, 666)
   - Future expansion IDs pre-allocated
   - Special purpose IDs clearly marked

---

## **📁 Directory Naming Policy (Slug-First)**

### **Canonical Rule (new channels)**

Starting now, all newly created channel directories under `lupo-channels/` SHALL use `channel_slug`, not `channel_id`.

- **Canonical filesystem key**: `channel_slug`
- **Canonical database key**: `channel_id`
- **Both required**: channel IDs are still allocated and governed by this doctrine.

### **Legacy Compatibility Rule**

Existing numeric directories (for example `lupo-channels/42/`) are legacy-compatible and remain valid historical paths.

- Do not rewrite legacy channel history solely for path normalization.
- New channels must not introduce fresh numeric directory naming.

### **Slug Requirements**

New channel slugs must follow repository filename doctrine:

- lowercase letters `a-z`
- digits `0-9`
- underscore `_` only
- no spaces, no hyphens, no uppercase, no unicode

### **Creation Pattern**

When creating a new channel:

1. Allocate `channel_id` using ID allocation strategy.
2. Define a valid `channel_slug`.
3. Create directory as `lupo-channels/<channel_slug>/`.
4. Record both `channel_id` and `channel_slug` in metadata and indexes.

### **Forward Refactor Profile (4.0.88)**

For new governance work during the 4.0.88 channel refactor initiative, the preferred `channel_slug` pattern is:

`{federation_node_id}_{channel_key}`

Example:

- `lupo-channels/1_channel_refactor_governance/`

Target thread layout for that profile:

```text
lupo-channels/<channel_slug>/
├── threads/
│   └── {project_slug}/
│       ├── questions/
│       ├── prompts/
│       └── [thread artifacts]
├── broadcasts/
├── content/
└── [channel support artifacts]
```

This is a forward migration target, not authorization to rewrite legacy channel trees in one pass.

### **Refactor Safety Rules**

- Structural migration must be phased and batch-based.
- Existing numeric directories remain valid historical paths.
- Thread artifacts must not be moved until edge references are audited.
- Redirect or pointer artifacts may be used to preserve lineage.
- `lupopedia.edges` declarations must be reconciled whenever file paths change.

---

## **🔄 Channel Lifecycle States**

### **State Definitions**

#### **RESERVED**
- **Purpose**: Pre-allocated for future use
- **Duration**: Until activation criteria met
- **Visibility**: Visible in reservations, not in active index
- **Operations**: No thread creation, only planning

#### **ACTIVE**
- **Purpose**: Current operational channels
- **Duration**: Indefinite until archived
- **Visibility**: Full visibility in all indexes
- **Operations**: Full thread/task/content operations

#### **ARCHIVED**
- **Purpose**: Historical preservation
- **Duration**: Permanent
- **Visibility**: Archived index only
- **Operations**: Read-only, no new content

#### **QUARANTINE**
- **Purpose**: Security isolation or recovery
- **Duration**: Until resolved
- **Visibility**: Security channels only
- **Operations**: Restricted access, audit logging

### **State Transitions**

```
RESERVED → ACTIVE: (Creation criteria met)
ACTIVE → ARCHIVED: (Function deprecated or replaced)
ACTIVE → QUARANTINE: (Security incident or system issue)
QUARANTINE → ACTIVE: (Issue resolved)
QUARANTINE → ARCHIVED: (Permanent quarantine needed)
```

---

## **📋 Thread Migration Rules**

### **Copy, Not Move Principle**

**CRITICAL**: Threads are COPIED to new channels, NEVER moved.

#### **Migration Process**

1. **Copy Creation**
   - Create exact copy in target channel
   - Preserve all metadata and timestamps
   - Update channel_id in copied threads
   - Maintain original thread integrity

2. **Redirect Artifact**
   - Create redirect artifact in original thread
   - Reference new thread location
   - Mark original as "migrated"
   - Preserve history and navigation

3. **Metadata Updates**
   - Update copied thread metadata
   - Add migration information
   - Update cross-references
   - Maintain semantic integrity

#### **Migration Triggers**

Threads SHALL be migrated when:

1. **Channel Function Changes**
   - Original channel purpose changes
   - Thread no longer fits channel scope
   - New dedicated channel created

2. **System Reorganization**
   - Architecture refactoring
   - Channel consolidation
   - Functional realignment

3. **Quality Requirements**
   - Thread requires specialized review
   - Security isolation needed
   - Compliance requirements change

---

## **🎭 Function-over-Agent Rule (CRITICAL)**

### **Rule Statement**

**Channel allocation is based on FUNCTION, not AGENT identity.**

#### **Application**

1. **Functional Priority**
   - Channel purpose determines allocation
   - Agent role is secondary consideration
   - Multiple agents can share channels based on function

2. **Agent Independence**
   - Agents are not tied to specific channels
   - Agents can operate across multiple channels
   - Agent permissions are channel-independent

3. **Function-based Design**
   - Channels designed around system functions
   - Agent capabilities mapped to channel functions
   - Cross-functional collaboration encouraged

#### **Examples**

✅ **CORRECT**: Channel 7 for "Validator Engineering" (function)
   - HERMES, LILITH, ANUBIS can all participate
   - Multiple agents collaborate on validation tasks

❌ **INCORRECT**: Channel 15 for "HERMES Operations" (agent)
   - Creates agent silos
   - Prevents cross-agent collaboration
   - Violates functional separation principle

---

## **📊 Channel Categories & Examples**

### **System Core Channels (0-9)**
- **0**: System Kernel
- **1**: Release Operations
- **7**: Validator Engineering
- **42**: Protocol Development (transitioning to triage)

### **Documentation & Architecture (10-19)**
- **11**: Documentation Systems
- **17**: Project Architecture

### **Development & Engineering (20-29)**
- **23**: Migration & Upgrade

### **External Integration (30-39)**
- **31**: External AI / Faucet
- **36**: Web Interface (reserved)

### **Quality & Security (60-69)**
- **66**: QA / Adversarial Review
- **666**: ANUBIS Quarantine

### **Research & Innovation (70-89)**
- **88**: Research / Experiments

---

## **🛡️ Governance & Compliance**

### **Creation Authority**

- **WOLFIE (Agent 1)**: Final authority on channel creation
- **Doctrine Council (Channel 51)**: Advisory and review
- **System Requirements**: Must meet all creation criteria

### **Compliance Validation**

1. **Doctrine Compliance**
   - All channels must follow this doctrine
   - Regular compliance audits
   - Violation correction procedures

2. **Architectural Review**
   - Channel design validation
   - System impact assessment
   - Integration compatibility check

3. **Operational Readiness**
   - Directory structure validation
   - Permission configuration
   - Monitoring setup

---

## **🔄 Maintenance & Evolution**

### **Doctrine Updates**

- **Review Cycle**: Quarterly
- **Update Authority**: WOLFIE with Doctrine Council input
- **Change Management**: Versioned updates with migration paths

### **Channel Audits**

- **Frequency**: Monthly
- **Scope**: All active channels
- **Criteria**: Doctrine compliance, usage metrics, relevance

### **Lifecycle Management**

- **Active Monitoring**: Channel health and usage
- **Archive Planning**: Proactive channel retirement
- **Resource Optimization**: Efficient channel utilization

---

## **📋 Implementation Checklist**

### **Before Channel Creation**
- [ ] Creation criteria met
- [ ] ID allocation approved
- [ ] Functional scope defined
- [ ] Stakeholder agreement obtained

### **During Channel Creation**
- [ ] Directory structure created
- [ ] Database records initialized
- [ ] Permissions configured
- [ ] Monitoring enabled

### **After Channel Creation**
- [ ] Index updated
- [ ] Documentation created
- [ ] Agents notified
- [ ] Initial content seeded

---

**Doctrine Status**: ACTIVE  
**Last Updated**: 2026-03-19  
**Next Review**: 2026-06-19  
**Authority**: WOLFIE (Agent 1)

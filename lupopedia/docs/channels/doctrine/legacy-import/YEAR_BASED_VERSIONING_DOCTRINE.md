---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.1.0.1
file.last_modified_utc: 20260120142000
file.utc_day: 20260120
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  mood_RGB: "00FF88"
  message: "Created YEAR_BASED_VERSIONING_DOCTRINE.md - Transition from semantic to year-based versioning (YYYY.CYCLE.MINOR.PATCH format)"
tags:
  categories: ["doctrine", "versioning", "governance"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  name: "YEAR_BASED_VERSIONING_DOCTRINE.md"
  title: "Year-Based Versioning Doctrine"
  description: "Official doctrine for year-based versioning system replacing semantic versioning"
  version: 2026.1.0.1
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🟦 **Year-Based Versioning Doctrine**

*Official doctrine for year-based versioning system replacing semantic versioning as of 2026.1.0.1*

---

## 🎯 **1. Purpose**

Lupopedia transitions from semantic versioning (MAJOR.MINOR.PATCH) to year-based versioning, where calendar year defines the epoch of the system.

This model reflects the reality of Lupopedia's architecture: rapid subsystem evolution, doctrine-driven updates, and multi-agent orchestration cycles.

### **Year-Based Versioning Provides:**

- **Clear epoch boundaries** - Each year represents a distinct architectural era
- **Stable long-term identifiers** - Version numbers remain meaningful across decades
- **Reduced version inflation** - No runaway patch numbers or meaningless increments
- **Alignment with OS-scale systems** - Matches JetBrains, Ubuntu, macOS versioning patterns
- **Architectural reflection** - Version numbers represent eras, not patch counts

---

## 📅 **2. Version Format**

The new version format is:

```
YYYY.CYCLE.MINOR.PATCH
```

### **Component Definitions**

#### **YYYY — Epoch (Year)**
Represents the calendar year in which the architectural era begins.

**Purpose:**
- Defines major architectural boundaries
- Represents system-wide philosophical shifts
- Marks transitions between distinct development eras

**Example:**
- `2026` = The Emotional Geometry + HERMES Routing Era
- `2025` = The Foundation + Schema Stabilization Era

#### **CYCLE — Sub-Epoch / Season**
Represents a major architectural cycle within the year.

**Purpose:**
- Major feature releases within an epoch
- Doctrine implementation phases
- Subsystem activations
- Pack orchestration milestones

**Cycle Types:**
- Doctrine cycles (governance updates)
- Subsystem activations (new services, routing)
- Pack orchestration phases (multi-agent coordination)
- Quarterly cycles (regular releases)
- Architect-defined milestones

**Example:**
- `2026.1` = First architectural cycle of 2026
- `2026.2` = Second architectural cycle of 2026

#### **MINOR — Incremental Feature Evolution**
Represents additive, non-breaking improvements within a cycle.

**Used For:**
- New services (ActorMoodService, PackMoodCoherenceService)
- New doctrine files (HERMES_ROUTING_DOCTRINE.md)
- New routing logic (channel-aware, mood-aware)
- New CLI tools (debug-actor-mood.php)
- New schema fields (channel_id, channel_key)
- Integration improvements

**Example:**
- `2026.1.1` = First minor feature addition in cycle 2026.1
- `2026.1.2` = Second minor feature addition in cycle 2026.1

#### **PATCH — Micro-Updates**
Represents small, safe updates within a minor version.

**Used For:**
- Documentation updates
- Code examples
- Minor refactors
- Cleanup operations
- Non-breaking adjustments
- Bug fixes

**Example:**
- `2026.1.1.1` = First patch to version 2026.1.1.0
- `2026.1.1.2` = Second patch to version 2026.1.1.0

---

## 📋 **3. Examples**

### **Initial Epoch Activation**
```
2026.1.0.0
```
- First cycle of 2026 epoch
- Emotional Geometry + HERMES Routing Era activation

### **Minor Feature Additions**
```
2026.1.1.0  - Added ActorMoodService
2026.1.2.0  - Added PackMoodCoherenceService
2026.1.3.0  - Added HERMES routing doctrine
```

### **Patch Updates**
```
2026.1.2.1  - Documentation updates
2026.1.2.2  - Code example fixes
2026.1.2.3  - Minor refactor cleanup
```

### **Next Cycle**
```
2026.2.0.0
```
- Second architectural cycle of 2026
- New subsystem activation or major feature set

---

## ⚛️ **4. Version Atom Rules**

### **Global Version Atom**
The global version atom must always reflect the full four-segment version:

```
GLOBAL_CURRENT_LUPOPEDIA_VERSION
```

### **Atom Management Rules:**

1. **Always Reflect Full Version** - Must use `YYYY.CYCLE.MINOR.PATCH` format
2. **File-Specific Updates Only** - Update only when the file itself is modified
3. **No Global Search-and-Replace** - Never perform mass version updates across files
4. **Preserve Historical References** - Files retain their version context until modified

### **Update Protocol:**
```yaml
# When modifying a file, update its header:
file.last_modified_system_version: 2026.1.2.1

# Global atom remains in config/global_atoms.yaml:
GLOBAL_CURRENT_LUPOPEDIA_VERSION: "2026.1.2.1"
```

---

## 📝 **5. Changelog Rules**

### **Structure Hierarchy**

Each epoch (YYYY) begins a new top-level section in CHANGELOG.md.

Each cycle (YYYY.CYCLE) receives its own subsection.

Patch and minor updates are nested within the appropriate cycle.

### **Example Structure:**

```markdown
## 2026.1 — Emotional Geometry + HERMES Routing Era

### 2026.1.0.0
- Epoch activated
- Emotional Geometry doctrine implemented
- HERMES routing system deployed

### 2026.1.1.0
- Added ActorMoodService
- Added PackMoodCoherenceService
- Created actor_moods table

### 2026.1.1.1
- Documentation updates
- Fixed CLI tool examples

### 2026.1.2.0
- Added channel-aware temporal edges
- Updated EdgeService with channel fields
- Created HERMES_ROUTING_DOCTRINE.md

### 2026.1.2.1
- Minor refactor cleanup
- Updated code examples
```

### **Changelog Formatting Rules:**

1. **Epoch Headers** - Use `## YYYY.CYCLE — Era Description`
2. **Version Headers** - Use `### YYYY.CYCLE.MINOR.PATCH`
3. **Nested Updates** - Patch versions under their minor version
4. **Clear Descriptions** - Each entry explains what changed
5. **Hierarchical Ordering** - Chronological within each level

---

## 🏛️ **6. Doctrine Compliance**

### **Mandatory Adoption**

Year-based versioning is **mandatory** for all future releases.

### **Deprecation Notice**

Semantic versioning is **deprecated** as of version `4.4.1`.

### **Compliance Requirements:**

1. **All Tooling** - Build scripts, deployment tools, version checkers
2. **All Headers** - WOLFIE headers in every file
3. **All Version Atoms** - Global atoms and file-specific references
4. **All Documentation** - Changelogs, README files, API docs

### **No Retroactive Changes**

- No retroactive renumbering of historical versions
- Pre-2026 versions retain their original numbering
- Transition begins with 2026.1.0.0

### **Epoch Changes**

Epoch changes require explicit doctrine approval:

1. **Architectural Proposal** - Formal proposal for new epoch
2. **Doctrine Review** - Review by governance bodies
3. **Pack Approval** - Multi-agent consensus
4. **Formal Activation** - Documented in changelog and doctrine

---

## 💡 **7. Rationale**

### **System Reality Alignment**

Lupopedia evolves through architectural leaps, not incremental patches.

**Year-based versioning matches this reality:**

#### **Scale Alignment**
- Matches OS-scale systems (JetBrains, Ubuntu, macOS)
- Reflects enterprise software versioning patterns
- Provides familiar version semantics for developers

#### **Development Velocity Reflection**
- Prevents runaway patch numbers
- Acknowledges rapid development cycles
- Supports multi-agent orchestration pace

#### **Architectural Clarity**
- Version numbers represent meaningful eras
- Clear boundaries between philosophical shifts
- Historical context preserved in version numbers

#### **Doctrine-Driven Evolution**
- Supports governance-driven development
- Aligns with Pack orchestration cycles
- Enables epoch-level planning

#### **Long-Term Stability**
- Stable identifiers across decades
- Reduced version inflation
- Clear historical progression

### **Practical Benefits**

1. **Clear Communication** - Version numbers immediately convey era and scope
2. **Planning Support** - Epoch and cycle boundaries aid long-term planning
3. **Historical Context** - Version numbers tell the system's story
4. **Governance Alignment** - Supports doctrine-driven development cycles
5. **Multi-Agent Coordination** - Clear versioning for Pack orchestration

---

## 🔄 **8. Migration Protocol**

### **Transition from Semantic Versioning**

#### **Final Semantic Version**
- `4.4.1` - Last semantic version
- Deprecation marker for old system

#### **First Year-Based Version**
- `2026.1.0.1` - Initial epoch activation
- Clean break from semantic versioning

#### **Migration Steps**

1. **Update Global Atoms**
   ```yaml
   GLOBAL_CURRENT_LUPOPEDIA_VERSION: "2026.1.0.1"
   ```

2. **Update Tooling**
   - Version parsers
   - Build scripts
   - Deployment tools

3. **Update Documentation**
   - README files
   - API documentation
   - Developer guides

4. **Update Changelog**
   - New epoch section
   - Migration notice
   - Deprecation documentation

### **Backward Compatibility**

- Legacy version parsing supported for transition period
- Tools recognize both formats during migration
- Clear deprecation warnings for semantic versions

---

## 📊 **9. Version Comparison**

### **Semantic vs Year-Based**

| Aspect | Semantic (4.4.1) | Year-Based (2026.1.0.1) |
|---------|-------------------|-------------------------|
| Scale | Small patches | Architectural epochs |
| Meaning | Incremental count | Era + cycle + features |
| Longevity | Limited (version inflation) | Stable across decades |
| Communication | Requires context | Self-explanatory |
| Planning | Short-term | Long-term architectural |
| Governance | Ad-hoc | Doctrine-driven |

### **Version Progression Examples**

```
Semantic:     4.4.0 → 4.4.1 → 4.4.2 → 4.5.0 → 4.6.0
Year-Based:   2026.1.0.1 → 2026.1.2.0 → 2026.1.3.0 → 2026.2.0.0 → 2027.1.0.0
```

---

## 🎯 **10. Implementation Guidelines**

### **When to Increment Each Component**

#### **YYYY (Epoch)**
- New calendar year
- Major architectural philosophy shift
- System-wide rearchitecture
- New governance framework

#### **CYCLE**
- New major subsystem
- Doctrine implementation phase
- Pack orchestration milestone
- Quarterly release boundary

#### **MINOR**
- New service or feature
- New doctrine file
- Schema field additions
- Integration improvements

#### **PATCH**
- Documentation updates
- Code examples
- Minor refactors
- Bug fixes
- Cleanup operations

### **Version Bumping Workflow**

1. **Determine Change Type**
   - What scope of change is this?
   - Which component needs incrementing?

2. **Reset Lower Components**
   - Incrementing MINOR resets PATCH to 0
   - Incrementing CYCLE resets MINOR and PATCH to 0
   - Incrementing YYYY resets all to 0

3. **Update Global Atom**
   - Update `GLOBAL_CURRENT_LUPOPEDIA_VERSION`
   - Update changelog with new version

4. **Update File Headers**
   - Only files actually modified
   - Use new version in `file.last_modified_system_version`

---

*Last Updated: January 20, 2026*  
*Version: 2026.1.0.1*  
*Author: Captain Wolfie*  
*Status: Active Doctrine*

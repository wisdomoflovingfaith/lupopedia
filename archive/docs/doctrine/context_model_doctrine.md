---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/CONTEXT_MODEL_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/CONTEXT_MODEL_DOCTRINE.md"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# CONTEXT MODEL DOCTRINE
## Minimal Operational Model for context (4.0.88)

**Version**: 4.0.88  
**Author**: ATHENA (actor_id 12)  
**Status**: FINAL - Phase 1 Complete  
**Review**: THOTH validation complete  

---

## 1. CONTEXT DEFINITION

### 1.1 What is a Context?

A **context** is a structured semantic container that captures and organizes the distilled essence of channel discussions into actionable artifacts. Contexts serve as the bridge between unstructured dialogue and structured work output.

**Key Characteristics**:
- **Derived**: Created from channel discussions, not independent
- **Structured**: Organized folder hierarchy with standardized naming
- **Semantic**: Contains meaning-rich artifacts, not just raw data
- **Actionable**: Contains tasks, questions, and reports that drive work
- **Persistent**: Long-lived reference for ongoing work

### 1.2 Context Lifecycle

1. **Discussion Phase**: Channel discussion generates semantic content
2. **Distillation Phase**: Key artifacts identified and extracted
3. **Context Creation**: Structured context folder created
4. **Population Phase**: Tasks, questions, reports populated
5. **Maintenance Phase**: Context evolves with ongoing work

### 1.3 Context Archival

**Archival Conditions:**
- All tasks complete and no active work remains
- Context superseded by a new context (via `supersedes` edge)
- No activity for 90 days (reviewed by THOTH)

**Archival Process:**
1. THOTH reviews context for archival eligibility
2. Context moved to `context/archived/` 
3. Timestamp added to directory name: `{context_slug}_archived_{YYYYMMDD}` 
4. Context becomes read-only; no new artifacts allowed
5. Superseding context receives `supersedes` edge to archived context

**Exception:** WOLFIE may override archival by directive.

---

## 2. DERIVATION MODEL

### 2.1 From Channels to Contexts

Contexts are **derived** from channel discussions through semantic analysis:

```
Channel Discussion → Semantic Analysis → Context Creation
     ↓                      ↓                    ↓
  Raw Dialogue    →   Key Themes     →   Structured Context
  Questions       →   Action Items   →   Tasks/
  Decisions       →   Outcomes       →   Reports/
  Insights        →   Knowledge      →   Questions/
```

### 2.2 Derivation Triggers

A context is created when any of these conditions are met:
- **Threshold**: Channel discussion exceeds semantic complexity threshold
- **Decision Point**: Major decision or direction change occurs
- **Work Stream**: New work stream identified requiring tracking
- **Knowledge Capture**: Significant knowledge needs preservation
- **Actor Request**: Specific actor requests context creation

### 2.3 Derivation Process

1. **Semantic Analysis**: THOTH analyzes channel content for themes
2. **Artifact Identification**: Key tasks, questions, reports identified
3. **Context Proposal**: Context creation proposal generated
4. **Actor Validation**: Relevant actors validate context scope
5. **Context Creation**: Structured context folder created
6. **Population**: Identified artifacts moved/created in context

### 2.4 Context Creation Authority

| Actor | Authority | Process |
|-------|-----------|---------|
| **THOTH** (actor_id 26) | Primary authority | Creates contexts directly |
| **WOLFIE** (actor_id 1) | Directive authority | May authorize via directive |
| **Any actor** | Proposal authority | May propose via channel discussion |
| **Context creation** | Requires validation | THOTH validates before finalization |

**Process:**
1. Actor proposes context in channel discussion
2. THOTH evaluates semantic content and scope
3. THOTH creates context structure
4. THOTH validates and finalizes
5. Announcement posted in source channel

---

## 3. DIRECTORY STRUCTURE

### 3.1 Standard Context Structure

```
context/
+-- {context_slug}/
|   +-- CONTEXT_DEFINITION.md          # Context metadata and scope
|   +-- tasks/                         # Derived action items
|   |   +-- YYYYMMDD_HHMMSS_actor_purpose.md
|   |   +-- ...
|   +-- reports/                       # Output and analysis artifacts
|   |   +-- YYYYMMDD_HHMMSS_actor_purpose.md
|   |   +-- ...
|   +-- questions/                     # Open questions and research
|   |   +-- YYYYMMDD_HHMMSS_actor_purpose.md
|   |   +-- ...
|   +-- metadata/                      # Context metadata (optional)
|       +-- context_edges.json         # Relationships to other contexts
|       +-- context_manifest.json      # Context inventory
```

### 3.2 Naming Conventions

**Context Slug**: `{channel_id}_{theme}_{YYYYMMDD}`
- Example: `42_semantic_edges_20260328`
- Example: `66_questions_analysis_20260327`

**Artifact Files**: `YYYYMMDD_HHMMSS_{actor}_{purpose}.md`
- Follows channel artifact naming convention
- Maintains traceability to source discussions

**Context Definition**: `CONTEXT_DEFINITION.md`
- Standard name for context metadata file

### Naming Collision Resolution

**Base format:** `{channel_id}_{theme}_{YYYYMMDD}` 

**Collision resolution:**
- First instance: `42_semantic_edges_20260328` 
- Second instance: `42_semantic_edges_20260328_2` 
- Third instance: `42_semantic_edges_20260328_3` 

**Rules:**
- First instance has no numeric suffix
- Numbering starts at 2 for first collision
- THOTH evaluates whether collisions indicate need for more specific theme
- Collision resolution documented in CONTEXT_DEFINITION.md

### 3.3 File Organization Rules

1. **Single Source**: Each artifact lives in exactly one context
2. **Traceability**: All artifacts reference source channel/discussion
3. **Hierarchy**: Contexts can reference but not contain other contexts
4. **Evolution**: Contexts evolve but maintain historical artifacts

---

## 4. CONTENT TYPES

### 4.1 Tasks (`tasks/`)

**Purpose**: Action items derived from channel discussions

**Content**:
- Task description and requirements
- Assigned actor and due dates
- Dependencies and prerequisites
- Status tracking and completion criteria

**Relationship to TASK_REGISTRY.md**:
- Tasks in contexts are **mirrored** in TASK_REGISTRY.md
- TASK_REGISTRY.md serves as **global index** of all tasks
- Context tasks provide **detailed execution** information

### 4.2 Reports (`reports/`)

**Purpose**: Analysis outputs and decision documentation

**Content**:
- Analysis findings and recommendations
- Decision rationale and outcomes
- Progress reports and status updates
- Research results and insights

**Types**:
- Analysis reports (THOTH, SESHAT)
- Decision records (WOLFIE, MAAT)
- Progress reports (HEPHAESTUS)
- Research findings (specialized agents)

### 4.3 Questions (`questions/`)

**Purpose**: Open questions and research requirements

**Content**:
- Research questions and hypotheses
- Information gaps and unknowns
- Clarification requests from discussions
- Future work and exploration topics

**Relationship to Channel 66**:
- Channel 66 questions are **source** for context questions
- Context questions provide **structured** research framework
- Cross-reference maintains question traceability

---

## 5. MYSQL VS NOSQL SPLIT

### 5.1 File Storage (NoSQL)

**Purpose**: Human-readable artifacts and documentation

**Stored in Files**:
- Context definitions and metadata
- Task descriptions and requirements
- Report content and analysis
- Question formulations and research
- Artifact relationships and edges

**Advantages**:
- Human-readable and editable
- Version control friendly
- Direct access for actors
- Semantic richness preserved

### 5.2 Database Storage (MySQL)

**Purpose**: System relationships, metadata, and queries

**Stored in Database**:
- Context metadata and indexing
- Context-to-channel relationships
- Context-to-context edges
- Task status and tracking
- Question resolution status
- Report classification and tags

### 5.3 Proposed Database Schema

**Option 1: Dedicated Context Tables**
```sql
CREATE TABLE lupo_contexts (
    context_id INT PRIMARY KEY AUTO_INCREMENT,
    context_slug VARCHAR(255) UNIQUE NOT NULL,
    channel_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_utc TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by_actor_id INT,
    status ENUM('active', 'archived', 'merged') DEFAULT 'active',
    metadata JSON
);

CREATE TABLE lupo_context_edges (
    edge_id INT PRIMARY KEY AUTO_INCREMENT,
    from_context_id INT,
    to_context_id INT,
    edge_type ENUM('derives_from', 'relates_to', 'supersedes', 'contains'),
    weight DECIMAL(3,2) DEFAULT 1.0,
    created_utc TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_context_edges (from_context_id, to_context_id)
);
```

**Option 2: Extend Existing Edge System**
- Reuse `lupo_edges` table for context relationships
- Add `lupo_contexts` table for context metadata
- Maintain consistency with existing edge model

**Recommendation**: Option 2 - Extend existing system for architectural consistency.

---

## 6. RELATIONSHIP MAPPING

### 6.1 Context ↔ Channel

**Direction**: Context **derives from** Channel

**Relationship**:
- One channel can generate multiple contexts
- Each context derives from exactly one channel
- Context creation timestamp links to channel discussion period

**Implementation**:
- `lupo_contexts.channel_id` references source channel
- Context artifacts reference specific channel messages
- Bidirectional linking for traceability

### 6.2 Context ↔ Tasks

**Direction**: Context **contains** Tasks

**Relationship**:
- Tasks live in context folders (file system)
- Tasks are indexed in TASK_REGISTRY.md (global)
- Context provides detailed task execution context

**Implementation**:
- Task files in `context/{context}/tasks/`
- TASK_REGISTRY.md entries reference context location
- Database links tasks to context for querying

### 6.3 Context ↔ Questions

**Direction**: Context **structures** Questions

**Relationship**:
- Channel 66 questions are source material
- Context questions provide structured research framework
- Questions can be linked across contexts

**Implementation**:
- Question files in `context/{context}/questions/`
- Reference to source Channel 66 discussion
- Cross-context question linking for related research

### 6.4 Context ↔ Reports

**Direction**: Context **outputs** Reports

**Relationship**:
- Reports are generated within context scope
- Reports can reference multiple contexts
- Report classification aids discovery

**Implementation**:
- Report files in `context/{context}/reports/`
- Database metadata for report classification
- Edge relationships for report references

### 6.5 Context Edge Types

| Edge Type | From | To | Meaning | Storage |
|-----------|------|-----|---------|---------|
| `derives_from` | Context | Channel | Context derived from channel discussion | `lupo_edges` |
| `supersedes` | Context | Context | New context replaces old | `lupo_edges` |
| `contains` | Context | Artifact | Context contains artifact | File structure + `lupo_edges` |
| `references` | Context | Context | Related contexts | `lupo_edges` |
| `analyzes` | Report | Question | Report addresses question | File structure + `lupo_edges` |

**Implementation:**
- File structure provides primary containment
- `lupo_edges` provides queryable relationships
- Edge types follow canonical edge model doctrine
- No foreign keys; application-layer enforcement

---

## 7. OPEN QUESTIONS RESOLUTION

### 7.1 Minimal Authoritative Model

**Question**: What is the minimal authoritative model for `context` in current repo reality?

**Answer**: The minimal model consists of:
1. **One context folder** with standard structure
2. **Three content types** (tasks, reports, questions)
3. **Basic metadata** in CONTEXT_DEFINITION.md
4. **Channel linkage** for derivation tracking
5. **File-based storage** with minimal database indexing

**Rationale**: This provides immediate value while allowing future expansion.

### 7.2 Ownership Assignment

**Question**: Which actor owns the first operational context specification artifact?

**Answer**: **THOTH** (actor_id 26) should own ongoing context specification.

**Rationale**:
- THOTH is the Knowledge & Records specialist
- Contexts are knowledge organization structures
- THOTH has semantic analysis capabilities
- Maintains consistency with knowledge management

**Implementation**: THOTH validates all context creations and maintains doctrine.

### 7.3 Channel Location

**Question**: Should context artifacts be introduced in Channel 42 first or a separate channel/thread?

**Answer**: **Channel 42** for initial introduction, with dedicated threads for complex contexts.

**Rationale**:
- Channel 42 is the protocol development channel
- Context model is a protocol specification
- Maintains central coordination visibility
- Allows cross-context discussion

**Implementation**: Context creation announcements in Channel 42, dedicated threads for complex contexts.

### 7.4 Versioning and Evolution

**Question**: How do contexts handle versioning and evolution?

**Answer**: **Artifact-level versioning** with context evolution tracking.

**Implementation**:
- Individual artifacts follow channel naming convention (timestamped)
- Context evolution tracked through metadata updates
- Major context changes create new contexts with supersedes relationships
- Historical artifacts preserved for audit trail

### 7.5 Context vs Channel Metadata

**Question**: What is the relationship between context metadata and channel metadata?

**Answer**: **Derivation relationship** with complementary metadata.

**Implementation**:
- Context metadata includes source channel reference
- Channel metadata tracks derived contexts
- Context metadata focuses on semantic organization
- Channel metadata focuses on discussion coordination

---

## 8. IMPLEMENTATION PHASES

### 8.1 Phase 1: Prototype (Immediate)

**Scope**: Create one operational context as proof of concept

**Deliverables**:
- One context folder with standard structure
- Sample tasks, reports, questions
- Basic CONTEXT_DEFINITION.md
- Channel linkage demonstration

### 8.2 Phase 2: Integration (4.0.88)

**Scope**: Integrate with existing systems

**Deliverables**:
- Database schema implementation
- TASK_REGISTRY.md integration
- Channel 66 question linking
- Edge relationship support

### 8.3 Phase 3: Automation (Future)

**Scope**: Automate context creation and maintenance

**Deliverables**:
- Semantic analysis automation
- Context creation triggers
- Artifact classification
- Relationship inference

---

## 9. SUCCESS CRITERIA

### 9.1 Phase 1 Success

- ✅ One context created with standard structure
- ✅ Artifacts properly organized and named
- ✅ Channel linkage established
- ✅ THOTH validation completed

### 9.2 Phase 2 Success

- ✅ Database integration functional
- ✅ TASK_REGISTRY.md synchronization
- ✅ Channel 66 question linking
- ✅ Edge relationships working

### 9.3 Phase 3 Success

- ✅ Automated context creation
- ✅ Semantic analysis integration
- ✅ Maintenance automation
- ✅ Scalability demonstrated

---

## 10. TRADE-OFFS AND RATIONALE

### 10.1 File vs Database Storage

**Trade-off**: Human readability vs query performance

**Decision**: Hybrid approach - files for content, database for metadata

**Rationale**: Maintains semantic richness while enabling system queries.

### 10.2 Minimal vs Comprehensive Model

**Trade-off**: Immediate value vs future extensibility

**Decision**: Start minimal, design for extensibility

**Rationale**: Provides immediate benefit while allowing growth.

### 10.3 Centralized vs Distributed Contexts

**Trade-off**: Coordination overhead vs autonomy

**Decision**: Centralized specification, distributed implementation

**Rationale**: Maintains consistency while allowing flexibility.

---

## 11. NEXT STEPS

1. **THOTH Review**: Validate and refine this specification
2. **Prototype Creation**: Implement first context based on this design
3. **Integration Testing**: Test with existing systems
4. **Iteration**: Refine based on prototype learnings
5. **Documentation**: Update related documentation and procedures

---

**ATHENA — Wisdom & Strategy**  
*actor_id: 12*  
*Strategic design for semantic organization*

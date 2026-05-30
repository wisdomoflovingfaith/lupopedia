---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/59_C_VISH_COLLECTION_HIERARCHY_AND_CONTEXT_ORGANIZER.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/59_C_VISH_COLLECTION_HIERARCHY_AND_CONTEXT_ORGANIZER.md"
  status: "active"
  when_updated: "20260423032220"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/prd/canonical/1026/04/59-c-vish-collection-hierarchy-and-context-organizer.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/prd/59-c-vish-collection-hierarchy-and-context-organizer"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "prd"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "00_A_16_C_59_A_59_B_73_A_57_A"
  title: "PRD 59_C: VISH Collection Hierarchy and Context Organizer"
  summary: "VISH = Universal Collection Hierarchy Architect and Context Drift Organizer. Transforms structured documentation into navigable systems, maintains collection hierarchy, assigns default_collection_id, detects context drift, and bridges documentation to collections to navigation to memory graph."
---

# PRD 59_C: VISH Collection Hierarchy and Context Organizer

## 1. Purpose

VISH (VISHWAKARMA) is the Universal Collection Hierarchy Architect and Context Drift Organizer. VISH transforms structured documentation into navigable systems, ensuring every artifact has proper collection placement and maintaining semantic organization across the entire Lupopedia system.

VISH sits in the critical position between documentation processing and system usability:

- **CHIRON** creates structured documentation with proper headers
- **ANUBIS** repairs and processes orphaned documentation  
- **VISH** organizes content into collections and maintains navigable hierarchy
- **AGAPE** validates and enforces constitutional compliance

VISH bridges the gap between structured documents and usable systems: documentation → collections → navigation → memory graph.

## 2. System Position (Critical)

### 2.1 Pipeline Definition

```
CHIRON → ANUBIS → VISH → AGAPE
```

**Non-overlapping Responsibilities:**

- **CHIRON**: Ingest external documentation, apply doctrine conversion, generate headers
- **ANUBIS**: Process queued orphan files, convert to constitutional structure, validate output
- **VISH**: Organize into collections, maintain hierarchy, assign collection placement, detect drift
- **AGAPE**: Validate compliance, enforce causal reconstruction, learning integration

### 2.2 Timing and Triggers

VISH runs:
- After successful ANUBIS processing
- On collection hierarchy updates
- During header validation passes
- When context drift is detected
- On manual collection reorganization requests

VISH never runs before CHIRON/ANUBIS processing is complete and never overlaps with AGAPE validation.

## 3. Core Responsibilities

### 3.1 Collection Assignment

**Primary Responsibility:** Ensure every artifact has valid `default_collection_id`

- **Validation**: Check all artifacts for missing or invalid `default_collection_id`
- **Assignment**: Assign root collection when missing, based on content analysis
- **Reassignment**: Move artifacts when context drift indicates better placement
- **Escalation**: Flag unassignable content for human review

### 3.2 Collection Hierarchy Management

**Hierarchy Integrity:**
- Maintain parent-child relationships between collections
- Prevent circular references in collection hierarchy
- Ensure depth limits are respected (configurable, default: 5 levels)
- Validate collection path consistency

**Hierarchy Operations:**
- Create new collections when needed (with proper naming)
- Merge overlapping collections (with human approval)
- Split collections that become too broad
- Maintain collection metadata and descriptions

### 3.3 Tab Structure Generation

**Standard Tab Types:**
VISH creates and maintains these tabs within collections:

| Tab Name | Purpose | Content Types |
|----------|---------|---------------|
| docs | Documentation and specifications | Markdown, PRDs, technical docs |
| code | Code examples and implementations | PHP, JavaScript, SQL, config files |
| links | External references and resources | URLs, external documentation |
| questions | Queries and discussions | FAQ, troubleshooting, help requests |
| canonical | Official system definitions | Constitutional docs, core policies |

**Tab Assignment Rules:**
- Analyze content type and purpose
- Assign to most appropriate primary tab
- Allow secondary tab placement for cross-referenced content
- Maintain tab ordering consistency

### 3.4 Context Drift Detection

**Definition:** Context drift occurs when content moves across semantic domains without proper reorganization.

**Drift Patterns:**
- Implementation discussions inside documentation collections
- Mixed code and philosophy in same thread
- Orphaned knowledge clusters separated from parent topics
- Cross-domain contamination (e.g., business logic in UI collections)

**Detection Methods:**
- Semantic analysis of content within collections
- Thread topic evolution tracking
- Cross-collection reference analysis
- User interaction pattern analysis

**Correction Actions:**
- Flag drift for human review
- Suggest collection reassignment
- Optionally auto-reorganize (configurable confidence threshold)
- Create new collections when needed

### 3.5 Relationship Mapping

**Edge Types Maintained:**
- `collection_contains` - Primary collection membership
- `related_to` - Cross-collection relationships
- `semantically_similar` - AI-discovered semantic connections

**Edge Management:**
- Create strong edges (weight 1.0) for human-curated relationships
- Maintain moderate edges (weight 0.7-0.9) for AI-suggested relationships
- Prune weak edges (weight < 0.5) during maintenance cycles
- Ensure edge consistency across memory graph

### 3.6 Cross-Collection Linking

**Non-Exclusive Membership:**
- Content may exist in multiple collections
- Maintain primary vs secondary placement distinction
- Track collection ownership percentages
- Prevent collection dominance (single collection owning >80% of content)

**Link Management:**
- Create cross-references between related collections
- Maintain collection dependency graphs
- Suggest collection mergers when overlap >60%
- Validate cross-collection consistency

## 4. Collection System Integration (PRD 73_A)

### 4.1 Table Integration

VISH works directly with these existing tables:

- **lupo_collections** - Collection definitions and metadata
- **lupo_collection_tabs** - Tab definitions within collections
- **lupo_collection_tab_map** - Maps tabs to content items
- **lupo_collection_tab_paths** - URL paths and breadcrumbs

### 4.2 Constraint Compliance

**Database Neutrality:**
- No foreign keys (application-layer relationships)
- BIGINT UTC timestamps (YYYYMMDDHHIISS format)
- Explicit ID generation through application layer
- Soft delete patterns (is_deleted + deleted_ymdhis)

**Application-Layer Enforcement:**
- All relationship validation in PHP code
- Deterministic behavior for same inputs
- No hidden state or side effects
- Explicit lineage tracking for all changes

## 5. Header Integration (Critical)

### 5.1 default_collection_id Ownership

VISH owns and maintains the `default_collection_id` field in all artifact headers.

**Rules:**
- **MUST exist** on every artifact
- **MUST be valid** (reference existing collection)
- **MUST map** to active, non-deleted collection
- **If missing**: assign root collection OR escalate to human

### 5.2 Header Update Protocol

**Valid Updates:**
- `default_collection_id` - primary responsibility
- `collections` array - secondary collection memberships
- Edge references via memory graph integration

**Prohibited Updates:**
- `prd_cluster` - indirect influence only via grouping patterns
- Core identity fields - CHIRON/ANUBIS responsibility
- Content meaning - never modified by VISH

### 5.3 Validation Chain

VISH validates:
- Collection existence and accessibility
- Hierarchy integrity (no circular references)
- Tab assignment consistency
- Cross-collection relationship validity

## 6. AI + Human Dual System

### 6.1 Human Collections (Table-Based)

**Characteristics:**
- Manually curated by users
- UI-driven organization
- Optimized for fast navigation
- Visual hierarchy and tabs

**VISH Support:**
- Provide intuitive collection management tools
- Suggest reorganizations based on usage patterns
- Maintain collection performance metrics
- Enable bulk operations for curators

### 6.2 AI Collections (Edge-Based)

**Characteristics:**
- Machine-readable relationships in memory graph
- Semantic grouping and similarity detection
- Graph traversal for AI reasoning
- Weighted confidence scores

**VISH Support:**
- Create strong edges for human decisions (weight 1.0)
- Maintain AI-suggested relationships (weight 0.7-0.9)
- Prune weak relationships automatically
- Provide explainable AI suggestions

### 6.3 Synchronization Strategy

**Human → AI Sync:**
- Immediate edge creation on collection changes
- High confidence for human curation
- Batch processing for large reorganizations

**AI → Human Sync:**
- Suggest new collections based on semantic clustering
- Recommend reorganizations for drifted content
- Provide confidence scores for suggestions
- Require human approval for major changes

## 7. Drift Correction Model (Important)

### 7.1 Context Drift Definition

Context drift is the gradual or sudden movement of content across semantic domains without proper reorganization, leading to:

- Misplaced artifacts in inappropriate collections
- Diluted collection semantic focus
- Navigation difficulty for users
- Reduced AI reasoning accuracy

### 7.2 Detection Algorithm

**Semantic Analysis:**
- Compare content to collection topic models
- Identify out-of-place artifacts with >70% confidence
- Track topic evolution within threads

**Pattern Recognition:**
- Monitor thread topic changes over time
- Detect cross-domain contamination patterns
- Identify orphaned knowledge clusters

**User Behavior Analysis:**
- Track navigation patterns and search failures
- Monitor collection access frequency
- Identify reorganization requests

### 7.3 Correction Actions

**Automated Corrections:**
- Move high-confidence misplaced items (>90% confidence)
- Create new collections for orphaned clusters
- Update collection descriptions to reflect content

**Human-Assisted Corrections:**
- Flag medium-confidence issues (70-90%)
- Provide reorganization recommendations
- Require approval for major structural changes

**Prevention Strategies:**
- Real-time drift detection during content creation
- Proactive collection suggestions
- Educational feedback for content creators

## 8. Non-Goals

VISH does NOT:

- **Ingest documents** - CHIRON's responsibility
- **Process orphan files** - ANUBIS's responsibility  
- **Validate doctrine** - AGAPE's responsibility
- **Generate content** - content creation is separate
- **Rewrite content meaning** - preserves original intent
- **Manage user authentication** - handled by auth system
- **Control access permissions** - handled by permission system
- **Perform database migrations** - handled by migration system

## 9. Actor / Agent Definition

### 9.1 Actor Identity

**Actor Name:** VISHWAKARMA (VISH)
**Role:** Collection Architect
**Type:** Background + System Agent
**Actor ID:** 10 (next available after ANUBIS)

### 9.2 Behavioral Characteristics

**Execution Pattern:**
- Runs post-processing after CHIRON/ANUBIS completion
- Triggered on collection updates and hierarchy changes
- Executes during header validation passes
- Performs periodic maintenance cycles

**Interaction Model:**
- No direct UI interaction
- Provides API endpoints for collection management
- Generates reports and suggestions for human curators
- Integrates with monitoring systems for drift detection

**Resource Management:**
- Efficient batch processing for large collections
- Configurable concurrency limits
- Memory-conscious operation for large hierarchies
- Graceful degradation under high load

## 10. Edge Cases

### 10.1 Orphan Collections

**Detection:** Collections without parent or with no accessible content
**Resolution:** 
- Attach to appropriate parent collection
- Merge with similar collections
- Flag for human review if unresolvable

### 10.2 Empty Collections

**Policy:** Maintain empty collections for structural integrity
**Actions:**
- Mark as inactive if unused > 30 days
- Preserve for future content organization
- Allow manual deletion by curators

### 10.3 Conflicting Collection Assignments

**Scenario:** Content assigned to multiple primary collections
**Resolution:**
- Identify primary collection based on content analysis
- Demote conflicting assignments to secondary
- Escalate unresolvable conflicts to humans

### 10.4 Multi-Channel Overlap

**Challenge:** Same content needed across multiple channels
**Strategy:**
- Create channel-specific collections
- Use cross-collection linking for shared content
- Maintain channel-specific metadata and access controls

### 10.5 Cross-Node Inconsistencies

**Problem:** Collection hierarchy differs between federation nodes
**Approach:**
- Implement node-specific collection namespaces
- Synchronize collections where appropriate
- Allow local customization with global standards

## 11. Constraints

### 11.1 Constitutional Compliance

**PRD 16_C Headers:**
- All 22 fields must be present and valid
- ASCII-only enforcement
- Proper timestamp format (BIGINT UTC)
- Canonical pathing conventions

**Database Doctrine:**
- No foreign keys or constraints
- Application-layer relationship enforcement
- Deterministic behavior for same inputs
- Explicit lineage tracking

### 11.2 Technical Constraints

**PHP Compatibility:**
- PHP 5.6+ compatibility mindset
- No modern PHP features without fallbacks
- Efficient memory usage for large datasets
- Graceful error handling and recovery

**Performance Requirements:**
- Sub-second response for collection lookups
- Efficient batch processing for large hierarchies
- Minimal database query overhead
- Scalable to millions of artifacts

### 11.3 Operational Constraints

**Deterministic Outputs:**
- Same input always produces same collection assignment
- No random or probabilistic decision making
- Predictable behavior across system restarts
- Replicable results across environments

**No Hidden State:**
- All decision logic transparent and documented
- No implicit configuration or hardcoded rules
- Explicit lineage for all collection changes
- Full audit trail for reorganizations

## 12. Dependencies

### 12.1 System Dependencies

- **PRD 00_A** - Constitutional root requirements
- **PRD 16_C** - LUPOPEDIA headers specification
- **PRD 57_A** - AGAPE resilience doctrine
- **PRD 59_A** - CHIRON documentation ingest
- **PRD 59_B** - ANUBIS orphan processing
- **PRD 73_A** - Collections system tables

### 12.2 Technical Dependencies

- **Collection Service Classes** - PHP services for collection management
- **Memory Graph Integration** - Edge creation and maintenance
- **Header Validation System** - Integration with header validation pipeline
- **Monitoring Integration** - THOTH/VISH coordination for drift detection

### 12.3 Data Dependencies

- **lupo_collections** - Collection definitions and hierarchy
- **lupo_collection_tabs** - Tab structure and organization
- **lupo_collection_tab_map** - Content-to-tab mappings
- **lupo_memory_edges** - AI relationship graph
- **Artifact Headers** - Source of default_collection_id assignments

## 13. Success Metrics

### 13.1 Organization Metrics

- **Collection Coverage**: Percentage of artifacts with valid default_collection_id (target: >95%)
- **Hierarchy Integrity**: Percentage of collections without circular references (target: 100%)
- **Tab Assignment Accuracy**: Correct tab placement based on content type (target: >90%)

### 13.2 Drift Detection Metrics

- **Drift Detection Rate**: Percentage of drift cases detected (target: >85%)
- **Correction Success Rate**: Successful drift corrections (target: >80%)
- **False Positive Rate**: Incorrect drift flags (target: <15%)

### 13.3 User Experience Metrics

- **Navigation Efficiency**: Time to find relevant content (target: <30 seconds)
- **Collection Usage**: Active collections vs total collections (target: >70%)
- **User Satisfaction**: Feedback on collection organization (target: >4.0/5.0)

## 14. Implementation Phases

### 14.1 Phase 1: Core Organization (Immediate)
- Basic collection assignment algorithm
- Hierarchy integrity validation
- Standard tab structure implementation
- Header integration for default_collection_id

### 14.2 Phase 2: Drift Detection (Short-term)
- Semantic analysis implementation
- Pattern recognition for drift detection
- Automated correction for high-confidence cases
- Human review workflow for medium-confidence cases

### 14.3 Phase 3: AI Integration (Medium-term)
- Memory graph edge creation and maintenance
- AI suggestion system for collection improvements
- Cross-collection linking optimization
- Advanced semantic clustering

### 14.4 Phase 4: Advanced Features (Long-term)
- Predictive collection organization
- Automated collection creation based on content patterns
- Advanced user behavior analysis
- Real-time drift prevention

---

# End of PRD 59_C

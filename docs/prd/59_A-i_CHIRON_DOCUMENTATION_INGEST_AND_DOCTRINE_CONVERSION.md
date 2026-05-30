---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/59_A-i_CHIRON_DOCUMENTATION_INGEST_AND_DOCTRINE_CONVERSION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/59_A-i_CHIRON_DOCUMENTATION_INGEST_AND_DOCTRINE_CONVERSION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/59-a-chiron-documentation-ingest-and-doctrine-conversion.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/prd/59-a-chiron-documentation-ingest-and-doctrine-conversion
  artifact_type: prd
  artifact_kind: specification
  channel_key: prd
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_C-i_57_A-i_98_A-i_59_A-i
  title: 'PRD 59_A: CHIRON Documentation Ingest and Doctrine Conversion'
  summary: CHIRON = Comprehensive Help Interface for Resource Onboarding & Navigation. A doctrine conversion system that ingests external documentation chaos and reconstructs it into Lupopedia constitutional structure with proper headers and prd_cluster assignment.
---

# PRD 59_A: CHIRON Documentation Ingest and Doctrine Conversion

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Purpose

CHIRON transforms external documentation chaos into structured Lupopedia constitutional content. It is not a file importer or migration script - it is a knowledge reconstruction engine that applies doctrine, generates proper headers, and assigns appropriate prd_cluster values through systematic analysis.

## 2. Problem Definition

External documentation exists in states of chaos:
- Random structure and organization
- Missing or inconsistent headers
- No prd_cluster assignments
- Duplicate truth across multiple locations
- Orphaned content without governance
- Inconsistent quality and standards

Manual conversion is error-prone, incomplete, and fails to maintain constitutional discipline.

## 3. Core Principles

### 3.1 Doctrine-First Conversion
All transformations must respect existing PRD hierarchy and constitutional rules. No content may be ingested without understanding its relationship to the established PRD system.

### 3.2 Intent Reconstruction
CHIRON must reconstruct the original intent of external content and map it to appropriate Lupopedia constitutional structures. Guessing is forbidden - uncertainty triggers AGAPE BLOCK.

### 3.3 ASCII-Only Enforcement
All ingested content must be converted to strict ASCII format per constitutional doctrine. Non-ASCII characters must be replaced with ASCII equivalents.

### 3.4 Minimal Cluster Assignment
prd_cluster values must be assigned based on actual content analysis, not pattern matching or heuristics. When intent cannot be determined with certainty, CHIRON must request human clarification.

### 3.5 Group Discovery from PRD Index (Mandatory First Step)

Before any content analysis or cluster assignment, CHIRON must:
1. Read the current PRD Index (docs/prd/PRD_INDEX.md)
2. Extract all existing prd_cluster values and their groupings
3. Present the user with the list of existing groups
4. Ask the user to select an existing group OR confirm that a new group must be created

If the user selects an existing group ??? CHIRON uses that group and skips cluster creation.  
If no suitable group exists ??? CHIRON proceeds to create a new grouping (new PRD or new cluster).

## 4. CHIRON Pipeline

### 4.0 Group Discovery Phase (First Step in Pipeline)

1. Read PRD Index and list all current groups
2. Show user existing groups
3. User either selects existing group or requests new grouping
4. If new grouping needed ??? proceed to cluster creation rules
5. If existing group selected ??? lock that prd_cluster for the entire import

### 4.1 Ingestion Phase
1. **Content Discovery** - Locate external documentation sources
2. **Structure Analysis** - Parse existing organization and hierarchy
3. **Content Classification** - Identify type and purpose of each document
4. **Duplicate Detection** - Find overlapping or conflicting information

### 4.2 Reconstruction Phase
1. **Intent Extraction** - Determine what the content is trying to accomplish
2. **PRD Mapping** - Map content to existing PRD categories or identify gaps
3. **Header Generation** - Create complete LUPOPEDIA headers with proper metadata
4. **Cluster Assignment** - Assign prd_cluster based on actual content dependencies

### 4.3 Integration Phase
1. **Validation** - Ensure all generated content passes constitutional validation
2. **AGAPE Integration** - Submit to AGAPE for causal chain verification
3. **Teaching Preparation** - Generate educational materials for maintainers
4. **Continuous Monitoring** - Track integration success and system health

## 5. PRD Cluster Inference Rules

CHIRON must first query the PRD Index before applying any cluster inference rules.

### 5.1 Mandatory Analysis
Before assigning any prd_cluster value, CHIRON must:
1. Read the complete content of the document
2. Identify all referenced PRDs, doctrines, or system components
3. Map relationships to existing PRD hierarchy
4. Verify logical consistency of the proposed cluster

### 5.2 Prohibited Practices
- Pattern matching on filenames or titles only
- Assuming prd_cluster based on document location
- Using heuristics or statistical analysis
- Assigning clusters without content understanding

### 5.3 Uncertainty Protocol
If intent cannot be determined with certainty:
1. DO NOT assign a prd_cluster
2. Create a WHY file documenting the uncertainty
3. Request human clarification through proper channels
4. AGAPE BLOCK until clarification is received

## 6. Header Generation Rules

### 6.1 Required Fields (All 22)
Every generated header must include all 22 required fields per PRD 16_C. No field may be omitted or left to default values without explicit justification.

### 6.2 Content-Specific Fields
- **prd_cluster**: Must reflect actual content dependencies
- **title**: Must accurately represent the document's purpose
- **summary**: Must be concise yet comprehensive
- **memory_toon**: Must follow canonical pathing conventions
- **transcript_jsonl**: Must follow proper channel and thread structure

### 6.3 Validation Requirements
All generated headers must pass:
- LUPOPEDIA header validation (PRD 16_C)
- prd_cluster shorthand validation (PRD 86)
- ASCII-only validation (constitutional)
- Path validation against actual file locations

## 7. Duplicate / Orphan Handling

### 7.1 Duplicate Detection
CHIRON must identify:
- Identical content in multiple locations
- Overlapping coverage of the same topics
- Conflicting information about the same subjects
- Redundant documentation across different sources

### 7.2 Resolution Strategy
1. **Content Comparison** - Analyze differences and similarities
2. **Authority Determination** - Identify which source should be canonical
3. **Merge Planning** - Create systematic merge strategies
4. **Orphan Migration** - Move isolated content to proper locations

### 7.3 Conflict Resolution
When conflicts are detected:
1. DO NOT automatically merge or overwrite
2. Create WHY files documenting the conflict
3. Request human resolution through established channels
4. AGAPE BLOCK until conflicts are resolved

## 8. AGAPE Integration

### 8.1 HARD GATE Compliance
CHIRON must comply with AGAPE HARD GATE requirements:
1. LOAD prd_cluster from generated headers
2. EXPAND cluster into actual PRD files
3. READ them IN ORDER
4. RECONSTRUCT complete causal chain (INTENT, WHO, WHAT, WHERE, WHEN, HOW)

### 8.2 Blocking Conditions
CHIRON must BLOCK and request clarification when:
- Content intent cannot be determined
- prd_cluster assignment is uncertain
- Header field values cannot be validated
- Conflicts with existing doctrine are detected

### 8.3 Learning Integration
CHIRON must contribute to AGAPE learning:
- Document common patterns in external documentation
- Track successful conversion strategies
- Identify recurring conflict types
- Generate teaching materials for human maintainers

## 9. Teaching Loop (Continuous System)

### 9.1 Human Education
CHIRON must teach maintainers how to:
- Recognize proper documentation structure
- Validate prd_cluster assignments
- Maintain ASCII-only compliance
- Use AGAPE effectively for content governance

### 9.2 System Improvement
CHIRON must continuously improve by:
- Analyzing conversion success rates
- Identifying common failure patterns
- Updating inference rules based on experience
- Refining teaching materials

### 9.3 Feedback Integration
All human feedback must be:
- Documented in appropriate WHY files
- Integrated into CHIRON's knowledge base
- Used to improve future conversion accuracy
- Shared with AGAPE for system-wide learning

## 10. Failure Modes

### 10.1 Content Analysis Failures
- **Symptom**: Unable to determine document intent
- **Response**: Create WHY file, request human clarification
- **Prevention**: Improve pattern recognition, expand training data

### 10.2 Header Generation Failures
- **Symptom**: Generated headers fail validation
- **Response**: AGAPE BLOCK, analyze failure, retry with corrected logic
- **Prevention**: Strengthen validation rules, improve field inference

### 10.3 Integration Failures
- **Symptom**: Converted content conflicts with existing system
- **Response**: Create conflict WHY file, request resolution
- **Prevention**: Better duplicate detection, improved conflict analysis

### 10.4 System Overload
- **Symptom**: Processing queue exceeds capacity
- **Response**: Throttle input, prioritize by impact
- **Prevention**: Implement efficient batching, optimize algorithms

## 14. Group Discovery Interface (Simple Rule)

The interface is intentionally minimal:
- Reads PRD_INDEX.md
- Displays current groups in human-readable format
- Allows user to pick existing group or request new one
- If new group is needed, CHIRON creates it following normal cluster assignment rules
- No complex UI ??? just a clear list + selection

This ensures we never create duplicate groups and always respect existing constitutional structure.

## 11. Non-Goals

CHIRON is NOT responsible for:
- File system operations or data migration
- Database schema modifications
- User interface development
- Network protocol implementation
- Real-time content synchronization
- Automated content creation or generation
- Performance optimization of existing systems
- Security vulnerability assessment
- Backup and recovery operations

## 12. Dependencies

- PRD 00_A - Constitutional root requirements
- PRD 16_C - LUPOPEDIA headers specification
- PRD 57_A - AGAPE resilience doctrine
- PRD 98_A - WHY files doctrine
- PRD 59_B - ANUBIS Orphan Documentation Processor (processes queued items, writes files, logs results, respects groupings decided by CHIRON)
- AGAPE agent (actor_id 705) for learning integration
- Header validation infrastructure
- ASCII-only enforcement systems

## 13. Success Metrics

- Conversion accuracy rate (target: >95%)
- Human clarification request frequency (target: <10%)
- Integration success rate (target: >90%)
- Teaching effectiveness (measured by maintainer competency)
- System learning improvement (measured by reduced failures over time)

---

# End of PRD 59_A

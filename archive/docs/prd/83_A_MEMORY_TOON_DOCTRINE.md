---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/83_A_MEMORY_TOON_DOCTRINE.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/83_A_MEMORY_TOON_DOCTRINE.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/83_memory_toon_doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/memory-toon-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_83_A
  title: "PRD 83: Memory TOON Doctrine"
  summary: "Defines memory_toon as the canonical token-efficient semantic compression of artifacts for multi-agent coordination."
---
# PRD 83: Memory TOON Doctrine

## 1. Purpose

Define the canonical doctrine for **memory_toon** files as the **short-token, compressed, machine-readable semantic representation** of artifacts. Memory_toon exists to prevent token exhaustion, reduce AI context loading, and provide a stable semantic anchor for multi-agent coordination.

Memory_toon is the semantic compression layer that enables efficient multi-agent coordination while maintaining deterministic meaning across agent resets and system restarts.

## 2. Scope

### 2.1 In Scope
- Definition of memory_toon as semantic compression
- Generation rules for deterministic creation
- Validator rules for compliance checking
- Agent behavior requirements for reading/updating
- Integration with runtime ledger coordination
- Integration with memory graph authority

### 2.2 Out of Scope
- Database persistence mechanisms
- Runtime ledger implementation details
- Installation and deployment scripts
- Agent configuration management
- Network protocols and APIs

## 3. Semantic Role of memory_toon

### 3.1 What memory_toon IS
- **Structured semantic compression** of the artifact
- **Token-efficient representation** for AI consumption
- **Deterministic semantic anchor** for coordination
- **Stable reference point** across agent resets
- **Machine-readable format** for automated processing
- **ASCII-only content** per PRD 16 constitutional rules

### 3.2 What memory_toon is NOT
- **NOT a copy** of the full file
- **NOT a summary** in prose form
- **NOT a replacement** for the original artifact
- **NOT a cache** of the file content
- **NOT a human-readable narrative**
- **NOT a storage for full text content**

### 3.3 Semantic Components
Memory_toon MUST contain these structured elements:
- **Identity**: What the artifact is (type, purpose, scope)
- **Structure**: How the artifact is organized (sections, hierarchy)
- **Meaning**: Core semantic concepts and relationships
- **Relationships**: Links to other artifacts and dependencies
- **Invariants**: Critical constraints and rules that must be preserved

## 4. Generation Rules

### 4.1 Automatic Generation
- Memory_toon MUST be generated automatically when the file changes
- Generation MUST be triggered by file modification events
- Generation MUST be deterministic across all agents and systems
- Generation MUST NOT require human intervention

### 4.2 Deterministic Requirements
- Same input file MUST produce identical memory_toon output
- Generation algorithm MUST be stable across time
- Generation MUST be reproducible on any system
- Generation MUST use only the source file as input

### 4.3 Content Constraints
- Memory_toon MUST NOT contain full prose from the source
- Memory_toon MUST NOT exceed reasonable token budget (target: < 500 tokens)
- Memory_toon MUST reflect the canonical structure of the artifact
- Memory_toon MUST preserve critical semantic meaning
- Memory_toon MUST be ASCII-only (PRD 16 constitutional requirement)

### 4.4 Structural Requirements
- Memory_toon MUST be valid JSON format
- Memory_toon MUST use consistent field naming conventions
- Memory_toon MUST maintain backward compatibility where possible
- Memory_toon MUST include version information for evolution

## 5. Agent Behavior Rules

### 5.1 Mandatory Reading Protocol
- Agents MUST read memory_toon first before loading the full artifact
- Agents MUST use memory_toon to determine if full file loading is necessary
- Agents MUST NOT skip memory_toon reading for any canonical artifact
- Agents MUST treat memory_toon as the authoritative semantic reference

### 5.2 Conditional Loading
- Agents MAY load the full file only when memory_toon indicates necessity
- Agents MUST use memory_toon to assess relevance and scope
- Agents MUST NOT load full files based on filename alone
- Agents MUST prioritize memory_toon content for decision making

### 5.3 Update Requirements
- Agents MUST update memory_toon when modifying the file
- Agents MUST regenerate memory_toon after any structural changes
- Agents MUST validate memory_toon generation after updates
- Agents MUST NOT commit file changes without corresponding memory_toon updates

### 5.4 Prohibited Actions
- Agents MUST NOT regenerate memory_toon from memory or hallucination
- Agents MUST NOT ignore memory_toon when present
- Agents MUST NOT modify memory_toon directly (always regenerate)
- Agents MUST NOT use outdated memory_toon for coordination

## 6. Validator Rules

### 6.1 Existence Requirements
- Memory_toon MUST exist for all canonical artifacts with PRD 16 headers
- Memory_toon MUST be referenced in the artifact's memory_toon header field
- Memory_toon file MUST exist at the path specified in the header
- Memory_toon MUST be accessible and readable

### 6.2 Structural Validation
- Memory_toon MUST be valid JSON format
- Memory_toon MUST contain required semantic components
- Memory_toon MUST match the artifact's basic structure
- Memory_toon MUST be ASCII-only (no non-ASCII characters)

### 6.3 Semantic Validation
- Memory_toon MUST accurately reflect the artifact's purpose
- Memory_toon MUST preserve critical relationships and dependencies
- Memory_toon MUST maintain consistency with the source artifact
- Memory_toon MUST NOT contain empty strings for nullable fields

### 6.4 Consistency Validation
- Memory_toon timestamp MUST be newer than or equal to source file timestamp
- Memory_toon version MUST be compatible with current system requirements
- Memory_toon MUST reference the correct source artifact
- Memory_toon MUST not contain references to non-existent artifacts

## 7. Integration with Runtime Ledger (PRD 70)

### 7.1 Handoff Protocol
- Memory_toon becomes part of the actor handoff protocol in runtime coordination
- Handoff events MUST include memory_toon references for continuity
- Receiving actors MUST read memory_toon before processing handoff tasks
- Memory_toon reduces token usage in runtime task coordination

### 7.2 Continuity Across Resets
- Memory_toon provides semantic continuity across agent and system resets
- Runtime state recovery MUST use memory_toon for artifact reconstruction
- Memory_toon enables efficient resumption of interrupted tasks
- Memory_toon maintains coordination state without full file loading

### 7.3 Multi-Agent Coordination
- Memory_toon serves as the coordination token between multiple actors
- Actors MUST coordinate using memory_toon semantics rather than full content
- Memory_toon enables deterministic coordination across different agent types
- Memory_toon reduces communication overhead in multi-agent workflows

## 8. Integration with Memory Graph (PRD 38 / PRD 51)

### 8.1 Semantic Anchor Role
- Memory_toon is the semantic anchor for memory nodes in the graph
- Memory_toon defines the structural meaning of the artifact for memory operations
- Memory_toon provides the canonical reference for memory node relationships
- Memory_toon enables efficient memory graph traversal and querying

### 8.2 THOTH Truth-Checking
- Memory_toon is used for THOTH truth-checking operations
- Memory_toon provides the semantic baseline for consistency validation
- Memory_toon enables efficient detection of semantic drift
- Memory_toon serves as the reference for automated truth verification

### 8.3 Memory Node Creation
- Memory_toon content informs memory node creation and classification
- Memory_toon relationships become memory graph edges
- Memory_toon structure determines memory node hierarchy
- Memory_toon invariants become memory graph constraints

## 9. File Format Specification

### 9.1 JSON Structure
```json
{
  "memory_toon_version": "1.0.0",
  "source_artifact": "path/to/source/file.md",
  "generated_timestamp": "YYYYMMDDHHIISS",
  "semantic_compression": {
    "identity": {
      "type": "prd",
      "purpose": "specification",
      "scope": "memory_toon_doctrine"
    },
    "structure": {
      "sections": ["purpose", "scope", "semantic_role", ...],
      "hierarchy": "canonical_prd_structure"
    },
    "meaning": {
      "core_concepts": ["semantic_compression", "token_efficiency", ...],
      "relationships": ["prd_16", "prd_38", "prd_51", "prd_70"]
    },
    "invariants": [
      "ascii_only",
      "deterministic_generation",
      "token_efficiency"
    ]
  }
}
```

### 9.2 Field Requirements
- All string fields MUST be ASCII-only
- All timestamps MUST be in YYYYMMDDHHIISS format
- All references MUST be valid paths or identifiers
- All arrays MUST be properly structured and non-empty when required

## 10. Cross-references

- Related: **PRD 16** - Header Doctrine (memory_toon field specification)
- Related: **PRD 38** - Memory Unification (memory graph integration)
- Related: **PRD 51** - Memory Graph Authority (semantic anchor role)
- Related: **PRD 70** - Actor Runtime Directory Structure (coordination integration)
- Related: **MULTI_AGENT_COORDINATION_DOCTRINE.md** - Multi-agent coordination patterns
- Related: **DATABASE_DOCTRINE.md** - Cross-platform compatibility requirements

---

This output complies with Lupopedia Constitutional Root Rules.

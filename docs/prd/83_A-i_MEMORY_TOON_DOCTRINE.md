---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/83_A-i_MEMORY_TOON_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/83_A-i_MEMORY_TOON_DOCTRINE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/canonical/development/memory_cluster/2026/05/83_memory_toon_doctrine.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/memory-toon-doctrine
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_83_A-i
  title: 'PRD 83: Memory TOON Doctrine'
  summary: Defines memory_toon as the canonical token-efficient semantic compression of artifacts for multi-agent coordination.
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _______________
. ./ \ ` ` `_-\ . | A four-axis, finite, constitutional PRD documentation architecture 
. '/| \-''-/_ / . | that lets docs build software. PRDs reference other PRDs, forming 
. { . , . , . ,\ .| clusters that define behavior, truth, limits, and system identity
. / . , . , . , \ | through positional priority (array index = reading order),
./ , . "O. |"O. } | significance weight (A–F letter), grouping (numeric category), and 
_| . , . , \ \ ;. | chronology (Roman numeral = time created).
. '\. . , . \ \'. | Each file carries a header that records the exact
.. '\_ . , . \__\ | four-axis prd_cluster (order, weight, and time created), the full
., , ''-_ , {\__/}| transcript_jsonl dialog, and atoms_toon for canonical truth,
. . , . / '-.____'| ensuring deterministic lineage and reproducibility. 
., , /. _ _ . -_ -| https://www.lupopedia.com/
.. , _'___________| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
___-' __________________________________________________________________
<!-- /ASCII_ART_BLOCK -->

### ASCII_ART_BLOCK Protection

ASCII_ART_BLOCK is immutable.

Agents MUST NOT:
- reformat spacing
- modify characters
- regenerate block

Violation is a doctrine failure (PRD 86).

ASCII MUST NOT:
- be parsed
- affect execution
- influence header interpretation

ASCII is human-readable only.

<!--HUMAN_SEMANTIC -->
This file belongs to:
- PRD Group 83 (Memory TOON Doctrine)
- Channel: development
- Trust tier: canonical

See also:
- PRD 16 - Lupopedia Headers
- PRD 38 - Memory Unification
- PRD 51 - Memory Graph as Contextual Inference Layer
- PRD 82 - HERMES Message Routing and Memory Gateway
<!-- /HUMAN_SEMANTIC -->

### 4.1.7 Preamble Compliance

This file follows the 4.1.7 three-part preamble:

1. YAML header
2. ASCII_ART_BLOCK
3. HUMAN_SEMANTIC

Execution authority remains YAML header only.
# PRD 83: Memory TOON Doctrine

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
- Memory_toon MUST be generated when the source artifact changes
- Generation MUST be explicitly invoked
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
- memory_toon SHOULD remain compact
- memory_toon size MUST be bounded by implementation-defined limits
- Memory_toon MUST reflect the canonical structure of the artifact
- Memory_toon MUST preserve critical semantic meaning
- ASCII-only requirement is defined in PRD 16

### 4.4 Structural Requirements
- Memory_toon MUST be valid JSON format
- Memory_toon MUST use consistent field naming conventions
- Memory_toon MUST maintain backward compatibility where possible
- Memory_toon MUST include version information for evolution

## 5. Agent Behavior Rules

All memory_toon usage MUST respect header interpretation semantics defined in PRD 16 (4.1.7).

memory_toon is not independently authoritative.

### 5.1 Mandatory Reading Protocol
- Agents MUST interpret context in header-defined reading order from PRD 16
- memory_toon MUST be read AFTER:
  - prd_cluster (governing doctrine)
  - atoms_toon (canonical truth)
- memory_toon is the first contextual expansion layer, not the first authority layer
- Agents MUST use memory_toon to determine if full file loading is necessary
- Agents MUST evaluate memory_toon when contextual expansion is required
- memory_toon is not required for all execution paths
- Agents MUST treat memory_toon as a contextual semantic reference only, subordinate to prd_cluster doctrine and atoms_toon

### 5.2 Deterministic Pointer Resolution

Agents MUST resolve `memory_toon` from the explicit PRD 16 header field.

Agents MUST NOT:
- scan memory directories for discovery
- infer alternate memory_toon paths
- guess missing memory_toon references

If resolution fails:
STOP
REPORT "DOCTRINE NOT FOUND"

### 5.3 Conditional Loading
- Agents MAY load the full file only when memory_toon indicates necessity
- Agents MUST use memory_toon to assess relevance and scope
- Agents MUST NOT load full files based on filename alone
- Agents MUST prioritize memory_toon content for decision making

### 5.4 Update Requirements
- Agents that modify artifacts MUST ensure memory_toon is regenerated
- Agents MUST regenerate memory_toon after any structural changes
- Agents MUST validate memory_toon generation after updates
- Agents MUST NOT commit file changes without corresponding memory_toon updates

### 5.5 Prohibited Actions
- Agents MUST NOT regenerate memory_toon from memory or hallucination
- Agents MUST evaluate memory_toon when context requires it
- Agents MUST NOT ignore memory_toon when it is relevant to the operation
- Agents MUST NOT modify memory_toon directly (always regenerate)
- Agents MUST NOT use outdated memory_toon for coordination

Memory_toon regeneration MUST:
- use only the current source artifact
- be deterministic
- not use prior memory_toon
- not use external memory or inference

## 6. Validator Rules

### 6.1 Existence Requirements
- Memory_toon MUST exist for all artifacts where trust_tier = "canonical"
- Memory_toon MUST be referenced in the artifact's memory_toon header field
- Memory_toon file MUST exist at the path specified in the header
- Memory_toon MUST be accessible and readable

### 6.2 Structural Validation
- Memory_toon MUST be valid JSON format
- Memory_toon MUST contain required semantic components
- Memory_toon MUST match the artifact's basic structure
- ASCII-only requirement is defined in PRD 16

### 6.3 Semantic Validation
- Memory_toon MUST accurately reflect the artifact's purpose
- Memory_toon MUST preserve critical relationships and dependencies
- Memory_toon MUST maintain consistency with the source artifact
- Memory_toon MUST NOT contain empty strings for nullable fields

### 6.4 Consistency Validation
- Memory_toon timestamp MUST be greater than or equal to source file timestamp
- Memory_toon MUST be regenerated when the source artifact changes
- Memory_toon version MUST be compatible with current system requirements
- Memory_toon MUST reference the correct source artifact
- Memory_toon MUST not contain references to non-existent artifacts

### 6.5 Validation Severity

Validator MUST classify failures:

- Missing memory_toon -> ERROR
- Invalid JSON format -> ERROR
- Incorrect path -> ERROR
- Outdated memory_toon -> WARNING
- Missing optional fields -> WARNING

Severity MUST align with PRD 86 enforcement rules

## 7. Integration with Runtime Ledger (PRD 70)

If runtime ledger (PRD 70) is unavailable:
memory_toon behavior remains valid and deterministic

### 7.1 Handoff Protocol
- Memory_toon becomes part of the actor handoff protocol in runtime coordination
- Handoff events MUST include memory_toon references for continuity
- Receiving actors MUST read memory_toon before processing handoff tasks
- Memory_toon is designed to reduce token usage in runtime task coordination

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

## Toon Authority Stack

atoms_toon:

* immutable
* canonical truth

memory_toon:

* regenerable
* contextual only
* MUST NOT override atoms_toon or PRD

questions_toon:

* unresolved signals only
* MUST NOT assert truth

transcript_jsonl:

* append-only history
* MUST NOT influence execution

### Memory Node Classification (4.1.7 Alignment)

Memory_toon-derived nodes MUST use classification semantics defined in PRD 82:

- kuleana
- pono
- kapakai
- kapu

memory_toon MAY reference or propagate classifications, but MUST NOT define, invent, or override classification rules.

### 8.1 Semantic Anchor Role
- Memory_toon is the semantic anchor for memory nodes in the graph
- Memory_toon reflects the structural meaning of the artifact for memory operations
- Memory_toon provides the primary contextual reference for memory node relationships
- Memory_toon enables efficient memory graph traversal and querying

### 8.2 THOTH Truth-Checking
- Memory_toon is used for THOTH truth-checking operations
- Memory_toon provides the semantic baseline for consistency validation
- Memory_toon enables efficient detection of semantic drift
- Memory_toon serves as the reference for automated truth verification

### 8.3 Memory Node Creation
- Memory_toon content informs memory node creation and classification
- Memory_toon relationships MAY be projected as memory graph edges
- Memory_toon structure determines memory node hierarchy
- Memory_toon invariants become memory graph constraints

### 8.4 memory_toon Edge Semantics (4.1.7 alignment)

When memory_toon relationships are projected to graph edges:

* edge meaning MUST be explicit and deterministic
* edge direction MUST be explicit
* edge traversal MUST honor bounded policies from PRD 51/PRD 38
* edge interpretation MUST NOT invent implied relationships

memory_toon may declare relationships, but it does not override canonical truth units from atoms or PRD governance.

Memory edge traversal MUST be:
- explicitly triggered
- bounded
- deterministic

Unbounded graph traversal is forbidden.

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
- Related: **PRD 51** - Memory Graph as Contextual Inference Layer (semantic anchor role)
- Related: **PRD 70** - Actor Runtime Directory Structure (coordination integration)
- Related: **MULTI_AGENT_COORDINATION_DOCTRINE.md** - Multi-agent coordination patterns
- Related: **DATABASE_DOCTRINE.md** - Cross-platform compatibility requirements

---

This output complies with Lupopedia Constitutional Root Rules.

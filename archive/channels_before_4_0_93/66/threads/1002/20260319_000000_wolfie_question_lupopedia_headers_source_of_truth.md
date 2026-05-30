---
lupopedia.headers:
  lupopedia.version: 4.0.80
  lupopedia.schema: thread
  system_version: 4.0.80
  file_path_from_root: channels/66/threads/1002/20260319_000000_wolfie_question_lupopedia_headers_source_of_truth.md
  web_path: http://www.lupopedia.com/channels/66/threads/1002/20260319_000000_wolfie_question_lupopedia_headers_source_of_truth.md
  last_modified_utc: '20260324182605'
  project_id: 0
  project_slug: lupopedia-core
  channel_id: 66
  thread_id: 1002
  task_id: task_lupopedia_headers_definition_001
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: thread
  artifact_kind: question
  purpose: 'WOLFIE QUESTION: What are Lupopedia Headers, how do they function as canonical
    source of truth, and how should they be structured, parsed, and used across the
    system?'
  tags:
  - channel66
  - headers
  - source_of_truth
  - question
  - lupopedia_headers
  - 4.0.80
  message_type: question
  when_updated: '20260324182605'
lupopedia.edges:
  outbound_edges:
  - to: docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: references
    weight: 1.0
    reason: Primary Lupopedia Headers doctrine
  - to: docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md
    type: references
    weight: 1.0
    reason: Header validation and tooling guidance
  - to: rules/root/toon-source-of-truth.md
    type: references
    weight: 0.9
    reason: TOON files as schema reference
  - to: rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md
    type: references
    weight: 0.9
    reason: Channel artifact routing doctrine
  - to: channels/66/threads/1001/20260319_230000_lilith_adjudication_wolfie_reframe_narrowing_or_block.md
    type: related_question
    weight: 1.0
    reason: Thread 1001 depends on header ingestion for Channel 66 indexing
  - to: channels/66/THREAD_INDEX.md
    type: references
    weight: 0.8
    reason: Channel 66 thread index context
lupopedia.interpretation:
  whoami:
    facet: orchestrator
    runtime_context: question_creation
    session_mode: analysis
    project_id: 0
    project_slug: lupopedia-core
    channel_id: 66
    thread_id: 1002
  whoareyou:
    actor_id: 1
    actor_name: wolfie
    identity_source: canonical_registry
    state: active
    authority_level: canonical_orchestrator
  whoopposesyou: lilith
lupopedia.footer:
  version: 4.0.80
  last_verified: '20260324182605'
  last_verified_by: cursor
  orchestrator: wolfie
  next_action:
  - 'LILITH: adversarial review of header definition and structure'
  - 'HEPHAESTUS: implementation perspective on header parsing and ingestion'
  - 'Others: evidence gathering on header usage across system'
  last_verified_by_actor_id: 102
---

# file: WOLFIE QUESTION — Lupopedia Headers Source of Truth — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/channels/66/threads/1002/20260319_000000_wolfie_question_lupopedia_headers_source_of_truth.md

# Thread 1002 — What are Lupopedia Headers?

**Thread:** 1002  
**Channel:** 66 (QA / Adversarial Review)  
**Author:** WOLFIE (actor_id 1)  
**Status:** Question thread — working material only. Not canonical doctrine.  
**Date:** 20260319  

---

## 1. EXPLICIT QUESTION

**Thread 1002 Question:** *"What are Lupopedia Headers, how do they function as canonical source of truth, and how should they be structured, parsed, and used across the system?"*

---

## 2. WHY THIS QUESTION EXISTS

### **2.1 Channel 66 Indexing System Dependency**

Thread 1001 (Channel 66 indexing system) has identified **header ingestion as P0 prerequisite**. However, the scope, structure, and implementation details of "header ingestion" remain undefined. Thread 1001 cannot proceed to implementation evidence without a clear understanding of what Lupopedia Headers are and how they function as source of truth.

### **2.2 System-Wide Header Usage Ambiguity**

Multiple system components reference Lupopedia Headers (metadata ingestion, edge extraction, artifact routing), but there is no single, comprehensive definition of:
- What constitutes a valid Lupopedia Header
- How headers map to database structures
- What parsing and validation requirements exist
- How headers maintain authority across different contexts

### **2.3 Canonical Source-of-Truth Clarity Needed**

The doctrine states "headers = source of truth, DB = projection," but the operational mechanics of this relationship need explicit definition to support Channel 66 indexing and other header-dependent systems.

---

## 3. SCOPE OF INVESTIGATION

This thread must answer:

### **3.1 Header Definition and Structure**
- What is the canonical header format?
- What are the required vs optional header blocks?
- How are headers validated for correctness?
- What is the relationship between YAML blocks and markdown content?

### **3.2 Header-to-Database Mapping**
- How do headers map to lupo_metadata table structure?
- What are the entity_type, domain_id, and class_name conventions?
- How are hierarchical header structures (root → block → property) represented?
- What are the ingestion patterns for different header types?

### **3.3 Edge Declaration and Ingestion**
- How are lupopedia.edges declared in headers?
- What are the valid edge_type and edge_category values?
- How are header edges converted to lupo_edges rows?
- What validation exists for edge targets?

### **3.4 Header Authority and Synchronization**
- How is header authority maintained across filesystem and database?
- What are the sync patterns (file→DB, DB→file)?
- How are conflicts resolved between headers and database state?
- What are the fallback mechanisms for parsing failures?

---

## 4. KNOWN DOCTRINE REFERENCES

### **4.1 Primary Header Doctrine**
- **[LUPOPEDIA_HEADERS/README.md](../../../../docs/doctrine/LUPOPEDIA_HEADERS/README.md)** — Core header specification and usage
- **[LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md](../../../../docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md)** — Validation rules and tooling guidance

### **4.2 Supporting Doctrine**
- **[toon-source-of-truth.md](../../../../rules/root/toon-source-of-truth.md)** — TOON files as derived schema, not semantic definition
- **[CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md](../../../../rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md)** — Channel artifact placement and authority rules

### **4.3 Related System Questions**
- **Thread 1001** — Channel 66 indexing system depends on header ingestion P0 requirement
- **Thread 1038** — Channel 66 question model (may reference header usage in question containers)

---

## 5. OPEN SUB-QUESTIONS

### **5.1 Structural Questions**
1. What is the canonical YAML block order in Lupopedia Headers?
2. Which header blocks are required vs optional for different artifact types?
3. How are header inheritance and overrides handled?
4. What are the validation rules for header completeness and correctness?

### **5.2 Parsing and Ingestion Questions**
1. What are the standard parsing patterns for different header block types?
2. How are parsing errors handled and reported?
3. What are the performance considerations for header ingestion at scale?
4. How are circular references in headers detected and resolved?

### **5.3 Database Mapping Questions**
1. What are the canonical entity_type values for different header objects?
2. How are hierarchical structures flattened into lupo_metadata rows?
3. What are the domain_id conventions for different contexts?
4. How are header-to-database conflicts detected and resolved?

### **5.4 Edge and Relationship Questions**
1. What are the canonical edge_type values for different relationship categories?
2. How are edge categories derived from grouped outbound_edges?
3. What validation exists for edge target existence?
4. How are bidirectional edges represented in headers vs database?

### **5.5 Authority and Sync Questions**
1. What are the canonical sync patterns between filesystem headers and database?
2. How is header authority enforced when database state diverges?
3. What are the rollback mechanisms for failed header ingestion?
4. How are concurrent header updates handled and synchronized?

---

## 6. NEXT ACTIONS

### **6.1 Immediate Adversarial Review**
- **LILITH** should review this question definition for completeness, identify missing aspects, and attack any assumptions about header scope or authority.

### **6.2 Implementation Evidence Gathering**
- **HEPHAESTUS** should provide implementation perspective on header parsing requirements, ingestion pipeline complexity, and technical feasibility of different header ingestion approaches.

### **6.3 System Evidence Collection**
- **Other actors** should gather evidence on current header usage across the system, identify gaps in existing header parsing, and document inconsistencies in header handling.

### **6.4 Doctrine Clarification**
- **WOLFIE** may provide additional clarification on header doctrine references if sub-questions reveal ambiguities or gaps in current documentation.

---

## 7. THREAD CONTEXT AND RELATIONSHIPS

Thread 1002 serves as a **foundational dependency** for Thread 1001's Channel 66 indexing system. A clear understanding of Lupopedia Headers is required before Thread 1001 can proceed to implementation evidence for header ingestion P0 requirements.

This thread will remain **question-focused** and will not produce canonical doctrine until the adversarial process in Channel 66 resolves the definition and scope of Lupopedia Headers as source of truth.

---

## 8. SUCCESS CRITERIA

Thread 1002 succeeds when:
- Comprehensive definition of Lupopedia Headers is established
- Header structure and parsing requirements are clearly documented
- Header-to-database mapping patterns are defined
- Edge declaration and ingestion mechanisms are specified
- Authority and synchronization patterns are clarified
- Thread 1001 can proceed with concrete implementation evidence for header ingestion

---

*End of Thread 1002 Question — Working material only.*

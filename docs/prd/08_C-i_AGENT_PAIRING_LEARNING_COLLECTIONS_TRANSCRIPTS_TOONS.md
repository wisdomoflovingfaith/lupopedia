---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/08_C-i_AGENT_PAIRING_LEARNING_COLLECTIONS_TRANSCRIPTS_TOONS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/08_C-i_AGENT_PAIRING_LEARNING_COLLECTIONS_TRANSCRIPTS_TOONS.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/08-c-agent-pairing-learning-collections-transcripts-toons.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/08_c_agent_pairing_learning_collections_transcripts_toons
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_08_A-i_08_B-i_08_C-i
  title: PRD 08_C ??? Agent Pairing, Learning, Collections, Transcripts, TOONs
  summary: Defines actor ??? agent ??? user relationships, learning through system artifacts, and memory formation through collections, transcripts, and TOON files.
---

# PRD 08_C ??? Agent Pairing, Learning, Collections, Transcripts, TOONs

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

## 1. PURPOSE

Define actor ??? agent ??? user relationships, how agent instances are formed, how learning occurs through system artifacts, and how collections, transcripts, and TOON files form memory.

### 1.1 SCOPE

Applies to:
* Agent identity and instance management
* User-agent pairing and binding
* Learning context and memory formation
* System artifact relationships and data flow

### 1.2 RELATIONSHIP TO PRD 08 SERIES

* PRD 08_A: Core agent system architecture
* PRD 08_Z: Agent roles and responsibilities
* PRD 08_C: Agent pairing, learning, and memory formation

## 2. CORE MODEL

### 2.1 ACTOR TABLE SEMANTICS

The `lupo_actors` table defines three key identifiers:

#### `auth_user_id`
* Identifies the human user
* NULL for system agents
* NOT NULL for user-bound agents
* Logical reference to user authentication system (no foreign key constraint; enforced in application layer per database doctrine)

#### `actor_source_id`
* Identifies the agent/personality type
* Examples: Wolfie (1), Lilith (2), ROSE (3), etc.
* Defines the core agent characteristics and capabilities
* Consistent across all instances of the same agent

#### `actor_id`
* Runtime instance identifier
* Primary key for the actor table
* Unique per agent instance
* Represents the specific running instance

### 2.2 INSTANCE FORMATION

```text
agent personality (actor_source_id)
+ optional human binding (auth_user_id)
= runtime agent instance (actor_id)
```

**Example Formation:**
* Wolfie personality (actor_source_id = 1) + Eric (auth_user_id = 123) = Wolfie instance (actor_id = 456)
* ROSE personality (actor_source_id = 3) + Sarah (auth_user_id = 124) = ROSE instance (actor_id = 457)

## 3. PAIRING RULE

### 3.1 TWO TYPES OF ACTORS

#### A. HUMAN-BOUND ACTORS

**Characteristics:**
* `auth_user_id` = NOT NULL
* Represents a user + agent pairing
* Exactly ONE agent per user instance
* Personalized context and learning

**Example Pairings:**
* Eric + Wolfie
* Sarah + ROSE
* Mike + Thoth

**Rules:**
* One primary agent per user
* User-bound agents learn from that user's context
* Isolated learning environment per user

#### B. SYSTEM / AGENT ACTORS

**Characteristics:**
* `auth_user_id` = NULL
* Pure agent instances
* Global responsibilities
* Shared context

**Examples:**
* Wolfie (system orchestration)
* Hermes (message routing)
* Lilith (audit and validation)

**Rules:**
* No user binding
* Global learning context
* System-wide responsibilities

### 3.2 PAIRING ENFORCEMENT

**Database Constraints:**
* At most one PRIMARY agent per auth_user_id
* Additional agent bindings MUST be explicitly defined and are not implied by default pairing rules
* Multiple instances allowed for same agent_source_id with different auth_user_id
* System agents have auth_user_id = NULL

**Default system behavior:**
* One auth_user_id ??? One primary actor instance

**Application Logic:**
* Enforce one primary agent per user
* Prevent multiple agent bindings for same user
* Validate pairing consistency

### 3.3 DUAL INSTANCE MODEL

An agent (actor_source_id) may exist in two forms:

1. **System Instance**
   * auth_user_id = NULL
   * global responsibilities
   * shared context

2. **User-Bound Instance**
   * auth_user_id = NOT NULL
   * specific to a user
   * isolated learning context

Both instances share the same core identity (actor_source_id) but differ in context, memory, and responsibility scope.

## 4. AGENT INSTANCE MODEL

### 4.1 INSTANCE IDENTITY

Each actor row represents a complete agent instance:

**Identity Components:**
* Core personality (actor_source_id)
* Optional user binding (auth_user_id)
* Runtime identifier (actor_id)
* Instance-specific memory and learning

**Instance Isolation:**
* User-bound instances: Isolated learning and memory
* System instances: Shared learning and memory
* Cross-instance communication through defined channels

### 4.2 MULTIPLE INSTANCES

Multiple instances may exist for the same agent:

**Example: ROSE Instances**
* ROSE + User A (actor_id = 100)
* ROSE + User B (actor_id = 101)
* ROSE + User C (actor_id = 102)

**Instance Characteristics:**
* Same core personality and capabilities
* Different learning contexts and memories
* Isolated behavioral adaptations
* Independent user relationships

## 5. LEARNING MODEL

### 5.1 LEARNING SOURCES

Agents learn from system artifacts:

#### Channels
* Real-time communication streams
* Multi-agent interactions
* User-agent dialogues
* System coordination messages

#### Transcripts
* Chronological interaction history
* Structured conversation records
* Context preservation
* Behavioral tracking

#### Collections
* Context grouping and organization
* Related artifact clustering
* Topic-based organization
* Learning signal clustering

#### Artifacts
* PRDs and documentation
* Code implementations
* System configurations
* Decision records

### 5.2 INSTANCE-AWARE LEARNING

**User-Bound Agents:**
* Learn from that user's specific context
* Personalized behavioral adaptations
* User preference learning
* Isolated from other users' data

**System Agents:**
* Learn from shared/global context
* System-wide pattern recognition
* Cross-user optimization
* Global behavioral refinement

Learning isolation is mandatory to prevent cross-user behavioral contamination.

### 5.3 LEARNING ISOLATION

**Data Separation:**
* User-bound agents: User-specific data only
* System agents: Aggregated, anonymized data
* Cross-contamination prevention
* Privacy preservation

**Memory Isolation:**
* Instance-specific memory storage
* Controlled knowledge sharing
* Privacy-respecting learning
* Contextual boundaries

## 6. COLLECTIONS AS CONTEXT GROUPING

### 6.1 COLLECTION PURPOSE

Collections group related system elements:

**Grouped Elements:**
* Conversations and dialogues
* Artifacts and documents
* Relationships and connections
* Learning signals and contexts

**Organizational Benefits:**
* Context boundary definition
* Learning signal clustering
* Memory organization
* Retrieval optimization

### 6.2 COLLECTION TYPES

#### Conversation Collections
* Related dialogues and discussions
* Thematic conversation grouping
* Participant-based organization
* Temporal conversation clusters

#### Artifact Collections
* Related documents and PRDs
* Code implementation groups
* Configuration sets
* Decision records

#### Learning Collections
* Training data clusters
* Context-specific learning sets
* Behavioral pattern groups
* Knowledge domains

### 6.3 COLLECTION MANAGEMENT

**Creation Rules:**
* Automatic clustering based on content
* Manual organization for specific contexts
* User-defined collection boundaries
* System-generated learning collections

**Maintenance:**
* Periodic collection optimization
* Relevance-based pruning
* Context boundary updates
* Learning signal refresh

## 7. TRANSCRIPTS AS TRAINING STREAM

### 7.1 TRANSCRIPT REPRESENTATION

Transcripts represent chronological interaction history:

**Content Types:**
* Agent-user dialogues
* Multi-agent coordination
* System operation logs
* Decision-making processes

**Structure:**
* Chronological message ordering
* Participant identification
* Context preservation
* Metadata enrichment

### 7.2 TRAINING APPLICATION

**Context Reconstruction:**
* Historical context rebuilding
* Conversation flow understanding
* Decision trace analysis
* Behavioral pattern identification

**Learning Input:**
* Supervised learning examples
* Behavioral pattern training
* Response optimization
* Contextual adaptation

**Behavioral Refinement:**
* Response quality improvement
* Context appropriateness
* User preference adaptation
* Performance optimization

### 7.3 TRANSCRIPT MANAGEMENT

**Generation:**
* Automatic conversation logging
* Real-time transcript creation
* Context preservation
* Metadata capture

**Processing:**
* Learning signal extraction
* Pattern identification
* Context analysis
* Behavioral analysis

**Storage:**
* Efficient transcript storage
* Indexed retrieval
* Context-based organization
* Privacy-respecting retention

## 8. TOON FILES AS MEMORY LAYER

### 8.1 TOON FILE REPRESENTATION

TOON files represent compressed memory snapshots:

**Content Types:**
* Canonical knowledge summaries
* Compressed learning artifacts
* Persistent memory representations
* Structured knowledge graphs

**Characteristics:**
* Deterministic generation
* Reproducible content
* Structured format
* Efficient storage

### 8.2 TOON GENERATION RULES

**Source Materials:**
* Derived from transcripts and collections
* Processed learning signals
* Structured knowledge extraction
* Canonical representation

**Generation Process:**
* Deterministic algorithms
* Reproducible transformations
* Quality validation
* Consistency verification

**Output Requirements:**
* Structured, machine-readable format
* Human-interpretable content
* Efficient storage and retrieval
* Version-controlled evolution

### 8.3 TOON vs RAW LOGS

**TOON Files:**
* Processed and structured
* Canonical representations
* Learning-optimized format
* Persistent memory artifacts

**Raw Logs:**
* Unprocessed conversation data
* Verbose and redundant
* Temporary storage
* Input for TOON generation

**Transformation:**
```
Raw Logs ??? Processing ??? Structuring ??? TOON Files
```

## 9. LEARNING FLOW

### 9.1 COMPLETE LEARNING PIPELINE

```text
channels ??? transcripts ??? collections ??? TOON ??? agent behavior
```

**Stage Breakdown:**

#### Channels (Input)
* Real-time data collection
* Multi-source aggregation
* Context capture
* Initial processing

#### Transcripts (Processing)
* Chronological organization
* Context preservation
* Pattern identification
* Learning signal extraction

#### Collections (Organization)
* Context grouping
* Signal clustering
* Knowledge organization
* Memory structuring

#### TOON (Memory)
* Canonical representation
* Persistent storage
* Knowledge compression
* Behavioral integration

#### Agent Behavior (Output)
* Contextual responses
* Personalized interactions
* Adaptive behavior
* Performance optimization

### 9.2 FEEDBACK LOOPS

**Learning Feedback:**
* Performance monitoring
* Behavioral adjustment
* Context refinement
* Memory optimization

**System Feedback:**
* User satisfaction tracking
* Response quality measurement
* Context relevance assessment
* Learning efficiency optimization

## 10. USER SCALING MODEL

### 10.1 MULTI-USER SUPPORT

System supports many users with personalized agents:

**Scaling Characteristics:**
* Many users (auth_user_id)
* Each mapped to ONE primary agent
* Isolated learning contexts
* Independent behavioral adaptations

**Example Scaling:**
* 20 users ??? 20 ROSE instances
* Same agent_source_id (ROSE = 3)
* Different actor_id (100-119)
* Different learning contexts

### 10.2 RESOURCE MANAGEMENT

**Memory Allocation:**
* Per-user memory quotas
* Efficient TOON storage
* Context-based optimization
* Resource sharing where appropriate

**Performance Optimization:**
* Instance-specific caching
* Context-aware loading
* Efficient retrieval mechanisms
- Scalable learning algorithms

## 11. SYSTEM AGENTS VS USER AGENTS

### 11.1 SYSTEM AGENTS

**Characteristics:**
* No auth_user_id (NULL)
* Global responsibilities
* Shared context
* System-wide learning

**Examples:**
* Wolfie (orchestration)
* Hermes (routing)
* Lilith (audit)
* Thoth (knowledge)

**Learning Context:**
* Global system patterns
* Cross-user optimization
* System-wide behavioral refinement
* Shared knowledge base

### 11.2 USER AGENTS

**Characteristics:**
* Bound to auth_user_id
* Personalized context
* Isolated learning
* User-specific adaptations

**Examples:**
* ROSE instances per user
* Personalized assistant agents
* User-specific interface agents
* Customized service agents

**Learning Context:**
* User-specific patterns
* Personalized preferences
* Individual behavioral adaptations
* Private knowledge base

## 12. CORE PRINCIPLES

### 12.1 FUNDAMENTAL PRINCIPLES

1. **Agents are defined by source, not instance**
   * actor_source_id defines core personality
   * actor_id defines runtime instance
   * Multiple instances per agent type allowed

2. **Instances carry context and memory**
   * Each instance has isolated learning
   * Context-specific behavioral adaptations
   * Memory isolation between instances

3. **Learning is context-driven, not global by default**
   * User-bound agents learn from user context
   * System agents learn from global context
   * Privacy-preserving learning isolation

4. **Users interact through paired agent instances**
   * One primary agent per user
   * Personalized interaction patterns
   * Isolated user experience

5. **TOON files are the persistent memory layer**
   * Canonical knowledge representation
   * Efficient storage and retrieval
   * Deterministic generation

### 12.2 DESIGN PRINCIPLES

**Privacy:**
* User data isolation
* Controlled knowledge sharing
* Privacy-respecting learning
* Secure memory management

**Scalability:**
* Efficient multi-user support
* Resource optimization
* Context-based scaling
* Performance optimization

**Consistency:**
* Deterministic TOON generation
* Reproducible learning outcomes
* Consistent behavioral patterns
* Reliable memory formation

## 13. FAILURE MODES

### 13.1 MIXED CONTEXT LEARNING

**Symptoms:**
* Agent learns from unrelated users
* Behavior drift and inconsistency
* Privacy violations
* Context contamination

**Causes:**
* Improper learning isolation
* Shared learning contexts
* Cross-user data leakage
* Inadequate privacy controls

**Prevention:**
* Strict learning isolation
* User-specific context boundaries
* Privacy-preserving algorithms
* Regular context validation

### 13.2 MULTIPLE AGENTS PER USER

**Symptoms:**
* Conflicting responses
* Identity confusion
* User experience fragmentation
* Resource competition

**Causes:**
* Improper pairing enforcement
* Multiple agent bindings
* Lack of primary agent designation
* Unclear user-agent relationships

**Prevention:**
* One primary agent per user rule
* Strict pairing validation
* Clear user-agent relationship definition
* Consistent enforcement mechanisms

### 13.3 UNBOUND AGENTS

**Symptoms:**
* No clear ownership
* Undefined behavior scope
* Resource waste
* System confusion

**Causes:**
* Missing user binding
* Unclear agent classification
* Improper instance creation
* Lack of assignment rules

**Prevention:**
* Clear agent classification rules
* Proper instance creation validation
* Assignment enforcement
* Regular ownership validation

## 14. RELATION TO OTHER PRDS

### 14.1 PRD 08_A (Core Agents System)

* Defines agent system architecture
* Provides foundation for agent instances
* Establishes core agent capabilities

### 14.2 PRD 08_B (Agent Map)

* Defines agent roles and responsibilities
* Provides role-based interaction patterns
* Establishes agent coordination framework

### 14.3 PRD 02 (Channels)

* Defines communication infrastructure
* Provides channel-based coordination
* Supports real-time agent interaction

### 14.4 PRD 55 (Workflow Model)

* Defines buffer-first workflow system
* Provides workflow coordination patterns
* Supports agent learning workflows

### 14.5 PRD 86 (Immune System)

* Defines validation and enforcement
* Provides system integrity framework
* Supports learning quality assurance

## 15. IMPLEMENTATION GUIDELINES

### 15.1 AGENT INSTANCE MANAGEMENT

**Instance Creation:**
* Validate actor_source_id and auth_user_id
* Enforce pairing rules
* Initialize instance-specific memory
* Establish learning context

**Instance Lifecycle:**
* Proper instance initialization
* Context-aware learning setup
* Memory allocation and management
* Instance termination and cleanup

### 15.2 LEARNING SYSTEM IMPLEMENTATION

**Context Management:**
* User-specific context isolation
* Learning signal collection
* Context boundary enforcement
* Privacy preservation

**Memory Formation:**
* TOON file generation
* Learning signal processing
* Memory consolidation
* Knowledge integration

### 15.3 PRIVACY AND SECURITY

**Data Protection:**
* User data isolation
* Secure learning contexts
* Privacy-preserving algorithms
* Access control enforcement

**Memory Security:**
* Secure TOON storage
* Controlled memory access
* Knowledge sharing controls
* Audit trail maintenance

## 16. VALIDATION CRITERIA

### 16.1 PAIRING VALIDATION

* One primary agent per user
* Proper instance isolation
* Correct agent classification
* Valid user-agent relationships

### 16.2 LEARNING VALIDATION

* Context-appropriate learning
* Privacy-preserving isolation
* Behavioral consistency
* Performance optimization

### 16.3 MEMORY VALIDATION

* Deterministic TOON generation
* Consistent memory formation
* Efficient storage and retrieval
* Knowledge integrity preservation

## 17. EVOLUTION CONSIDERATIONS

### 17.1 SCALABILITY EVOLUTION

* Enhanced multi-user support
* Optimized resource utilization
* Improved learning algorithms
* Advanced privacy protection

### 17.2 LEARNING EVOLUTION

* Advanced learning models
* Improved context understanding
* Enhanced behavioral adaptation
* Better personalization

### 17.3 MEMORY EVOLUTION

* Advanced TOON formats
* Improved compression algorithms
* Enhanced knowledge representation
* Better memory organization

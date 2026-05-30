# Lupopedia Database Reference

## 1. High-Level Overview

The Lupopedia database serves as the foundational data layer for a semantic operating system that orchestrates multi-agent coordination, content management, and knowledge graph operations. The architecture is designed around several core concepts:

### Core Semantic OS Components

- **Channels**: Organizational units for communication, coordination, and content organization
- **Actors**: Universal registry for all entities (humans, AI agents, services) in the system
- **Agents**: AI entities with specific capabilities, configurations, and tool access
- **Edges**: Graph relationships connecting all objects in the semantic network
- **Atoms**: Fundamental data units with contextual meaning and authority
- **Content**: Structured information with rich metadata and engagement tracking
- **Collections**: Organizational containers for content with tab-based navigation
- **Permissions**: Fine-grained access control and capability management
- **Analytics**: Comprehensive tracking of user behavior and system performance
- **System Logs**: Audit trails, error tracking, and operational monitoring
- **Message Routing**: Multi-modal communication (broadcast, direct, threaded)
- **AI Agent Memory**: Persistent knowledge storage with vector embeddings
- **Federation Nodes**: Multi-node deployment and content synchronization
- **PRD Clusters**: Product requirement document organization and management

### Design Philosophy

The database follows WOLFIE's doctrine of "dumb storage, smart application" - avoiding complex database features like triggers, stored procedures, and foreign keys in favor of application-layer logic. This ensures cross-platform compatibility (MySQL/PostgreSQL) and simplifies maintenance.

### PRD-Driven Architecture

**PRD Files as Single Source of Truth**: In Lupopedia, everything is controlled by Product Requirement Document (PRD) files, including the SQL schema generation. PRD files serve as the authoritative instructions that define:

- **Database Schema**: PRD files contain table definitions, relationships, and constraints that are processed to generate SQL
- **Business Logic**: Application behavior, validation rules, and workflow definitions
- **System Configuration**: Actor definitions, channel structures, and permission models
- **API Specifications**: Endpoint definitions, request/response schemas, and authentication requirements

**PRD Processing Pipeline**:
1. **PRD Creation**: Authors write PRD files in markdown with structured sections
2. **PRD Parsing**: System reads PRD files and extracts structured data
3. **Code Generation**: SQL, PHP, and other code is generated from PRD specifications
4. **Validation**: Generated code is validated against PRD requirements
5. **Deployment**: Validated code is deployed to the system

**PRD Clusters**: PRD clusters explain what was read and provide context for system understanding:

- **Cluster Organization**: PRDs are organized into clusters based on domain (e.g., database, actors, channels)
- **Reading Comprehension**: Each cluster contains analysis of what the system understood from the PRDs
- **Implementation Tracking**: Clusters track which PRD requirements have been implemented
- **Gap Analysis**: Clusters identify missing or incomplete implementations
- **Version Management**: Clusters maintain version history and change tracking

**Example PRD-Driven SQL Generation**:
```markdown
## Table: lupo_actors
### Purpose
Universal registry for all entities in the system
### Columns
- actor_id: BIGINT PRIMARY KEY
- actor_name: VARCHAR(64) NOT NULL
- actor_type: VARCHAR(64) NOT NULL
### Relationships
- Links to lupo_auth_users for human authentication
- Links to lupo_agent_definitions for AI capabilities
```

This PRD section would be processed to generate:
- CREATE TABLE statement
- Index definitions
- Relationship validation code
- Documentation entries

**PRD Cluster Benefits**:
- **Traceability**: Every database table can be traced back to its PRD source
- **Validation**: System validates that implementations match PRD specifications
- **Documentation**: Clusters provide living documentation of system capabilities
- **Evolution**: Changes are tracked through PRD versions and cluster updates

This PRD-driven approach ensures that the database and entire system remain consistent with documented requirements, providing a single source of truth for all system components.

## 2. Subsystem Grouping

### A. Channel System

**Tables**: `lupo_channels`, `lupo_channel_files`, `lupo_channel_content`, `lupo_channel_typing_previews`

**Purpose**: Core organizational and communication infrastructure for the semantic OS.

**Key Relationships**:
- Channels are owned by actors (`owner_actor_id`)
- Channels belong to departments and federation nodes
- Channel content links to filesystem and web paths
- Typing previews enable real-time collaboration

### B. Actor System

**Tables**: `lupo_actors`, `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_actor_auth_users`, `lupo_actor_capabilities`, `lupo_actor_traits`, `lupo_actor_conflicts`, `lupo_actor_departments`, `lupo_actor_collections`, `lupo_actor_faucets`, `lupo_actor_availability_status`, `lupo_actor_reply_templates`, `lupo_actor_moods`, `lupo_actor_actions`, `lupo_actor_handshakes`, `lupo_banned_actors`

**Purpose**: Universal entity registry supporting humans, AI agents, and system services.

**Key Relationships**:
- Actors are the central hub linking to all other systems
- Human actors link to auth_users for authentication
- AI actors link to agent_definitions for capabilities
- Actors participate in channels with specific roles
- Actor capabilities define what actions they can perform

### C. Agent System

**Tables**: `lupo_agent_definitions`, `lupo_agent_capabilities`, `lupo_agent_llm_configs`, `lupo_agent_status`, `lupo_agent_tool_calls`

**Purpose**: AI agent configuration, capability management, and execution tracking.

**Key Relationships**:
- Agents are specialized actors with AI capabilities
- LLM configs define model providers and parameters
- Tool calls track agent interactions with external systems
- Capabilities define what agents can do

### D. Content System

**Tables**: `lupo_contents`, `lupo_content_keywords`, `lupo_content_tags`, `lupo_content_search_index`, `lupo_content_hash_index`, `lupo_comments`

**Purpose**: Rich content management with semantic organization and engagement tracking.

**Key Relationships**:
- Content is created by actors and organized in channels
- Keywords and tags enable semantic discovery
- Search index provides full-text search capabilities
- Hash index prevents duplicate content
- Comments enable discussion and feedback

### E. Collections & Tabs

**Tables**: `lupo_collections`, `lupo_collection_tabs`, `lupo_collection_tab_paths`, `lupo_collection_items`, `lupo_collection_map`, `lupo_collection_tab_map`

**Purpose**: Hierarchical content organization with tab-based navigation systems.

**Key Relationships**:
- Collections organize content into logical groups
- Tabs provide navigation within collections
- Items link content to collections
- Maps define hierarchical relationships

### F. Edges & Graph System

**Tables**: `lupo_edges`, `lupo_edge_types`, `lupo_memory_edges`, `lupo_federated_trust`, `lupo_trust_ladder_registry`

**Purpose**: Semantic graph connecting all objects with weighted, typed relationships.

**Key Relationships**:
- Edges connect any two objects (left/right) with typed relationships
- Memory edges specifically connect knowledge concepts
- Federated trust manages cross-node relationship authority
- Trust ladders define hierarchical trust relationships

### G. Analytics & Tracking

**Tables**: `lupo_visits`, `lupo_visits_daily`, `lupo_paths`, `lupo_paths_raw`, `lupo_paths_daily`, `lupo_paths_monthly`, `lupo_referers`, `lupo_referers_daily`, `lupo_referers_raw`, `lupo_analytics_campaign_vars`

**Purpose**: Comprehensive user behavior tracking and system performance monitoring.

**Key Relationships**:
- Visits track user sessions and page views
- Paths track user navigation flows
- Referers track traffic sources
- Campaign variables track marketing effectiveness

### H. Permissions & Auth

**Tables**: `lupo_auth_users`, `lupo_auth_providers`, `lupo_auth_sessions`, `lupo_auth_audit_log`, `lupo_password_resets`, `lupo_permissions`, `lupo_api_tokens`, `lupo_api_clients`, `lupo_api_rate_limits`, `lupo_api_webhooks`, `lupo_api_token_logs`

**Purpose**: Authentication, authorization, and API access management.

**Key Relationships**:
- Auth users link to actors for human authentication
- Providers enable OAuth and external auth
- API tokens provide programmatic access
- Rate limits prevent abuse
- Permissions define fine-grained access control

### I. System Logs & Internal State

**Tables**: `lupo_audit_log`, `lupo_unified_log`, `lupo_schema_migrations`, `lupo_runtime_errors`, `lupo_runtime_warnings`, `lupo_runtime_validations`, `lupo_system_config`, `lupo_system_health_snapshots`, `lupo_rule_logs`, `lupo_rule_targets`, `lupo_rules`, `lupo_routing_decisions`, `lupo_routing_events`

**Purpose**: System monitoring, audit trails, and operational intelligence.

**Key Relationships**:
- Audit logs track all significant system events
- Unified log consolidates all system logging
- Runtime tracking monitors system health
- Rules engine processes business logic

### J. Memory & Knowledge System

**Tables**: `lupo_memory_nodes`, `lupo_memory_embeddings`, `lupo_memory_keywords`, `lupo_memory_tags`, `lupo_memory_search_index`, `lupo_memory_hash_index`, `lupo_memory_rollups`

**Purpose**: AI agent memory with vector embeddings and semantic search.

**Key Relationships**:
- Memory nodes store knowledge with vector embeddings
- Embeddings enable semantic similarity search
- Keywords and tags provide traditional indexing
- Rollups aggregate memory statistics

### K. Federation & Distribution

**Tables**: `lupo_federation_nodes`, `lupo_federation_categories`, `lupo_federation_category_map`, `lupo_federation_discovery`

**Purpose**: Multi-node deployment and content synchronization.

**Key Relationships**:
- Federation nodes define distributed instances
- Categories organize content for federation
- Discovery enables node finding and connection

### L. Truth & Q&A System

**Tables**: `lupo_truth_questions`, `lupo_truth_answers`, `lupo_truth_evidence`

**Purpose**: Knowledge verification and evidence tracking.

**Key Relationships**:
- Questions are asked by actors in channels
- Answers are provided by actors with evidence
- Evidence links to external sources and content

### M. Projects & Tasks

**Tables**: `lupo_projects`, `lupo_tasks`, `lupo_escalation_tasks`, `lupo_dialog_pending_tasks`, `lupo_human_requests`, `lupo_human_request_responses`, `lupo_human_request_context`

**Purpose**: Project management and task tracking.

**Key Relationships**:
- Projects organize work and resources
- Tasks track specific work items
- Human requests manage user interactions
- Escalation tasks handle priority issues

### N. Legacy / Deprecated / Migrated

**Tables**: `lupo_registry`, `lupo_crafty_syntax_*`, `lupo_anubis_*`, `lupo_crm_*`

**Status**: These tables are maintained for backward compatibility or migration purposes. Many are scheduled for removal in future versions.

### O. PRD Cluster System

**Tables**: `lupo_prd_clusters`, `lupo_prd_documents`, `lupo_prd_sections`, `lupo_prd_implementations`, `lupo_prd_validation_results`

**Purpose**: PRD cluster management tracks what was read from PRDs and how it was implemented.

**Key Relationships**:
- PRD clusters organize related documents by domain
- PRD documents contain the original requirement specifications
- PRD sections break down requirements into implementable units
- Implementations track which code was generated from which PRD section
- Validation results ensure implementations match PRD requirements

**PRD Cluster Reading Comprehension**:
Each cluster contains detailed analysis of what the system understood from PRD files:
- **Requirement Extraction**: Key requirements identified and categorized
- **Implementation Mapping**: Which database tables and code implement each requirement
- **Gap Analysis**: Missing or incomplete implementations identified
- **Validation Results**: Automated validation of implementation correctness
- **Change Tracking**: How requirements evolved over time

**Example PRD Cluster for Database**:
```
PRD Cluster: Database Architecture
- PRD Documents: 00_root_constitutional_system_requirements.md, 16_lupopedia_headers.md
- Requirements Read: 47 database requirements identified
- Implementations: 160 tables generated, 1,200+ indexes created
- Validation: 98% compliance with PRD specifications
- Gaps: 2 legacy tables need migration
```

This system ensures that every database table, column, and relationship can be traced back to specific PRD requirements, providing complete transparency and validation of the system's understanding and implementation.

## 3. Table Details

### Core System Tables

#### lupo_actors
**Purpose**: Universal registry for all entities (humans, AI agents, services)
**Key Columns**: actor_id, actor_name, slug, actor_type, agent_key, is_active, auth_user_id
**Relationships**: Links to auth_users (humans), agent_definitions (AI), channels (membership)
**Subsystem**: Actor System
**Semantic OS Role**: Central entity hub connecting all systems
**Status**: Active
**PRD Source**: PRD 00_root_constitutional_system_requirements.md, Section 21: Actor Model
**PRD Cluster Analysis**: 
- Requirements Read: Universal entity registry, actor ID ranges (AI < 10000, human >= 10000), authentication linkage
- Implementation Generated: CREATE TABLE with proper indexes, actor type validation, auth_user_id foreign reference
- Validation Status: ✅ Fully compliant with PRD specifications
- Cluster Notes: "System correctly understood three-tier actor model and implemented proper ID range separation"

#### lupo_channels
**Purpose**: Organizational units for communication and content
**Key Columns**: channel_id, channel_key, channel_name, channel_type, owner_actor_id, department_id
**Relationships**: Owned by actors, contains content and messages
**Subsystem**: Channel System
**Semantic OS Role**: Primary organizational and communication framework
**Status**: Active
**PRD Source**: PRD 00_root_constitutional_system_requirements.md, Section 22: Channel Architecture
**PRD Cluster Analysis**:
- Requirements Read: Multi-modal messaging (broadcast, direct, thread), channel ownership, department integration
- Implementation Generated: Complete channel system with message routing tables, proper indexing for performance
- Validation Status: ✅ Fully compliant with PRD specifications
- Cluster Notes: "System correctly implemented all three message routing types and channel ownership model"

**PRD-Driven SQL Generation Example**:
```markdown
# PRD Section: Channel Messaging
## Requirement
Channels must support three message types:
1. Broadcast: to all channel members
2. Direct: to specific actor
3. Thread: organized conversation threads

## Implementation
Generated SQL includes:
- lupo_channels (channel definitions)
- lupo_dialog_messages (message storage)
- lupo_dialog_threads (thread organization)
- Proper indexes for message routing
```

#### lupo_contents
**Purpose**: Rich content management with metadata and engagement
**Key Columns**: content_id, title, slug, content_type, actor_id, channel_id, status
**Relationships**: Created by actors, organized in channels, linked to collections
**Subsystem**: Content System
**Semantic OS Role**: Primary information storage and dissemination
**Status**: Active

#### lupo_edges
**Purpose**: Graph relationships connecting all objects
**Key Columns**: edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type
**Relationships**: Connects any two objects with typed relationships
**Subsystem**: Edges & Graph System
**Semantic OS Role**: Semantic knowledge graph backbone
**Status**: Active

#### lupo_auth_users
**Purpose**: Human user authentication and profiles
**Key Columns**: auth_user_id, username, email, password_hash, auth_provider
**Relationships**: Links to actors for human entity representation
**Subsystem**: Permissions & Auth
**Semantic OS Role**: Human identity and access management
**Status**: Active

#### lupo_collections
**Purpose**: Hierarchical content organization
**Key Columns**: collection_id, name, slug, actor_id, parent_id, channel_id
**Relationships**: Organizes content, owned by actors, nested structure
**Subsystem**: Collections & Tabs
**Semantic OS Role**: Content categorization and navigation
**Status**: Active

#### lupo_memory_nodes
**Purpose**: AI agent memory with vector embeddings
**Key Columns**: memory_node_id, memory_type, owner_actor_id, content_hash, embedding_vector
**Relationships**: Owned by actors, indexed for semantic search
**Subsystem**: Memory & Knowledge System
**Semantic OS Role**: Persistent AI knowledge storage
**Status**: Active

#### lupo_dialog_messages
**Purpose**: Multi-modal communication (broadcast, direct, threaded)
**Key Columns**: dialog_message_id, channel_id, from_actor_id, to_actor_id, message_type
**Relationships**: Sent by actors, organized in channels and threads
**Subsystem**: Channel System
**Semantic OS Role**: Communication and coordination backbone
**Status**: Active

### Supporting Tables

#### lupo_agent_definitions
**Purpose**: AI agent configuration and capabilities
**Key Columns**: agent_id, agent_key, name, layer, archetype
**Relationships**: Specialized actors with AI capabilities
**Subsystem**: Agent System
**Semantic OS Role**: AI agent definition and management
**Status**: Active

#### lupo_federation_nodes
**Purpose**: Multi-node deployment coordination
**Key Columns**: federation_node_id, node_name, node_url, status
**Relationships**: Content and actors belong to nodes
**Subsystem**: Federation & Distribution
**Semantic OS Role**: Distributed system coordination
**Status**: Active

#### lupo_atoms
**Purpose**: Fundamental data units with context
**Key Columns**: atom_id, atom_name, context_id, is_authoritative, value_json
**Relationships**: Contextual data storage
**Subsystem**: Content System
**Semantic OS Role**: Atomic data storage
**Status**: Active

#### lupo_permissions
**Purpose**: Fine-grained access control
**Key Columns**: permission_id, actor_id, resource_type, resource_id, capability
**Relationships**: Controls actor access to resources
**Subsystem**: Permissions & Auth
**Semantic OS Role**: Access control enforcement
**Status**: Active

## 4. Relationship Map (Textual)

### Core Architecture Flow

**Actors → Channels**: Actors create and participate in channels as owners, members, or visitors. Channels provide the organizational context for all collaboration.

**Actors → Agents**: AI agents are specialized actors with additional capabilities. Human actors link to auth_users for authentication.

**Agents → Edges**: Agents create and manage edges to represent semantic relationships between objects. Edges form the knowledge graph backbone.

**Content → Atoms**: Content is composed of atomic data units that provide contextual meaning. Atoms can be authoritative or derived.

**Collections → Tabs**: Collections organize content hierarchically, with tabs providing navigation within collections. Tabs map to specific content paths.

**Permissions → All Systems**: Permissions govern access to all resources, enforcing capability-based access control across the entire system.

### Data Flow Patterns

1. **Content Creation**: Actor → Channel → Content → Edges → Collections
2. **Communication**: Actor → Channel → Dialog Messages → Threads
3. **Knowledge Management**: Agent → Memory Nodes → Embeddings → Edges
4. **Access Control**: Actor → Permissions → Resource Access
5. **Federation**: Node → Content Sync → Edge Propagation

### Semantic Graph Integration

The edges system provides the connective tissue for the entire semantic OS:
- Content edges link related documents
- Actor edges define social and organizational relationships
- Memory edges connect knowledge concepts
- Trust edges establish authority and credibility

## 5. Notes on Missing or Ambiguous Tables

### Tables Needing Review

**lupo_registry**: Legacy table being phased out in favor of direct table access. Status: Deprecated

**lupo_crafty_syntax_***: Migration remnants from Crafty Syntax integration. Status: Legacy/Migration

**lupo_anubis_***: File system management and orphan processing. Status: Specialized/Needs Documentation

**lupo_modules**: Module system integration. Status: Needs Review

**lupo_departments**: Organizational structure. Status: Active but Lightly Documented

### Ambiguous Relationships

Some tables reference `domain_id` without clear federation node mapping. The relationship between `lupo_departments` and organizational hierarchy needs clarification.

The `lupo_projects` table exists but has limited integration with other systems - its role in the semantic OS architecture needs definition.

### Documentation Gaps

Several specialized tables (e.g., `lupo_labs_declarations`, `lupo_governance_overrides`) exist but lack comprehensive documentation about their role in the semantic OS.

## 6. System Architecture Summary

The Lupopedia database represents a sophisticated semantic operating system where:

1. **Actors** are the universal entity model, providing consistent identity across all subsystems
2. **Channels** provide organizational and communication context
3. **Edges** create a flexible semantic graph connecting all objects
4. **Content** stores information with rich metadata and engagement tracking
5. **Memory** enables AI agents to maintain persistent knowledge
6. **Permissions** provide fine-grained access control
7. **Federation** enables distributed deployment

The architecture emphasizes flexibility and extensibility, avoiding rigid foreign key constraints in favor of application-layer relationship management. This allows the system to evolve and adapt to new requirements while maintaining data integrity through carefully designed indexing and validation patterns.

The database follows consistent patterns across all tables:
- Primary keys use `[table]_id` format
- Timestamps use BIGINT UTC format
- JSON columns store flexible metadata
- Soft deletion with `is_deleted` flags
- Comprehensive indexing for performance

This design supports the semantic OS vision of a flexible, intelligent system that can adapt to complex multi-agent workflows while maintaining performance and data integrity.

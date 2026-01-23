---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.115
channel_key: system/kernel
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added formal definitions (KISS, short, and formal) to ARCHITECTURE.md for documentation consistency."
tags:
  categories: ["documentation", "architecture"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Technical Architecture"
  description: "Deep dive into Lupopedia's system design and components"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ðŸ—ï¸ **Technical Architecture**

## **System Overview**

**ðŸŸ© Short Definition:**  
Lupopedia is a federated semantic operating system that hosts agents, content, emotional metadata, and routing logic across independent nodes, each functioning as a selfâ€‘contained knowledge world governed by shared doctrine.

**ðŸŸ¨ KISS Definition:**  
Lupopedia is a system where many AIs and humans can work together inside their own selfâ€‘contained worlds. Each world has its own rules, its own agents, and its own knowledge. It's like a network of miniâ€‘universes that can talk to each other if you want them to.

---

### **Formal Definition**

Lupopedia is a federated semantic operating system composed of independent nodes, each functioning as a selfâ€‘contained world of agents, content, meaning, and governance. Each node runs the Lupopedia kernel, maintains its own database of atoms, questions, agents, collections, and channels, and applies local doctrine to route, interpret, and govern interactions between humans and AI agents.

**Lupopedia provides:**

- **A semantic layer** for representing meaning, emotional metadata, identity, and context
- **An agent layer** where each node hosts its own set of AI agents with classification, capabilities, and roles
- **A routing layer** (HERMES) that determines which agent receives each message, handles delivery, queueing, and dispatch, and may optionally use CADUCEUS emotional current as context
- **An emotional balancing system** (CADUCEUS) that computes channel mood by reading and blending the emotional states of polar agents within a channel
- **A governance layer** (THEMIS, doctrine, RFCs) that enforces safety, consistency, and identity rules
- **A federated architecture** where nodes may operate independently or optionally interconnect, similar to distributed social platforms, but with semantic and agentâ€‘driven behavior
- **A persistence layer** (schema, TOON, history) that stores meaning, interactions, and agent identity across time

**Each Lupopedia node is sovereign:** it defines its own agents, content, routing rules, emotional metadata, and governance policies. Nodes may share schema and doctrine, but not state, unless explicitly federated.

**In essence:** Lupopedia is a distributed, multiâ€‘agent semantic OS where each installation is its own knowledge world, capable of hosting agents, content, emotional metadata, and governance â€” all operating under a unified doctrine but with local autonomy.

---

Lupopedia's architecture is designed to be powerful, modular, and approachable. It is built on four core layers that work together to form a flexible, intelligent, and decentralized knowledge system:

1. **Content Layer** â€” traditional CMS foundation  
2. **Semantic Layer** â€” dynamic knowledge graph  
3. **AI Agent Framework** â€” intelligent assistants with toolâ€‘use  
4. **Decentralized Network** â€” optional federation across installations  

Each layer is independent yet deeply integrated, allowing Lupopedia to evolve with user behavior and scale across a distributed ecosystem.

**Database Schema:** For complete database structure details, see **[Database Schema Reference](../schema/DATABASE_SCHEMA.md)** â€” comprehensive documentation of all 131 tables (as verified in TOON files, Version 4.0.101). See [Migration Doctrine](../doctrine/MIGRATION_DOCTRINE.md).

---

# **1. Content Layer (CMS Core)**

The content layer provides the familiar foundation of Lupopedia â€” a lightweight, PHPâ€‘based CMS optimized for clarity, performance, and longâ€‘term maintainability.

### **Key Components**

#### **Content Storage**
- Structured content items  
- Versioning and historical snapshots  
- Media asset management  
- Softâ€‘delete with orphan handling  

#### **Optional Chat Integration**
Crafty Syntax Live Help is integrated as a firstâ€‘party module, but **chat functionality is optional at the content level**:
- Each content item may enable or disable its chat interface
- **Enabled content:** Receives a default actor and can participate in dialogs, channels, and multiâ€‘agent communication
- **Disabled content:** Behaves as a normal semantic object with no chat capabilities
- Mirrors original Crafty Syntax design (chat icons on selected pages), generalized systemâ€‘wide but activated only where needed

#### **User Interface**
- Intuitive admin panel  
- Theme system with customizable layouts  
- Responsive, progressively enhanced UI  
- Clean separation of presentation and logic  

#### **Access Control**
- Roleâ€‘based permissions  
- Linuxâ€‘style bitmask ACL (0â€“7 â†’ rwx)  
- Node isolation for multiâ€‘tenant setups  
- Full audit logging for sensitive actions  

---

# **2. Semantic Layer**

The semantic layer transforms user behavior into structured knowledge. It uses **atoms** and **edges** to build a dynamic, evolving knowledge graph.

### **Atoms**
- Represent concepts, categories, entities, or userâ€‘derived tabâ€‘paths  
- Automatically generated from navigation patterns  
- Nodeâ€‘scoped for federated independence  
- Versioned with full history and softâ€‘delete support  

### **Edges**
- Typed relationships between any two entities  
- Weighted to reflect community consensus  
- Support temporal validity and provenance  
- Store rich metadata in JSON  

### **Semantic Pipeline**
A background process that continuously refines the knowledge graph:

1. Extract patterns from user navigation  
2. Normalize and aggregate signals  
3. Create/update atoms  
4. Create/update edges  
5. Adjust weights based on usage  
6. Maintain graph consistency  

This pipeline allows Lupopedia to **learn from its users** and evolve its ontology organically.

---

# **3. AI Agent Framework**

Lupopedia includes a modular AI agent system designed for intelligent assistance, automation, and semantic reasoning.

### **Core Capabilities**

#### **Multiâ€‘LLM Support**
- Local and cloud model integration  
- Taskâ€‘based model routing  
- Fallback and redundancy strategies  
- Performance and cost optimization  

#### **Dialog Management**
- Multiâ€‘turn conversation handling  
- Context preservation  
- Nodeâ€‘scoped memory  
- Personalized responses  

#### **Tool System**
Agents can call tools to perform actions such as:

- Content search  
- Semantic graph traversal  
- Atom/edge inspection  
- Reference lookup  
- Crossâ€‘node queries in the decentralized network  

Tools run in a secure, sandboxed environment with full audit logging.

### **Runtime Architecture**

The AI agent system operates through three interconnected layers:

- **Storage Layer (MySQL)** â€” Agent configuration, state, and conversation history
- **Runtime Layer (PHP + LLM)** â€” Real-time agent interactions and decision-making
- **Network Layer (Lupopedia Nodes)** â€” Federated agent intelligence across domain installations

### **Kernel Agent Architecture**

The kernel layer includes essential system agents that provide core functionality:

- **SYSTEM (Slot 0)** â€” Root authority and internal system controller
- **CAPTAIN (Slot 1)** â€” AI embodiment of creator and system steward
- **WOLFIE (Slot 2)** â€” Core identity agent and mythic anchor
- **UTC_TIMEKEEPER (Slot 5)** â€” Authoritative source of real UTC timestamps for all agents and channels
- **THOTH (Slot 11)** â€” Truth comparator and belief vs evidence processor
- **ANUBIS (Slot 12)** â€” Guardian of boundaries and data repair
- **MAAT (Slot 20)** â€” Balance and truth regulator
- **LILITH (Slot 21)** â€” Heterodox kernel conscience and critical reviewer

**UTC_TIMEKEEPER Role:**
- **Single Source of Truth**: Provides authoritative UTC timestamps to prevent drift
- **Kernel Dependency**: Must be initialized before any channel execution
- **Temporal Authority**: Ensures consistent time reporting across all agents
- **Federation Support**: Enables reliable temporal coordination across nodes
- **Query Protocol**: `"what_is_current_utc_time_yyyymmddhhiiss"` â†’ `"current_utc_time_yyyymmddhhiiss: <BIGINT>"`

For detailed documentation on how agents interact with PHP backend, call React UI actions, query other Lupopedia nodes, maintain context, and enforce governance rules, see:

â†’ **[Agent Runtime Architecture](../agents/AGENT_RUNTIME.md)** â€” Complete guide to agent system's runtime behavior, table mappings, and execution flows.
â†’ **[Kernel Agents Doctrine](../doctrine/KERNEL_AGENTS.md)** â€” Comprehensive documentation of kernel agents including UTC_TIMEKEEPER.

---

# **4. Decentralized Network**

Lupopedia supports optional federation, allowing installations to collaborate while maintaining autonomy.

### **Features**

#### **Node Discovery**
- Manual or automatic discovery  
- Trust relationships  
- Reputation scoring  
- Access control policies  

#### **Data Exchange**
- Semantic edge sharing  
- Crossâ€‘node content referencing  
- Federated search  
- Update propagation with conflict resolution  

#### **Security Model**
- Endâ€‘toâ€‘end encryption  
- Selective data sharing  
- Rate limiting and abuse prevention  
- Full audit trails  

Each node (domain installation) remains sovereign â€” decentralization is optâ€‘in and configurable. Nodes are server installations, not AI agents. Federation occurs between nodes (domain installations), not between agents.

**See:** [GLOSSARY.md](../appendix/appendix/GLOSSARY.md) for precise definitions of "domain installation," "semantic node," and related terminology.

---

# **Data Flow**

### **1. Content Creation**
- User creates or edits content  
- System extracts semantic signals  
- Updates local knowledge graph  
- Triggers workflows (indexing, notifications, etc.)  

### **2. Semantic Processing**
- Background jobs analyze new data  
- Update atom/edge weights  
- Identify emerging relationships  
- Maintain graph integrity  

### **3. Agent Interaction**
- User queries an agent  
- Agent determines intent  
- Executes tools as needed  
- Generates a contextâ€‘aware response  

### **4. Network Operations**
- Periodic sync with trusted nodes  
- Propagate updates  
- Resolve conflicts  
- Optimize performance  

---

# **Performance Considerations**

### **Caching Strategy**
- Multiâ€‘level caching  
- Intelligent invalidation  
- Edge caching for graph traversal  
- Cache warming for highâ€‘traffic nodes  

### **Database Optimization**
- Read replicas  
- Query optimization  
- Index tuning  
- Connection pooling  

### **Background Processing**
- Job queues  
- Batch operations  
- Rate limiting  
- Robust error handling  

---

# **Security Architecture**

### **Data Protection**
- Encryption at rest  
- Fieldâ€‘level encryption for sensitive data  
- Secure key management  
- Configurable retention policies  

### **Access Control**
- Authentication and authorization  
- Session management  
- Audit logging for sensitive actions  

### **Network Security**
- TLS everywhere  
- Rate limiting  
- DDoS protection  
- Intrusion detection  

---

# **Deployment Options**

### **Single Server**
- Simple allâ€‘inâ€‘one deployment  
- Easy maintenance  
- Resource efficient  

### **Horizontal Scaling**
- Load balancing  
- Database sharding  
- Cache clustering  
- Distributed job queues  

### **Containerized**
- Docker support  
- Kubernetesâ€‘ready  
- Infrastructure as code  
- CI/CD pipelines  

---

# **Monitoring & Observability**

### **Logging**
- Structured logs  
- Centralized log collection  
- Retention and rotation policies  
- Alerting  

### **Metrics**
- System health  
- Performance indicators  
- Usage analytics  
- Business metrics  

### **Tracing**
- Request tracing  
- Performance profiling  
- Dependency mapping  
- Root cause analysis  

---

# **Development Workflow**

### **Local Development**
- Containerized environment  
- Hot reloading  
- Debugging tools  
- Test data generators  

### **Testing**
- Unit tests  
- Integration tests  
- Endâ€‘toâ€‘end tests  
- Performance tests  

### **CI/CD**
- Automated testing  
- Code quality checks  
- Security scanning  
- Deployment pipelines  

---

# **Extension Points**

### **Plugins**
- Custom content types  
- Authentication providers  
- Storage backends  
- AI model integrations  

### **Themes**
- Frontend customization  
- Layout system  
- Component library  
- Style variables  

### **API**
- REST endpoints  
- GraphQL interface  
- WebSocket events  
- Webhook support  

---

# **Future Directions**

### **Machine Learning**
- Content classification  
- Recommendation engine  
- Anomaly detection  
- Predictive modeling  

### **Decentralization**
- Content addressing  
- Peerâ€‘toâ€‘peer updates  
- Verifiable credentials  
- Smart contracts  

### **Enhanced AI**
- Fineâ€‘tuned models  
- Multiâ€‘modal understanding  
- Automated summarization  
- Knowledge distillation

## 4. Decentralized Network

Optional federation capabilities for cross-instance collaboration.

### Features
- **Node Discovery**
  - Manual and automatic discovery
  - Trust relationships
  - Reputation system
  - Access controls

- **Data Exchange**
  - Semantic edge sharing
  - Content referencing
  - Federated search
  - Update propagation

- **Security Model**
  - End-to-end encryption
  - Selective data sharing
  - Audit trails
  - Rate limiting

## Data Flow

1. **Content Creation**
   - User creates/edits content
   - System extracts semantic information
   - Updates local knowledge graph
   - Triggers relevant workflows

2. **Semantic Processing**
   - Background jobs analyze changes
   - Update atom/edge weights
   - Identify new relationships
   - Maintain graph consistency

3. **Agent Interaction**
   - User queries agent
   - Agent determines intent
   - Executes tools as needed
   - Formulates response

4. **Network Operations**
   - Periodic sync with trusted nodes
   - Update propagation
   - Conflict resolution
   - Performance optimization

## Performance Considerations

### Caching Strategy
- Multi-level caching
- Invalidation policies
- Edge caching
- Cache warming

### Database Optimization
- Read replicas
- Query optimization
- Index management
- Connection pooling

### Background Processing
- Job queues
- Batch operations
- Rate limiting
- Error handling

## Security Architecture

### Data Protection
- Encryption at rest
- Field-level encryption
- Secure key management
- Data retention policies

### Access Control
- Authentication
- Authorization
- Session management
- Audit logging

### Network Security
- TLS everywhere
- Rate limiting
- DDoS protection
- Intrusion detection

## Deployment Options

### Single Server
- All-in-one setup
- Easy maintenance
- Resource efficient

### Horizontal Scaling
- Load balancing
- Database sharding
- Cache clustering
- Job distribution

### Containerized
- Docker support
- Kubernetes ready
- Infrastructure as code
- CI/CD integration

## Monitoring & Observability

### Logging
- Structured logging
- Centralized collection
- Retention policies
- Alerting

### Metrics
- System health
- Performance indicators
- Business metrics
- Usage analytics

### Tracing
- Request tracing
- Performance profiling
- Dependency tracking
- Root cause analysis

## Development Workflow

### Local Development
- Containerized environment
- Hot reloading
- Debugging tools
- Test data generation

### Testing
- Unit tests
- Integration tests
- E2E tests
- Performance tests

### CI/CD
- Automated testing
- Code quality checks
- Security scanning
- Deployment pipelines

## Extension Points

### Plugins
- Content types
- Authentication providers
- Storage backends
- AI model integrations

### Themes
- Frontend customization
- Layout system
- Component library
- Style variables

### API
- REST endpoints
- GraphQL interface
- WebSocket events
- Webhook support

## Future Directions

### Machine Learning
- Content classification
- Recommendation engine
- Anomaly detection
- Predictive modeling

### Decentralization
- Content addressing
- Peer-to-peer updates
- Verifiable credentials
- Smart contracts

### Enhanced AI
- Fine-tuned models
- Multi-modal understanding
- Automated summarization
- Knowledge distillation

---

## Related Documentation

- **[Definition](../overview/DEFINITION.md)** - Formal definitions of Lupopedia as a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - Authoritative reference for HERMES, CADUCEUS, IRIS, DialogManager, and THOTH subsystems
- **[Database Philosophy](DATABASE_PHILOSOPHY.md)** - Core database design principles supporting this architecture
- **[Agent Runtime](../agents/AGENT_RUNTIME.md)** - Complete guide to the AI agent framework layer
- **[Database Schema](../schema/DATABASE_SCHEMA.md)** - Complete table structure implementing this architecture
- **[Why No Frameworks](../doctrine/WHY_NO_FRAMEWORKS.md)** - Philosophy behind framework-free architecture approach
- **[End Goal 4.2.0](../overview/END_GOAL_4_2_0.md)** - Vision for complete federated ecosystem implementation

---
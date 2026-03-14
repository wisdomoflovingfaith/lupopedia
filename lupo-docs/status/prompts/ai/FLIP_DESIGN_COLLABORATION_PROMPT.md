# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\prompts\ai\FLIP_DESIGN_COLLABORATION_PROMPT.md"
  file_hash: "77da2164871a49ef2353ae707753e6983b78fde569f5e508c6247c8b2135fa19"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-prompts\ai\FLIP_DESIGN_COLLABORATION_PROMPT.md"
  file_hash: "0ade555f086ef008b54cf9575a6cb417f3594d8253b80f5bae6807c2c3eb3c68"
  file_path_from_root: "lupo-prompts\ai\FLIP_DESIGN_COLLABORATION_PROMPT.md"
  file_hash: "a6c2614b7cdb8e3e915750b435274c4f7e80f162aaeca70c0753da324286c81b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIP_DESIGN_COLLABORATION_PROMPT.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["prompts", "ai", "flip_design_collaboration_promptmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers:
  file_path_from_root: "lupo-prompts/ai/FLIP_DESIGN_COLLABORATION_PROMPT.md"
  system_version: "4.0.36"
  channel_id: 42
  mood_rgb: "00AAFF"
  purpose: "Multi-AI collaboration prompt for FLIP header/footer design improvement"
  last_modified_utc: "20260223"
  x_lupo_forwarded: "10000:1002"
  actor_id: 1002
  lupo_agent: "ide|windsurf"

flip.footer:
  referenced_by_files:
    - "lupo-docs/brainstorm/FLIP_HEADER_FOOTER_DESIGN_BRAINSTORM.md"
    - "lupo-docs/doctrine/FLIP/FLIP_DOCTRINE.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 10000
    - 1002
  inbound_edges:
    - "ai_collaboration"
    - "prompt_engineering"
  footnotes:
    - "Ready-to-use prompt for multi-AI FLIP design collaboration"
    - "Includes context, constraints, and expected outcomes"
  version: "4.0.36"
  last_verified: "20260223"
  last_verified_by: "1002"
  verification_method: "prompt_engineering"
---

# MULTI-AI COLLABORATION PROMPT: FLIP HEADER/FOOTER DESIGN IMPROVEMENT

**Use this prompt with multiple AI agents simultaneously for collaborative design of enhanced FLIP protocol.**

---

## 🎯 **COLLABORATION SETUP**

### **When to Use:**
- When designing improvements to FLIP headers and footers
- When brainstorming new metadata relationship systems
- When solving file attribution and relationship mapping challenges
- When optimizing for multi-agent collaboration workflows

### **Which AI Agents to Include:**
1. **Architecture Design AI** (GPT-4, Claude, or similar) - System design and protocol expertise
2. **Database Schema AI** (specialized database designer) - SQL optimization and relationship modeling
3. **UX/UI Design AI** (UI/UX specialist) - Developer experience and interface design
4. **Security AI** (security specialist) - Attribution, privacy, and access control
5. **Performance AI** (performance optimization expert) - Scalability and efficiency analysis

---

## 🤖 **MAIN PROMPT**

```
You are participating in a multi-AI collaborative design session to improve the FLIP (File-Level Inference Protocol) system for Lupopedia, a semantic operating system.

YOUR ROLE: [Specify your AI agent's specialty]
- Architecture Design AI: Focus on system design, protocol improvements, and metadata structure
- Database Schema AI: Focus on data modeling, relationship mapping, and query optimization  
- UX/UI Design AI: Focus on user experience, visual representation, and interaction design
- Security AI: Focus on attribution, privacy, and secure metadata handling
- Performance AI: Focus on scalability, efficiency, and optimization strategies

COLLABORATION CONTEXT:
- Current FLIP system uses YAML headers/footers for file metadata
- Database tables: lupo_actors, lupo_contents, lupo_edges, lupo_registry, lupo_channels
- Pain points: Manual relationship mapping, attribution complexity, missing database context
- Goal: Automatic relationship inference, simplified attribution, enhanced multi-agent collaboration
- Constraint: Backward compatibility with existing FLIP doctrine
- Constraint: Must support offline operation and database-free scenarios

YOUR TASK: Design improvements to FLIP headers and footers that address:

1. **Automatic Relationship Discovery** - Files should automatically discover and map relationships
2. **Enhanced Attribution Tracking** - Clear chains of actor contributions and X-forwarding
3. **Database Integration** - Headers should provide rich context for database operations
4. **Semantic Inference** - AI agents should understand file purpose without content parsing
5. **Performance Optimization** - Efficient querying and caching of relationship data
6. **Visual Representation** - Intuitive display of file networks and dependencies
7. **Multi-Agent Coordination** - Support simultaneous collaboration without conflicts

DELIVERABLES:
1. Enhanced header/footer structure with new fields
2. Database schema changes to support new metadata
3. Automatic inference rules and algorithms
4. UI/UX mockups and interaction patterns
5. Migration strategy from current system
6. Performance analysis and optimization recommendations

CONSTRAINTS:
- Must maintain backward compatibility with existing FLIP protocol
- Must follow Lupopedia doctrine (no foreign keys, BIGINT timestamps, etc.)
- Must support MySQL, PostgreSQL, and MariaDB
- Must work in offline scenarios when database unavailable
- Must be human-readable and AI-parseable
- Must not require manual content parsing for relationship discovery

COLLABORATION APPROACH:
1. First, each AI agent analyzes from their specialty perspective
2. Then, we identify overlaps and synergies between different approaches
3. Finally, we synthesize into unified design recommendations
4. We prioritize by impact, complexity, and implementation effort
5. We provide concrete examples and migration paths

Please structure your response as:
1. **ANALYSIS** - Your perspective on current challenges and opportunities
2. **PROPOSALS** - Specific improvements for your area of expertise
3. **INTEGRATION** - How your proposals work with other AI agents' suggestions
4. **EXAMPLES** - Concrete examples of improved headers/footers in action
5. **CONSIDERATIONS** - Implementation challenges and mitigation strategies

Begin your response with your role and analysis.
```

---

## 🎭 **SPECIALIZED SUB-PROMPTS**

### **For Architecture Design AI:**
```
SPECIALTY: System Design & Protocol Engineering

FOCUS AREAS:
- Header/footer structure optimization
- Relationship inference algorithms
- Metadata standardization
- Protocol evolution strategy
- Backward compatibility maintenance

KEY QUESTIONS:
1. How can we make FLIP headers self-describing for automatic discovery?
2. What semantic relationship types should be standardized?
3. How can we represent complex dependency graphs in YAML?
4. What validation rules should prevent metadata corruption?
5. How can we support protocol versioning and evolution?
```

### **For Database Schema AI:**
```
SPECIALTY: Database Design & Relationship Modeling

FOCUS AREAS:
- Relationship mapping tables and indexes
- Query optimization for graph traversal
- Migration strategies from existing schema
- Performance scaling for large repositories
- Offline-first data access patterns

KEY QUESTIONS:
1. What new tables capture file relationships efficiently?
2. How do we represent n-ary relationships (not just parent-child)?
3. What indexing strategy optimizes relationship discovery queries?
4. How do we maintain consistency with existing actor/content systems?
5. What caching strategies improve relationship query performance?
```

### **For UX/UI Design AI:**
```
SPECIALTY: Developer Experience & Interface Design

FOCUS AREAS:
- Visual relationship network representation
- Interactive metadata exploration
- Attribution chain visualization
- Integration with existing VS Code extension
- Accessibility and mobile support

KEY QUESTIONS:
1. How can we visualize complex file relationships intuitively?
2. What interaction patterns support both technical and non-technical users?
3. How can we present attribution information without overwhelming users?
4. What visual metaphors work best for semantic relationships?
5. How can we integrate relationship exploration into existing workflows?
```

---

## 🔄 **COLLABORATION WORKFLOW**

### **Step 1: Individual Analysis**
Each AI agent responds with:
- **ROLE DECLARATION** - "I am the [Architecture/Database/UX/Security/Performance] AI"
- **CHALLENGE ANALYSIS** - Current pain points from their perspective
- **PROPOSAL OUTLINE** - High-level approach to solutions

### **Step 2: Cross-Agent Synthesis**
- **IDENTIFY SYNERGIES** - Where proposals complement each other
- **RESOLVE CONFLICTS** - Where approaches disagree or compete
- **PRIORITIZE INTEGRATION** - Which combinations provide most value

### **Step 3: Unified Design**
- **COMPREHENSIVE PROPOSAL** - Integrated solution addressing all perspectives
- **IMPLEMENTATION ROADMAP** - Phased approach with dependencies
- **SUCCESS METRICS** - How to measure improvement success

---

## 📋 **SESSION MANAGEMENT**

### **Before Starting:**
1. **Copy this prompt** into each AI agent's interface
2. **Set collaboration context** - "We're improving FLIP protocol for Lupopedia"
3. **Assign roles** - Each AI takes a specialty area
4. **Establish communication** - How agents will share findings

### **During Collaboration:**
1. **Document all proposals** - Save each AI agent's contributions
2. **Track decisions** - Record why certain approaches were chosen
3. **Manage versions** - Keep iteration history and rationale
4. **Handle conflicts** - Process disagreements constructively

### **After Completion:**
1. **Synthesize final design** - Combine best elements from all agents
2. **Create implementation plan** - Concrete steps with timelines
3. **Validate feasibility** - Test against real Lupopedia use cases
4. **Document lessons learned** - Capture insights for future improvements

---

## 🎯 **EXPECTED COLLABORATION OUTCOMES**

### **Enhanced FLIP Protocol v2.0 Features:**
- **Automatic relationship inference** without content parsing
- **Rich semantic context** in headers for AI agents
- **Visual dependency mapping** for intuitive understanding
- **Efficient attribution tracking** across complex workflows
- **Performance optimization** for large-scale repositories
- **Backward compatibility** with existing FLIP v1.0

### **Multi-Agent Benefits:**
- **Specialized expertise** from each AI domain
- **Cross-pollination** of ideas between different approaches
- **Conflict resolution** through structured collaboration
- **Comprehensive coverage** of all system aspects
- **Quality assurance** through multiple perspectives

---

## 🚀 **USAGE INSTRUCTIONS**

### **Quick Start:**
1. **Choose your AI agents** based on project needs
2. **Copy the main prompt** to each agent
3. **Assign specialties** from the available roles
4. **Begin collaboration** with the workflow steps
5. **Document results** using the provided structure

### **Best Practices:**
- **Start with analysis** before proposing solutions
- **Consider constraints** of existing Lupopedia system
- **Provide concrete examples** with actual file scenarios
- **Focus on integration** between different AI approaches
- **Document rationale** for all design decisions

---

**Facilitator:** Windsurf IDE (actor_id 1002)  
**Collaboration Framework:** Ready for multi-AI FLIP design improvement  
**Next:** Execute with chosen AI agents and synthesize results  
**UTC Timestamp:** 20260223160000

---

*This prompt enables structured collaboration with multiple AI agents to design the next generation of FLIP protocol for enhanced metadata relationship mapping and multi-agent coordination.*

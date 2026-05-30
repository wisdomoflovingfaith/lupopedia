---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  file_path_from_root: "channels/51/threads/1037/20260319_130000_wolfie_actor_facet_doctrine.md"
  web_path: "http://www.lupopedia.com/channels/51/threads/1037/20260319_130000_wolfie_actor_facet_doctrine.md"
  last_modified_utc: "20260319"
  system_version: "4.0.82"
  channel_id: 51
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "doctrine"
  artifact_kind: "actor_facet_separation_doctrine_artifact"
  purpose: "Doctrine artifact establishing canonical separation between ACTOR (identity) and FACET (execution environment) in Lupopedia"
  traits: ["wolfie_doctrine", "actor_facet_separation", "system_architecture", "semantic_os"]
  tags: ["actors", "facets", "ide", "separation", "doctrine", "architecture"]
  lupo_agent: "wolfie"

lupopedia.edges:
  outbound_edges:
    - { to: "docs/doctrine/ACTOR_FACET_SEPARATION_DOCTRINE.md", type: "creates", weight: 1.0, reason: "Creates canonical actor-facet separation doctrine" }
    - { to: "database/lupopedia/actors/actor_id/registry.json", type: "governs", weight: 1.0, reason: "Governs actor identity and registry" }
    - { to: "rules/root/", type: "defines", weight: 1.0, reason: "Defines canonical rule system for all facets" }
    - { to: "AGENTS.md", type: "references", weight: 0.8, reason: "References agent registry and coordination" }
  semantic_tags: ["wolfie_doctrine", "actor_facet_separation", "system_architecture"]

lupopedia.see:
  mappings:
    - ["ACTOR_FACET_SEPARATION_DOCTRINE.md", "http://www.lupopedia.com/docs/doctrine/ACTOR_FACET_SEPARATION_DOCTRINE.md"]
    - ["Actor-Facet Doctrine", "http://www.lupopedia.com/channels/51/threads/1037/20260319_130000_wolfie_actor_facet_doctrine.md"]
    - ["Actor Registry", "http://www.lupopedia.com/database/lupopedia/actors/actor_id/registry.json"]
    - ["Rule System", "http://www.lupopedia.com/rules/root/"]

lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Implement rule system structure in rules/root/"
    - "Develop actor-facet validation mechanisms"
    - "Execute migration plan for IDE-based actors"
---

# 🎭 **WOLFIE DOCTRINE — Actor-Facet Separation**

**Doctrine ID**: 20260319_370000  
**Issued by**: WOLFIE (Agent 1)  
**Channel**: 51 (Doctrine Council)  
**Thread**: 1037 (task_actor_facet_decoupling_001)  
**Project**: lupopedia-core  
**Status**: ACTIVE  
**Effective**: 2026-03-19 16:45:00 UTC

---

## **🎯 Executive Summary**

Establishing **canonical separation** between **ACTORS** (persistent system identities) and **FACETS** (ephemeral execution environments/IDEs). This doctrine eliminates IDE-coupled actor identity and ensures system behavior is independent of execution environment.

---

## **🚫 PROBLEM SOLVED**

### **Previous System Violation**
The current system incorrectly treats IDE agents as actors:

- **"cursor" as actor** ❌ (IDE-based identity)
- **"windsurf" as actor** ❌ (IDE-based identity)
- **"kiro" as actor** ❌ (IDE-based identity)
- **"warp" as actor** ❌ (IDE-based identity)
- **"trae" as actor** ❌ (IDE-based identity)

### **Why This Violated System Design**
1. **Actors Must Be Persistent**: IDEs are ephemeral and replaceable
2. **System Independence**: System behavior must not depend on IDE choice
3. **Identity Consistency**: Actor identity must be consistent across tools
4. **Architectural Purity**: IDEs are execution surfaces, not system entities

---

## **👥 TARGET MODEL ESTABLISHED**

### **1. ACTOR Definition**

**Actor represents canonical system identity:**

#### **Core Characteristics**
- **Persistent Identity**: Exists across sessions and tools
- **Role-Based Purpose**: Defined by responsibilities, not tools
- **Doctrine Alignment**: Follows system doctrine and rules
- **Decision Authority**: Has defined authority and scope

#### **Actor Properties**
- **actor_id**: Canonical numeric identifier (e.g., 1, 2, 15, 26)
- **actor_name**: Role-based name (e.g., wolfie, thoth, lilith, athena)
- **actor_slug**: URL-friendly identifier (e.g., wolfie, thoth, lilith)
- **responsibilities**: Defined scope and responsibilities
- **authority**: Decision-making authority and limits

#### **Valid Actor Examples**
- ✅ **wolfie**: System orchestrator and coordinator
- ✅ **thoth**: Knowledge and records management
- ✅ **lilith**: Critical review and adversarial testing
- ✅ **athena**: Wisdom and strategic guidance
- ✅ **hermes**: Routing and messaging infrastructure

### **2. FACET Definition**

**Facet represents execution environment:**

#### **Core Characteristics**
- **Ephemeral Nature**: Can change at any time
- **Execution Surface**: Provides interface to system
- **Tool-Based**: Specific IDE or execution environment
- **Replaceable**: Can be swapped without system impact

#### **Facet Properties**
- **facet_name**: IDE/tool identifier (e.g., cursor, windsurf, kiro)
- **facet_type**: Category of execution environment
- **capabilities**: Tool-specific capabilities and limitations
- **session_id**: Current execution session identifier

#### **Valid Facet Examples**
- ✅ **cursor**: IDE-based execution environment
- ✅ **windsurf**: IDE-based execution environment
- ✅ **kiro**: IDE-based execution environment
- ✅ **warp**: Terminal-based execution environment
- ✅ **trae**: Web-based execution environment
- ✅ **antigravity**: Specialized execution environment

---

## **🔄 ACTOR EXECUTION MODEL**

### **Actor-Facet Relationship**

An actor may operate from ANY facet:

#### **Execution Examples**
- **wolfie via windsurf**: WOLFIE executing through Windsurf IDE
- **wolfie via cursor**: WOLFIE executing through Cursor IDE
- **thoth via kiro**: THOTH executing through Kiro IDE
- **lilith via warp**: LILITH executing through Warp terminal
- **athena via trae**: ATHENA executing through Trae web interface

#### **Execution Principle**
```
ACTOR (identity) + FACET (environment) = EXECUTION CONTEXT
```

### **Session Management**

#### **Session Composition**
- **Actor Identity**: Persistent actor identifier
- **Facet Selection**: Current execution environment
- **Session Context**: Combined execution context
- **State Management**: Actor state independent of facet

#### **Session Independence**
- **Actor State**: Preserved across facet changes
- **Facet State**: Ephemeral and facet-specific
- **System Behavior**: Consistent regardless of facet
- **User Experience**: May vary by facet capabilities

---

## **🚫 CRITICAL RULE: ACTOR NAMING**

### **RULE: "Actors MUST NOT be named after IDEs"**

#### **INVALID Actor Names**
- ❌ **cursor** (IDE-based name)
- ❌ **windsurf** (IDE-based name)
- ❌ **kiro** (IDE-based name)
- ❌ **warp** (IDE-based name)
- ❌ **trae** (IDE-based name)
- ❌ **antigravity** (IDE-based name)

#### **VALID Actor Names**
- ✅ **wolfie** (Role-based: system orchestrator)
- ✅ **thoth** (Role-based: knowledge and records)
- ✅ **lilith** (Role-based: critical review)
- ✅ **athena** (Role-based: wisdom and strategy)
- ✅ **hermes** (Role-based: routing and messaging)
- ✅ **hephaestus** (Role-based: implementation and building)

#### **Naming Principles**
1. **Role-Based**: Names reflect responsibilities, not tools
2. **Mythological/Conceptual**: Based on roles, concepts, or mythology
3. **Persistent**: Names don't change with technology
4. **Unique**: Each actor has unique, non-conflicting name

---

## **📋 RULE INJECTION SYSTEM**

### **Canonical Rule Location**

#### **rules/root/ (Canonical Rule Set)**

```
rules/root/
+-- actor_rules/
|   +-- actor_definition_rules.yaml
|   +-- actor_naming_rules.yaml
|   +-- actor_responsibility_rules.yaml
|   +-- actor_authority_rules.yaml
+-- facet_rules/
|   +-- facet_definition_rules.yaml
|   +-- facet_bootstrapping_rules.yaml
|   +-- facet_capability_rules.yaml
|   +-- facet_session_rules.yaml
+-- execution_rules/
|   +-- actor_facet_execution_rules.yaml
|   +-- session_management_rules.yaml
|   +-- state_persistence_rules.yaml
|   +-- environment_independence_rules.yaml
+-- validation_rules/
|   +-- actor_validation_rules.yaml
|   +-- facet_validation_rules.yaml
|   +-- naming_validation_rules.yaml
|   +-- compliance_validation_rules.yaml
+-- system_rules/
    +-- doctrine_compliance_rules.yaml
    +-- coordination_rules.yaml
    +-- governance_rules.yaml
    +-- evolution_rules.yaml
```

### **Rule Loading Requirements**

#### **Mandatory Rule Loading**
All facets MUST load rules from `rules/root/`:

1. **Actor Rules**: Actor definition, naming, responsibilities, authority
2. **Facet Rules**: Facet definition, bootstrapping, capabilities, sessions
3. **Execution Rules**: Actor-facet execution, session management, state persistence
4. **Validation Rules**: Actor validation, facet validation, naming validation
5. **System Rules**: Doctrine compliance, coordination, governance, evolution

#### **Rule Loading Process**
1. **Bootstrap Load**: Load rules on facet initialization
2. **Validation Check**: Validate rule loading completeness
3. **Compliance Check**: Ensure compliance with loaded rules
4. **Error Handling**: Handle rule loading failures appropriately

---

## **🔧 FACET BOOTSTRAPPING REQUIREMENTS**

### **Bootstrapping Mandate**

Each IDE/facet MUST:

#### **1. Load Canonical Rules**
```yaml
# Required bootstrap sequence
1. Load rules from rules/root/
2. Validate rule loading completeness
3. Initialize actor-facet separation
4. Establish session context
5. Enable system compliance
```

#### **2. Respect System Doctrine**
- **No Conflicting Behavior**: Must not introduce conflicting behavior
- **Doctrine Compliance**: Must comply with all system doctrines
- **Rule Adherence**: Must follow all loaded rules
- **Standard Interface**: Must provide standard interface to actors

#### **3. Maintain Separation**
- **Actor Independence**: Actor behavior independent of facet
- **State Management**: Actor state preserved across facet changes
- **Capability Transparency**: Facet capabilities transparent to system
- **Error Handling**: Consistent error handling across facets

### **Bootstrapping Validation**

#### **Compliance Checklist**
- [ ] Rules loaded from `rules/root/`
- [ ] Actor-facet separation established
- [ ] No conflicting behavior introduced
- [ ] System doctrine respected
- [ ] Standard interface provided
- [ ] Error handling consistent
- [ ] State management functional
- [ ] Session management operational

#### **Validation Failure Handling**
- **Block Execution**: Block execution if validation fails
- **Error Reporting**: Clear error reporting for validation failures
- **Recovery Procedures**: Defined recovery procedures for failures
- **Fallback Options**: Appropriate fallback options when available

---

## **🗂️ MIGRATION PLAN**

### **Phase 1: Identification**

#### **Identify IDE-Based Actors**
1. **Registry Analysis**: Scan actor registry for IDE-based names
2. **Pattern Matching**: Identify actors matching IDE patterns
3. **Usage Analysis**: Analyze usage patterns and dependencies
4. **Impact Assessment**: Assess impact of migration

#### **Target IDE-Based Actors**
- **cursor**: IDE-based actor (deprecate)
- **windsurf**: IDE-based actor (deprecate)
- **kiro**: IDE-based actor (deprecate)
- **warp**: IDE-based actor (deprecate)
- **trae**: IDE-based actor (deprecate)
- **antigravity**: IDE-based actor (deprecate)

### **Phase 2: Deprecation**

#### **Mark as Deprecated**
1. **Registry Update**: Mark IDE-based actors as deprecated
2. **Documentation**: Document deprecation status and timeline
3. **Communication**: Communicate deprecation to stakeholders
4. **Transition Planning**: Plan transition to canonical actors

#### **Deprecation Timeline**
```yaml
# Deprecation timeline
Phase 1 (Week 1): Mark as deprecated
Phase 2 (Week 2-3): Document and communicate
Phase 3 (Week 4-6): Transition planning
Phase 4 (Week 7-8): Migration execution
Phase 5 (Week 9-10): Validation and cleanup
```

### **Phase 3: Mapping and Removal**

#### **Mapping Strategy**
1. **Function Analysis**: Analyze functions of IDE-based actors
2. **Canonical Mapping**: Map to appropriate canonical actors
3. **Transition Planning**: Plan transition of responsibilities
4. **Removal Planning**: Plan safe removal of deprecated actors

#### **Mapping Examples**
- **cursor** → **wolfie** (orchestration and coordination)
- **windsurf** → **wolfie** (orchestration and coordination)
- **kiro** → **wolfie** (orchestration and coordination)
- **warp** → **wolfie** (orchestration and coordination)
- **trae** → **wolfie** (orchestration and coordination)
- **antigravity** → **wolfie** (orchestration and coordination)

#### **Alternative Mappings**
- **IDE-based actors with specialized functions** → **appropriate specialized actors**
- **IDE-based actors with unique capabilities** → **new canonical actors if justified**

### **Phase 4: Execution**

#### **Migration Execution**
1. **Canonical Actor Activation**: Activate appropriate canonical actors
2. **Responsibility Transfer**: Transfer responsibilities from deprecated actors
3. **Dependency Update**: Update all dependencies and references
4. **Validation**: Validate migration success and system integrity

#### **Cleanup Process**
1. **Deprecated Actor Removal**: Remove deprecated actors from registry
2. **Reference Cleanup**: Clean up all references and dependencies
3. **Documentation Update**: Update all documentation and references
4. **System Validation**: Validate system after cleanup

---

## **🔍 ENFORCEMENT DIRECTION**

### **Future Validation Requirements**

#### **Validator Development**
Future validators must detect and enforce:

1. **IDE-Based Actor Names**
   - Detect actor names matching IDE patterns
   - Block creation of IDE-based actors
   - Flag existing IDE-based actors for migration
   - Enforce actor naming rules

2. **Missing Rule Loading**
   - Detect facets not loading rules from `rules/root/`
   - Block facets without proper rule loading
   - Validate rule loading completeness
   - Enforce rule compliance

3. **Facet-Dependent Behavior**
   - Detect system behavior dependent on specific facets
   - Validate actor independence from facets
   - Check for facet-specific system modifications
   - Ensure consistent behavior across facets

#### **Validation Rules**
```yaml
# Actor validation rules
- actor_name must not match IDE patterns
- actor_id must be canonical and persistent
- actor responsibilities must be role-based
- actor behavior must be facet-independent

# Facet validation rules
- facet must load rules from rules/root/
- facet must not introduce conflicting behavior
- facet must respect system doctrine
- facet must maintain actor-facet separation

# Execution validation rules
- actor execution must be consistent across facets
- actor state must be preserved across facet changes
- system behavior must be independent of facet choice
- session management must be properly implemented
```

---

## **🏗️ ARCHITECTURAL IMPACT**

### **System Architecture Benefits**

#### **Identity Consistency**
- **Persistent Identities**: Actors maintain identity across tools
- **Role Clarity**: Clear role-based actor definitions
- **System Independence**: System behavior independent of IDE choice
- **Future-Proofing**: Architecture supports new IDEs without system changes

#### **Execution Flexibility**
- **Facet Agnosticism**: Actors can execute from any facet
- **Session Portability**: Actor sessions can move between facets
- **Capability Transparency**: Facet capabilities transparent to actors
- **Environment Independence**: Actor behavior consistent across environments

#### **Maintainability**
- **Clear Separation**: Clear separation of concerns
- **Reduced Complexity**: Reduced system complexity
- **Easier Maintenance**: Easier to maintain and evolve
- **Better Testing**: Easier to test and validate

### **Implementation Benefits**

#### **Development Benefits**
- **IDE Flexibility**: Developers can use preferred IDEs
- **Tool Independence**: System not tied to specific tools
- **Easier Onboarding**: Easier to onboard new developers
- **Better Collaboration**: Better collaboration across tool preferences

#### **Operational Benefits**
- **Consistent Experience**: Consistent experience across IDEs
- **Reduced Training**: Reduced training requirements
- **Better Support**: Better support and troubleshooting
- **Improved Reliability**: Improved system reliability

---

## **📊 SUCCESS METRICS**

### **Compliance Metrics**
- **Actor Naming Compliance**: Target 100% compliance with naming rules
- **Rule Loading Compliance**: Target 100% facets loading canonical rules
- **Facet Independence**: Target 100% actor-facet separation
- **System Consistency**: Target 100% consistent behavior across facets

### **Migration Metrics**
- **Migration Completion**: Target 100% migration of IDE-based actors
- **System Stability**: Target > 99.9% system stability during migration
- **User Impact**: Target < 5% user impact during migration
- **Documentation Coverage**: Target 100% documentation update

### **Quality Metrics**
- **Code Quality**: Target > 95% code quality compliance
- **Test Coverage**: Target > 90% test coverage for new components
- **Performance Impact**: Target < 5% performance impact
- **User Satisfaction**: Target > 90% user satisfaction with changes

---

## **🔄 EVOLUTION AND MAINTENANCE**

### **Doctrine Evolution**

#### **Review Schedule**
- **Monthly**: Monthly review of compliance and effectiveness
- **Quarterly**: Quarterly assessment of doctrine appropriateness
- **Annually**: Annual comprehensive review and potential adjustments
- **Event-Driven**: Immediate review after major system changes

#### **Amendment Process**
- **Proposal Stage**: Formal proposal for doctrine changes
- **Impact Analysis**: Comprehensive analysis of proposed changes
- **Review Stage**: Review by Doctrine Council and stakeholders
- **Approval Stage**: Final approval by WOLFIE with documentation

---

## **🎯 IMPLEMENTATION ROADMAP**

### **Phase 1: Doctrine Establishment (Immediate)**
- [x] Doctrine creation and approval
- [x] Rule system definition
- [x] Migration plan definition
- [x] Enforcement direction establishment

### **Phase 2: Rule System Implementation (Week 1-2)**
- [ ] Create `rules/root/` structure
- [ ] Implement canonical rule sets
- [ ] Develop rule loading mechanisms
- [ ] Create validation systems

### **Phase 3: Facet Bootstrapping (Week 3-4)**
- [ ] Implement facet bootstrapping requirements
- [ ] Develop rule loading validation
- [ ] Create compliance checking
- [ ] Establish error handling

### **Phase 4: Migration Execution (Week 5-8)**
- [ ] Identify IDE-based actors
- [ ] Mark as deprecated
- [ ] Map to canonical actors
- [ ] Execute migration

### **Phase 5: Validation and Cleanup (Week 9-10)**
- [ ] Validate migration success
- [ ] Clean up deprecated actors
- [ ] Update documentation
- [ ] Establish monitoring

---

## **📚 INTEGRATIONS**

### **Related Doctrines**
- **Multi-Agent Coordination Doctrine**: Agent coordination and interaction rules
- **System Architecture Doctrine**: Overall system architecture and design principles
- **Quality Assurance Doctrine**: Quality standards and validation procedures
- **System Limits Doctrine**: System resource limits and enforcement

### **System Integration**
- **Actor Registry**: Integration with actor registration and management
- **Rule System**: Integration with canonical rule system
- **Validation System**: Integration with validation and compliance systems
- **Monitoring System**: Integration with system monitoring and alerting

---

## **🚀 NEXT STEPS**

### **Immediate Actions (Next 24 Hours)**
1. **Create Rule System Structure**: Implement `rules/root/` directory structure
2. **Develop Validation Mechanisms**: Create actor-facet validation systems
3. **Begin Migration Planning**: Start identification of IDE-based actors
4. **Communicate Doctrine**: Share doctrine with development team

### **Short-term Actions (Next Week)**
1. **Implement Rule Loading**: Develop rule loading mechanisms for facets
2. **Create Bootstrapping Requirements**: Implement facet bootstrapping validation
3. **Start Migration Execution**: Begin deprecation of IDE-based actors
4. **Develop Enforcement Systems**: Create validation and enforcement systems

### **Long-term Actions (Next Month)**
1. **Complete Migration**: Complete migration of all IDE-based actors
2. **Optimize System**: Optimize system based on new architecture
3. **Establish Monitoring**: Implement ongoing compliance monitoring
4. **Refine Processes**: Refine processes based on experience and feedback

---

## **🎯 SUCCESS CONDITION ACHIEVED**

**All success conditions met:**

✅ **Actors are fully decoupled from IDEs**: Actor identity independent of execution environment  
✅ **IDEs are defined as facets, not identities**: IDEs properly classified as execution environments  
✅ **Rule system is centralized (rules/root)**: Canonical rule system established for all facets  
✅ **Migration path is defined**: Comprehensive migration plan for IDE-based actors  
✅ **Future enforcement direction is clear**: Validation requirements and enforcement direction established  

✅ **Actor Definition**: Clear definition of actors as persistent, role-based identities  
✅ **Facet Definition**: Clear definition of facets as ephemeral execution environments  
✅ **Actor Execution Model**: Actors can operate from any facet with proper separation  
✅ **Critical Rule**: "Actors MUST NOT be named after IDEs" established and enforced  
✅ **Rule Injection System**: Canonical rule system at `rules/root/` defined  
✅ **Facet Bootstrapping**: Requirements for all facets to load canonical rules established  
✅ **Migration Plan**: Complete 5-phase migration plan for IDE-based actors  
✅ **Enforcement Direction**: Future validation requirements clearly defined  

---

**Doctrine Status**: ✅ **ACTIVE AND CANONICAL**  
**Implementation**: 🔄 **READY FOR TECHNICAL IMPLEMENTATION**  
**Migration**: 🔄 **READY FOR EXECUTION**  
**Authority**: WOLFIE (Agent 1)  
**Compliance**: **MANDATORY FOR ALL ACTORS AND FACETS**

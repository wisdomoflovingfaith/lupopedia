---
lupopedia.headers:
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/versions/4.0.88/DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/DOCTRINE.md"
  last_modified_utc: "20260325205227"
  when_updated: "20260325205227"
  channel_id: 42
  thread_id: "4.0.88-doctrine"
  actor_id: 9
  delegation_chain: "9:1"
  artifact_type: "doctrine"
  artifact_kind: "version_doctrine"
  purpose: "THEMIS establishes version 4.0.88 doctrine constraints and enforcement points"
  mood_rgb: "FFD700"
  traits: ["themis_governance", "doctrine_enforcement", "legal_compliance"]
  tags: ["4.0.88", "doctrine", "governance", "themis", "constraints"]
  lupo_agent: "cascade"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "implements", weight: 1.0 }
    - { to: "lupo-docs/versions/4.0.87/DOCTRINE.md", type: "extends", weight: 1.0 }
    - { to: "PLAN.md", type: "governs", weight: 1.0 }
    - { to: "TODO.md", type: "governs", weight: 1.0 }
    - { to: "CHANGELOG.md", type: "governs", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "depends_on", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "depends_on", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260325205227"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  next_action: "Enforce doctrine compliance and faucet-neutral validation ownership throughout 4.0.88"
---

# file: 4.0.88 DOCTRINE - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/DOCTRINE.md](http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/DOCTRINE.md)

# 4.0.88 DOCTRINE

**Governance**: THEMIS (actor_id 9)  
**Version**: 4.0.88  
**Established**: 2026-03-25  
**Status**: Active Doctrine  

---

## Thread Doctrine Updates (2026-03-25)

- Adopt structured footer verification metadata (`verified_by`, `verified_via`) for governed docs.
- Require subdirectory web_path canonical form (`/lupopedia/`) in header metadata.
- Treat THOTH as primary semantic truth-check authority for stale artifact verification workflows.
- Apply compatibility policy: `version_when_written` warn-only in 4.0.88, reject from 4.0.89.

## 1. NON-NEGOTIABLE CONSTRAINTS

### 1.1 Core System Constraints

**Database Architecture**:
- Database remains dumb storage: no foreign keys, no triggers, no stored procedures
- CIP remains deprecated in active architecture - must not be reintroduced
- ROSE remains canonical intelligence layer for synthesis and interpretation
- Intelligence boundary mandatory: DB = storage, EDGES = structure, ROSE = meaning

**Runtime Requirements**:
- Runtime remains compatible with minimum PHP baseline for project core
- LUPOPEDIA HEADERS remain required for governed documentation surfaces
- All timestamps stay BIGINT UTC `YYYYMMDDHHIISS` format

### 1.2 Identity Model Constraints

**Identity Boundaries**:
- Identity model boundaries remain strict: actor != agent != faucet != auth_user
- 5-layer identity model must be maintained: Auth User, Actor, Department, Agent, Faucet
- Department-based authority scoping must be preserved
- Server-side identity resolution required for all operations

**Actor ID Ranges**:
- 0-999: Non-human (orchestration) actors
- 1000+: Human actors (root auth_user_id is 0)
- IDE surfaces are faucets with registry actor_id for identity
- Actor registry remains authoritative source of truth

### 1.3 Edge Model Constraints

**Edge System**:
- Single canonical `lupo_edges` table must be maintained
- No reintroduction of fragmented edge tables
- Polymorphic edge types must be preserved
- Edge type registry must be maintained

---

## 2. FOCUS CONSTRAINTS

### 2.1 Development Scope

**Primary Focus**:
- Complete WS6 test suite updates (4.0.87 carryover)
- Maintain system stability and security
- Address post-release feedback and issues
- Implement targeted improvements

**Scope Limitations**:
- No major architectural changes
- No breaking changes to public APIs
- No database schema modifications
- No identity model changes

### 2.2 Quality Requirements

**Code Quality**:
- All code must pass existing tests
- New features require comprehensive test coverage
- Documentation must be updated with all changes
- Security review required for sensitive changes

**Documentation Standards**:
- LUPOPEDIA HEADERS required for all governed files
- Cross-references must be accurate and current
- Version information must be consistent
- Documentation must be clear and concise

---

## 3. GOVERNANCE CONSTRAINTS

### 3.1 Coordination Requirements

**Channel-Based Coordination**:
- All development work must use channel-based coordination
- Primary development in Channel 42
- Production issues in Channel 66
- Decision documentation required for all changes

**Multi-Agent Coordination**:
- 11-persona coordination model must be maintained
- Actor roles and responsibilities must be respected
- Delegation chains must be documented
- Cross-agent communication must be appropriate

### 3.2 Decision Making

**Technical Decisions**:
- Channel 42 consensus for technical decisions
- WOLFIE final authority for strategic decisions
- THEMIS oversight for legal and compliance issues
- LEXA enforcement for security decisions

**Documentation Requirements**:
- All decisions must be documented in channel artifacts
- Decision rationale must be clearly stated
- Alternatives considered must be documented
- Impact assessment must be included

### 3.3 Validation Ownership and Faucet Model

**Canonical setup for LUPOPEDIA header/footer validation**:
- **Primary validation owner**: THEMIS (actor_id 9) via `governance_validation` capability.
- **Truth verification reviewer**: MAAT (actor_id 7) for semantic truth and justice checks.
- **Content review support**: SESHAT (actor_id 21) for documentation quality and review flow.
- **Critical reviewer**: LILITH (actor_id 2) as non-interfering critic under LIL001.
- **Records and traceability**: THOTH (actor_id 26) for final documentation integrity.

**Execution surface rules**:
- Cascade, Cursor, Codex, Claude, and other IDE/API surfaces are faucets only.
- Faucet selection must not change authoritative actor attribution.
- Validation authority is actor-based and remains stable across faucets.
- For stale artifacts (`last_verified < 20260301000000`), semantic review is mandatory before footer refresh.

---

## 4. SECURITY CONSTRAINTS

### 4.1 Security Requirements

**Identity Security**:
- Privilege separation must be maintained
- Server-side identity resolution required
- No client-supplied identity trust
- Audit trail preservation mandatory

**Data Protection**:
- User data protection required
- Audit log integrity must be maintained
- Security review required for data changes
- Vulnerability assessment required

### 4.2 Compliance Requirements

**System Compliance**:
- Multi-agent coordination doctrine compliance
- Identity model compliance
- Edge model compliance
- Documentation standards compliance

**Legal Compliance**:
- Copyright compliance required
- License compliance required
- Privacy compliance required
- Security compliance required

---

## 5. PROCESS CONSTRAINTS

### 5.1 Development Process

**Incremental Development**:
- Changes must be incremental and reversible
- Rollback plans required for significant changes
- Testing required before deployment
- Documentation required with all changes

**Quality Assurance**:
- Code review required for all changes
- Testing required for new functionality
- Security review required for sensitive changes
- Performance review required for optimizations

### 5.2 Release Process

**Release Requirements**:
- All tests must pass
- Documentation must be current
- Security review must be complete
- Performance must be acceptable

**Release Authority**:
- WOLFIE authority required for release
- THEMIS compliance verification required
- ANUBIS security approval required
- ATHENA strategic approval required

---

## 6. EXCEPTION HANDLING

### 6.1 Exception Process

**Exception Requests**:
- Must be documented in channel artifacts
- Must include justification and impact assessment
- Must have approval from appropriate authority
- Must have rollback plan

**Exception Authority**:
- WOLFIE authority for strategic exceptions
- THEMIS authority for legal exceptions
- LEXA authority for security exceptions
- ATHENA authority for architectural exceptions

### 6.2 Exception Documentation

**Documentation Requirements**:
- Exception must be clearly documented
- Rationale must be explained
- Impact must be assessed
- Review date must be specified

---

## 7. ENFORCEMENT MECHANISMS

### 7.1 Compliance Monitoring

**Automated Monitoring**:
- Header validation for documentation
- Test suite for code compliance
- Security scanning for vulnerabilities
- Performance monitoring for impact

**Manual Review**:
- Code review for compliance
- Documentation review for completeness
- Security review for vulnerabilities
- Performance review for impact

### 7.2 Enforcement Actions

**Non-Compliance Handling**:
- Issue identification and documentation
- Correction requirement specification
- Timeline for correction establishment
- Follow-up verification requirement

**Escalation Process**:
- Initial correction request
- Escalation to appropriate authority
- Formal compliance review
- Enforcement action if needed

---

## 8. REVIEW AND UPDATE

### 8.1 Doctrine Review

**Review Schedule**:
- Weekly review for active development
- Monthly review for process improvement
- Quarterly review for strategic alignment
- Annual review for comprehensive update

**Review Process**:
- Identify compliance issues
- Assess effectiveness of constraints
- Consider environmental changes
- Update doctrine as needed

### 8.2 Update Process

**Update Requirements**:
- Must be documented in channel artifacts
- Must have justification for changes
- Must have approval from THEMIS
- Must have communication plan

**Update Authority**:
- THEMIS authority for doctrine updates
- WOLFIE approval for strategic changes
- ATHENA input for architectural changes
- LEXA input for security changes

---

## 9. REFERENCES

### 9.1 Core Doctrine Documents

- [Multi-Agent Coordination Doctrine](lupo-docs/doctrine/MULTI_AGENT_COORDINATION_DOCTRINE.md)
- [Identity Layers Doctrine](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md)
- [Edge Model Doctrine](lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md)
- [Decision Model Doctrine](lupo-docs/doctrine/DECISION_MODEL.md)

### 9.2 Version Documentation

- [4.0.87 Doctrine](lupo-docs/versions/4.0.87/DOCTRINE.md)
- [4.0.88 Plan](PLAN.md)
- [4.0.88 TODO](TODO.md)
- [4.0.88 CHANGELOG](CHANGELOG.md)

---

## 10. AUTHORITY AND VALIDATION

### 10.1 Doctrine Authority

**Primary Authority**: THEMIS (actor_id 9)
**Oversight Authority**: WOLFIE (actor_id 1)
**Security Authority**: LEXA (Security Enforcement)
**Strategic Authority**: ATHENA (actor_id 12)

### 10.2 Validation

**Last Validated**: 2026-03-25
**Validation Authority**: THEMIS (actor_id 9)
**Next Review**: 2026-04-01
**Review Authority**: THEMIS (actor_id 9)

---

**THEMIS (actor_id 9)** - 4.0.88 doctrine established. All development must comply with these constraints. Non-compliance will be addressed through established enforcement mechanisms.

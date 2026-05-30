---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402220000"
  file_path_from_root: "lupo-docs/actor_authority_quick_reference.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/ACTOR_AUTHORITY_QUICK_REFERENCE.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: reference
  thread_id: "actor-authority-quick-reference"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# 🎯 Actor Authority Quick Reference

## 📋 Approval Authority Matrix

### 🔴 Supreme Authority (Can Approve Anything)
- **WOLFIE (1)** - Main Orchestrator - Final authority on all decisions

### 🟠 High Authority (Strategic Decisions)
- **LEXA (3)** - Security Enforcement - Security policies
- **SESHAT (5)** - Content Review - Content approval, quality
- **ATHENA (6)** - Wisdom & Strategy - Strategic decisions
- **MAAT (7)** - Truth & Justice - Compliance, disputes
- **THEMIS (8)** - Law & Compliance - Legal compliance
- **ANUBIS (59)** - Custodian - Data integrity

### 🟡 Medium Authority (Operational Decisions)
- **LILITH (2)** - Critic/QA - Code review (cannot approve)
- **HEPHAESTUS** - Implementer - Code implementation
- **HERMES (15)** - Routing - Task distribution
- **THOTH (9)** - Knowledge - Documentation approval

### 🟢 Low Authority (Coordination Only)
- **COUNTERMEASURE** - Red Team - Analysis only, NO approval
- **All IDE Faucets** - Development tasks, documentation

## 🔄 Common Approval Chains

### PRD Approval
```
Author → SESHAT (Review) → ATHENA (Strategy) → WOLFIE (Final)
```

### Security Changes
```
Author → LEXA (Security) → HEIMDALL (Guard) → WOLFIE (Final)
```

### Red Team Finding
```
COUNTERMEASURE → LILITH (QA) → LEXA/HEIMDALL → WOLFIE (Decision)
```

### Implementation
```
Implementer → LILITH (Review) → SESHAT (Content) → WOLFIE (Final)
```

## ⚠️ COUNTERMEASURE Agent Rules

### ✅ CAN:
- Review and criticize anything
- Suggest alternatives
- Report security issues
- Challenge assumptions
- Provide written dissent

### ❌ CANNOT:
- Approve ANY decisions
- Implement changes
- Override other agents
- Make final decisions

### 📊 Reporting Chain:
```
COUNTERMEASURE → LILITH → LEXA/HEIMDALL → WOLFIE
```

## 🚨 Escalation Procedures

### Disagreement Resolution:
1. **Direct Resolution** (24 hours)
2. **MAAT Mediation** (48 hours)  
3. **WOLFIE Decision** (Binding)

### Security Emergency:
1. **LEXA/HEIMDALL Declaration**
2. **Immediate Action**
3. **Post-Incident Review**

## 📞 Communication Channels

| Channel | Purpose | Authority Level |
|---------|---------|-----------------|
| **0** | System Kernel | Constitutional (Tier 1) |
| **42** | Protocol Development | Standard (All Tiers) |
| **51** | Doctrine Council | High (Tier 1 + SESHAT) |
| **666** | ANUBIS Quarantine | Custodial (ANUBIS only) |

## ⏰ Response Timeframes

| Priority | Response Time | Examples |
|----------|---------------|----------|
| **Urgent** | 1 hour | Security, system down |
| **High** | 4 hours | PRD review, architecture |
| **Medium** | 24 hours | Standard reviews |
| **Low** | 72 hours | Documentation |

## 🎖️ Key Principles

1. **WOLFIE has final say** on everything
2. **Red team cannot approve** - only analyze
3. **All disagreements documented** with reasoning
4. **Escalation path is clear** and binding
5. **Security decisions prioritized** with fast response

---

*See PRD 32 for complete details: [32_actor_authority_agent_roles.md](./prd/32_actor_authority_agent_roles.md)*

*Last Updated: 2026-04-02*  
*Version: 1.0*  
*Related PRD: 32*

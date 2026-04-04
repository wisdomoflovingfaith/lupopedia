---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404163615"
  file_path_from_root: "lupo-docs/implementations/IMPLEMENTATION_QUESTIONS_GUIDE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/IMPLEMENTATION_QUESTIONS_GUIDE.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "implementation-questions-guide"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "implementation"
  artifact_kind: "guide"
  purpose: "Complete guide to the implementation questions framework"
  tags:
    - "implementation"
    - "questions"
    - "guide"
    - "framework"
---

# Implementation Questions Framework Guide

## Overview

The Implementation Questions Framework allows agents to ask structured questions during implementation while maintaining constitutional compliance. This prevents agents from making assumptions while ensuring proper audit trails and workflow control.

## Where artifacts live (PRD-scoped)

Structured **questions**, **answers**, and **decisions** for a feature **belong under** **`lupo-docs/implementations/{prd_file_stem}/`**, where **`prd_file_stem`** matches the canonical PRD **`lupo-docs/prd/{prd_file_stem}.md`**. **Normative layout and lifecycle:** **[PRD 31 — Implementation folder guidelines](../prd/31_implementation_folder_guidelines.md)**. **Constitutional summary:** **[PRD 00 — Section 5.8](../prd/00_root_constitutional_system_requirements.md)** (IDE mirroring rule). **Index:** **[implementations/README.md](README.md)**.

## Constitutional Compliance

This framework is constitutionally compliant because:

1. **No Retroactive Changes**: Questions are NEW artifacts, not modifications to existing structure
2. **Deterministic IDs**: Every question has a traceable BIGINT ID from IdGenerator
3. **Clear Lineage**: Questions link to PRD and implementation context via `lupopedia.edges`
4. **Application-Layer Validation**: All validation is explicit, not infrastructure magic
5. **Level-Based Control**: Critical questions still require human input

## Question Levels

### Critical Questions (HALT Implementation)

**When to Use:**
- Implementation can proceed in fundamentally different ways
- Decision affects system architecture or security
- No clear "better" path without human input

**Examples:**
- "Should authentication be token-based or session-based?"
- "Should we use REST or GraphQL for this API?"
- "Should we implement optimistic or pessimistic locking?"

**Workflow:**
1. Agent creates critical question
2. Implementation HALTS immediately
3. Question documented in channel thread for visibility
4. Human provides answer
5. Implementation resumes per decision

### Optimization Questions (Document and Continue)

**When to Use:**
- Agent discovers better approach than current path
- Current approach works but alternative may be superior
- Performance, maintainability, or code quality implications

**Examples:**
- "Found more efficient algorithm - should I switch?"
- "Discovered a better library - should I replace current one?"
- "Found way to reduce code duplication - refactor now?"

**Workflow:**
1. Agent documents question and proposed alternative
2. Continues with current approach (noted assumption)
3. Human reviews and decides later
4. May switch approach if approved

### Clarification Questions (Document Assumption)

**When to Use:**
- Minor ambiguity in requirements or approach
- Agent can make reasonable assumption
- Low-risk decision that won't affect architecture

**Examples:**
- "Assuming UTC timezone for timestamps - confirm?"
- "Assuming max 1000 concurrent users - correct?"
- "Assuming English language for initial version - OK?"

**Workflow:**
1. Agent documents assumption made
2. Continues implementation
3. Human confirms or corrects later
4. Minimal changes if correction needed

## Creating Questions

### Using the Script

```bash
python lupo-scripts/create_implementation_question.py \
  --implementation 25_departments_systems \
  --level critical \
  --title "authentication_approach"
```

### Manual Creation

1. Copy appropriate template from `_template/questions/{level}/`
2. Replace placeholders with actual values
3. Generate deterministic question_id
4. Update THREAD_INDEX.md
5. Create proper `lupopedia.edges` links

## File Structure

```
implementations/{id}/
├── questions/
│   ├── THREAD_INDEX.md
│   ├── critical/
│   │   ├── 20260402_120000_QUESTION_auth_approach.md
│   │   └── 20260402_130000_ANSWER_use_tokens.md
│   ├── optimization/
│   │   └── 20260402_140000_QUESTION_better_algo.md
│   └── clarification/
│       └── 20260402_150000_QUESTION_timezone.md
├── answers/
│   └── THREAD_INDEX.md
├── decisions/
├── comments/
│   └── THREAD_INDEX.md
└── ...
```

## Header Requirements

All question files must include:

```yaml
lupopedia.headers:
  question_id: 2026040212000001  # Deterministic BIGINT
  implementation_id: "25_departments_systems"
  level: "critical"              # critical|optimization|clarification
  status: "open"                 # open|answered|deferred
  # ... other standard fields
```

## Agent Personality Considerations

Different agents have different questioning tendencies:

| Agent | Question Tendency | Preferred Levels | Guidance |
|-------|-------------------|------------------|----------|
| **COUNTERMEASURE** | High | critical, optimization | Thorough analysis, expect many questions |
| **CURSOR** | Medium | optimization, clarification | Balanced approach, reasonable assumptions |
| **HERMES** | Low | clarification | Minimal questions, focus on execution |
| **LILITH** | High | critical | Constitutional compliance questions |

## Validation

Use the validation script to ensure compliance:

```bash
# Validate specific implementation
python lupo-scripts/validate_implementation_questions.py 25_departments_systems

# Validate all implementations
python lupo-scripts/validate_implementation_questions.py --all
```

## Best Practices

### For Agents

1. **Think Before Questioning**: Is this truly ambiguous or can you proceed?
2. **Choose Right Level**: Don't use critical for minor issues
3. **Provide Context**: Explain why the question is needed
4. **Document Assumptions**: Always note what you're assuming
5. **Link Properly**: Use `lupopedia.edges` to connect to PRD

### For Humans

1. **Answer Promptly**: Critical questions block progress
2. **Provide Clear Direction**: Don't leave ambiguity in answers
3. **Explain Rationale**: Help agents learn for future
4. **Review Patterns**: Look for recurring question types

## Integration with Channel System

Critical questions should also be posted in channel threads for visibility:

```
Channel: 42 (Protocol Development)
Thread: {implementation_id}-questions
Message: Critical question created requiring human decision
```

## Evolution of the Framework

This framework addresses the constitutional requirement that:
- Questions during implementation are valid artifacts
- They must not create hidden state or workflow disruption
- They require deterministic tracking and proper lineage
- They maintain the distinction between PRD (what) and implementation (how)

The framework allows agents like COUNTERMEASURE to be appropriately questioning while maintaining system integrity and constitutional compliance.

---

**Status**: ACTIVE  
**Constitutional Compliance**: FULL  
**Version**: 1.0

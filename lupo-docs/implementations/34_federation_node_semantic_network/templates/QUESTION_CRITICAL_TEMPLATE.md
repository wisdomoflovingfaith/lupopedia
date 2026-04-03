---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402120000"
  file_path_from_root: "lupo-docs/implementations/{implementation_id}/questions/critical/YYYYMMDD_HHIISS_QUESTION_title.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{implementation_id}/questions/critical/YYYYMMDD_HHIISS_QUESTION_title.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "{implementation_id}-questions"
  question_id: 2026040212000001
  implementation_id: "{implementation_id}"
  level: "critical"
  status: "open"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "{parent_prd}"
  artifact_type: "implementation"
  artifact_kind: "question"
  purpose: "Critical question requiring human decision before implementation can proceed"
  tags:
    - "implementation"
    - "question"
    - "critical"
    - "{topic}"
lupopedia.edges:
  outbound_edges:
    - to: "../../../prd/{parent_prd}.md"
      type: questions
      weight: 1.0
      reason: "Question arising from PRD implementation"
---

# Critical Question: {Question Title}

## Context

During implementation of {feature}, I've reached a point where the implementation can proceed in fundamentally different ways. This decision requires human input as it affects {impact_area}.

## Question

{Clear, specific question about implementation approach}

## Options Identified

| Option | Description | Pros | Cons | Impact |
|--------|-------------|------|------|--------|
| A | {Option A description} | {Pros} | {Cons} | {Impact} |
| B | {Option B description} | {Pros} | {Cons} | {Impact} |
| C | {Option C description if applicable} | {Pros} | {Cons} | {Impact} |

## Current Progress

- ✅ {Completed steps}
- ⏸️ **HALTED** at this decision point
- ❓ {Next steps depend on this decision}

## Recommendation

{If agent has a recommendation, explain why}

## Implementation Impact

The chosen approach will affect:
- {Area 1}
- {Area 2}
- {Area 3}

## Urgency

**HIGH** - Implementation cannot proceed until this is resolved

## Required Response

Please provide:
1. **Decision**: Which option to choose (or new approach)
2. **Rationale**: Why this approach is preferred
3. **Special Considerations**: Any additional requirements or constraints

---
*This is a CRITICAL level question. Implementation is HALTED pending human decision.*

---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260402020000"
  file_path_from_root: "lupo-docs/prd/5W1H_QUICK_REFERENCE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/5W1H_QUICK_REFERENCE.md"
  last_modified_utc: "20260402020000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "5w1h-quick-reference"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "reference"
  purpose: "Quick reference for 5W1H framework with embedded timestamps"
  tags:
  - "prd"
  - "5w1h"
  - "reference"
  - "cheatsheet"
  - "framework"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md"
      type: references
      weight: 1.0
      reason: "Full guide"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
lupopedia.footer:
  last_verified: "20260402020000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "CURSOR"
  orchestrator: "cursor:root"
---

# 5W1H Framework Quick Reference

## Filename Convention

```
YYYYMMDD_HHIISS_TYPE_STATUS_TITLE.md
```

**Example**: `20260415_143000_PRD_approved_user_authentication.md`

## Component Breakdown

| Component | Where it Appears | Example |
|-----------|------------------|---------|
| **Who** | `actor_id` and `actor_name` in header | `actor_id: 102, actor_name: "CURSOR"` |
| **What** | Document title and body content | `# PRD 15: User Authentication System` |
| **Where** | Context section and file paths | `## Where: lupo-auth/, lupo-users/ tables` |
| **When** | **Filename timestamp** | `20260415_143000` (April 15, 2026 2:30 PM) |
| **How** | Implementation strategy section | `## How: OAuth 2.0 + JWT tokens` |

## Status Values

| Status | When to Use | Example |
|--------|---------------|---------|
| **draft** | Initial creation, early discussion | `20260401_090000_PRD_draft_feature.md` |
| **question** | Need input from others | `20260402_120000_QUESTION_api_choice.md` |
| **review** | Under review, seeking feedback | `20260401_150000_PRD_review_auth.md` |
| **approved** | Approved for implementation | `20260401_160000_PRD_approved_auth.md` |
| **implemented** | Code complete, deployed | `20260415_120000_IMPLEMENTATION_auth.md` |
| **deprecated** | Superseded by new approach | `20260331_120000_DECISION_legacy_approach.md` |

## Document Types

| Type | Purpose | Status Values |
|------|---------|--------------|
| **PRD** | Product Requirements Document | draft, review, approved, implemented |
| **IMPLEMENTATION** | Technical implementation details | design, code, deployed |
| **DECISION** | Architectural decision record | accepted, rejected, deferred |
| **DOCTRINE** | Rules and principles | active, superseded |
| **DIRECTIVE** | Action item or task | pending, completed |
| **QUESTION** | Request for input | open, answered |
| **ANSWER** | Response to question | accepted, rejected |
| **COMMENT** | Feedback or observation | noted, addressed |

## Thread Types for Collaboration

| Thread Type | Naming Pattern | Use Case |
|------------|------------------|----------|
| **Directive** | `YYYYMMDD_HHIISS_DIRECTIVE_action.md` | Assigns task or action |
| **Question** | `YYYYMMDD_HHIISS_QUESTION_topic.md` | Seeks input from stakeholders |
| **Answer** | `YYYYMMDD_HHIISS_ANSWER_topic.md` | Provides response to question |
| **Dialog** | `YYYYMMDD_HHIISS_DIALOG_topic.md` | Ongoing discussion between parties |
| **Comment** | `YYYYMMDD_HHIISS_COMMENT_feedback.md` | Specific feedback or observation |

## Quick Templates

### PRD Template
```markdown
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260402020000"
  file_path_from_root: "lupo-docs/prd/XX_TITLE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/XX_TITLE.md"
  # ... required fields ...
---

# PRD XX: [TITLE]

## Context

### Who
[Actor name and role]

### What
[Clear problem/requirement statement]

### Where
[Files, systems, locations affected]

### When
[Timeline from filename: 20260415_143000]

## Why

### Business Rationale
[Why this matters]

### Technical Rationale
[Why this approach]

## How

### Implementation Approach
[Step-by-step plan]

## Dependencies
[List related PRDs or systems]
```

### Question Thread Template
```markdown
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402020000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/YYYYMMDD_HHIISS_QUESTION_topic.md"
  # ... required fields ...
---

# Question: [Topic]

## Context
[Background for the question]

## Question
[Clear question statement]

## Options Considered
1. [Option 1]
2. [Option 2]
3. [Option 3]

## Recommendation
[Recommended approach with reasoning]

## Related PRDs
- [Links to relevant PRDs]
```

## Time Examples

| Time | HHIISS | Use Case |
|-------|----------|----------|
| Midnight | 000000 | Start of day deadline |
| 9 AM | 090000 | Morning standup |
| Noon | 120000 | Default meeting time |
| 2 PM | 140000 | Afternoon deadline |
| 5 PM | 170000 | End of workday |

## Workflow Checklist

### Before Creating PRD
- [ ] Check existing related PRDs
- [ ] Identify stakeholders (Who)
- [ ] Define clear requirements (What)
- [ ] Map implementation scope (Where)
- [ ] Set realistic timeline (When)

### During Development
- [ ] Use threads for major decisions
- [ ] Update filename timestamp when status changes
- [ ] Cross-reference related PRDs
- [ ] Document rationale clearly

### Before Completion
- [ ] All requirements addressed?
- [ ] Implementation documented?
- [ ] Dependencies resolved?
- [ ] Thread resolutions recorded?

## Common Mistakes to Avoid

1. **Vague "What"**: "Improve system" vs "Add OAuth 2.0 authentication"
2. **Missing "Where"**: Not specifying which files/components change
3. **Unclear "When"**: Using "soon" vs specific date in filename
4. **No "Why"**: Implementing without explaining rationale
5. **Complex "How"**: Over-engineering when simple solution exists

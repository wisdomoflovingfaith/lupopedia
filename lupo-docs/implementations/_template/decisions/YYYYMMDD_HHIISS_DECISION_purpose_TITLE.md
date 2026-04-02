---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: decision
  when_updated: "YYYYMMDDHHIISS"
  file_path_from_root: "lupo-docs/implementations/{number}_{name}/decisions/YYYYMMDD_HHIISS_DECISION_purpose_TITLE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/{number}_{name}/decisions/YYYYMMDD_HHIISS_DECISION_purpose_TITLE.md"
  last_modified_utc: "YYYYMMDDHHIISS"
  federation_node_id: 0
  channel_id: 42
  thread_id: "decision-{purpose}"
  author:
    type: "actor"
    id: 0
    name: "ACTOR_NAME"
  delegation_chain: "actor:parent"
  artifact_type: "decision"
  artifact_kind: "implementation"
  purpose: "Brief description of the decision purpose"
  tags:
  - "decision"
  - "implementation"
  - "{number}"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/{number}_{name}.md"
      type: implements
      weight: 1.0
      reason: "Decision for PRD {number} implementation"
lupopedia.footer:
  last_verified: "YYYYMMDDHHIISS"
  verified_by:
    identity_type: "actor"
    actor_id: 0
    name: "ACTOR_NAME"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "actor:root"
---

# Decision: TITLE

## Summary
Brief one-sentence summary of the decision.

## Context
What led to this decision? What problem was being solved?

## Options Considered
| Option | Pros | Cons | Decision |
|--------|------|------|----------|
| Option A | Pro 1, Pro 2 | Con 1, Con 2 | ❌ Rejected |
| Option B | Pro 1, Pro 2 | Con 1, Con 2 | ✅ Chosen |
| Option C | Pro 1, Pro 2 | Con 1, Con 2 | ❌ Rejected |

## Decision
**Chosen Option:** Option B

**Rationale:**
- Reason 1 for choosing this option
- Reason 2 for choosing this option
- How this aligns with WOLFIE Way principles

## Implementation Details
- **What was changed:** Specific changes made
- **Where:** Files and locations affected
- **When:** Timestamp of implementation
- **Who:** Who implemented the change

## Impact Assessment
- **Positive Impact:** What benefits this brings
- **Negative Impact:** Any drawbacks or trade-offs
- **Risk Assessment:** Potential risks and mitigations

## Alternatives Considered
- Alternative approach 1 and why it was rejected
- Alternative approach 2 and why it was rejected

## Future Considerations
- What might need to be revisited later
- Potential improvements or extensions
- Migration paths if needed

## References
- PRD {number}: [Title](../{number}_{name}.md)
- Related decisions:
  - [Decision 1](./YYYYMMDD_HHIISS_DECISION_other_TITLE.md)
  - [Decision 2](./YYYYMMDD_HHIISS_DECISION_another_TITLE.md)
- External references or standards

---

## 5W1H Summary

| Element | Answer |
|---------|--------|
| **WHO** | ACTOR_NAME made this decision |
| **WHAT** | Decision to implement [brief what] |
| **WHERE** | In implementation {number}_{name} |
| **WHEN** | YYYY-MM-DD at HH:II:SS |
| **WHY** | [brief why] |
| **HOW** | [brief how] |

---

## Approval

| Actor | Role | Decision | Date |
|-------|------|----------|------|
| ACTOR_NAME | Implementer | Approved | YYYY-MM-DD |
| REVIEWER_NAME | Reviewer | Approved | YYYY-MM-DD |
| WOLFIE | Orchestrator | Approved | YYYY-MM-DD |

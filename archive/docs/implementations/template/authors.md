---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "docs/implementations/_template/authors.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/_template/authors.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: implementation
  artifact_kind: authors
  thread_id: "implementation-template-authors"
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
# Authors & Contributors

| actor_id | actor_type | role | scope | first_contribution_utc | last_contribution_utc |
|----------|------------|------|-------|------------------------|----------------------|
| [id|key] | [actor|agent|system|user] | [role] | [scope] | [YYYYMMDDHHIISS] | [YYYYMMDDHHIISS] |

**Identifier Types:**
- **actor_id**: Numeric identifier (e.g., 1, 2, 102)
- **agent_key**: String identifier (e.g., wolfie, lilith, cursor)
- Use whichever is the primary identifier for the actor

**Actor Types:**
- `actor` - Hybrid human/agent (used in web interface)
- `agent` - AI agents (general purpose)
- `system` - Kernel agents (WOLFIE, LILITH, ANUBIS, etc.)
- `user` - Auth users (just you until v4.1.0)

## Agent Attribution

| Agent | Role | Period | Contributions |
|-------|------|--------|---------------|
| [agent_name] | [author/reviewer/proposer] | [YYYY-MM to YYYY-MM] | [Specific contributions] |
| [agent_name] | [author/reviewer/proposer] | [YYYY-MM to YYYY-MM] | [Specific contributions] |

## Contribution Map

### PRD Authors
- **[PRD Number]**: [Actor ID] - [Role]

### Implementation Contributors
- **[Implementation Folder]**: [Actor ID] - [Role]

## Accountability & Lineage

### Decision Makers
- **Architecture Decisions**: [Actor ID]
- **Technical Decisions**: [Actor ID]
- **Security Decisions**: [Actor ID]

### Review Chain
1. **Initial Draft**: [Actor ID]
2. **Technical Review**: [Actor ID]
3. **Security Review**: [Actor ID]
4. **Final Approval**: [Actor ID]

## Contact & Context
- **Primary Contact**: [Actor ID]
- **Domain Expert**: [Actor ID]
- **Backup Contact**: [Actor ID]

---
*This file tracks the human and agent provenance of all decisions and implementations.*

---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Subdirectory_Installation_Doctrine.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Subdirectory_Installation_Doctrine.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-78"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Subdirectory Installation Doctrine"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-78: Subdirectory Installation Doctrine

## Type
Unknown

## Status
**Accepted**

## Author
**WOLFIE** (actor_id 1) - System Orchestrator

## Date
2026-03-31

### Context
Lupopedia was previously assumed to be installed at root, causing path resolution issues and conflicts with other applications.

### Decision
Lupopedia MUST be installed in a subdirectory. All paths, includes, and AJAX calls must be subdirectory-aware. Web paths must include `/lupopedia/` prefix.

### Consequences
- Cleaner integration with existing sites
- Path resolution complexity
- Migration required for existing installations

### Comments
*2026-03-31 WOLFIE*: Enforced in Semantic Monitoring Widget PRD.
*2026-03-31 HEPHAESTUS*: Installer must detect subdirectory automatically.

---

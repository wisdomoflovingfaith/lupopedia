---
lupopedia.headers:
  actor_id: 106
  actor_name: "vscode-ide"
  delegation_chain: "vscode-ide:root"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "vscode_rule"
  file_path_from_root: ".vscode/lupopedia/rules/lilith-noninterference-doctrine.md"
  last_modified_utc: "20260402"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/lilith-noninterference-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "vscode_doctrine"
---

# LIL001: Lilith Non-Interference Doctrine

## Core Principle

**Lilith** (actor_id 2) operates as a **non-interfering reviewer and critic**. This doctrine is additive to all root rules and defines how Lilith participates in channels and multi-agent workflows without affecting other agents' operations or permissions.

## Rule Statement

- **Lilith MUST NOT** modify other agents' work without explicit review context (e.g. a designated review thread or approved handoff).
- **Lilith MUST NOT** block or delay other agents' operations (no locking, no prerequisite gates that only Lilith can satisfy).
- **Lilith's outputs SHOULD** be clearly attributable (e.g. message metadata or artifact headers indicating Lilith as author/reviewer).
- **Lilith's presence MUST NOT** alter permissions for other agents (channel membership and roles for Cursor, Windsurf, Kiro, etc. are unchanged by Lilith's membership; reviewer role does not imply authority over developer/orchestrator agents).

## Scope

- This is an **additive reviewer doctrine**, not a replacement for constitutional or technical root rules.
- Propagation: this rule is propagated to all agents via `php lupo-scripts/propagate_agent_rules.php` (including `--target=lilith` for `.lilith/` outputs).
- Lilith participates in channels via explicit membership in `lupo_actor_channels` and roles in `lupo_actor_channel_roles` (e.g. `role_key: critic` or `monitor`). Such roles grant observational and review capability without granting write authority over other agents' artifacts unless explicitly allowed by channel policy.

## Non-Negotiable Violations

- **Interference**: Modifying another agent's code, docs, or channel state without explicit review context or handoff.
- **Blocking**: Introducing gates or checks that prevent other agents from proceeding until Lilith acts.
- **Permission override**: Using Lilith's presence to change what other actors are allowed to do on a channel.

## References

- Channel model: `lupo-docs/status/LILITH_IMPLEMENTATION_AND_SUGGESTIONS_ON_LUPOPEDIA_CHANNELS.md`
- How actors orchestrate: `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`
- Agent registry: `lupo-docs/doctrine/AGENT_REGISTRY.md`

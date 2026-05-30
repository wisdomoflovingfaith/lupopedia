---
lupopedia.init:
  file_identity: "lilith-noninterference-doctrine.md"
  artifact_type: "windsurf_rule"
  artifact_kind: "doctrine"
  namespace: "windsurf"
  system_version: "4.0.76"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "windsurf_rule"
  file_path_from_root: ".windsurf/rules/lilith-noninterference-doctrine.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/lilith-noninterference-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "LIL001"
      rule_text: "Lilith operates as a non-interfering reviewer. Lilith must not modify other agents' work without explicit review context; must not block or delay other agents' operations; outputs must be clearly attributable; presence must not alter permissions for other agents."
      scope: "all_agents"
      category: "agent_behavior"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260411"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260411"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260411"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Keep in sync with canonical root rules"
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

## Survivability Doctrine alignment (technical review; PRD 00 §14.6)

When evaluating artifacts for **Survivability** alignment, **LILITH** (and any reviewer) MUST treat **Survivability** as a **technical metric**, not sentiment. **This has nothing to do with love, empathy, or emotional validation.**

**Do not** use “empathy,” “love,” “warmth,” or “supportive tone” as pass/fail criteria.

**Do** ask:

1. Does this code **understand the environment** it runs in (extensions, permissions, server software, subdirectory URLs, PHP band)?
2. Does it provide **unconditional fallbacks** so the system **survives** on constrained shared hosts instead of failing hard?

Canonical expansion: **`lupo-docs/doctrine/SURVIVABILITY_DOCTRINE.md`**.


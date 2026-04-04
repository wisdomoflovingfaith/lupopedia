---
lupopedia.headers:
  when_updated: "20260404160851"
  file_path_from_root: "lupo-docs/implementations/service_agents/status/STATUS.md"
  last_modified_utc: "20260404160851"
  artifact_type: documentation
  artifact_kind: status
  purpose: "Service agent implementation status"
  actor_id: 102
lupopedia.footer:
  last_verified: "20260404"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
---

# file: service_agents status — web_path: (implementation)

# Status: service agents

**Last reviewed (UTC):** 20260404160851

## Completion (high level)

| Area | State | Notes |
|------|-------|--------|
| Constitutional §5.10 | **Done** | Roster + runtime loop contrast |
| Doctrine `SERVICE_AGENT_ARCHITECTURE.md` | **Done** | PHP-first, KAIROS flow, THOTH grounding |
| KAIROS PHP service + API | **In tree** | `KairosConsolidationService`, `kairos-api.php` |
| IRIS PHP faucet | **In tree** | `lupo-includes/classes/iris.php` |
| Prompt-to-PHP migrations | **Ongoing** | See **`decisions/`** |

## What is next

- Keep **`runtime_actors.yaml`** and dialog UI aligned so service agents are not mistaken for default chat targets.
- Record further “logic moved to PHP” decisions under **`decisions/`**.

This output complies with Lupopedia Constitutional Root Rules.

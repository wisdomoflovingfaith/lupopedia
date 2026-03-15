---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/projects/PROJECTS.md"
      reason: "Project definition and lifecycle"
    - path: "lupo-docs/doctrine/ROOTRULES_EXTERNAL_ACTOR.md"
      reason: "External actor doctrine"
  required_context:
    - "External actors interact with projects via REST; they must declare project context."

lupopedia.metadata:
  comment: "API specification for external actor interaction with projects."
  title: "Projects API"
  description: "External agents must declare project_id, channel_id, thread_id, actor_id, and use BIGINT UTC timestamps."

lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/projects/PROJECTS_API.md"
  last_modified_utc: "20260315"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 102
  purpose: "External actor interaction with projects; request/response and capability rules."
  artifact_type: "documentation"
  artifact_kind: "api_spec"
  tags: ["projects", "api", "external_actor"]

lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  federation_node_id: 1

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/projects/PROJECTS.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/ROOTRULES_EXTERNAL_ACTOR.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260315"
  last_verified_by: "cursor"
  next_action:
    - "Align REST dialog endpoints with this spec when implementing project-aware API"
---

# Projects API — External Actor Specification

This document describes **external actor** interaction with projects. IDE agents infer project context from the workspace; **external actors must declare project context** in every request.

---

## API Concepts

External agents must **declare project context** in payloads that create or reference dialogs, threads, or channel-scoped work.

**Example request payload:**

```json
{
  "project_id": 1001,
  "project_name": "lupopedia-core",
  "federation_node_id": 1,
  "channel_id": 42,
  "thread_id": 1001,
  "actor_id": 102,
  "actor_name": "cursor",
  "message": "Working on project documentation",
  "timestamp": "20260315235959"
}
```

- **timestamp** must be BIGINT UTC `YYYYMMDDHHIISS` (e.g. `20260315235959`).
- **actor_id** must come from the canonical actor registry.
- **project_id**, **channel_id**, and **thread_id** scope the operation to a project and its channel/thread.

**Implementation (4.0.76):** Project REST endpoints live under **`lupo-api/v1/projects/`**: `list.php` (GET), `get.php` (GET, by id), `create.php` (POST), `update.php` (PUT/POST), `archive.php` (POST), `freeze.php` (POST). All responses include `utc_timestamp` (BIGINT) and `system_version`. See [PROJECTS.md](PROJECTS.md) for implementation locations and [CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md](../status/CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md) for migration.

---

## External Actor Capabilities

**External actors MUST:**

- Authenticate using API key or OAuth (as defined by the deployment).
- Include **project_id** in every request that targets project-scoped resources.
- Include **actor_id** from the registry (no anonymous operations).
- Include **channel_id** and **thread_id** when posting or querying dialogs.
- Use **BIGINT UTC** timestamps (`YYYYMMDDHHIISS`) for all time fields.

**External actors MUST NOT:**

- Write files directly to the repository.
- Modify database schema.
- Bypass actor identity requirements.

External actors communicate **only** through REST dialog endpoints (and any other project-approved API surfaces). They do not get filesystem or schema write access.

---

## Relation to Projects Doctrine

- Projects contain channels; channels contain threads/dialogs. Therefore any dialog or thread operation is implicitly within a project once **project_id** and **channel_id** are supplied.
- See [PROJECTS.md](PROJECTS.md) for lifecycle, governance, and registry rules.
- See [PROJECT_REGISTRY_DOCTRINE.md](lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md) for canonical project identity and allocation doctrine.
- See [lupo-docs/doctrine/ROOTRULES_EXTERNAL_ACTOR.md](../doctrine/ROOTRULES_EXTERNAL_ACTOR.md) for database, directory, and identity doctrine that external actors must follow.

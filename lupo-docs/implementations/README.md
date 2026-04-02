---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "lupo-docs/implementations/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/README.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "implementations-index"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "implementation"
  artifact_kind: "index"
  purpose: "Index of all implementation documentation"
  tags:
  - "implementation"
  - "index"
  - "documentation"
---

# Implementations

This directory contains implementation documentation for PRDs.

## Implementation Index

| PRD | Implementation Folder | Status | Last Updated |
|-----|---------------------|--------|--------------|
| [00_root_constitutional_system_requirements.md](../prd/00_root_constitutional_system_requirements.md) | N/A (constitutional) | N/A | N/A |
| [18_channel_chat_display.md](../prd/18_channel_chat_display.md) | [18_channel_chat_display/](./18_channel_chat_display/) | 🟡 In Progress | 2026-04-02 |
| [25_departments_system.md](../prd/25_departments_system.md) | [25_departments_systems/](./25_departments_systems/) | 🟡 In Progress | 2026-04-02 |
| [26_five_layer_documentation_architecture.md](../prd/26_five_layer_documentation_architecture.md) | [26_five_layer_documentation_architecture/](./26_five_layer_documentation_architecture/) | 🟢 Complete | 2026-04-02 |
| [26_project_structure.md](../prd/26_project_structure.md) | [26_project_structure/](./26_project_structure/) | 🟡 In Progress | 2026-04-02 |
| 24_actor_onboarding_flow | [24_actor_onboarding_flow/](./24_actor_onboarding_flow/) | Draft |

## Structure

Each implementation folder contains:
- `README.md` - Overview of the implementation
- `changelog.md` - Changes to the implementation
- `discussions.md` - Design discussions
- `todo.md` - Remaining tasks
- Implementation files (lowercase with underscores)

## Template

Use the [_template/](./_template/) folder as a starting point for new implementations.

## Naming Convention

Implementation files use `lowercase_with_underscores` naming for consistency with PRD files.

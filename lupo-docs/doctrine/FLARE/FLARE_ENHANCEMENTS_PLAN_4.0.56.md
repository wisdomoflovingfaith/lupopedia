# FLARE Header Enhancements Plan - v4.0.56 (Refined)

## 1. Overview
This plan outlines the introduction of `flame.init` and `flame.close` blocks to the FLARE header protocol. These enhancements provide lifecycle hooks for pre-processing and post-reading actions. Refined in v4.0.56 to ensure implementation safety, typed actions, and targeted enforcement.

## 2. New Header Blocks

### 2.1 flame.init (Initialization/Requirements)
The `flame.init` block defines prerequisites that must be met before an agent processes the file.
- **Canonical Key**: `flame.init`
- **Execution Mode**: `execution_mode` (Required)
  - `advisory`: Agents may ignore actions.
  - `required`: Agents must execute or fail.
  - `system`: reserved for system-level operations.
- **Typed Actions**: Actions must be objects, not plain strings.
  - Example: `dependency_check: "path/to/file"`
  - Example: `service_check: "DatabaseFactory"`

### 2.2 flare.conditional (Guards & Briefing)
The `flare.conditional` block provides granular execution control and a "5W1H" briefing for the artifact.
- **guards**: Defines who can execute (`allow`/`deny`), when (`time_window`), and under what environmental conditions (`conditions`).
- **brief**: A human and machine readable summary of the artifact's purpose, scope, urgency, and success criteria (Who, What, Where, When, Why, How).

### 2.3 flare.headers (Standard Metadata)
Standard metadata for indexing and attribution.
- **Canonical Key**: `flare.headers`
- Includes: `file_path_from_root`, `system_version`, `actor_id`, `channel_id`, `artifact_kind`, etc.

### 2.4 flame.close (Finalization/Post-Actions)
The `flame.close` block defines actions to be performed after the file has been processed.
- **Canonical Key**: `flame.close`
- **Actor Responsibility**: `actor_id` defaults to the file's `flare.headers.actor_id` to maintain local responsibility.
- **Typed Actions**: Actions must be objects.
  - Example: `type: register_completion`

## 3. Targeted Enforcement (The "Safety Rule")
To prevent massive legacy migration overhead, `flame` blocks are **MANDATORY** only for the following `artifact_kind` values (4.0.55+):
- `prompt`
- `documentation_task`
- `agent_instruction`
- `artifact`
- `thread`

They are **OPTIONAL** for:
- `archive`
- `history`
- `changelog` (unless active development is occurring)

## 4. Canonical Order
Headers MUST follow this exact order to ensure parser stability:
1. `flame.init`
2. `flare.conditional`
3. `flare.headers`
4. `flare.edges`
5. `flare.footer`
6. `flame.close`

## 5. YAML Structure Example

```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flame.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

flare.conditional:
  guards:
    execution_mode: "required"
    allow:
      actor_ids: [0]
      agent_names: ["system"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-10T00:00:00Z"
    conditions:
      - type: env_var_equals
        key: "LUPO_ENV"
        value: "prod"
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents", "maintainers"]
    what:
      artifact_type: "prompt"
      objective: "Example objective"
    where:
      repo_paths: ["path/to/file"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 42
    when:
      urgency: "high"
      effective_utc: "2026-03-04T06:00:00Z"
    why:
      rationale: "Why this artifact exists"
    how:
      method: "How it works"
      success_criteria: ["Criteria 1"]

flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "example.md"
  system_version: "4.0.56"
  actor_id: 1004
  channel_id: 42

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 0.8 }

flare.footer:
  last_verified: "20260304"

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---
```

## 6. Implementation Steps

1.  **Template Update**: Modify `lupo-tools/flare_header_template.txt` to include `flare.conditional`.
2.  **Tooling Support**:
    *   Update `lupo-tools/flare_apply.py` to support `flare.conditional`.
    *   Update `lupo-tools/flare_validate.py` to enforce `flare.conditional` constraints.
3.  **Governance**: Update `FLARE_DOCTRINE.md` to reflect Section 16: Conditional Guards & Briefing.

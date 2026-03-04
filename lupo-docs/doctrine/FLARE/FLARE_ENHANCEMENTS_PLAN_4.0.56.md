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

### 2.2 flame.close (Finalization/Post-Actions)
The `flame.close` block defines actions to be performed after the file has been processed.
- **Canonical Key**: `flame.close`
- **Actor Routing**: `actor_id` defaults to the file's `flare.headers.actor_id` to maintain local responsibility, rather than flooding the system actor (0).
- **Typed Actions**:
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
2. `flare.headers`
3. `flare.edges`
4. `flare.footer`
5. `flame.close`

## 5. YAML Structure Example

```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flame.init:
  requirements:
    flare.version: ">=4.0.55"
  execution_mode: "required"
  pre_actions:
    - dependency_check: "lupo-includes/bootstrap.php"

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

1.  **Template Update**: Modify `lupo-tools/flare_header_template.txt` to include typed placeholders and canonical order.
2.  **Tooling Support**:
    *   Update `lupo-tools/flare_apply.py` to support object-based actions and execution modes.
    *   Update `lupo-tools/flare_validate.py` to enforce ordering and targeted mandatory rules based on `artifact_kind`.
3.  **Governance**: Update `FLARE_DOCTRINE.md` to reflect Section 15: Structural Integrity & Typed Actions.

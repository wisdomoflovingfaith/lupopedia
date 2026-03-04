# FLARE Header Enhancements Plan - v4.0.56

## 1. Overview
This plan outlines the introduction of `flame.init` and `flame.close` blocks to the FLARE header protocol. These enhancements provide lifecycle hooks for pre-processing and post-reading actions in the multi-agent ecosystem. "Flame" is a variant identifier for the FLARE protocol, representing active operations.

## 2. New Header Blocks

### 2.1 flame.init (Initialization/Requirements)
The `flame.init` block defines prerequisites that must be met before an agent processes the file.
- **Aliases**: `flame.on`, `flame.open`, `flame.requirements`
- **Mandatory for**: All markdown files with `system_version` 4.0.55+
- **Fields**:
  - `requirements`: Dict of required versions or states (e.g., `flare.version: "4.0.55+"`)
  - `pre_actions`: List of strings indicating actions an agent should perform before reading.

### 2.2 flame.close (Finalization/Post-Actions)
The `flame.close` block defines actions to be performed after the file has been processed.
- **Aliases**: `flame.off`
- **Default Behavior**: For all 4.0.55+ files, `actor_id` defaults to 0 (system) in this block unless specified.
- **Fields**:
  - `post_actions`: List of actions to take after reading.
  - `actor_id`: Target actor for the result (default: 0).
  - `faucet_id`: Optional identifier for a specialized faucet to handle the result.

## 3. YAML Structure Example

```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flame.init:
  requirements:
    flare.version: "4.0.55+"
  pre_actions:
    - "verify dependency path"

flare.headers:
  # ... existing fields ...
  system_version: "4.0.56"

flare.edges:
  # ... existing fields ...

flare.footer:
  # ... existing fields ...

flame.close:
  post_actions:
    - "register completion in channel 0"
  actor_id: 0
---
```

## 4. Implementation Steps

1.  **Template Update**: Modify `lupo-tools/flare_header_template.txt` to include the new sections.
2.  **Tooling Support**:
    *   Update `lupo-tools/flare_apply.py` to support generating these blocks.
    *   Update `lupo-tools/flare_validate.py` (or `flare_header_issues.json` logic) to enforce these blocks for 4.0.55+ files.
3.  **Governance**:
    *   Update `FLARE_DOCTRINE.md` to document the purpose and usage of these blocks.
    *   Align with `ActorService` and `FaucetLoader` for `flame.close` integration.
4.  **Application**: Apply the new header structure to `CHANGELOG.md` and other key 4.0.55/4.0.56 files.

## 5. Pros and Cons

### Pros
- **Declarative Requirements**: Agents know if they can process a file before reading the body.
- **Standardized Post-Processing**: Clear instructions for what happens next.
- **Improved Automation**: Faucet integration allows for automated task handovers.

### Cons
- **Header Bloat**: Increases the size of the prologue.
- **Update Effort**: Existing 4.0.55 files need to be processed to be fully compliant.

## 6. Next Actions
- [ ] Update template files.
- [ ] Patch Python tools.
- [ ] Generate implementation report.

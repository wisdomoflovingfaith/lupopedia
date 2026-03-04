# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/CURSOR_FLARE_APPLY_LINK_CHECK_4.0.57

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
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "FLARE_APPLY URL mapping existence check (4.0.57)"
    where:
      repo_paths: ["docs/status/CURSOR_FLARE_APPLY_LINK_CHECK_4.0.57.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:56:12Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

flare.headers:
  flare.version: "1.0"
  flare.schema: "status"
  file_path_from_root: "docs/status/CURSOR_FLARE_APPLY_LINK_CHECK_4.0.57.md"
  file_hash: "3c94a1466003e1177be5dcbc951329ac04ae3a9ec1d10588ac63dbfd62c93de1"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE_APPLY URL mapping existence check (4.0.57)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["docs", "status", "cursor_flare_apply_link_check_4057md"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["docs/status/CURSOR_FLARE_APPLY_LINK_CHECK_4.0.57.md", "http://www.lupopedia.com/status/CURSOR_FLARE_APPLY_LINK_CHECK_4.0.57"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# FLARE_APPLY URL mapping existence check (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Target:** http://www.lupopedia.com/flare_apply → markdown documentation for `lupo-tools/flare_apply.py`

## Search performed

Searched repo and DB seeds for:

- `flare_apply`
- `lupopedia.com/flare_apply`
- `/flare_apply`

## Findings

### 1. No existing URL mapping for `/flare_apply`

- **lupo_contents:** No row with `custom_path = 'flare_apply'` or `file_path_from_root` pointing to a flare_apply doc.
- **lupo_channel_content:** No row with `web_path` containing `flare_apply`. The only similar seed is `web_path = 'http://www.lupopedia.com/FLARE'` (FLARE.md) in `install_new_lupopedia.sql`.
- **UrlResolver** (Tier 1) resolves only from `lupo_contents` via `file_path_from_root`, `custom_path`. No seed or code maps `/flare_apply` to any markdown file.
- **Module routing:** `lupo_route_slug()` calls `lupo_resolve_web_path()` only when slug matches `^(doctrine|qa|docs|flp)/`. The slug `flare_apply` does **not** match, so the resolver is never invoked for `http://www.lupopedia.com/flare_apply`.

### 2. References to flare_apply (tool only)

- **CHANGELOG.md, docs/status/*.md, lupo-docs/doctrine/FLARE/*.md:** Mention `lupo-tools/flare_apply.py` as tooling; no URL mapping.
- **flare_see.py:** Builds `artifacts/index/flame_see_index.json` from `flame.see` mappings in markdown files. No markdown file currently declares a `flame.see` mapping for `http://www.lupopedia.com/flare_apply`.

### 3. Conclusion

| Item | Status |
|------|--------|
| Mapping exists (DB or seed) | **No** |
| Markdown file for flare_apply doc | **No** (no `docs/doctrine/FLARE/FLARE_APPLY.md`) |
| Route that invokes resolver for `flare_apply` | **No** |
| `lupo see http://www.lupopedia.com/flare_apply` | **No** (no flame.see mapping) |

**Action:** Create mapping end-to-end: markdown doc, FLARE header with flame.see, route for slug `flare_apply`, and seed row in `lupo_contents` (and optionally `lupo_channel_content` if needed). Ensure `content_resolve_body_from_file` can serve the doc via `file_path_from_root`.

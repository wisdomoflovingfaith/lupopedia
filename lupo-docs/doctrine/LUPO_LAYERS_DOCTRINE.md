---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/LUPO_LAYERS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPO_LAYERS_DOCTRINE.md"
  last_modified_utc: "20260406034823"
  when_updated: "20260406034823"
  federation_node_id: 0
  channel_id: 42
  thread_id: "lupo-layers-doctrine"
  author:
    type: "actor"
    id: 1
    name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "constitutional"
  purpose: "Canonical doctrine for shipped UI DHTML layers — LupoLayer in lupo-layers.js; heritage DynAPI and PRD 00 section 16.1 alignment"
  status: "active"
  tags:
    - "ui"
    - "layers"
    - "lupolayer"
    - "lupo-layers.js"
    - "animation"
    - "prd00"
    - "rule_93"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "PRD 00 section 16 UI layer and animation (RULE 93.UI_LAYERS); section 9.20 proven code"
    - to: "lupo-includes/js/lupo-layers.js"
      type: references
      weight: 1.0
      reason: "Canonical implementation (LupoLayer, LupoLayerInit, DynLayerInit alias)"
    - to: "lupo-includes/js/dynapi/js/dynlayer.js"
      type: references
      weight: 0.95
      reason: "Heritage DynLayer lineage; in-tree for proven paths only per section 9.20"
    - to: "lupo-docs/doctrine/DYNAPI_DOCTRINE.md"
      type: references
      weight: 0.85
      reason: "Deprecated heritage narrative; historical context for DynAPI"
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 0.9
      reason: "WOLFIE Eyes / layer choreography consumer"
lupopedia.footer:
  last_verified: "20260406034823"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "Keep in lockstep with PRD 00 section 16.1 and lupo-layers.js header comment"
    - "When extending API, preserve no-eval and no string-timer animation constraints"
---

# file: LUPO_LAYERS_DOCTRINE — delegation: wolfie:root — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPO_LAYERS_DOCTRINE.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPO_LAYERS_DOCTRINE.md)

# LUPO_LAYERS DOCTRINE (formerly DynAPI as default)

This document is the **active** doctrine for **shipped** browser UI that needs **absolute positioning**, **z-index choreography**, and **slide-style motion**. It **supersedes** the historical default described in **[DYNAPI_DOCTRINE.md](DYNAPI_DOCTRINE.md)** (now **deprecated** for normative use).

**Constitutional source:** [PRD 00 — section 16](00_root_constitutional_system_requirements.md) (RULE **93.UI_LAYERS**) and section **9.20** (proven code preservation).

## 1. What is LupoLayer?

**LupoLayer** is the constitutional replacement for using **DynAPI `dynlayer.js`** as the default layer controller for **new** work. It lives in **`lupo-includes/js/lupo-layers.js`** and provides:

- A **DynLayer-compatible API surface** where migration matters: `moveTo()`, `moveBy()`, `slideTo()`, `slideBy()`, `show()`, `hide()`, clip helpers, `write()`, `setZ()`, etc. (see file header JSDoc in `lupo-layers.js` for the exact contract).
- **CSS transitions** for smooth motion when appropriate (e.g. `slideTo(x, y, durationMS)` with sufficient duration).
- **Stepped animation** parity with heritage `slideTo` call shapes **without** `eval()` and **without** string-based `setTimeout` / `setInterval` for control flow.
- **Zero npm / zero build** dependency — vanilla JS only.
- **Init without eval:** `LupoLayerInit` / heritage alias **`DynLayerInit`**; **`DynLayer === LupoLayer`** for legacy `new DynLayer(id)` call sites. **Do not** load **`lupo-layers.js`** together with legacy **`dynapi/js/dynlayer.js`** (duplicate globals).

## 2. Why DynAPI is no longer the default doctrine

| Reason | Explanation |
|--------|-------------|
| **Constitutional compliance** | Heritage **DynAPI** patterns relied on **`eval()`**-class behavior; **PRD 00 section 16** forbids that class of pattern for **new** shipped UI animation/control flow. **LupoLayer** is written to the same lineage **without** those patterns. |
| **Modern motion** | Prefer **GPU-friendly CSS transitions** where they match product needs; keep stepped slides when parity or fallbacks require it. |
| **Maintainability** | One modern, reviewed module (`lupo-layers.js`) is easier for agents and humans to extend than spreading new code across legacy eval-era patterns. |
| **Same call sites** | WOLFIE Eyes and similar surfaces can move to **`LupoLayer`** while preserving familiar method names. |

## 3. Heritage DynAPI (in-tree, not the default)

- **`lupo-includes/js/dynapi/js/dynlayer.js`** (and related DynAPI tree) remains **in-tree** for **proven** legacy paths under **PRD 00 section 9.20**.
- **New features** MUST **not** copy **`eval()`**, **`new Function(string)`**, or **string-based timers** from heritage code.
- Narrative and tone context for the old “DynAPI doctrine” lives in **[DYNAPI_DOCTRINE.md](DYNAPI_DOCTRINE.md)** — treated as **historical**, with **`status: deprecated`** in its header.

## 4. Agent and implementation rules (summary)

1. **Mandatory:** New layering / slide / z-index choreography on **shipped** surfaces uses **`LupoLayer`** (or thin wrappers that delegate to it). See **PRD 00 section 16.1** for the full rule table.
2. **Forbidden:** Introducing npm/webpack/vite/babel as a **runtime** requirement for these surfaces; adding framework animation stacks for the same scope.
3. **Heritage edits:** Changes to **`dynlayer.js`** require justification under **section 9.20** — preserve working behavior; do not “modernize away” without cause.
4. **Single source for API truth:** When this doctrine and the source file disagree, **`lupo-includes/js/lupo-layers.js`** (header + implementation) wins; update this doctrine after intentional API changes.

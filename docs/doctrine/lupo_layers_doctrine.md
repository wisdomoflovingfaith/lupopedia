---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/lupo_layers_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupo_layers_doctrine.md
  status: active
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: null
  federation_node_id: 0
  thread_key: layers-doctrine
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: LUPO_LAYERS_DOCTRINE — delegation: wolfie:root — web_path: [http://www.lupopedia.com/lupopedia/docs/doctrine/LUPO_LAYERS_DOCTRINE.md](http://www.lupopedia.com/lupopedia/docs/doctrine/LUPO_LAYERS_DOCTRINE.md)

# LUPO_LAYERS DOCTRINE (formerly DynAPI as default)

This document is the **active** doctrine for **shipped** browser UI that needs **absolute positioning**, **z-index choreography**, and **slide-style motion**. It **supersedes** the historical default described in **[DYNAPI_DOCTRINE.md](DYNAPI_DOCTRINE.md)** (now **deprecated** for normative use).

**Constitutional source:** [PRD 00 — section 16](00_root_constitutional_system_requirements.md) (RULE **93.UI_LAYERS**) and section **9.20** (proven code preservation).

## 1. What is LupoLayer?

**LupoLayer** is the constitutional replacement for using **DynAPI `dynlayer.js`** as the default layer controller for **new** work. It lives in **`includes/js/layers.js`** and provides:

- A **DynLayer-compatible API surface** where migration matters: `moveTo()`, `moveBy()`, `slideTo()`, `slideBy()`, `show()`, `hide()`, clip helpers, `write()`, `setZ()`, etc. (see file header JSDoc in `layers.js` for the exact contract).
- **CSS transitions** for smooth motion when appropriate (e.g. `slideTo(x, y, durationMS)` with sufficient duration).
- **Stepped animation** parity with heritage `slideTo` call shapes **without** `eval()` and **without** string-based `setTimeout` / `setInterval` for control flow.
- **Zero npm / zero build** dependency — vanilla JS only.
- **Init without eval:** `LupoLayerInit` / heritage alias **`DynLayerInit`**; **`DynLayer === LupoLayer`** for legacy `new DynLayer(id)` call sites. **Do not** load **`layers.js`** together with legacy **`dynapi/js/dynlayer.js`** (duplicate globals).

## 2. Why DynAPI is no longer the default doctrine

| Reason | Explanation |
|--------|-------------|
| **Constitutional compliance** | Heritage **DynAPI** patterns relied on **`eval()`**-class behavior; **PRD 00 section 16** forbids that class of pattern for **new** shipped UI animation/control flow. **LupoLayer** is written to the same lineage **without** those patterns. |
| **Modern motion** | Prefer **GPU-friendly CSS transitions** where they match product needs; keep stepped slides when parity or fallbacks require it. |
| **Maintainability** | One modern, reviewed module (`layers.js`) is easier for agents and humans to extend than spreading new code across legacy eval-era patterns. |
| **Same call sites** | WOLFIE Eyes and similar surfaces can move to **`LupoLayer`** while preserving familiar method names. |

## 3. Heritage DynAPI (in-tree, not the default)

- **`includes/js/dynapi/js/dynlayer.js`** (and related DynAPI tree) remains **in-tree** for **proven** legacy paths under **PRD 00 section 9.20**.
- **New features** MUST **not** copy **`eval()`**, **`new Function(string)`**, or **string-based timers** from heritage code.
- Narrative and tone context for the old “DynAPI doctrine” lives in **[DYNAPI_DOCTRINE.md](DYNAPI_DOCTRINE.md)** — treated as **historical**, with **`status: deprecated`** in its header.

## 4. Agent and implementation rules (summary)

1. **Mandatory:** New layering / slide / z-index choreography on **shipped** surfaces uses **`LupoLayer`** (or thin wrappers that delegate to it). See **PRD 00 section 16.1** for the full rule table.
2. **Forbidden:** Introducing npm/webpack/vite/babel as a **runtime** requirement for these surfaces; adding framework animation stacks for the same scope.
3. **Heritage edits:** Changes to **`dynlayer.js`** require justification under **section 9.20** — preserve working behavior; do not “modernize away” without cause.
4. **Single source for API truth:** When this doctrine and the source file disagree, **`includes/js/layers.js`** (header + implementation) wins; update this doctrine after intentional API changes.

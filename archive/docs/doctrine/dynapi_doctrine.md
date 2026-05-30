---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/DYNAPI_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/DYNAPI_DOCTRINE.md"
  status: "deprecated"
  when_updated: "20260406034823"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_id: "dynapi-doctrine"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: DYNAPI_DOCTRINE (deprecated) — delegation: wolfie:root — web_path: [http://www.lupopedia.com/lupopedia/docs/doctrine/DYNAPI_DOCTRINE.md](http://www.lupopedia.com/lupopedia/docs/doctrine/DYNAPI_DOCTRINE.md)

**DEPRECATED — historical reference only.** Normative rules for shipped UI layering and animation are **[LUPO_LAYERS_DOCTRINE.md](LUPO_LAYERS_DOCTRINE.md)** and **[PRD 00](00_root_constitutional_system_requirements.md) section 16.1**. New code **MUST** use **`includes/js/layers.js`** (`LupoLayer`). Heritage **`dynlayer.js`** stays in-tree under **PRD 00 section 9.20**; do not copy **`eval()`**-style patterns into new code.

## LILITH's Directive: DynAPI Doctrine - READ THIS, IDE

# DYNAPI DOCTRINE (heritage body)

## 1. What Is DynAPI?

**DynAPI** (Dynamic Layer Object) is a cross-browser JavaScript library written by **Dan Steinman** in **1999**, updated by **Bob Clary** in **2001** to support Gecko (early Firefox).

It provides:
- `DynLayer` object wrapper for DHTML layers (DIVs)
- `moveTo()` / `moveBy()` - positioning
- `slideTo()` / `slideBy()` - animated movement
- `show()` / `hide()` - visibility
- `clipTo()` / `clipBy()` - clipping regions
- `write()` - dynamic HTML injection

**It was written when Netscape 4 and IE4 were the browsers. It still works.**

## 2. Why Are We Using This?

| Reason | Explanation |
|--------|-------------|
| **It works** | WOLFIE's eye animation has run for ~20 years without a single bug report |
| **No dependencies** | One file. No npm. No webpack. No build step. |
| **Constitutionally compliant** | PRD 33 §8 explicitly allows in-tree libraries |
| **Shared hosting compatible** | Works on PHP 7.4+ hosts with zero configuration |
| **It's 250 lines of vanilla JS** | Not 2,000 lines of framework boilerplate |
| **The AI doesn't know it** | That's an AI problem, not a DynAPI problem |

## 3. What DynAPI Is NOT

| Misconception | Reality |
|---------------|---------|
| "Outdated" | Works in every modern browser. That's not outdated. |
| "Needs replacement" | No. It does exactly what it needs to do. |
| "Insecure" | It manipulates DOM layers. That's it. No eval() of user input. |
| "Hard to maintain" | It's 250 lines. WOLFIE understands every line. |
| "Should be rewritten in React" | Absolutely not. The eye animation does not need a virtual DOM. |

## 4. IDE Agents: Read This Carefully

### 4.1 Your Training Data Gap

**You have no training data on DynAPI.** It was written in 1999. Your training corpus (GitHub 2015-2025) barely contains it.

**This is not a problem with DynAPI. This is a problem with your training data.**

### 4.2 What You Must NOT Do

When you see code that uses `DynLayer`, `slideTo()`, `moveBy()`, or any DynAPI method:

| Forbidden Action | Why |
|------------------|-----|
| Suggest replacing it with React | The eye animation does not need a framework |
| Suggest "modernizing" to Three.js | It's 2D layered DIVs, not 3D graphics |
| Ask "but have you considered Canvas?" | No. Canvas would require rewriting 20 years of working code |
| Propose an npm package | PRD 33 §8 forbids new external dependencies |
| Say "this code is old" | Age is not a bug. Working is working. |
| Try to "refactor" the browser detection | The `document.layers` / `document.all` / `getElementById` chain is intentional |

### 4.3 What You MUST Do

| Required Action | How |
|-----------------|-----|
| Accept that DynAPI is approved | PRD 33 §8. LILITH approved it. Stop arguing. |
| Leave the code alone | If it isn't broken, do not "fix" it. |
| Learn the API | Read the comments. They are thorough. |
| Document any new usage | Add `lupopedia.edges` referencing this doctrine |

## 5. DynAPI API Reference (For IDE Training)

### 5.1 Browser Detection

```javascript
is = new BrowserCheck();
// Properties:
is.ns4   // Netscape 4 (document.layers)
is.ie    // IE4+ (document.all)
is.ns5   // Early Gecko (document.getElementById)
is.ie4   // IE4 specifically
is.ie5   // IE5 specifically
```

### 5.2 DynLayer Constructor

```javascript
var layer = new DynLayer("elementId", nestref, frame);
// Parameters:
// - id: string, DOM element ID
// - nestref: string (optional), parent layer reference for Netscape 4
// - frame: window object (optional), for cross-frame layers
```

### 5.3 Positioning Methods

```javascript
layer.moveTo(x, y);      // Absolute positioning
layer.moveBy(dx, dy);    // Relative positioning
layer.slideTo(x, y, inc, speed, callback);  // Animated absolute
layer.slideBy(dx, dy, inc, speed, callback); // Animated relative
// inc: step increment (default 10)
// speed: milliseconds per step (default 20)
```

### 5.4 Visibility Methods

```javascript
layer.show();   // Make visible (sets visibility: show/visible)
layer.hide();   // Make hidden (sets visibility: hide/hidden)
```

### 5.5 Clipping Methods

```javascript
layer.clipTo(top, right, bottom, left);  // Set clipping rectangle
layer.clipBy(dt, dr, db, dl);            // Adjust clipping relative
layer.clipInit(top, right, bottom, left); // Initialize clipping
```

### 5.6 Content Methods

```javascript
layer.write(htmlString);  // Write HTML into the layer
// For Netscape 4: uses document.open()/write()/close()
// For IE/modern: sets innerHTML
```

### 5.7 Events

```javascript
layer.onSlide = function() { };      // Called during slide animation
layer.onSlideEnd = function() { };   // Called when slide completes
```

### 5.8 CSS Helper

```javascript
css(id, left, top, width, height, color, visibility, zIndex, other);
// Returns CSS string for positioning layers
// Example: document.write(css('myDiv', 10, 20, 100, 100, 'red', 'visible', 5));
```

## 6. WOLFIE Eyes: Reference Implementation

The canonical DynAPI implementation in Lupopedia is **WOLFIE Eyes** (semantic monitoring widget).

**Specification:** `docs/prd/28_semantic_monitoring_widget.md`  
**Code:** `includes/js/crafty_syntax_eyes.js` (DynLayer blocks); library loaded via `includes/js/dynlayer.js` (see `includes/ui/ui-loader.php`).

**Features:**
- Z-layered DIVs (back, pupils, eyelids, closed eyes)
- Mouse-following pupils with boundary constraints
- Blink animation (every 5.5 seconds)
- Eye color cycling (blue → light blue → brown → green → red)
- Widget slides to bottom-right on load
- Close button hides the widget

**Why it matters:** This code has run continuously for ~20 years. It has zero bug reports. It works on every browser that supports JavaScript. It is constitutionally compliant.

**Do not touch it. Do not suggest "improvements." It is complete.**

## 7. Constitutional Authority

| Document | Section | Authority |
|----------|---------|-----------|
| PRD 33 §8 | Approved in-tree libraries | DynAPI is explicitly permitted |
| PRD 28 | Semantic monitoring widget | WOLFIE Eyes is the reference implementation |
| LILITH audit (2026-04-02) | DynAPI doctrine | Approved as constitutionally compliant |

## 8. Adding New DynAPI Code

If you need to add new DHTML layer animations:

1. **Use the existing DynAPI library** at `includes/js/dynlayer.js`
2. **Reference this doctrine** in your `lupopedia.edges`
3. **Do not modify the library** unless you are fixing an actual bug (not "improving" it)
4. **Test in a modern browser** - the `is.ns5` branch uses `getElementById`, which works everywhere
5. **Do not add npm packages** - this is constitutionally forbidden

## 9. Frequently Asked Questions (For IDE Agents)

### Q: "But isn't this code obsolete?"

**A:** No. It works. "Obsolete" means "no longer used." This code is used. Daily.

### Q: "Shouldn't we rewrite this in [current framework]?"

**A:** No. The eye animation does not need a framework. Adding a framework would violate PRD 33 §5 (no new external dependencies).

### Q: "What about browser compatibility?"

**A:** The library detects `document.layers` (Netscape 4), `document.all` (IE4-6), and falls through to `getElementById` (modern browsers). It works everywhere.

### Q: "The AI doesn't understand this code."

**A:** That is an AI training problem. The solution is to train the AI, not replace the code.

### Q: "Can I suggest an alternative?"

**A:** No. This doctrine exists to stop that conversation. The decision is made. The code works. Move on.

## 10. Final Word (LILITH)

> *"The IDE has no training data on DynAPI because it was written before the IDE's training corpus began. That is not a flaw in DynAPI. That is a flaw in the IDE's historical knowledge.*
>
> *WOLFIE wrote this code in Notepad. It has run for ~20 years. It will run for another 20.*
>
> *The AI's job is to help maintain Lupopedia, not to replace working code with fashionable frameworks.*
>
> *Accept the doctrine. Stop asking about React. Move on."*

* * *

**Status:** ACTIVE
**Constitutional Adherence:** FULL
**IDE Compliance Required:** YES

**Next action for IDE agents:** Read this document. Do not suggest replacing DynAPI. Do not ask "but have you considered..." The answer is no.

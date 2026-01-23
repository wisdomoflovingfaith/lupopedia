---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created UI Library Doctrine documenting mandatory rules for treating jQuery, TinyMCE, and DynLayer as canonical, intentional dependencies that must not be replaced, modernized, or refactored."
tags:
  categories: ["doctrine", "ui", "javascript", "dependencies"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "doctrine"]
file:
  title: "UI Library Doctrine"
  description: "Mandatory rules for treating jQuery, TinyMCE, and DynLayer as canonical UI dependencies"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# UI Library Doctrine

This doctrine establishes the canonical JavaScript library dependencies for the Lupopedia UI subsystem. These libraries are intentional, stable dependencies and must not be replaced, modernized, or refactored.

## Overview

Lupopedia intentionally uses three external JavaScript libraries as canonical dependencies of the UI subsystem:

1. **jQuery** - JavaScript library
2. **TinyMCE** - Rich text editor
3. **DynLayer** - Legacy 1999 cross-browser layer engine

These libraries are **NOT** to be replaced, rewritten, modernized, or substituted with any framework or alternative library.

## Library Locations

As the UI is migrated into Lupopedia, these libraries will be placed in:

```
/lupo-includes/js/
```

Cursor must wait for explicit instructions before reorganizing or moving these libraries. They will be placed in the JS folder when migration reaches that step.

## Mandatory Rules

### What Cursor MUST Do

Cursor **MUST**:

- ✅ Reference these libraries exactly as provided
- ✅ Load them from the vendor JS folder (`/lupo-includes/js/`)
- ✅ Preserve their behavior and integration points
- ✅ Treat them as stable, intentional parts of the UI architecture
- ✅ Wait for explicit instructions before reorganizing or moving files

### What Cursor MUST NOT Do

Cursor **MUST NOT**:

- ❌ Replace jQuery with native JavaScript
- ❌ Replace TinyMCE with another editor (Markdown editors, Quill, Slate, etc.)
- ❌ Replace DynLayer with modern animation frameworks (GSAP, Framer Motion, etc.)
- ❌ Convert any of these libraries into ES6 modules
- ❌ Rewrite them for ES6/ES2015+ syntax
- ❌ Minify them unless explicitly instructed
- ❌ Remove legacy patterns or browser checks inside DynLayer
- ❌ Attempt to "modernize" or "update" the libraries
- ❌ Inline library code into other files
- ❌ Refactor library internals
- ❌ Replace library functionality with "modern alternatives"
- ❌ Suggest alternatives or "better" libraries
- ❌ Remove compatibility code or polyfills

## Library Details

### jQuery

**Purpose:** JavaScript library for DOM manipulation, event handling, and AJAX.

**Status:** Canonical dependency - must not be replaced.

**Rules:**
- Do not replace jQuery calls with native JavaScript
- Do not suggest jQuery alternatives
- Preserve all jQuery usage patterns
- Load from `/lupo-includes/js/jquery.js` (or similar)

### TinyMCE

**Purpose:** Rich text WYSIWYG editor for content editing.

**Status:** Canonical dependency - must not be replaced.

**Rules:**
- Do not replace with other editors (Markdown editors, Quill, Slate, etc.)
- Do not suggest TinyMCE alternatives
- Preserve TinyMCE configuration and integration
- Load from `/lupo-includes/js/tinymce/` (or similar)

### DynLayer

**Purpose:** Legacy 1999 cross-browser layer engine for dynamic positioning and animation.

**Status:** Canonical legacy dependency - must not be replaced or modernized.

**Rules:**
- Do not replace with modern animation frameworks
- Do not remove legacy browser checks
- Do not "modernize" the code
- Do not remove compatibility code
- Preserve all legacy patterns and browser detection
- Load from `/lupo-includes/js/dynlayer.js` (or similar)
- This is an intentional legacy library for cross-browser compatibility

## Integration Rules

### File Organization

These libraries will be placed in `/lupo-includes/js/` during UI migration:

```
/lupo-includes/js/
  ├── jquery.js (or jquery.min.js)
  ├── tinymce/
  │   └── [TinyMCE files]
  └── dynlayer.js
```

**Important:** Cursor must wait for explicit instructions before moving or reorganizing these files. They will be placed during migration steps.

### Loading Libraries

Libraries should be loaded using standard `<script>` tags:

```html
<!-- jQuery -->
<script src="/lupo-includes/js/jquery.js"></script>

<!-- TinyMCE -->
<script src="/lupo-includes/js/tinymce/tinymce.min.js"></script>

<!-- DynLayer -->
<script src="/lupo-includes/js/dynlayer.js"></script>
```

### Usage Patterns

- Use jQuery exactly as provided (no conversion to native JS)
- Use TinyMCE with existing configuration patterns
- Use DynLayer with legacy API patterns
- Do not attempt to wrap libraries in modules
- Do not attempt to create "modern" abstractions

## Rationale

These libraries are **intentional choices** for specific reasons:

1. **jQuery** - Established, stable, well-understood DOM manipulation library
2. **TinyMCE** - Mature, feature-rich WYSIWYG editor with proven integration
3. **DynLayer** - Legacy cross-browser compatibility for older browser support

The choice to use these libraries (including the legacy DynLayer) is deliberate and must be respected. Cursor must not second-guess these decisions or suggest "better" alternatives.

## Migration Context

As the UI subsystem is migrated from Crafty Syntax to Lupopedia:

- Libraries will be moved into `/lupo-includes/js/` during migration steps
- Cursor must wait for explicit instructions before moving files
- Integration points will be preserved exactly as they exist
- No modernization or refactoring of library code

## Related Doctrine

- **[Operator UI Doctrine](OPERATOR_UI_DOCTRINE.md)** - Multi-thread, multi-color UI interface
- **[Module Doctrine](MODULE_DOCTRINE.md)** - Module organization and structure
- **[SQL Rewrite Doctrine](SQL_REWRITE_DOCTRINE.md)** - SQL refactoring rules (for contrast with UI library rules)

## Compliance

This doctrine is **mandatory** for all UI-related work. Cursor must:

1. ✅ Treat jQuery, TinyMCE, and DynLayer as canonical dependencies
2. ✅ Never suggest replacements or alternatives
3. ✅ Preserve library code exactly as provided
4. ✅ Wait for explicit instructions before reorganizing files
5. ✅ Load libraries from `/lupo-includes/js/` when placed there
6. ✅ Never modernize, refactor, or rewrite library code

**Violations of this doctrine are not allowed.**

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  message: "Added WOLFIE headers to CSLH-URL-Semantics.md. Phase 2 documentation consistency audit correction."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "crafty-syntax", "url-semantics"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
file:
  title: "Crafty Syntax URL Semantics Doctrine"
  description: "Canonical rules for Crafty Syntax URL semantics: URLs are web-facing slugs exactly as seen in the browser address bar, not filesystem paths"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Crafty Syntax URL Semantics Doctrine

## Core Principle
Crafty Syntax does NOT store filesystem paths. It stores the exact web-facing URL as seen in the browser address bar at the time of the visit.

Examples:
- http://lupopedia.com/what_was_crafty_syntax.php
- https://wordpress.com/reader/blogs/10822809/posts/54283
- http://collaborativepages.com

These values are:
- semantic identifiers
- opaque slugs
- NOT file paths
- NOT internal routes
- NOT guaranteed to correspond to any file on disk

## Migration Rule
During migration into Lupopedia:
- pageurl â†’ slug (stored exactly as-is)
- NEVER attempt to resolve to a filesystem path
- NEVER rewrite or normalize the URL
- NEVER treat it as a file reference

## Recno Resolution
Tables like livehelp_paths_monthly use recnos that map back to pageurl.
Migration must:
- JOIN recno â†’ pageurl
- store the slug
- set content_id = 0 (slug resolution happens later in Lupopedia logic)

## Purpose
This preserves Crafty Syntax's original meaning:
A map of how humans navigated the web, not how files were arranged on disk.

---

## Related Documentation

- [CSLH-Historical-Context.md](../history/CSLH-Historical-Context.md) - Crafty Syntax history and architectural relevance
- [Lupopedia-Reference-Layer-Doctrine.md](Lupopedia-Reference-Layer-Doctrine.md) - Lupopedia as semantic reference layer
- [URL_ROUTING_DOCTRINE.md](URL_ROUTING_DOCTRINE.md) - HTTP URL routing doctrine
- [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](SUBDIRECTORY_INSTALLATION_DOCTRINE.md) - Path handling and installation rules

---

**This doctrine is absolute and binding for all Crafty Syntax URL handling.**

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  message: "Added WOLFIE headers to Lupopedia-Reference-Layer-Doctrine.md. Phase 2 documentation consistency audit correction."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "architecture", "reference-layer"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
file:
  title: "Lupopedia Reference Layer Doctrine"
  description: "Canonical rules defining Lupopedia as a semantic reference layer, not a CMS"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Lupopedia Reference Layer Doctrine

## Core Principle
Lupopedia is NOT a CMS and does NOT replace the host website.

Lupopedia is a semantic reference layer installed in a subdirectory:
    /lupopedia/

The host site continues to:
- serve its own pages
- run its own CMS
- handle its own mod_rewrite rules
- control its own routing

## Lupopedia's Role
Lupopedia acts like a reference book for the website.

Example:
Live page:
    https://wordpress.com/reader/blogs/10822809/posts/54283

Lupopedia view:
    /lupopedia/reader/blogs/10822809/posts/54283

Lupopedia provides:
- semantic metadata
- emotional geometry
- collections and tabs
- truth questions
- agent commentary
- historical context
- relational mapping

## Namespace Isolation
Lupopedia must NEVER collide with host site URLs.

All Lupopedia routes must live under:
    /lupopedia/

Slugs like:
    reader/blogs/10822809/posts/54283
are treated as opaque identifiers, not module names.

## Routing Safety
/lupopedia/.htaccess may override rules ONLY inside /lupopedia/.
It cannot override the root site's routing.

---

## Related Documentation

- [CSLH-URL-Semantics.md](CSLH-URL-Semantics.md) - Crafty Syntax URL semantics doctrine
- [CSLH-Historical-Context.md](../history/CSLH-Historical-Context.md) - Crafty Syntax historical context
- [URL_ROUTING_DOCTRINE.md](URL_ROUTING_DOCTRINE.md) - HTTP URL routing doctrine
- [SUBDIRECTORY_INSTALLATION_DOCTRINE.md](SUBDIRECTORY_INSTALLATION_DOCTRINE.md) - Path handling and installation rules
- [WHAT_LUPOPEDIA_IS.md](../overview/WHAT_LUPOPEDIA_IS.md) - Complete explanation of Lupopedia architecture

---

**This doctrine is absolute and binding for all Lupopedia installations.**

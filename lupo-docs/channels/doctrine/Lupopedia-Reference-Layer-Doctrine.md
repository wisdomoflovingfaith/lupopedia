# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\Lupopedia-Reference-Layer-Doctrine.md"
  file_hash: "d9d40ca270ceba43fbb0a6016ccb1fac437699ed90b80829815f2c69c72ec779"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\doctrine\Lupopedia-Reference-Layer-Doctrine.md"
  file_hash: "ab8511016c4059a294ff20398ce3db4fa66b2174656e91a7bab01a3f45884be5"
  file_path_from_root: "lupo-docs\channels\doctrine\Lupopedia-Reference-Layer-Doctrine.md"
  file_hash: "c4798c96f8268d3d5398c574a8c8bef5b8ce45b76d18672591ea10bd7dc1fef2"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for Lupopedia-Reference-Layer-Doctrine.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "lupopedia-reference-layer-doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.18
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

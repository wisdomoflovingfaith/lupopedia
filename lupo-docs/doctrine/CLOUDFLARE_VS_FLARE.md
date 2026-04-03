---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/CLOUDFLARE_VS_FLARE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CLOUDFLARE_VS_FLARE.md"
  last_modified_utc: "20260403113047"
  when_updated: "20260403113047"
  federation_node_id: 0
  channel_id: 42
  thread_id: "doctrine-header-repair"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "CLOUDFLARE VS FLARE"
  status: active
  tags:
    - "doctrine"
    - "header_repair"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; orphan batch 20260403 (manual category map)"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"
---

# file: CLOUDFLARE_VS_FLARE — delegation: cursor:root

# FLARE vs Cloudflare — Terminology (Doctrine)

In Lupopedia documentation and code, two distinct concepts use similar names:

| Term | Meaning in Lupopedia |
|------|----------------------|
| **FLARE** | The project’s **File-Level Inference Protocol**: YAML frontmatter (or custom headers) at the top of Markdown files and database-backed content. Used for identity, versioning, routing, and semantic integration. See `lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md` and FLIP doctrine. |
| **Cloudflare** | The **CDN/WAF provider** (cloudflare.com). When Lupopedia is fronted by Cloudflare, request headers such as `CF-Connecting-IP`, `CF-IPCountry`, `CF-Ray` are used for real client IP, geolocation, logging, and optional security (threat score, geo blocking). |

**FLARE headers** = Lupopedia’s own metadata blocks in content.  
**Cloudflare headers** = HTTP headers added by Cloudflare’s proxy to the request to the origin.

The Cloudflare integration (e.g. `CloudflareRequestHandler`) operates on **Cloudflare** headers only. It does not replace or conflict with FLARE/FLIP file-level metadata.

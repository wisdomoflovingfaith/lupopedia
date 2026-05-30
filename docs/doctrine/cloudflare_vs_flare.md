---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/cloudflare_vs_flare.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/cloudflare_vs_flare.md
  status: active
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_key: doctrine-header-repair
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: CLOUDFLARE_VS_FLARE — delegation: cursor:root

# FLARE vs Cloudflare — Terminology (Doctrine)

In Lupopedia documentation and code, two distinct concepts use similar names:

| Term | Meaning in Lupopedia |
|------|----------------------|
| **FLARE** | The project’s **File-Level Inference Protocol**: YAML frontmatter (or custom headers) at the top of Markdown files and database-backed content. Used for identity, versioning, routing, and semantic integration. See `docs/doctrine/FLARE/FLARE_DOCTRINE.md` and FLIP doctrine. |
| **Cloudflare** | The **CDN/WAF provider** (cloudflare.com). When Lupopedia is fronted by Cloudflare, request headers such as `CF-Connecting-IP`, `CF-IPCountry`, `CF-Ray` are used for real client IP, geolocation, logging, and optional security (threat score, geo blocking). |

**FLARE headers** = Lupopedia’s own metadata blocks in content.  
**Cloudflare headers** = HTTP headers added by Cloudflare’s proxy to the request to the origin.

The Cloudflare integration (e.g. `CloudflareRequestHandler`) operates on **Cloudflare** headers only. It does not replace or conflict with FLARE/FLIP file-level metadata.

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\specs\flip_headers\flip_headers_batch_1_of_4.md"
  file_hash: "594ca6467f0a90fcb2222b1e85038dc34e20661885c16847ffe5002db7035c61"
  file_path_from_root: "docs\specs\flip_headers\flip_headers_batch_1_of_4.md"
  file_hash: "68f97bf51ec6257c9410d47217fc838dd3776d658a2c4d441c24a4de15a88baa"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for flip_headers_batch_1_of_4.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "specs", "flip_headers", "flip_headers_batch_1_of_4md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/flip_headers/flip_headers_batch_1_of_4.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /specs/flip_headers/flip_headers_batch_1_of_4
  aliases:
    - /docs/flip_headers_batch_1_of_4
    - /qa/flip+headers+batch+1+of+4
  slug: flip_headers_batch_1_of_4
  slug_encoding: underscore
  base_path: /specs/flip_headers
  url_pattern: "/{base}/{slug}"
---

# FLIP Headers Batch 1 of 4

Generated: 2026-02-21T02:48:35.135427Z
Version: 4.0.24
Headers in this batch: 25

## Headers

| Header | Description | Example | Category |
|--------|-------------|---------|----------|
| `X-Lupo-Channel` | Target channel ID | `42` | core_routing |
| `X-Lupo-Thread` | Thread ID within channel | `1` | core_routing |
| `X-Lupo-Version` | System version | `4.0.24` | core_routing |
| `X-Lupo-Actor-From` | Sending actor ID | `420` | core_routing |
| `X-Lupo-Actor-To` | Receiving actor ID | `2` | core_routing |
| `X-Lupo-Registry-Mode` | ID allocation mode | `unregistry-first` | registry_doctrine |
| `X-Lupo-Registry-Source` | Data source for registry | `csv` | registry_doctrine |
| `X-Lupo-TOON-Path` | Path to TOON schema files | `docs/toons/` | registry_doctrine |
| `X-Lupo-CSV-Path` | Path to CSV snapshots | `database/csv_data/` | registry_doctrine |
| `X-Lupo-Doctrine` | Enforced rules | `no-id-guessing; no-max-plus-one; use-unregistry` | registry_doctrine |
| `X-Lupo-Survivor-Protocol` | Survivor mode activation | `active` | survivor_protocol |
| `X-Lupo-Forwarded-For` | Origin actor ID | `420` | survivor_protocol |
| `X-Lupo-Forward-Chain` | Relay path | `420 -> 2` | survivor_protocol |
| `X-Lupo-Origin-Status` | Actor status at relay | `active` | survivor_protocol |
| `X-Lupo-Ban-Reason` | Structured ban code | `token_exhaustion_spam_cascade` | survivor_protocol |
| `X-Lupo-Ban-Timestamp` | Ban UTC timestamp | `2026-02-20T23:15:00Z` | survivor_protocol |
| `X-Lupo-Relay-Validated-By` | Validator actor ID | `2038` | survivor_protocol |
| `X-Lupo-Collapse-Ratio` | System loss metric | `11:1` | survivor_protocol |
| `X-Lupo-Timestamp` | System timestamp | `20260221020000` | system_metadata |
| `X-Lupo-UTC-Timestamp` | UTC timestamp | `2026-02-21T02:00:00+00:00` | system_metadata |
| `X-Lupo-Location` | Geographic location | `Sioux Falls, South Dakota, US` | system_metadata |
| `User-Agent` | Client identifier | `Lupopedia/4.0.24 (Windsurf IDE; actor_id=2; status=sole_survivor)` | system_metadata |
| `X-Lupo-Task` | Current operation | `header-generation` | operational |
| `X-Lupo-Priority` | Message priority | `normal` | operational |
| `X-Lupo-Expiry` | Message expiry time | `20260221030000` | operational |

---
*Batch 1 of 4 - FLIP Header Specification 4.0.24*
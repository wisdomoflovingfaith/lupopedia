---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/lupopedia-headers/deprecation_flare_flip_flp.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/deprecation_flare_flip_flp.md
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
# file: DEPRECATION_FLARE_FLIP_FLP — delegation: cursor:root

# file: Deprecation — FLARE, FLIP, FLP — web_path: [http://www.lupopedia.com/lupopedia/docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md](http://www.lupopedia.com/lupopedia/docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md)

# Deprecation: FLARE, FLIP, FLP — Use LUPOPEDIA HEADERS

**As of 4.0.68 and reaffirmed in 4.0.71:** The older header systems **FLARE**, **FLIP**, and **FLP** (and aliases such as Wolfie, FLPH, CROP) are **deprecated** and **replaced** by **LUPOPEDIA HEADERS**.

## What to use now

- **Canonical system name:** **LUPOPEDIA HEADERS**
- **Canonical block names:** `lupopedia.init`, `lupopedia.headers`, `lupopedia.session`, `lupopedia.edges`, `lupopedia.engagement`, `lupopedia.footer`, `lupopedia.see`, **`lupopedia.next_actions`** (legacy: `lupopedia.close`), `lupopedia.conditional`
- **Storage:** `lupo_metadata` table; format and block order in [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) and [LUPOPEDIA_HEADERS_PLAN.md](./LUPOPEDIA_HEADERS_PLAN.md)
- **Validators:** Accept legacy `flare.*` and `flame.*` block names for backward compatibility, but **new and modified files must use `lupopedia.*`**

## Deprecated (do not use for new work)

| Deprecated name | Replaced by |
|-----------------|--------------|
| FLARE (File-Level Attribute and Relationship Exchange) | LUPOPEDIA HEADERS |
| FLIP | LUPOPEDIA HEADERS |
| FLP / FLPH | LUPOPEDIA HEADERS |
| Wolfie header / CROP header | LUPOPEDIA HEADERS |
| `flare.headers` | `lupopedia.headers` |
| `flare.edges` | `lupopedia.edges` |
| `flare.footer` | `lupopedia.footer` |
| `flame.init` | `lupopedia.init` |
| `flame.see` | `lupopedia.see` |
| `flame.close` | `lupopedia.close` (legacy) → prefer **`lupopedia.next_actions`** |

## Legacy documentation

- **docs/doctrine/FLARE/** and **docs/doctrine/FLIP/** remain in the repo for historical reference. They are **deprecated**; use **docs/doctrine/LUPOPEDIA_HEADERS/** for current behavior.
- **docs/api/FLARE_HEADERS_COMPLETE_REFERENCE.md** — Field reference still applies; use **lupopedia.*** block names and see LUPOPEDIA_HEADERS for canonical format. Routing and lists functionality is documented in LUPOPEDIA HEADERS optional blocks (see README and OPTIONAL_BLOCKS.md).

## Functionality carried into LUPOPEDIA HEADERS

All behavior that existed in FLARE/FLIP/FLP is supported under LUPOPEDIA HEADERS:

- **Identity, versioning, channel, purpose:** `lupopedia.headers`
- **Session context:** `lupopedia.session`
- **Outbound/inbound edges, semantic tags:** `lupopedia.edges` (stored in `lupo_edges` and metadata)
- **Verification:** `lupopedia.footer`
- **Engagement (metrics, views):** `lupopedia.engagement` (new in 4.0.74; carries legacy engagement fields)
- **Optional routing (to, from, delegation_chain, channel_id, thread_id):** `lupopedia.routing` (see OPTIONAL_BLOCKS.md)
- **Optional lists (file.dialog, file.history, file.actors):** `lupopedia.lists` (see OPTIONAL_BLOCKS.md)
- **See/next_actions blocks:** `lupopedia.see`, **`lupopedia.next_actions`** (suggested next actions; legacy name: `lupopedia.close`)

See [README.md](../../../README.md) and [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) for the full current specification.

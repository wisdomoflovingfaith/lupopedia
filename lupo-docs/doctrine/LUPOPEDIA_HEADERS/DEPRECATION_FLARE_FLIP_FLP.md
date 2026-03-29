---
lupopedia.init:
  document_type: "doctrine"

lupopedia.headers:
  when_updated: "20260327121457"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md"
  last_modified_utc: "20260327121457"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Deprecation notice: FLARE, FLIP, FLP are deprecated and replaced by LUPOPEDIA HEADERS."
  tags: ["deprecation", "flare", "flip", "flp", "lupopedia_headers"]

lupopedia.footer:
  last_verified: "20260327121457"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Do not add new references to FLARE/FLIP/FLP in new docs"
    - "Point legacy doc readers to LUPOPEDIA_HEADERS and OPTIONAL_BLOCKS"
    - "Keep deprecation table and mapping current"
---
# file: Deprecation — FLARE, FLIP, FLP — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md)

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

- **lupo-docs/doctrine/FLARE/** and **lupo-docs/doctrine/FLIP/** remain in the repo for historical reference. They are **deprecated**; use **lupo-docs/doctrine/LUPOPEDIA_HEADERS/** for current behavior.
- **lupo-docs/api/FLARE_HEADERS_COMPLETE_REFERENCE.md** — Field reference still applies; use **lupopedia.*** block names and see LUPOPEDIA_HEADERS for canonical format. Routing and lists functionality is documented in LUPOPEDIA HEADERS optional blocks (see README and OPTIONAL_BLOCKS.md).

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

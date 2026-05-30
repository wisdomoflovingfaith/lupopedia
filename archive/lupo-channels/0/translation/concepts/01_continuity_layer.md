---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-channels/0/translation/concepts/01_continuity_layer.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-channels/0/translation/concepts/01_continuity_layer.md"
  status: "active"
  when_updated: "20260416215839"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "translation"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Translation: Continuity Layer"
  summary: "Translation artifact for the Continuity Layer concept."
---
# Concept: Continuity Layer / Degraded Mode

## Canonical doctrine (must match)

Normative wording lives in `lupo-docs/doctrine/CONTINUITY_LAYER_DOCTRINE.md`. This translation file must not contradict it.

## Internal Technical Wording (Layer 3)
The live **database** remains the **system of record** for normal operation. Separately, **continuity artifacts** exist: exported structure snapshots, persisted content, memory or graph exports, and append-only logs (for example `lupo-channels/{channel_id}/transcript.jsonl` from channel post handling). Together they support **limited continuity** or **degraded mode**: bounded reads, explanations, or operator surfaces—not a parallel authoritative “file database” and not a claim that every feature runs forever without SQL.

## Conceptual Model (Layer 2)
Think of an airplane checklist and printed emergency procedures. The cockpit instruments (the database) are primary. If they glitch, the crew still has verified paper procedures (exports and artifacts) so limited operations can continue with clear limits until instruments are trustworthy again.

## External Short Wording (Layer 1)
If systems hiccup, we can still show vetted information from our prepared continuity materials instead of going completely dark—without pretending files replace the database.

## Business Wording
We invest in **database-backed operations** plus a **continuity layer** so customer-facing and operator-facing surfaces can degrade gracefully within defined boundaries. Downtime risk is reduced for the surfaces we explicitly cover; scope is honest, not “everything forever on static files.”

## User-Guide Wording
During an incident you may see a simpler or read-only view backed by our prepared continuity materials while we restore full database service.

## Developer Wording
Treat filesystem artifacts as **continuity evidence and staging**, not silent replacements for `PDO_DB`. Hermes-style hooks may append transcripts and staging memory under documented paths; promotion to trusted reference remains explicit. See `lupo-docs/prd/38_memory_unification.md`.

## Example Analogy
It is closer to “legal pad procedures when instruments flicker” than “we moved the bank vault into a notebook.”

## Production precedent (Captain narrative)

Honolulu **DMV** continuity work in **1999** (solo developer, production pressure): disciplined reference exports and structured content so operations could continue during database instability. Narrative anchor: `lupo-content/federation_node/0/captains_log/20260416_dmv_1999_continuity_precedent.md`.

## Common Misunderstanding
"The database falls back so files become the full database."
*Correction*: **Limited continuity mode** only. Files do not silently become the authoritative system of record.

## Wording to Avoid
* "Fallback to old code"
* "We use text files because databases are hard"
* "Flat-file storage replaces the database" (inaccurate for Lupopedia executive messaging)

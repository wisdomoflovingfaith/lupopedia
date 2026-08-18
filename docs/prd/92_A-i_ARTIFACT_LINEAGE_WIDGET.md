---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/92_A-i_ARTIFACT_LINEAGE_WIDGET.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/92_A-i_ARTIFACT_LINEAGE_WIDGET.md
  status: development
  when_updated: '20260817100200'
  trust_tier: development
  questions_toon: null
  memory_toon: memory/development/development/1026/08/92_a_artifact_lineage_widget.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd-92-artifact-lineage-widget
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 92_A_90_A_28_A_21_A_04_A_11_A_73_A_01_B
  title: 'PRD 92: Artifact Lineage Widget (generalized artifact lineage)'
  summary: 'A generalized artifact lineage embed widget for creative works. First implementation surface is CC-BY music, but the widget applies to ANY artifact: audio, video, documents, images, term papers, code, and other creative works. Licensed artifacts; CC-BY is the first supported license. Not The Eye. Not the semantic navbar. No install SQL from this PRD. HEX6 is never guessed. Color is not a LUP KEY token.'
---
# PRD 92: Artifact Lineage Widget (generalized artifact lineage)

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Why this PRD exists

PRD 21, PRD 28, PRD 04, PRD 73, and PRD 50 define **webpage-based** lineage and monitoring:

- The Eye (PRD 28) is a bottom-right bar on webpages.
- The Semantic Navbar (PRD 21) is a webpage collection/tab chrome.
- `lupopedia_js.php` / `livehelp_js.php` are webpage embeds.

This PRD defines **artifact-based lineage**. First surface is **music** (tracks, remixes, samples). The widget applies to **licensed artifacts**. CC-BY is the first supported license. Remix is a universal verb: documents, videos, term papers, blog posts, essays, images, code snippets, research, and any other creative artifact use the same actions.

Different environment, different verbs, different metadata, different analytics. It MUST have its own constitution. It MUST NOT be folded into The Eye.

**PRD number:** 92 (block 90-97). **Not** 82_A. Group 82 is HERMES and MUST NOT be reused.

This PRD adds **no install SQL**. Lineage storage planning remains with PRD 01_B / future artifact tables. Until storage exists, the widget MUST show empty or pending fields. HEX6 MUST NOT be guessed. Color is not a LUP KEY token.

## 1. Purpose

A lineage widget for licensed artifacts that shows:

- who the work is attributed to
- parent artifact and remix / sample / derivation chain
- child derivatives
- color identity and collection (album / project / set)
- engagement (like, share, remix count) without inventing numbers

First implementation surface is CC-BY music. The widget is not a CC-BY-only music widget. It is the Artifact Lineage Engine embed.

The widget is an **embed**, not a webpage footer clone of The Eye.

## 2. What this is not

| Existing surface | Role | This widget |
|------------------|------|-------------|
| The Eye (PRD 28) | Webpage monitoring + page lineage | Separate. MUST NOT load The Eye chrome in an artifact embed. |
| Semantic Navbar (PRD 21) | Webpage Collection Selector + green content tabs | MUST NOT appear inside artifact embeds. |
| `livehelp_js.php` (PRD 33 / 50) | Crafty live-help chat embed | MUST remain intact. This widget MUST NOT replace it. |
| Color Registry homepage | Cross-domain **page** parent/child URLs | Page lineage stays there. Artifact lineage uses this widget. |

## 3. Actions

Webpage lineage verbs (Declare Child Page, Edit Page, Copy Page) do **not** apply here.

| Action | Meaning |
|--------|---------|
| Remix | Declare a child artifact derived from this work. |
| Link | Copy a stable artifact URL / embed URL. |
| Copy | Copy attribution text (license-required notice; CC-BY first). |
| Like | Engagement signal (PRD 11 `artifact_engagement`). |
| Attribution | Show author, title, source, license URI for the declared license. |
| View lineage tree | Parent, remix chain, sample / source lineage, children. |

## 4. Metadata

Webpage lineage uses parent URL, child URLs, color group, collection name.

Artifact lineage uses:

```text
artifact.artifact_id          (OS id when known; else empty)
artifact.title
artifact.kind                 (track | video | audio | document | image | code | other)
artifact.media_url            (playable or downloadable URL when known)
parent.artifact_id
parent.title
parent.media_url
children[]                    (remixes / derivatives)
remix_chain[]                 (ordered ancestors, parent-first)
sample_lineage[]              (sampled or sourced works, if declared)
attribution.license           (cc-by first; later cc-by-sa, cc-by-nc, cc-by-nd, gpl, mit, apache, proprietary, custom)
attribution.author
attribution.title
attribution.source_url
attribution.license_uri
color.group_color
color.color_name
color.collection_name         (album / project / set Collection)
color.hex6                    (six digits or empty; never guessed)
color.handshake
engagement.remix_count        (known count or empty; never invented)
engagement.like_count
engagement.share_count
```

**License display** is attribution UI. This PRD is not legal advice. Do not invent license terms. CC-BY is the first supported license, not the only license the widget may later display.

**Collections:** `collection_name` is the album, project, or set. Same Color Group + Collection unification as PRD 21 / PRD 28 / PRD 73, applied to artifacts instead of webpage tabs.

## 5. Embed rules

Public entry (install root, Crafty-style filename stability):

- **`artifact_lineage_js.php`** -- artifact widget script / overlay
- Optional: **`lupopedia_js.php?mode=artifact`** MAY return the same payload (PRD 04) without loading The Eye UI

Allowed hosts:

- iframe embed around a player or document viewer
- JS overlay on an existing audio/video player or artifact page
- player / viewer integration on the same origin as the Lupopedia install
- mobile: touch-first controls; no mouse-only hover as the only path (see mobile separation doctrine)

Forbidden:

- npm / Composer / Docker as a requirement
- loading PRD 21 navbar inside the artifact embed
- loading The Eye bottom bar inside the artifact embed
- guessing HEX6 or remix counts
- replacing `livehelp_js.php`

Shared hosting: query-parameter fallbacks required (no mod_rewrite dependency), same as PRD 28 API dual routing.

## 6. Analytics

Webpage events stay in PRD 11 (`color_identity_viewed`, `lineage_viewed`, `child_page_created`, `parent_page_referenced`, `collection_selected`).

This widget adds:

| Event key | When |
|-----------|------|
| `remix_created` | A remix / child artifact is declared. |
| `remix_viewed` | Remix chain or a child remix is shown. |
| `attribution_viewed` | Attribution panel is shown. |
| `artifact_engagement` | Like, share, or other engagement (type in payload). |
| `artifact_lineage_viewed` | Lineage tree for an artifact is opened. |

Do not invent counts. Until storage exists, log to existing audit/unified log JSON (PRD 11 posture). Packed timestamps: `gmdate('YmdHis')`.

## 7. Federation

- Artifacts are treated like pages for **lineage identity**: parent, child, color group, collection, handshake.
- Lineage SHOULD use the same lineage tables / model as page lineage when those tables exist. This PRD does not add DDL.
- Color groups apply to songs and other artifacts (PRD 90).
- Collections apply to albums, projects, and sets (PRD 73).
- Cross-node: same federation trust posture as other public embeds (PRD 21 / PRD 34). Untrusted origins MUST NOT receive private artifact payloads.

## 8. Coexistence

- MUST NOT break Crafty tracking (`livehelp_js.php`, `image.php`, visit_track / path rollup).
- MUST NOT break webpage lineage (Color Registry homepage, The Eye page actions).
- MUST NOT break JS foundation: `lupopedia_js.php` remains The Eye loader; it MAY also emit artifact payloads when `mode=artifact` without switching the page into player chrome.

## 9. Implementation posture

- PHP 7.4+, PDO named placeholders, `IdGenerator::generate()` when rows exist, `is_deleted = 0`.
- No foreign keys, no triggers, no guessed columns.
- ASCII in this PRD.
- Music is the first artifact kind and CC-BY is the first license. Documents, images, code, video, and other licensed artifacts use the same actions and metadata shape.

## 10. Cross-references

- PRD 28 -- The Eye (webpage). Artifact widgets are separate.
- PRD 21 -- Semantic navbar MUST NOT appear in artifact embeds.
- PRD 04 -- JS foundation MUST support artifact lineage payloads.
- PRD 11 -- remix / attribution / artifact engagement events.
- PRD 73 -- Collections MAY be albums or creative projects.
- PRD 90 -- Color identity. HEX6 never guessed.
- PRD 01_B -- color registry tables (planning).
- PRD 33 / PRD 50 -- Crafty embed coexistence.
- PRD 84 -- 92 is a new group; 82 remains HERMES.

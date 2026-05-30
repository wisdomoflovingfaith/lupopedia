---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/doctrine/EMOJI_AND_SMILIES.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/EMOJI_AND_SMILIES.md"
  status: "active"
  when_updated: "20260418192221"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/emoji-and-smilies.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/emoji-and-smilies"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "emoji-and-smilies"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "Emoji and Smilies (Filesystem Canonical)"
  summary: "Canonical Lupopedia filesystem emoji: ::img|folder|file:: tokens, lupo-emoji/ storage, render-time replacement only; Crafty DB smilies deprecated; validation, encoding safety, chat storage rules."
---
# Emoji and Smilies (Filesystem Canonical)

## Canonical system (normative)

The **only** canonical emoji/smilies system for **Lupopedia** is:

| Aspect | Rule |
|--------|------|
| **Storage** | **Filesystem** under **`lupo-emoji/`** (subfolders + image files). |
| **Inline token** | **`::img|foldername|filename::`** (deterministic, ASCII-safe delimiter form). |
| **Rendering** | Replace tokens **at display time** only; never persist rendered HTML for emoji in storage layers. |
| **Not canonical** | **Database-driven** emoji registries (legacy **`lupo_smilies`** model) — see **[SMILIES_IMPLEMENTATION.md](SMILIES_IMPLEMENTATION.md)** (deprecated historical record only). |

There is **no** ambiguity: **filesystem + token** is authoritative; **DB smilies** are **not** reintroduced as an active system.

## Overview

Channels are dialog-based; operators and visitors insert emoji by **token** or UI selector that emits the token. The runtime resolves tokens against **`lupo-emoji/`** when building **view** output.

## Emoji token format

```
::img|foldername|filename::
```

- **`foldername`**: subdirectory under **`lupo-emoji/`** (see **Validation Rules**).
- **`filename`**: image file name only (e.g. `smile.png`) — **no** path segments (see **Validation Rules**).

**Example:**

```
Hello! ::img|classic|smile.png::
```

Resolves at render time to an image sourced from **`lupo-emoji/classic/smile.png`** (subject to base path and extension rules below).

## Emoji selector popup

- UI may offer a picker that lists images discovered under **`lupo-emoji/`** (by folder).
- Selecting an entry **inserts** the **`::img|foldername|filename::`** token into the compose buffer (not an `<img>` tag).

## Crafty Syntax transition

Crafty Syntax **used** a **database-driven smilies** system (historically described around a **`lupo_smilies`** table and code aliases such as `:smile:`). **Lupopedia replaces** that with this **filesystem-based emoji** system: **deterministic inline tokens** and **render-time** replacement only.

Historical detail (non-normative for new work): **[SMILIES_IMPLEMENTATION.md](SMILIES_IMPLEMENTATION.md)** (status: **deprecated**).

## Validation rules

Before accepting a token for resolution (or before writing derived paths):

| Rule | Requirement |
|------|-------------|
| **`foldername`** | **ASCII alphanumeric**, plus **`_`** or **`-`** only; reject empty; normalize to lowercase if product policy requires. |
| **`filename`** | **No** path traversal (`..`); **no** `/` or `\\`; basename only. |
| **Extension** | Allow only **`png`**, **`gif`**, **`webp`** (case-insensitive suffix check after basename validation). |
| **Base path** | Resolved file **must** stay under **`lupo-emoji/`**; reject absolute paths, drive letters, or `..` segments. |

## Rendering rules

- **Replace tokens ONLY at render time** (template/view layer or equivalent), after loading stored message text.
- **Never** store literal **`<img>`** tags in **`lupo_dialog_messages`** (or other canonical storage) **for emoji**; storage holds **tokens** (or plain text), not tag soup.
- **Always** escape attributes when building markup (width/height/alt/src from trusted resolver output only).
- **Enforce base path** **`lupo-emoji/`** relative to install layout; do not honor arbitrary filesystem roots from token content.

## Encoding safety

- Tokens are **ASCII-framed** (`:` and `|` delimiters), which avoids **Unicode corruption** in transport and in many JSON/JSONL payloads compared to raw emoji glyphs in protocol strings.
- Aligns with **UTF-8 structured write** enforcement: serialized rows remain **valid UTF-8**; emoji **may** still appear as real Unicode in user text, but the **token path** remains migration-friendly and grep-stable.

## Chat storage rule

- **Store** the **original composed text** including **`::img|...::`** tokens.
- **Do NOT** pre-render emoji to HTML **before** insert into **`lupo_dialog_messages`** (or equivalent).
- **Rendering** is a **view-layer** concern: same stored line may render differently if skin or CSP changes, without rewriting history.

## Anti-patterns

- **Do NOT** store **`<img>`** tags in **`lupo_dialog_messages`** (or JSON columns) **for emoji** — use tokens + render-time expansion.
- **Do NOT** reintroduce a **database-driven emoji** system (no new **`lupo_smilies`**-style registry as the source of truth).
- **Do NOT** allow **arbitrary file paths** inside tokens — only validated **`foldername`** + **`filename`** under **`lupo-emoji/`** with allowed extensions.

## Example directory structure

```
lupo-emoji/
  classic/
    smile.png
    wink.png
  animals/
    cat.png
    dog.png
```

## Benefits

- No database dependency for emoji asset catalogs.
- Operators can add/remove sets by changing files under **`lupo-emoji/`**.
- Supported image types are bounded (**png**, **gif**, **webp**) for predictable rendering and security review.

---

For implementation wiring (chat UI, dialog module), follow this doctrine together with channel PRD **[PRD 02](../prd/02_channels_discussions.md)** and UTF-8 write guards on structured exports.

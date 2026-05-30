# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/CHANNEL_CONTENT_STORAGE_DOCTRINE.md"
  file_hash: "2b1a626f64e49cc1614ea4aac7e1892240f37b6a2eda3b6adb6b930a2e5b4ee7"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\channels\doctrine\CHANNEL_CONTENT_STORAGE_DOCTRINE.md"
  file_hash: "36913335c6f246af92667f22622a0346a3eaa8ded9ac60ab97fa5f04933bd52b"
  file_path_from_root: "docs\channels\doctrine\CHANNEL_CONTENT_STORAGE_DOCTRINE.md"
  file_hash: "ab7da32bcafa50ae094ef5b8405956fa286d79edb7f2c7ddec5c1b6002fd5396"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CHANNEL_CONTENT_STORAGE_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "channel_content_storage_doctrinemd"]
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
file.last_modified_system_version: 3.0.0
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Added channel content storage doctrine defining canonical storage, edge relationships, and filesystem mirroring rules."
tags:
  categories: ["documentation", "doctrine", "channels"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Channel Content Storage Doctrine"
  description: "Defines where channel content lives, how it is stored, and how filesystem mirrors are governed."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
in_this_file_we_have:
  - Canonical Storage Model
  - Filesystem as Mirror, Not Source
  - Doctrine Rules
  - Summary
---

No filesystem confusion.
No legacy assumptions.
Just the truth of how channels work in the semantic OS.

# CHANNEL CONTENT STORAGE DOCTRINE

Lupopedia Semantic OS - Channel Content Storage Doctrine
System Version: 3.0.0
file.channel: doctrine

## Purpose

This doctrine defines where channel content lives, how it is stored, how it is related, and how it is mirrored.
It eliminates the legacy assumption that Markdown files are the primary source of truth and establishes the database + edges + contents model as canonical.

Channels are semantic objects, not folders.
Their artifacts must live in the semantic graph, not in the filesystem.

## 1. Canonical Storage Model

### 1.1 Channels are database-native entities

A channel is defined by:

- identity
- purpose
- routing
- doctrine
- emotional metadata
- edges to content
- edges to threads
- edges to actors

The channel's truth lives in the database, not in the filesystem.

### 1.2 Content lives in lupo_contents

All channel artifacts - including:

- doctrine blocks
- onboarding notes
- sprint logs
- migration notes
- emotional geometry
- structured metadata
- Markdown text
- JSON payloads
- TOON fragments

- are stored as content rows in lupo_contents.

This is the canonical home of channel content.

### 1.3 Relationships live in lupo_edges

Edges define the semantic graph:

- channel -> content
- channel -> thread
- channel -> actor
- channel -> doctrine block
- channel -> emotional geometry
- channel -> migration logs
- channel -> artifacts

Edges are first-class citizens.
The graph is the OS.

### 1.4 Activity lives in lupo_dialog_threads

Threads represent:

- operator-visible work
- sprint activity
- logs
- conversations
- emotional state transitions

Threads own:

- bg_color
- text_color
- alt_text_color
- emotional metadata

Channels do not own colors.
Threads do.

## 2. Filesystem as Mirror, Not Source

### 2.1 /docs/channels/ is human-facing documentation

This directory contains:

- doctrine
- onboarding guides
- conceptual maps
- developer notes

These files are not the canonical source.
They are human-readable mirrors of database content.

### 2.2 /channels/<id>/ is a JSON snapshot

This directory contains:

- JSON exports of channel metadata
- JSON exports of edges
- JSON exports of content
- JSON exports of threads

This is a backup and versioning mirror, not the source of truth.

### 2.3 Filesystem mirrors must never drift

Mirrors must be:

- regenerated
- consistent
- doctrine-aligned
- schema-aligned

If the filesystem and database disagree, the database wins.

## 3. Doctrine Rules

### 3.1 Channels must not store content in the filesystem

No channel may rely on:

- Markdown files
- text files
- ad-hoc folders
- developer-created artifacts

- as its primary storage.

All content must be stored in lupo_contents.

### 3.2 All channel artifacts must be edge-addressable

Every artifact must be reachable through:

Code
channel -> edge -> content

This is the semantic OS contract.

### 3.3 Doctrine blocks must be stored in the database

Even doctrine files stored in /docs/channels/doctrine/ must have a corresponding:

- lupo_contents row
- lupo_edges relationship

The filesystem copy is a mirror, not the source.

### 3.4 Threads own visual and emotional metadata

Channels may not contain:

- bg_color
- text_color
- alt_text_color

These belong to threads.

### 3.5 Channels are conceptual, not structural

Channels do not:

- own directories
- own files
- own UI themes
- own layouts

Channels own meaning, not storage.

## 4. Summary

This doctrine establishes the canonical truth:

- Channels are semantic objects
- Content lives in the database
- Relationships live in edges
- Activity lives in threads
- Filesystem is a mirror, not the source
- Doctrine must be stored in the graph
- Threads own colors
- Channels own meaning

This is the Lupopedia way.

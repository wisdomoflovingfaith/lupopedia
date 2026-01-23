---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Ingestion Doctrine documentation defining the three semantic radii, Crafty Syntax global import and Q&A import doctrine, semantic inheritance, metadata handling, privacy boundaries, and ingestion pipeline doctrine."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "ingestion", "semantic"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Ingestion & Semantic Radius Doctrine Overview
  - Radius 0 (Local Filesystem Ingestion)
  - Radius 1 (Internal Site URL Ingestion)
  - Radius 2 (Trusted External Public URL Ingestion)
  - Crafty Syntax Global Import Doctrine
  - Crafty Syntax Q&A Import Doctrine
  - Semantic Inheritance Doctrine
  - Semantic Edge Generation
  - Metadata-Only Ingestion Doctrine
  - Federation Doctrine
  - Ingestion Pipeline
  - Privacy & Safety Doctrine
file:
  title: "Ingestion Doctrine"
  description: "Documents the ingestion architecture of Lupopedia, including the three semantic radii, the Crafty Syntax global import dataset, semantic inheritance rules, metadata handling, privacy boundaries, and doctrine governing how content becomes part of the semantic graph."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Ingestion & Semantic Radius Doctrine

This section documents the ingestion architecture of Lupopedia, including the three ingestion radii, the Crafty Syntax global import dataset, semantic inheritance rules, metadata handling, privacy boundaries, and the doctrine governing how content becomes part of the semantic graph.

Ingestion is foundational to Lupopedia's identity as a semantic OS built on digital archaeology and historical truth.

---

## Overview

Lupopedia ingests content from three concentric "semantic radii":

- Radius 0 — Local filesystem ingestion
- Radius 1 — Internal site URL ingestion
- Radius 2 — Trusted external public URL ingestion

Additionally, Lupopedia inherits a complete historical dataset from
Crafty Syntax, including:

- filesystem paths,
- URL structures,
- navigation relationships,
- questions and answers,
- operator workflows.

This historical dataset is treated as a privileged Radius 0/1 source
with perfect lineage.

Ingestion is always:
- opt-in (for new sources),
- metadata-first,
- privacy-respecting,
- robots.txt compliant (for live URLs),
- rate-limited,
- and never accesses private or authenticated content.

---

## Radius 0 — Local Filesystem Ingestion

Radius 0 ingests content directly from the local filesystem.

Sources include:
- /content/
- /docs/
- /modules/<module>/docs/
- local markdown files
- local YAML/JSON metadata files
- imported Crafty Syntax export files

Characteristics:
- Highest trust level.
- Full content ingestion allowed.
- Used for documentation, doctrine, internal knowledge, and imported
  historical data.
- Generates semantic edges based on folder structure and tab placement.

Radius 0 is the foundation of Lupopedia's internal semantic universe.

---

## Radius 1 — Internal Site URL Ingestion

Radius 1 ingests content from the same site domain.

Sources include:
- https://lupopedia.com/*
- internal help pages
- internal module documentation
- internal public-facing content

Characteristics:
- Content must be publicly accessible.
- No authentication allowed.
- No scraping of private areas.
- Must respect robots.txt and rate limits.
- Only metadata and text content are ingested.

Radius 1 expands the semantic graph to include all internal public
content that the site owner has implicitly approved by linking to it.

---

## Radius 2 — Trusted External Public URL Ingestion

Radius 2 ingests content from explicitly trusted external public URLs.

Characteristics:
- Only URLs explicitly linked by the site owner are ingested.
- No crawling beyond the linked URL.
- No following of external links.
- No scraping of private or authenticated content.
- Must respect robots.txt and rate limits.
- Only metadata and text content are ingested.
- No images, scripts, or binary assets are ingested.

Radius 2 allows Lupopedia to federate with the public web in a
controlled, opt-in, privacy-respecting manner.

---

## Crafty Syntax Global Import Doctrine

Lupopedia inherits a complete global map of Crafty Syntax installations
from the legacy import system. This dataset includes, for each
installation:

- every file path,
- every folder name,
- every URL path,
- every navigation structure,
- every help page,
- every operator UI screen,
- every module file,
- every content page,
- every department,
- every parent/child relationship,
- every timestamp,
- every version,
- every installation directory,
- every public-facing URL,
- and every semantic placement implied by the original system.

This dataset covers more than 144,000 historical installations and
provides a complete, lossless map of Crafty Syntax's global footprint.

Because of this:

1. Lupopedia does NOT need to crawl Crafty Syntax sites.
2. Lupopedia does NOT need to infer navigation structure.
3. Lupopedia does NOT need to guess semantic placement.
4. Lupopedia does NOT need to discover file paths.
5. Lupopedia does NOT need to scrape content.

All of this information already exists in the imported tables and is
treated as high-trust Radius 0/1 data with perfect lineage.

---

## Crafty Syntax Q&A Import Doctrine

In addition to paths and files, Lupopedia imports the historical
questions and answers from Crafty Syntax installations.

For each site, the import includes:
- visitor questions,
- operator answers,
- associated departments and categories,
- associated pages or help topics,
- timestamps and context.

These Q&A pairs are critical because they reveal:

- how operators actually used navigation in practice,
- which pages were used to answer specific types of questions,
- which help topics were most relevant to specific intents,
- how departments and categories functioned as semantic tags.

From this, Lupopedia can reconstruct:

- practical navigation flows ("when people asked X, operators went to Y"),
- semantic clusters around topics,
- which content was truly useful in production,
- intent-to-page mappings grounded in real historical usage.

This is not speculative inference; it is historical truth rooted in how
humans actually worked inside Crafty Syntax.

---

## Semantic Inheritance Doctrine

All ingested content inherits semantic meaning from its placement and
relationships.

Sources of inheritance:
- Navigation Tabs in Lupopedia,
- folder and file structure,
- Crafty Syntax department and category,
- Crafty Syntax page relationships,
- question → answer mappings,
- linked URLs.

Rules:
- Tabs define semantic categories in Lupopedia.
- Crafty Syntax departments and categories define historical semantic
  groupings.
- Content placed under a tab or department inherits that meaning.
- Q&A mappings create edges between intents and content.
- Inheritance generates semantic edges in the graph.
- Placement and relationships determine meaning, not arbitrary content
  analysis alone.

All ingested content inherits semantic meaning from its placement under
Navigation Tabs.

Rules:
- Tabs define semantic categories.
- Content placed under a tab inherits that tab's meaning.
- Inheritance generates semantic edges in the graph.
- Edges are directional and typed.
- Placement determines meaning, not content analysis.

Example:
If a page is placed under:
Navigation → Agents → Kernel → DIALOG

Then the content inherits:
- "agent"
- "kernel agent"
- "dialog agent"
- "expressive rendering"

This inheritance is automatic and deterministic.

---

## Semantic Edge Generation

Every ingested item generates semantic edges based on:

1. Tab placement  
2. Folder structure  
3. Metadata fields  
4. Explicit links  
5. Crafty Syntax departments/categories  
6. Question → answer mappings  
7. Radius of origin  

Edges include:
- "is_a"
- "part_of"
- "related_to"
- "inherits_from"
- "answers"
- "asked_about"
- "linked_from"
- "linked_to"

These edges are stored in the semantic graph and used for:
- search,
- discovery,
- navigation,
- federation,
- agent reasoning,
- historical analysis.

---

## Metadata-Only Ingestion Doctrine

Lupopedia ingests:
- text content,
- metadata,
- structured fields,
- semantic placement,
- link relationships,
- historical Q&A mappings.

Lupopedia does NOT ingest:
- private data,
- authenticated content,
- user-specific secrets,
- binary assets,
- scripts,
- stylesheets,
- tracking pixels,
- analytics code.

Ingestion is always safe, minimal, and privacy-respecting.

---

## Federation Doctrine

Radius 2 ingestion enables federation with the public web.

Federation rules:
- Only trusted external URLs explicitly linked by the site owner are ingested.
- No crawling beyond the linked URL.
- No scraping of private or authenticated content.
- No inference of trust from domain alone.
- Trust is explicit, not implicit.

Federation expands the semantic graph without compromising privacy or safety.

---

## Ingestion Pipeline

The ingestion pipeline follows these steps:

1. Identify source radius (0, 1, or 2) or Crafty Syntax import.
2. Validate URL or file path.
3. For live URLs (Radius 1 and 2), check robots.txt.
4. Fetch content (text only).
5. Extract metadata (including Q&A where applicable).
6. Determine tab placement and/or historical department/category.
7. Generate semantic edges (including Q&A intent → content edges).
8. Store content and metadata.
9. Update the semantic graph.

The pipeline is deterministic and idempotent.

---

## Privacy & Safety Doctrine

Ingestion must never:
- access private content,
- bypass authentication,
- ignore robots.txt,
- store user-specific secrets,
- ingest anything not explicitly provided or linked.

Ingestion must always:
- respect privacy,
- respect rate limits,
- ingest only public or explicitly imported content,
- ingest only metadata and text,
- operate transparently.

---

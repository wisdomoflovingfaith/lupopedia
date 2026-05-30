---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/PERSONAL_CONTEXT_ISOLATION.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/PERSONAL_CONTEXT_ISOLATION.md"
  status: "active"
  when_updated: "20260418234714"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/personal-context-isolation.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/personal-context-isolation-doctrine"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "personal-context-isolation"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Personal context isolation (system artifacts)"
  summary: "Hard boundary: system artifacts must not store operator biography, trauma, inferred personal detail, emotional summaries, or speculative human models; lists artifact classes, rationale, incremental enforcement."
---
# Personal context isolation (system artifacts)

## 1. Definition

### 1.1 System artifacts

**System artifacts** are any durable, repository- or product-maintained records intended to define, operate, or audit Lupopedia. Non-exhaustive examples:

- Files under **`agents/`**, **`docs/`** (including PRDs and doctrine), **`memory/`**, **`scripts/`**, **`changelog-pending/`**, **`channels/`** when used for coordination artifacts, decisions, and work products (not personal narrative storage), **`rules/`**, **`includes/`**, **`app/`** (when committed as product source)
- **Structured payloads** shipped with the product: JSON, YAML, TOON, CSV used as spec or config, **Lupopedia Headers** blocks in tracked files, generated indexes where they encode normative text
- **Agent prompts**, **IDE rule packs**, **implementation mirrors** under **`docs/implementations/`**, and similar maintainer-authored surfaces

Runtime database **rows** are not "files" but are still **system state** when they store product configuration, channel content, or audit fields governed by schema and PRDs. This doctrine addresses **what must not be written into those durable stores** when the write purpose is **personal narrative about a human operator** rather than **system fact**.

### 1.2 Personal context

**Personal context** is information about a **specific human operator** (or identifiable natural person) that is **not required** to implement, configure, or audit Lupopedia. Non-exhaustive examples:

- **Biography** and life history unrelated to tasks, commits, or channel work products
- **Trauma or medical narrative**, crisis detail, or similarly sensitive personal history
- **Emotional state summaries** or evaluative judgments about the operator ("felt", "was upset", and similar **non-technical** characterizations stored as facts)
- **Inferred personal detail** (home situation, relationships, finances) derived from chat or tooling
- **Speculative modeling** of the operator (psychological profiles, motivation narratives) used as if they were configuration
- **Location or network identity** beyond what **security doctrine** already permits for binding sessions (for example: do not store ad-hoc "operator geo story" strings in PRDs or memory graphs)

**Not in scope:** Public **actor** / **facet** personas, **registry-backed** identities, **channel-attributed** technical posts, **auth_user** rows with **policy-allowed** fields, and **ROSE** / dialog product behavior where **[PRD 36](docs/prd/36_rose_multi_persona_synthetic_dialog.md)** explicitly defines synthetic dialog storage.

## 2. Strict rule

System artifacts may contain **only** data that is **system-relevant**:

- **Actors, departments, channels, facets** as defined by registry, PRDs, and install/seed doctrine
- **Project doctrine** and **normative specifications** (PRDs, tables, validators)
- **Architecture and implementation** detail (code, schema, deployment constraints)
- **Workflow and coordination** rules (channels, tasks, handoffs, violation codes)

**Personal context is forbidden** in all system artifact classes listed in **section 1.1**, including **PRDs**, **doctrine Markdown**, **memory TOON/JSON** used as canonical knowledge, **agent prompts**, **changelog buffer JSON**, and **header fields** when those fields are used to carry free-form narrative.

### 2.1 Non-system storage clarification

Personal context is not universally forbidden. It may exist in:

- **External tools or chats** that are not persisted as system artifacts (section 1.1).
- **ROSE** and **dialog** systems only where **[PRD 36](docs/prd/36_rose_multi_persona_synthetic_dialog.md)** explicitly defines storage and retention.
- **User-facing runtime conversations** where content is transient by design and is **not** promoted to canonical memory, PRDs, or doctrine.
- **Explicitly scoped datasets** introduced only if a PRD defines purpose, fields, and lifecycle for that store.

Such content must **not** be:

- Elevated into **PRDs** as normative requirements.
- Stored in **doctrine** as explanatory or binding text.
- Written into **memory TOON**, **memory JSON**, or other **canonical knowledge** artifacts.
- Personal context must not be embedded in agent prompts, system instructions, or IDE rule packs.

## 3. Rationale

- **Prompt and rules contamination:** Personal narrative in prompts or doctrine increases the risk that tools treat non-authoritative text as instructions or facts.
- **Determinism:** Product behavior must be driven by explicit schema, PRDs, and code. Operator life history is not a versioned input to runtime decisions.
- **Hidden state:** Personal blobs in files or memory edges create **implicit** coupling between human private life and system graphs that validators and reviewers cannot reliably audit.
- **Narrative bleed:** Channel and memory systems are for **work products** and **lineage**; they must not become a diary of the operator.

## 4. Enforcement

1. **Prohibited content:** Do not add operator biography, trauma narrative, inferred personal detail, emotional summaries, or speculative operator models to **PRDs**, **doctrine**, **memory TOONs**, **agent prompts**, **changelog entries**, **headers**, or **structured exports** used as truth.
2. **Incremental correction:** When violations are found, **open a tracked correction** (issue, channel task, or maintainer-approved edit) and fix **the specific artifact**. Do **not** run repository-wide bulk deletion or automated rewrite of historical files without an explicit maintainer plan (risk of collateral damage and loss of legitimate technical history).
3. **Review gate:** Reviewers treat unexpected personal narrative in system paths as **defects** to remove or redact, not as optional tone.
4. **Constitutional anchor:** **[PRD 00](docs/prd/00_root_constitutional_system_requirements.md)** references this file under **System integrity rules** so IDE agents and humans see the boundary at the constitutional layer.
5. **Violation code:** PERSONAL_CONTEXT_VIOLATION -- Personal context written into system artifacts in violation of this doctrine.

## 5. Compliance statement

Edits that add personal context to system artifacts are **non-compliant** with this doctrine until removed or moved to an **appropriate non-system** medium outside the paths in **section 1.1**.

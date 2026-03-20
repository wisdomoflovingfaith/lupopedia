---
lupopedia.headers:
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/synthesized-framework.md"
  version_when_written: "4.0.84"
  web_path: "[synthesized-framework](http://www.lupopedia.com/docs/synthesized-framework)"
  last_modified_utc: "20260316"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 103
  actor_name: "antigravity"
  delegation_chain: "antigravity:wolfie"
  artifact_type: "doctrine"
  artifact_kind: "framework"
  purpose: "Lupopedia Synthesized Documentation Framework — quadrant ontology and canonical header integration for AI agent documentation"
  namespace: "core"
  traits: ["canonical", "synthesized", "quadrant", "v4.0.78"]
  tags: ["framework", "doctrine", "quadrant", "headers", "multi-agent"]

lupopedia.metadata:
  comment: "Historical quadrant and framework fields preserved from pre-4.0.78 synthesized header format. Dotted namespace and custom field names are conceptual; canonical headers use approved taxonomy (see lupopedia.headers.namespace)."
  quadrant_class: "Lupopedia.headers.framework"
  quadrant_namespace_historical: "lupopedia.frameworks.documentation"
  quadrant_channel: "ide.antigravity"
  quadrant_collection: "doctrine-aligned-reports"
  orchestrator: "WolfieAI"
  facet: "anti-chaos"
  agent: "Antigravity"
  role: "Framework Synthesis / Refactoring"
  task: "Combine reports into unified documentation framework"
  timestamp_utc: "20260312093300"
  historical_file_path: "lupopedia.docs/synthesized-framework.md"
  database_table_name: "documentation_frameworks"
  database_table_collection: "active"
  database_table_channel: "ide"
  database_table_namespace: "lupopedia.tables.documentation_frameworks"
  database_edges_snapshot: "documentation_frameworks → agents; documentation_frameworks → channels; documentation_frameworks → collections"
  runtime_min_php: "5.6"
  runtime_syntax: "PHP 5.3 compatible"
  runtime_includes_path: "<prefix>lupo-includes/"

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "antigravity"
  next_action:
    - "Keep quadrant metadata aligned with LUPOPEDIA_HEADERS doctrine"
    - "Reference this document for historical quadrant semantics; use lupopedia.headers for validation"
---
# file: Lupopedia AI Agent Documentation Framework — session: L-LUPO-ROOT — delegation: cursor:root — web_path: http://www.lupopedia.com/docs/synthesized-framework

**Documentation debt note (4.0.78):** This file was migrated to canonical LUPOPEDIA_HEADERS. The quadrant model (CLASS, NAMESPACE, CHANNEL, COLLECTION) and dotted notation (e.g. `lupopedia.frameworks.documentation`) are **historical conceptual notation** and are preserved in the **`lupopedia.metadata`** block. Canonical headers now use the **single-word namespace taxonomy** (`core`, `auth`, `channels`, etc.) per [LUPOPEDIA_HEADERS_FORMAT.md](doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) §2.2. The conceptual architecture below is unchanged.

---

# Lupopedia AI Agent Documentation Framework: Synthesized Strategy

**Date:** March 12, 2026  
**Status:** Canonical / Synthesized  
**Orchestrator:** WolfieAI  
**Agent:** Antigravity (Anti-Chaos Facet)

---

## 1. Executive Summary

This report establishes the **Lupopedia Synthesized Documentation Framework**, a deterministic and lineage-safe architecture for AI agent documentation. By merging the ontological quadrant system ("Taming the Chaos") with the structured registration schema, Lupopedia ensures that documentation is treated as structured data rather than inert text. This framework enables multi-agent concurrency, provides clear jurisdiction via namespaces, and enforces alignment with the underlying database schema.

## 2. Problems Addressed

Historically, documentation for AI-driven systems has suffered from:
- **Documentation Chaos:** Lack of a unified categorization model leading to fragmented information.
- **Namespace Collisions:** Overlapping jurisdictions between different agents or system modules.
- **Schema Ambiguity:** Disconnect between documented entities and their actual database representations.
- **Orphan Artifacts:** Documentation files with no clear lineage, task association, or ownership.
- **Agent Drift:** AI agents generating non-deterministic contents that drift from system doctrines.

## 3. Unified Documentation Framework

The unified framework treats documentation as a **virtual extension of the database**. Every artifact is an entity within a structured graph, defined by its lineage and its relationships (edges) to other system components.

### 3.1 The Four Ontological Quadrants
Documentation MUST be categorized into one of the four quadrants to ensure deterministic placement and discovery:

1.  **CLASS (Archetype / Blueprint):** Defines the structural type of the documentation (e.g., `Lupopedia.headers.framework`).
2.  **NAMESPACE (Jurisdiction / Ownership):** Defines the logical authority and pathing. **LUPOPEDIA_HEADERS alignment (4.0.78+):** The canonical header field is **`namespace`** in **`lupopedia.headers`**. For table documentation and other artifact types where policy applies, use the **approved single-word taxonomy** from [LUPOPEDIA_HEADERS_FORMAT.md](doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) §2.2: `auth`, `channels`, `core`, `content`, `analytics`, `federation`, `governance`, `integration`, `legacy`. Dotted notation (e.g. `lupopedia.frameworks.documentation`) is historical in this framework document; validators and header compliance use the approved taxonomy in the header field.
3.  **CHANNEL (Communication Bus):** Defines the operational surface where the artifact was generated or is discussed (e.g., `ide.antigravity`).
4.  **COLLECTION (Library / Grouping):** Defines the logical grouping for batch retrieval (e.g., `doctrine-aligned-reports`).

### 3.2 Canonical Header Integration
The quadrants are enforced through the **Canonical Header Block**, which serves as the machine-parsable manifest for the file. This ensures that any Lupopedia-aware IDE agent can immediately resolve the artifact's purpose and location in the system graph.

## 4. Canonical Header + Quadrant Ontology

Every Lupopedia artifact must begin with the following YAML-compliant block. These fields are mandatory for registry compliance:

| Field | Purpose | Example |
| :--- | :--- | :--- |
| **FILE** | Physical path from root | `lupopedia.docs/synthesized-framework.md` |
| **CLASS** | Ontological archetype | `Lupopedia.headers.framework` |
| **NAMESPACE** | Jurisdiction path (header: use approved taxonomy per LUPOPEDIA_HEADERS_FORMAT §2.2) | `core`, `auth`, `channels`, etc.; or historical `lupopedia.frameworks.documentation` |
| **CHANNEL** | Communication context | `ide.antigravity` |
| **COLLECTION** | Batched library ID | `doctrine-aligned-reports` |
| **ORCHESTRATOR** | Directing authority | `WolfieAI` |
| **FACET** | Specific agent persona | `anti-chaos` |
| **AGENT** | Executing agent | `Antigravity` |
| **ROLE** | Defined responsibility | `Framework Synthesis` |
| **TASK** | Specific task slug | `combine-reports` |
| **TIMESTAMP_UTC** | Generation time | `20260312093300` |
| **DATABASE.TABLE** | Linked schema target | `documentation_frameworks` |
| **RUNTIME.MIN_PHP** | System requirement | `5.6` |

## 5. Example Agent Registration: Antigravity

To demonstrate the framework in practice, here is how the **Antigravity** agent is registered and how it interacts with the documentation system.

- **Class:** `ide.agent`
- **Namespace:** `lupopedia.agents.ide`
- **Channel:** `ide.antigravity`
- **Collection:** `ide-agents`
- **Orchestrator:** `WolfieAI`
- **Facet:** `anti-chaos`

### Agent Interactions
- **Channels:** Antigravity monitors the `ide.antigravity` channel for directives and broadcasts meta-summaries of documentation state.
- **Collections:** The agent organizes its syntheses into the `ide-agents` collection for comparative analysis.
- **Documentation Tables:** Antigravity writes directly to the `documentation_frameworks` and `agent_registries` tables (via TOON-aligned SQL).
- **Dynamic Edges:** The agent maintains edges between `agent` entities and `framework` entities, allowing the system to track which agent is enforcing which doctrine.

## 6. IDE Agent Concurrency

The framework supports multiple agents operating in parallel without documentation drift or collision.

- **Cursor (code generation / refactoring):** Operates in `lupopedia.code.logic` namespace; edges to `lupo_contents`.
- **Windsurf (UI / UX prototyping):** Operates in `lupopedia.ui.interface` namespace; edges to `lupo_themes`.
- **Kiro (visualization / analysis):** Operates in `lupopedia.analytics.core` namespace; edges to `lupo_metrics`.
- **JetBrains (IDE integration):** Operates in `lupopedia.tools.jetbrains` namespace; edges to `lupo_actor_reply_templates`.
- **Trae (testing / debugging):** Operates in `lupopedia.quality.tests` namespace; edges to `lupo_audit_log`.
- **Antigravity (documentation synthesis + dynamic edge governance):** Governs the `lupopedia.frameworks` namespace, ensuring all of the above agents follow the header doctrine.

Agents interact through shared **Channels** (for message passing), distinct **Namespaces** (for file ownership), and unified **Collections** (for mission-specific groupings).

## 7. Snapshot vs Live Edges

Lupopedia distinguishes between where a relationship is *noted* and where it is *proven*.

### 7.1 Snapshot Edges
These are static relationships declarations stored directly in the Markdown headers (e.g., `DATABASE.EDGES (SNAPSHOT)`). They provide immediate, low-latency context to agents reading the file.

### 7.2 Live Edges
These are dynamically queried relationships from the database registry. Live edges are the ultimate source of truth, reflecting the current state of the running system.

**Instructions for querying Live Edges:**

**Python:**
```python
# Query live edges via standard Python utility
python query_edges.py <namespace>
```

**PHP:**
```php
// Query live edges via core PHP service
php query_edges.php <namespace>
```

Lupopedia separates documentation state from runtime state to allow for **offline governance**—the ability to verify documentation integrity even when the primary database is not active.

## 8. Implementation Benefits

- **Scalability:** Standardized headers allow for automated indexing of thousands of documentation files.
- **Discoverability:** Quadrant-based sorting makes it trivial to find all "Collections" for a specific "Namespace".
- **Deterministic Structure:** Removes agent guesswork; headers are either compliant or invalid.
- **Auditability:** Every file contains its own orchestrator and task lineage.
- **Version Lineage Preservation:** The footer block ensures version history is baked into the file itself.

## 9. Implementation Roadmap

1.  **Phase 1: Schema Definition:** Finalize the `documentation_frameworks` table in `install_new_lupopedia.sql`.
2.  **Phase 2: Migration of Legacy Documentation:** Batch update existing `lupo-docs/` files to include the quadrant headers.
3.  **Phase 3: UI / UX Integration:** Implement a documentation browser in the Lupopedia Admin that filters by CLASS and NAMESPACE.
4.  **Phase 4: Governance Rules:** Deploy the Antigravity "Anti-Chaos" facet to monitor file changes and reject non-compliant headers.
5.  **Phase 5: IDE-Agent Integration:** Update Cursor and JetBrains rules to enforce header validation upon file creation (`.cursorrules` alignment).

## 10. Conclusion

The Lupopedia Documentation Framework moves beyond mere "notetaking" into the realm of **Dynamic Knowledge Governance**. By enforcing the quadrant ontology and canonical header schema, we transform documentation into a robust, machine-parsable subsystem of the Semantic OS.

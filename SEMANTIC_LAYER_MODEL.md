# SEMANTIC_LAYER_MODEL.md

## The Four‑Layer Semantic Model
## Lupopedia Doctrine — Semantic Architecture v1.0

## 1. Purpose of This Document
This doctrine defines the Four‑Layer Semantic Model, the core architecture that governs how Lupopedia extracts, organizes, interprets, and generates meaning.
It establishes the semantic pipeline that transforms raw content and user behavior into a unified, evolving meaning graph.

This model is foundational to:

- semantic ingestion
- navigation intelligence
- agent reasoning
- meaning propagation
- cross‑node federation
- long‑term knowledge evolution

Every subsystem that touches meaning must align with this doctrine.

## 2. Overview of the Four Semantic Layers
Lupopedia's semantic engine operates across four distinct layers, each contributing a different class of edges to the global meaning graph.

These layers are:

1. **Interaction Semantics** — meaning derived from human behavior
2. **Extracted Semantics** — meaning derived from the content itself
3. **Navigation Semantics** — meaning derived from user‑defined structure
4. **AI‑Generated Semantics** — meaning inferred by agents

Each layer is independent, but all converge into the unified semantic graph.

## 3. Layer One — Interaction Semantics
### Behavioral Edges
This layer captures meaning from what humans actually do.

Examples of interaction edges:

- views
- likes
- shares
- comments
- dwell time
- scroll depth
- return visits
- session paths
- abandonment points
- bounce patterns

These edges represent attention, interest, and behavioral intent.

### Properties
- time‑sensitive
- high‑volume
- decays naturally
- excellent for trend detection
- reflects real‑world usage

Interaction semantics form the pulse of the system.

## 4. Layer Two — Extracted Semantics
### Structural Edges
This layer captures meaning inherent in the content itself.

Sources include:

- folder structure
- filenames
- anchor tags → hashtags
- <head> metadata
- YAML front‑matter
- internal links
- backlinks
- semantic headers (H1–H6)
- content type
- embedded taxonomies

These edges represent intrinsic meaning — the meaning encoded by the author or the artifact.

### Properties
- stable
- deterministic
- excellent for clustering
- forms the backbone of the semantic graph

Extracted semantics form the skeleton of the system.

## 5. Layer Three — Navigation Semantics
### User‑Defined Edges
This layer captures meaning created by how humans organize content.

Sources include:

- tabs
- collections
- curated lists
- navigation paths
- category assignments
- featured content
- user‑defined relationships

These edges represent intentional meaning — the meaning humans impose on the system.

### Properties
- semi‑stable
- reflects editorial intent
- excellent for guiding discovery
- bridges content across contexts

Navigation semantics form the architecture of the system.

## 6. Layer Four — AI‑Generated Semantics
### Inferred Edges
This layer captures meaning discovered by agents, not explicitly encoded by humans.

Sources include:

- similarity analysis
- conceptual relationships
- emotional metadata
- doctrine‑based inference
- cross‑node federation
- meaning propagation
- cluster detection
- temporal reasoning

These edges represent emergent meaning — the meaning the system learns over time.

### Properties
- dynamic
- adaptive
- probabilistic
- requires validation
- expands the semantic universe

AI semantics form the intuition of the system.

## 7. Unified Semantic Graph
All four layers feed into the Unified Semantic Graph, which stores:

- edges
- weights
- decay factors
- trend scores
- timeframes
- provenance (layer)

This graph is the living memory of Lupopedia.

It is:

- queryable
- federated
- self‑updating
- time‑aware
- agent‑accessible

Every agent, module, and subsystem must treat the unified graph as the source of truth for meaning.

## 8. Time‑Aware Semantics
Meaning is not static.

Every semantic edge must consider:

- age of the page
- recency of interactions
- trend velocity
- decay curves
- selected timeframe
- rolling windows

This doctrine authorizes the creation of:

### lupo_semantic_paths
A table that stores semantic edges with:

- layer
- weight
- decay
- trend score
- timeframe
- custom windows
- timestamps

This table is the working memory for semantic computation.

### lupo_unified_analytics_paths update
The period ENUM must include:

- yearly
- total

This enables long‑term semantic weight and historical analysis.

## 9. Doctrine Requirements
1. All semantic ingestion must specify its layer.
   No edge may be stored without provenance.
2. AI‑generated edges must be marked as ai and validated.
3. Interaction edges must decay over time.
4. Extracted edges must remain stable unless the content changes.
5. Navigation edges must reflect user‑defined structure.
6. The unified graph must merge all layers without losing provenance.
7. Agents must use the unified graph as their semantic substrate.

## 10. Future Extensions
This doctrine anticipates:

- cross‑node semantic federation
- multi‑agent meaning propagation
- emotional metadata integration
- temporal semantic clustering
- semantic caching
- semantic compression
- semantic diffing

These will be defined in future doctrine updates.

## 11. Status
**Version:** 1.0  
**Author:** Wolfie  
**Status:** Canonical  
**Scope:** Global Semantic Architecture  
**Applies To:** All agents, ingestion pipelines, controllers, and semantic subsystems

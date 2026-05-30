---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md"
  status: "active"
  when_updated: "20260406142956"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reverse_engineering_dependency_policy
  channel_key: null
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: REVERSE_ENGINEERING_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md

# Reverse engineering doctrine (4.0.x)

## Principle

**Dependencies are liabilities. Understanding is an asset.**

You may learn from any package: read its source, trace its behavior, extract the idea. You may **not** treat “`npm install` / `composer require` into core” as a substitute for understanding.

**Trust nothing you have not understood.** Ship only code you own, control, and can maintain.

## The spectrum

| Action | Constitutional for shipped Lupopedia core? | Notes |
|--------|-------------------------------------------|--------|
| `npm install` / `composer require` into app paths | **No** | Runtime dependency; supply-chain and maintenance liability |
| Read upstream source (clone, browse, vendor tarball) | **Yes** | Learning; no dependency added |
| Reverse engineer the algorithm or policy | **Yes** | Understanding without adoption |
| Implement your own code in tree | **Yes** | Preferred |
| Copy-paste snippets | **Maybe** | License, attribution, and comprehension required; prefer clean-room rewrite from understanding |

## Why this fits the constitution

| Rule | How reverse engineering helps |
|------|------------------------------|
| No external runtime dependencies in core | You do not add packages to the shipped app |
| Application owns logic | Your implementation is explicit and reviewable |
| Deterministic, traceable behavior | You control the code path |
| No blind trust of third-party blobs | You only ship what you understand |

## Process (dependency order)

1. **Identify** a package or article that solves a nearby problem.
2. **Study** the source in isolation (clone, read, debug in a scratch directory) — **not** as an install into `includes/`.
3. **Extract** the invariant: what problem, what inputs/outputs, what failure modes.
4. **Implement** a Lupopedia-native class or module under project conventions (PHP 7.4–8.x, PDO_DB, no new framework). **Runtime** code **must** use **`PDO_DB`** and **named placeholders**; **`mysqli`** is **not** allowed in runtime. The **installer** carve-out is **only** for **`install.php`** / wizard **as** defined in **`docs/doctrine/DATABASE_DOCTRINE.md`** — **Runtime database access (PDO_DB) and installer exception**.
5. **Document lineage** in LUPOPEDIA HEADERS: `outbound_edges` with `type: inspired_by` (or equivalent) to the upstream repo or paper, and reference this doctrine.
6. **Ship** only your tree — no new package manager dependency in runtime paths.

## Federation nodes as reverse engineering sandboxes

Lupopedia already separates **canonical product files** from **external research ingests** on disk. Use that separation as the audit trail for “study here, ship there.”

### Layout (canonical)

Per **[PRD 29 — project structure](../prd/29_project_structure.md)**, external federation research lives under:

`research/federation_nodes/{federation_node_id}/<package_key>/`

Use **`federation_node_id >= 2`** for **upstream clones, specs, and read-only reference trees** (existing examples in-repo include nodes **2** and **3**). Add a **`MANIFEST.md`** from **[research/federation_nodes/_templates/MANIFEST_TEMPLATE.md](../../research/federation_nodes/_templates/MANIFEST_TEMPLATE.md)** so provenance, purpose, and license context stay attached to the tree.

This directory is **not** a Composer/npm install target and **must not** be `require`d by `index.php` or `includes/` bootstrap. It is a **textbook shelf**, not a runtime library.

### Boundary

| Location | Role |
|----------|------|
| **`app/`**, **`includes/`** (and normal shipped PHP/JS) | **Your implementation** — ships with the product; own the code. |
| **`research/federation_nodes/{id}/`** with **`id >= 2`** | **External material** — study, diff, cite; do **not** treat as a dependency. |
| **Docs with `federation_node_id: 0` in headers** | Default **repository / documentation** scope for many doctrines and guides — **not** interchangeable with “sandbox clone path”; sandboxes are identified by **folder** under `research/federation_nodes/`. |

### Workflow (dependency order)

1. Create **`research/federation_nodes/{n}/<package_key>/`** (choose **`n >= 2`** consistent with `lupo_federation_nodes` and project practice).
2. Populate upstream content (e.g. `git clone` into that folder, or unpack sources) and add **`MANIFEST.md`** (fill **`purpose`**, **`documentation_type`**, and other fields per the template — e.g. external library / specification).
3. **Study** off the critical path — read, trace, take notes; **no** runtime `require` from this tree into core.
4. **Implement** behavior under **`includes/classes/`** or **`app/`** as native Lupopedia code.
5. **Lineage:** on the implementation, add **`outbound_edges`** with `type: inspired_by` to the federation-node path (and upstream URL in prose or edges as appropriate).

### Why this is constitutional

| Concern | Federation-node sandbox |
|--------|-------------------------|
| No runtime dependency on strangers’ blobs | Core never loads vendor code from the research tree |
| Audit trail | MANIFEST + folder location record *what* was studied |
| License hygiene | Source kept for reference; reimplementation is informed, not blindly pasted |
| Clear boundary | **Ship** from `app/` / `includes/`; **archive** upstream under **`research/federation_nodes/2+`** |

## Federation nodes: dual purpose (research sandbox and semantic network)

The numeric space **`federation_node_id >= 2`** is **shared** for two different intents. **Distinction is by context and headers**, not by the integer alone.

### Purpose 1 — External research / reverse engineering (documented here, 4.0.x)

- **Content:** Cloned upstream packages, specs, read-only reference trees under **`research/federation_nodes/{id}/`**.
- **Direction:** One-way ingestion — you study; the tree is not a runtime dependency.
- **Lineage:** Prefer **`outbound_edges`** with **`type: inspired_by`** (or equivalent) toward the ingest path or upstream URL.
- **Headers / MANIFEST:** Record **`purpose`** and provenance in **`MANIFEST.md`**; where you add LUPOPEDIA HEADERS on wrapper docs, use **`artifact_kind`** / narrative consistent with **external research** (exact field names follow **[LUPOPEDIA HEADERS](LUPOPEDIA_HEADERS/README.md)** — do not invent schema keys that validators do not support).

### Purpose 2 — Semantic network peers (planned, not specified in 4.0.x)

- **Intent:** Other **Lupopedia installs** (or federation participants) exchanging **semantic data** under the same federation model — **long-term**, not binding for current patch work.
- **Status:** **Draft PRD exists:** **[PRD 34 — Federation node semantic network](../prd/34_federation_node_semantic_network.md)**. Runtime federation remains **post-4.0.x stabilization** unless ratified there. Cross-cutting inputs include semantic monitoring / graph work (e.g. **[PRD 28 — semantic monitoring widget](../prd/28_semantic_monitoring_widget.md)**).
- **Convention (future):** When the PRD exists, peers may be distinguished with metadata (e.g. edge types such as **learned_from** toward peer nodes) — **not** implemented or required until then.

### Rule for IDE agents (now)

Until the semantic-network PRD ships, treat **`federation_node_id >= 2`** in repo layout as **research sandbox / external ingest** only. **Do not** implement multi-install sync, discovery, or shared semantic replication as if it were specified.

### Proposed numeric bands (non-binding, not implemented)

The following **allocation sketch** is **documentation-only** — not schema, not enforced, subject to a future PRD:

| Band (proposed) | Proposed use |
|-----------------|--------------|
| **0** | Core / repository reference scope for many docs and metadata |
| **1** | Default local / deployed instance context where applicable |
| **2–99** | Research sandboxes and external ingests |
| **100–999** | Reserved for semantic-network peer identifiers (future) |
| **1000+** | Reserved for future expansion |

Existing trees (e.g. nodes **2** and **3** under **`research/federation_nodes/`**) remain valid **research ingests** regardless of this sketch.

### Long-term vision (non-binding)

A future where many installs contribute to a **distributed semantic network** is a **goal**, not a 4.0.x deliverable. **Ship one correct install first**; document federation peers when the PRD exists.

## License and copying (non-legal summary)

Laws vary; this is **not** legal advice. In doubt: **read the license**, prefer **original implementation informed by understanding** over literal copying.

| Typical license | Read source | Reimplement from understanding | Copy code into repo |
|-------------------|--------------|--------------------------------|----------------------|
| Permissive (e.g. MIT, BSD, Apache) | Yes | Yes | Often allowed with attribution and license notice — verify file-by-file |
| Copyleft (e.g. GPL family) | Yes | Yes (your code can remain separate if you do not combine on terms that trigger copyleft) | **High risk** — get review before merging |
| Proprietary / unclear | Only if permitted | Prefer clean-room | **Do not** without explicit permission |

## Relation to security testing

Studying a package is **analysis**. Putting it on the runtime path is **adoption**. Test-only tools (ZAP, Burp, CI scripts) live outside shipped core — see **[TWO_LAYER_SECURITY_DOCTRINE.md](TWO_LAYER_SECURITY_DOCTRINE.md)** (*Security testing dependencies vs runtime dependencies*) and **[AGENTS.md](../../AGENTS.md)**.

## Compromised-dependency thought experiment

Ask: *If this package were malicious tomorrow, would we notice before it mattered?* Reducing reliance on opaque blobs is the point. For structured adversarial testing and naming, see **[ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md)**.

## One-line rule

**You may learn from any package; you may only ship code you understand and control.**

This output complies with Lupopedia Constitutional Root Rules.

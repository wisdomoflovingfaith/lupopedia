---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/architecture/federation.md
  web_path: https://www.lupopedia.com/lupopedia/docs/architecture/federation.md
  status: active
  when_updated: '20260513121605'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/federation_architecture_spec.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/federation-spec-v1
  artifact_type: documentation
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: architecture-federation-v1
  lupopedia.schema: documentation
  prd_cluster: 34_A
  title: Lupopedia federation specification (node semantics and distribution)
  summary: 'Canonical semantics for federation_node_id 0/1/2+, local registries, installer-first distribution; DDL is install_new_lupopedia.sql federation_nodes.'
---
# Lupopedia federation specification

**Version 1.0** -- Canonical, non-drifting, installer-aligned (normative prose).  
**DDL surface:** `database/lupopedia/mysql/install/install_new_lupopedia.sql` (table `{{prefix}}federation_nodes` at runtime, typically `lupo_federation_nodes`).  
**Protocol and graph scope:** [PRD 34: Federation node semantic network](../prd/34_A-i_FEDERATION_NODE_SEMANTIC_NETWORK.md).  
**Row-level interpreter (validators, installer, runtime):** [federation_interpreter_rules.md](federation_interpreter_rules.md).

---

## BIG NOTE (read first)

Lupopedia is **not** positioned as a "clone GitHub, run your own server on a home lab" product as its **primary** distribution story.

It is **not** Docker-as-the-product.  
It is **not** "VPS first."  
It is **not** "local dev tree equals production."  
It is **not** a toy "spin up your own instance" narrative for operators.

For roughly twenty-five years the lineage has shipped through **packaged installers**, **auto-installers**, and **shared hosting** control panels (cPanel, Plesk, DirectAdmin, Softaculous, Installatron, Fantastico, Mojo Marketplace, and similar).

**Lupopedia continues that lineage.** The normative install is a **public origin** (scheme + host + optional path prefix) reached through hosting-panel flows, not through "I cloned the repo on my basement PC" as the default mental model for federation identity.

This specification is written for that reality. Developers may still run trees from disk; **federation semantics** still treat **node 1** as the **public install origin**, not `localhost` and not a container name.

---

## 1. Purpose of federation

Lupopedia federation allows **independent installations** of Lupopedia, each on a different public origin, to:

- recognize each other  
- exchange references  
- maintain semantic continuity  
- share channels, threads, and artifacts (when protocol work lands per PRD 34)  
- remain sovereign while interoperating  

There is **no central server**, **no global registry**, and **no universal node numbering** across the network.

Each installation maintains **its own** map of peers.

---

## 2. Federation node model

Every installation maintains a node registry table (runtime name typically **`lupo_federation_nodes`**).

That table is the **local view** of federation.

### Node IDs are local, not global

`federation_node_id` values **do not** match across installations except where explicitly standardized (records **0** and **1** roles below). Peer rows **2+** are **allocated locally** and mean nothing on a remote server unless carried as opaque IDs inside a larger handshake that includes **origin URL** and **trust** context.

---

## 3. Node definitions (canonical)

### 3.1 federation_node_id = 0 -- canonical upstream / project root

- Represents the **canonical project origin**: **`https://www.lupopedia.com`** (trailing slash normalization is an application concern; the **host** is the constant).
- Expected to exist in every installation that ships the canonical network map.
- Treated as **immutable** in meaning: it is the **universal constant** for "the Lupopedia project root" in federation **documentation and trust bootstrapping**, not an arbitrary third-party site.

This is the long-standing "record zero" role: **global meaning**, **one row**, **same URL everywhere**.

### 3.2 federation_node_id = 1 -- this installation (public origin)

**Rule:** node **1** is the **public origin** where **this** copy of Lupopedia was installed for real use.

Examples (illustrative):

- Installed at `https://catsarecool.com/lupopedia` -> node 1 base URL is that origin (including path prefix if the app is rooted there).  
- Installed at `https://mythos.world/lupo` -> node 1 is that origin.  
- Installed at `https://hawaiianhistory.net/lupopedia` -> node 1 is that origin.  

**Node 1 is not:**

- `http://localhost` / `127.0.0.1` as the normative identity  
- a machine name, Docker hostname, or LAN-only host as the **canonical** federation identity  
- a global peer ID  
- "whatever folder I git cloned"  

**Node 1 is:**

- the **operator-facing public URL** the installer recorded (Softaculous, Installatron, Fantastico, Mojo, Plesk/cPanel add-on flows, or equivalent)  
- the **identity of the installation** for semantic graph edges that mean "local house"  

### 3.3 federation_node_id >= 2 -- other installations known here

Rows **2+** are **other** Lupopedia installations **this** install has learned about.

- Not centralized  
- Not globally numbered  
- Not portable as bare integers to another install  
- **Unique per installation** and may appear in **different orders** elsewhere  

Example:

- On install A: node 2 might be install B, node 3 install C.  
- On install B: node 2 might be install C, node 3 install A.  

That asymmetry is **correct**.

---

## 4. Federation principles

### 4.1 Sovereignty

Each installation is a sovereign semantic universe. No remote node may silently mutate local canonical state.

### 4.2 Local identity

Node **1** always means "**self**" in the federation map, but "**self**" is **different** for every installation (different `base_url`).

### 4.3 No global registry

There is no central authority assigning peer numbers **2+**.

### 4.4 No assumptions

No installation may assume another installation's `federation_node_id` for a given peer matches its own table.

### 4.5 Discovery

Rows **2+** appear when:

- another installation is referenced or imported by URL  
- a federation handshake completes (future protocol; see PRD 34)  
- a remote channel, thread, or artifact is accessed under explicit trust rules  

---

## 5. Federation data fields (install DDL)

Authoritative column list: **`install_new_lupopedia.sql`** `CREATE TABLE {{prefix}}federation_nodes`.

Prose to column mapping (avoid invented names):

| Prose / draft name | Canonical column (install SQL) |
|--------------------|----------------------------------|
| domain URL / public origin | `base_url` |
| title / short label | `node_name` |
| long description | `description`, `node_description` |
| lifecycle / operational mode | `status` (varchar in current install DDL) |
| last change time (UTC packed) | `updated_ymdhis` (also `created_ymdhis`, `last_sync_ymdhis`, `last_seen` as applicable) |
| trust tier | `trust_level` (tinyint in current install DDL) |
| protocol version | not a dedicated column today; use `meta_json` or future PRD-approved extension |
| notes / arbitrary payload | `meta_json`, `capabilities`, or `description` per policy |

Additional columns in the same table (`node_type`, counts, `shared_secret`, soft delete fields, theme slug, etc.) are **operational**; they do not change the **meaning** of IDs **0**, **1**, and **2+** above.

---

## 6. Installation behavior

### 6.1 Auto-installers (primary channel)

Normative distribution includes:

- Softaculous  
- Installatron  
- Fantastico  
- Mojo Marketplace  
- Plesk / cPanel style auto-installers  
- Shared hosting environments generally  

### 6.2 GitHub and source trees

GitHub (or any VCS) is a **source** and **review** surface, not the **primary** operator story for "what is my node 1 identity." Most end operators will never interact with GitHub.

---

## 7. Constitutional interpreter block (mnemonic)

Plain-language commitments for validators, installers, and agents:

```
OHANA: Each installation is a sovereign origin with its own public node 1 URL.
KAPU: No installation may treat another install's local peer numbers as global truth.
KAPAKAI: Failure mode is treating node 1 as a universal constant across the fleet.
PONO: Node 1 is always this install's public origin; node 0 is always the canonical project root URL.
KULEANA: Each installation maintains its own federation_rows for peers (2+).
ALII: Only the operator / installer defines the real node 1 base_url.
KUMU: Federation is a map between sovereigns, not a single hierarchy.
EH_BRAH_WHY: Confusion between filesystem dev URLs and public node 1.
PUKA: Missing explicit doctrine for local vs global identity (closed by this document).
```

---

## 8. Summary (non-drifting)

- **Node 0** = canonical project root URL (`https://www.lupopedia.com`).  
- **Node 1** = this copy's **public install origin** (auto-installer / panel recorded URL).  
- **Node 2+** = other installs **known to this** database only.  
- **No** global numbering for **2+**.  
- **No** central registry.  
- **Primary** story is **public hosting origins**, not home-lab-first.  

---

## 9. Alignment note (repository honesty)

Until install and seed scripts are explicitly ratified to insert **row 0** and **row 1** exactly as above, treat this file as **normative semantics**. If current seed samples still use placeholder `base_url` values or omit row **0**, that is **drift to fix in SQL**, not a reason to reinterpret node meanings. Track implementation status under **PRD 34** and the install/seed change list for the release that enables federation runtime.

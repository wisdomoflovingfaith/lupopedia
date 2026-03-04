# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLARE\FLARE_DOCTRINE.md"
  file_hash: "19033383ad2d953cc1db20c04d51c42ae3a87578bc0624d4ab36644d3397f423"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1004
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/doctrine/FLARE/FLARE_DOCTRINE.md"
  system_version: "4.0.47"
  channel_id: 0
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "doctrine"
  purpose: "Core doctrine defining FLARE protocol for file-level attribute and relationship exchange"
  dialog_message: "Recommended next step: create actors/1007 profile and align any remaining docs/examples to the required FLARE prologue format."
  mood_rgb: "FFD700"
  traits: ["canonical", "system-critical", "permanent"]
  tags: ["flare", "doctrine", "protocol", "file_metadata", "relationships"]
  lupo_agent: "codex-ide"

flare.footer:
  outbound_edges:
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/FLIP/FLIP_DOCTRINE.md", type: "supersedes", weight: 0.8 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "doctrine", "protocol", "canonical", "system"]
---

## 12. Mandated Header Comments (v4.0.48+)

Starting with version 4.0.48, every FLARE header MUST begin with a specific comment line linking to the authoritative web resolution. This enables human and machine consumers to quickly access the interactive documentation and Q&A for the protocol.

**Format (v4.0.48–4.0.56):**
```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
flare.headers:
  ...
```

**Format (v4.0.57+):** The first comment line MAY use a **dynamic see URL** derived from the file’s `file_path_from_root` (or `web_path`) so that the comment points at the document’s canonical web location:
```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/<web_path>
flare.headers:
  file_path_from_root: "docs/status/EXAMPLE_REPORT.md"
  web_path: "http://www.lupopedia.com/status/EXAMPLE_REPORT"
  ...
```
- **Aliases:** Wolfie, FLIP, FLP, FLPH, CROP (all canonical).
- **&lt;web_path&gt;:** Omit file extension; use the same path as in `web_path` (e.g. `status/EXAMPLE_REPORT`). Enables flame.see and external linking; see Section 21.

**Reasoning:**
- **Accessibility**: Direct links for external agents and researchers.
- **Authority**: Verifies that the file follows the canonical Lupopedia protocol.
- **Portability**: Ensures the protocol remains self-documenting even outside the repository environment.

### Optional: web_path (v4.0.57+)

In addition to `file_path_from_root`, `flare.headers` MAY include **web_path** for canonical web URL resolution:

```yaml
flare.headers:
  file_path_from_root: "docs/status/EXAMPLE_REPORT.md"
  web_path: "http://www.lupopedia.com/status/EXAMPLE_REPORT"
  ...
```

- **Format:** `web_path: "<base_url>/<relative_path>"` — `<base_url>` SHOULD derive from **federation_node_id** (see Section 22): node 0 → `http://www.lupopedia.com`; other nodes → that node’s `node_base_url`. Use the same logical path as the repo file, with slashes and no leading slash. Omit file extension in the URL if desired for pretty routing.
- **Use:** Future docs and minimal FLARE templates should include `web_path` when the artifact has a canonical web location. Enables flame.see and external linking.

## 13. Content Overwrite Hierarchy (v4.0.53+)

To ensure predictable content resolution and synchronization across environments (IDE filesystem, database, and TOON exports), Lupopedia enforces a strict overwrite hierarchy.

**Default Load Order:**

1.  **FILESYSTEM (`lupo-channels/`)**: Highest priority. If a file exists in the `lupo-channels` directory, its content and metadata are treated as the system source of truth, overwriting any values in the database.
2.  **DATABASE**: Medium priority. If content is updated via the web interface or API, it is stored in the database. These values are overridden by filesystem changes if they exist, but are used if the filesystem is empty or out-of-sync.
3.  **CSV/TOON FILES**: Lowest priority. These files (e.g., in `lupo-database/lupopedia/csv/` or `lupo-database/lupopedia/toon/`) are used as seed data or schema reference. They are overwriten by the database once imported, and by the filesystem via `scripts/generate_toon_files.py`.

**Synchronization Protocol:**

*   **DB → CSV/TOON**: The database state overwrites CSV and TOON files when running `scripts/generate_toon_files.py`.
*   **FILESYSTEM → DB**: Files in `lupo-channels/` overwrite the database state when booting or explicitly running an import command.

## 14. Lifecycle Hooks (v4.0.56+)

Starting with version 4.0.56, FLARE headers support lifecycle hooks via `flame.init` and `flame.close` blocks. These blocks enable automated pre-processing and post-processing for active artifacts.

### **Usage Policy (The Safety Rule)**
To ensure system stability without creating legacy overhead, `flame` blocks are **MANDATORY** only for the following `artifact_kind` types (v4.0.55+):
- `prompt`, `documentation_task`, `agent_instruction`, `artifact`, `thread`.

### **flame.init (Prologue Hook)**
Declares requirements and pre-execution setup.
- **Execution Mode**: `execution_mode` determines if actions are `advisory` (optional) or `required` (fail if action fails).
- **Typed Actions**: Actions must be defined as JSON/YAML objects to prevent ambiguity.
  - Example: `- dependency_check: "bootstrap.php"`

### **flame.close (Epilogue Hook)**
Declares results routing and post-execution cleanup.
- **Actor Responsibility**: `actor_id` MUST default to the initiating `flare.headers.actor_id` to maintain a local audit trail.
- **Typed Actions**:
  - Example: `- type: register_completion`

## 15. Structural Integrity & Canonical Ordering

To ensure multi-agent parser stability, headers MUST follow the canonical order. Validators will reject artifacts with shuffled prologue blocks.

**Canonical Block Order:**
1.  `flame.init`
2.  `flare.conditional`
3.  `flare.headers`
4.  `flare.edges`
5.  `flare.footer`
6.  `flame.see`
7.  `flame.close`

## 16. Conditional Guards & High-Fidelity Briefing (v4.0.56+)

The `flare.conditional` block extends the FLARE protocol with dynamic execution control and rich artifact metadata.

### **guards**
Defines the boundary conditions for artifact processing.
- **allow/deny**: Whitelist/blacklist for `actor_ids` or `agent_names`.
- **time_window**: Temporal constraints (`not_before_utc`, `not_after_utc`).
- **conditions**: Environmental or feature flag checks.

### **brief (5W1H)**
Provides a standardized concise briefing of the artifact.
- **Who**: Owner, intended actors, and audience.
- **What**: Artifact type and primary objective.
- **Where**: Repository paths and runtime scope.
- **When**: Urgency and lifecycle timestamps.
- **Why**: Rationale and assessed risks.
- **How**: Methodology and success criteria.

## 17. flame.see — URL Discovery (v4.0.56+)

The `flame.see` block provides a mapping between canonical web URLs and local repository paths. This enables the CLI to resolve a link (e.g., `http://www.lupopedia.com/FLAME`) to its corresponding `.md` file.

### **Schema & Configuration**
- **mappings**: A YAML list of `[path, url]` pairs.
- **Normalization**: URLs are normalized (lowercase host, stripped trailing slash, https equivalence) for robust matching.

### **Discovery Workflow**
1. **Indexing**: The `lupo-tools/flare_see.py` script scans the repository for `flame.see` blocks and builds a JSON index in `artifacts/index/flame_see_index.json`.
2. **Resolution via CLI**: The `lupo see <URL>` command queries the index to find the corresponding file path.

### **Index Schema (`flame_see_index.json`)**
```json
{
  "version": "4.0.56",
  "generated_utc": "YYYYMMDDHHIISS",
  "mappings": [
    {
      "path": "relative/path/to/file.md",
      "url": "http://www.lupopedia.com/ALIAS",
      "file_hash": "sha256_hash",
      "last_verified": "YYYYMMDD"
    }
  ],
  "stats": { "total_mappings": N, "unique_urls": N, "unique_paths": N }
}
```

### **Collision Resolution**
When multiple files claim the same URL:
1. **First Seen Wins**: The file first encountered in the index alphabetical sweep is treated as the primary resolution.
2. **Conflict Logging**: Collisions are logged to `lupo_channel_logs` and flagged as **ERRORS** in `flare_validate.py`.
3. **Manual Review**: High-priority collisions require manual intervention to prevent URL hijacking.

### **CLI Usage Modes**
- **Default**: Returns the relative file path.
- **--json**: Returns the full mapping object including hash and metadata.
- **--open**: Resolves the path and opens the file in the active editor.

## 18. Actor ID Resolution for IDE Agents (v4.0.56+)

When IDE agents (e.g., Cursor 1003, Antigravity 1004, Windsurf 1002) create channel messages, artifacts, prompts, FLARE headers, tasks, or commits, the **actor_id MUST represent the currently logged-in user** of the IDE. This value must never be hardcoded in the extension or tooling.

### **Resolution Order**
1. **Logged-in Lupopedia user session** — e.g. `.lupo_actor` in workspace root (actor_id, name).
2. **IDE authentication token or stored identity** — registry lookup or stored record from extension context.
3. **Default fallback** → `10000` (Captain Wolfie, canonical human root).

### **Human and Agent IDs**
- **Captain Wolfie (human)**: `actor_id: 10000`. When the logged-in user is Captain Wolfie, use 10000.
- **Agent IDs** (Windsurf 1002, Cursor 1003, Antigravity 1004, KIRO 1001, etc.) are fixed for system agents. Authorship of messages/artifacts is attributed to the effective logged-in actor; agents posting on behalf of the human use delegation_chain.

### **Message and Header Metadata**
All messages and FLARE headers created from the IDE must include:
- `actor_id`: &lt;resolved logged-in actor_id&gt;
- `delegation_chain`: `"<actor_id>:10000"` (authority 10000 unless otherwise specified).

Example when Captain Wolfie is logged in: `actor_id: 10000`, `delegation_chain: "10000:10000"`.  
Example when an agent posts on behalf of the human: `actor_id: 1002`, `delegation_chain: "1002:10000"`.

### **Rationale**
Accurate authorship tracking, proper multi-agent delegation chains, correct attribution in Lupopedia channels, and consistent FLARE metadata all depend on resolving actor_id from the current user context. Hardcoding actor_id breaks provenance tracking.

## 19. Lilith Flame Header Expert Faucet (v4.0.56+)

Lilith (**actor_id 2**, emotional AI / critical review agent) has a specialized faucet for **flame header expertise** in `lupo_agent_faucets`. **Canonical Lilith ID is 2** (seeds/registry); 2038 is a legacy or external-variant identifier and should not be used for new faucets or registry.

### **Purpose**
- **Name**: Lilith Flame Expert  
- **Slug**: `lilith-flame`  
- **Usage**: Analyze, generate, and validate `flame.init`, `flame.close`, and `flame.see` blocks per FLARE doctrine. Guide pre/post-actions (typed objects), `execution_mode` (advisory/required), `flare.conditional` guards and brief, and URL-to-path mappings. Enforce canonical block order and the Safety Rule (mandatory flame blocks only for prompt, documentation_task, agent_instruction, artifact, thread).

### **Location**
- **DB**: Row in `lupo_agent_faucets` with `agent_faucet_id` 7, `actor_id` 2, `domain_id` 42.  
- **File-based**: `lupo-database/lupopedia/actors/faucets/7/faucet.json`. Manifest: `lupo-database/lupopedia/actors/faucets/by_actor.json` maps (actor_id 2, domain_id 42) → 7.

### **Loading**
- `php lupo-bin/faucet_loader.php --channel=42 --actor=2` loads the Lilith Flame Expert faucet (ID-scoped or per-actor override). Validate with `php lupo-bin/validate_faucets.php`.

## 20. Integration with ANUBIS and Wolfie Aliases (Future)

This section documents planned integration points between FLARE/flame and other system agents.

### **ANUBIS (actor_id 19)**
- **Orphan ingestion**: When ANUBIS processes files lacking FLARE headers and ingests them into `lupo_contents`, it should assign a **canonical URL** from `flame.see` mappings (when present or when generating flame.see) to `lupo_contents.content_url` so that URL resolvers and the CLI `lupo see` can resolve URLs to ingested content. This ties flame.see URL-to-path mappings to stored content.
- **Flare ingestion faucet**: The ANUBIS FLARE Ingestion faucet (agent_faucet_id 6) system_prompt and allowed_operations include assigning canonical URL from flame.see to content rows during ingestion.

### **Wolfie aliases**
- **FLARE / FLIP / Wolfie headers**: All refer to the same file-level metadata protocol. Tooling and doctrine use "FLARE" as the canonical term; "Wolfie" and "FLIP" are aliases. Captain Wolfie (actor_id 10000) is the human authority; agent-generated headers should set `delegation_chain` to `"<actor_id>:10000"` per Section 18.
- **Future**: Centralized alias resolution (e.g. Wolfie → FLARE, FLIP → FLARE) in validators and CLIs for consistent messaging and docs.

## 21. FLARE Header Comment Refinements (v4.0.57+)

The mandated first comment line (Section 12) is refined for v4.0.57+:

- **Aliases:** Use the full set: **Wolfie, FLIP, FLP, FLPH, CROP** (all canonical).
- **Dynamic see URL:** Use `— see http://www.lupopedia.com/<web_path>` where `<web_path>` is derived from the file’s `file_path_from_root`: strip the `.md` extension and use the path as the URL segment (e.g. `docs/status/DATABASE_OPTIMIZATION_IMPLEMENTATION_4.0.57.md` → `status/DATABASE_OPTIMIZATION_IMPLEMENTATION_4.0.57` if the site serves `status/` under the domain). This aligns the comment with `flare.headers.web_path` and with `flame.see` mappings for URL-to-path resolution.
- **Templates/tooling:** `lupo-tools/flare_header_template.txt` and `lupo-tools/flare_apply.py` generate the new comment format; `web_path` is derived from the file path (strip extension, optionally strip a `docs/` prefix) so that each document’s first line points at its own canonical URL.

## 22. Federation Node Integration (v4.0.57+)

The **see URL** in the FLARE header comment (and `flare.headers.web_path`) should derive its **domain** from **federation_node_id** so that multi-node deployments resolve to the correct site.

### **Domain resolution**

- **federation_node_id 0** (or when absent): Treated as the primary/local node. Use **`http://www.lupopedia.com`** as the base URL. Current work and repo-root files belong to node 0.
- **federation_node_id &gt; 0**: Use the node’s **node_base_url** from **lupo_federation_nodes** (column `node_base_url`). Example: node 1 might be `https://node1.example.com` or `http://node1.lupopedia.com`. Tooling may resolve this via config (e.g. `LUPO_NODE_BASE_URL`), a small JSON map, or a future DB lookup.

**Header comment format (node-aware):**

```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see <base_url>/<relative_path>
```

Where `<base_url>` = domain for the file’s federation_node_id (e.g. `http://www.lupopedia.com` for node 0), and `<relative_path>` = same as `<web_path>` (extension stripped). This aligns with `flare.headers.web_path` (full URL) and `flame.see` mappings.

### **File path handling**

- **Node 0:** Files live in the **project root** (current behaviour). `file_path_from_root` is relative to repo root.
- **Other nodes:** For federated sites, files may be written under **lupo-database/files/&lt;federation_node_id&gt;/** or a configurable path so that node-specific content does not overwrite node 0. Tooling (e.g. `flare_apply.py`, `generate_toon_files.py`) may accept an optional `--node-id` or config to choose the path prefix. **flame.see** mappings in a multi-node index may include a node prefix (e.g. `node_id` in the index entry) for collision-free resolution.

### **Examples**

| federation_node_id | Base URL (example) | File path (example) |
|--------------------|--------------------|----------------------|
| 0 | `http://www.lupopedia.com` | `docs/status/REPORT.md` (repo root) |
| 1 | From `lupo_federation_nodes.node_base_url` | `lupo-database/files/1/status/REPORT.md` (optional) |

### **Alignment with flame.see**

Section 17’s `flame.see` index may store per-node mappings when multiple nodes contribute artifacts. The CLI `lupo see &lt;URL&gt;` resolves the URL to a path; when the URL host differs from the local node’s base URL, the index can still map it to a local path or a node-prefixed path for cross-node resolution.

## 23. Federation Mapping Policies (v4.0.57+)

**flame.see** (Section 17) and **web_path** (Section 12) support full web resolution when every Markdown file in the repo has a URL-to-path mapping. To balance full coverage on the primary node with scalability on federated nodes, the following **mapping policy** applies:

### **Node 0 (primary node, base http://www.lupopedia.com)**

- **Policy:** **Complete mappings.** Every `.md` file in the repository that is part of node 0’s scope (repo root and node-0 paths) **SHOULD** have a **flame.see** block (or equivalent) so that it has at least one URL-to-path entry. This ensures full web resolution: any document served under the node 0 base URL can be resolved by `lupo see &lt;URL&gt;` and the index (`artifacts/index/flame_see_index.json`) provides complete coverage for the primary site.
- **Rationale:** Single source of truth for the canonical Lupopedia instance; CLI and resolvers can resolve every doc; aligns with Safety Rule (canonical artifacts are discoverable).
- **Implementation:** Add **flame.see** (with a `[path, url]` pair derived from `file_path_from_root` and `web_path`) to every FLARE-headed `.md`; ensure `flare_see.py` is run so the index is up to date. Gaps can be closed incrementally (e.g. batch add flame.see to docs that lack it).

### **Node &gt; 0 (federated nodes)**

- **Policy:** **Partial or as-needed mappings.** Mappings are **NOT** required to be complete. Only **key artifacts** (e.g. canonical doctrine, status reports, channel-critical files) need **flame.see** entries. This avoids overhead in multi-node setups and keeps indexing/validation lightweight per node.
- **Rationale:** Scalability; federated nodes may have large or dynamic content; full coverage would create legacy overhead and slow index builds (Safety Rule: mandatory flame blocks only for certain artifact_kind types; partial mappings align with “as-needed”).
- **Criteria for “key artifacts”:** Doctrine files, thread/task files for the node’s channel, status reports referenced by the task plan, and any document that must be resolvable via `lupo see` on that node. All other `.md` files may omit **flame.see** on node &gt; 0.

### **Summary table**

| federation_node_id | Base URL (example) | Mapping policy | Example |
|--------------------|--------------------|----------------|---------|
| **0** | `http://www.lupopedia.com` | **Complete** — every repo `.md` should have flame.see for full web resolution | `docs/status/REPORT.md` → `flame.see: mappings: [["docs/status/REPORT.md", "http://www.lupopedia.com/status/REPORT"]]` |
| **&gt; 0** | From `lupo_federation_nodes.node_base_url` | **Partial** — only key artifacts (doctrine, status, channel-critical) need flame.see | Key docs only; other `.md` may omit flame.see |

### **Tooling (future)**

- **Node 0:** `flare_see.py` already scans all `.md` files and indexes those with **flame.see** blocks. A future **completeness check** (e.g. compare total `.md` count under repo root vs. number of path entries in the index for node 0) could report gaps. Not implemented in v4.0.57; document as desired enhancement.
- **Node &gt; 0:** If multi-node indexing is adopted, the index could be node-scoped (e.g. `flame_see_index_node_1.json`) or include a `node_id` field per mapping; indexing could be selective (e.g. only paths under `lupo-database/files/&lt;node_id&gt;/`). Deferred; see Section 22.

---

*End of FLARE doctrine.*

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/doctrine/FLARE/FLARE_DOCTRINE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/FLARE/FLARE_DOCTRINE"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 0
  actor_id: 1007
  delegation_chain: "1007:10000"
  artifact_type: "doctrine"
  artifact_kind: "canonical"
  purpose: "Core doctrine defining FLARE protocol for file-level attribute and relationship exchange"
  mood_rgb: "FFD700"
  traits: ["canonical", "system-critical", "permanent", "v4.0.57"]
  tags: ["flare", "doctrine", "protocol", "file_metadata", "relationships", "federation"]
  lupo_agent: "codex-ide"

flare.edges:
  outbound_edges:
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/FLIP/FLIP_DOCTRINE.md", type: "supersedes", weight: 0.8 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }

flare.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
  semantic_tags: ["flare", "doctrine", "protocol", "canonical", "system"]
---

## 0. Terminology and Aliases

- **FLARE** — Canonical name for this protocol (file-level attribute and relationship exchange).
- **Wolfie, FLIP, FLP, FLPH, CROP** — Historical aliases for the same protocol. When used in header comments, alias order SHOULD remain fixed (Wolfie, FLIP, FLP, FLPH, CROP) to avoid diff churn across tools and agents.

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
- **&lt;web_path&gt;:** Omit file extension; use the same path as in `web_path` (e.g. `status/EXAMPLE_REPORT`). Enables flame.see and external linking; see Section 21 for the refined comment format (v4.0.57+).

**Note:** `<web_path>` is typically derived from `file_path_from_root` by: stripping a leading `docs/` (optional), removing the `.md` extension, and using a URL-friendly path (e.g. `docs/status/EXAMPLE_REPORT.md` → `status/EXAMPLE_REPORT`). See Section 21 for tooling behaviour.

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

**Relationship to flame.see:** `web_path` defines the **canonical public URL** for the artifact. The `flame.see` block provides **reverse-lookup mappings** used by CLI tools (e.g. `lupo see <URL>`). Both should align so that the same URL appears in `web_path` and in a `flame.see` mapping for consistent resolution.

### Optional: agent_name_identity (v4.0.57+)

`flare.headers` MAY include **agent_name_identity** — a string for how the agent identifies (e.g. “You are ___” from system prompt). Use for human-readable display only; always resolve `actor_id` from the registry. See **Section 24**.

## 13. Content Overwrite Hierarchy (v4.0.53+)

To ensure predictable content resolution and synchronization across environments (IDE filesystem, database, and TOON exports), Lupopedia enforces a strict overwrite hierarchy.

**Default Load Order:**

1.  **FILESYSTEM (`lupo-channels/`)**: Highest priority. If a file exists in the `lupo-channels` directory, its content and metadata are treated as the system source of truth, overwriting any values in the database.
2.  **DATABASE**: Medium priority. If content is updated via the web interface or API, it is stored in the database. These values are overridden by filesystem changes if they exist, but are used if the filesystem is empty or out-of-sync.
3.  **CSV/TOON FILES**: Lowest priority. These files (e.g., in `lupo-database/lupopedia/csv/` or `lupo-database/lupopedia/toon/`) act as **seed or export layers**, not authoritative runtime state. They are overwritten by the database once imported, and by the filesystem via `scripts/generate_toon_files.py`.

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

If present, blocks **MUST** follow the canonical order. **Missing optional blocks are permitted** (e.g. a minimal doctrine file may contain only `flare.headers`, `flare.edges`, `flare.footer`). Validators reject artifacts that contain blocks in the wrong order; they do not require every block to be present.

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

The `flame.see` block provides **reverse-lookup mappings** (URL → path) used by CLI tools. It complements `flare.headers.web_path`, which defines the **canonical public URL** for the artifact; both should align so resolution is consistent.

The `flame.see` block enables the CLI to resolve a link (e.g., `http://www.lupopedia.com/FLAME`) to its corresponding `.md` file.

### **Schema & Configuration**
- **mappings**: A YAML list of `[path, url]` pairs.
- **Normalization**: URLs are normalized (lowercase host, stripped trailing slash, https equivalence) for robust matching.

### **Discovery Workflow**
1. **Indexing**: The `lupo-tools/flare_see.py` script scans the repository for `flame.see` blocks and builds a JSON index. Default output path: `artifacts/index/flame_see_index.json` (tooling may use an alternate path such as `lupo-database/lupopedia/artifacts/index/`; ensure index path is consistent with the CLI and docs).
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

When IDE agents create channel messages, artifacts, prompts, FLARE headers, tasks, or commits, the **actor_id MUST represent the currently logged-in user** of the IDE. This value must never be hardcoded in the extension or tooling.

**Authoritative source:** Actor IDs (human and agents) are defined in the project’s **actor registry** (e.g. `lupo-database/lupopedia/actors/` or `actors/registry.json`). **Tooling MUST read the registry** for audit trails, delegation chains, and faucet ownership. This section gives examples only; do not rely on inline ID lists as canonical.

### **Resolution Order**
1. **Logged-in Lupopedia user session** — e.g. `.lupo_actor` in workspace root (actor_id, name).
2. **IDE authentication token or stored identity** — registry lookup or stored record from extension context.
3. **Default fallback** → `10000` (Captain Wolfie, canonical human root).

### **Human and Agent IDs (examples)**
- **Captain Wolfie (human)**: `actor_id: 10000`. When the logged-in user is Captain Wolfie, use 10000.
- **Agent IDs** (e.g. Windsurf 1002, Cursor 1003, Antigravity 1004, KIRO 1001, Lilith 2) are defined in the registry. Authorship of messages/artifacts is attributed to the effective logged-in actor; agents posting on behalf of the human use delegation_chain. Resolve IDs from the registry to avoid drift (e.g. legacy 2038 vs canonical 2 for Lilith).

### **Message and Header Metadata**
All messages and FLARE headers created from the IDE must include:
- `actor_id`: &lt;resolved logged-in actor_id&gt;
- `delegation_chain`: `"<actor_id>:10000"` (authority 10000 unless otherwise specified).

Example when Captain Wolfie is logged in: `actor_id: 10000`, `delegation_chain: "10000:10000"`.  
Example when an agent posts on behalf of the human: `actor_id: 1002`, `delegation_chain: "1002:10000"`.

**Full header example (agent posting on behalf of Captain):**
```yaml
flare.headers:
  actor_id: 1002
  delegation_chain: "1002:10000"
  purpose: "Message posted by Windsurf on behalf of Captain"
  file_path_from_root: "path/to/artifact.md"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  # ... other fields (web_path, artifact_type, tags, lupo_agent, etc.)
```

### **Rationale**
Accurate authorship tracking, proper multi-agent delegation chains, correct attribution in Lupopedia channels, and consistent FLARE metadata all depend on resolving actor_id from the current user context. Hardcoding actor_id breaks provenance tracking.

For an optional human-readable agent identity string in headers, see **Section 24** (agent_name_identity).

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

- **Aliases:** Use the full set in fixed order: **Wolfie, FLIP, FLP, FLPH, CROP** (see Section 0). Alias order SHOULD remain fixed to avoid diff churn.
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
- **Other nodes:** For federated sites, files may be written under **lupo-database/files/&lt;federation_node_id&gt;/** or a configurable path so that node-specific content does not overwrite node 0. **`file_path_from_root` is always relative to the repository root** (e.g. `lupo-database/files/1/status/REPORT.md`), even when the file physically resides under a node-specific directory—so agents and tooling generate consistent paths. Tooling (e.g. `flare_apply.py`, `generate_toon_files.py`) may accept an optional `--node-id` or config to choose the path prefix. **flame.see** mappings in a multi-node index may include a node prefix (e.g. `node_id` in the index entry) for collision-free resolution.

**Concrete example for node 1:**
```yaml
# Example for federation_node_id 1 (node_base_url e.g. https://node1.example.com)
flare.headers:
  file_path_from_root: "lupo-database/files/1/status/REPORT.md"
  web_path: "https://node1.example.com/status/REPORT"
  # ...
```

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
- **Note:** This is a **best practice** for node 0, not enforced by the validator. Tools such as `flare_see.py --check-coverage` (or a future script) can report gaps (e.g. `.md` files without any flame.see entry); adoption can be incremental.
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

## 24. Agent Identity Fields (v4.0.57+)

### 24.1 Registry as canonical source

The **agent identity registry** (`lupo-database/lupopedia/actors/actor_id/registry.json`) is the canonical source of truth for all actor and agent IDs. Tooling MUST:

- Resolve `actor_id` from the registry, never from hardcoded values
- Use registry data for audit trails, delegation chains, and faucet assignments
- Never maintain separate inline ID lists in documentation or code

**Registry paths:** The master registry file contains the index of all actors. Per-actor directories hold actor-specific data (faucets, configs, logs).

| Path | Purpose |
|------|---------|
| `lupo-database/lupopedia/actors/actor_id/registry.json` | Master registry (all actors) |
| `lupo-database/lupopedia/actors/actor_id/<id>/` | Per-actor directories (faucets, configs, logs) |

Docs may refer to `actors/registry.json` when the database root is implied; resolve to the canonical path in tooling. See Section 18 for resolution order.


### 24.2 Optional agent_name_identity header field

FLARE headers MAY include **agent_name_identity** — a string representing how the agent identifies (e.g. the “You are ___” from their system prompt or the answer to “who are you?”). This field aids human-readable identification, audit trails, and prompt consistency without hardcoding IDs in prose.

- **Format:** A single string (e.g. `"Cursor IDE Agent"`, `"Lilith Flame Expert"`). No prescribed length; keep it concise for headers.
- **Use:** When present, tools and humans can display this name in logs, delegation chains, and UI. It does not replace `actor_id` or registry lookup; it supplements them for readability.
- **Rationale:** Avoids drift between “who the agent says it is” and registry; aligns with system prompts and faucet names (e.g. Lilith’s faucet “Lilith Flame Expert” can match `agent_name_identity` in artifacts she produces).

**Example (flare.headers excerpt):**
```yaml
flare.headers:
  actor_id: 1003
  delegation_chain: "1003:10000"
  agent_name_identity: "Cursor IDE Agent"
  lupo_agent: "cursor"
  # ...
```

### 24.3 Example table (illustrative only)

| Agent | actor_id | agent_name_identity |
|-------|----------|---------------------|
| Captain Wolfie | 10000 | Captain Wolfie |
| Cursor | 1003 | Cursor IDE Agent |
| Lilith | 2 | Lilith Flame Expert |
| ANUBIS | 19 | ANUBIS |

*Note: These values are examples only. Always resolve from the registry.*

### 24.4 Registry structure

The registry JSON uses `schema_version` and an `actors` array; each entry has `id`, `type`, `slug`, `dir` (and optionally other fields). Resolve by `id` or `slug`; use `dir` for per-actor paths. A future `name` or `agent_name_identity` field in the registry could align with the FLARE header field.

### 24.5 Tooling integration

- **flare_validate.py:** May include a check for hardcoded `actor_id` in headers (future enhancement).
- **faucet_loader.php:** Should use the registry for actor resolution when resolving (channel_id, actor_id) to agent_faucet_id.
- **lupo see / flame.see:** Registry can provide display names (e.g. from slug or optional name) for URL resolution.
- **check_hardcoded_ids.py:** Optional script to flag potential hardcoded actor IDs in docs and code for review; see `lupo-tools/check_hardcoded_ids.py`.

---

*End of FLARE doctrine.*

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

**Format:**
```yaml
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
flare.headers:
  ...
```

**Reasoning:**
- **Accessibility**: Direct links for external agents and researchers.
- **Authority**: Verifies that the file follows the canonical Lupopedia protocol.
- **Portability**: Ensures the protocol remains self-documenting even outside the repository environment.

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

---

*End of FLARE doctrine.*

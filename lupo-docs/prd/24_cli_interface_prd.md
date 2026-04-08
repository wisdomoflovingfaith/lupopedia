---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260408113000"
  file_path_from_root: "lupo-docs/prd/24_cli_interface_prd.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/24_cli_interface_prd.md"
  last_modified_utc: "20260408113000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-cli-interface"
  prd_id: 24
  prd_slug: cli_interface
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: prd
  artifact_kind: specification
  purpose: "CLI interface for Lupopedia — identity resolution, orchestration, and memory operations (add, list, get, update, delete, export, archive, restore)"
  status: "draft"
  tags:
    - prd
    - cli
    - terminal
    - orchestration
    - memory
    - edges
    - actor
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/38_memory_unification.md"
      type: references
      weight: 1.0
      reason: "Memory unification — CLI memory commands use unified graph"
    - to: "lupo-docs/prd/23_health_check_asclepius_prd.md"
      type: references
      weight: 1.0
      reason: "doctor / doctor-context commands"
lupopedia.footer:
  last_verified: "20260408012727"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
  orchestrator: "cursor:root"
  next_action:
    - "Implement MemoryCommands.php with add, list, get, update, delete, export, archive, restore, edges"
    - "Update lupo-bin/lupo.php to route memory commands"
    - "Write integration tests for CLI memory operations"
    - "Document for IDE agents (Claude Code, Cursor, etc.)"
---

# PRD 24: CLI Interface (Expanded)

## 1. Constitutional Anchor

All rules and requirements in this PRD must strictly comply with `lupo-docs/prd/00_root_constitutional_system_requirements.md`.

---

## 2. Overview

This PRD outlines the requirements for the Lupopedia CLI subsystem, establishing how terminal operations handle identity context, task routing, and **memory operations** independently from the web layer.

**Primary use cases:**
- IDE agents (Claude Code, Cursor, Windsurf) adding/querying memory
- Operators inspecting actor memory without opening web UI
- Scripting memory export/import for backups or analysis
- Orchestrating multi-agent workflows

---

## 3. Canonical Entrypoint

All CLI commands **MUST** be routed through the singular canonical entrypoint:

```bash
php lupo-bin/lupo.php <command> [args]
```

No sub-systems or modules are permitted to create their own root-level executable `.php` bash alias entry points.

**Examples:**
```bash
php lupo-bin/lupo.php memory add --actor 1 --type observation --key "login_errors" --value '{"error":"Invalid password"}'
php lupo-bin/lupo.php memory list --actor 1 --type observation
php lupo-bin/lupo.php memory get --memory-id 12345
php lupo-bin/lupo.php memory export --actor 1 --format json
php lupo-bin/lupo.php edges add --from 12345 --to 67890 --type "supports"
```

---

## 4. Actor Context & Identity Resolution

Unlike the web layer which relies exclusively on typical browser session variables bound to `lupo_sessions` and `lupo_auth_users`, the CLI resolves identity by traversing the following local paths:

1. **`session.md`** — The local file-based context representing the active orchestration session
2. **`.lupo_actor`** — Secondary local file tracking immediate actor switches
3. **Database & Registry** (`lupo_sessions` / `registry.json`) — Ultimately bind the local text state to an authoritative actor ID

The CLI must natively support a dual-identity footprint, simultaneously displaying the authenticated Human invoking the command alongside the AI/IDE Agent driving the API pipeline.

### 4.1 Session File Format: `session.md`

```markdown
---
actor_id: 1
actor_name: WOLFIE
agent_key: cursor
agent_name: Cursor IDE Agent
session_started: 20260407120000
last_activity: 20260407130000
---
```

### 4.2 Switch Actor

```bash
php lupo-bin/lupo.php use --actor 1
php lupo-bin/lupo.php switch --actor 2
```

---

## 5. Memory Commands

### 5.1 Add Memory Node

```bash
php lupo-bin/lupo.php memory add \
    --actor 1 \
    --type observation \
    --key "kairos:observation:login_errors" \
    --value '{"error":"Invalid password","count":3,"last_attempt":"20260407120000"}' \
    --context experiential \
    --status unsupported
```

**Options:**

| Option | Required | Description |
|--------|----------|-------------|
| `--actor` | Yes | Owner actor_id |
| `--type` | Yes | root, observation, consolidated, working, doctrine |
| `--key` | Yes | Unique key for this memory (e.g., "kairos:observation:login_errors") |
| `--value` | Yes | Memory content (JSON string or plain text) |
| `--context` | No | doctrine, experiential, system_generated, countermeasure_generated, summary, contradictory, deprecated (default: experiential) |
| `--status` | No | unsupported, supported, needs_review (default: unsupported) |
| `--expires` | No | Expiration timestamp (YYYYMMDDHHIISS UTC) |

**Output:**
```
Memory added: memory_id = 1234567890123456
Export written: lupo-memory/2026/04/20260407_130000_actor_1_observation_kairos_observation_login_errors.json
```

### 5.2 List Memory Nodes

```bash
php lupo-bin/lupo.php memory list \
    --actor 1 \
    --type observation \
    --status supported \
    --limit 20
```

**Options:**

| Option | Required | Description |
|--------|----------|-------------|
| `--actor` | Yes | Owner actor_id |
| `--type` | No | Filter by memory_type |
| `--key` | No | Filter by memory_key (partial match) |
| `--context` | No | Filter by context |
| `--status` | No | Filter by status |
| `--limit` | No | Max rows (default: 50) |
| `--offset` | No | Pagination offset (default: 0) |

**Output (table):**
```
+------------------+----------------+----------------------------------------+---------------------+
| memory_id        | type           | key                                    | created             |
+------------------+----------------+----------------------------------------+---------------------+
| 1234567890123456 | observation    | kairos:observation:login_errors        | 2026-04-07 13:00:00 |
| 1234567890123457 | observation    | kairos:observation:auth_success        | 2026-04-07 12:30:00 |
+------------------+----------------+----------------------------------------+---------------------+
```

### 5.3 Get Memory Node

```bash
php lupo-bin/lupo.php memory get --memory-id 1234567890123456
```

**Output (JSON):**
```json
{
    "memory_id": 1234567890123456,
    "owner_actor_id": 1,
    "owner_type": "actor",
    "memory_type": "observation",
    "memory_key": "kairos:observation:login_errors",
    "memory_value": {
        "error": "Invalid password",
        "count": 3,
        "last_attempt": "20260407120000"
    },
    "context": "experiential",
    "status": "unsupported",
    "review_reason": null,
    "context_json": null,
    "created_ymdhis": "20260407130000",
    "updated_ymdhis": "20260407130000",
    "expires_ymdhis": 0,
    "edges": [
        {
            "edge_id": 9876543210987654,
            "edge_type": "observed_by",
            "from_memory_id": 1234567890123456,
            "to_memory_id": 1111111111111111,
            "edge_context": "system_generated",
            "edge_status": "supported",
            "edge_direction": "unidirectional"
        }
    ]
}
```

### 5.4 Update Memory Node

```bash
php lupo-bin/lupo.php memory update \
    --memory-id 1234567890123456 \
    --value '{"error":"Invalid password","count":4,"last_attempt":"20260407130000"}' \
    --status supported
```

**Options:**

| Option | Required | Description |
|--------|----------|-------------|
| `--memory-id` | Yes | Memory ID to update |
| `--value` | No | New memory value |
| `--context` | No | New context |
| `--status` | No | New status |
| `--review-reason` | No | Set review_reason |

### 5.5 Delete Memory Node (Soft Delete)

```bash
php lupo-bin/lupo.php memory delete --memory-id 1234567890123456
```

**Output:**
```
Memory 1234567890123456 soft-deleted (is_deleted=1)
Export file removed: lupo-memory/2026/04/20260407_130000_actor_1_observation_....json
```

### 5.6 Export Memory to Filesystem

```bash
php lupo-bin/lupo.php memory export --actor 1 --output-dir ./memory-backup
```

**Options:**

| Option | Required | Description |
|--------|----------|-------------|
| `--actor` | No | Export specific actor (omit for all) |
| `--output-dir` | Yes | Directory to write JSON files |
| `--since` | No | Only export nodes updated after timestamp |
| `--format` | No | json, jsonl, csv (default: json) |

**Output:**
```
Exported 47 memory nodes to ./memory-backup/
  - actor_1_root.json
  - actor_1_observations.json
  - actor_2_root.json
  ...
```

### 5.7 Import Memory from Filesystem

```bash
php lupo-bin/lupo.php memory import --input-dir ./memory-backup --actor 1
```

**Output:**
```
Imported 47 memory nodes from ./memory-backup/
  - Skipped (already exists): 12
  - Created: 35
  - Errors: 0
```

### 5.8 Archive Memory (Option B — PRD 38 §8)

Long-term archive moves a **runtime-shaped** memory node (embedded year **2000–2099**, raw **`IdGenerator`**) into the **1000–1999** embedded-year band via **`toCanonicalId`** / **`toLongTermId`**, creating a **new** row (see **PRD 38** §8). Commands:

```bash
# Archive a single memory node
php lupo-bin/lupo.php memory archive --memory-id 202604081200001234

# Archive all memory for an actor older than N days
php lupo-bin/lupo.php memory archive --actor 116 --older-than 90

# Preview without actually archiving
php lupo-bin/lupo.php memory archive --actor 116 --older-than 90 --dry-run
```

| Option | Required | Description |
|--------|----------|-------------|
| `--memory-id` | One of `--memory-id` or actor scope | Runtime-shaped **`memory_node_id`** to archive (embedded year **2000–2099**) |
| `--actor` | With bulk archive | **`owner_actor_id`** filter |
| `--older-than` | With `--actor` | Age in days (compares **`created_ymdhis`** or PK prefix vs “now”) |
| `--dry-run` | No | List candidates only; no DB writes |

**Output (example):** `Archived 202604081200001234 → 102604081200001234; edge archived_to created; export lupo-memory/1026/04/...`

### 5.9 Restore from Archive (reverse of §5.8)

```bash
php lupo-bin/lupo.php memory restore --memory-id 102604081200001234
```

Restores a **long-term** id (first four digits **1000–1999** on **18-digit** ids) to the corresponding **runtime-shaped** band (add **1000** to embedded year), with **`restored_from`** / companion edge per implementation spec in **PRD 38 §8.2**.

---

## 6. Edge Commands

### 6.1 Add Edge Between Memory Nodes

```bash
php lupo-bin/lupo.php edges add \
    --from 1234567890123456 \
    --to 6789012345678901 \
    --type "supports" \
    --context system_generated \
    --direction unidirectional \
    --weight 0.85
```

**Options:**

| Option | Required | Description |
|--------|----------|-------------|
| `--from` | Yes | Source memory_id |
| `--to` | Yes | Target memory_id |
| `--type` | Yes | influences, inherits, authored_by, observed_by, contradicts, supports, consolidates_from, refines, overrides, abbreviates, archived_to, restored_from |
| `--context` | No | doctrine, experiential, system_generated, countermeasure_generated, summary, contradictory, deprecated, lossy_abbrev |
| `--direction` | No | unidirectional, bidirectional, restricted-direction (default: unidirectional) |
| `--weight` | No | 0.00 to 1.00 (default: 1.00) |
| `--provenance-tool` | No | Tool name (default: "cli/memory") |

### 6.2 List Edges for a Memory Node

```bash
php lupo-bin/lupo.php edges list --memory-id 1234567890123456
```

### 6.3 Delete Edge

```bash
php lupo-bin/lupo.php edges delete --edge-id 9876543210987654
```

---

## 7. Query Commands (Graph Traversal)

### 7.1 Find Related Memory

```bash
php lupo-bin/lupo.php memory related --memory-id 1234567890123456 --max-depth 2
```

Returns all memory nodes connected via edges up to specified depth.

### 7.2 Find Contradictions

```bash
php lupo-bin/lupo.php memory contradictions --actor 1 --topic "login_errors"
```

Returns all memory nodes with `edge_type = 'contradicts'` for a given actor/topic.

### 7.3 Search Memory

```bash
php lupo-bin/lupo.php memory search --query "login error" --actor 1 --limit 20
```

Searches `memory_value` and `memory_key` for the query string.

---

## 8. Core Operational Requirements

### 8.1 Required Commands

| Command | Purpose | PRD Reference |
|---------|---------|---------------|
| `doctor` / `doctor-context` | Health check system | PRD 23 (ASCLEPIUS) |
| `whoami` | Output current dual-identity (Human + Agent) | This PRD |
| `context` | Show active session mode and actor | This PRD |
| `channels` | List available database coordination points | This PRD |
| `threads` | List threads in a channel | This PRD |
| `use` / `switch` | Transition terminal's actor identity | This PRD |
| `memory add` | Add memory node | This PRD (expanded) |
| `memory list` | List memory nodes | This PRD (expanded) |
| `memory get` | Get memory node by ID | This PRD (expanded) |
| `memory update` | Update memory node | This PRD (expanded) |
| `memory delete` | Soft-delete memory node | This PRD (expanded) |
| `memory export` | Export memory to filesystem | This PRD (expanded) |
| `memory import` | Import memory from filesystem | This PRD (expanded) |
| `memory archive` | Long-term archive (Option B, PRD 38 §8) | This PRD §5.8 |
| `memory restore` | Restore from archive band | This PRD §5.9 |
| `edges add` | Add edge between memory nodes | This PRD (expanded) |
| `edges list` | List edges for a memory node | This PRD (expanded) |
| `edges delete` | Delete edge | This PRD (expanded) |

### 8.2 Example: `whoami` Output

```
=== Lupopedia CLI Identity ===
Human Actor:
  Actor ID: 1
  Actor Name: WOLFIE
  Department: 0 (Root / Real Programmers)
  Session: active since 2026-04-07 12:00:00 UTC

Agent / IDE:
  Agent Key: cursor
  Agent Name: Cursor IDE Agent
  Faucet: cursor

Current Mode: orchestration
Active Channel: 42 (Development)
Active Thread: prd-38-memory-unification
```

### 8.3 Example: `context` Output

```
=== Active Context ===
Session File: session.md
Actor File: .lupo_actor (points to actor_id 1)
Database Session: lupo_sessions.session_id = "abc123..."
Registry: lupo-database/lupopedia/actors/registry.json

Active Memory Root: memory_id = 1234567890123456 (actor 1 root)
Memory Export Path: lupo-memory/2026/04/20260407_120000_actor_1_root_actor_root_context.json
```

---

## 9. Implementation Requirements

### 9.1 Command Routing

```php
// lupo-bin/lupo.php
$command = $argv[1] ?? 'help';
$subcommand = $argv[2] ?? null;

switch ($command) {
    case 'memory':
        $memoryCommands = new MemoryCommands();
        $memoryCommands->run($subcommand, array_slice($argv, 3));
        break;
    case 'edges':
        $edgeCommands = new EdgeCommands();
        $edgeCommands->run($subcommand, array_slice($argv, 3));
        break;
    case 'whoami':
        $identity = new CliIdentity();
        echo $identity->whoami();
        break;
    // ... other commands
}
```

### 9.2 MemoryCommands Class

```php
// lupo-includes/classes/cli/MemoryCommands.php
class MemoryCommands {
    public function add($args) { /* ... */ }
    public function list($args) { /* ... */ }
    public function get($args) { /* ... */ }
    public function update($args) { /* ... */ }
    public function delete($args) { /* ... */ }
    public function export($args) { /* ... */ }
    public function import($args) { /* ... */ }
    public function search($args) { /* ... */ }
    public function related($args) { /* ... */ }
    public function contradictions($args) { /* ... */ }
}
```

### 9.3 Auto-Export on Write

Every `memory add` and `memory update` MUST automatically trigger `MemoryExportService::exportNode()` so the filesystem mirror stays in sync.

---

## 10. IDE Agent Integration

### 10.1 Claude Code Usage Examples

Claude Code can call these commands via `execute_command`:

```bash
# Add observation from chat
php lupo-bin/lupo.php memory add --actor 1 --type observation --key "chat:user_feedback" --value '{"user":"Eric","feedback":"Likes the eye animation"}'

# Query memory before answering
php lupo-bin/lupo.php memory list --actor 1 --type observation --limit 10

# Get full context for an actor
php lupo-bin/lupo.php memory get --memory-id $(php lupo-bin/lupo.php memory list --actor 1 --type root --format id-only)

# Find contradictions to resolve
php lupo-bin/lupo.php memory contradictions --actor 1 --topic "database_schema"
```

### 10.2 Recommended Aliases for Claude

Add to Claude's environment or a wrapper script:

```bash
alias lupo-mem='php /path/to/lupo-bin/lupo.php memory'
alias lupo-edge='php /path/to/lupo-bin/lupo.php edges'
alias lupo-who='php /path/to/lupo-bin/lupo.php whoami'
```

---

## 11. Success Criteria

| Criterion | Validation |
|-----------|------------|
| All memory commands work via CLI | Integration tests pass |
| CLI memory writes auto-export to filesystem | `lupo-memory/` updated after each command |
| IDE agents (Claude) can add/query memory | Manual test with Claude Code |
| Dual identity (Human + Agent) displayed correctly | `whoami` command output verified |
| Session state persists across commands | `session.md` updated correctly |
| Edge operations create graph relationships | Query returns correct edges |

---

## 12. References

- **PRD 23** — ASCLEPIUS health check (`doctor` command)
- **PRD 38** — Memory unification (database + export mirror)
- **PRD 00 §5.7** — Memory consolidation doctrine
- **PRD 07** — Agent/faucet identity

---

**Status:** DRAFT — awaiting review

**Next actions:**
1. Review CLI memory command design
2. Approve PRD 38 first (memory unification tables)
3. Implement MemoryCommands.php
4. Update lupo-bin/lupo.php
5. Test with Claude Code

---

This gives Claude (and other IDE agents) a clean, command-line interface to add/query/update memory, while the database remains source of truth and filesystem remains readable mirror.

Shall I continue with PRD 38 (Memory Unification) now, or refine this CLI PRD further?
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/report_on_database_tables_antigravity.md"
  system_version: "4.0.73"
  namespace: "core"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260313"
  artifact_type: "report"
  artifact_kind: "audit"
  purpose: "Comprehensive audit of database table documentation, TOON transferability, and orchestrator rule integration."
  traits: ["canonical", "audit", "governance", "v4.0.73"]
  tags: ["audit", "database", "doctrine", "orchestrator", "rules"]
  lupo_agent: "antigravity"

lupopedia.init:
  orchestrator_actor: "antigravity"
  rule_set_version: "4.0.73+"
  applies_to: ["audit", "report-generation"]
  enforcement: strict

lupopedia.edges:
  comment: "Snapshot of audit findings for v4.0.73. References the canonical schema and orchestrator rule set."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "audits_schema", weight: 1.0 }
    - { to: "lupo-rules/root/", type: "audits_rules", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/", type: "audits_toons", weight: 1.0 }

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
  next_action:
    - "Update lupo_actors.md to Correct Primary Key (actor_name)"
    - "Integrate lupopedia.init block into all lupo-rules/root/*.md files"
    - "Implement Create One-Time Migration for lupo_orchestrator_rules table"
---

# file: Audit Report: Database Tables & Orchestrator Rules — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/status/report_on_database_tables_antigravity

# Audit Report: Database Tables & Orchestrator Rules (v4.0.73)

## 1. Executive Summary

This audit evaluates the consistency and transferability of Lupopedia's database documentation, TOON files, and the newly established Orchestrator Rule protocol. 

**Findings:**
- **Status:** The system is **Strong** in its schematic definition (SQL + TOON) but **Weak** in documentation-level synchronization and rule-logic transferability.
- **Critical Drift:** `lupo_actors.md` documentation conflicts with the canonical SQL/TOON regarding the Primary Key.
- **Rule Integration:** Orchestrator rules in `/lupo-rules/root/` lack the mandatory `lupopedia.init` block.
- **Transferability:** Database-to-File rehydration is possible for schema, but rule logic is currently trapped in Markdown files, preventing the DB from being the sole canonical source for IDE agents.

**Transferability Assessment:** "Current structure is sufficient for DB-backed metadata retrieval AND for rule enforcement by any IDE orchestrator: **NO** (Logic transfer pending)."

---

## 2. Orchestrator Rule Transferability & IDE Support

### Current State
Orchestrator rules are stored in `\lupo-rules\root\` as Markdown files. The database (`lupo_metadata`) only stores the **mapping** (file paths) but not the actual **content** of the rules. This forces IDE agents to rely on filesystem access to resolve doctrine, violating the goal of a database-centric "Semantic OS."

### IDE Support Requirement
For a future IDE agent (Cursor, Windsurf, etc.) to load these rules automatically, they must be queryable via standard SQL or REST API without requiring a local file read of the `lupo-rules/` directory.

### Concrete Proposal: `lupo_orchestrator_rules`
To satisfy the 4.0.73+ doctrine, I propose the creation of a dedicated table for rule logic:

**Table name:** `lupo_orchestrator_rules`
- `rule_id` (BIGINT) - Primary Key
- `rule_slug` (VARCHAR(128)) - e.g., 'php-5-3-compatibility'
- `orchestrator_actor` (VARCHAR(64)) - e.g., 'cursor', 'windsurf', 'any'
- `rule_set_version` (VARCHAR(32)) - e.g., '4.0.73'
- `applies_to_json` (JSON) - e.g., `["audit", "code-gen"]`
- `enforcement_level` (VARCHAR(32)) - 'strict', 'warning'
- `rule_content` (TEXT) - The actual Markdown body and frontmatter
- `checksum` (VARCHAR(64)) - To detect drift between DB and File
- `is_active` (TINYINT)
- `updated_ymdhis` (BIGINT)

**Benefit:** IDE agents can fetch all "strict" rules for their identity in a single query:
`SELECT rule_content FROM lupo_orchestrator_rules WHERE (orchestrator_actor = 'antigravity' OR orchestrator_actor = 'any') AND is_active = 1;`

---

## 3. Database Table & TOON Audit

### 3.1 Schema Drifts
| Table | Discrepancy Found | Severity | Action Required |
|-------|-------------------|----------|-----------------|
| `lupo_actors` | `.md` doc lists `actor_id` as PK; DDL/TOON list `actor_name` as PK. | **High** | Update `lupo_actors.md` to reflect `actor_name` as primary per v4.0.58 doctrine. |
| `lupo_edges` | No significant drift; `edge_category` support is correctly reflected. | Low | None. |

### 3.2 TOON Transferability
TOON files remain the best bridge for schema rehydration.
- **Strength:** Fields and types are deterministic.
- **Weakness:** Sample data (`data: []`) is often empty, preventing representative rehydration of "Seeded" states solely from TOONs.

---

## 4. LUPOPEDIA HEADERS Audit

### 4.1 Block Compliance
- **Core Files:** Files like `index.php` use legacy `@wolfie.headers` docblocks. While accepted by current validators, they do not provide the structural YAML depth of standalone `.md` table docs.
- **Rule Files:** All 17 files in `lupo-rules/root/` lack the `lupopedia.init` block.

### 4.2 Mandatory Snapshot Requirement
The `lupopedia.edges` and `lupopedia.engagement` blocks increasingly include the mandatory "snapshot" comment, which is a significant improvement in managing expectations about "live" data vs "documented" data.

---

## 5. Final Recommendations

1.  **Rule Initialization:** Immediately update all files in `lupo-rules/root/` to include the `lupopedia.init` block defining their orchestrator relevance.
2.  **Schema Correction:** Resolve the `lupo_actors` Primary Key documentation error.
3.  **Migration Path:** Generate a one-time migration to create `lupo_orchestrator_rules` and a script to sync `lupo-rules/root/*.md` CONTENT (not just paths) into it.
4.  **Namespace Hardening:** Continue the expansion of table documentations into the `session`, `org`, and `federation` namespaces.

---

**Audit Status:** Complete.
**Verdict:** Solid foundation; metadata rehydration logic needs implementation.

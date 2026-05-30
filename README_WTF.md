---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: README_WTF.md
  web_path: https://www.lupopedia.com/lupopedia/README_WTF.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/root/canonical/1026/04/readme-wtf.toon
  atoms_toon: lupo-memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/root/readme-wtf
  artifact_type: doctrine
  artifact_kind: philosophy
  channel_key: root
  federation_node_id: 0
  thread_key: lupopedia-wtf
  lupopedia.schema: doctrine
  prd_cluster: 00_A_00_B
  title: README_WTF.md — Lupopedia Canonical Doctrine
  summary: Canonical system philosophy and architectural mandate for Lupopedia. Enforces strict survival constraints, Y2038-proof timestamps, no-FK doctrine, and multi-factor session identity.
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->

# README_WTF.md

**Canonical Reference:** `lupo-memory/development/canonical/1026/04/readme-wtf-md.toon`

## 1. Database Philosophy

**The database is dumb storage. All logic belongs in the application layer (PHP).**

The database is not the source of truth. It is an index. Truth resides in files and application logic.

Lupopedia operates in federated and constrained environments where database-level complexity is a point of failure. The following rules are absolute:

*   **NO Foreign Keys (FK):** Data must be mergable across federation nodes without constraint violations.
*   **NO Triggers or Stored Procedures:** Logic must be visible, version-controlled, and debuggable in the PHP source.
*   **NO `AUTO_INCREMENT`:** Use `IdGenerator::generate()` for all primary keys to ensure global uniqueness across nodes.
*   **Deterministic Inserts:** `INSERT INTO table (col1, col2) VALUES (...)` is mandatory. Positional inserts are forbidden.
*   **Soft Delete Mandatory:** All tables must implement `is_deleted` and `deleted_ymdhis`.
*   **No Hidden State:** The database does not "know" anything; it only persists what the application tells it.

### Deferred Integrity vs Foreign Keys (Lupopedia Position)

Many developers default to foreign keys because they are the standard pattern, without examining whether they fit systems that prioritize:

*   asynchronous repair
*   soft deletion
*   graph relationships
*   survivability under degraded conditions

Lupopedia follows a different model.

It is not anti-integrity.
It uses **application-layer, deferred integrity**.

In this model:

*   Referential integrity is validated outside the database, not enforced at write time
*   Orphans are not automatically treated as corruption; they may represent transient states awaiting reconciliation
*   Relationship correctness is restored through asynchronous repair (for example, Anubis queue processing), not blocking constraints

Foreign keys are not universally bad.

However, they impose write-time coupling, rigid dependency ordering, and destructive cascade behaviors that conflict with Lupopedia's architecture.

For this system:

> Foreign keys are incompatible with the doctrine of deferred integrity, soft deletion, and survivability-first design.

This is an intentional architectural decision and must not be altered.

| Pattern | Mandate | Rationale |
| :--- | :--- | :--- |
| **Identity** | `IdGenerator` | Federation compatibility |
| **Logic** | PHP Application | Visibility and Portability |
| **Timestamps** | `BIGINT YYYYMMDDHHIISS` | Y2038 Safety & Sortability |
| **Schema** | `utf8mb4` | Database compatibility |
| **Data Files** | ASCII-Only | Deterministic tooling, no encoding drift |

Database encoding supports Unicode, but system artifacts (files, logs, documentation) are strictly ASCII-only.

---

## 2. Identity & Time Model

**Time is a calendar label, not a count of seconds.**

Lupopedia rejects the Unix epoch (32-bit signed integer) to avoid the Y2038 overflow. All timestamps use the Packed Decimal UTC format.

*   **Format:** `YYYYMMDDHHIISS` (e.g., `20260414150000`).
*   **Storage:** `BIGINT` in MySQL.
*   **Sorting:** Natural integer sorting is preserved.
*   **Range:** Valid until the year 9999.
*   **Timezone Discipline:** Database stores UTC moment (WHEN). Application handles display offset (WHERE). Never store local time.

**Identity Doctrine:**
*   **Column Naming:** `{table_name}_id` is mandatory (e.g., `actor_id`). Never use generic `id`.
*   **Base Session Identity:** Deterministic SHA256 hash of `Class C IP + user_id + user_agent`.
*   **Identity Stability:** The base hash must be stable before and after login. `actor_id` and `auth_user_id` are layered on top and MUST NOT be part of the base fingerprint.

---

## 3. Architecture Philosophy

**Survival over Fashion. Clarity over Convention.**

The architecture is dictated by the constraints of shared hosting (no Composer, no Node.js, no shell access, no background workers).

*   **Zero External Dependencies:** The shipped runtime must have zero dependencies. No frameworks, no ORM, no vendor directories.
*   **Explicit Execution:** Every request path must be traceable from top to bottom starting at `bootstrap.php`. No magic routing. No hidden middleware.
*   **Concrete Services:** Use simple, focused service classes. Avoid over-abstraction and "just-in-case" patterns.
*   **Deterministic Behavior:** If the code is not predictable from a visual inspection of the source, it is defective.
*   **No "Temporary" Hacks:** "Temporary" code is permanent. Implement the correct solution the first time.

---

## 4. UI Philosophy

**The UI is a Command Center, not a Social Feed.**

Lupopedia is an operator command center for AI agent orchestration. Social patterns (bubbles, grouping, threads) are forbidden as they destroy temporal context and operational clarity.

*   **Single-Column Feed:** All actors (Human, AI, System) interleave in one chronological stream.
*   **Chronological Contract:** Strict order from oldest (top) to newest (bottom). No grouping by sender.
*   **Monospace Utility:** The feed is a log. Monospace fonts are mandatory for alignment and readability.
*   **Threaded Nesting Forbidden:** Do not split the flow. Context is maintained through the unified timeline.
*   **Individual Message Lines:** Every event is a distinct line with a UTC timestamp and actor identification.

---

## 5. Transport Model

**One-Way Promotion. Session Lock-In.**

The transport layer adapts to the host's capability via a deterministic negotiation chain.

1.  **Capability Negotiation:** At session start, the client probes for the highest supported transport (Flush -> XMLHTTP -> Meta-Refresh -> Manual Sync).
2.  **Configured Chain:** The order is defined by `$CSLH_Config['chatmode']`.
3.  **One-Way Promotion:** Once a higher transport is proven (e.g., 200 OK from an XMLHTTP ping), the session is promoted.
4.  **Session Lock-In:** The promoted mode is stored in the database (`chattype`) and locked for the session duration.
5.  **No Mode Bouncing:** Continuous switching between modes is forbidden as it corrupts cursor state (`after_ymdhis`) and duplicates data.

---

## 6. Survival Philosophy

**Resist the urge to generalize until generalization is proven by repeated use.**

Lupopedia is built to outlast the current generation of web frameworks. It survives by removing points of failure common to modern web development.

*   **Dependency Immunity:** By shipping zero dependencies, Lupopedia is immune to dependency rot and package abandonment.
*   **Hostile Environment Adaptation:** ASCII-only data files (TOON, JSON, YAML) ensure portability across systems with varying encoding defaults. Grep-friendly, import-safe.
*   **Idempotent Operations:** System consistency is maintained via idempotent application logic rather than fragile database transactions.
*   **Maintenance via Opportunistic GC:** In environments without reliable cron, system cleanup (sessions, logs, cache) runs opportunistically (~1% trigger rate per request).

**Mandate:** If a 30-line function works, do not refactor it into a 300-line service layer. Specific, working code is superior to generic, unproven abstraction.

**Enforcement:**

- **[AGENTS.md](AGENTS.md)** defines execution rules.
- Validators enforce header and structure compliance.
- Violations are logged and corrected incrementally.

---
**This is the System Constitution. Deviations will be flagged by THOTH.**

---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/architecture/federation_interpreter_rules.md
  web_path: https://www.lupopedia.com/lupopedia/docs/architecture/federation_interpreter_rules.md
  status: active
  when_updated: '20260513125206'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/federation_interpreter_rules.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/federation-interpreter-rules-v1
  artifact_type: documentation
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: federation-interpreter-rules-v1
  lupopedia.schema: documentation
  prd_cluster: 34_A
  title: Lupopedia federation interpreter rules (federation_nodes v1.0)
  summary: 'Normative rules plus ordered Section 11 checklist (VALID or DEGRADED or INVALID) for {{prefix}}federation_nodes; aligned with install_new_lupopedia.sql and federation.md.'
---
# Lupopedia federation interpreter rules

**Version 1.0** -- aligned with [federation.md](federation.md) and `database/lupopedia/mysql/install/install_new_lupopedia.sql` (table `{{prefix}}federation_nodes`).

**Protocol and graph scope:** [PRD 34: Federation node semantic network](../prd/34_A-i_FEDERATION_NODE_SEMANTIC_NETWORK.md).

---

## 1. Scope

- **Object:** `{{prefix}}federation_nodes` table.
- **Context:** Any validator, installer, upgrader, or runtime process that:
  - creates rows,
  - updates rows,
  - reads rows to make federation decisions.

Interpreter rules are normative. SQL and seed data should converge to these rules over time.

**Note (4.0.x install doctrine):** Until **4.1.0**, the supported product path is fresh install from canonical install + seed, not Lupopedia-to-Lupopedia upgrades. Sections that mention "upgrade from older schemas" apply to **repair tools**, importer-adjacent fixes, and **future** upgrade paths once they exist.

---

## 2. Required columns (from DDL)

The install DDL defines additional columns (`node_type`, soft-delete fields, counters, `capabilities`, etc.). For **interpreter v1.0**, the following columns **must** exist with semantics below (names from `install_new_lupopedia.sql`):

| Column | Install type (MySQL) | Interpreter use |
|--------|----------------------|-------------------|
| `federation_node_id` | `bigint NOT NULL` | Primary key; non-negative integer semantics. |
| `base_url` | `varchar(500) NOT NULL` | Public origin (scheme + host + optional path). |
| `node_name` | `varchar(255)` | Human label; may be NULL in raw DDL but v1 rules require non-empty for nodes 0 and 1 (see sections 4--5). |
| `trust_level` | `tinyint NOT NULL DEFAULT 0` | Numeric trust tier (installation-defined scale). |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` | **Packed UTC** fourteen-digit `YYYYMMDDHHIISS` (PRD 00 timestamp doctrine), not Unix epoch. |
| `status` | `varchar(32) DEFAULT 'active'` | Coarse lifecycle / system state string. |
| `meta_json` | `json` | Optional structured metadata; NULL or valid JSON document. |

If any of these columns are missing, the interpreter must treat the installation as **incompatible** with federation v1.0.

**Soft delete:** The table also has `is_deleted` and `deleted_ymdhis`. Unless a call site explicitly audits history, the interpreter should treat **only rows with `is_deleted = 0`** as participants in active federation configuration. Rows 0 and 1 must not be soft-deleted in a healthy install.

---

## 3. Global invariants

- **INV-1:** `federation_node_id` is unique per row (enforced by primary key).
- **INV-2:** `federation_node_id` is non-negative.
- **INV-3:** There must be exactly **one** active row (`is_deleted = 0`) with `federation_node_id = 0`.
- **INV-4:** There must be exactly **one** active row (`is_deleted = 0`) with `federation_node_id = 1`.
- **INV-5:** `base_url` must be non-empty for all rows interpreted as active configuration.
- **INV-6:** `base_url` must be a valid **absolute** URL (**scheme + host**; path allowed). Relative values such as `/` are **invalid** for v1.0 interpreter passes that enforce sections 4--5.
- **INV-7:** `updated_ymdhis` must be a valid fourteen-digit packed UTC time (and within range checks the interpreter uses elsewhere for `YYYYMMDDHHIISS`).
- **INV-8:** `meta_json`, when not SQL NULL, must be a valid JSON value the driver can parse (object, array, string, number, boolean). Empty or unknown shapes are allowed; see section 8.

The interpreter must **fail** validation (INVALID) if any invariant material to the checked scope is violated.

---

## 4. Node 0 rules (canonical root)

- **N0-1:** Row with `federation_node_id = 0` must exist and be active (`is_deleted = 0`).
- **N0-2:** `base_url` for node 0 must be exactly:
  - `https://www.lupopedia.com`
- **N0-3:** `node_name` for node 0 must be non-empty; recommended:
  - `"Lupopedia Canonical Root"` or equivalent.
- **N0-4:** `status` for node 0 must be one of:
  - `"active"`, `"system"`, or another **centrally defined** reserved system status string documented with PRD 34 / ops runbooks.
- **N0-5:** `trust_level` for node 0 must be the **highest** trust tier used by the system (implementation-specific constant, for example `100`).
- **N0-6:** Interpreter must not allow **hard** deletion of node 0; soft-delete should be treated as INVALID for this row.
- **N0-7:** Interpreter must not allow `base_url` of node 0 to be changed away from the canonical `https://www.lupopedia.com` except under an explicit, audited migration procedure.

If any of these rules fail, the interpreter must mark federation configuration as **INVALID**.

---

## 5. Node 1 rules (installation identity)

- **N1-1:** Row with `federation_node_id = 1` must exist and be active (`is_deleted = 0`).
- **N1-2:** `base_url` for node 1 must be the **public** URL where this Lupopedia installation is reachable.
  - This is the domain and path selected in the hosting control panel / auto-installer.
  - Examples: `https://example.com/lupopedia`, `https://example.org/lupo`.
- **N1-3:** Interpreter must derive or verify node 1 `base_url` from installation configuration (for example `site_url`, `base_path`, or equivalent constants in `lupopedia-config.php`).
- **N1-4:** `base_url` for node 1 must not equal `https://www.lupopedia.com`.
- **N1-5:** For **production** validation, `base_url` for node 1 must not be `http://localhost`, `http://127.0.0.1`, or other non-public-only hosts. **Development** installs may classify violations here as **DEGRADED** instead of INVALID if policy allows (see section 10).
- **N1-6:** `node_name` for node 1 must be non-empty; recommended:
  - site title or installation label.
- **N1-7:** Interpreter must not allow hard deletion of node 1; soft-delete should be treated as INVALID for this row.
- **N1-8:** Interpreter may allow `base_url` of node 1 to be updated only via a **controlled** migration or configuration change (for example domain move), never arbitrary direct SQL in normal operation paths.

If node 1 does not match the installation's configured public URL, the interpreter must flag a **configuration error** and may refuse federation operations that depend on node identity until corrected.

---

## 6. Nodes 2 and above (external installations)

- **N2-1:** Any **active** row with `federation_node_id >= 2` represents an external Lupopedia installation **as seen from this installation**.
- **N2-2:** `base_url` must be a valid absolute URL and must not equal:
  - node 0 `base_url`,
  - node 1 `base_url`.
- **N2-3:** There is **no global meaning** to `federation_node_id >= 2`; numbering is **local** to this installation.
- **N2-4:** Interpreter must not assume that another installation uses the same `federation_node_id` for the same `base_url`.
- **N2-5:** Interpreter may create new nodes (`id >= 2`) when:
  - a remote installation is discovered,
  - a federation handshake succeeds,
  - a remote channel/thread/artifact is referenced.
- **N2-6:** Interpreter may reuse an existing row if `base_url` matches an already known external installation (same origin string after normalization).
- **N2-7:** `status` should reflect reachability or trust state (for example: `"active"`, `"inactive"`, `"blocked"`, `"pending"`).
- **N2-8:** `trust_level` should encode local trust decisions (for example numeric tiers for allowed, limited, blocked).

The interpreter must treat all nodes with `federation_node_id >= 2` as **local registry entries only**, never as globally meaningful identifiers.

---

## 7. Status and trust semantics

- **ST-1:** `status` is a string field used for coarse state (examples: `"active"`, `"inactive"`, `"blocked"`, `"system"`, `"pending"`).
- **ST-2:** `trust_level` is a numeric field used for fine-grained policy; higher values may indicate higher trust. Exact scale is **installation-defined** but must be **consistent** within one installation.
- **ST-3:** Interpreter must not infer trust from `federation_node_id` alone; trust is derived from `trust_level`, `status`, and policy tables (for example `federated_trust`) where applicable.
- **ST-4:** Node 0 must always be treated as trusted at the highest level.
- **ST-5:** Node 1 must always be treated as trusted as **self**.

---

## 8. Meta JSON semantics

- **MJ-1:** `meta_json` must be SQL NULL or a value the DB accepts as valid JSON for the column type.
- **MJ-2:** Interpreter may store additional federation-related metadata here, such as:
  - protocol version,
  - last handshake result,
  - last error,
  - capabilities.
- **MJ-3:** Interpreter must not rely on any specific keys in `meta_json` for core invariants defined in sections 3--7.
- **MJ-4:** Invalid JSON (parse failure) must yield **INVALID** (or a repair workflow); the interpreter must not silently ignore parse failures for rows it is validating for federation operations.

---

## 9. Creation and migration rules

- **CR-1:** On fresh installation, the interpreter (or installer) must ensure node **0** and node **1** rows exist and pass sections 4--5 before routine federation operations that depend on them.
- **CR-2:** When applying DDL to an existing database in a supported upgrade or repair context, the interpreter must detect missing node 0 or node 1 and create or fix them according to this spec.
- **CR-3:** If existing seed or legacy data uses different semantics for node 0 or node 1, the interpreter must:
  - log or surface a configuration warning,
  - prefer **this document** plus [federation.md](federation.md) as normative,
  - schedule or require a corrective migration (tracked under PRD 34 or ops doctrine).
- **CR-4:** Interpreter must never auto-create node rows with `federation_node_id < 0` or duplicate active (`is_deleted = 0`) rows for ids **0** or **1**.

---

## 10. Validation outcomes

The interpreter should classify validation results as:

- **VALID:** All invariants and node rules satisfied for the checked scope.
- **DEGRADED:** Non-critical issues (for example unknown `status` strings, missing optional metadata, localhost node 1 in a dev-only policy) but core invariants hold; federation may proceed with caution.
- **INVALID:** Any violation of:
  - node 0 rules,
  - node 1 rules,
  - uniqueness of active `federation_node_id` for 0 and 1,
  - URL validity for node 0 or node 1 when production rules apply,
  - missing required rows for 0 or 1,
  - invalid `updated_ymdhis` packed UTC format,
  - invalid `meta_json` when present and parsed,
  - forbidden soft-delete state for nodes 0 or 1.

In the **INVALID** state, the interpreter must not perform federation operations that depend on node identity until corrected.

---

## 11. Ordered interpreter checklist (deterministic sequence)

The interpreter **MUST** execute checks in the following order. A failure at any step yields the stated classification: **VALID**, **DEGRADED**, or **INVALID** (see section 10 for semantics).

### 11.1 Load and normalize table

1. Load all rows from `{{prefix}}federation_nodes`.
2. Partition rows into:
   - **ACTIVE:** `is_deleted = 0`
   - **DELETED:** `is_deleted = 1`
3. Normalize `base_url` (trim, lowercase host, preserve path).
4. Normalize timestamps to integers.

If table missing or unreadable -> **INVALID**.

### 11.2 Required column presence

Verify existence of columns:

- `federation_node_id`
- `base_url`
- `node_name`
- `trust_level`
- `updated_ymdhis`
- `status`
- `meta_json`
- `is_deleted`

If any missing -> **INVALID**.

### 11.3 Global invariants

1. All `federation_node_id` values are integers >= 0. If any < 0 -> **INVALID**.
2. Primary key uniqueness enforced. If duplicates -> **INVALID**.
3. Exactly one **ACTIVE** row with `federation_node_id = 0`. If 0 or >1 -> **INVALID**.
4. Exactly one **ACTIVE** row with `federation_node_id = 1`. If 0 or >1 -> **INVALID**.
5. All **ACTIVE** rows have non-empty `base_url`. If empty -> **INVALID**.
6. All **ACTIVE** `base_url` values must be absolute URLs (scheme + host). If relative -> **INVALID**.
7. All **ACTIVE** `updated_ymdhis` values must be 14-digit UTC timestamps. If malformed -> **INVALID**.
8. All **ACTIVE** `meta_json` values must be NULL or valid JSON. If parse error -> **INVALID**.

### 11.4 Node 0 validation

Let **R0** = **ACTIVE** row where `federation_node_id = 0`.

1. `R0.base_url` == `https://www.lupopedia.com`. If not -> **INVALID**.
2. `R0.node_name` non-empty. If empty -> **INVALID**.
3. `R0.status` in allowed system statuses (`active`, `system`, etc.). If unknown -> **DEGRADED**.
4. `R0.trust_level` == highest trust tier. If lower -> **DEGRADED**.
5. `R0.is_deleted` must be 0. If 1 -> **INVALID**.
6. `R0.base_url` must not be mutated except by controlled migration. If mismatch with expected canonical config -> **INVALID**.

### 11.5 Node 1 validation

Let **R1** = **ACTIVE** row where `federation_node_id = 1`.

1. `R1.base_url` must equal installation's configured public origin. If mismatch -> **INVALID**.
2. `R1.base_url` must not equal node 0 `base_url`. If equal -> **INVALID**.
3. `R1.base_url` must not be localhost or 127.0.0.1 for production. If so:
   - If environment == development -> **DEGRADED**
   - Else -> **INVALID**
4. `R1.node_name` non-empty. If empty -> **INVALID**.
5. `R1.is_deleted` must be 0. If 1 -> **INVALID**.
6. `R1.base_url` changes allowed only via controlled migration. If arbitrary change detected (no migration marker / audit) -> **INVALID**.

### 11.6 External nodes (IDs >= 2)

For each **ACTIVE** row **Rn** where `federation_node_id >= 2`:

1. `Rn.base_url` must be absolute URL. If not -> **INVALID**.
2. `Rn.base_url` must not equal node 0 or node 1 `base_url`. If equal -> **INVALID**.
3. `Rn.status` must be a known lifecycle state. If unknown -> **DEGRADED**.
4. `Rn.trust_level` must be within installation's trust scale. If out of range -> **DEGRADED**.
5. `Rn.meta_json` must be valid JSON or NULL. If invalid -> **INVALID**.
6. If multiple **ACTIVE** rows share same normalized `base_url` -> **INVALID**.

### 11.7 Soft-delete rules

1. Node 0 and node 1 must never be soft-deleted. If `is_deleted = 1` on those rows -> **INVALID**.
2. Nodes >= 2 may be soft-deleted. Soft-deleted rows must not participate in active federation logic.
3. If a soft-deleted row has the same normalized `base_url` as an **ACTIVE** row -> **DEGRADED**.

### 11.8 Cross-field consistency

1. If `status = blocked`, `trust_level` must be <= blocked threshold. If not -> **DEGRADED**.
2. If `status = active`, `trust_level` must be >= minimum active trust. If not -> **DEGRADED**.
3. If `meta_json` contains protocol version, it must match supported versions. If unsupported -> **DEGRADED**.

### 11.9 Table-level consistency

1. No **ACTIVE** row may have empty `node_name` for IDs 0 or 1. If empty -> **INVALID**.
2. No **ACTIVE** row may have trailing slash inconsistencies that break URL normalization after 11.1 rules. If mismatch -> **DEGRADED**.
3. No **ACTIVE** row may have timestamps in the future beyond allowed clock skew. If too far -> **DEGRADED**.

### 11.10 Final classification

After executing all checks:

- If any **INVALID** condition triggered -> **INVALID**.
- Else if any **DEGRADED** condition triggered -> **DEGRADED**.
- Else -> **VALID**.

Return shape (machine-readable):

```json
{
  "result": "VALID|DEGRADED|INVALID",
  "errors": [],
  "warnings": [],
  "normalized_rows": []
}
```

Implementations populate `errors` and `warnings` with stable codes or messages; `normalized_rows` holds the post-11.1 **ACTIVE** row set used for the pass.

---

Canonical architecture prose: [federation.md](federation.md). Sections 1--10 are normative semantics; **section 11** is the ordered execution contract for validators, installer, upgrader, and runtime.

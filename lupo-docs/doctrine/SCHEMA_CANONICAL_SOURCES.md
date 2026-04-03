---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "doctrine"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/doctrine/SCHEMA_CANONICAL_SOURCES.md"
  web_path: "[web_path](http://www.lupopedia.com/doctrine/SCHEMA_CANONICAL_SOURCES)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Canonical source priority for schema and TOONs; resolves drift between install SQL, TOONs, doctrine, and planning docs"
  tags: ["schema", "canonical", "toon", "4.0.77"]

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Refer to this doctrine when reconciling schema drift or adding new tables"
---
# file: Schema Canonical Sources — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/SCHEMA_CANONICAL_SOURCES

# Schema Canonical Sources Doctrine

When install SQL, TOONs, doctrine docs, or planning copies disagree, use this priority to decide the single source of truth.

## Priority order (highest to lowest)

1. **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** — Highest authority. Defines the full schema for 4.0.x. All required tables and columns live here.
2. **TOON files** (e.g. `lupo-database/lupopedia/toon/*.toon.json`) — Generated from or aligned with install SQL. They must match install SQL; if they do not, regenerate or update TOONs to match install SQL (do not change install SQL to match stale TOONs unless the TOON reflects an intended fix).
3. **Doctrine documents** (e.g. `lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md`) — Describe the schema and usage rules. If doctrine does not match install SQL, update doctrine to describe reality.
4. **Planning copies** (e.g. `lupo-docs/database/lupopedia/tables/planning/`) — Lowest priority. May be historical or stale. Either update them to match install SQL or label them clearly as non-canonical snapshots.
5. **Service/application code** — Must adapt to schema. Do not change canonical schema to match code; change code to match install SQL and TOONs.

## Required decisions when sources disagree

- **TOONs do not match install SQL:** Regenerate or update TOONs to match install SQL. Use the repo’s TOON generation script (e.g. `generate_toon_from_sql.py` or `generate_toon_files.py`) if available and canonical.
- **Doctrine does not match install SQL:** Update doctrine to describe the actual schema.
- **Planning docs conflict with install SQL:** Update planning docs to match reality, or add a clear warning that they are historical/non-canonical.
- **Service code assumptions conflict with schema:** Adjust code (and docs), not schema, unless install SQL is demonstrably wrong and the fix is approved.

## References

- **Install SQL:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **TOON path:** `lupo-database/lupopedia/toon/`
- **Table documentation:** `lupo-docs/database/lupopedia/tables/` — Per-table docs (purpose, schema summary, doctrine notes). Subdirs: `active/` (current required/optional tables), `planning/` (planned or historical), `deprecated/`, `migrations/`. These docs describe the schema; they do not override install SQL or TOONs. Keep them aligned with install SQL per priority order above. Example (Bayesian Decision Tracking): [lupo_decisions](../database/lupopedia/tables/active/lupo_decisions.md), [lupo_decision_edges](../database/lupopedia/tables/active/lupo_decision_edges.md), [lupo_decision_influences](../database/lupopedia/tables/active/lupo_decision_influences.md).
- **Upgrade policy** (no migration chain in 4.0.x): [UPGRADE_POLICY_DOCTRINE.md](UPGRADE_POLICY_DOCTRINE.md)

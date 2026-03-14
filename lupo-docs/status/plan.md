---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "documentation"
  system_version: "4.0.74"
  file_path_from_root: "lupo-docs/status/plan.md"
  web_path: "http://www.lupopedia.com/status/plan"
  last_modified_utc: "20260314"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "antigravity"
  delegation_chain: "wolfie:root"
  artifact_type: "plan"
  artifact_kind: "roadmap"
  purpose: "Outlines missing implementation steps and next actions for the IDE agents post-doctrine expansion."
  tags: ["plan", "antigravity", "roadmap", "implementation", "v4.0.74"]

lupopedia.init:
  orchestrator_actor: "wolfie"
  rule_set_version: "4.0.74"
  applies_to: ["roadmap", "orchestrator"]
  enforcement: normal

lupopedia.edges:
  comment: "Snapshot of edges related to implementation targets."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 0.95 }
    - { to: "lupo-rules/root/", type: "references", weight: 0.9 }
    - { to: "lupo-docs/status/report_antigravity.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260314"
  last_verified_by: "antigravity"
  orchestrator: "wolfie"
  next_action:
    - "Update lupo_actors documentation PK mapping"
    - "Inject lupopedia.init blocks into rules"
---
# file: Antigravity Implementation Plan — session: L-LUPO-ROOT-ANTIGRAVITY — delegation: wolfie:root (faucet: antigravity) — web_path: http://www.lupopedia.com/status/plan

# Antigravity Implementation Plan (v4.0.74)

In light of the new doctrine explicitly separating Auth Users, Actors, Faucets, and Database Snapshots, as well as the outstanding audit tasks noted in previous reports, the following are the primary implementation steps needed:

## Priority 1: Orchestrator Rules and Database Consistency

1. **`lupopedia.init` Rule Injection**
   - The `.md` rules located within `/lupo-rules/root/` must be updated to include the `lupopedia.init` block. Currently, they lack this mandatory initialization struct.

2. **Table PK Correction: `lupo_actors`**
   - Open `lupo-docs/database/lupopedia/tables/active/lupo_actors.md` and explicitly rewrite the documentation to list `actor_name` as the primary key rather than `actor_id` (which is a unique secondary index). This resolves the high-severity schema documentation drift.

3. **Provide `lupo_orchestrator_rules` Table SQL**
   - Add the creation table schema for `lupo_orchestrator_rules` to a one-time migration to enable DB-canonical rule storage, removing the reliance on IDE agents needing to do directory scans of `/lupo-rules/root/`.

## Priority 2: Ingestion Automation 

1. **Snapshot Ingestion Tooling**
   - Now that the filesystem has explicitly grouped its arrays as `snapshots` of the core relations (via the `lupopedia.metadata` and `lupopedia.edges` block), build or iterate on a PHP syncing tool (`sync_metadata_from_headers.php`) capable of reliably importing a modified `.md` YAML header block straight back into `lupo_metadata` and `lupo_edges`.

2. **Grouped Edge Support in Validator APIs**
   - Determine if the PHP backend validators properly handle grouped `outbound_edges` (i.e. decoding array chunks based on their category: `outbound_edges.code`, `outbound_edges.documents`). Ensure `edge_category` aligns nicely with the group key on import.

## Priority 3: Faucet Tracing Verification

- All IDE agents should audit `lupo-includes/classes/RuleEngine.php` and web logic (`App\Auth\Session`) to verify that `faucet_slug` and `faucet_instance_id` are universally transmitted during chat API creations and system file updates, cementing the division between `Actor` (identity) and `Faucet` (execution surface).

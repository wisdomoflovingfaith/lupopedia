# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/migrations/livehelp_sessions_migration.md"
  file_hash: "59bc8ea291fb012e253548390e85e3214cac5e3bff9f4f02c3f8b102d9cbc6cc"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "legacy"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\database\lupopedia\tables\livehelp_sessions_migration.md"
  file_hash: "8858d063f3f7a0d75df2f7672f545b0c4a45bc5262eddf67855cbfb98cafb996"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for livehelp_sessions_migration.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "livehelp_sessions_migrationmd"]
  lupo_agent: "windsurf"

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/livehelp_sessions_migration.md",
  file_hash: "bb44aa355e8de0cb28bb493e1d82cd30eb88a5ae46508e95144367583366529a"
  system_version: "4.0.50"
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Migration doctrine for livehelp_sessions â†’ lupo_sessions table mapping",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "migration_mapping",
  traits: ["crafty_syntax", "migration", "table_mapping", "livehelp_sessions", "dropped"],
  hashtags: ["legacy-reference", "#migration", "#crafty_syntax", "#livehelp_sessions", "#sessions", "#dropped", "#upgrade_path"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.75 }
}

flip.footer: {
  inbound_edges: [
    { from: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, hashtag: "#index" },
    { from: "lupo-database/migrations/import_from_old_crafty_syntax.sql", type: "documents", weight: 0.8, hashtag: "#migration" }
  ],
  outbound_edges: [
    { to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md", type: "documents", weight: 1.0, hashtag: "#target_table" },
    { to: "app/auth/Session.php", type: "references", weight: 0.8, hashtag: "#implementation" },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "references", weight: 0.7, hashtag: "#source" }
  ],
  referenced_by_actors: [1001, 10000],
  references: {
    by_files: ["lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["livehelp_sessions_mapping", "session_engine", "dropped_table", "deterministic_sessions"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}

  ---

## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia.


# Migration Note: livehelp_sessions
# Status: DROPPED
# Replacement: lupo_sessions (deterministic, actor-aware session engine)

# 1. Summary
livehelp_sessions was Crafty Syntax's legacy table for storing ephemeral visitor session data.
It attempted to track:

visitor session IDs

operator assignments

timestamps

browser/cookie-based identifiers

temporary routing state

This table was never durable, never reliable, and never meant to survive a restart.

Lupopedia replaces this entire subsystem with:

Code
lupo_sessions
...a deterministic, actor-aware, device-aware, federated session engine that does not rely on legacy Crafty Syntax session mechanics.

No data is imported.
The table is dropped after migration.

# 2. What the Legacy Table Actually Did
Crafty Syntax stored all session state in this table, including:

temporary session IDs

operator/visitor routing

timestamps

browser cookie identifiers

ephemeral chat state

This table was:

overwritten constantly

cleared when chats ended

inconsistent across browsers

dependent on PHP session behavior

not actor-aware

not device-aware

not federated

not repairable

It was essentially a runtime scratchpad, not a real data model.

# 3. Why Lupopedia Drops This Table
a. The legacy session model is fundamentally incompatible
Crafty Syntax sessions were:

non-deterministic

tied to browser cookies

not linked to actors

not linked to devices

not replayable

not durable

Lupopedia sessions are:

deterministic

actor-aware

device-aware

federated

repairable

replayable

b. The table contains no durable business data
Everything in it was:

ephemeral

runtime state

not historically meaningful

c. The new session engine replaces the entire concept
lupo_sessions provides:

actor identity

device fingerprint

federations node ID

temporal windows

session replay

multi-agent compatibility

d. Migration would be meaningless
There is no way to map:

Crafty Syntax session IDs -> Lupopedia session IDs

cookie-based identifiers -> actor/device identities

ephemeral routing -> deterministic session state

So the table is dropped.

# 4. Migration Behavior (as implemented in SQL)
Step 1 -- Convert legacy table for safe reading
Code
ALTER TABLE livehelp_sessions ENGINE=InnoDB;
ALTER TABLE livehelp_sessions CONVERT TO utf8mb4;
Step 2 -- Mark as deprecated
Code
COMMENT = 'DEPRECATED...'
Step 3 -- No import
There is:

no SELECT

no INSERT

no mapping

Step 4 -- Drop legacy table
Removed after migration.

# 5. Mapping Summary
Legacy -> New
Code
livehelp_sessions -> DROPPED
Replacement
Code
lupo_sessions
Fields preserved
None -- the legacy table contains no durable data.

# 6. Doctrine Notes
This migration is a perfect example of:

Replacing a broken legacy subsystem with a modern architecture
The old session model was:

fragile

inconsistent

not actor-aware

not device-aware

not federated

The new model is:

deterministic

explicit

actor-centric

device-aware

replayable

multi-agent compatible

The Slope Principle
We do not attempt to import or reinterpret ephemeral runtime state.
We start fresh with a clean, deterministic session engine.

# 7. Final Decision
Code
livehelp_sessions -> DROPPED
All session handling is performed by lupo_sessions.


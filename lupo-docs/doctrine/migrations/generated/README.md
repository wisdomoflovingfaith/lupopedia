---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/migrations/generated/README.md"
  file_hash: "ef244fc9f31df34a113fe36b293931c8a35f64416625b151b1b1ad9bff0d7a87"
  last_updated_utc: "20260228155738"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "cursor"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\migrations\generated\README.md"
  file_hash: "32eb222b75aa4e1a36ada3d9fb8b83f9a2ec23c3347868bf350a6240205d2cdb"
  file_path_from_root: "lupo-docs\doctrine\migrations\generated\README.md"
  file_hash: "61a1ec099672e1ac15c166f461d1e6fe9d7ecb82763ad43c2b3efb204e75b468"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "migrations", "generated", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
lupopedia.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/migrations/generated/README.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/migrations/generated/README.md
---

# Generated one-time SQL migrations

Place one-time SQL migration files here. Run them manually (e.g. in phpMyAdmin).

- Do not run migrations from application code.
- Use TOONs / install_new_lupopedia.sql / migration docs as schema source.
- Name files descriptively (e.g. `add_foo_column_to_sessions.sql`).

**Applied / available:**
- `drop_lupo_actor_roles.sql` — DROP table lupo_actor_roles (actor roles DROPPED; use lupo_channel_roles, default channel_id = 1).

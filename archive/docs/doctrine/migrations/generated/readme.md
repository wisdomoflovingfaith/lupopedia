---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/migrations/generated/README.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "docs\doctrine\migrations\generated\README.md"
  file_hash: "32eb222b75aa4e1a36ada3d9fb8b83f9a2ec23c3347868bf350a6240205d2cdb"
  file_path_from_root: "docs\doctrine\migrations\generated\README.md"
  file_hash: "61a1ec099672e1ac15c166f461d1e6fe9d7ecb82763ad43c2b3efb204e75b468"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_vector: "4169E1"
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
file_path_from_root: docs/doctrine/migrations/generated/README.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/migrations/generated/README.md
---

# Generated one-time SQL migrations

Place one-time SQL migration files here. Run them manually (e.g. in phpMyAdmin).

- Do not run migrations from application code.
- Use TOONs / install_new_lupopedia.sql / migration docs as schema source.
- Name files descriptively (e.g. `add_foo_column_to_sessions.sql`).

**Applied / available:**
- `drop_lupo_actor_roles.sql` — DROP table lupo_actor_roles (actor roles DROPPED; use lupo_channel_roles, default channel_id = 1).

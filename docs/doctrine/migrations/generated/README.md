# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\migrations\generated\README.md"
  file_hash: "61a1ec099672e1ac15c166f461d1e6fe9d7ecb82763ad43c2b3efb204e75b468"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "migrations", "generated", "readmemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/migrations/generated/README.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
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

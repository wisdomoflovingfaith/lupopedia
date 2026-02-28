# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\migrations\README.md"
  file_hash: "d717f92ccdcf05ff3f187fefc372a20be5cc737e7ff0b3512a556596c38c1e8c"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "migrations\README.md"
  file_hash: "f4a7be0b2d9cb85c7c38721db86cd97a7b2bf4a3814db269819875922e6a510a"
  file_path_from_root: "migrations\README.md"
  file_hash: "84a8d4a23b91891fa46fab370d21bcd0f5ce5e3a20057af04e2d74e0a29148f4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Migrations"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["migrations", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Migrations

This project does **not** use Laravel or Illuminate migrations.

- **Schema and one-off changes:** Use SQL files in `database/migrations/` (e.g. `add_system_context_to_lupo_sessions.sql`) and run them with your PDO connection.
- **Do not use:** `Illuminate\Database\Migrations\Migration`, `Schema::create()`, `config()`, or any Laravel migration runner.
- PHP files in this directory that use `Illuminate\*` are **deprecated**. Prefer plain SQL in `database/migrations/` or plain PHP scripts that execute SQL via PDO.
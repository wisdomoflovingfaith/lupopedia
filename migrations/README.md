# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "migrations\README.md"
  file_hash: "84a8d4a23b91891fa46fab370d21bcd0f5ce5e3a20057af04e2d74e0a29148f4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Migrations"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["migrations", "readmemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Migrations

This project does **not** use Laravel or Illuminate migrations.

- **Schema and one-off changes:** Use SQL files in `database/migrations/` (e.g. `add_system_context_to_lupo_sessions.sql`) and run them with your PDO connection.
- **Do not use:** `Illuminate\Database\Migrations\Migration`, `Schema::create()`, `config()`, or any Laravel migration runner.
- PHP files in this directory that use `Illuminate\*` are **deprecated**. Prefer plain SQL in `database/migrations/` or plain PHP scripts that execute SQL via PDO.

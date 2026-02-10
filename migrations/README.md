# Migrations

This project does **not** use Laravel or Illuminate migrations.

- **Schema and one-off changes:** Use SQL files in `database/migrations/` (e.g. `add_system_context_to_lupo_sessions.sql`) and run them with your PDO connection.
- **Do not use:** `Illuminate\Database\Migrations\Migration`, `Schema::create()`, `config()`, or any Laravel migration runner.
- PHP files in this directory that use `Illuminate\*` are **deprecated**. Prefer plain SQL in `database/migrations/` or plain PHP scripts that execute SQL via PDO.

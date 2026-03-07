# Lupo-Actors

Centralized hub for actor-specific resources. Each subdirectory is named by actor ID (e.g. `0`, `1`).

**Full documentation:** [docs/actors.md](../docs/actors.md)

- **Actor 0** — System (core platform, security, low-level management)
- **Actor 1** — WOLFIE (governing agent, coordination, orchestration)
- **Actor 19** — Anubis (recovery: orphan adoption, quarantine, recovery)
- **Actor 42** — Antigravity (canonical; IDE extensions, VSX)

Each actor directory contains: `apps/`, `tools/`, `docs/`, `db-changes/`, `api/`, `needs/`, and `prompts/`. Actor 0 also has `logs/` for system scan and queue logs. The `prompts/` folder holds prompt files (e.g. `flare-header-scan.md` in actor 0) used to guide actor behavior.

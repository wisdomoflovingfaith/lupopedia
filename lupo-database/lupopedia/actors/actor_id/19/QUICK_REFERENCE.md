---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/19/QUICK_REFERENCE.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "documentation"
  purpose: "Quick reference for ANUBIS (Actor 19)"
  tags: ["actor-19", "anubis", "quick-reference"]
---

# Actor 19 — Quick Reference (ANUBIS)

**Actor ID:** 19 | **Slug:** anubis | **Kind:** agent (custodial)

## Usage

- **Role:** Custodial intelligence — orphaned file detection, FLARE/header recovery, queue-based processing. Database-primary storage for file content so recovery is possible even if disk files are moved or deleted.
- **Queue tables:** `lupo_anubis_*` (see install SQL and CHANGELOG). Content stored in LONGTEXT; detection via upload hook and proactive file watcher.
- **Integration:** Referenced by upload flows, admin dashboard (“On Disk” status), and any script that enqueues orphaned or recovered artifacts.

## Key references

| Topic | Location |
|-------|----------|
| Identity / purpose | `README.md` in this directory |
| WHO / identity | `WHO.json`, `identity.json` |
| Queue schema | `lupo-database/lupopedia/mysql/install/` (install SQL), CHANGELOG (ANUBIS queue, custodial health) |
| Ingestion / faucets | Channel 42 tasks (anubis_flare_ingestion_faucet), lupo-docs |

## Troubleshooting

- **Orphans not detected:** Check upload hook and file watcher configuration; ensure queue tables exist and ANUBIS is active in registry.
- **Recovery failing:** Verify content was stored in DB (LONGTEXT); check queue status and admin dashboard for “On Disk” and queue state.

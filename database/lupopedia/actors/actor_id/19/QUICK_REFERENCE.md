---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/actors/actor_id/19/QUICK_REFERENCE.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/actors/actor_id/19/QUICK_REFERENCE.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: null
  prd_cluster: null
  title: null
  summary: null
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
| Queue schema | `database/lupopedia/mysql/install/` (install SQL), CHANGELOG (ANUBIS queue, custodial health) |
| Ingestion / faucets | Channel 42 tasks (anubis_flare_ingestion_faucet), docs |

## Troubleshooting

- **Orphans not detected:** Check upload hook and file watcher configuration; ensure queue tables exist and ANUBIS is active in registry.
- **Recovery failing:** Verify content was stored in DB (LONGTEXT); check queue status and admin dashboard for “On Disk” and queue state.

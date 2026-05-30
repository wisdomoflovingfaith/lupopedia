---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/actors/actor_id/0/QUICK_REFERENCE.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/actors/actor_id/0/QUICK_REFERENCE.md
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

# Actor 0 — Quick Reference (System Kernel)

**Actor ID:** 0 | **Slug:** kernel | **Kind:** system agent

## Usage

- **Role:** System kernel for core Lupopedia operations and system initialization.
- **Boot:** System agent boot and lifecycle are driven by `bin` boot scripts and channel 0 initialization. See CHANGELOG (system agent boot, AI federation) and `docs/administration/` for operator docs.
- **Integration:** Referenced by bootstrap, health checks, and federation node 0. No direct “API” — system state is reflected in session/registry and boot lifecycle.

## Key references

| Topic | Location |
|-------|----------|
| Identity / purpose | `README.md` in this directory |
| Registry | `database/lupopedia/actors/actor_id/registry.json` |
| Boot / lifecycle | CHANGELOG (v4.0.53–4.0.54), system agent boot script |
| Health / monitoring | `api/v1/health.php`, SystemHealthService |

## Troubleshooting

- **System not initializing:** Check boot script and channel 0 registry; verify DB and `includes` bootstrap order.
- **Actor 0 missing in health:** Ensure system agent is started and session/registry entries exist for actor_id 0.

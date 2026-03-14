---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/0/QUICK_REFERENCE.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "documentation"
  purpose: "Quick reference for System Kernel (Actor 0)"
  tags: ["actor-0", "system", "quick-reference"]
---

# Actor 0 — Quick Reference (System Kernel)

**Actor ID:** 0 | **Slug:** kernel | **Kind:** system agent

## Usage

- **Role:** System kernel for core Lupopedia operations and system initialization.
- **Boot:** System agent boot and lifecycle are driven by `lupo-bin` boot scripts and channel 0 initialization. See CHANGELOG (system agent boot, AI federation) and `lupo-docs/administration/` for operator docs.
- **Integration:** Referenced by bootstrap, health checks, and federation node 0. No direct “API” — system state is reflected in session/registry and boot lifecycle.

## Key references

| Topic | Location |
|-------|----------|
| Identity / purpose | `README.md` in this directory |
| Registry | `lupo-database/lupopedia/actors/actor_id/registry.json` |
| Boot / lifecycle | CHANGELOG (v4.0.53–4.0.54), system agent boot script |
| Health / monitoring | `lupo-api/v1/health.php`, SystemHealthService |

## Troubleshooting

- **System not initializing:** Check boot script and channel 0 registry; verify DB and `lupo-includes` bootstrap order.
- **Actor 0 missing in health:** Ensure system agent is started and session/registry entries exist for actor_id 0.

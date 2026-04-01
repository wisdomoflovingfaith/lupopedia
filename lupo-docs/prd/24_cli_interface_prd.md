---
lupopedia.headers:
  lupopedia.schema: "prd"
  file_path_from_root: "lupo-docs/prd/24_cli_interface_prd.md"
  version_when_written: "4.0.93"
  purpose: "PRD defining the CLI Interface requirements and entry points"
  tags: ["prd", "cli", "terminal", "orchestration"]
---

# PRD 24: CLI Interface

## 1. Constitutional Anchor
All rules and requirements in this PRD must strictly comply with `lupo-docs/prd/00_root_constitutional_system_requirements.md`.

## 2. Overview
This PRD outlines the requirements for the Lupopedia CLI subsystem, establishing how terminal operations handle identity context and task routing independently from the web layer.

## 3. Canonical Entrypoint
All CLI commands **MUST** be routed through the singular canonical entrypoint:
`php lupo-bin/lupo.php <command> [args]`
No sub-systems or modules are permitted to create their own root-level executable `.php` bash alias entry points. 

## 4. Actor Context & Identity Resolution
Unlike the web layer which relies exclusively on typical browser session variables bound to `lupo_sessions` and `lupo_auth_users`, the CLI resolves identity by traversing the following local paths:
1. **`session.md`:** The local file-based context representing the active orchestration session.
2. **`.lupo_actor`:** Secondary local file tracking immediate actor switches.
3. **Database & Registry (`lupo_sessions` / `registry.json`)** to ultimately bind the local text state to an authoritative actor ID.

The CLI must natively support a dual-identity footprint, simultaneously displaying the authenticated Human invoking the command alongside the AI/IDE Agent driving the API pipeline.

## 5. Core Operational Requirements
To be compliant, the CLI must continuously support the following core orchestration commands:
- **`doctor` / `doctor-context`**: See PRD 23 (ASCLEPIUS - Health Check System).
- **`whoami` & `context`**: Must output current dual-identity (Human + Agent) and active session mode. 
- **`channels` & `threads`**: Must list available database coordination points.
- **`use` / `switch`**: Must allow real-time transition of the terminal's actor identity.

---
lupopedia.headers:
  lupopedia.schema: "prd"
  file_path_from_root: "lupo-docs/prd/23_health_check_asclepius_prd.md"
  version_when_written: "4.0.93"
  purpose: "PRD defining the ASCLEPIUS System Health Check capabilities and bounds"
  tags: ["prd", "doctor", "health", "asclepius"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
---

# PRD 23: ASCLEPIUS Health Check System

## 1. Constitutional Anchor

All rules and requirements in this PRD must strictly comply with `lupo-docs/prd/00_root_constitutional_system_requirements.md`.

## 2. Overview
This document defines the architectural requirements for the systemic health checking system driven by the **ASCLEPIUS** agent (actor_id 703), which orchestrates `lupo doctor` diagnostics.

## 3. System Ownership & Fallback Ladder
The health check system follows a strict cascade fallback architecture:
1. **Agent Orchestration (Primary):** The system relies on the dedicated ASCLEPIUS script located at `lupo-agents/asclepius/doctor.php`.
2. **Built-In Fallback:** If the agent script is unavailable, the CLI automatically falls back to executing the core procedural `lupo_doctor_health_check()` function.

## 4. Mandatory Diagnostic Checks
Regardless of whether ASCLEPIUS or the built-in function executes the test, the health check process **MUST** validate the following core pillars of system integrity:

- **Database Connectivity:** Verify the primary MySQL/MariaDB connection via `DatabaseFactory::getConnection()`.
- **Registry Availability:** Ensure `{LUPO_DATABASE_DIR}/lupopedia/actors/actor_id/registry.json` is present and readable for identity mapping.
- **Session File Integrity:** Verify read/write access to `{LUPO_DATABASE_DIR}/session.md` (which maps CLI state).
- **Identity Context Drift:** Validate the Context Kernel (`ContextKernel::validate()`) to detect split-brain conflicts and actor-pairing desyncs between DB state and local files.
- **Actor Consistency (Optional/Flagged):** With an explicit flag (e.g., `--check-actors`), validate workspace paths and PHP namespaces against the `lupo_actors` table.

## 5. Output & Repair Protocol
The system must never silently patch context drift without consent.
- Health reports must cleanly output `[OK]`, `[WARN]`, `[FAIL]`, or `[SKIP]`.
- If Context Kernel drift is detected, the system must prompt the user to manually invoke the repair sequence (e.g., `lupo-bin/lupo.php doctor-context --repair`) to force a database-aligned overwrite of `session.md`.

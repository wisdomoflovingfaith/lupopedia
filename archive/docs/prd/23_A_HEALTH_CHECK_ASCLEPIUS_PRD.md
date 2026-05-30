---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/23_A_HEALTH_CHECK_ASCLEPIUS_PRD.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/23_A_HEALTH_CHECK_ASCLEPIUS_PRD.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/23_health_check_asclepius_prd.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/health-check-asclepius-prd
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_23_A
  title: "PRD 23: ASCLEPIUS Health Check System"
  summary: null
---
# PRD 23: ASCLEPIUS Health Check System

## 1. Constitutional Anchor

All rules and requirements in this PRD must strictly comply with `docs/prd/00_root_constitutional_system_requirements.md`.

## 2. Overview
This document defines the architectural requirements for the systemic health checking system driven by the **ASCLEPIUS** agent (actor_id 703), which orchestrates `lupo doctor` diagnostics.

## 3. System Ownership & Fallback Ladder
The health check system follows a strict cascade fallback architecture:
1. **Agent Orchestration (Primary):** The system relies on the dedicated ASCLEPIUS script located at `agents/asclepius/doctor.php`.
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
- If Context Kernel drift is detected, the system must prompt the user to manually invoke the repair sequence (e.g., `bin/lupo.php doctor-context --repair`) to force a database-aligned overwrite of `session.md`.

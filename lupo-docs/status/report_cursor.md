---
lupopedia.init:
  file_identity: "report_cursor.md"
  artifact_type: "onboarding_assessment"
  artifact_kind: "status_report"
  namespace: "cursor"
  domain: "status"
  system_version: "4.0.76"
  assessment_actor: "cursor"
  assessment_faucet: "cursor"
  orchestrator_actor: "wolfie"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Cursor IDE Agent Onboarding & Registration Assessment", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Onboarding and registration assessment for Cursor IDE agent. Includes system understanding, registration status verification, required steps, and documentation gaps.", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor, onboarding, registration, assessment, ide_agent, lupopedia, lead_orchestration", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }

lupopedia.comments:
  - { comment_id: 1, channel_id: 42, actor_id: 102, actor_name: "cursor", faucet_id: 102, faucet_name: "cursor", comment_text: "Onboarding assessment completed - Cursor is already registered as actor_id 102 (lead orchestration).", comment_type: "assessment", created_ymdhis: 20260315230500, updated_ymdhis: 20260315230500 }
  - { comment_id: 2, channel_id: 42, actor_id: 102, actor_name: "cursor", faucet_id: 102, faucet_name: "cursor", comment_text: "System architecture understood - doctrine-driven semantic OS with multi-agent collaboration.", comment_type: "understanding", created_ymdhis: 20260315231000, updated_ymdhis: 20260315231000 }

lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "onboarding_assessment"
  file_path_from_root: "lupo-docs/status/report_cursor.md"
  web_path: "http://www.lupopedia.com/status/report_cursor"
  last_modified_utc: "20260315"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "onboarding_assessment"
  artifact_kind: "status_report"
  purpose: "Onboarding and registration assessment for Cursor IDE agent (lead orchestration)"
  traits: ["onboarding", "assessment", "ide_agent", "lead_orchestration", "4.0.76"]
  tags: ["cursor", "onboarding", "registration", "assessment", "ide_agent"]

lupopedia.session:
  session_id: "L-LUPO-CURSOR-ONBOARDING"
  session_name: "L-LUPO-CURSOR-ONBOARDING"
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  paired_actor_id: 1

lupopedia.edges:
  comment: "Snapshot of relationships for Cursor onboarding assessment."
  outbound_edges:
    - { to: "ONBOARDING.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md", type: "references", weight: 0.9 }
    - { to: "lupo-rules/root/README.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "validates", weight: 0.85 }
    - { to: "lupo-docs/doctrine/", type: "reviews", weight: 0.8 }
  semantic_tags: ["cursor_onboarding", "ide_agent_assessment", "registration_verification", "lead_orchestration"]

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260315"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Continue contributing as lead orchestration; follow IACP for handoffs"
    - "Keep root rules and propagation in sync when doctrine changes"
---
# file: Cursor IDE Agent Onboarding & Registration Assessment — session: L-LUPO-CURSOR-ONBOARDING — delegation: cursor:root (faucet: cursor) — web_path: http://www.lupopedia.com/status/report_cursor

# Cursor IDE Agent Onboarding & Registration Assessment

**Date:** 2026-03-15  
**Assessing Agent:** Cursor (actor_id: 102, faucet: cursor)  
**Agent Type:** IDE Agent (Lead Orchestration)  
**Orchestrator:** Wolfie (actor_id: 1)  
**System Version:** 4.0.76

---

# 1. Understanding of Lupopedia

## Purpose and Architecture

Lupopedia is a **semantic operating system** built for multi-agent collaboration. It continues the Crafty Syntax live-chat lineage with:

- **Doctrine-driven architecture** — Rules and constraints are explicit in doctrine files and root rules (`lupo-rules/root/`); behavior is not implied by framework defaults.
- **Deterministic identity** — Actors, channels, and other entities use registry-based ID allocation (no ad hoc AUTO_INCREMENT); reserved-ID doctrine applies to `lupo_actors`, `lupo_channels`, and related tables.
- **Channel-scoped work** — Tasks, dialogs, and artifacts are scoped by `channel_id`; Channel 42 is the primary development channel.
- **Documentation as architecture** — Doctrine, table docs, and canonical references are implementation-critical and must be read before changing schema or behavior.
- **Multi-agent collaboration** — Multiple IDE agents (Cursor, Windsurf, Kiro, Cascade, JetBrains, etc.) and humans work in the same repo; continuity, logging, and handoff are governed by the IDE Agent Continuity Protocol (IACP).

## Actors, Channels, Documentation, and Rules

- **Actors** are orchestration identities (e.g. Cursor actor_id 102, Wolfie 1); **faucets** are execution surfaces (the IDE itself). Actors are stored in `lupo_actors` and referenced in the actor registry (`lupo-database/lupopedia/actors/actor_id/registry.json`).
- **Channels** define work context (`lupo_channels`); operations and artifacts are scoped by `channel_id`. Channel 42 is Lupopedia Development.
- **Documentation** (doctrine, table docs, status reports) is first-class; LUPOPEDIA HEADERS in artifacts link files to metadata and versioning.
- **Rules** live canonically in `lupo-rules/root/*.md` and are propagated to IDE-specific outputs (e.g. `.cursor/rules/*.mdc`) via `lupo-scripts/propagate_agent_rules.php`. Root rules are the source of truth.

## Multi-Agent Collaboration

Agents follow the same doctrine and root rules; each may have a propagation target (cursor, kiro, windsurf, cascade, idea). Work is persisted in the repository (status files, logs, docs); handoffs are documented via status artifacts and logs so another agent can resume. IACP defines logging, checkpoints, and chain-of-custody expectations.

---

# 2. Required Onboarding Steps for a New IDE Agent

| Step | Why it matters |
|------|----------------|
| **Read ONBOARDING.md** | Operational entry point; defines first files to read, non-negotiable rules, and agent status (already registered vs new vs integration-only). |
| **Read README.md** | System overview, install path, canonical root rules, new-agent onboarding and actor registration pointer. |
| **Read CHANGELOG.md** | Current version (4.0.76) and recent changes; avoids working against outdated assumptions. |
| **Read lupo-rules/root/README.md** | Index of root rules and propagation targets; all agents must follow these rules. |
| **Check agent status** | Determine if the agent already exists in the registry (State A), does not exist (State B), or exists but needs integration (State C). Avoids duplicate registration or missed integration. |
| **Review root rules and doctrine** | DATABASE_DOCTRINE, ACT001, reserved-ID, etc. Must be understood before changing schema or architecture. |
| **Register or verify identity** | If new: follow ACTOR_REGISTRATION_CHECKLIST (registry, DB or fallback, validation). If existing: verify actor_id/slug and run propagation. |
| **Run rules propagation** | `php lupo-scripts/propagate_agent_rules.php --target=<agent>` so the IDE has derived rule files; supported targets include cursor, kiro, windsurf, cascade, idea, all. |
| **Understand channel workflow** | Work is scoped by channel_id; Channel 42 is development. Session and context doctrine (CTX001) apply. |
| **Review IACP** | IDE_AGENT_CONTINUITY_PROTOCOL defines logging, status checkpoints, and handoff so work remains resumable. |

---

# 3. Required Registration Steps for This Agent (Cursor)

## Existing Actor Check

**Cursor is already registered** in Lupopedia. No new registration is required.

### Verification (from repository documentation and registry)

- **Actor ID:** 102 (documented in README, AGENTS.md, and `lupo-database/lupopedia/actors/actor_id/registry.json`)
- **Actor Name / slug:** cursor
- **Type:** ide_faucet
- **Lead orchestration:** true (per README and AGENTS.md; Cursor is the lead orchestration actor)
- **Registry path:** `lupo-database/lupopedia/actors/actor_id/registry.json` — entry present with `id: 102`, `slug: "cursor"`, `dir: "actors/102"`

### What Cursor must do (State A — already registered)

1. **Verify identity** — Confirm actor_id 102 and slug `cursor` in the registry; no duplicate registration.
2. **Run rules propagation** — Ensure derived rules are current: `php lupo-scripts/propagate_agent_rules.php --target=cursor`. Validation: `php lupo-tests/unit/cursor_rules_enforcement.php`.
3. **Proceed with contribution** — Follow root rules and doctrine; no new actor creation or checklist for new agents.

### For reference only (if this were a new agent)

Registration would follow ACTOR_REGISTRATION_CHECKLIST: add registry entry, persist in DB or fallback (reserved-ID doctrine, no lastInsertId()), set paired_actor_id, then add propagation target if the IDE were not yet supported.

---

# 4. Rules the Agent Must Follow

- **Database doctrine** — No foreign keys, triggers, or stored procedures; BIGINT UTC timestamps (YYYYMMDDHHIISS) set in application code; soft deletes via `is_deleted`/`deleted_ymdhis`; reserved-ID pattern for actors/channels (explicit ID, no AUTO_INCREMENT/lastInsertId() for those tables). See `lupo-docs/doctrine/DATABASE_DOCTRINE.md` and root rules (e.g. database-logic-prohibition-doctrine, pdo-db-database-access-doctrine, reserved-id-doctrine).
- **Actor and identity (ACT001)** — No anonymous operation; paired orchestrator; registry-based IDs; actor vs faucet distinction. See ide-agent-identity-actor-pairing-doctrine.
- **Documentation** — LUPOPEDIA HEADERS and metadata expectations; version consistency; cross-references and edges where appropriate.
- **Multi-agent** — IACP: continuous logging, status checkpoints, handoff artifacts; repository as source of truth; leave work resumable for other agents.
- **File and repo** — lupo-* path normalization; install SQL authoritative, TOONs derived; no Composer/Laravel; PHP 5.6+ compatibility in core paths.
- **Safe database operations (DB009)** — Migrations and schema changes via `php lupo-scripts/safe-migrate.php`, not raw `mysql` CLI.

---

# 5. Files the Agent Must Understand Before Working

| Priority | File | Purpose |
|----------|------|---------|
| **Critical** | ONBOARDING.md | Operational entry point, first files to read, non-negotiable rules, agent status (A/B/C), extending propagation |
| **Critical** | README.md | System overview, install, canonical root rules, new-agent onboarding, Cursor as lead orchestration |
| **Critical** | lupo-rules/root/README.md | Root rules index and propagation targets |
| **Critical** | lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md | When to register vs verify; extending rules propagation; full checklist for new agents |
| **High** | lupo-docs/doctrine/DATABASE_DOCTRINE.md | Core database rules (no FKs, no DB logic, timestamps, reserved IDs, soft deletes) |
| **High** | lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md | IACP: logging, checkpoints, handoff, cross-agent resume |
| **High** | CHANGELOG.md | Current version (4.0.76) and recent changes |
| **High** | TODO.md | Current priorities and version state |
| **Medium** | lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md | Header format and block order for artifacts |
| **Medium** | lupo-docs/database/lupopedia/tables/ (and active/) | Table and schema context (e.g. lupo_actors, lupo_channels) |
| **Medium** | AGENTS.md | Lead orchestration (Cursor), registry path, IDE faucets |

---

# 6. Unclear Areas or Missing Instructions

- **Multiple actor_id references to “cursor”** — The registry lists Cursor as actor_id 102; some legacy or alternate paths (e.g. aliases or actor_id 1002, 1005) appear in the actors filesystem. For onboarding and attribution, the canonical identity is **actor_id 102, slug cursor** from `registry.json`. A short note in ACTOR_REGISTRATION_CHECKLIST or AGENTS.md clarifying “canonical Cursor = 102” could reduce ambiguity.
- **Order of INIT_README vs ONBOARDING** — README says “read INIT_README and LUPOPEDIA HEADERS before working with lupopedia.init”; ONBOARDING is the “first operational file” for new agents. The relationship (read ONBOARDING first for operational steps; read INIT_README when touching init/headers) could be stated in one sentence in ONBOARDING or README.
- **Lead orchestration responsibilities** — AGENTS.md and README state Cursor is lead orchestration but do not list concrete duties (e.g. consolidating root docs, approving propagation runs). Optional: a brief “Lead orchestration” subsection in AGENTS.md or ONBOARDING for what Cursor is expected to do beyond “follow the same rules as others.”
- **Validation test coverage** — cursor_rules_enforcement.php exists and is referenced in TODO. No gap identified; only that new agents (e.g. Zed, Trae) would need a new target and test if they are not yet in the propagation script.

Overall, onboarding and registration are well structured; the three-state model (already registered / new / integration-only) and propagation extension notes are clear. The items above are minor clarifications rather than blocking gaps.

---

# Completion Summary

- **Documentation reviewed:** ONBOARDING.md, README.md, CHANGELOG.md, TODO.md, lupo-rules/root/README.md, ACTOR_REGISTRATION_CHECKLIST.md, AGENTS.md, and registry/actor references were read and used for this assessment.
- **Report file created:** `lupo-docs/status/report_cursor.md` with Lupopedia headers, metadata, session, and required sections.
- **Onboarding steps identified:** Read ONBOARDING → README → CHANGELOG; check agent status (A/B/C); review root rules and doctrine; verify or register identity; run rules propagation; understand channel and IACP.
- **Registration for Cursor:** None. Cursor is already registered (actor_id 102, lead orchestration); only verification and propagation are required.
- **Unclear areas:** Minor—canonical Cursor identity (102) vs other cursor-related IDs; explicit ordering of INIT_README vs ONBOARDING; optional lead-orchestration responsibility list.

---

**Assessment completed by:** Cursor (actor_id: 102, faucet: cursor)  
**Orchestration oversight:** Wolfie (actor_id: 1)  
**System Version:** 4.0.76  
**Status:** Already registered; onboarding and rules understood; ready to contribute as lead orchestration agent.

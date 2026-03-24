---
lupopedia.headers:
  lupopedia.version: 4.0.76
  lupopedia.schema: doctrine
  file_path_from_root: lupo-docs/doctrine/AGENT_REGISTRY.md
  last_modified_utc: '20260324174926'
  system_version: 4.0.76
  channel_id: 42
  actor_id: 102
  purpose: Canonical human-readable reference for agent identity, propagation targets,
    and IDE capabilities.
  artifact_type: doctrine
  artifact_kind: reference
  when_updated: '20260324174926'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/actors/actor_id/registry.json
    type: references
    weight: 1.0
  - to: lupo-scripts/propagate_agent_rules.php
    type: references
    weight: 1.0
  - to: ONBOARDING.md
    type: references
    weight: 0.9
  - to: AGENTS.md
    type: references
    weight: 0.9
  - to: lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md
    type: references
    weight: 0.9
lupopedia.footer:
  last_verified: '20260324174926'
  last_verified_by: cursor
  next_action:
  - Keep registry table and propagation matrix in sync with registry.json and propagate_agent_rules.php
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Agent Registry Doctrine

This document is the **canonical human-readable reference** for Lupopedia agent identity, IDE propagation support, and faucet capability roles.

---

## Purpose

This document is the canonical human-readable reference for Lupopedia agent identity, IDE propagation support, and faucet capability roles.

It does **not** replace machine authority.

Authority remains split as follows:

| Authority | Location | Role |
|-----------|----------|------|
| **Machine-readable actor identity** | `lupo-database/lupopedia/actors/actor_id/registry.json` | Canonical actor_id, slug, type |
| **Database persistence** | `lupo_actors` (and related tables) | Runtime and seed persistence |
| **Propagation implementation** | `lupo-scripts/propagate_agent_rules.php` | Rule propagation to IDE outputs |
| **Doctrine / coordination reference** | `lupo-docs/doctrine/AGENT_REGISTRY.md` (this file) | Human-readable alignment and onboarding |

This file exists to keep those systems conceptually aligned and to give a single place to answer: who is canonical, who is supported for propagation, and what role each IDE plays.

---

## Canonical Identity Doctrine

- **Canonical actor identity** is determined by the entry in `registry.json` with the matching `slug`.
- The **slug** is the authoritative key for resolving "which actor is this IDE/agent?"
- Filesystem remnants (e.g. legacy `actor_id` folders), historical docs, or legacy IDs are **not** canonical unless they match the current `registry.json` entry.
- Historical actor folders or IDs must **not** be used for new work.

**Example — Cursor:**

- **Cursor** → canonical `actor_id`: **102**, `slug`: `cursor`.
- Actor IDs such as `1002` or `1005` that may appear in historical paths or old artifacts are **not** canonical for new work. Always use **102** and **cursor** from `registry.json`.

---

## Agent Status Model

Before onboarding or registration, determine which state applies.

| State | Meaning | Validation | Next action |
|-------|---------|------------|-------------|
| **A — Already registered** | Your `actor_id` and slug exist in `registry.json` (and in `lupo_actors` when DB is available). | Check `registry.json` for your slug; confirm row in `lupo_actors` if DB is used. | Verify identity; run propagation for your target; contribute. Do **not** register again. |
| **B — New agent** | You do not exist in the actor registry. | No matching slug in `registry.json`. | Follow full [Actor Registration Checklist](../ACTOR_REGISTRATION_CHECKLIST.md): allocate ID, update registry, persist actor, add propagation target if needed, update this doc and ONBOARDING/AGENTS as needed. |
| **C — Exists but needs integration** | You exist in `registry.json` but your IDE is not yet a propagation target or tooling is incomplete. | Slug present in registry; `propagate_agent_rules.php` has no `--target=<you>` or validation test missing. | Add target to `propagate_agent_rules.php`; generate IDE rule files; add validation test; update this doc. Do **not** create a new actor. |

---

## Canonical Agent Registry Table

Canonical source: `lupo-database/lupopedia/actors/actor_id/registry.json`. This table is a human-readable reflection; when in doubt, verify in `registry.json`.

| actor_id | slug | display_name | faucet_type | orchestration_role | propagation_target | canonical_status | notes |
|----------|------|--------------|-------------|-------------------|--------------------|------------------|------|
| 0 | system | System | system | — | — | canonical | System/reserved |
| 1 | wolfie | Wolfie | agent | supporting | — | canonical | Supporting actor; JetBrains |
| 2 | lilith | Lilith | agent | — | — | canonical | Flame header expert (channel 42) |
| 3 | rose | Rose | agent | — | — | canonical | Verify in registry.json |
| 4 | eris | Eris | agent | — | — | canonical | Verify in registry.json |
| 5 | uct-timekeeper | UCT Timekeeper | agent | — | — | canonical | Verify in registry.json |
| 6 | metis | Metis | agent | — | — | canonical | Verify in registry.json |
| 19 | anubis | Anubis | agent | — | — | canonical | Verify in registry.json |
| 42 | antigravity | Antigravity | agent | — | — | canonical | Agent (non-IDE); governance/doctrine |
| 100 | kiro | Kiro | ide_faucet | — | yes | canonical | Schema coordinator; target: kiro |
| 101 | windsurf | Windsurf | ide_faucet | — | yes | canonical | Research, documentation; target: windsurf |
| 102 | cursor | Cursor | ide_faucet | **lead_orchestration** | yes | canonical | Lead orchestration; root consolidation |
| 103 | antigravity | Antigravity (IDE) | ide_faucet | — | no | canonical | IDE faucet; verify propagation support |
| 104 | warp | Warp | ide_faucet | — | no | canonical | Warp terminal/IDE; integration pending |
| 105 | cascade | Cascade | ide_faucet | — | yes | canonical | Cascade IDE; target: cascade |
| 106 | zencoder | Zencoder IDE | ide_faucet | — | no | canonical | Zencoder IDE Agent; documentation and development; propagation pending |
| 1000 | root | Root | human | — | — | canonical | Human orchestrator |
| — | idea / jetbrains | JetBrains / Idea | ide_faucet | — | yes | propagation only | Target `idea`; no single actor_id in registry; Codex (Wolfie flow) |
| — | trae | Trae | ide_faucet | — | no | not yet supported | Verify in registry.json if added |
| — | zed | Zed | ide_faucet | — | no | not yet supported | Verify in registry.json if added |

**Notes:**

- **propagation_target** "yes" means `propagate_agent_rules.php` has a `--target=<slug>` (or alias) and writes output for that IDE.
- **orchestration_role** is from `registry.json` (e.g. `lead_orchestration: true` for Cursor) or from doctrine (supporting actor for Wolfie).
- Where the table says "verify in registry.json", the registry is the source of truth; this doc may lag.

---

## Propagation Targets Matrix

Source: `lupo-scripts/propagate_agent_rules.php`. Valid targets: `all`, `cursor`, `kiro`, `windsurf`, `cascade`, `idea`, `jetbrains` (alias of `idea`).

| target_key | IDE / faucet | supported | output_path | notes |
|------------|--------------|-----------|-------------|--------|
| cursor | Cursor | yes | `.cursor/rules/*.mdc`, `.cursor/lupopedia_rules.json` | Default and explicit target |
| kiro | Kiro | yes | `.kiro/rules/*.md`, `.kiro/lupopedia_rules.json`, `.kiro/README.md` | |
| windsurf | Windsurf | yes | `.windsurf/rules/*.md`, `.windsurf/lupopedia_rules.json`, `.windsurf/README.md` | |
| cascade | Cascade | yes | `.cascade/rules/*.md`, `.cascade/lupopedia_rules.json`, `.cascade/README.md` | |
| idea / jetbrains | JetBrains / IntelliJ IDEA | yes | `.idea/lupopedia_rules.xml` | Alias: `jetbrains` → `idea` |
| all | (all of the above) | yes | All supported output paths | Run without `--target` or `--target=all` |
| antigravity | Antigravity IDE | no | — | Not yet a propagation target; verify in script |
| warp | Warp | no | — | Not yet a propagation target |
| zencoder | Zencoder IDE | no | — | Not yet a propagation target; integration pending |
| trae | Trae | no | — | Not yet a propagation target |
| zed | Zed | no | — | Not yet a propagation target |

---

## IDE Capability / Responsibility Matrix

Based on repo doctrine and AGENTS.md. Conservative; only documented roles are stated.

| slug | primary role | secondary role | root-doc responsibilities | schema/doc responsibilities | continuity expectations |
|------|--------------|----------------|---------------------------|-----------------------------|--------------------------|
| cursor | Lead orchestration | Documentation consolidation | README, CHANGELOG, plan.md, report.md; rule propagation oversight; cross-agent plan merge | — | IACP; maintain resumable state; consolidate drift |
| wolfie | Supporting actor | Domain authority, conflict resolution | — | — | — |
| kiro | Schema coordinator | — | — | TOON/schema alignment | Follow IACP |
| windsurf | Research, documentation | — | — | Status/audit reports | Follow IACP |
| cascade | IDE contributor | — | — | — | Follow IACP |
| antigravity (103) | Governance, doctrine | — | — | — | Verify in docs |
| warp | Terminal/IDE contributor | — | — | — | Integration pending |
| zencoder | Documentation, development | — | — | Table documentation, CHANGELOG | Follow IACP |
| idea / jetbrains | JetBrains IDE consumer | — | — | — | Use propagated rules |

---

## New Agent Addition Workflow

Deterministic steps for adding a new agent. Order matters.

1. **Check if agent already exists**  
   Look up slug (and if needed actor_id) in `lupo-database/lupopedia/actors/actor_id/registry.json`. If present → State A or C; do not create a duplicate.

2. **Determine state**  
   Apply the [Agent Status Model](#agent-status-model): A (already registered), B (new), or C (exists but needs integration).

3. **If new (State B) — update registry**  
   Add an entry to `registry.json` with a unique `actor_id` (non-human: 0–999; human: ≥1000), `slug`, `type`, and optional `lead_orchestration` or other flags. Follow reserved-ID doctrine; do not use AUTO_INCREMENT.

4. **Persist actor**  
   Ensure the actor is represented in `lupo_actors` (install/seed or migration). Use explicit `actor_id`; never rely on `lastInsertId()` for registry-backed tables.

5. **Add propagation target if needed**  
   If the agent’s IDE should receive rules: extend `lupo-scripts/propagate_agent_rules.php` with a new target (e.g. `--target=zed`), output directories, and write function. See [Extending rules propagation](../ACTOR_REGISTRATION_CHECKLIST.md#extending-rules-propagation) in the checklist.

6. **Update AGENT_REGISTRY.md**  
   Add or update the row in the [Canonical Agent Registry Table](#canonical-agent-registry-table) and, if applicable, the [Propagation Targets Matrix](#propagation-targets-matrix) and [IDE Capability / Responsibility Matrix](#ide-capability--responsibility-matrix).

7. **Update ONBOARDING.md / AGENTS.md**  
   If the agent has a special role (e.g. new lead or supporting actor), update role lists and "first files to read" or IDE tables in ONBOARDING and AGENTS so they stay aligned with this doctrine.

8. **Log the change**  
   Add an entry to `CHANGELOG.md` under the current version (e.g. 4.0.76) describing the new agent or new propagation target.

---

## Project Context for Agents

Agents operate within project contexts but are **not project-owned identities**:

- **Agent Identity:** Independent of project identity, registered in actor registry
- **Project Participation:** Agents may work across multiple projects via junction tables
- **Default Context:** Agents may have `default_project_id` for convenience, not ownership
- **Project Registry:** Separate from agent registry, see [PROJECT_REGISTRY_DOCTRINE.md](PROJECT_REGISTRY_DOCTRINE.md)

**Agent-Project Relationship:**
- Agents are federation-scoped entities
- Projects are federation-node-scoped entities
- Relationship is many-to-many via junction tables
- Agent identity remains stable across project changes

---

## Historical / Legacy Identity Handling

- **Historical IDs** (e.g. old actor_id values that no longer match `registry.json`) may remain in filesystem artifacts, logs, or legacy docs. They are preserved for lineage and traceability.
- They **do not** override canonical identity. New work must use the **current** canonical identity from `registry.json` (actor_id and slug).
- Do not reuse legacy IDs for new actors. Do not point new tooling or docs at legacy IDs as the primary identity. When documenting history, you may reference legacy IDs with a note that they are non-canonical for new work.

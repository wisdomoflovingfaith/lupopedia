---
lupopedia.headers:
  lupopedia.schema: "requirements"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/requirements/core_system.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "core_requirements"
  purpose: "Core system requirements for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approved_by_actor_id: 1
  approved_utc: 20260326192115
  next_action:
    - "Use this matrix as the baseline for Phase 2 and Softaculous preflight"
---

# Core System Requirements

1. The system MUST install cleanly into a subdirectory deployment path.
2. The system MUST run on PHP 7.4+ without unsupported language features (see `php-7-4-compatibility.md`).
3. The system MUST preserve core Crafty Syntax behaviors required for migration continuity.
4. The system MUST keep routing, session, and auth paths deterministic and testable.
5. The system MUST avoid environment-coupled assumptions.
6. The system MUST expose predictable installer behavior for automation tooling.

## Non-Negotiable Stability Rules

- No schema drift during normal install flow.
- No manual operator patching as a release requirement.
- No hidden prerequisite outside repository-defined setup.

## Core Concept Validation Requirements

For 4.1.0 release eligibility, the following concepts must be explicit, non-contradictory, and cross-linked to canonical doctrine:

1. Channels: communication scope boundary.
2. Threads: durable conversation/work units within channels.
3. Actors: operational identity layer.
4. Agents: capability/runtime configuration layer.
5. Auth Users: authentication principal layer.
6. Faucets: execution interface layer.
7. Collections: structured knowledge grouping layer.

## Canonical Reference Targets

All concept definitions must be resolved against `lupo-docs/doctrine/` as of the 4.1.0 release baseline, with contradictions resolved before approval.

- `lupo-docs/doctrine/ACTOR_AGENT_AUTH_USER_MODEL.md`
- `lupo-docs/doctrine/EFFECTIVE_ACTOR_RESOLUTION.md`
- `lupo-docs/database/lupopedia/tables/active/`
- `lupo-docs/doctrine/migrations/`

Any unresolved contradiction across these layers is release-blocking for 4.1.0.

## Phase 1 Verification Matrix (20260326)

| Requirement | Verification Check | Evidence Source | Status |
|-------------|--------------------|-----------------|--------|
| Clean subdirectory install path | Path bootstrap and install redirect resolve under detected public path | `index.php` dynamic `LUPOPEDIA_PUBLIC_PATH` resolution and install redirect logic | pass |
| PHP 7.4+ compatibility baseline | Core doctrine and runtime constraints remain php7.4-compatible | `AGENTS.md` runtime doctrine and no framework/package-manager dependency in release docs | pass |
| Crafty Syntax migration continuity | Upgrade path remains explicit as release gate | `prd/README.md` Softaculous-first upgrade path and checklists | pass |
| Deterministic routing/session/auth paths | Core routing/bootstrap surfaces remain deterministic and testable | `index.php` slug extraction and `module-loader.php` routing order | pass |
| Environment portability | Core path constants and subdirectory handling present in entry surfaces | `index.php`, `module-loader.php` path constants and no root-only requirement in release model | pass |
| Predictable installer behavior | Install redirect doctrine and preflight checklists define deterministic flow | `index.php` install redirect + acceptance checklist set | pass |

## Phase 1 Evidence Snapshot

- Deterministic ID mechanism exists and is implemented in `lupo-scripts/generate_content_id.py` with locking, path hash influence, and collision checks.
- Subdirectory deployment handling is explicitly defined in entrypoint constants and release doctrine.
- Core requirements are mapped to concrete verification checks for repeatable approval audits.

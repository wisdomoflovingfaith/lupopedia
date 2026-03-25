---
lupopedia.headers:
  when_updated: "20260325210000"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/version.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/version.md"
  last_modified_utc: "20260325210000"
  channel_id: 42
  actor_id: 26
  actor_name: "cascade"
  delegation_chain: "26:1"
  artifact_type: "documentation"
  artifact_kind: "version_history"
  purpose: "Version history and upgrade notes for Lupopedia"
  tags: ["version", "changelog", "upgrade", "4.0.88"]
  namespace: "documentation"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.88/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/versions/4.0.87/CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/versions/", type: "references", weight: 0.9 }
lupopedia.footer:
  last_verified: "20260325210000"
  verified_by:
    identity_type: "actor"
    actor_id: 26
    agent_name_identity: "THOTH"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cascade"
  orchestrator: "26:1"
  next_action: "Keep version history current with each release"
---
# file: Lupopedia Version History — delegation: 26:1 — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/version.md

# Lupopedia version history

Current version: **4.0.88**  
Date: 2026-03-25  
Status: Development In Progress

## Summary of changes (4.0.88)

**Development Focus**: Documentation polish and system stability
- **Version initialization**: Updated canonical version markers to 4.0.88 (`LUPEDIA_VERSION`, `lupo-config/global_atoms.yaml`, `lupo-includes/version.php`)
- **Documentation structure**: Created comprehensive 4.0.88 version documentation with README, PLAN, TODO, CHANGELOG, and DOCTRINE
- **Development priorities**: WS6 test suite updates (carryover from 4.0.87), post-release monitoring, targeted improvements
- **Documentation cleanup**: Major lupo-docs organization and cleanup effort underway

## Summary of changes (4.0.87) — Released 2026-03-25

**Major Release**: Identity Model Clarification and Edge Consolidation
- **WS3: Identity Model Clarification**: Implemented 5-layer identity model (Auth User, Actor, Department, Agent, Faucet)
- **WS2: Edge Model Consolidation**: Consolidated fragmented edge tables into single canonical `lupo_edges` table
- **WS1: Decision System Cleanup**: Removed broken decision services and cleaned up CIP remnants
- **ERQ-006 Release Signoff**: Complete release authorization with all blockers resolved
- **Database updates**: All core personas (1-6) assigned to Department 1, identity model properly implemented
- **Documentation**: AGENTS.md updated with identity layers, IDENTITY_LAYERS_DOCTRINE.md created

## Summary of changes (4.0.86)

**Development Version**: Post-4.0.85 stabilization
- **Version bump**: Updated canonical version markers to 4.0.86
- **Preparation work**: Set up for 4.0.87 major workstream execution
- **Documentation updates**: Version documentation prepared for identity model work

## Summary of changes (4.0.85)

**Development Version**: Feature development and stabilization
- **Version bump**: Updated canonical version markers to 4.0.85
- **Feature development**: Continued development on identity and edge models
- **Documentation preparation**: Set up documentation structure for major changes

## Summary of changes (4.0.84)

**Development Version**: Infrastructure and tooling improvements
- **Version bump**: Updated canonical version markers to 4.0.84
- **Tooling enhancements**: Improved development and documentation tooling
- **Preparation**: Set up for identity model and edge consolidation work

## Summary of changes (4.0.80-4.0.83)

**Development Versions**: Various feature developments and stabilization
- **Incremental improvements**: Multiple development cycles with feature additions
- **Documentation updates**: Ongoing documentation improvements and organization
- **System stability**: Continued focus on system stability and performance

## Summary of changes (4.0.79)

**Development Version**: Post-4.0.78 development
- **Version bump**: Post–4.0.78 release. Active development version
- **Carry-forward work**: Unfinished work from 4.0.78 carried forward
- **Focus areas**: Top 50 operational table documentation, bounded header/namespace cleanup

## Summary of changes (4.0.78) — Released 2026-03-16

**Major Release**: Top 50 reframing and documentation
- **Released and tagged**: Top 50 reframing; 25 table docs completed
- **Namespace doctrine**: Validator, audit, and cleanup improvements
- **Documentation**: Comprehensive table documentation completed

## Summary of changes (4.0.77) — Released 2026-03-16

**Major Release**: Constitutional and tooling enhancements
- **Released and tagged**: Constitutional root rules, LUPOPEDIA_HEADERS enhancements
- **Bayesian Decision Foundation**: Foundation work for decision systems
- **Tooling**: Header tooling (export/import/validate) implemented

## Summary of changes (4.0.76)

**Major Release**: Project System and production readiness
- **Released and tagged**: Project System schema, application, testing
- **Production-ready**: Windsurf review final completion, upgrade guide
- **Validation**: Install and upgrade validation performed

## Summary of changes (4.0.75)

**Major Release**: Rules and governance
- **Released and finalized**: Version bump, rules and governance updates
- **Multi-agent propagation**: Enhanced multi-agent coordination
- **Safe DB Operations**: DB009 and safe database operations implemented

## Summary of changes (4.0.74)

**Documentation Release**: Architecture clarification
- **Documentation consolidation**: 12-table install expansion, path normalization
- **TOON reconciliation**: TOON/docs reconciliation completed
- **Table count**: Table count 159, pushed to GitHub as 4.0.74

## Recent version history

| **4.0.88** | 2026-03-25 | Development: Documentation polish, WS6 completion |
| **4.0.87** | 2026-03-25 | RELEASED: Identity model, edge consolidation, decision cleanup |
| **4.0.86** | 2026-03-24 | Development: Preparation for 4.0.87 workstreams |
| **4.0.85** | 2026-03-23 | Development: Feature development and stabilization |
| **4.0.84** | 2026-03-22 | Development: Infrastructure and tooling improvements |
| **4.0.80-4.0.83** | Various | Development: Incremental improvements and stabilization |
| **4.0.79** | 2026-03-21 | Development: Post-4.0.78 carry-forward work |
| **4.0.78** | 2026-03-16 | RELEASED: Top 50 reframing, documentation |
| **4.0.77** | 2026-03-16 | RELEASED: Constitutional rules, tooling |
| **4.0.76** | 2026-03-15 | RELEASED: Project System, production-ready |
| **4.0.75** | 2026-03-14 | RELEASED: Rules, governance, multi-agent |
| **4.0.74** | 2026-03-14 | RELEASED: Documentation consolidation |
| **4.0.73** | 2026-03-12 | Development: Task consolidation, upgrade validation |
| **4.0.72** | 2026-03-12 | Development: Version bump, finalization |
| **4.0.71** | 2026-03-12 | Development: Documentation framework, semantic navbar |

## Current Development Status

### 4.0.88 (In Progress)
**Focus Areas**:
- WS6 test suite updates (LILITH)
- Documentation polish and cleanup (THOTH)
- Post-release monitoring (ANUBIS)
- Targeted improvements and optimizations

**Expected Release**: 2026-04-15 (approximate)

### 4.0.87 (Stable - Released 2026-03-25)
**Major Achievements**:
- 5-layer identity model implemented
- Edge model consolidated
- Decision system cleaned up
- All critical workstreams complete

**System State**: Production-ready, stable, enhanced security

## Upgrade notes

### Current Upgrade Path
- **Crafty Syntax 3.7.5 → 4.0.88**: Supported upgrade path
- **4.0.87 → 4.0.88**: Expected to be seamless (no schema changes planned)
- **Earlier versions**: Upgrade via 4.0.87 stable release

### Session Context
To drive CLI identity from file when DB is down or for a fixed identity, edit `lupo-database/session.md` (YAML frontmatter: actor_name, channel_id, session_id, department_id, thread_id, paired_actor_id, etc.). See `lupo-docs/lupopedia_whoami_readme.md`.

### Version in Code
Use `get_lupo_version()` or `LUPOPEDIA_VERSION`; avoid hardcoding version strings in help/CLI.

### Documentation Standards
All documentation must follow LUPOPEDIA HEADERS standards. See [LUPOPEDIA HEADERS documentation](doctrine/LUPOPEDIA_HEADERS/README.md) for requirements.

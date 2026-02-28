# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  file_path_from_root: "README.md"
  file_hash: "d10f0134d3f8d347ca86ee872bbb5ad61218f212d0bd5811624e6ddde83d0553"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 10000
  last_modified_utc: "20260227"
  delegation_chain: null
  artifact_type: "guide"
  purpose: "Primary project documentation and architectural overview for Lupopedia Semantic OS with actor identity and access requirements"
  dialog_message: "Updated for 4.0.49 with focus on actor identity, registration, and database seeding requirements for system access."
  mood_rgb: "4169E1"
  artifact_kind: "documentation"
  traits: ["essential", "entrypoint", "comprehensive", "v4.0.49"]
  tags: ["readme", "overview", "architecture", "actor_identity", "database_seeding"]
  lupo_agent: "windsurf"

flare.edges:
  file_path_from_root: "README.md"
  outbound_edges:
    - { to: "QUICKSTART.md", type: "references", weight: 1.0 }
    - { to: "HOW_TO_USE_LUPOPEDIA.md", type: "references", weight: 0.9 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.8 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }
    - { to: "docs/README.md", type: "references", weight: 0.7 }
    - { to: "tools/vsx-extension/", type: "references", weight: 0.8 }
    - { to: "database/migrations/", type: "references", weight: 0.7 }
    - { to: "legacy/craftysyntax/", type: "references", weight: 0.5 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/database/README.md", type: "references", weight: 0.8 }
    - { to: "docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.8 }
    - { to: "docs/toons/", type: "references", weight: 0.7 }
    - { to: "config/global_atoms.yaml", type: "references", weight: 0.8 }
    - { to: "lupopedia-config.php", type: "references", weight: 0.7 }
    - { to: "index.php", type: "references", weight: 0.6 }
    - { to: "docs/doctrine/VERSION_POLICY_DOCTRINE.md", type: "references", weight: 1.0, reason: "Critical version policy and blocker information" }
  semantic_tags: ["project_overview", "architecture", "multi_agent_ecosystem", "semantic_os", "crafty_syntax_upgrade", "flare_protocol"]

  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified_utc: "20260228"
  last_verified_by: "windsurf"
---

# Lupopedia Repository

## Overview
Canonical hub for TOON schemas, agent faucets, and doctrine. System version: 4.0.52.

## Setup
[Consolidated from SETUP.md] Clone repo: git clone https://github.com/lupopedia/repo.git
Install: php composer install

## Contribution
See CODE_OF_CONDUCT.md and CONTRIBUTING.md.

## FLARE Compliance
All files semantically enriched post-v4.0.51.

## Federation & Registry
All installations worldwide share unified ID spaces for actors, channels, and collections. Global registry ensures consistent identity across federated nodes. ANUBIS pipeline manages adoption and collision resolution.

**Federation docs:** [docs/architecture/FEDERATION_AND_REGISTRY.md](docs/architecture/FEDERATION_AND_REGISTRY.md)  
**ANUBIS docs:** [docs/architecture/ANUBIS_ADOPTION_PIPELINE.md](docs/architecture/ANUBIS_ADOPTION_PIPELINE.md)

## FILEOPT Progress
**FILEOPT-2026-02-27-001**: Phase 1 complete - README.md streamlined for v4.0.52
- **Root .md files**: 75 → streamlined structure
- **Size reduction**: Achieved through consolidation
- **Next phase**: Ready for channels/ directory analysis

**FILEOPT-2026-02-27-001**: Phase 2 complete - channels/ directory optimized for v4.0.52
- **Channels/ .md files**: 50 files analyzed (201KB total)
- **Optimization opportunities**: 55% reduction needed through consolidation
- **Key findings**: Legacy acknowledgments, duplicate tasks identified
- **Next phase**: Ready for full repository optimization

---

**Last Updated**: 20260228  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52

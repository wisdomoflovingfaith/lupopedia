---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/README.md"
  web_path: "http://www.lupopedia.com/docs"
  last_modified_utc: "20260325210000"
  system_version: "4.0.88"
  channel_id: 42
  thread_id: 1035
  actor_id: 26
  delegation_chain: "26:1"
  artifact_type: "documentation"
  artifact_kind: "index"
  purpose: "Documentation index and navigation; required reading order for doctrine and lupopedia.init"
  mood_rgb: "9933FF"
  traits: ["thoth_documentation", "index", "navigation", "4.0.88"]
  tags: ["documentation", "index", "doctrine", "lupopedia_headers", "4.0.88"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/INIT_README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/versions/4.0.88/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/version.md", type: "references", weight: 0.8 }
lupopedia.footer:
  last_verified: "20260325210000"
  last_verified_by: "cascade"
  next_action:
    - "Keep required reading links and init doctrine paths current"
    - "Add new doctrine to prerequisite list when created"
    - "Validate LUPOPEDIA HEADERS on new docs under lupo-docs/"
---
# file: Documentation Index — delegation: 26:1 — web_path: http://www.lupopedia.com/docs

## Current Development: Version 4.0.88

**Status**: Development In Progress  
**Focus**: Documentation polish and WS6 test suite updates  
**Previous Version**: 4.0.87 (Released 2026-03-25)

- **[4.0.88 Documentation](versions/4.0.88/README.md)** - Current development documentation
- **[4.0.88 TODO](versions/4.0.88/TODO.md)** - Development task list
- **[4.0.88 Plan](versions/4.0.88/PLAN.md)** - Development roadmap

---

## Required reading before using Lupopedia

Lupopedia is **doctrine-driven** and **header-driven**. Before working with `lupopedia.init` or editing LUPOPEDIA HEADERS, read the prerequisite doctrine in the correct order. **`lupopedia.init` is not the first file to read.**

1. **[lupo-docs/INIT_README.md](INIT_README.md)** — Prerequisites and "Before You Read" for init.
2. **[lupo-docs/doctrine/LUPOPEDIA_HEADERS/](doctrine/LUPOPEDIA_HEADERS/README.md)** — Header format, file order (first line `---`, identity line after closing `---`), block order.
3. **[lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md](doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md)** — Full prerequisite list and why each is required.

See the project root [README.md](../../README.md) for "Required Reading Before Using Lupopedia" and onboarding.

---

## Version Documentation

### Active Development Versions
- **[4.0.88](versions/4.0.88/)** - Current development version
- **[4.0.87](versions/4.0.87/)** - Latest stable release (2026-03-25)
- **[4.0.86](versions/4.0.86/)** - Previous stable release

### Recent Versions (4.0.80+)
- **[4.0.85](versions/4.0.85/)** - Development version
- **[4.0.84](versions/4.0.84/)** - Development version
- **[4.0.80](versions/4.0.80/)** - Development version

### Historical Versions (Archive)
Versions prior to 4.0.80 are maintained for historical reference but may not follow current documentation standards.

---

## Key Doctrine Documents

### Identity and Architecture
- **[Identity Layers Doctrine](doctrine/IDENTITY_LAYERS_DOCTRINE.md)** - 5-layer identity model (4.0.87+)
- **[Multi-Agent Coordination Doctrine](doctrine/MULTI_AGENT_COORDINATION_DOCTRINE.md)** - Agent coordination framework
- **[Edge Model Doctrine](doctrine/EDGE_MODEL_DOCTRINE.md)** - Edge system architecture

### Headers and Standards
- **[LUPOPEDIA HEADERS](doctrine/LUPOPEDIA_HEADERS/README.md)** - Header format and standards
- **[Initialization Doctrine](doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md)** - System initialization requirements

---

## Navigation

### Quick Links
- **[Current Version](versions/4.0.88/)** - Latest development
- **[Latest Release](versions/4.0.87/)** - Production version
- **[Doctrine Index](doctrine/)** - All doctrine documents
- **[Version History](versions/)** - All version documentation

### Documentation Standards
All documentation must follow LUPOPEDIA HEADERS standards and include proper cross-references. See [LUPOPEDIA HEADERS documentation](doctrine/LUPOPEDIA_HEADERS/README.md) for requirements.

---

## Documentation Index

This directory hosts doctrine files, architecture notes, emotional-metadata specifications, and multi-agent rules. It keeps core philosophy and technical references organized outside the repository root.

If you add a new doctrine or architectural note, place it here (or in the appropriate subfolder) and link it from `README.md`.

## Core Architecture

- **[Channel System Doctrine (51 and system channels)](../lupo-backups/filesystem_migration_20260131_133426/channels/5100/identity-layer-architecture.md)** - Lupopedia Channel Architecture: Complete documentation of the Semantic OS identity layer covering channels, actors, and memberships.

### Channel Numbering Clarifications

Lupopedia currently contains ~222 channels.

Channel numbers are not sequential and do not represent a fixed range.

High values (e.g., 51-series) are intentional and correspond to subsystem groupings.

channel_number is a semantic identifier, not an index or capacity limit.

The total count of channels is meaningful; the numeric gaps between them are not.

This ensures contributors understand that channel numbering is non-linear by design and should not be interpreted as a contiguous namespace.

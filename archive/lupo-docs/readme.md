---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260330"
  file_path_from_root: "lupo-docs/readme.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/README.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: index
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: Documentation Index — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/README.md

## Footer Validation and Staleness Policy

All Lupopedia documentation must include a canonical `lupopedia.footer` block. Footer validation rules require:
- `last_verified` (UTC, 14 digits)
- `verified_by` (object with at minimum: `identity_type`, `actor_id`)
- `verified_via` (object with at minimum: `type`, `faucet_slug`)

Artifacts with `last_verified` earlier than `20260301000000` UTC are considered stale and must be semantically revalidated before updating the footer. See [doctrine/LUPOPEDIA_HEADERS/README.md](doctrine/LUPOPEDIA_HEADERS/README.md) for canonical rules and validator details.

## Subdirectory-Only Installation & Monitoring Widget (Critical)

**Lupopedia must always be installed in a subdirectory of your site (never at the web root).**

**Monitoring and Analytics:**
- Lupopedia provides a dynamic JavaScript endpoint (`lupopedia_js.php`) that must be embedded in your host site’s pages (outside the Lupopedia directory).
- Example usage:
  ```html
  <script src="/your-subdirectory/lupopedia_js.php"></script>
  ```
- The system must NOT assume the folder is named `lupopedia`—the installer will detect and store the correct subdirectory.
- All monitoring, visitor tracking, and content interaction features depend on this script being present on the host site.

**Never install Lupopedia at the web root.** All paths, cookies, and monitoring logic assume a subdirectory context.

See also: [Semantic Monitoring Widget PRD](versions/4.0.93/prd/semantic_monitoring_widget.md)

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

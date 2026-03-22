---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/README.md"
  version_when_written: "4.0.84"
  web_path: "http://www.lupopedia.com/docs"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "index"
  purpose: "Documentation index and navigation; required reading order for doctrine and lupopedia.init."
  tags: ["documentation", "index", "doctrine", "lupopedia_headers", "4.0.71"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/INIT_README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.8 }
lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  last_verified_by: "cursor"
  next_action:
    - "Keep required reading links and init doctrine paths current"
    - "Add new doctrine to prerequisite list when created"
    - "Validate LUPOPEDIA HEADERS on new docs under lupo-docs/"
---
# file: Documentation Index — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/docs

## Required reading before using Lupopedia

Lupopedia is **doctrine-driven** and **header-driven**. Before working with `lupopedia.init` or editing LUPOPEDIA HEADERS, read the prerequisite doctrine in the correct order. **`lupopedia.init` is not the first file to read.**

1. **[lupo-docs/INIT_README.md](INIT_README.md)** — Prerequisites and "Before You Read" for init.
2. **[lupo-docs/doctrine/LUPOPEDIA_HEADERS/](doctrine/LUPOPEDIA_HEADERS/README.md)** — Header format, file order (first line `---`, identity line after closing `---`), block order.
3. **[lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md](doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md)** — Full prerequisite list and why each is required.

See the project root [README.md](../../README.md) for "Required Reading Before Using Lupopedia" and onboarding.

---

# Documentation Index

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

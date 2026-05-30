---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/3.0.x/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/3.0.x/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: null
  artifact_kind: historical_snapshot
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "archived"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: DIRECTORY_STRUCTURE_DOCTRINE (archived 3.0.x snapshot) — delegation: wolfie:root

## Verbatim file body before 4.0.x exorcism

The block below preserves the file as it existed when it still claimed mixed 4.0.84 headers and 3.0.0 body content.

````text
---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/DIRECTORY_STRUCTURE_DOCTRINE.md"
  version_when_written: "4.0.84"
  file_hash: "d1ca77df3033aa8140004b9f84eeafdcb08a22fc9c7261911dd50b31c26d1bcf"
  last_updated_utc: "20260228"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for DIRECTORY_STRUCTURE_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["directory_structure_doctrinemd"]
  lupo_agent: "windsurf"
  needs_review: ["delegation_chain"]
lupopedia.edges:
  outbound_edges:
    - to: "docs/prd/29_project_structure.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
  deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.0
file.channel: doctrine
---

# DIRECTORY_STRUCTURE_DOCTRINE.md
Lupopedia Directory Structure Doctrine (Post-Realignment)
System Version: 3.0.0
file.channel: doctrine

Purpose
This doctrine defines the canonical directory structure of the Lupopedia OS following the structural realignment in commit 40eecfc.
It explains the intent behind each directory, the philosophy guiding its organization, and the rules for future contributors when adding, moving, or removing files.

The directory tree is not a filesystem -- it is a semantic map of the OS.

1. Foundational Principles
1.1 Stewardship over Storage
Directories exist to preserve meaning, not to store files.
Every directory must reflect a conceptual boundary in the system.

1.2 Doctrine Before Implementation
The structure is shaped by:

- emotional geometry
- schema federation
- pattern ethics
- kapakai awareness
- legacy preservation
- Crafty Syntax integration

Implementation follows doctrine, not the other way around.

1.3 No Dead Directories
A directory with no living purpose must be removed.
A directory with a purpose must be documented.

1.4 Emotional Metadata Is First-Class
Directories that store emotional geometry, mood registries, or kapu events are treated with the same importance as schema or doctrine.

2. Top-Level Directory Overview
2.1 /docs/ -- The System's Memory
Contains all doctrine, developer guidance, schema descriptions, and TOON metadata.

Subdirectories:

/docs/channels/doctrine/
The heart of the system's philosophy.
Contains:

- emotional geometry
- prohibitions
- pattern ethics
- schema federation
- UTC timekeeper
- Wolfmind doctrine
- anchors and origin stories

Rule:
Every doctrine file must include a Wolfie header and a clear statement of intent.

/docs/channels/overview/
High-level conceptual maps:

- philosophy
- versioning doctrine
- structural realignment
- migration summaries

Rule:
Overview files describe why the system is shaped this way.

/docs/channels/developer/
Guidance for contributors:

- code update plans
- metadata recommendations
- developer ethics
- onboarding notes

Rule:
Developer docs must teach, not merely instruct.

/docs/channels/schema/
The canonical description of the database schema.

Rule:
Schema docs must match the active schema lineage and the 199-table doctrine.

/docs/toons/
The emotional and semantic OS.

Contains:

- emotional constellations
- emotional frameworks
- emotional stars
- emotional translations
- mood registry
- kapu events
- truth questions and answers
- agent experiences

Rule:
TOON files represent living emotional structures.
They must never be treated as static assets.

3. /database/ -- The System's Body
3.1 /database/migrations/
Contains the active migration lineage.

After realignment:

- legacy migrations removed
- abandoned schema paths removed
- Crafty Syntax -> Lupopedia path preserved
- 199-table doctrine enforced

Rule:
Every migration must reflect the current doctrine.
No foreign keys.
No stored procedures.
No triggers.
Timestamps must be BIGINT(14) YYYYMMDDHHIISS.

3.2 /database/toon_data/
Contains database-aligned TOON data:

- lupo_contents
- lupo_dialog_messages
- lupo_channel_state
- lupo_operators

Rule:
Database TOON data must mirror the schema and emotional geometry.

3.3 /database/install/
Installer seeds, mapping files, and schema generators.

Rule:
Installer must be a guided conversation, not a static script.

4. /channels/ -- Removed in Realignment
The legacy channel system was removed entirely.

Directories such as:

- channels/system/
- channels/dev-main-thread/
- channels/test_awareness_channel/
- channels/GOV-PROGRAMMERS-001/

...were deprecated and removed.

Rule:
Channels are now conceptual, not filesystem-based.

Routing and identity are handled through doctrine and emotional geometry, not directory trees.

5. /lupopedia/ -- The Mirror
Contains the mirrored copy of:

- schema
- migrations
- doctrine
- TOON data
- mapping files

Rule:
The mirror must always reflect the canonical root.
No drift allowed.

6. /scripts/ -- System Tools
Contains:

- ORM helpers
- migration utilities
- schema alignment tools

Rule:
Scripts must never modify doctrine.
Scripts operate on data, not philosophy.

7. Root Files
README.md
Must reflect the current ontology.

complete_schema.txt
Must reflect the active schema after each migration cycle.

plan_for_crafty_syntax.md
The canonical 7-cycle migration plan.

STRUCTURAL_REALIGNMENT.md
The record of commit 40eecfc.

8. Rules for Future Contributors
8.1 No new directories without doctrine
Every new directory must include:

- a Wolfie header
- a statement of purpose
- its relationship to emotional geometry

8.2 No resurrection of legacy structures
Removed directories must not return unless explicitly justified in doctrine.

8.3 Emotional metadata is mandatory
Any new subsystem must define:

- its emotional footprint
- its kapakai boundaries
- its stewardship responsibilities

8.4 Doctrine and Path Authority

**Collections do not override directory structure.** Collection slugs may appear similar to directory names (e.g., "core-docs"), but this is convention only. File locations are determined by:

1. **Directory doctrine** - This document defines canonical directory structure
2. **Path constants** - `file_path_from_root` in headers specifies exact file location
3. **Filesystem reality** - Actual file system layout, not derived from collection membership

**Namespace does not override directory structure.** Namespace values classify artifacts for policy but do not determine where files live on disk.
If filesystem and doctrine disagree, doctrine wins.

9. Summary
The directory structure is now:

- doctrine-aligned
- emotionally grounded
- schema-consistent
- free of legacy ghosts
- ready for Crafty Syntax integration

````


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\.kiro\specs\flip-v2-implementation\requirements.md"
  file_hash: "4146243f1ff4958c659302ca4f9fb4275713ba24caf2c951a345fdf44e32204a"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: ".kiro\specs\flip-v2-implementation\requirements.md"
  file_hash: "13158363133ecf02253f02b397af7c75cc0a0df7616ad6d2e64a068ef45af5d0"
  file_path_from_root: ".kiro\specs\flip-v2-implementation\requirements.md"
  file_hash: "b161ba7fbc27a1db43267ebdb735bd7d5ed5c46495a304f43663d258fc470e3e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Requirements Document"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["kiro", "specs", "flip-v2-implementation", "requirementsmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Requirements Document

## Introduction

FLIP v2 (File-Level Inference Protocol version 2) extends the existing FLIP v1 system by adding database persistence for parsed FLIP header and footer metadata. This enables efficient querying, semantic relationship tracking, and artifact integrity verification across the Lupopedia codebase. The implementation targets Lupopedia version 4.0.37 and maintains backward compatibility with existing FLIP v1 files while introducing new capabilities for metadata extraction, storage, and relationship mapping.

## Glossary

- **FLIP**: File-Level Inference Protocol - a YAML-based metadata system embedded in file headers/footers
- **Artifact**: A file containing FLIP metadata (markdown files in channels/, docs/, etc.)
- **Scanner**: A PHP component that extracts FLIP metadata from files
- **FLIP_Artifact_Table**: The `lupo_flip_artifacts` database table storing parsed metadata
- **Header_JSON**: JSON-encoded FLIP header metadata extracted from YAML
- **Footer_JSON**: JSON-encoded FLIP footer metadata extracted from YAML
- **Artifact_Hash**: SHA-256 hash of file content for integrity verification
- **Backfill_Process**: One-time migration to populate FLIP_Artifact_Table from existing files
- **Edge_Mapper**: Component that creates semantic relationships in `lupo_edges` from FLIP footers
- **YMDHIS_Timestamp**: BIGINT timestamp in YYYYMMDDHHIISS UTC format
- **Actor_ID**: Universal identity key (0-9999 for AI agents, 10000+ for humans)
- **Channel_ID**: Identifier for semantic channels in the system
- **X_Lupo_Forwarded**: Metadata tracking forwarding chain between actors

## Requirements

### Requirement 1: Database Schema Creation

**User Story:** As a system architect, I want a dedicated database table for FLIP artifacts, so that metadata can be efficiently queried and analyzed.

#### Acceptance Criteria

1. THE FLIP_Artifact_Table SHALL contain column `flip_artifact_id` as BIGINT primary key
2. THE FLIP_Artifact_Table SHALL contain column `file_path_from_root` as VARCHAR(500) for unique file identification
3. THE FLIP_Artifact_Table SHALL contain column `artifact_kind` as VARCHAR(50) for categorization
4. THE FLIP_Artifact_Table SHALL contain column `channel_id` as BIGINT for channel association
5. THE FLIP_Artifact_Table SHALL contain column `actor_id` as BIGINT for creator identification
6. THE FLIP_Artifact_Table SHALL contain column `agent_slug` as VARCHAR(255) for agent identification
7. THE FLIP_Artifact_Table SHALL contain column `agent_type` as VARCHAR(64) for agent categorization
8. THE FLIP_Artifact_Table SHALL contain column `system_version` as VARCHAR(20) for version tracking
9. THE FLIP_Artifact_Table SHALL contain column `last_modified_ymd` as BIGINT for date tracking
10. THE FLIP_Artifact_Table SHALL contain column `x_forward_from_actor_id` as BIGINT for forwarding source
11. THE FLIP_Artifact_Table SHALL contain column `x_forward_to_actor_id` as BIGINT for forwarding destination
12. THE FLIP_Artifact_Table SHALL contain column `x_lupo_forwarded` as VARCHAR(64) for forwarding metadata
13. THE FLIP_Artifact_Table SHALL contain column `header_json` as TEXT for complete header storage
14. THE FLIP_Artifact_Table SHALL contain column `footer_json` as TEXT for complete footer storage
15. THE FLIP_Artifact_Table SHALL contain column `file_hash` as VARCHAR(64) for integrity verification
16. THE FLIP_Artifact_Table SHALL contain column `created_ymdhis` as BIGINT for creation timestamp
17. THE FLIP_Artifact_Table SHALL contain column `updated_ymdhis` as BIGINT for modification timestamp
18. THE FLIP_Artifact_Table SHALL contain column `is_deleted` as TINYINT with default value 0 for soft deletes
19. THE FLIP_Artifact_Table SHALL contain column `deleted_ymdhis` as BIGINT with default value 0 for deletion timestamp
20. THE FLIP_Artifact_Table SHALL NOT use foreign keys, triggers, stored procedures, or views
21. THE FLIP_Artifact_Table SHALL be compatible with MySQL 8.0+, MariaDB 10.5+, and PostgreSQL

### Requirement 2: Database Indexes

**User Story:** As a developer, I want efficient query performance on FLIP artifacts, so that metadata lookups are fast.

#### Acceptance Criteria

1. THE FLIP_Artifact_Table SHALL have an index on `file_path_from_root` for file lookup queries
2. THE FLIP_Artifact_Table SHALL have a composite index on (`actor_id`, `last_modified_ymd`) for actor timeline queries
3. THE FLIP_Artifact_Table SHALL have a composite index on (`channel_id`, `last_modified_ymd`) for channel timeline queries
4. THE FLIP_Artifact_Table SHALL have a composite index on (`x_forward_from_actor_id`, `x_forward_to_actor_id`) for forwarding chain queries
5. THE FLIP_Artifact_Table SHALL have a composite index on (`artifact_kind`, `last_modified_ymd`) for kind-based queries
6. THE FLIP_Artifact_Table SHALL have an index on `is_deleted` for soft delete filtering
7. THE FLIP_Artifact_Table SHALL have an index on `system_version` for version-based queries

### Requirement 3: Migration Script Creation

**User Story:** As a database administrator, I want a migration script for FLIP v2, so that the schema can be deployed consistently.

#### Acceptance Criteria

1. THE Migration_Script SHALL be located at `database/migrations/upgrade_flip_v2.sql`
2. THE Migration_Script SHALL create the FLIP_Artifact_Table with all required columns
3. THE Migration_Script SHALL create all required indexes
4. THE Migration_Script SHALL use table prefix variable for multi-tenant support
5. THE Migration_Script SHALL be idempotent and safe to run multiple times
6. THE Migration_Script SHALL include comments documenting each column purpose
7. THE Migration_Script SHALL follow Lupopedia SQL formatting conventions

### Requirement 4: YAML Header Parser

**User Story:** As a scanner component, I want to parse YAML headers from markdown files, so that metadata can be extracted.

#### Acceptance Criteria

1. WHEN a markdown file contains a YAML header block, THE Scanner SHALL extract the YAML content
2. THE Scanner SHALL recognize YAML headers starting with `---` on the first line
3. THE Scanner SHALL recognize YAML headers ending with `---` or `...`
4. WHEN YAML parsing succeeds, THE Scanner SHALL convert the result to JSON for Header_JSON storage
5. IF YAML parsing fails, THEN THE Scanner SHALL log the error and continue processing
6. THE Scanner SHALL extract `file_path_from_root` from the header
7. THE Scanner SHALL extract `actor_id` from the header when present
8. THE Scanner SHALL extract `agent_slug` from the header when present
9. THE Scanner SHALL extract `agent_type` from the header when present
10. THE Scanner SHALL extract `artifact_kind` from the header when present
11. THE Scanner SHALL extract `channel_id` from the header when present
12. THE Scanner SHALL extract `system_version` from the header when present
13. THE Scanner SHALL extract `x_lupo_forwarded` metadata from the header when present

### Requirement 5: YAML Footer Parser

**User Story:** As a scanner component, I want to parse YAML footers from markdown files, so that semantic relationships can be extracted.

#### Acceptance Criteria

1. WHEN a markdown file contains a YAML footer block, THE Scanner SHALL extract the YAML content
2. THE Scanner SHALL recognize YAML footers starting with `---` after content
3. WHEN YAML parsing succeeds, THE Scanner SHALL convert the result to JSON for Footer_JSON storage
4. THE Scanner SHALL extract `inbound_edges` array from the footer when present
5. THE Scanner SHALL extract semantic relationship metadata from the footer
6. IF YAML parsing fails, THEN THE Scanner SHALL log the error and continue processing

### Requirement 6: File Hash Generation

**User Story:** As a system component, I want to generate file hashes for artifacts, so that content integrity can be verified.

#### Acceptance Criteria

1. WHEN processing a FLIP artifact, THE Scanner SHALL compute a SHA-256 hash of the file content
2. THE Scanner SHALL store the hash in the `file_hash` column as a 64-character hexadecimal string
3. WHEN a file is re-scanned, THE Scanner SHALL compare the new hash with the stored hash
4. IF the hash differs, THEN THE Scanner SHALL update the artifact record with new metadata
5. IF the hash matches, THEN THE Scanner SHALL skip re-processing the file

### Requirement 7: Artifact Storage

**User Story:** As a scanner component, I want to store parsed FLIP metadata in the database, so that it can be queried.

#### Acceptance Criteria

1. WHEN a FLIP artifact is successfully parsed, THE Scanner SHALL insert a record into FLIP_Artifact_Table
2. THE Scanner SHALL set `created_ymdhis` to the current UTC timestamp in YMDHIS format
3. THE Scanner SHALL set `updated_ymdhis` to the current UTC timestamp in YMDHIS format
4. WHEN an artifact already exists, THE Scanner SHALL update the existing record instead of inserting
5. THE Scanner SHALL use prepared statements with named placeholders for all database operations
6. THE Scanner SHALL use the LUPO_TABLE_PREFIX constant for table names
7. THE Scanner SHALL access the database through DatabaseFactory::getConnection()

### Requirement 8: Backfill Process for Channels

**User Story:** As a system administrator, I want to backfill FLIP artifacts from the channels directory, so that existing metadata is preserved.

#### Acceptance Criteria

1. THE Backfill_Process SHALL scan all markdown files in the `channels/` directory recursively
2. WHEN a markdown file contains FLIP metadata, THE Backfill_Process SHALL extract and store it
3. THE Backfill_Process SHALL log the number of files processed
4. THE Backfill_Process SHALL log the number of artifacts successfully stored
5. THE Backfill_Process SHALL log any parsing errors with file paths
6. IF a file cannot be read, THEN THE Backfill_Process SHALL log the error and continue

### Requirement 9: Backfill Process for Documentation

**User Story:** As a system administrator, I want to backfill FLIP artifacts from documentation directories, so that all metadata is captured.

#### Acceptance Criteria

1. THE Backfill_Process SHALL scan all markdown files in `docs/directives/` recursively
2. THE Backfill_Process SHALL scan all markdown files in `docs/status/` recursively
3. THE Backfill_Process SHALL scan all markdown files in `docs/brainstorm/` recursively
4. THE Backfill_Process SHALL scan all markdown files in `docs/doctrine/` recursively
5. THE Backfill_Process SHALL scan all markdown files in `docs/versions/` recursively
6. THE Backfill_Process SHALL process directories in the order listed above
7. THE Backfill_Process SHALL maintain a count of total artifacts processed across all directories

### Requirement 10: Edge Mapping from FLIP Footers

**User Story:** As a semantic engine component, I want to create edge relationships from FLIP footers, so that content relationships are tracked.

#### Acceptance Criteria

1. WHEN a FLIP footer contains `inbound_edges` array, THE Edge_Mapper SHALL process each edge
2. FOR EACH inbound edge, THE Edge_Mapper SHALL create or update a record in `lupo_edges` table
3. THE Edge_Mapper SHALL extract source artifact path from the edge metadata
4. THE Edge_Mapper SHALL extract relationship type from the edge metadata
5. THE Edge_Mapper SHALL set edge timestamps using YMDHIS format
6. THE Edge_Mapper SHALL use soft deletes for edge removal (is_deleted = 1)
7. IF an edge already exists, THEN THE Edge_Mapper SHALL update the `updated_ymdhis` timestamp

### Requirement 11: FLIP v2 Doctrine Document

**User Story:** As a developer, I want comprehensive documentation of FLIP v2, so that I understand the system design.

#### Acceptance Criteria

1. THE Doctrine_Document SHALL be located at `docs/doctrine/FLIP_V2_DOCTRINE.md`
2. THE Doctrine_Document SHALL define the purpose and scope of FLIP v2
3. THE Doctrine_Document SHALL document the complete database schema with column descriptions
4. THE Doctrine_Document SHALL document all YAML header fields and their meanings
5. THE Doctrine_Document SHALL document all YAML footer fields and their meanings
6. THE Doctrine_Document SHALL provide examples of valid FLIP v2 headers
7. THE Doctrine_Document SHALL provide examples of valid FLIP v2 footers
8. THE Doctrine_Document SHALL document the backfill process and usage
9. THE Doctrine_Document SHALL document the scanner architecture
10. THE Doctrine_Document SHALL document integration with the `lupo_edges` table
11. THE Doctrine_Document SHALL document backward compatibility with FLIP v1
12. THE Doctrine_Document SHALL document the artifact hashing mechanism
13. THE Doctrine_Document SHALL document query patterns for common use cases

### Requirement 12: CHANGELOG Update

**User Story:** As a project maintainer, I want FLIP v2 documented in the CHANGELOG, so that changes are tracked.

#### Acceptance Criteria

1. THE CHANGELOG SHALL contain a new entry for version 4.0.37
2. THE CHANGELOG entry SHALL document the addition of `lupo_flip_artifacts` table
3. THE CHANGELOG entry SHALL document the FLIP v2 scanner implementation
4. THE CHANGELOG entry SHALL document the backfill process
5. THE CHANGELOG entry SHALL document the edge mapping integration
6. THE CHANGELOG entry SHALL document the migration script location
7. THE CHANGELOG entry SHALL document the doctrine document location
8. THE CHANGELOG entry SHALL follow the existing CHANGELOG format and style

### Requirement 13: Status Report Generation

**User Story:** As a project manager, I want a status report for FLIP v2 implementation, so that progress is documented.

#### Acceptance Criteria

1. THE Status_Report SHALL be located at `docs/status/kiro_flip_v2_implementation_4_0_37.md`
2. THE Status_Report SHALL contain a FLIP v2 header with appropriate metadata
3. THE Status_Report SHALL document implementation objectives
4. THE Status_Report SHALL document completed tasks
5. THE Status_Report SHALL document database schema details
6. THE Status_Report SHALL document backfill results (files processed, artifacts stored)
7. THE Status_Report SHALL document any issues encountered during implementation
8. THE Status_Report SHALL document next steps or future enhancements
9. THE Status_Report SHALL include timestamps in YMDHIS format

### Requirement 14: TOON File Generation

**User Story:** As a database administrator, I want a TOON file for the FLIP artifacts table, so that schema is documented.

#### Acceptance Criteria

1. WHEN the FLIP_Artifact_Table is created, THE System SHALL generate `docs/toons/lupo_flip_artifacts.toon.json`
2. THE TOON file SHALL be generated using `python scripts/generate_toon_files.py`
3. THE TOON file SHALL contain all column definitions with types
4. THE TOON file SHALL contain all index definitions
5. THE TOON file SHALL NOT be hand-edited after generation

### Requirement 15: PHP 5.3 Compatibility

**User Story:** As a developer, I want all FLIP v2 code to run on PHP 5.3, so that compatibility is maintained.

#### Acceptance Criteria

1. THE Scanner SHALL NOT use named arguments
2. THE Scanner SHALL NOT use union types
3. THE Scanner SHALL NOT use match expressions
4. THE Scanner SHALL NOT use enums
5. THE Scanner SHALL NOT use typed properties
6. THE Scanner SHALL NOT use attributes
7. THE Scanner SHALL NOT use arrow functions
8. THE Scanner SHALL NOT use strict types declarations
9. THE Scanner SHALL NOT use return type declarations
10. THE Scanner SHALL use array() syntax instead of [] for arrays

### Requirement 16: Timestamp Handling

**User Story:** As a developer, I want correct timestamp handling in FLIP v2, so that dates are accurate.

#### Acceptance Criteria

1. THE Scanner SHALL use `gmdate('YmdHis')` for all timestamp generation
2. THE Scanner SHALL store all timestamps as BIGINT in YYYYMMDDHHIISS format
3. THE Scanner SHALL NOT use DATETIME or TIMESTAMP column types
4. THE Scanner SHALL NOT use epoch seconds for timestamps
5. THE Scanner SHALL NOT use ISO8601 format for timestamps
6. WHEN comparing timestamps, THE Scanner SHALL use the timestamp_ymdhis class methods
7. THE Scanner SHALL NOT add seconds directly to timestamp integers

### Requirement 17: Actor ID Validation

**User Story:** As a security component, I want to validate actor IDs in FLIP metadata, so that invalid actors are rejected.

#### Acceptance Criteria

1. WHEN processing FLIP metadata with actor_id, THE Scanner SHALL validate the actor exists in `lupo_actors`
2. IF actor_id is between 0 and 9999, THEN THE Scanner SHALL verify it exists in `lupo_agents`
3. IF actor_id is 10000 or greater, THEN THE Scanner SHALL verify it exists in `lupo_auth_users`
4. IF actor_id validation fails, THEN THE Scanner SHALL log a warning and set actor_id to NULL
5. THE Scanner SHALL continue processing even if actor_id is invalid

### Requirement 18: Path Handling

**User Story:** As a scanner component, I want to handle file paths correctly, so that artifacts are properly identified.

#### Acceptance Criteria

1. THE Scanner SHALL store paths relative to LUPOPEDIA_PATH root
2. THE Scanner SHALL normalize path separators to forward slashes
3. THE Scanner SHALL remove leading slashes from stored paths
4. THE Scanner SHALL validate that file_path_from_root does not exceed 500 characters
5. IF path exceeds 500 characters, THEN THE Scanner SHALL log an error and skip the file

### Requirement 19: Error Handling and Logging

**User Story:** As a system administrator, I want comprehensive error logging for FLIP v2, so that issues can be diagnosed.

#### Acceptance Criteria

1. WHEN a YAML parsing error occurs, THE Scanner SHALL log the file path and error message
2. WHEN a database error occurs, THE Scanner SHALL log the query and error message
3. WHEN a file cannot be read, THE Scanner SHALL log the file path and error reason
4. WHEN an actor_id is invalid, THE Scanner SHALL log the actor_id and file path
5. THE Scanner SHALL use the existing Lupopedia logging system
6. THE Scanner SHALL NOT halt execution on individual file errors
7. THE Scanner SHALL continue processing remaining files after errors

### Requirement 20: Backward Compatibility

**User Story:** As a developer, I want FLIP v2 to coexist with FLIP v1, so that existing files continue to work.

#### Acceptance Criteria

1. THE Scanner SHALL process files with FLIP v1 headers without errors
2. THE Scanner SHALL process files with FLIP v2 headers without errors
3. THE Scanner SHALL process files with no FLIP headers without errors
4. WHEN a file has no FLIP metadata, THE Scanner SHALL skip it without logging errors
5. THE Scanner SHALL NOT modify existing file content during scanning
6. THE Scanner SHALL NOT require migration of FLIP v1 files to FLIP v2 format
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/.kiro/specs/flip-v2-implementation/tasks

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: [".kiro/specs/flip-v2-implementation/tasks.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:58Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".kiro/specs/flip-v2-implementation/tasks.md"
  file_hash: "2ad97b47bcd0c01eccc24a6ba080674c204a70d6676c37b49214daa282594f4a"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["kiro", "specs", "flip-v2-implementation", "tasksmd"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - [".kiro/specs/flip-v2-implementation/tasks.md", "http://www.lupopedia.com/.kiro/specs/flip-v2-implementation/tasks"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: ".kiro\specs\flip-v2-implementation\tasks.md"
  file_hash: "9f7d56f09289474b63b469013b0ceca7dc4c50f7d11b1c0393a17c8c02839c07"
  file_path_from_root: ".kiro\specs\flip-v2-implementation\tasks.md"
  file_hash: "f58c0e0c1f9aa81f8ff0bd98a15c3373647c77abd9c0e4a20d72c8e58808c791"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Implementation Plan: FLIP v2 Database Persistence"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["kiro", "specs", "flip-v2-implementation", "tasksmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Implementation Plan: FLIP v2 Database Persistence

## Overview

This implementation plan breaks down the FLIP v2 feature into discrete coding tasks. FLIP v2 adds database persistence for FLIP header/footer metadata, enabling efficient querying and semantic relationship tracking. The implementation follows Lupopedia's architectural doctrines: PHP 5.3 compatibility, no external dependencies, BIGINT YMDHIS timestamps, and PDO_DB-only database access.

## Tasks

- [ ] 1. Create database schema and migration script
  - Create `lupo-database/migrations/upgrade_flip_v2.sql` with lupo_flip_artifacts table definition
  - Define all 19 columns (flip_artifact_id, file_path_from_root, artifact_kind, channel_id, actor_id, agent_slug, agent_type, system_version, last_modified_ymd, x_forward_from_actor_id, x_forward_to_actor_id, x_lupo_forwarded, header_json, footer_json, file_hash, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
  - Create 7 indexes for efficient querying (path, actor_date, channel_date, forward, kind_date, deleted, version)
  - Ensure MySQL 8.0+, MariaDB 10.5+, and PostgreSQL compatibility
  - Make migration idempotent with IF NOT EXISTS checks
  - Add column comments documenting purpose
  - _Requirements: 1.1-1.21, 2.1-2.7, 3.1-3.7_

- [ ] 2. Implement YAML parser for FLIP metadata
  - [ ] 2.1 Create FLIPYAMLParser class in `lupo-includes/classes/FLIPYAMLParser.php`
    - Implement parse() method for simple YAML structures
    - Implement parseLine() method for key-value pairs
    - Implement parseArray() method for array notation
    - Support quoted strings, integers, simple arrays, and one-level nested objects
    - Use native yaml_parse() if available, fallback to custom parser
    - Handle malformed YAML gracefully (return null on errors)
    - _Requirements: 4.1-4.5, 5.1-5.3, 15.1-15.10_

  - [ ]* 2.2 Write property test for YAML parsing
    - **Property 1: YAML Block Extraction**
    - **Property 2: YAML to JSON Conversion**
    - **Validates: Requirements 4.1-4.4, 5.1-5.3**

- [ ] 3. Implement FLIP scanner for metadata extraction
  - [ ] 3.1 Create FLIPScanner class in `lupo-includes/classes/FLIPScanner.php`
    - Implement scanFile() method to extract metadata from single file
    - Implement scanDirectory() method for recursive scanning
    - Implement extractHeader() method to extract YAML headers (between --- markers at start)
    - Implement extractFooter() method to extract YAML footers (between --- markers at end)
    - Implement computeHash() method for SHA-256 file hashing
    - Implement path normalization (relative to LUPOPEDIA_PATH, forward slashes, no leading slash)
    - Validate file_path_from_root does not exceed 500 characters
    - Log errors for malformed YAML and continue processing
    - _Requirements: 4.1-4.13, 5.1-5.6, 6.1-6.2, 18.1-18.5, 19.1-19.7_

  - [ ]* 3.2 Write unit tests for FLIPScanner
    - Test YAML header extraction with various formats
    - Test YAML footer extraction
    - Test hash computation consistency
    - Test path normalization edge cases (Windows paths, mixed separators, leading slashes)
    - Test error handling for malformed YAML
    - Test path length validation (>500 characters)

  - [ ]* 3.3 Write property tests for scanner
    - **Property 3: Header Field Extraction**
    - **Property 4: Footer Edge Extraction**
    - **Property 5: Parse Error Recovery**
    - **Property 6: File Hash Computation**
    - **Property 15: Path Normalization**
    - **Property 16: Path Length Validation**
    - **Property 18: Non-Destructive Scanning**
    - **Validates: Requirements 4.6-4.13, 5.4-5.6, 6.1-6.2, 18.1-18.5, 20.5**

- [ ] 4. Implement artifact repository for database persistence
  - [ ] 4.1 Create FLIPArtifactRepository class in `app/Services/FLIPArtifactRepository.php`
    - Implement constructor accepting PDO_DB connection
    - Implement upsert() method for insert or update based on file_path_from_root
    - Implement findByPath() method to retrieve artifact by file path
    - Implement findByActor() method to retrieve artifacts by actor_id
    - Implement findByChannel() method to retrieve artifacts by channel_id
    - Implement needsUpdate() method to compare file hashes
    - Implement softDelete() method to mark artifacts as deleted
    - Use DatabaseFactory::getConnection() for database access
    - Use LUPO_TABLE_PREFIX constant for table names
    - Use prepared statements with named placeholders
    - Use timestamp_ymdhis::now() for all timestamps
    - Implement validateActorId() private method to check lupo_actors/lupo_agents/lupo_auth_users
    - Set invalid actor_id to NULL and log warning
    - _Requirements: 7.1-7.7, 16.1-16.7, 17.1-17.5_

  - [ ]* 4.2 Write unit tests for FLIPArtifactRepository
    - Test insert new artifact with all fields
    - Test update existing artifact (upsert behavior)
    - Test findByPath returns correct record
    - Test findByActor with pagination
    - Test findByChannel with pagination
    - Test needsUpdate with matching and differing hashes
    - Test softDelete sets is_deleted=1 and deleted_ymdhis
    - Test actor_id validation (valid AI agent, valid human, invalid ID)
    - Test LUPO_TABLE_PREFIX usage

  - [ ]* 4.3 Write property tests for repository
    - **Property 7: Hash-Based Update Detection**
    - **Property 8: Artifact Insertion**
    - **Property 9: Artifact Upsert Behavior**
    - **Property 13: YMDHIS Timestamp Format**
    - **Property 14: Actor ID Validation**
    - **Property 20: Soft Delete Preservation**
    - **Validates: Requirements 6.3-6.5, 7.1-7.4, 16.1-16.7, 17.1-17.5**

- [ ] 5. Checkpoint - Verify core scanning and storage
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 6. Implement edge mapper for semantic relationships
  - [ ] 6.1 Create FLIPEdgeMapper class in `app/Services/FLIPEdgeMapper.php`
    - Implement constructor accepting PDO_DB connection
    - Implement processFooterEdges() method to process inbound_edges array from footer
    - Implement upsertEdge() method to create or update edge in lupo_edges table
    - Implement findEdgesBySource() method to retrieve edges by source_id
    - Implement findEdgesByTarget() method to retrieve edges by target_id
    - Resolve source artifact paths to flip_artifact_id using FLIPArtifactRepository
    - Set source_type and target_type to "flip_artifact"
    - Extract relationship_type and weight from footer edge metadata
    - Use timestamp_ymdhis::now() for edge timestamps
    - Log warnings for unresolvable source paths and continue processing
    - _Requirements: 10.1-10.7_

  - [ ]* 6.2 Write unit tests for FLIPEdgeMapper
    - Test edge creation from footer with valid source path
    - Test edge upsert behavior (update existing edge)
    - Test source path resolution to flip_artifact_id
    - Test invalid source path handling (log warning, skip edge)
    - Test relationship_type and weight extraction
    - Test findEdgesBySource returns correct edges
    - Test findEdgesByTarget returns correct edges

  - [ ]* 6.3 Write property tests for edge mapper
    - **Property 12: Edge Creation from Footer**
    - **Property 19: Edge Upsert Behavior**
    - **Validates: Requirements 10.1-10.7**

- [ ] 7. Implement backfill service for existing files
  - [ ] 7.1 Create FLIPBackfillService class in `app/Services/FLIPBackfillService.php`
    - Implement constructor accepting PDO_DB, FLIPScanner, FLIPArtifactRepository, FLIPEdgeMapper
    - Implement backfillAll() method to scan all configured directories
    - Implement backfillDirectory() method to scan single directory recursively
    - Implement getProgress() method to return statistics
    - Scan directories in order: lupo-channels/, lupo-docs/directives/, lupo-docs/status/, lupo-docs/brainstorm/, lupo-docs/doctrine/, lupo-docs/versions/
    - Track statistics: files_processed, artifacts_stored, edges_created, errors
    - Log all errors with file paths
    - Continue processing on individual file failures
    - _Requirements: 8.1-8.6, 9.1-9.7_

  - [ ]* 7.2 Write unit tests for FLIPBackfillService
    - Test directory scanning with nested subdirectories
    - Test file filtering by extension (.md only)
    - Test statistics collection (files_processed, artifacts_stored, errors)
    - Test error recovery (continue after file read error)
    - Test backfillAll processes all configured directories

  - [ ]* 7.3 Write integration test for backfill
    - **Property 10: Recursive Directory Scanning**
    - **Property 11: Metadata Extraction and Storage**
    - **Validates: Requirements 8.1-8.6, 9.1-9.7**

- [ ] 8. Create backfill execution script
  - Create `lupo-scripts/flip_v2_backfill.php` CLI script
  - Instantiate FLIPBackfillService with all dependencies
  - Call backfillAll() and display progress
  - Output statistics: files processed, artifacts stored, edges created, errors
  - Exit with status code 0 on success, 1 on errors
  - _Requirements: 8.1-8.6, 9.1-9.7_

- [ ] 9. Checkpoint - Verify backfill process
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 10. Generate TOON file for schema documentation
  - Run `python lupo-scripts/generate_toon_files.py` to generate `lupo-docs/toons/lupo_flip_artifacts.toon.json`
  - Verify TOON file contains all column definitions with types
  - Verify TOON file contains all index definitions
  - _Requirements: 14.1-14.5_

- [ ] 11. Create FLIP v2 doctrine document
  - Create `lupo-docs/doctrine/FLIP_V2_DOCTRINE.md` with FLIP v2 header
  - Document purpose and scope of FLIP v2
  - Document complete database schema with column descriptions
  - Document all YAML header fields and their meanings
  - Document all YAML footer fields and their meanings
  - Provide examples of valid FLIP v2 headers
  - Provide examples of valid FLIP v2 footers
  - Document backfill process and usage
  - Document scanner architecture and component interaction
  - Document integration with lupo_edges table
  - Document backward compatibility with FLIP v1
  - Document artifact hashing mechanism
  - Document query patterns for common use cases
  - _Requirements: 11.1-11.13_

- [ ] 12. Update CHANGELOG for version 4.0.37
  - Add new entry for version 4.0.37 in CHANGELOG.md
  - Document addition of lupo_flip_artifacts table
  - Document FLIP v2 scanner implementation (FLIPScanner, FLIPArtifactRepository, FLIPEdgeMapper, FLIPBackfillService)
  - Document backfill process and script location
  - Document edge mapping integration with lupo_edges
  - Document migration script location (lupo-database/migrations/upgrade_flip_v2.sql)
  - Document doctrine document location (lupo-docs/doctrine/FLIP_V2_DOCTRINE.md)
  - Follow existing CHANGELOG format and style
  - _Requirements: 12.1-12.8_

- [ ] 13. Create status report for FLIP v2 implementation
  - Create `lupo-docs/status/kiro_flip_v2_implementation_4_0_37.md` with FLIP v2 header
  - Document implementation objectives
  - Document completed tasks (schema, scanner, repository, edge mapper, backfill)
  - Document database schema details
  - Document backfill results (files processed, artifacts stored, edges created)
  - Document any issues encountered during implementation
  - Document next steps or future enhancements
  - Include timestamps in YMDHIS format
  - _Requirements: 13.1-13.9_

- [ ] 14. Final checkpoint - Verify complete implementation
  - Run all unit tests: `sh lupo-scripts/run_unit_tests.sh .`
  - Run all property tests
  - Run integration tests
  - Run backfill script on actual codebase: `php lupo-scripts/flip_v2_backfill.php`
  - Verify artifact count matches expected file count
  - Verify edge relationships created correctly
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties
- Unit tests validate specific examples and edge cases
- All code must be PHP 5.3 compatible (no modern PHP features)
- All database access must use DatabaseFactory::getConnection()
- All timestamps must use BIGINT YMDHIS format via timestamp_ymdhis class
- All paths must be normalized relative to LUPOPEDIA_PATH

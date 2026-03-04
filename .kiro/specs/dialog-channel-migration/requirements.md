# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/.kiro/specs/dialog-channel-migration/requirements

---
flame.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

flare.conditional:
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
      repo_paths: [".kiro/specs/dialog-channel-migration/requirements.md"]
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

flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".kiro/specs/dialog-channel-migration/requirements.md"
  file_hash: "6c562f7a0bf6333ed500874f597f4b0451083e4b73849f581127352c6473e954"
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
  tags: ["kiro", "specs", "dialog-channel-migration", "requirementsmd"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - [".kiro/specs/dialog-channel-migration/requirements.md", "http://www.lupopedia.com/.kiro/specs/dialog-channel-migration/requirements"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: ".kiro\specs\dialog-channel-migration\requirements.md"
  file_hash: "4ca34280ec95e3ff1578eb4760638cf3064ebf2c5ebcfb540fba91abc406741f"
  file_path_from_root: ".kiro\specs\dialog-channel-migration\requirements.md"
  file_hash: "ab52d27f569a2906d0ee1d51460f6420120acbaf62b0f310c44f4ae62d33f343"
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
  tags: ["kiro", "specs", "dialog-channel-migration", "requirementsmd"]
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

The Dialog Channel Migration feature migrates all existing `.md` dialog files into a structured, MySQL-backed `dialog_channels` system. This replaces the file-based dialog logs with a normalized, queryable, scalable database layer while preserving all existing content and maintaining backwards compatibility.

## Glossary

- **Dialog_System**: The database-backed system for managing dialog channels and messages
- **Dialog_Parser**: The component that extracts WOLFIE headers and dialog blocks from `.md` files
- **Channel_Builder**: The component that creates channel entries in MySQL
- **Message_Builder**: The component that creates message entries linked to channels
- **Migration_Orchestrator**: The CLI tool that runs the entire migration process
- **Validation_Tool**: The component that confirms migration accuracy and completeness
- **WOLFIE_Header**: The YAML frontmatter containing dialog metadata
- **Dialog_Block**: The structured message content within dialog files

## Requirements

### Requirement 1: Database Schema Creation

**User Story:** As a system architect, I want structured database tables for dialog storage, so that dialog data is normalized, queryable, and scalable.

#### Acceptance Criteria

1. THE Dialog_System SHALL create a dialog_channels table with all metadata fields from WOLFIE headers
2. THE Dialog_System SHALL create a dialog_messages table linked to dialog_channels
3. THE Dialog_System SHALL support speaker, target, timestamp, mood_RGB, categories, tags, and channels fields
4. THE Dialog_System SHALL support message text up to 272 characters per message
5. THE Dialog_System SHALL support multi-message threads with proper ordering
6. THE Dialog_System SHALL include all required indexes for performance
7. THE Dialog_System SHALL include proper foreign key relationships between tables

### Requirement 2: Content Preservation

**User Story:** As a data steward, I want all existing dialog content preserved during migration, so that no historical information is lost.

#### Acceptance Criteria

1. THE Migration_Orchestrator SHALL preserve all existing `.md` dialog content with no data loss
2. THE Migration_Orchestrator SHALL preserve all messages with exact text content
3. THE Migration_Orchestrator SHALL preserve all metadata from WOLFIE headers
4. THE Migration_Orchestrator SHALL preserve all timestamps in original format
5. THE Migration_Orchestrator SHALL preserve all categories and tags
6. THE Migration_Orchestrator SHALL prevent duplicate message entries
7. THE Migration_Orchestrator SHALL maintain message ordering within threads

### Requirement 3: Migration Process

**User Story:** As a system administrator, I want an automated migration script, so that I can reliably transfer all dialog data to the database.

#### Acceptance Criteria

1. THE Dialog_Parser SHALL read all `.md` files in the dialogs directory
2. THE Dialog_Parser SHALL extract WOLFIE headers from each file
3. THE Dialog_Parser SHALL extract dialog blocks from each file
4. THE Channel_Builder SHALL insert channel metadata into MySQL
5. THE Message_Builder SHALL insert individual messages into MySQL
6. THE Migration_Orchestrator SHALL log all errors during processing
7. THE Migration_Orchestrator SHALL generate a comprehensive migration report

### Requirement 4: Data Validation

**User Story:** As a quality assurance engineer, I want validation tools to confirm migration accuracy, so that I can verify data integrity.

#### Acceptance Criteria

1. THE Validation_Tool SHALL confirm message counts match between files and database
2. THE Validation_Tool SHALL confirm metadata matches between files and database
3. THE Validation_Tool SHALL confirm no missing fields in migrated data
4. THE Validation_Tool SHALL confirm no duplicate entries exist
5. THE Validation_Tool SHALL confirm no orphaned messages exist
6. THE Validation_Tool SHALL generate a validation report with pass/fail status
7. THE Validation_Tool SHALL identify specific discrepancies when validation fails

### Requirement 5: Documentation Updates

**User Story:** As a developer, I want updated documentation reflecting the new dialog system, so that I can understand and use the new architecture.

#### Acceptance Criteria

1. THE Dialog_System SHALL update DIALOGS_AND_CHANNELS.md with new database schema
2. THE Dialog_System SHALL update routing_changelog.md with migration notes
3. THE Dialog_System SHALL update CHANGELOG.md with version changes
4. THE Dialog_System SHALL add new schema documentation for both tables
5. THE Dialog_System SHALL update WOLFIE Header specification with new fields
6. THE Dialog_System SHALL provide examples of database-backed dialog usage
7. THE Dialog_System SHALL document migration procedures and validation steps

### Requirement 6: WOLFIE Header Specification Updates

**User Story:** As a system integrator, I want updated WOLFIE header specifications, so that I can implement database-backed dialogs correctly.

#### Acceptance Criteria

1. THE Dialog_System SHALL add new required fields for database-backed dialogs
2. THE Dialog_System SHALL provide examples of new header format
3. THE Dialog_System SHALL document migration notes for header changes
4. THE Dialog_System SHALL specify parsing rules for new fields
5. THE Dialog_System SHALL define error handling for malformed headers
6. THE Dialog_System SHALL maintain backwards compatibility with existing headers
7. THE Dialog_System SHALL document validation rules for new header fields

### Requirement 7: Backwards Compatibility

**User Story:** As a system maintainer, I want backwards compatibility preserved, so that existing workflows continue to function.

#### Acceptance Criteria

1. THE Dialog_System SHALL preserve `.md` files as archival sources
2. THE Dialog_System SHALL make the database the primary source of truth
3. THE Dialog_System SHALL eliminate runtime dependency on `.md` files
4. THE Dialog_System SHALL ensure `.md` files remain readable for reference
5. THE Dialog_System SHALL introduce no breaking changes to existing agent interfaces
6. THE Dialog_System SHALL ensure all agents can read the new database format
7. THE Dialog_System SHALL maintain API compatibility for dialog access

### Requirement 8: Error Handling and Logging

**User Story:** As a system operator, I want comprehensive error handling and logging, so that I can troubleshoot migration issues effectively.

#### Acceptance Criteria

1. THE Migration_Orchestrator SHALL log all file processing attempts
2. THE Migration_Orchestrator SHALL log all database insertion attempts
3. THE Migration_Orchestrator SHALL log detailed error messages for failures
4. THE Migration_Orchestrator SHALL continue processing after non-fatal errors
5. THE Migration_Orchestrator SHALL provide progress indicators during migration
6. THE Migration_Orchestrator SHALL generate summary statistics upon completion
7. THE Migration_Orchestrator SHALL create rollback procedures for failed migrations
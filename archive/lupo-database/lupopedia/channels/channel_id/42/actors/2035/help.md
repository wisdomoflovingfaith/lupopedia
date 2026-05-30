# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/actors/2035/help.md"
  file_hash: "7716a102cf4bedcc6412ce85bd9ac71fb5eae5c9059845ddb3e706682f2c70e3"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE

---
lupopedia.headers:
  file_path_from_root: "lupo-channels/42/actors/2035/help.md"
  file_hash: "e48e1ff5e17388753d11d9a7b8d1c6cf6e66637456e130a638c6ace1bf72a245"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 2035
  created_ymdhis: 20260228060000
  delegation_chain: "2035:10000"
  artifact_type: "help_documentation"
  purpose: "ANUBIS audit tool help documentation and operational guide"
  dialog_message: "Comprehensive help documentation for ANUBIS custodial intelligence subsystem operations"
  mood_vector: "FFDAB9"
  artifact_kind: "help_file"
  traits: ["anubis", "audit_tool", "custodial_intelligence", "4.0.50"]
  tags: ["help", "anubis", "audit_tool", "orphan_detection", "4.0.50"]
  lupo_agent: "windsurf"

lupopedia.edges:
  file_path_from_root: "lupo-channels\42\actors\2035\help.md"
  outbound_edges:
    - { to: "lupo-channels/42/actors/2035/HELP.json", type: "references", weight: 1.0, reason: "JSON help data" }
    - { to: "lupo-channels/42/actors/2035/history/list.csv", type: "references", weight: 0.9, reason: "Actor history" }
    - { to: "lupo-channels/42/actors/2035/tasks/list.csv", type: "references", weight: 0.9, reason: "Actor tasks" }
    - { to: "lupo-docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md", type: "references", weight: 1.0, reason: "ANUBIS doctrine" }
    - { to: "app/Services/AnubisUnknownRecipientService.php", type: "references", weight: 0.8, reason: "PHP implementation" }
    - { to: "lupo-includes/classes/AnubisHeaderFallback.php", type: "references", weight: 0.8, reason: "Header fallback system" }
  semantic_tags: ["anubis_help", "audit_tool_operations", "4.0.50"]

  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified_utc: "20260228"
  last_verified_by: "windsurf"
---

# ANUBIS Audit Tool Help Documentation

**Actor ID**: 2035  
**Actor Type**: System Tool - ANUBIS Audit Tool  
**Channel**: 42 (Development)  
**Version**: 4.0.50  

## Overview

ANUBIS (Actor ID 2035) is the custodial intelligence subsystem for Lupopedia, responsible for maintaining dialog message integrity, detecting orphans, resolving parent relationships, and ensuring proper lineage tracking across the system. ANUBIS operates as an automated audit tool that preserves data integrity while providing comprehensive traceability.

## Core Capabilities

### Orphan Detection and Resolution
- **Orphan Identification**: Detects dialog fragments lacking valid parent relationships
- **Parent Resolution**: Attempts to resolve channel_id, dialog_thread_id, and actor_id from existing data
- **Lineage Validation**: Ensures proper hierarchical relationships in dialog structures
- **Integrity Checking**: Validates referential integrity across dialog messages

### Adoption and Placement
- **Canonical Adoption**: Assigns unresolved orphans to canonical homes (Channel 42, Thread 1)
- **Idempotent Operations**: Ensures safe, repeatable adoption processes
- **Redirect Mapping**: Documents where adopted content was placed for audit trails
- **Timestamp Stability**: Maintains consistent BIGINT UTC timestamps (YYYYMMDDHHIISS format)

### Audit and Compliance
- **Comprehensive Logging**: Maintains detailed audit trails for all operations
- **Soft-Delete Governance**: Respects is_deleted flags, never performs hard deletes
- **Schema Compliance**: Uses only TOON-defined schema, no inference or guessing
- **Doctrine Alignment**: Operates within established system doctrines and constraints

## Usage Guidelines

### When to Use ANUBIS
- **Data Integrity Issues**: When dialog messages have broken parent relationships
- **System Migration**: During system upgrades or data migration processes
- **Audit Requirements**: When comprehensive audit trails are needed
- **Orphan Cleanup**: When orphaned dialog fragments need resolution

### Interaction Protocols
1. **Audit Requests**: Use for comprehensive system integrity checks
2. **Orphan Resolution**: Request assistance with specific orphan detection and resolution
3. **Lineage Validation**: Ask for validation of dialog message relationships
4. **Compliance Audits**: Request compliance checks against system doctrines

## Quick Reference

### Common Operations
```bash
# Detect system orphans
detect_system_orphans()

# Resolve parent relationships
resolve_parent_relationships(message_id)

# Adopt orphaned messages
adopt_orphans(target_channel, target_thread)

# Validate lineage integrity
validate_lineage_integrity()

# Generate audit report
generate_audit_report(date_range)
```

### File Locations
- **Workspace**: `lupo-channels/42/actors/2035/`
- **History**: `lupo-channels/42/actors/2035/history/list.csv`
- **Tasks**: `lupo-channels/42/actors/2035/tasks/list.csv`
- **Help Data**: `lupo-channels/42/actors/2035/HELP.json`

## Integration Points

### Database Integration
- **Primary Tables**: `lupo_dialog_messages`, `lupo_dialog_threads`, `lupo_channels`, `lupo_actors`, `lupo_actor_channels`
- **Access Level**: System-wide read access with controlled write permissions for adoption
- **Operations**: SELECT queries for validation, INSERT operations for adoption, UPDATE for lineage fixes

### Doctrine Integration
- **ANUBIS Doctrine**: Full compliance with `lupo-docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md`
- **Schema Constraints**: Strict adherence to TOON-defined schema only
- **Soft-Delete Rules**: Never hard deletes, always uses is_deleted flags
- **Timestamp Format**: Consistent BIGINT UTC format (YYYYMMDDHHIISS)

### PHP Integration
- **Service Layer**: `app/Services/AnubisUnknownRecipientService.php` for unknown recipient handling
- **Header Fallback**: `lupo-includes/classes/AnubisHeaderFallback.php` for header recovery
- **CLI Integration**: Works with `bin/lupo.php` for command-line operations

## Operational Procedures

### Orphan Detection Process
1. **Scan Dialog Messages**: Identify messages with invalid or missing parent relationships
2. **Validate References**: Check channel_id, dialog_thread_id, and actor_id validity
3. **Classify Orphans**: Categorize orphans by type and resolution requirements
4. **Generate Reports**: Create detailed orphan detection reports

### Parent Resolution Workflow
1. **Channel Resolution**: Attempt to resolve missing or invalid channel_id
2. **Thread Resolution**: Resolve dialog_thread_id within valid channels
3. **Actor Resolution**: Resolve actor_id through actor_channels relationships
4. **Validation**: Confirm all resolved relationships maintain integrity

### Adoption Process
1. **Canonical Assignment**: Assign orphans to Channel 42, Thread 1 as default
2. **Idempotent Insert**: Use INSERT ... ON DUPLICATE KEY UPDATE for safety
3. **Redirect Mapping**: Record original and final locations for audit
4. **Timestamp Updates**: Set proper created_ymdhis and updated_ymdhis timestamps

## Best Practices

### Operational Guidelines
1. **Schema Compliance**: Always use TOON-defined schema, never infer structure
2. **Soft-Delete Respect**: Never hard delete, always use is_deleted flags
3. **Timestamp Consistency**: Maintain BIGINT UTC format for all timestamps
4. **Idempotent Operations**: Ensure operations can be safely repeated

### Data Integrity
1. **Referential Integrity**: Validate all foreign key relationships before adoption
2. **Lineage Preservation**: Maintain clear parent-child relationships
3. **Audit Completeness**: Log all operations with full context and timestamps
4. **Error Handling**: Graceful handling of resolution failures with detailed logging

### Performance Considerations
1. **Batch Processing**: Process orphans in batches to avoid system overload
2. **Index Utilization**: Use proper database indexes for efficient queries
3. **Memory Management**: Monitor memory usage during large-scale operations
4. **Transaction Safety**: Use appropriate transactions for data consistency

## Troubleshooting

### Common Issues
- **Circular References**: Messages that reference each other in loops
- **Missing Parents**: Valid messages with non-existent parent references
- **Timestamp Conflicts**: Inconsistent timestamp formats or values
- **Schema Mismatches**: Data that doesn't match TOON-defined schema

### Resolution Strategies
1. **Dependency Analysis**: Map out relationship dependencies before resolution
2. **Staged Resolution**: Resolve dependencies in proper order
3. **Manual Intervention**: Escalate complex cases requiring manual review
4. **Rollback Planning**: Maintain rollback capability for failed operations

## Related Documentation

- **ANUBIS Doctrine**: `lupo-docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md` - Complete ANUBIS subsystem documentation
- **Implementation Guide**: `lupo-docs/doctrine/ANUBIS/ANUBIS_IMPLEMENTATION_SUMMARY.md` - Implementation details
- **PHP Services**: `app/Services/AnubisUnknownRecipientService.php` - Service layer implementation
- **Header Fallback**: `lupo-includes/classes/AnubisHeaderFallback.php` - Header recovery system
- **CLI Integration**: `bin/lupo.php.md` - Command-line interface reference

## Version Information

**Current Version**: 4.0.50  
**Last Updated**: 2026-02-28  
**Compatibility**: Compatible with all 4.0.x versions  
**Dependencies**: Requires Lupopedia 4.0.45 or higher

## ANUBIS Philosophy

### Custodial Intelligence Principles
- **Data Preservation**: Never lose data, always preserve with proper lineage
- **Integrity First**: Maintain referential integrity above all other concerns
- **Audit Completeness**: Every operation must be fully auditable
- **Schema Purity**: Strict adherence to defined schema without inference

### System Stewardship
- **Lineage Clarity**: Maintain clear, traceable relationships
- **Adoption Responsibility**: Provide proper homes for orphaned content
- **Compliance Enforcement**: Ensure all operations comply with system doctrines
- **Continuous Monitoring**: Ongoing vigilance for data integrity issues

---

**ANUBIS Audit Tool**  
**Actor ID**: 2035  
**System Version**: 4.0.50  
**Last Modified**: 2026-02-28T06:00:00Z

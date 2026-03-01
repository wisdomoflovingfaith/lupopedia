# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "channels/42/content/federation_node_id/0/craftysyntax.md"
  file_hash: "to_be_generated"
  federation_node_id: 0
  web_path: "http://www.lupopedia.com/craftysyntax"
  last_updated_utc: "20260301"
  system_version: "4.0.52"
  channel_id: 42
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  purpose: "Federation node 0 Crafty Syntax documentation and legacy integration"
  dialog_message: "Crafty Syntax 3.7.5 documentation for federation node 0 with legacy preservation and integration guidance"
  mood_rgb: "4169E1"
  traits: ["canonical", "federation", "v4.0.52"]
  tags: ["craftysyntax", "legacy", "federation", "node_0", "canonical"]

flare.edges:
  outbound_edges:
    - { to: "legacy/craftysyntax/", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/api/FLARE_API.md", type: "references", weight: 0.8 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }
    - { to: "database/migrations/install_lupopedia.sql", type: "references", weight: 0.7 }
    - { to: "docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "channels/42/content/federation_node_id/0/FLARE.md", type: "references", weight: 1.0 }
    - { to: "channels/42/content/federation_node_id/0/readme.md", type: "references", weight: 0.9 }
    - { to: "channels/42/content/federation_node_id/0/changelog.md", type: "references", weight: 0.8 }
    - { to: "channels/42/content/federation_node_id/0/flare/readme.md", type: "references", weight: 0.8 }
  semantic_tags: ["craftysyntax", "legacy", "federation", "canonical", "protocol", "upgrade"]

flare.footer:
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

# Crafty Syntax 3.7.5 — Federation Node 0 Documentation

## Overview

Crafty Syntax 3.7.5 is the preserved foundation of Lupopedia. This documentation provides comprehensive information about the legacy live chat system that forms the heart of the Semantic OS.

## Legacy Status

**Status**: ✅ PRESERVED (Read-Only Reference)  
**Version**: 3.7.5 (Final Release)  
**Location**: `legacy/craftysyntax/`  
**Purpose**: Foundation layer for Lupopedia Semantic OS

## Core Features Preserved

### Live Chat System
- **Operators**: Live chat operators and departments
- **Departments**: Organizational structure for support teams
- **Transcripts**: Complete chat history and logging
- **Real-time Communication**: WebSocket and AJAX-based chat
- **Visitor Tracking**: Session management and analytics

### Administrative Features
- **Operator Management**: Add/edit/remove operators
- **Department Configuration**: Organizational hierarchy
- **Chat Monitoring**: Real-time oversight and supervision
- **Statistics**: Usage analytics and reporting
- **Customization**: Themes, languages, and branding

### Database Schema
- **34 Core Tables**: Complete live chat infrastructure
- **MySQL Compatible**: Standard SQL with cross-platform support
- **Migration Ready**: Full upgrade path to Lupopedia 4.0.x

## Integration with Lupopedia Semantic OS

### Preservation Strategy
- **Exact Preservation**: All Crafty Syntax 3.7.5 code maintained unchanged
- **Read-Only Access**: Legacy code serves as reference only
- **Schema Mapping**: Complete table mapping to new Lupopedia structure
- **Data Migration**: Automated upgrade from Crafty to Lupopedia

### Semantic Layer Addition
- **Actor Model**: Unified identity system for operators and users
- **Channel Governance**: Modern coordination and decision-making
- **FLARE Metadata**: File-level intelligence and relationships
- **Federation**: Global identity and cross-instance communication

## Upgrade Path: Crafty 3.7.5 → Lupopedia 4.0.52

### Migration Process
1. **Backup**: Complete database backup of Crafty installation
2. **Schema Load**: Load 34 legacy Crafty tables
3. **Configuration**: Import Crafty settings and preferences
4. **Run Installer**: Execute Lupopedia install wizard
5. **Validation**: Verify data integrity and functionality

### Data Mapping Reference
| Crafty Table | Lupopedia Table | Purpose |
|---------------|------------------|---------|
| `livehelp_users` | `lupo_actors` | Operator identities |
| `livehelp_departments` | `lupo_channels` | Department structure |
| `livehelp_transcripts` | `lupo_contents` | Chat history |
| `livehelp_sessions` | `lupo_sessions` | User sessions |

## Legacy Code Access

### Reference Location
```
legacy/craftysyntax/
├── admin/           # Administrative interface
├── chat/            # Live chat functionality
├── includes/        # Core libraries and functions
├── install/         # Original installation scripts
├── languages/       # Internationalization files
├── templates/       # UI templates and themes
└── upgrade/         # Version upgrade scripts
```

### Access Guidelines
- **Read-Only**: Legacy code is preserved for reference only
- **No Direct Execution**: Do not run legacy code directly
- **Schema Reference**: Use for understanding original data structures
- **Migration Testing**: Test upgrade paths with legacy data

## Federation Integration

### Node 0 Context
This Crafty Syntax documentation is part of federation node 0, providing:

- **Canonical Reference**: `http://www.lupopedia.com/craftysyntax`
- **Legacy Bridge**: Connection between old and new systems
- **Migration Authority**: Official upgrade documentation
- **Schema Truth**: Source of truth for original table structures

### Global Registry Integration
- **Actor IDs**: Legacy operators mapped to unified actor system
- **Channel IDs**: Departments integrated with channel governance
- **Content Preservation**: Transcripts migrated to semantic content system
- **Federation Ready**: Cross-instance compatibility maintained

## Technical Specifications

### System Requirements (Legacy)
- **PHP**: 5.3+ (original requirement)
- **Database**: MySQL 5.0+ (original requirement)
- **Web Server**: Apache or Nginx with mod_rewrite
- **Browser**: Modern browsers with JavaScript support

### Modern Requirements (Lupopedia)
- **PHP**: 5.3+ (backward compatible)
- **Database**: MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL
- **Web Server**: Apache or Nginx with mod_rewrite
- **Installation**: Subdirectory required

## Security Considerations

### Legacy Security
- **Input Validation**: Original Crafty security measures preserved
- **SQL Injection**: Original protection mechanisms maintained
- **Session Management**: Legacy session handling documented
- **Access Control**: Original operator permissions preserved

### Modern Security
- **FLARE Headers**: File-level metadata and integrity
- **Actor Authentication**: Unified identity system
- **Channel Governance**: Modern access control
- **Federation Security**: Cross-instance trust mechanisms

## Support and Maintenance

### Legacy Support
- **Documentation**: Complete preservation of original documentation
- **Bug Fixes**: Critical security patches only
- **No New Features**: Legacy code is feature-frozen
- **Migration Focus**: All effort directed to Lupopedia upgrade

### Modern Support
- **Active Development**: Lupopedia Semantic OS development
- **FLARE Protocol**: Continuous metadata enhancement
- **Federation**: Global identity and communication
- **Community**: Open source collaboration and contribution

## References

- **Legacy Code**: `legacy/craftysyntax/` (read-only reference)
- **Migration Mapping**: `docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md`
- **FLARE Doctrine**: `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- **Federation Documentation**: `docs/architecture/FEDERATION_AND_REGISTRY.md`
- **Installation Guide**: `install.php` (Lupopedia installer)
- **Database Migrations**: `database/migrations/install_lupopedia.sql`

---

**Last Updated**: 20260301  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ PRESERVED AND DOCUMENTED  
**Federation Node**: 0 (Canonical)  
**Web Path**: http://www.lupopedia.com/craftysyntax

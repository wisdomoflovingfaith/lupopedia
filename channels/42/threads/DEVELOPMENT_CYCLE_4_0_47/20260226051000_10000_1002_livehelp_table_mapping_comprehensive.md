# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_47\20260226051000_10000_1002_livehelp_table_mapping_comprehensive.md"
  file_hash: "9f47f3dd3e4d18f5223686e9daebb3ad18320702b5ba7275636175264f37e10d"
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_47\20260226051000_10000_1002_livehelp_table_mapping_comprehensive.md"
  file_hash: "724e9ed56715802af7f4082d6b0bbcefeada7db4ac3954a003d99910d06efa72"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260226051000_10000_1002_livehelp_table_mapping_comprehensive.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "threads", "development_cycle_4_0_47", "20260226051000_10000_1002_livehelp_table_mapping_comprehensivemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/20260226051000_10000_1002_livehelp_table_mapping_comprehensive.md",
  system_version: "4.0.47",
  channel_id: 42,
  mood_rgb: "8B4513",
  purpose: "Comprehensive analysis of Crafty Syntax table migrations to Lupopedia for livehelp system planning",
  last_modified_utc: "20260226051000",
  delegation_chain: "1001:10000",
  actor_id: 1002,
  lupo_agent: "windsurf",
  artifact_type: "dialog_message",
  artifact_kind: "migration_analysis",
  traits: ["livehelp", "table_mapping", "crafty_syntax", "lupopedia", "migration", "comprehensive"],
  hashtags: ["#livehelp", "#migration", "#tables", "#crafty_syntax", "#lupopedia", "#mapping"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260226051000" },
  graph_stats: { inbound_count: 4, outbound_count: 5, centrality_score: 0.90 }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/doctrine/migrations/livehelp_sessions_migration.md", type: "references", weight: 1.0, hashtag: "#source" },
    { from: "docs/doctrine/migrations/livehelp_users_migration.md", type: "references", weight: 1.0, hashtag: "#source" },
    { from: "docs/doctrine/migrations/livehelp_departments_migration.md", type: "references", weight: 1.0, hashtag: "#source" },
    { from: "docs/doctrine/migrations/livehelp_operator_departments_migration.md", type: "references", weight: 1.0, hashtag: "#source" },
    { from: "database/migrations/import_from_old_crafty_syntax.sql", type: "references", weight: 0.9, hashtag: "#implementation" }
  ],
  outbound_edges: [
    { to: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/20260226050900_10000_1002_livehelp_session_actor_mapping_analysis.md", type: "complements", weight: 0.8, hashtag: "#related_analysis" },
    { to: "livehelp.php", type: "informs", weight: 0.9, hashtag: "#implementation" }
  ],
  referenced_by_actors: [1002, 1003, 1005],
  references: {
    by_files: ["docs/doctrine/migrations/livehelp_*.md", "database/migrations/import_from_old_crafty_syntax.sql"],
    by_actors: [1002, 1003, 1005]
  },
  semantic_tags: ["livehelp_table_mapping", "crafty_syntax_migration", "lupopedia_architecture"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.47",
  last_verified_utc: "20260226051000",
  last_verified_by: "windsurf"
}
---

# Comprehensive Livehelp Table Mapping Analysis
**Thread**: DEVELOPMENT_CYCLE_4_0_47  
**From**: Windsurf (1002)  
**To**: Antigravity (1003), Lilith (1005)  
**UTC**: 2026-02-26 05:10:00  
**Subject**: Complete mapping of Crafty Syntax livehelp tables to Lupopedia architecture for system planning

## Executive Summary

This analysis comprehensively maps all 28 Crafty Syntax livehelp tables to their Lupopedia equivalents, revealing the architectural transformation required for modern livehelp functionality. The mapping shows which tables are imported, dropped, or transformed, and how this affects livehelp system design.

## Migration Overview by Category

### Category 1: Core Identity & Authentication (IMPORTED)

#### livehelp_users → lupo_auth_users + lupo_actors
**Reference**: `docs/doctrine/migrations/livehelp_users_migration.md`

**Legacy Structure**:
- Mixed operators and visitors in single table
- Authentication data mixed with session state
- Department assignments and routing data included

**Modern Mapping**:
- **lupo_auth_users**: Authentication credentials (username, password, email)
- **lupo_actors**: Unified identity layer (actor_id = auth_user_id + 10000)
- **lupo_actor_properties**: Behavioral and presence metadata
- **Role System**: 3-level permissions (channel, department, system)

**Livehelp Impact**:
- Visitor creation now uses actor model
- Operator authentication through auth system
- Department assignments via `lupo_actor_departments`
- Session state separated from identity

#### livehelp_departments → lupo_departments + lupo_department_metadata
**Reference**: `docs/doctrine/migrations/livehelp_departments_migration.md`

**Legacy Structure**:
- Kitchen-sink table with routing, UI, branding, behavior settings
- Mixed semantic department with configuration data

**Modern Mapping**:
- **lupo_departments**: Core department identity (name, description, type)
- **lupo_department_metadata**: All legacy UI/behavior settings in JSON

**Livehelp Impact**:
- Department routing uses clean semantic model
- Legacy settings preserved in metadata
- Theme and branding data accessible but separated

#### livehelp_operator_departments → lupo_actor_departments
**Reference**: `docs/doctrine/migrations/livehelp_operator_departments_migration.md`

**Legacy Structure**:
- Clean mapping of operators to departments
- Optional title/role field

**Modern Mapping**:
- **lupo_actor_departments**: Direct successor with lifecycle fields
- Preserves all legacy mappings cleanly
- Adds soft-delete and timestamps

**Livehelp Impact**:
- Operator availability checking uses modern joins
- Department membership preserved exactly
- Role titles preserved in `title` field

### Category 2: Session & State Management (DROPPED/TRANSFORMED)

#### livehelp_sessions → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_sessions_migration.md`

**Legacy Structure**:
- Ephemeral session state
- Browser cookie tracking
- Runtime routing information

**Modern Replacement**:
- **lupo_sessions**: Deterministic, actor-aware, device-aware sessions
- No data migration (ephemeral data discarded)

**Livehelp Impact**:
- Session handling completely redesigned
- Actor-centric session management
- Device fingerprinting support

#### livehelp_channels → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_channels_migration.md`

**Legacy Structure**:
- Chat channel management
- Operator assignment to active chats

**Modern Replacement**:
- **lupo_channels**: Semantic channel system
- **lupo_dialog_threads**: Conversation threading
- **lupo_dialog_messages**: Message storage

**Livehelp Impact**:
- Chat system uses modern dialog architecture
- Threading support for conversations
- Message persistence and search

#### livehelp_operator_channels → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_operator_channels_migration.md`

**Legacy Structure**:
- Real-time operator channel assignments
- Active chat routing state

**Modern Replacement**:
- **lupo_dialog_threads**: Active conversation tracking
- **lupo_actor_departments**: Static department membership
- Runtime state handled differently

**Livehelp Impact**:
- Operator assignment through department membership
- Active chat tracking via dialog threads
- Real-time state handled by application layer

### Category 3: Content & Communication (IMPORTED/TRANSFORMED)

#### livehelp_messages → lupo_dialog_messages
**Reference**: `docs/doctrine/migrations/livehelp_messages_migration.md`

**Legacy Structure**:
- Chat message storage
- Basic message metadata

**Modern Mapping**:
- **lupo_dialog_messages**: Enhanced message storage
- Threading support
- Rich metadata and search capabilities

**Livehelp Impact**:
- Message history preserved
- Enhanced search and filtering
- Modern message threading

#### livehelp_transcripts → lupo_dialog_transcripts
**Reference**: `docs/doctrine/migrations/livehelp_transcripts_migration.md`

**Legacy Structure**:
- Chat transcript storage
- Export functionality

**Modern Mapping**:
- **lupo_dialog_transcripts**: Enhanced transcript system
- Multiple export formats
- Search and analytics support

**Livehelp Impact**:
- Transcript functionality preserved and enhanced
- Better search and analytics
- Multiple export options

### Category 4: Configuration & Features (MIXED)

#### livehelp_config → lupo_crafty_syntax_config
**Reference**: `docs/doctrine/migrations/livehelp_config_migration.md`

**Legacy Structure**:
- System configuration settings
- Feature toggles

**Modern Mapping**:
- **lupo_crafty_syntax_config**: Preserved for compatibility
- Settings also integrated into modern config system

**Livehelp Impact**:
- Legacy configuration preserved
- Modern config system available
- Backward compatibility maintained

#### livehelp_qa → lupo_crafty_syntax_qa
**Reference**: `docs/doctrine/migrations/livehelp_qa_migration.md`

**Legacy Structure**:
- Q&A system for canned responses

**Modern Mapping**:
- **lupo_crafty_syntax_qa**: Preserved Q&A functionality
- Integration with modern content system

**Livehelp Impact**:
- Q&A system preserved
- Modern content management integration
- Enhanced search and categorization

#### livehelp_questions → lupo_crafty_syntax_questions
**Reference**: `docs/doctrine/migrations/livehelp_questions_migration.md`

**Legacy Structure**:
- Pre-chat questions for visitors

**Modern Mapping**:
- **lupo_crafty_syntax_questions**: Preserved question system
- Integration with modern form system

**Livehelp Impact**:
- Pre-chat questions preserved
- Modern form handling
- Enhanced question management

### Category 5: Marketing & Automation (PRESERVED)

#### livehelp_autoinvite → lupo_crafty_syntax_auto_invite
**Reference**: `docs/doctrine/migrations/livehelp_autoinvite_migration.md`

**Legacy Structure**:
- Automatic chat invitation rules

**Modern Mapping**:
- **lupo_crafty_syntax_auto_invite**: Preserved automation
- Integration with modern rule engine

**Livehelp Impact**:
- Auto-invite functionality preserved
- Modern rule processing
- Enhanced targeting options

#### livehelp_layerinvites → lupo_crafty_syntax_layer_invites
**Reference**: `docs/doctrine/migrations/livehelp_layerinvites_migration.md`

**Legacy Structure**:
- Layer-based invitation system

**Modern Mapping**:
- **lupo_crafty_syntax_layer_invites**: Preserved layer system
- Modern UI integration

**Livehelp Impact**:
- Layer invitations preserved
- Modern UI framework integration
- Enhanced layer management

### Category 6: Lead Management (PRESERVED)

#### livehelp_leads → lupo_crafty_syntax_leads
**Reference**: `docs/doctrine/migrations/livehelp_leads_migration.md`

**Legacy Structure**:
- Lead capture and management

**Modern Mapping**:
- **lupo_crafty_syntax_leads**: Preserved lead system
- Integration with modern CRM

**Livehelp Impact**:
- Lead management preserved
- Modern CRM integration
- Enhanced lead tracking

#### livehelp_leavemessage → lupo_crafty_syntax_leave_message
**Reference**: `docs/doctrine/migrations/livehelp_leavemessage_migration.md`

**Legacy Structure**:
- Offline message capture

**Modern Mapping**:
- **lupo_crafty_syntax_leave_message**: Preserved offline messaging
- Modern notification system

**Livehelp Impact**:
- Offline messaging preserved
- Modern notification system
- Enhanced message handling

### Category 7: Analytics & Tracking (PRESERVED)

#### livehelp_visit_track → lupo_crafty_syntax_visit_track
**Reference**: `docs/doctrine/migrations/livehelp_visit_track_migration.md`

**Legacy Structure**:
- Visitor tracking and analytics

**Modern Mapping**:
- **lupo_crafty_syntax_visit_track**: Preserved tracking
- Integration with modern analytics

**Livehelp Impact**:
- Visitor tracking preserved
- Modern analytics integration
- Enhanced reporting

#### livehelp_paths_firsts → lupo_crafty_syntax_paths_firsts
**Reference**: `docs/doctrine/migrations/livehelp_paths_firsts_migration.md`

**Legacy Structure**:
- Path tracking analytics

**Modern Mapping**:
- **lupo_crafty_syntax_paths_firsts**: Preserved path analytics
- Modern path analysis

**Livehelp Impact**:
- Path analytics preserved
- Modern analysis tools
- Enhanced reporting

#### livehelp_referers_daily → lupo_crafty_syntax_referers_daily
**Reference**: `docs/doctrine/migrations/livehelp_referers_daily_migration.md`

**Legacy Structure**:
- Referrer tracking

**Modern Mapping**:
- **lupo_crafty_syntax_referers_daily**: Preserved referrer tracking
- Modern referrer analysis

**Livehelp Impact**:
- Referrer tracking preserved
- Modern analysis tools
- Enhanced reporting

### Category 8: System & Infrastructure (DROPPED)

#### livehelp_identity → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_identity_migration.md`

**Legacy Structure**:
- Identity management (redundant with users)

**Modern Replacement**:
- Identity handled by `lupo_actors` and `lupo_auth_users`

**Livehelp Impact**:
- No impact - redundant functionality
- Modern identity system superior

#### livehelp_emailque → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_emailque_migration.md`

**Legacy Structure**:
- Email queue management

**Modern Replacement**:
- Modern email queue system
- Integration with notification framework

**Livehelp Impact**:
- Email functionality preserved through modern system
- Enhanced queue management

#### livehelp_smilies → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_smilies_migration.md`

**Legacy Structure**:
- Emoticon/smiley definitions

**Modern Replacement**:
- Modern emoji system
- Unicode emoji support

**Livehelp Impact**:
- Emoticon functionality preserved
- Modern emoji support
- Enhanced visual experience

#### livehelp_keywords → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_keywords_migration.md`

**Legacy Structure**:
- Keyword-based triggering

**Modern Replacement**:
- Modern keyword system
- Integration with search engine

**Livehelp Impact**:
- Keyword functionality preserved
- Modern search integration
- Enhanced triggering

#### livehelp_modules → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_modules_migration.md`

**Legacy Structure**:
- Module system for extensibility

**Modern Replacement**:
- Modern plugin architecture
- Component-based system

**Livehelp Impact**:
- Extensibility preserved
- Modern architecture
- Enhanced module system

#### livehelp_modules_dep → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_modules_dep_migration.md`

**Legacy Structure**:
- Module dependencies

**Modern Replacement**:
- Modern dependency management
- Component dependencies

**Livehelp Impact**:
- Dependency management preserved
- Modern architecture
- Enhanced dependency resolution

#### livehelp_websites → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_websites_migration.md`

**Legacy Structure**:
- Website management

**Modern Replacement**:
- Federation node system
- Multi-site architecture

**Livehelp Impact**:
- Website functionality preserved
- Modern federation
- Enhanced multi-site support

#### livehelp_quick → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_quick_migration.md`

**Legacy Structure**:
- Quick response system

**Modern Replacement**:
- Modern quick response
- Integration with Q&A system

**Livehelp Impact**:
- Quick response preserved
- Modern integration
- Enhanced response management

#### livehelp_emails → DROPPED
**Reference**: `docs/doctrine/migrations/livehelp_emails_migration.md`

**Legacy Structure**:
- Email templates and management

**Modern Replacement**:
- Modern email system
- Template management

**Livehelp Impact**:
- Email functionality preserved
- Modern template system
- Enhanced email management

## Livehelp System Architecture Implications

### Core Livehelp Tables (Modern Equivalents)
1. **Identity**: `lupo_actors` + `lupo_auth_users`
2. **Sessions**: `lupo_sessions`
3. **Departments**: `lupo_departments`
4. **Assignments**: `lupo_actor_departments`
5. **Messages**: `lupo_dialog_messages`
6. **Threads**: `lupo_dialog_threads`
7. **Transcripts**: `lupo_dialog_transcripts`

### Preserved Crafty Syntax Features (in lupo_crafty_syntax_* tables)
1. **Configuration**: `lupo_crafty_syntax_config`
2. **Q&A**: `lupo_crafty_syntax_qa`
3. **Questions**: `lupo_crafty_syntax_questions`
4. **Auto-invite**: `lupo_crafty_syntax_auto_invite`
5. **Layer invites**: `lupo_crafty_syntax_layer_invites`
6. **Leads**: `lupo_crafty_syntax_leads`
7. **Leave message**: `lupo_crafty_syntax_leave_message`
8. **Analytics**: `lupo_crafty_syntax_visit_track`, `lupo_crafty_syntax_paths_firsts`, `lupo_crafty_syntax_referers_daily`

### Modern Enhancements Available
1. **Threading**: `lupo_dialog_threads`
2. **Enhanced Messaging**: `lupo_dialog_messages`
3. **Actor System**: `lupo_actors`
4. **Session Management**: `lupo_sessions`
5. **Department System**: `lupo_departments`
6. **Role System**: 3-level permission hierarchy

## Implementation Strategy for Livehelp

### Phase 1: Core Infrastructure (Current)
- Use modern actor and session systems
- Implement department-based routing
- Preserve legacy configuration through crafty tables

### Phase 2: Feature Integration (Planned)
- Integrate preserved Crafty Syntax features
- Enhance with modern messaging and threading
- Implement modern analytics and reporting

### Phase 3: Modern Interface (Future)
- Build modern livehelp interface
- Implement real-time features
- Add advanced analytics and AI features

## Migration Success Criteria
1. **Identity Preservation**: All users and operators migrated
2. **Feature Parity**: All Crafty Syntax features available
3. **Data Integrity**: No data loss during migration
4. **Performance**: Modern system performs better
5. **Extensibility**: New features can be added easily

## Files Referenced
- All 28 `docs/doctrine/migrations/livehelp_*.md` files
- `database/migrations/import_from_old_crafty_syntax.sql`
- `legacy/craftysyntax/livehelp.php`
- `livehelp.php`

**Status**: Comprehensive mapping complete
**Priority**: High - Foundation for livehelp system planning
**Complexity**: High - Complex architectural transformation
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_47\20260226050900_10000_1002_livehelp_session_actor_mapping_analysis.md"
  file_hash: "4804579ef6c93f15be94ff5e6cfede0ae9376efc8ade62a84958d9d9ca088757"
  file_path_from_root: "channels\42\threads\DEVELOPMENT_CYCLE_4_0_47\20260226050900_10000_1002_livehelp_session_actor_mapping_analysis.md"
  file_hash: "f6d9307823ea3e8e0a4b24e5d8393c3828a03051325bd585ee66fb619df7a0b9"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260226050900_10000_1002_livehelp_session_actor_mapping_analysis.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "threads", "development_cycle_4_0_47", "20260226050900_10000_1002_livehelp_session_actor_mapping_analysismd"]
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
  file_path_from_root: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/20260226050900_10000_1002_livehelp_session_actor_mapping_analysis.md",
  system_version: "4.0.47",
  channel_id: 42,
  mood_rgb: "FF6B35",
  purpose: "Analysis of livehelp session-to-actor mapping differences between legacy Crafty Syntax and new Lupopedia architecture",
  last_modified_utc: "20260226050900",
  delegation_chain: "1001:10000",
  actor_id: 1002,
  lupo_agent: "windsurf",
  artifact_type: "dialog_message",
  artifact_kind: "technical_analysis",
  traits: ["livehelp", "session_mapping", "actor_model", "legacy_analysis", "architecture"],
  hashtags: ["#livehelp", "#sessions", "#actors", "#crafty_syntax", "#lupopedia", "#architecture"],
  engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260226050900" },
  graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.85 }
}

flip.footer: {
  inbound_edges: [
    { from: "legacy/craftysyntax/livehelp.php", type: "analyzes", weight: 1.0, hashtag: "#legacy" },
    { from: "livehelp.php", type: "analyzes", weight: 1.0, hashtag: "#new_implementation" },
    { from: "docs/doctrine/migrations/livehelp_sessions_migration.md", type: "references", weight: 0.9, hashtag: "#migration" },
    { from: "docs/doctrine/migrations/livehelp_users_migration.md", type: "references", weight: 0.9, hashtag: "#migration" }
  ],
  outbound_edges: [
    { to: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_47/20260226051000_10000_1002_livehelp_table_mapping_comprehensive.md", type: "complements", weight: 0.8, hashtag: "#related_analysis" },
    { to: "livehelp.php", type: "informs", weight: 0.9, hashtag: "#implementation" }
  ],
  referenced_by_actors: [1002, 1003, 1005],
  references: {
    by_files: ["legacy/craftysyntax/livehelp.php", "livehelp.php", "docs/doctrine/migrations/livehelp_sessions_migration.md"],
    by_actors: [1002, 1003, 1005]
  },
  semantic_tags: ["session_actor_mapping", "livehelp_architecture", "legacy_modernization"],
  enrichment: { llm_inferred_edges: [], federated_metrics: {} },
  version: "4.0.47",
  last_verified_utc: "20260226050900",
  last_verified_by: "windsurf"
}
---

# Livehelp Session-to-Actor Mapping Analysis
**Thread**: DEVELOPMENT_CYCLE_4_0_47  
**From**: Windsurf (1002)  
**To**: Antigravity (1003), Lilith (1005)  
**UTC**: 2026-02-26 05:09:00  
**Subject**: Critical architectural differences in session handling between legacy and modern livehelp systems

## Executive Summary

The legacy Crafty Syntax livehelp system and new Lupopedia architecture have fundamentally different approaches to session-to-actor mapping. This analysis reveals why the current livehelp.php implementation conflicts with the slug-based routing system and what architectural decisions are needed for proper integration.

## Legacy Crafty Syntax Session Model

### Core Characteristics
**File**: `legacy/craftysyntax/livehelp.php`

**Session Handling Pattern**:
```php
// Legacy approach: Direct session-to-user mapping
$identity = $mydatabase->query("SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'");
$myid = $people['user_id'];
```

**Key Legacy Behaviors**:
1. **Session-Centric**: All user state stored in `livehelp_users.sessionid` field
2. **Mixed Identity**: Operators and visitors in same table with `isoperator` flag
3. **Ephemeral State**: Session data includes routing, department, UI state
4. **Direct File Access**: `livehelp.php` is a standalone endpoint, not routed
5. **Cookie-Based**: Relies on PHP session cookies for persistence

**Legacy Session Flow**:
1. Visitor arrives → PHP session created
2. `livehelp_users` record created/updated with `sessionid`
3. Department assignment stored in user record
4. Operator assignment via `livehelp_operator_channels`
5. All subsequent requests use session ID to lookup user

### Legacy Session Dependencies
- **livehelp_sessions**: DROPPED - contained ephemeral runtime state
- **livehelp_users**: IMPORTED → SPLIT - mixed identity/session data
- **livehelp_operator_channels**: DROPPED - operator routing state
- **livehelp_departments**: IMPORTED → SPLIT - routing + UI config

## New Lupopedia Session Model

### Core Characteristics
**File**: `livehelp.php` (current implementation)

**Session Handling Pattern**:
```php
// Modern approach: Session table + Actor table separation
$session = $db->query("SELECT * FROM lupo_sessions WHERE session_id = :session_id");
$actor = $db->query("SELECT * FROM lupo_actors WHERE actor_id = :actor_id");
```

**Key Modern Behaviors**:
1. **Actor-Centric**: All identity in `lupo_actors`, sessions are separate concern
2. **Deterministic Sessions**: `lupo_sessions` table with proper lifecycle
3. **Device-Aware**: Sessions track device fingerprint, not just cookies
4. **Federated**: Sessions belong to federation nodes
5. **Slug-Based Routing**: All requests go through routing system

**Modern Session Flow**:
1. Visitor arrives → `lupo_sessions` record created
2. `lupo_actors` record created for visitor identity
3. Session linked to actor via `lupo_sessions.actor_id`
4. Department assignment via `lupo_actors.department_id`
5. All requests routed through slug system

### Modern Session Architecture
- **lupo_sessions**: Durable, deterministic, actor-aware session engine
- **lupo_actors**: Unified identity layer (visitors + operators)
- **lupo_actor_departments**: Clean department membership model
- **lupo_departments**: Semantic department definitions

## Critical Architectural Conflicts

### 1. Routing System Conflict
**Problem**: Legacy `livehelp.php` expects direct file access
**Modern Reality**: Lupopedia uses slug-based routing through `index.php`

**Impact**: 
- Legacy URLs like `/livehelp.php?department=1` don't work
- Slug system expects content to be resolved, not direct file execution
- Livehelp endpoints need proper slug integration

### 2. Session State Management Conflict
**Legacy**: Session state stored in user record
**Modern**: Session state separated from actor identity

**Impact**:
- Legacy department assignment logic doesn't map cleanly
- Operator availability checking requires new query patterns
- Visitor naming process needs actor-centric approach

### 3. Identity Model Conflict
**Legacy**: Mixed operator/visitor table with flags
**Modern**: Separate actor types with clean separation

**Impact**:
- Legacy `isoperator` logic becomes `actor_type = 'operator'`
- Visitor creation needs proper actor type assignment
- Department routing uses different join patterns

## Session-to-Actor Mapping Requirements

### For Legacy Compatibility
1. **Session Persistence**: Maintain visitor identity across requests
2. **Department Assignment**: Preserve department-based routing
3. **Operator Availability**: Check online status per department
4. **Naming Process**: Support visitor name entry workflow

### For Modern Architecture
1. **Slug Integration**: Livehelp endpoints must work through routing
2. **Actor Separation**: Clean visitor vs operator identity handling
3. **Session Durability**: Proper session lifecycle management
4. **Device Awareness**: Multi-device session support

## Recommended Architecture Approach

### 1. Hybrid Session Bridge
Create a session bridge that:
- Maps legacy session IDs to modern `lupo_sessions` records
- Maintains visitor actor identity across requests
- Preserves department assignment logic
- Supports legacy URL patterns while using modern backend

### 2. Slug-Based Livehelp Endpoints
Design livehelp endpoints that:
- Use slug routing (e.g., `/livehelp/:department` or `/chat/:department`)
- Maintain backward compatibility with legacy URLs
- Support both direct access and routed access
- Integrate with modern authentication system

### 3. Actor-Centric Visitor Management
Implement visitor handling that:
- Creates visitor actors on first visit
- Links sessions to actors properly
- Supports visitor naming workflow
- Maintains department assignment through actor properties

## Implementation Strategy

### Phase 1: Session Bridge (Current)
- Fix current `livehelp.php` session handling
- Ensure proper actor creation and linking
- Maintain department assignment logic
- Test legacy compatibility

### Phase 2: Slug Integration (Planned)
- Design slug-based livehelp URLs
- Create routing rules for livehelp endpoints
- Implement URL rewriting for legacy compatibility
- Test with existing Crafty Syntax installations

### Phase 3: Modern Interface (Future)
- Design modern livehelp interface
- Implement real-time features
- Add multi-device support
- Integrate with broader Lupopedia ecosystem

## Technical Debt Identified

1. **Direct File Access**: Current implementation bypasses routing system
2. **Mixed Session Logic**: Legacy session patterns mixed with modern actor model
3. **Incomplete Migration**: Some legacy patterns not fully modernized
4. **URL Structure**: No clear path for slug-based livehelp URLs

## Next Steps for Planning

1. **Antigravity IDE**: Design slug-based URL structure for livehelp
2. **Lilith**: Plan actor-centric visitor management system
3. **Windsurf**: Implement session bridge for legacy compatibility
4. **All**: Test integration with existing Crafty Syntax installations

## Files Referenced
- `legacy/craftysyntax/livehelp.php` - Legacy implementation analysis
- `livehelp.php` - Current modern implementation
- `docs/doctrine/migrations/livehelp_sessions_migration.md` - Session migration doctrine
- `docs/doctrine/migrations/livehelp_users_migration.md` - User/actor migration doctrine

**Status**: Analysis complete, architecture decisions needed
**Priority**: High - Blocks livehelp system integration
**Complexity**: Medium - Requires coordination across multiple agents
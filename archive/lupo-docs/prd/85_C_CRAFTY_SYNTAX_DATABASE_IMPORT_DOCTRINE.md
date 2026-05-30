---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/85_C_CRAFTY_SYNTAX_DATABASE_IMPORT_DOCTRINE.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/85_C_CRAFTY_SYNTAX_DATABASE_IMPORT_DOCTRINE.md"
  status: "active"
  when_updated: "20260423034029"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/prd/canonical/1026/04/85-c-crafty-syntax-database-import-doctrine.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/prd/85-c-crafty-syntax-database-import-doctrine"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "00_A_13_A_16_C_59_A_59_B_73_A_85_A"
  title: "PRD 85_C: Crafty Syntax Database Import Doctrine"
  summary: "Canonical doctrine for understanding and importing the Crafty Syntax 3.7.5 database into Lupopedia. Documents all 33 legacy tables, their purposes, field meanings, implicit relationships, and mapping to Lupopedia structures for CHIRON, ANUBIS, VISH, and AGAPE integration."
---

# PRD 85_C: Crafty Syntax Database Import Doctrine

## 1. Purpose

This PRD serves as the canonical doctrine for understanding and importing the Crafty Syntax 3.7.5 database into Lupopedia. It provides comprehensive documentation of the legacy schema, table purposes, field meanings, implicit relationships, and systematic mapping to Lupopedia-compatible structures.

This doctrine enables:
- Complete understanding of legacy Crafty Syntax data structures
- Safe and systematic import of all legacy data
- Preservation of meaning and relationships during migration
- Conversion into Lupopedia constitutional compliance
- Feeding of CHIRON, ANUBIS, VISH, and AGAPE with historical truth

## 2. Source File

**Authoritative Source:** `old_crafty_syntax_3_7_5_start.sql`

**Location:** `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`

**Authority:** This file is the definitive legacy schema and seed data. All import logic must align exactly with this file. No guessing, inference beyond documented evidence, or assumption of missing relationships is permitted.

**Content:** Contains complete table definitions, field specifications, example data, implicit relationships, and historical usage patterns from Crafty Syntax Live Help 3.7.5.

## 3. Table Inventory (Critical)

### 3.1 Complete Table List (33 tables)

| Table Name | Purpose | Category |
|------------|---------|----------|
| `livehelp_autoinvite` | Automated chat invitation rules | System |
| `livehelp_channels` | Live chat session channels | Session |
| `livehelp_config` | Global system configuration | Configuration |
| `livehelp_departments` | Department/organizational structure | Identity |
| `livehelp_emailque` | Email queue for offline messages | Communication |
| `livehelp_emails` | Email templates and settings | Communication |
| `livehelp_identity_daily` | Daily identity analytics | Engagement |
| `livehelp_identity_monthly` | Monthly identity analytics | Engagement |
| `livehelp_keywords_daily` | Daily keyword search analytics | Engagement |
| `livehelp_keywords_monthly` | Monthly keyword search analytics | Engagement |
| `livehelp_layerinvites` | Layer-based invitation system | System |
| `livehelp_leads` | Customer lead capture | Content |
| `livehelp_leavemessage` | Offline message storage | Content |
| `livehelp_messages` | Chat message content | Content |
| `livehelp_modules` | System module definitions | System |
| `livehelp_modules_dep` | Department-module relationships | System |
| `livehelp_operator_channels` | Operator channel assignments | Identity |
| `livehelp_operator_departments` | Operator department assignments | Identity |
| `livehelp_operator_history` | Operator activity history | Engagement |
| `livehelp_paths_firsts` | First-time path analytics | Engagement |
| `livehelp_paths_monthly` | Monthly path flow analytics | Engagement |
| `livehelp_qa` | Q&A content | Content |
| `livehelp_questions` | User-submitted questions | Content |
| `livehelp_quick` | Quick response templates | Content |
| `livehelp_referers_daily` | Daily referrer analytics | Engagement |
| `livehelp_referers_monthly` | Monthly referrer analytics | Engagement |
| `livehelp_sessions` | PHP session storage | Session |
| `livehelp_smilies` | Emoji/emoticon definitions | System |
| `livehelp_transcripts` | Chat transcript storage | Content |
| `livehelp_users` | User accounts and profiles | Identity |
| `livehelp_visits_daily` | Daily visit analytics | Engagement |
| `livehelp_visits_monthly` | Monthly visit analytics | Engagement |
| `livehelp_visit_track` | Individual visit tracking | Engagement |
| `livehelp_websites` | Website configuration | Configuration |

## 4. Field Analysis

### 4.1 Identity Tables

#### `livehelp_users`
**Purpose:** Central user account management
**Key Fields:**
- `user_id` (int) - Primary user identifier
- `username` (varchar) - Login username
- `displayname` (varchar) - Display name for chat
- `password` (varchar) - Password hash (nullable for OAuth)
- `email` (varchar) - User email address
- `isoperator` (char) - Whether user is an operator
- `isadmin` (char) - Whether user is administrator
- `department` (int) - Department assignment
- `lastaction` (bigint) - Last activity timestamp
- `isonline` (char) - Online status
- `visits` (int) - Visit count
- `sessionid` (varchar) - Current session identifier
- `ipaddress` (varchar) - User IP address
- `useragent` (varchar) - Browser user agent
- `authenticated` (char) - Authentication status
- `auth_provider` (varchar) - OAuth provider (if applicable)
- `provider_id` (varchar) - OAuth provider user ID

#### `livehelp_departments`
**Purpose:** Organizational department structure
**Key Fields:**
- `recno` (int) - Primary department identifier
- `nameof` (varchar) - Department name
- `messageemail` (varchar) - Contact email
- `leaveamessage` (varchar) - Whether to allow offline messages
- `opening` (mediumtext) - Welcome message
- `offline` (mediumtext) - Offline message
- `timeout` (int) - Chat timeout seconds
- `visible` (int) - Visibility status
- `ordering` (int) - Display order
- `theme` (varchar) - Visual theme
- `website` (int) - Website assignment

### 4.2 Engagement Tables

#### `livehelp_paths_monthly` (CRITICAL)
**Purpose:** Monthly aggregated user path analytics
**Key Fields:**
- `id` (int) - Primary identifier
- `visit_recno` (int) - Entry path/point identifier
- `exit_recno` (int) - Exit path/point identifier
- `dateof` (int) - Date in YYYYMM format
- `visits` (int) - Number of visits for this path

**Sample Data Interpretation:**
```sql
(1, 0, 1, 202511, 168) -- 168 visits from entry 0 to exit 1 in Nov 2025
(2, 1, 3, 202511, 42)  -- 42 visits from entry 1 to exit 3 in Nov 2025
```

#### `livehelp_visits_monthly`
**Purpose:** Monthly visit aggregation analytics
**Key Fields:**
- `id` (int) - Primary identifier
- `pageid` (int) - Page identifier
- `dateof` (int) - Date in YYYYMM format
- `hits` (int) - Number of visits
- `visitors` (int) - Unique visitor count

### 4.3 Content Tables

#### `livehelp_messages`
**Purpose:** Chat message storage
**Key Fields:**
- `id_num` (int) - Primary message identifier
- `message` (mediumtext) - Message content
- `channel` (int) - Channel identifier
- `timeof` (bigint) - Message timestamp
- `saidfrom` (int) - Sender user ID
- `saidto` (int) - Recipient user ID
- `typeof` (varchar) - Message type

#### `livehelp_transcripts`
**Purpose:** Complete chat session transcripts
**Key Fields:**
- `id` (int) - Primary transcript identifier
- `sessionid` (varchar) - Session identifier
- `operatorid` (int) - Operator user ID
- `userid` (int) - Customer user ID
- `dateof` (int) - Session date
- `transcript` (mediumtext) - Full transcript content

### 4.4 Session Tables

#### `livehelp_channels`
**Purpose:** Live chat session management
**Key Fields:**
- `id` (int) - Primary channel identifier
- `user_id` (int) - User identifier
- `statusof` (char) - Channel status
- `startdate` (bigint) - Session start timestamp
- `sessionid` (varchar) - Session identifier
- `website` (int) - Website identifier

### 4.5 Configuration Tables

#### `livehelp_config`
**Purpose:** Global system configuration
**Key Fields:**
- `version` (varchar) - System version
- `site_title` (varchar) - Site title
- `webpath` (varchar) - Web path
- `speaklanguage` (varchar) - Default language
- `maxrecords` (int) - Maximum records
- `tracking` (char) - Whether tracking enabled
- `colorscheme` (varchar) - Color scheme

## 5. Implicit Relationships

### 5.1 User-Centric Relationships
- `livehelp_users.user_id` → `livehelp_channels.user_id` (user owns channels)
- `livehelp_users.user_id` → `livehelp_messages.saidfrom/saidto` (message participants)
- `livehelp_users.user_id` → `livehelp_transcripts.userid/operatorid` (transcript participants)
- `livehelp_users.department` → `livehelp_departments.recno` (department assignment)

### 5.2 Session-Based Relationships
- `livehelp_channels.sessionid` → `livehelp_transcripts.sessionid` (session transcripts)
- `livehelp_channels.id` → `livehelp_messages.channel` (channel messages)
- `livehelp_sessions.session_id` → user session data

### 5.3 Department-Based Relationships
- `livehelp_departments.recno` → `livehelp_operator_departments.departmentid` (operator assignments)
- `livehelp_departments.recno` → `livehelp_modules_dep.departmentid` (module assignments)
- `livehelp_users.department` → `livehelp_departments.recno` (user department)

### 5.4 Analytics Relationships
- `livehelp_paths_monthly.visit_recno/exit_recno` → page/path identifiers
- `livehelp_visits_monthly.pageid` → page identifiers
- Temporal relationships via `dateof` fields across all analytics tables

## 6. Data Domains

### 6.1 User / Identity Domain
**Tables:** `livehelp_users`, `livehelp_departments`, `livehelp_operator_departments`, `livehelp_operator_channels`
**Purpose:** User management, authentication, authorization, organizational structure
**Key Concepts:** User accounts, operators, departments, permissions, assignments

### 6.2 Sessions / Visits Domain
**Tables:** `livehelp_channels`, `livehelp_sessions`, `livehelp_visit_track`
**Purpose:** Active session management, visit tracking, real-time interaction
**Key Concepts:** Chat sessions, user sessions, visit lifecycle, session state

### 6.3 Chat / Communication Domain
**Tables:** `livehelp_messages`, `livehelp_transcripts`, `livehelp_leavemessage`, `livehelp_emailque`
**Purpose:** Real-time communication, message storage, offline communication
**Key Concepts:** Chat messages, transcripts, offline messages, email queue

### 6.4 Paths / Navigation Domain
**Tables:** `livehelp_paths_monthly`, `livehelp_paths_firsts`
**Purpose:** User flow analysis, navigation patterns, path optimization
**Key Concepts:** Entry/exit points, user journeys, popular paths, dead ends

### 6.5 Engagement / Analytics Domain
**Tables:** `livehelp_visits_daily/monthly`, `livehelp_referers_daily/monthly`, `livehelp_keywords_daily/monthly`, `livehelp_identity_daily/monthly`, `livehelp_operator_history`
**Purpose:** Usage analytics, performance metrics, behavioral insights
**Key Concepts:** Visit counts, ref tracking, keyword analysis, identity tracking

### 6.6 System / Config Domain
**Tables:** `livehelp_config`, `livehelp_websites`, `livehelp_modules`, `livehelp_modules_dep`, `livehelp_smilies`, `livehelp_autoinvite`, `livehelp_layerinvites`, `livehelp_qa`, `livehelp_questions`, `livehelp_quick`, `livehelp_leads`
**Purpose:** System configuration, module management, templates, automation
**Key Concepts:** Global settings, module system, templates, automation rules

## 7. Engagement Data (Critical Section)

### 7.1 Core Engagement Table: `livehelp_paths_monthly`

**Schema:**
```sql
CREATE TABLE `livehelp_paths_monthly` (
  `id` int UNSIGNED NOT NULL,
  `visit_recno` int UNSIGNED NOT NULL DEFAULT '0',
  `exit_recno` int UNSIGNED NOT NULL DEFAULT '0',
  `dateof` int NOT NULL DEFAULT '0',
  `visits` int UNSIGNED NOT NULL DEFAULT '0'
);
```

**Field Meanings:**
- `visit_recno`: Entry point identifier (where users begin)
- `exit_recno`: Exit point identifier (where users end)
- `dateof`: Time period (YYYYMM format for monthly aggregation)
- `visits`: Number of users following this specific path

### 7.2 Aggregation Model

**Canonical Query:**
```sql
SELECT enter, exit, dateof, SUM(visits) AS total_visits
FROM livehelp_paths_monthly
GROUP BY enter, exit, dateof
ORDER BY total_visits DESC;
```

**Interpretation:**
- **Enter (visit_recno)**: Starting point of user journey
- **Exit (exit_recno)**: Ending point where users leave or complete tasks
- **Dateof (dateof)**: Temporal context for behavior patterns
- **Total Visits**: Quantified popularity of specific paths

### 7.3 Behavioral Insights

**User Flow Analysis:**
- Popular entry points indicate important starting content
- Common exit points may indicate task completion or abandonment
- Path popularity guides navigation design and content prioritization
- Temporal changes reveal evolving user needs and content relevance

**Navigation Patterns:**
- High-traffic paths should be optimized and made more prominent
- Dead-end paths (high exit, low continuation) indicate usability issues
- Popular exit points may indicate natural completion points
- Cross-path analysis reveals user mental models

**Organizational Intelligence:**
- Content popularity informs CHIRON conversion priority
- Path flows guide VISH collection organization
- Usage patterns validate AGAPE structural decisions
- Historical truth provides empirical basis for navigation design

## 8. Import Mapping to Lupopedia

### 8.1 Identity Mapping
**Legacy Users → Lupopedia Actors:**
- `livehelp_users.user_id` → `lupo_actors.actor_id`
- `livehelp_users.username` → `lupo_actors.actor_name`
- `livehelp_users.email` → `lupo_actors.email`
- `livehelp_users.isoperator` → `lupo_actors.actor_type`
- Department mapping to `lupo_departments`

**Legacy Departments → Lupopedia Departments:**
- `livehelp_departments.recno` → `lupo_departments.department_id`
- `livehelp_departments.nameof` → `lupo_departments.department_name`

### 8.2 Content Mapping
**Legacy Messages → Lupopedia Content:**
- `livehelp_messages` → `lupo_content` (chat content type)
- `livehelp_transcripts` → `lupo_content` (transcript content type)
- `livehelp_leavemessage` → `lupo_content` (message content type)
- Message threading to `lupo_threads`

**Legacy Q&A → Lupopedia Knowledge Base:**
- `livehelp_qa` → `lupo_content` (FAQ content type)
- `livehelp_questions` → `lupo_content` (question content type)

### 8.3 Session Mapping
**Legacy Sessions → Lupopedia Sessions:**
- `livehelp_channels` → `lupo_sessions` (chat sessions)
- `livehelp_sessions` → `lupo_sessions` (user sessions)
- Session state and metadata preservation

### 8.4 Navigation Mapping
**Legacy Paths → Lupopedia Navigation:**
- `livehelp_paths_monthly` → `lupo_paths` + engagement weights
- Path popularity → navigation priority scores
- Entry/exit points → navigation hierarchy

### 8.5 Engagement Mapping
**Legacy Analytics → Lupopedia Intelligence:**
- Visit counts → content importance scores
- Path flows → collection organization hints
- Temporal patterns → content lifecycle management
- User behavior → AGAPE validation inputs

### 8.6 Configuration Mapping
**Legacy Config → Lupopedia Settings:**
- `livehelp_config` → `lupo_settings` (global configuration)
- Module definitions → plugin/system module registry
- Templates → content template system

## 9. Agent Integration

### 9.1 CHIRON Integration
**Conversion Prioritization:**
- Use `livehelp_paths_monthly.visit_recno` to identify high-value entry points
- Prioritize content conversion based on `livehelp_visits_monthly.hits`
- Focus on historically popular content first
- Weight conversion effort by documented usage patterns

**Content Strategy:**
- Identify which legacy content deserves immediate attention
- Detect gaps between available content and user demand
- Guide content creation based on historical need patterns

### 9.2 ANUBIS Integration
**Orphan Detection:**
- Use engagement data to identify unused or abandoned content
- Detect content with zero or declining engagement
- Flag potentially orphaned legacy content for review
- Prioritize processing of historically important content

**Processing Priority:**
- Queue content based on historical importance scores
- Focus repair efforts on high-traffic content
- Use path analysis to identify broken user journeys

### 9.3 VISH Integration
**Collection Organization:**
- Use `livehelp_paths_monthly` flow data to suggest natural collections
- Identify content that should be co-located based on user paths
- Detect collections that don't match observed user workflows
- Suggest tab structures based on common navigation patterns

**Navigation Design:**
- Optimize collection ordering by historical popularity
- Identify dead-end collections that users abandon
- Create shortcuts for frequently traversed paths
- Validate that collection hierarchy matches user mental models

### 9.4 AGAPE Integration
**Structural Validation:**
- Detect when organization contradicts observed usage patterns
- Generate WHY files for structural anti-patterns
- Validate that high-traffic content is easily accessible
- Check that organizational changes don't disrupt established workflows

**Compliance Checking:**
- Ensure that important content isn't buried in obscure locations
- Validate that navigation paths match user expectations
- Check that organizational choices align with strong behavioral signals

## 10. Temporal Normalization

### 10.1 Timestamp Conversion Requirements

**Legacy Timestamp Formats:**
- `bigint` fields (Unix timestamps)
- `int` fields (YYYYMM format for dates)
- Various timestamp representations across tables

**Lupopedia Canonical Format:**
- **BIGINT UTC timestamps** in YYYYMMDDHHIISS format
- No vendor-specific datetime types
- Explicit conversion in import layer
- No silent assumptions or automatic conversions

### 10.2 Conversion Logic

**Unix Timestamp → BIGINT UTC:**
```php
$canonical_timestamp = date('YmdHis', $legacy_unix_timestamp);
```

**YYYYMM → BIGINT UTC:**
```php
$canonical_timestamp = $legacy_yyyymm . '01000000'; // First of month, midnight
```

**Validation Requirements:**
- All timestamps must be valid UTC
- Handle timezone offsets from legacy data
- Preserve temporal ordering and relationships
- Document any estimation or imputation

## 11. Import Strategy

### 11.1 High-Level Process

**Phase 1: Discovery**
- Read all legacy tables and analyze structure
- Identify data volumes and relationships
- Map legacy identifiers to Lupopedia equivalents
- Document transformation requirements

**Phase 2: Normalization**
- Convert all timestamps to canonical format
- Normalize text encodings to UTF-8
- Standardize identifier formats
- Validate data integrity and constraints

**Phase 3: Transformation**
- Map legacy entities to Lupopedia structures
- Generate missing relationships and metadata
- Create engagement scores and weights
- Preserve provenance and lineage information

**Phase 4: Integration**
- Import transformed data into Lupopedia tables
- Create memory graph edges for relationships
- Generate guidance signals for agents
- Validate import completeness and accuracy

### 11.2 Deterministic Principles

**No Hidden Transformations:**
- All conversion rules must be explicit and documented
- No random or probabilistic decision making
- Same input always produces same output
- Replicable results across environments

**Explicit Lineage:**
- Track source of all imported data
- Maintain mapping between legacy and new identifiers
- Document all transformation decisions
- Preserve audit trail for debugging and validation

## 12. Non-Goals

This PRD does NOT:

- **Redesign Crafty Syntax**: Legacy schema is imported as-is, not redesigned
- **Mutate Legacy Data Meaning**: Preserve original intent and relationships
- **Assume Missing Relationships**: Only document relationships evident in schema/data
- **Centralize Across Installs**: Each installation imports its own historical data
- **Replace Live Analytics**: Focus on historical import, not real-time tracking
- **Automatically Reorganize**: Provide guidance and validation, not autonomous restructuring
- **Create New Content**: Import existing content, do not generate new content
- **Override Human Judgment**: Use engagement data as one signal among many

## 13. Constraints

### 13.1 Constitutional Compliance

**Database Doctrine:**
- No foreign keys or constraints
- No triggers, stored procedures, or functions
- Application-layer relationship enforcement
- Deterministic outputs for same inputs
- Explicit lineage tracking for all changes

**Header Requirements:**
- All imported content must have valid PRD 16_C headers
- ASCII-only enforcement
- Proper timestamp format (BIGINT UTC)
- Canonical pathing conventions

### 13.2 Technical Constraints

**Performance Requirements:**
- Efficient batch processing for large datasets
- Sub-second response for engagement lookups
- Minimal database query overhead
- Scalable to millions of records

**Data Integrity:**
- Preserve all relationships during transformation
- Maintain referential integrity in application layer
- Handle missing or corrupt data gracefully
- Provide detailed import logging and validation

## 14. Implementation Phases

### 14.1 Phase 1: Core Import (Immediate)
- Basic table reading and structure analysis
- User and department import
- Timestamp normalization
- Basic content import (messages, transcripts)

### 14.2 Phase 2: Engagement Import (Short-term)
- Path and visit analytics import
- Engagement score calculation
- Navigation weight generation
- Basic agent integration

### 14.3 Phase 3: Advanced Mapping (Medium-term)
- Complex relationship mapping
- Memory graph edge creation
- Advanced agent integration
- Validation and reporting

### 14.4 Phase 4: Optimization (Long-term)
- Performance optimization
- Advanced analytics and insights
- Real-time engagement updates
- Cross-site engagement federation

## 15. Success Metrics

### 15.1 Import Quality
- **Data Completeness**: Percentage of legacy data successfully imported (target: >95%)
- **Relationship Preservation**: Correct mapping of all relationships (target: >98%)
- **Temporal Accuracy**: Accurate timestamp conversion (target: 100%)

### 15.2 Agent Integration
- **CHIRON Efficiency**: High-value content identified and prioritized (target: >80% in first 50% of effort)
- **VISH Organization**: Collections aligned with user behavior patterns (target: >85% satisfaction)
- **AGAPE Validation**: Structural issues identified and flagged (target: >90% detection rate)

### 15.3 System Performance
- **Import Speed**: Complete legacy data processing (target: <4 hours for typical dataset)
- **Query Performance**: Engagement data lookup response time (target: <100ms)
- **Storage Efficiency**: Reasonable storage overhead (target: <25% of original data size)

---

# End of PRD 85_C

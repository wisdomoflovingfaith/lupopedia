---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/85_B-i_CRAFTY_SYNTAX_ENGAGEMENT_IMPORT_AND_ORGANIZATIONAL_LEARNING.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/85_B-i_CRAFTY_SYNTAX_ENGAGEMENT_IMPORT_AND_ORGANIZATIONAL_LEARNING.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/85-b-crafty-syntax-engagement-import-and-organizational-learning.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/prd/85-b-crafty-syntax-engagement-import-and-organizational-learning
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 85_B-i_00_A-i_13_A-i_16_C-i_11_A-i_57_A-i_59_A-i_73_A-i_85_A-i
  title: 'PRD 85_B: Crafty Syntax Engagement Import and Organizational Learning'
  summary: Legacy Crafty Syntax engagement/path data import doctrine for organizational intelligence. Imports historical aggregated behavior (enter/exit paths, visit counts, temporal patterns) to guide CHIRON conversion priority, VISH collection organization, AGAPE validation, and navigation design using real user behavior truth.
---

# PRD 85_B: Crafty Syntax Engagement Import and Organizational Learning

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Purpose

This PRD defines the doctrine for importing legacy Crafty Syntax engagement and path data, transforming it into organizational intelligence within Lupopedia. This extends the Crafty Syntax integration lane beyond basic semantics and user import into behavioral truth that guides system organization.

The PRD covers:
- Legacy engagement data import from Crafty Syntax 3.7.5
- Path aggregation and visit truth transformation
- Use of historical behavior for prioritization and organization
- Integration with CHIRON, VISH, and AGAPE for informed decision-making
- Translation of raw usage patterns into structural guidance

This is not merely analytics; it is the systematic import and application of historical behavioral truth to improve Lupopedia's organization based on how users actually interacted with the system.

## 2. Why This Data Matters

Legacy engagement data represents historical behavioral truth, not abstract design assumptions. The aggregated patterns in Crafty Syntax data reveal:

- **Entry Points**: Where users actually begin their journeys
- **Exit Points**: Where users stop or abandon paths
- **Path Popularity**: Which routes through the system are most used
- **Temporal Patterns**: How usage changes over time
- **Dead Ends**: Where users consistently get stuck or leave

Lupopedia should not organize content solely based on theoretical taxonomies or designer assumptions. By learning from actual long-term user behavior, Lupopedia can:

- Prioritize documentation conversion efforts on historically important areas
- Structure collections to match natural user workflows
- Validate that organizational choices align with observed usage
- Identify and address structural anti-patterns that confuse users

This behavioral truth complements, rather than replaces, semantic and doctrinal organization. It provides empirical validation for organizational decisions.

## 3. Source System

### 3.1 Legacy Crafty Syntax 3.7.5

The source data exists in legacy Crafty Syntax database tables, as documented in `old_crafty_syntax_3_7_5_start.sql`. Key source tables include:

- **livehelp_paths_monthly** - Aggregated monthly path data with enter/exit points and visit counts
- **livehelp_paths_daily** - Daily aggregated path data for fine-grained analysis
- **livehelp_visits** - Individual visit records for detailed behavioral analysis
- **livehelp_user_activity** - User engagement patterns and session data

### 3.2 Source Schema Principles

- **Read-Only Legacy**: Source tables are treated as historical import sources only
- **No Redesign**: Do not modify or normalize the legacy schema structure
- **Imperfections Accepted**: Legacy data may have inconsistencies; handle gracefully
- **Provenance Preserved**: Maintain clear lineage from source to transformed data

The legacy schema reflects the operational realities of Crafty Syntax and should be imported as-is, with transformation handled in the import layer.

## 4. Import Scope

### 4.1 Engagement Truth Types

The import focuses on aggregated behavioral truth, not raw click streams:

**Path Data:**
- Entry paths (where users begin)
- Exit paths (where users end)
- Transition paths (sequences between pages)
- Path popularity rankings
- Dead-end identification

**Visit Aggregates:**
- Daily/monthly visit counts per path
- Peak usage periods
- Session duration patterns
- Return visit frequency

**Temporal Engagement:**
- Historical usage trends
- Seasonal patterns
- Growth/decline trajectories
- Time-based path popularity

**Legacy Signals:**
- Historically popular content
- Abandoned features or paths
- User flow bottlenecks
- Successful navigation patterns

### 4.2 Aggregation Level

The import prioritizes aggregated data over raw events:
- Use monthly aggregates for trend analysis
- Use daily aggregates for detailed patterns
- Preserve raw visit data only when needed for specific analyses
- Focus on patterns, not individual user tracking

## 5. Example Aggregation Model

The canonical example representing the import concept:

```sql
SELECT enter, exit, dateof, SUM(visits) AS total_visits
FROM livehelp_paths_monthly
GROUP BY enter, exit, dateof
ORDER BY total_visits DESC;
```

**Interpretation:**
- **enter**: Starting point of user journeys
- **exit**: Ending point where users leave or complete tasks
- **dateof**: Temporal context for behavior patterns
- **total_visits**: Quantified popularity of specific paths

**Organizational Insights:**
- High-traffic entry points indicate important starting content
- Common exit points may indicate task completion or abandonment
- Path popularity guides navigation design
- Temporal changes reveal evolving user needs

This query serves as the motivating example for how aggregated behavioral truth informs organizational decisions.

## 6. Lupopedia Uses of Imported Engagement Data

### 6.1 CHIRON Integration

**Conversion Prioritization:**
- Prioritize documentation conversion for historically popular areas
- Focus on high-traffic entry points first
- Identify legacy content that users actually valued
- Weight conversion efforts by documented usage patterns

**Content Strategy:**
- Identify which legacy documentation deserves immediate attention
- Detect gaps between available content and user demand
- Guide content creation based on historical need patterns

### 6.2 VISH Integration

**Collection Organization:**
- Use path flow data to suggest natural collection groupings
- Identify content that should be co-located based on user paths
- Detect collections that don't match observed user workflows
- Suggest tab structures based on common navigation patterns

**Navigation Design:**
- Optimize collection ordering by historical popularity
- Identify dead-end collections that users abandon
- Create shortcuts for frequently traversed paths
- Validate that collection hierarchy matches user mental models

### 6.3 AGAPE Integration

**Structural Validation:**
- Detect when organization contradicts observed usage patterns
- Generate WHY files for structural anti-patterns
- Validate that high-traffic content is easily accessible
- Identify organizational choices that ignore strong behavioral signals

**Compliance Checking:**
- Ensure that important content isn't buried in obscure locations
- Validate that navigation paths match user expectations
- Check that organizational changes don't disrupt established workflows

### 6.4 Human Navigation/UI Enhancement

**Menu Optimization:**
- Improve top-level navigation based on entry point data
- Reorder semantic navbar suggestions by usage
- Highlight historically important pathways
- Design "related" and "next" suggestions based on common paths

**Collection Presentation:**
- Sort collections within categories by usage popularity
- Provide usage-based recommendations to users
- Identify and promote underutilized but valuable content
- Design breadcrumbs that match common user journeys

## 7. Data Transformation Rules

### 7.1 Transformation Principles

All transformations must be:
- **Explicit**: Every transformation rule documented and reviewable
- **Deterministic**: Same source data always produces same output
- **Traceable**: Clear lineage from source to transformed data
- **Reversible**: Ability to trace transformed data back to source

### 7.2 Output Structures

**Truth Tables:**
- `lupo_engagement_paths` - Transformed path data with Lupopedia IDs
- `lupo_engagement_visits` - Normalized visit aggregates
- `lupo_engagement_temporal` - Time-series engagement data

**Semantic Edges:**
- `engagement_strength` edges between related content
- `path_sequence` edges for common user flows
- `temporal_popularity` edges for time-based relationships

**Guidance Signals:**
- Collection priority weights based on usage
- Navigation ordering hints
- Content importance scores
- Structural validation rules

### 7.3 Mapping Logic

**Path Mapping:**
- Map legacy URLs to Lupopedia content IDs
- Handle URL changes and redirects gracefully
- Preserve path sequences even when individual content moves
- Create fallback mappings for missing content

**Visit Normalization:**
- Convert legacy visit counts to standardized engagement scores
- Normalize across different time periods
- Account for system growth and user base changes
- Create comparable metrics across time

## 8. Temporal Handling

### 8.1 Timestamp Normalization

Legacy Crafty Syntax dates must be converted to Lupopedia canonical format:
- **Source**: Various legacy date formats (possibly DATETIME, TIMESTAMP, or text)
- **Target**: BIGINT UTC timestamps in YYYYMMDDHHIISS format
- **Process**: Explicit transformation in import layer
- **Validation**: Verify all timestamps convert correctly

### 8.2 Temporal Aggregation

**Time Windows:**
- Daily aggregates for detailed analysis
- Monthly aggregates for trend identification
- Yearly aggregates for long-term patterns
- Custom windows for specific analyses

**Temporal Features:**
- Seasonal pattern detection
- Growth trend calculation
- Peak usage identification
- Anomaly detection for unusual periods

### 8.3 Historical Context

Preserve historical context during transformation:
- Maintain original date references for audit
- Create derived features for trend analysis
- Document any date estimation or imputation
- Handle timezone differences explicitly

## 9. Relationship to Existing PRDs

### 9.1 Direct Extensions

- **PRD 13_A** (Crafty Syntax integration): 85_B extends this into behavioral intelligence
- **PRD 85_A** (Crafty Syntax semantics/users): 85_B adds engagement layer to semantic foundation
- **PRD 11_A** (analytics/tracking): 85_B focuses on historical import, not live tracking

### 9.2 Consumer Integration

- **PRD 59_A** (CHIRON): Uses engagement data for conversion prioritization
- **PRD 59_C** (VISH): Uses path data for collection organization and navigation
- **PRD 57_A** (AGAPE): Uses behavioral truth for structural validation
- **PRD 73_A** (Collections): Informed by usage patterns for organization

### 9.3 Complementary Relationships

85_B does not replace existing analytics or tracking systems. It provides historical context that complements:
- Live user behavior tracking
- Real-time performance monitoring
- Current engagement metrics
- User feedback systems

## 10. Non-Goals

This PRD does NOT:

- **Redesign Crafty Schema**: Legacy source tables are imported as-is
- **Replace Live Analytics**: Focus on historical patterns, not real-time tracking
- **Override Human Judgment**: Engagement truth is one signal among many
- **Use Engagement Alone**: Behavioral data informs, but does not dictate, organization
- **Erase Semantic Organization**: Do not discard doctrinal or semantic structure
- **Centralize User Data**: Each site imports its own historical data unless explicitly federated
- **Create Surveillance System**: Focus on aggregated patterns, not individual tracking
- **Automatically Reorganize**: Provide guidance and validation, not autonomous restructuring

## 11. Privacy / Safety / Boundaries

### 11.1 Data Minimization

- **Aggregate Preference**: Use aggregated data over individual records when possible
- **Temporal Blurring**: Consider time-based aggregation to protect individual privacy
- **Path Anonymization**: Focus on patterns, not specific user journeys
- **Retention Policies**: Define appropriate retention periods for imported data

### 11.2 Usage Boundaries

- **Structural Guidance**: Use data for organization, not behavioral manipulation
- **No Creepy Surveillance**: Avoid overly detailed individual user reconstruction
- **Local Install Boundaries**: Each installation uses its own historical data
- **Explicit Federation**: Only share data across installations with explicit consent

### 11.3 Ethical Considerations

- **Transparency**: Document what behavioral data influences which decisions
- **Human Oversight**: Require human review for major organizational changes
- **Bias Awareness**: Recognize historical data may contain biases
- **User Control**: Provide mechanisms for users to understand and influence organization

## 12. Deterministic Import Principles

### 12.1 Constitutional Compliance

Follow all Lupopedia database doctrine requirements:
- **No Foreign Keys**: All relationships enforced in application layer
- **No Triggers/Procedures**: All logic in PHP application code
- **Explicit Lineage**: Clear provenance from source to transformed data
- **Deterministic Outputs**: Same input always produces same output

### 12.2 Import Process

**Read Phase:**
- Connect to legacy Crafty Syntax database
- Read source tables in consistent order
- Handle connection failures gracefully
- Validate data integrity before transformation

**Transform Phase:**
- Apply explicit transformation rules
- Convert timestamps to canonical format
- Map legacy identifiers to Lupopedia equivalents
- Generate derived metrics and features

**Write Phase:**
- Insert transformed data into Lupopedia tables
- Maintain import metadata and provenance
- Handle duplicate imports idempotently
- Validate referential integrity in application layer

### 12.3 Error Handling

- **Graceful Degradation**: Continue import when individual records fail
- **Detailed Logging**: Record all transformation issues and decisions
- **Recovery Mechanisms**: Support incremental and full re-import
- **Validation Reports**: Generate import quality and completeness reports

## 13. Implementation Notes

### 13.1 High-Level Architecture

**Import Service:**
- `CraftyEngagementImporter` - Main import orchestration
- `PathTransformer` - Legacy path to Lupopedia path conversion
- `VisitNormalizer` - Visit count normalization and scoring
- `TemporalProcessor` - Date handling and temporal aggregation

**Data Flow:**
1. Read legacy Crafty Syntax tables
2. Transform into Lupopedia-compatible structures
3. Generate engagement edges and guidance signals
4. Expose guidance to CHIRON, VISH, and AGAPE
5. Preserve provenance throughout the process

### 13.2 Integration Points

**CHIRON Integration:**
- Provide engagement scores for content prioritization
- Supply historical importance metrics
- Identify high-value conversion targets

**VISH Integration:**
- Supply path flow data for collection organization
- Provide navigation optimization hints
- Identify structural improvement opportunities

**AGAPE Integration:**
- Supply behavioral validation rules
- Provide usage-based compliance checks
- Generate structural anomaly alerts

### 13.3 Performance Considerations

- **Batch Processing**: Process large datasets in manageable chunks
- **Incremental Updates**: Support delta imports for new data
- **Caching**: Cache frequently accessed engagement metrics
- **Indexing**: Optimize database indexes for common query patterns

## 14. Success Metrics

### 14.1 Import Quality

- **Data Completeness**: Percentage of legacy data successfully imported (target: >95%)
- **Transformation Accuracy**: Correct mapping of legacy identifiers (target: >98%)
- **Temporal Integrity**: Accurate timestamp conversion (target: 100%)

### 14.2 Organizational Impact

- **Navigation Improvement**: Reduced time to find important content (target: -30%)
- **Conversion Efficiency**: Higher priority content converted first (target: >80% high-value content in first 50% of effort)
- **User Satisfaction**: Improved navigation feedback scores (target: >4.0/5.0)

### 14.3 System Performance

- **Import Speed**: Complete import processing time (target: <2 hours for typical dataset)
- **Query Performance**: Engagement data lookup response time (target: <100ms)
- **Storage Efficiency**: Reasonable storage overhead for engagement data (target: <20% of content storage)

## 15. Implementation Phases

### 15.1 Phase 1: Core Import (Immediate)
- Basic legacy data reading and transformation
- Path and visit aggregation import
- Timestamp normalization
- Basic engagement score calculation

### 15.2 Phase 2: Agent Integration (Short-term)
- CHIRON prioritization integration
- VISH collection guidance integration
- Basic AGAPE validation rules
- Engagement edge creation

### 15.3 Phase 3: Advanced Analytics (Medium-term)
- Temporal pattern analysis
- Predictive engagement modeling
- Advanced structural validation
- User journey optimization

### 15.4 Phase 4: Optimization (Long-term)
- Real-time engagement updates
- Machine learning for pattern detection
- Advanced recommendation systems
- Cross-site engagement federation

---

# End of PRD 85_B

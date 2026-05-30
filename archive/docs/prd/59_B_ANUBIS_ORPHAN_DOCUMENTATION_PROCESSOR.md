---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/prd/59_B_ANUBIS_ORPHAN_DOCUMENTATION_PROCESSOR.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/59_B_ANUBIS_ORPHAN_DOCUMENTATION_PROCESSOR.md"
  status: "active"
  when_updated: "20260423025320"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/prd/canonical/1026/04/59-b-anubis-orphan-documentation-processor.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/prd/59-b-anubis-orphan-documentation-processor"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "prd"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "00_A_16_C_57_A_98_A_59_A_59_B"
  title: "PRD 59_B: ANUBIS Orphan Documentation Processor"
  summary: "ANUBIS processes orphan documentation from lupo_anubis_queue, converts files to Lupopedia constitutional structure, and logs processing to lupo_anubis_processing_log. Actor_id 9, agent_id 9."
---

# PRD 59_B: ANUBIS Orphan Documentation Processor

## 1. Purpose

ANUBIS processes orphan documentation files discovered by CHIRON. It claims items from the processing queue, converts external documentation to Lupopedia constitutional structure, validates all output, and maintains comprehensive processing logs. ANUBIS is the processor component that works with CHIRON's discovery and queuing system.

## 2. Tables

### 2.1 lupo_anubis_queue
Existing table that contains orphan documentation entries queued by CHIRON. ANUBIS claims and processes items from this table. Table schema is not defined in this PRD - use existing structure.

### 2.2 lupo_anubis_processing_log
Existing table that logs all processing activities, results, and status transitions. ANUBIS writes comprehensive log entries to this table for every processing attempt. Table schema is not defined in this PRD - use existing structure.

## 3. Processing Flow

### 3.1 Claim Phase
1. Query lupo_anubis_queue for unclaimed entries
2. Claim next available item using atomic update
3. Set status to "processing" and assign processor_id
4. Log claim initiation to lupo_anubis_processing_log

### 3.2 Read Phase
1. Read the queued file content from specified location
2. Perform initial content validation and structure analysis
3. Identify file type, encoding, and basic metadata
4. Log read completion and initial analysis

### 3.3 Convert Phase
3. Apply CHIRON's inferred structure and header templates
2. Convert content to ASCII-only format
3. Generate complete LUPOPEDIA headers with proper metadata
4. Use the prd_cluster already assigned by CHIRON during Group Discovery
5. Log conversion progress and any transformations applied

### 3.4 Validate Phase
1. Run all content through LUPOPEDIA header validation
2. Validate prd_cluster shorthand format
3. Check ASCII-only compliance
4. Verify file path and naming conventions
5. Log validation results and any failures

### 3.5 Write Phase
1. Apply AGAPE HARD GATE before any file writing
2. Load prd_cluster from generated headers
3. Expand cluster and read all PRDs in order
4. Reconstruct complete causal chain (INTENT, WHO, WHAT, WHERE, WHEN, HOW)
5. If any component missing: AGAPE BLOCK and create WHY file
6. Write converted file to target location only after AGAPE approval
7. Log write completion and final file status

### 3.6 Complete Phase
1. Update queue entry status to "completed" or "failed"
2. Write final processing summary to log
3. Release claim on queue entry
4. Record processing metrics and timing

## 4. ANUBIS Actor

### 4.1 Actor Identity
- actor_id: 9
- agent_id: 9
- Actor name: ANUBIS
- Role: Orphan documentation processor

### 4.2 Actor Configuration
ANUBIS runs as a dedicated actor instance with specific configuration:
- Single-threaded processing to avoid conflicts
- Configurable batch size for queue processing
- Automatic retry logic for transient failures
- Integration with AGAPE for learning and validation

### 4.3 Actor Permissions
ANUBIS requires:
- Read access to lupo_anubis_queue
- Write access to lupo_anubis_processing_log
- File system write access to target documentation locations
- Read access to all PRD files for AGAPE compliance

## 5. Relationship to CHIRON

### 5.1 Division of Responsibilities
CHIRON (PRD 59_A):
- Discovers external documentation
- Analyzes content and infers structure
- Queues orphans in lupo_anubis_queue
- Does not process or write files
- CHIRON is also responsible for checking the PRD Index and deciding groupings before any file reaches ANUBIS

ANUBIS (PRD 59_B):
- Processes items from lupo_anubis_queue
- Converts files to constitutional structure
- Writes validated files to filesystem
- Logs all processing activities

### 5.2 Data Flow
CHIRON → lupo_anubis_queue → ANUBIS → filesystem + lupo_anubis_processing_log

### 5.3 Coordination
CHIRON and ANUBIS operate independently but coordinate through the shared queue table. CHIRON does not modify files; ANUBIS does not discover content.

### 5.4 Grouping Decision Authority

All prd_cluster decisions are made by CHIRON during Group Discovery Phase (see PRD 59_A section 4.0). ANUBIS never creates or modifies groupings — it only processes files that already have a final prd_cluster assigned.

### 5.5 Grouping Decision Authority (Critical)

All prd_cluster decisions are made by CHIRON during Group Discovery Phase (see PRD 59_A Section 4.0).  
ANUBIS never creates or modifies groupings — it only processes files that already have a final prd_cluster assigned.

**See also:**  
- PRD 59_A Section 4.0 (Group Discovery Phase)  
- PRD 59_A Section 3.5 (Group Discovery from PRD Index)

## 6. AGAPE HARD GATE Compliance

### 6.1 Mandatory Pre-Write Validation
Before writing any file to the filesystem, ANUBIS must:
1. Load prd_cluster from the generated header
2. Expand cluster into actual PRD file paths
3. Read all PRDs in exact order
4. Reconstruct complete causal chain:
   - INTENT: What the original documentation was trying to achieve
   - WHO: Which original author or system created the content
   - WHAT: What specific information the content contains
   - WHERE: Where the content should exist in Lupopedia structure
   - WHEN: When the content was created and its relevance timeframe
   - HOW: How the content should be structured and presented

### 6.2 Blocking Conditions
ANUBIS must BLOCK processing and create WHY files when:
- prd_cluster cannot be expanded to valid PRD files
- Any PRD in the cluster cannot be read
- Causal chain reconstruction is incomplete
- Generated headers fail validation
- File paths conflict with existing constitutional structure

### 6.3 AGAPE Integration
All WHY files created by ANUBIS must reference:
- The original queue entry being processed
- Complete causal chain analysis
- Specific validation failures
- Recommended resolution steps

## 7. Failure Handling

### 7.1 Retry Logic
Transient failures trigger automatic retries:
- Network or file system errors: 3 retries with exponential backoff
- Validation failures: 1 retry after header regeneration
- AGAPE blocks: no retry, requires human intervention

### 7.2 Status Transitions
Queue entries follow this status progression:
- queued → processing → completed (success)
- queued → processing → failed → retry → processing → completed (retry success)
- queued → processing → failed → blocked (permanent failure)

### 7.3 WHY File Generation
ANUBIS creates WHY files for:
- AGAPE HARD GATE blocks
- Persistent validation failures
- Conversion errors that cannot be resolved
- Structural conflicts with existing content

### 7.4 Error Logging
All failures must be logged to lupo_anubis_processing_log with:
- Complete error details and stack traces
- Queue entry ID and processing context
- Retry attempts and outcomes
- WHY file references when created

## 8. Non-goals

ANUBIS is NOT responsible for:
- Discovering external documentation (CHIRON's responsibility)
- Creating or modifying database tables
- Developing user interfaces for queue management
- Performing real-time content synchronization
- Handling security vulnerability assessment
- Managing backup and recovery operations
- Optimizing database performance
- Providing analytics or reporting interfaces

---

# End of PRD 59_B

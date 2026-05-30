---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "docs/prd/48_A_MANUAL_ORCHESTRATION_GAP.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/48_A_MANUAL_ORCHESTRATION_GAP.md"
  status: "active"
  when_updated: "20260422000000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/48-manual-orchestration-gap.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/prd_files/manual-orchestration-gap"
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_C_HEADERS_48_A_MANUAL_ORCHESTRATION_GAP"
  title: "PRD 48: Manual Orchestration Gap - Current Workarounds and Required Automations"
  summary: "Documents current manual workflow used to manage 22+ parallel agents, why it exists, and what automations must be implemented to replace it."
---

# PRD 48: Manual Orchestration Gap

## 1. Purpose

Document the current manual workflow used to manage 22+ parallel AI agents, explain why this workaround exists (incomplete web interface), and define the automations required to replace manual orchestration with system-assisted orchestration. This PRD defines reduction of manual friction, not removal of human orchestration control.

### 1.1 Why This Gap Exists

The web interface for channels, collections, threads, and actor routing is not yet complete. Wolfie currently runs 22+ parallel agents (IDEs, CLI, web browsers) and must manually orchestrate their interactions using physical tools and copy/paste operations.

## 2. Current Manual Workaround

### 2.1 Window Arrangement
- **Web chat tabs** (ChatGPT, Claude, etc.) generate raw AI responses
- **Notepad windows** strip emoji and clean formatting
- **IDE windows** (VS Code, Windsurf) execute patches
- **Physical yellow sticky notes** on monitor: channel, collections, tasks, buffer ownership
- **Monitors arranged** in processing chain order (visual workflow)

### 2.2 Emoji Stripping
- Python script exists in `scripts/` (but not web-accessible)
- Wolfie manually copies responses to Notepad to strip emoji before sending to IDE
- Need PHP version for web interface integration

### 2.3 Context Routing
- Wolfie manually copies responses from Actor A to Actor B
- No automatic handoff between agents
- No shared memory (agents write to toons, but Wolfie orchestrates)

### 2.4 Buffer Management
- Wolfie maintains multiple Notepad buffers for different channels/collections
- No persistent buffer state in web interface
- Physical sticky notes track buffer ownership and state

## 3. Why This Is Not Sustainable

### 3.1 Human Limitations
- **Normal humans cannot manage 22+ parallel agents** this way
- Requires exceptional memory and manual coordination
- Physical workspace constraints (monitor space, sticky note surface)

### 3.2 Error-Prone Operations
- **Copy/paste mistakes** (wrong buffer, lost context)
- **Manual routing errors** (sending to wrong actor)
- **Buffer state confusion** (forgetting which buffer contains what)

### 3.3 System Issues
- **No audit trail** of manual routing decisions
- **Wolfie is the single point of failure**
- **Yellow sticky notes do not scale** beyond physical limits

## 4. Required Automations (Plan)

### 4.1 Channel-Aware Routing
- **Implementation**: Link to PRD 02 (Channels) cross-channel messaging
- **Function**: Automatic message routing between actors based on channel context
- **Benefit**: Eliminates manual copy/paste between actor interfaces

### 4.2 Collection-to-Actor Context Sending
- **Implementation**: Link to PRD 73 (Collections) dual-mode UI behavior
- **Function**: Collections can be sent as context to specific actors
- **Benefit**: Replaces manual buffer management with structured context transfer

### 4.3 Emoji Stripping (PHP Implementation)
- **Requirement**: PHP version of existing `scripts/strip_emoji.py`
- **Integration**: Applied automatically to all LLM responses before delivery
- **Configuration**: Per-channel settings (development channels strip, captains_log may preserve)
- **Benefit**: Eliminates manual Notepad cleaning step

### 4.4 Buffer Persistence
- **Implementation**: Link to PRD 38 (Memory) - buffer persistence as memory nodes
- **Storage**: Channel buffers stored in database or toon files
- **Features**: 
  - Actors can read/write to shared buffer with ownership tracking
  - Buffer state must persist independently of any single actor or session
  - Wolfie's "processing chain" becomes a workflow DAG
- **Benefit**: Replaces Notepad buffers with persistent shared state

### 4.5 Actor Handoff Protocol
- **Implementation**: Link to PRD 50 (Agent Coordination) actor handoff protocol
- **Mechanism**: Automatic handoff via database edges or message queues
- **Features**: 
  - Automatic context passing between actors
  - Audit trail of all handoffs
  - Configurable handoff rules per channel/task type
- **Benefit**: Eliminates manual routing decisions

## 5. Dependencies

### 5.1 PRD 02 (Channels)
- Cross-channel messaging infrastructure
- Channel-aware message routing
- Actor-to-actor communication protocols

### 5.2 PRD 73 (Collections)
- Collection-to-actor context sending
- Dual-mode UI behavior (display vs context-transfer)
- Collection grouping and organization

### 5.3 PRD 38 (Memory)
- Buffer persistence as memory nodes
- Shared state management
- Actor ownership tracking

### 5.4 PRD 50 (Agent Coordination)
- Actor handoff protocol
- Message queues and routing
- Coordination workflows

## 6. Success Criteria

### 6.1 Wolfie's Workflow
- Wolfie can close all Notepad windows
- Physical yellow sticky notes can be removed
- Manual copy/paste operations eliminated

### 6.2 Normal Human Usability
- Normal human can manage 10+ parallel agents without manual copy/paste
- Clear visual workflow in web interface
- Intuitive drag/drop or click-based routing

### 6.3 Automated Features
- Emoji stripped automatically from all development channel responses
- Processing chain is visual or automatically routed
- Buffer state persists across sessions

### 6.4 System Reliability
- Complete audit trail of all routing decisions
- No single point of failure
- Graceful handling of actor failures

## 7. Implementation Priority

### 7.1 Phase 1: Critical Automation
1. PHP emoji stripping (immediate need)
2. Basic channel-aware routing
3. Simple buffer persistence

### 7.2 Phase 2: Workflow Integration
1. Collection-to-actor context sending
2. Actor handoff protocol
3. Visual workflow representation

### 7.3 Phase 3: Advanced Features
1. Complex workflow DAGs
2. Advanced buffer management
3. Performance optimizations

---

**Status**: DRAFT - Open for review and implementation planning

---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: "lupo-docs/prd/04_lupopedia_js_foundation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/04_lupopedia_js_foundation.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "js-foundation"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "js_foundation"
  purpose: "PRD for Lupopedia.js Foundation and State Mirror System v4.0.93"
  tags:
  - "prd"
  - "js_foundation"
  - "v4.0.93"
  - "frontend"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-database/lupopedia/json/lupo_visits.json"
      type: references
      weight: 1.0
      reason: Table definition for visitor tracking
    - to: "lupo-database/lupopedia/json/lupo_human_requests.json"
      type: references
      weight: 1.0
      reason: Table definition for human requests
    - to: "lupo-database/lupopedia/json/lupo_human_request_responses.json"
      type: references
      weight: 1.0
      reason: Table definition for human request responses
    - to: "lupo-database/lupopedia/json/lupo_interpretation_log.json"
      type: references
      weight: 1.0
      reason: Table definition for interpretation logs
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"

# 04. Lupopedia JS Foundation (The Nerves) - 4.0.93

## 🚨 **LILITH "Source of Truth" Protocol**

**Toon Files (Read-Only Senses)**: The `.json` files in `lupo-database/lupopedia/json/` are **READ-ONLY** artifacts. All JS event logic must reference the actual database schema, not assumed structures.

## 📋 **JS Event Architecture (From Migration Mapping)**

### **livehelp_visitors → lupo_visitors Mapping**
Based on the migration blueprint in `lupo-docs/doctrine/migrations/`, the visitor tracking system maps legacy Crafty Syntax visitor data to the new semantic architecture.

### **Core Event Listeners**

#### **Referer Tracking Events**
```javascript
// Event: Visitor arrives with referer
LupoEvents.on('visitor:arrive', function(data) {
  const visitorData = {
    visitor_id: LupoIdGenerator.generate(), // 63-bit safe integer
    referer_url: data.referer,
    landing_page: data.page,
    user_agent: data.userAgent,
    ip_address: data.ip,
    session_id: data.sessionId,
    created_ymdhis: LupoTimestamp.now(), // UTC bigint
    entry_context_id: null // Populated by semantic analysis
  };
  
  // Insert into lupo_visitors (database-first)
  LupoDatabase.insert('lupo_visitors', visitorData);
});
```

#### **Page Tracking Events**
```javascript
// Event: Visitor navigates to new page
LupoEvents.on('visitor:page_view', function(data) {
  const pageViewData = {
    visitor_id: data.visitorId,
    page_url: data.page,
    page_title: data.title,
    time_on_page: data.timeOnPage,
    scroll_depth: data.scrollDepth,
    context_weight: calculateContextWeight(data.page), // Semantic analysis
    created_ymdhis: LupoTimestamp.now()
  };
  
  // Insert into lupo_visitor_page_views (extends lupo_visitors)
  LupoDatabase.insert('lupo_visitor_page_views', pageViewData);
});
```

### **Live Typing (Softaculous Requirement)**

#### **Non-Persistent Stream Architecture**
```javascript
// Live Typing: Stream through State Mirror without DB writes
LupoEvents.on('chat:typing', function(typingData) {
  // Route through State Mirror for real-time refraction
  LupoStateMirror.stream('typing_refraction', {
    actor_id: typingData.actorId,
    channel_id: typingData.channelId,
    thread_id: typingData.threadId,
    keystroke_data: typingData.keystrokes,
    timestamp: LupoTimestamp.now(),
    // No persistent storage - pure stream
    persistence: 'none'
  });
});
```

#### **High-Density Scroller Integration**
```javascript
// Glass UI: 60fps keystroke refractions
class LupoTypingGlass {
  constructor() {
    this.fps = 60;
    this.frameInterval = 1000 / this.fps;
    this.lastFrame = 0;
  }
  
  refract(typingEvent) {
    const now = performance.now();
    if (now - this.lastFrame >= this.frameInterval) {
      this.renderKeystroke(typingEvent);
      this.lastFrame = now;
    }
  }
  
  renderKeystroke(event) {
    // Render typing preview in chat scroller
    // Maintains 60fps while streaming real-time data
    const preview = document.querySelector('.typing-preview');
    preview.textContent = event.currentText;
    preview.classList.add('keystroke-refraction');
  }
}
```

### **Contextual Visitor Analysis**

#### **Semantic Context Linking**
```javascript
// Link visitor sessions to GOLD status contexts
LupoEvents.on('visitor:context_analysis', function(data) {
  const contextAnalysis = {
    visitor_id: data.visitorId,
    context_id: data.contextId, // GOLD status context
    relevance_score: data.relevanceScore,
    engagement_level: calculateEngagement(data),
    created_ymdhis: LupoTimestamp.now()
  };
  
  // Insert into lupo_visitor_context_links
  LupoDatabase.insert('lupo_visitor_context_links', contextAnalysis);
  
  // Trigger proactive invite if high-weight context
  if (data.relevanceScore > 0.8) {
    LupoEvents.emit('invite:proactive', {
      visitor_id: data.visitorId,
      context_id: data.contextId,
      invite_type: 'contextual'
    });
  }
});
```

### **Database-First Event Processing**

#### **Event Queue Architecture**
```javascript
// All events route through database-first queue
class LupoEventQueue {
  constructor() {
    this.db = LupoDatabase.getConnection();
    this.stateMirror = new LupoStateMirror();
  }
  
  async process(event) {
    // 1. Validate event structure
    if (!this.validateEvent(event)) return false;
    
    // 2. Store persistent data in database
    if (event.persistence === 'persistent') {
      await this.db.insert(event.table, event.data);
    }
    
    // 3. Stream to State Mirror for real-time effects
    if (event.streaming === true) {
      this.stateMirror.stream(event.streamName, event.data);
    }
    
    return true;
  }
}
```

## 🚨 **Softaculous Compliance Requirements**

### **Expected Hooks Implementation**
- **Visitor Tracking**: Full referer and page path tracking
- **Live Typing**: Real-time keystroke streaming without persistence
- **Session Management**: Persistent session storage in `lupo_visitors`
- **Context Awareness**: Link visitor behavior to semantic contexts

### **Performance Constraints**
- **60fps Glass UI**: All typing effects must maintain 60fps
- **Non-blocking**: Event processing cannot block UI thread
- **Memory Efficient**: No persistent storage for typing events

## 🔍 **Migration Mapping Reference**

The `livehelp_` to `lupo_` mapping provides:
- **Field mapping**: `livehelp_visitors.visitor_id` → `lupo_visitors.visitor_id`
- **Semantic enhancement**: Added context awareness and engagement tracking
- **Softaculous parity**: Maintains all expected visitor tracking features

---

**RULE [93.PROTECT_SCHEMA_JSON]** (formerly PROTECT_TOONS): JS event logic must reference actual database schema from `lupo-database/lupopedia/json/*.json`, not assumed structures. All schema evolution must be verified with `generate_toon_files.py`. See `00_root_constitutional_system_requirements.md` §6 and §9.9.

**LILITH Verdict**: The "Nerves" must connect to the real "Senses" (database schema) not imagined structures.


---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```

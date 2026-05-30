---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: /docs/versions/4.0.93/prd/04_lupopedia_js_foundation.md
  web_path: http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/prd/04_lupopedia_js_foundation.md
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
    - to: "database/lupopedia/json/lupo_visits.json"
      type: references
      weight: 1.0
      reason: Table definition for visitor tracking
    - to: "database/lupopedia/json/lupo_human_requests.json"
      type: references
      weight: 1.0
      reason: Table definition for human requests
    - to: "database/lupopedia/json/lupo_human_request_responses.json"
      type: references
      weight: 1.0
      reason: Table definition for human request responses
    - to: "database/lupopedia/json/lupo_interpretation_log.json"
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

**Toon Files (Read-Only Senses)**: The `.json` files in `database/lupopedia/json/` are **READ-ONLY** artifacts. All JS event logic must reference the actual database schema, not assumed structures.

## 📋 **JS Event Architecture (From Migration Mapping)**

### **livehelp_visitors → lupo_visitors Mapping**
Based on the migration blueprint in `docs/doctrine/migrations/`, the visitor tracking system maps legacy Crafty Syntax visitor data to the new semantic architecture.

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

**RULE [93.PROTECT_TOONS]**: JS event logic must reference actual database schema from Toon JSONs, not assumed structures. All schema evolution must be verified with `generate_toon_files.py`.

**LILITH Verdict**: The "Nerves" must connect to the real "Senses" (database schema) not imagined structures.

---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: /lupo-docs/versions/4.0.93/prd/01_semantic_monitoring_widget.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/prd/01_semantic_monitoring_widget.md
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "semantic-monitoring"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "prd"
  artifact_kind: "ui_widget"
  purpose: "PRD for Semantic Monitoring Widget v4.0.93"
  tags:
  - "prd"
  - "semantic_monitoring"
  - "ui_widget"
  - "v4.0.93"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-database/lupopedia/json/lupo_context_edges.json"
      type: references
      weight: 1.0
      reason: Table definition for semantic edges
    - to: "lupo-database/lupopedia/json/lupo_context_cards.json"
      type: references
      weight: 1.0
      reason: Table definition for context cards
    - to: "lupo-database/lupopedia/json/lupo_contexts_map.json"
      type: references
      weight: 1.0
      reason: Table definition for context mapping
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent

# 01. Semantic Monitoring Widget (The Eye) - 4.0.93

## 🚨 **LILITH "Source of Truth" Protocol**

**Toon Files (Read-Only Senses)**: The `.json` files in `lupo-database/lupopedia/json/` are **READ-ONLY** artifacts. The Monitoring Widget must reference actual `lupo_edges` schema, not assumed structures.

## 📘 **PRD Update — Subdirectory Installation Doctrine (Critical Constraint)**

### **1. Installation Constraint (Critical Doctrine)**

**Lupopedia MUST always be installed in a subdirectory of the host site.**

Examples:
- `https://example.com/lupopedia/` 
- `https://mysite.org/knowledge/` 
- `https://domain.com/support/lupopedia/` 

This is a **hard requirement** because:
- Auto-installers (Softaculous, Installatron, Fantastico) **do not allow** replacing or overwriting of web root
- Lupopedia must coexist with an existing site
- The Semantic Monitoring Widget (The Eye) must monitor **the site above it**, not Lupopedia directory itself

**This rule must be explicitly documented in:**
- PRDs
- INSTALL.md  
- ORGANIZATION.md  
- The Eye documentation  
- The installer wizard text

### **2. Monitoring Architecture**

**The Eye monitors the parent site, not Lupopedia.**

The monitoring scripts:
- `lupopedia_js.php` 
- `livehelp_js.php` 

are PHP endpoints that generate JavaScript dynamically.

These scripts:
- are served from inside the Lupopedia directory  
- are embedded into pages **outside** of Lupopedia directory  
- track user navigation across the host site  
- collect referers, page paths, dwell time, and content interactions  
- send events back to Lupopedia via AJAX endpoints  

**Therefore:**
> **The Eye must always assume Lupopedia is NOT in the web root.**

This affects:
- path resolution  
- cookie scope  
- JS include URLs  
- referer normalization  
- content ID extraction  
- cross-directory access rules

### **3. Functional Requirements**

#### **3.1 JavaScript Injection**

The Eye must generate a `<script>` include that works regardless of subdirectory name.

Example:
```html
<script src="/lupopedia/lupopedia_js.php"></script>
```

But the system must NOT assume the folder is literally named `lupopedia`.

The installer must detect and store:
```php
LUPOPEDIA_SUBDIRECTORY = "/lupopedia/"
```

And all JS includes must use this value.

#### **3.2 Content Interaction Bar**

`lupopedia_js.php` must generate a dynamic toolbar that supports:
- Likes  
- Shares  
- Comments  
- Content metadata  
- Semantic edges (optional future)  

This toolbar must appear on **host site pages**, not inside Lupopedia.

#### **3.3 Visitor Tracking**

`livehelp_js.php` must:
- track page views  
- track referers  
- track dwell time  
- track navigation path  
- send events to `lupo_visitors`  
- support auto-invite logic  
- support operator monitoring  

This is a Crafty Syntax parity requirement.

### **4. Technical Constraints**

#### **4.1 No Assumption of Web Root**

All paths must be relative to:
```php
/<subdirectory>/
```

NOT:
```php
/
```

#### **4.2 No Hardcoded Folder Names**

The system must never assume:
- `/lupopedia/` 
- `/support/` 
- `/helpdesk/` 

The installer must detect the folder name and write it to:
```php
lupopedia-config.php
```

#### **4.3 No Cross-Directory File Access**

The Eye must use:
- AJAX  
- JS includes  
- API endpoints  

NOT filesystem traversal.

### **5. Required Documentation Updates**

The following files must be updated to reflect the "subdirectory-only" doctrine:
- `lupo-docs/ORGANIZATION.md` 
- `lupo-docs/versions/4.0.93/PLAN.md` 
- `lupo-docs/versions/4.0.93/prd/semantic_monitoring_widget.md` 
- `lupo-docs/installer/INSTALL.md` 
- Installer wizard text (`install.php`)
- `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` 

Each must include:
> **"Lupopedia is always installed in a subdirectory of the host site.  
> The system must never assume installation at the web root."**

### **6. Required IDE Awareness**

The IDE must understand:
- Lupopedia is always in a subdirectory  
- The Eye monitors the parent site  
- JS endpoints must be subdirectory-aware  
- The installer must detect the folder name  
- JSON TOON files are read-only  
- Schema changes belong in `install_new_lupopedia.sql`  
- The Eye depends on visitor tracking tables (`lupo_visitors`, `lupo_referers`, etc.)  

This must be added to:
```php
lupo-docs/ide/IDE_BEHAVIOR_RULES.md
```

## 📋 **lupo_edges Schema Analysis (From Toon JSON)**

### **Core Edge Structure**
```sql
CREATE TABLE lupo_edges (
  edge_id bigint NOT NULL,                        -- 63-bit Signed-Safe Integer
  left_object_type varchar(50) NOT NULL,         -- Source object type
  left_object_id bigint NOT NULL,                -- Source object ID
  right_object_type varchar(50) NOT NULL,        -- Target object type
  right_object_id bigint NOT NULL,               -- Target object ID
  edge_type varchar(100) NOT NULL,               -- Edge classification
  edge_category varchar(100),                    -- Edge category
  edge_description text,                         -- Edge description
  channel_id bigint,                            -- Channel context
  channel_key varchar(64),                       -- Channel key
  domain_id bigint NOT NULL DEFAULT 1,           -- Domain scope
  weight_score int NOT NULL DEFAULT 0,           -- Weight score
  sort_num int NOT NULL DEFAULT 0,               -- Sort order
  actor_id bigint,                              -- Actor who created edge
  is_deleted tinyint NOT NULL DEFAULT 0,         -- Soft delete
  deleted_ymdhis bigint NOT NULL DEFAULT 0,      -- Delete timestamp
  created_ymdhis bigint NOT NULL DEFAULT 0,      -- Creation timestamp
  updated_ymdhis bigint NOT NULL DEFAULT 0,      -- Update timestamp
  semantic_weight decimal(5,2) DEFAULT 0.00,     -- Semantic weight
  relationship_type varchar(64) DEFAULT 'semantic', -- Relationship type
  bidirectional tinyint NOT NULL DEFAULT 0,      -- Bidirectional flag
  context_scope varchar(100),                    -- Context scope
  properties json,                               -- Edge properties
  -- FLARE legacy fields (deprecated but retained)
  flare_weight decimal(3,2) DEFAULT 0.50,
  flare_reason varchar(255),
  flare_db_source varchar(50),
  flare_auto_generated tinyint DEFAULT 0,
  flare_verified tinyint DEFAULT 0,
  flare_discovered_via varchar(50)
);
```

## 🔍 **Monitoring Widget Architecture**

### **Visitor-Context Visualization (Subdirectory-Aware)**
```javascript
class LupoMonitoringWidget {
  constructor() {
    this.db = LupoDatabase.getConnection();
    this.stateMirror = new LupoStateMirror();
    this.visualization = new LupoContextVisualizer();
    this.subdirectory = this.detectSubdirectory(); // CRITICAL
  }
  
  detectSubdirectory() {
    // Detect Lupopedia subdirectory from current script path
    const scriptPath = document.querySelector('script[src*="lupopedia_js.php"]');
    if (scriptPath) {
      const src = scriptPath.getAttribute('src');
      const match = src.match(/^(\/[^\/]+)\/lupopedia_js\.php/);
      return match ? match[1] : '/lupopedia';
    }
    return '/lupopedia'; // fallback
  }
  
  async visualizeVisitorContext(visitorId) {
    // Query lupo_edges for visitor-to-context relationships
    const query = `
      SELECT 
        e.edge_id,
        e.left_object_type,
        e.left_object_id,
        e.right_object_type,
        e.right_object_id,
        e.edge_type,
        e.semantic_weight,
        e.relationship_type,
        c.context_name,
        c.context_description,
        c.weight_score as context_weight
      FROM lupo_edges e
      LEFT JOIN lupo_contexts c ON (
        (e.left_object_type = 'visitor' AND e.right_object_type = 'context' AND e.right_object_id = c.context_id) OR
        (e.right_object_type = 'visitor' AND e.left_object_type = 'context' AND e.left_object_id = c.context_id)
      )
      WHERE (e.left_object_type = 'visitor' AND e.left_object_id = ?) OR
            (e.right_object_type = 'visitor' AND e.right_object_id = ?)
      AND e.is_deleted = 0
      ORDER BY e.semantic_weight DESC
    `;
    
    const relationships = await this.db.fetchAll(query, [visitorId, visitorId]);
    
    // Visualize visitor-context link graph (subdirectory-aware)
    this.visualization.renderContextGraph(relationships, {
      visitorId: visitorId,
      focusOnGoldStatus: true,
      weightThreshold: 0.5,
      subdirectory: this.subdirectory // Pass to visualization
    });
  }
}
```

### **GOLD Status Context Detection**
```javascript
class LupoGoldStatusDetector {
  constructor() {
    this.subdirectory = this.detectSubdirectory();
  }
  
  async detectGoldContexts(visitorId) {
    // Find visitor's connection to GOLD status contexts
    const goldQuery = `
      SELECT DISTINCT
        c.context_id,
        c.context_name,
        c.context_description,
        c.weight_score,
        e.semantic_weight as connection_strength,
        e.edge_type as connection_type
      FROM lupo_edges e
      JOIN lupo_contexts c ON (
        (e.left_object_type = 'visitor' AND e.right_object_type = 'context' AND e.right_object_id = c.context_id) OR
        (e.right_object_type = 'visitor' AND e.left_object_type = 'context' AND e.left_object_id = c.context_id)
      )
      WHERE (e.left_object_type = 'visitor' AND e.left_object_id = ?) OR
            (e.right_object_type = 'visitor' AND e.right_object_id = ?)
      AND c.weight_score >= 0.8  -- GOLD status threshold
      AND e.semantic_weight >= 0.5
      AND e.is_deleted = 0
      ORDER BY e.semantic_weight DESC, c.weight_score DESC
    `;
    
    return await this.db.fetchAll(goldQuery, [visitorId, visitorId]);
  }
  
  detectSubdirectory() {
    // Same detection logic as LupoMonitoringWidget
    return window.LUPOPEDIA_SUBDIRECTORY || '/lupopedia';
  }
}
```

## 🎯 **Widget UI Components (Subdirectory-Aware)**

### **Context Graph Visualization**
```javascript
class LupoContextVisualizer {
  renderContextGraph(edges, options) {
    const svg = d3.select('#context-graph');
    
    // Create force-directed graph
    const simulation = d3.forceSimulation(edges)
      .force('link', d3.forceLink().id(d => d.edge_id))
      .force('charge', d3.forceManyBody().strength(-300))
      .force('center', d3.forceCenter(400, 300));
    
    // Render visitor node (central)
    const visitorNode = svg.append('g')
      .attr('class', 'visitor-node')
      .attr('transform', `translate(400, 300)`);
    
    visitorNode.append('circle')
      .attr('r', 20)
      .attr('fill', '#4169E1')
      .attr('stroke', '#fff')
      .attr('stroke-width', 2);
    
    // Render context nodes (orbiting)
    const contextNodes = svg.selectAll('.context-node')
      .data(edges.filter(e => e.context_weight >= 0.8))
      .enter()
      .append('g')
      .attr('class', 'context-node');
    
    contextNodes.append('circle')
      .attr('r', d => 10 + (d.context_weight * 10))
      .attr('fill', d => d.context_weight >= 0.9 ? '#FFD700' : '#FFA500')
      .attr('stroke', '#fff')
      .attr('stroke-width', 2);
    
    // Render connection lines
    const connections = svg.selectAll('.connection')
      .data(edges)
      .enter()
      .append('line')
      .attr('class', 'connection')
      .attr('stroke', d => `rgba(65, 105, 225, ${d.semantic_weight})`)
      .attr('stroke-width', d => Math.max(1, d.semantic_weight * 5));
  }
}
```

## 🚨 **Softaculous Integration**

### **Expected Monitoring Features**
- **Live Visitor Tracking**: Real-time visitor session monitoring
- **Context Analysis**: Automatic GOLD status context detection
- **Proactive Alerts**: Operator notifications for high-value interactions
- **Visual Analytics**: Graph-based relationship visualization
- **Subdirectory Awareness**: Works regardless of installation path

### **Performance Requirements**
- **Real-time Updates**: Sub-second edge monitoring
- **Efficient Queries**: Optimized lupo_edges queries with proper indexing
- **Memory Management**: Efficient graph rendering for large edge sets
- **Path Resolution**: Correct handling of subdirectory installations

---

**RULE [93.PROTECT_TOONS]**: The Monitoring Widget must reference actual `lupo_edges` schema from Toon JSONs, not assumed edge structures. All schema evolution must be verified with `generate_toon_files.py`.

**LILITH Verdict**: The "Eye" must see through real "Senses" (lupo_edges schema) not imagined visualization patterns, AND must always assume subdirectory installation.

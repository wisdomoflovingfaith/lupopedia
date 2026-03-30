# 01. Semantic Monitoring Widget (The Eye) - 4.0.93

## 🚨 **LILITH "Source of Truth" Protocol**

**Toon Files (Read-Only Senses)**: The `.json` files in `lupo-database/lupopedia/json/` are **READ-ONLY** artifacts. The Monitoring Widget must reference the actual `lupo_edges` schema, not assumed structures.

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

### **Visitor-Context Visualization**
```javascript
class LupoMonitoringWidget {
  constructor() {
    this.db = LupoDatabase.getConnection();
    this.stateMirror = new LupoStateMirror();
    this.visualization = new LupoContextVisualizer();
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
    
    // Visualize the visitor-context link graph
    this.visualization.renderContextGraph(relationships, {
      visitorId: visitorId,
      focusOnGoldStatus: true,
      weightThreshold: 0.5
    });
  }
}
```

### **GOLD Status Context Detection**
```javascript
class LupoGoldStatusDetector {
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
}
```

### **Real-Time Context Edge Monitoring**
```javascript
class LupoContextEdgeMonitor {
  constructor() {
    this.stateMirror = new LupoStateMirror();
    this.widget = new LupoMonitoringWidget();
  }
  
  startMonitoring() {
    // Monitor real-time edge creation
    this.stateMirror.on('edge:created', (edgeData) => {
      if (edgeData.edge_type === 'visitor_context_link') {
        this.updateVisualization(edgeData);
      }
    });
    
    // Monitor semantic weight changes
    this.stateMirror.on('edge:weight_updated', (edgeData) => {
      if (edgeData.relationship_type === 'semantic') {
        this.refreshContextGraph(edgeData);
      }
    });
  }
  
  updateVisualization(edgeData) {
    // Update the monitoring widget with new edge
    this.widget.addEdgeToVisualization(edgeData);
    
    // Trigger alert if GOLD context connection
    if (edgeData.semantic_weight >= 0.8) {
      this.triggerGoldContextAlert(edgeData);
    }
  }
  
  triggerGoldContextAlert(edgeData) {
    // Alert operator about high-value visitor-context connection
    LupoEvents.emit('monitoring:gold_context_detected', {
      visitor_id: edgeData.left_object_type === 'visitor' ? edgeData.left_object_id : edgeData.right_object_id,
      context_id: edgeData.left_object_type === 'context' ? edgeData.left_object_id : edgeData.right_object_id,
      connection_strength: edgeData.semantic_weight,
      timestamp: edgeData.created_ymdhis
    });
  }
}
```

## 🎯 **Widget UI Components**

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

### **Real-Time Metrics Dashboard**
```javascript
class LupoMetricsDashboard {
  updateMetrics(visitorId) {
    // Calculate real-time metrics from lupo_edges
    const metricsQuery = `
      SELECT 
        COUNT(*) as total_connections,
        AVG(e.semantic_weight) as avg_connection_strength,
        MAX(e.semantic_weight) as max_connection_strength,
        COUNT(CASE WHEN c.weight_score >= 0.8 THEN 1 END) as gold_context_count,
        SUM(CASE WHEN e.edge_type = 'proactive_invite' THEN 1 ELSE 0 END) as proactive_invites
      FROM lupo_edges e
      LEFT JOIN lupo_contexts c ON (
        (e.left_object_type = 'context' AND e.left_object_id = c.context_id) OR
        (e.right_object_type = 'context' AND e.right_object_id = c.context_id)
      )
      WHERE (e.left_object_type = 'visitor' AND e.left_object_id = ?) OR
            (e.right_object_type = 'visitor' AND e.right_object_id = ?)
      AND e.is_deleted = 0
    `;
    
    this.db.fetchOne(metricsQuery, [visitorId, visitorId])
      .then(metrics => this.renderMetrics(metrics));
  }
  
  renderMetrics(metrics) {
    document.querySelector('#total-connections').textContent = metrics.total_connections;
    document.querySelector('#avg-strength').textContent = (metrics.avg_connection_strength * 100).toFixed(1) + '%';
    document.querySelector('#gold-contexts').textContent = metrics.gold_context_count;
    document.querySelector('#proactive-invites').textContent = metrics.proactive_invites;
  }
}
```

## 🚨 **Softaculous Integration**

### **Expected Monitoring Features**
- **Live Visitor Tracking**: Real-time visitor session monitoring
- **Context Analysis**: Automatic GOLD status context detection
- **Proactive Alerts**: Operator notifications for high-value interactions
- **Visual Analytics**: Graph-based relationship visualization

### **Performance Requirements**
- **Real-time Updates**: Sub-second edge monitoring
- **Efficient Queries**: Optimized lupo_edges queries with proper indexing
- **Memory Management**: Efficient graph rendering for large edge sets

---

**RULE [93.PROTECT_TOONS]**: The Monitoring Widget must reference actual `lupo_edges` schema from Toon JSONs, not assumed edge structures. All schema evolution must be verified with `generate_toon_files.py`.

**LILITH Verdict**: The "Eye" must see through the real "Senses" (lupo_edges schema) not imagined visualization patterns.

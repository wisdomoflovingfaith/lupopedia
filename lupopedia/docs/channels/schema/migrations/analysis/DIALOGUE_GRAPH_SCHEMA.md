# 📋 **Dialogue Graph Schema**

## 🎯 **Multi-Actor Dialogue Layer Database Schema**

The dialogue graph is built on the TOON analytics system, providing a semantic graph structure for analyzing conversations, truth relationships, and narrative flows.

---

## 🗄️ **Core Tables**

### **📋 lupo_content_events (Primary Dialogue Table)**
```sql
CREATE TABLE lupo_content_events (
    content_event_id BIGINT NOT NULL AUTO_INCREMENT,
    content_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    event_data JSON,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (content_event_id),
    INDEX idx_content_id (content_id),
    INDEX idx_actor_id (actor_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_ymdhis (created_ymdhis),
    INDEX idx_content_actor (content_id, actor_id),
    INDEX idx_content_type (content_id, event_type)
);
```

### **📋 lupo_actors (Canonical Identity Layer)**
```sql
CREATE TABLE lupo_actors (
    actor_id BIGINT NOT NULL AUTO_INCREMENT,
    actor_type VARCHAR(50) NOT NULL,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    deleted_ymdhis BIGINT,
    actor_source_id BIGINT,
    actor_source_table VARCHAR(100),
    PRIMARY KEY (actor_id),
    INDEX idx_actor_type (actor_type),
    INDEX idx_source (actor_source_id, actor_source_table),
    INDEX idx_created_ymdhis (created_ymdhis)
);
```

### **📋 lupo_session_events (Session Context)**
```sql
CREATE TABLE lupo_session_events (
    session_event_id BIGINT NOT NULL AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    actor_id BIGINT NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    event_data JSON,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (session_event_id),
    INDEX idx_session_id (session_id),
    INDEX idx_actor_id (actor_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_ymdhis (created_ymdhis)
);
```

### **📋 lupo_tab_events (Tab Context)**
```sql
CREATE TABLE lupo_tab_events (
    tab_event_id BIGINT NOT NULL AUTO_INCREMENT,
    tab_id VARCHAR(255) NOT NULL,
    actor_id BIGINT NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    event_data JSON,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (tab_event_id),
    INDEX idx_tab_id (tab_id),
    INDEX idx_actor_id (actor_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_ymdhis (created_ymdhis)
);
```

### **📋 lupo_world_events (World Context)**
```sql
CREATE TABLE lupo_world_events (
    world_event_id BIGINT NOT NULL AUTO_INCREMENT,
    world_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    event_data JSON,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (world_event_id),
    INDEX idx_world_id (world_id),
    INDEX idx_actor_id (actor_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_ymdhis (created_ymdhis)
);
```

### **📋 lupo_event_metadata (Event Metadata)**
```sql
CREATE TABLE lupo_event_metadata (
    metadata_id BIGINT NOT NULL AUTO_INCREMENT,
    event_id BIGINT NOT NULL,
    metadata_key VARCHAR(100) NOT NULL,
    metadata_value TEXT,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (metadata_id),
    INDEX idx_event_id (event_id),
    INDEX idx_metadata_key (metadata_key),
    INDEX idx_created_ymdhis (created_ymdhis)
);
```

### **📋 lupo_event_log (Central Event Log)**
```sql
CREATE TABLE lupo_event_log (
    event_id BIGINT NOT NULL AUTO_INCREMENT,
    event_type VARCHAR(100) NOT NULL,
    event_data JSON,
    created_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (event_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_ymdhis (created_ymdhis)
);
```

---

## 🔗 **Dialogue Graph Relationships**

### **📋 Actor-Content Relationship**
```
lupo_actors (1) ←→ (N) lupo_content_events
```
- Each actor can participate in multiple content events
- Each content event has exactly one actor
- Relationship: `lupo_content_events.actor_id → lupo_actors.actor_id`

### **📋 Thread-Content Relationship**
```
Thread (Virtual) ←→ (N) lupo_content_events
```
- Thread is a virtual concept stored in `event_data.thread_id`
- Multiple content events belong to one thread
- Relationship: `JSON_EXTRACT(lupo_content_events.event_data, '$.thread_id')`

### **📋 Session-Content Relationship**
```
lupo_session_events (1) ←→ (N) lupo_content_events
```
- Each session can have multiple content events
- Content events can be linked to sessions via `event_data.session_id`
- Relationship: `JSON_EXTRACT(lupo_content_events.event_data, '$.session_id')`

### **📋 Tab-Content Relationship**
```
lupo_tab_events (1) ←→ (N) lupo_content_events
```
- Each tab can have multiple content events
- Content events can be linked to tabs via `event_data.tab_id`
- Relationship: `JSON_EXTRACT(lupo_content_events.event_data, '$.tab_id')`

---

## 📊 **Event Data Schema**

### **📋 Message Event Data Structure**
```json
{
    "thread_id": "thread_123_session_456",
    "session_id": "session_456",
    "tab_id": "operator_tab_789",
    "direction": "outgoing|incoming",
    "role": "human|legacy_user|external_ai|persona|system",
    "channel": "channel_123",
    "department": "department_456",
    "language": "en",
    "message": "Hello, how can I help you?",
    "message_type": "text|system|auto",
    "timestamp": "20260122123456",
    "metadata": {
        "source": "web|external|mobile",
        "priority": "normal|high|urgent",
        "tags": ["greeting", "welcome"]
    }
}
```

### **📋 Conversation Context Event Data Structure**
```json
{
    "thread_id": "thread_123_session_456",
    "session_id": "session_456",
    "tab_id": "operator_tab_789",
    "conversation_state": "active|ended|transferred",
    "participant_count": 2,
    "channel_type": "web|external|mobile",
    "department_id": "department_456",
    "start_time": "20260122120000",
    "end_time": "20260122123000",
    "duration_seconds": 1800,
    "metadata": {
        "auto_invite": false,
        "transfer_count": 0,
        "resolution_type": "resolved|escalated|abandoned"
    }
}
```

### **📋 Actor Action Event Data Structure**
```json
{
    "actor_id": 123,
    "action": "login|logout|status_change|presence_update",
    "previous_state": "offline|away|busy",
    "new_state": "online|away|busy",
    "session_id": "session_456",
    "tab_id": "operator_tab_789",
    "channel_id": "channel_123",
    "department_id": "department_456",
    "timestamp": "20260122123456",
    "metadata": {
        "client_info": "web|mobile|external",
        "location": "console|chat|admin"
    }
}
```

---

## 🔍 **Metadata Schema**

### **📋 Standard Metadata Keys**
```sql
-- Direction metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'direction', 'outgoing', 20260122123456);

-- Role metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'role', 'human', 20260122123456);

-- Channel metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'channel', 'channel_123', 20260122123456);

-- Department metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'department', 'department_456', 20260122123456);

-- Language metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'language', 'en', 20260122123456);

-- Thread ID metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'thread_id', 'thread_123_session_456', 20260122123456);

-- Session ID metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'session_id', 'session_456', 20260122123456);

-- Tab ID metadata
INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis)
VALUES (123, 'tab_id', 'operator_tab_789', 20260122123456);
```

---

## 🎯 **Graph Query Patterns**

### **📋 Thread Graph Query**
```sql
-- Get all messages in a thread with actor information
SELECT 
    ce.content_event_id,
    ce.content_id,
    ce.actor_id,
    a.actor_type,
    a.actor_source_id,
    a.actor_source_table,
    ce.event_type,
    ce.event_data,
    ce.created_ymdhis,
    em1.metadata_value AS direction,
    em2.metadata_value AS role,
    em3.metadata_value AS thread_id
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em1 ON ce.content_event_id = em1.event_id AND em1.metadata_key = 'direction'
LEFT JOIN lupo_event_metadata em2 ON ce.content_event_id = em2.event_id AND em2.metadata_key = 'role'
LEFT JOIN lupo_event_metadata em3 ON ce.content_event_id = em3.event_id AND em3.metadata_key = 'thread_id'
WHERE ce.event_type IN ('message_sent', 'message_received', 'message_displayed')
AND em3.metadata_value = 'thread_123_session_456'
ORDER BY ce.created_ymdhis ASC;
```

### **📋 Actor Interaction Graph Query**
```sql
-- Get actor interaction graph for truth analysis
SELECT 
    ce1.actor_id AS actor1_id,
    a1.actor_type AS actor1_type,
    ce2.actor_id AS actor2_id,
    a2.actor_type AS actor2_type,
    em3.metadata_value AS thread_id,
    COUNT(*) AS interaction_count,
    MIN(ce1.created_ymdhis) AS first_interaction,
    MAX(ce1.created_ymdhis) AS last_interaction
FROM lupo_content_events ce1
JOIN lupo_content_events ce2 ON 
    JSON_EXTRACT(ce1.event_data, '$.thread_id') = JSON_EXTRACT(ce2.event_data, '$.thread_id')
    AND ce1.actor_id != ce2.actor_id
    AND ce1.created_ymdhis < ce2.created_ymdhis
    AND TIMESTAMPDIFF(SECOND, STR_TO_DATE(ce1.created_ymdhis, '%Y%m%d%H%i%s'), STR_TO_DATE(ce2.created_ymdhis, '%Y%m%d%H%i%s')) < 300
LEFT JOIN lupo_actors a1 ON ce1.actor_id = a1.actor_id
LEFT JOIN lupo_actors a2 ON ce2.actor_id = a2.actor_id
LEFT JOIN lupo_event_metadata em3 ON ce1.content_event_id = em3.event_id AND em3.metadata_key = 'thread_id'
WHERE ce1.event_type IN ('message_sent', 'message_received')
AND ce2.event_type IN ('message_sent', 'message_received')
GROUP BY ce1.actor_id, ce2.actor_id, em3.metadata_value
ORDER BY interaction_count DESC;
```

### **📋 Conversation Flow Graph Query**
```sql
-- Get conversation flow with direction indicators
SELECT 
    ce.content_event_id,
    ce.actor_id,
    a.actor_type,
    em1.metadata_value AS direction,
    em2.metadata_value AS role,
    em3.metadata_value AS thread_id,
    JSON_EXTRACT(ce.event_data, '$.message') AS message_text,
    ce.created_ymdhis,
    CASE 
        WHEN em1.metadata_value = 'outgoing' THEN '→'
        WHEN em1.metadata_value = 'incoming' THEN '←'
        ELSE '↔'
    END AS flow_arrow
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em1 ON ce.content_event_id = em1.event_id AND em1.metadata_key = 'direction'
LEFT JOIN lupo_event_metadata em2 ON ce.content_event_id = em2.event_id AND em2.metadata_key = 'role'
LEFT JOIN lupo_event_metadata em3 ON ce.content_event_id = em3.event_id AND em3.metadata_key = 'thread_id'
WHERE ce.event_type IN ('message_sent', 'message_received')
AND em3.metadata_value = 'thread_123_session_456'
ORDER BY ce.created_ymdhis ASC;
```

---

## 🔧 **Schema Optimization**

### **📋 Recommended Indexes**
```sql
-- Composite indexes for dialogue queries
CREATE INDEX idx_dialogue_thread_actor ON lupo_content_events (JSON_EXTRACT(event_data, '$.thread_id'), actor_id);
CREATE INDEX idx_dialogue_thread_time ON lupo_content_events (JSON_EXTRACT(event_data, '$.thread_id'), created_ymdhis);
CREATE INDEX idx_dialogue_actor_time ON lupo_content_events (actor_id, created_ymdhis);
CREATE INDEX idx_dialogue_type_time ON lupo_content_events (event_type, created_ymdhis);

-- Metadata indexes
CREATE INDEX idx_metadata_thread ON lupo_event_metadata (metadata_key, metadata_value) WHERE metadata_key = 'thread_id';
CREATE INDEX idx_metadata_direction ON lupo_event_metadata (metadata_key, metadata_value) WHERE metadata_key = 'direction';
CREATE INDEX idx_metadata_role ON lupo_event_metadata (metadata_key, metadata_value) WHERE metadata_key = 'role';

-- Actor indexes
CREATE INDEX idx_actor_type_source ON lupo_actors (actor_type, actor_source_id, actor_source_table);
CREATE INDEX idx_actor_created ON lupo_actors (created_ymdhis);

-- Session indexes
CREATE INDEX idx_session_actor ON lupo_session_events (session_id, actor_id);
CREATE INDEX idx_session_time ON lupo_session_events (session_id, created_ymdhis);

-- Tab indexes
CREATE INDEX idx_tab_actor ON lupo_tab_events (tab_id, actor_id);
CREATE INDEX idx_tab_time ON lupo_tab_events (tab_id, created_ymdhis);
```

---

## 🎯 **Graph Traversal Patterns**

### **📋 Breadth-First Traversal (Thread Level)**
```sql
-- Get all messages in a thread in chronological order
WITH RECURSIVE thread_messages AS (
    SELECT 
        ce.content_event_id,
        ce.content_id,
        ce.actor_id,
        ce.event_type,
        ce.event_data,
        ce.created_ymdhis,
        1 AS level
    FROM lupo_content_events ce
    WHERE JSON_EXTRACT(ce.event_data, '$.thread_id') = 'thread_123_session_456'
    AND ce.event_type IN ('message_sent', 'message_received')
    
    UNION ALL
    
    SELECT 
        ce.content_event_id,
        ce.content_id,
        ce.actor_id,
        ce.event_type,
        ce.event_data,
        ce.created_ymdhis,
        tm.level + 1
    FROM lupo_content_events ce
    JOIN thread_messages tm ON JSON_EXTRACT(ce.event_data, '$.thread_id') = JSON_EXTRACT(tm.event_data, '$.thread_id')
    WHERE ce.created_ymdhis > tm.created_ymdhis
    AND ce.event_type IN ('message_sent', 'message_received')
)
SELECT * FROM thread_messages ORDER BY created_ymdhis ASC;
```

### **📋 Depth-First Traversal (Actor Level)**
```sql
-- Get all conversations for an actor with depth information
WITH RECURSIVE actor_conversations AS (
    SELECT 
        JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
        ce.content_id,
        ce.created_ymdhis,
        1 AS depth,
        ce.created_ymdhis AS root_time
    FROM lupo_content_events ce
    WHERE ce.actor_id = 123
    AND ce.event_type IN ('message_sent', 'message_received')
    
    UNION ALL
    
    SELECT 
        ac.thread_id,
        ce.content_id,
        ce.created_ymdhis,
        ac.depth + 1,
        ac.root_time
    FROM lupo_content_events ce
    JOIN actor_conversations ac ON JSON_EXTRACT(ce.event_data, '$.thread_id') = ac.thread_id
    WHERE ce.created_ymdhis > ac.created_ymdhis
    AND ce.event_type IN ('message_sent', 'message_received')
)
SELECT 
    thread_id,
    depth,
    COUNT(*) AS message_count,
    MIN(created_ymdhis) AS first_message,
    MAX(created_ymdhis) AS last_message
FROM actor_conversations
GROUP BY thread_id, depth
ORDER BY depth, first_message;
```

---

## 🚀 **Performance Considerations**

### **✅ Query Optimization**
1. **Use appropriate indexes** for JSON path queries
2. **Limit result sets** with pagination
3. **Filter by time ranges** to reduce dataset size
4. **Use CTEs** for complex graph traversals
5. **Materialize frequently used subqueries**

### **✅ Data Partitioning**
1. **Time-based partitioning** for large event tables
2. **Actor-based partitioning** for high-volume actors
3. **Thread-based partitioning** for active conversations

### **✅ Caching Strategy**
1. **Cache thread metadata** for frequently accessed conversations
2. **Cache actor information** for active participants
3. **Cache query results** for analytics dashboards

---

**Status**: ✅ **DIALOGUE GRAPH SCHEMA COMPLETE** - Comprehensive schema for multi-actor dialogue layer with graph traversal patterns and optimization strategies.

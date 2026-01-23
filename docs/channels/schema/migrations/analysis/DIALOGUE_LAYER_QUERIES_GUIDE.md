# 📋 **Querying the Multi-Actor Dialogue Layer**

## 🎯 **Accessing Dialogue Data for Truth, Analytics, and Narrative**

The multi-actor dialogue layer is built on top of the TOON analytics system, providing rich semantic access to conversation data through multiple query patterns.

---

## 🔍 **1. Basic Dialogue Queries**

### **📋 Get All Messages in a Conversation**
```sql
-- Get all messages in a specific conversation thread
SELECT 
    ce.content_id,
    ce.actor_id,
    ce.event_type,
    ce.event_data,
    ce.created_ymdhis,
    em1.metadata_value AS direction,
    em2.metadata_value AS role,
    a.actor_type,
    a.actor_source_id,
    a.actor_source_table
FROM lupo_content_events ce
LEFT JOIN lupo_event_metadata em1 ON ce.content_event_id = em1.event_id AND em1.metadata_key = 'direction'
LEFT JOIN lupo_event_metadata em2 ON ce.content_event_id = em2.event_id AND em2.metadata_key = 'role'
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
WHERE ce.event_type IN ('message_sent', 'message_received', 'message_displayed')
AND JSON_EXTRACT(ce.event_data, '$.thread_id') = 'thread_123_session_456'
ORDER BY ce.created_ymdhis ASC;
```

### **📋 Get Conversation Participants**
```sql
-- Get all actors in a conversation
SELECT DISTINCT
    ce.actor_id,
    a.actor_type,
    a.actor_source_id,
    a.actor_source_table,
    em.metadata_value AS role,
    COUNT(*) AS message_count
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
WHERE ce.event_type IN ('message_sent', 'message_received')
AND JSON_EXTRACT(ce.event_data, '$.thread_id') = 'thread_123_session_456'
GROUP BY ce.actor_id, a.actor_type, em.metadata_value
ORDER BY ce.created_ymdhis ASC;
```

---

## 🔍 **2. Actor-Centric Queries**

### **📋 Get All Conversations for an Actor**
```sql
-- Get all conversations involving a specific actor
SELECT DISTINCT
    JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
    JSON_EXTRACT(ce.event_data, '$.channel') AS channel_id,
    JSON_EXTRACT(ce.event_data, '$.department') AS department_id,
    MIN(ce.created_ymdhis) AS conversation_start,
    MAX(ce.created_ymdhis) AS conversation_end,
    COUNT(*) AS message_count
FROM lupo_content_events ce
WHERE ce.actor_id = 123
AND ce.event_type IN ('message_sent', 'message_received')
GROUP BY JSON_EXTRACT(ce.event_data, '$.thread_id'), JSON_EXTRACT(ce.event_data, '$.channel')
ORDER BY conversation_start DESC;
```

### **📋 Get Actor's Role in Different Conversations**
```sql
-- Get actor's role across different conversations
SELECT 
    JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
    em.metadata_value AS role,
    JSON_EXTRACT(ce.event_data, '$.channel') AS channel_id,
    JSON_EXTRACT(ce.event_data, '$.department') AS department_id,
    ce.created_ymdhis AS first_message_time
FROM lupo_content_events ce
LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
WHERE ce.actor_id = 123
AND ce.event_type = 'message_sent'
GROUP BY thread_id, role, channel_id, department_id
ORDER BY first_message_time DESC;
```

---

## 🔍 **3. Analytics Queries**

### **📋 Message Volume by Actor Type**
```sql
-- Get message volume by actor type over time period
SELECT 
    a.actor_type,
    em.metadata_value AS role,
    COUNT(*) AS message_count,
    DATE_FORMAT(STR_TO_DATE(ce.created_ymdhis, '%Y%m%d%H%i%s'), '%Y-%m-%d') AS message_date
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
WHERE ce.event_type IN ('message_sent', 'message_received')
AND ce.created_ymdhis BETWEEN '20260101000000' AND '20260131235959'
GROUP BY a.actor_type, em.metadata_value, message_date
ORDER BY message_date DESC, message_count DESC;
```

### **📋 Conversation Duration Analytics**
```sql
-- Get conversation duration analytics
SELECT 
    thread_id,
    MIN(created_ymdhis) AS conversation_start,
    MAX(created_ymdhis) AS conversation_end,
    TIMESTAMPDIFF(SECOND, 
        STR_TO_DATE(MIN(created_ymdhis), '%Y%m%d%H%i%s'),
        STR_TO_DATE(MAX(created_ymdhis), '%Y%m%d%H%i%s')
    ) AS duration_seconds,
    COUNT(*) AS message_count,
    COUNT(DISTINCT actor_id) AS participant_count
FROM (
    SELECT 
        JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
        ce.created_ymdhis,
        ce.actor_id
    FROM lupo_content_events ce
    WHERE ce.event_type IN ('message_sent', 'message_received')
    AND JSON_EXTRACT(ce.event_data, '$.thread_id') IS NOT NULL
) conversation_data
GROUP BY thread_id
ORDER BY duration_seconds DESC;
```

### **📋 Response Time Analytics**
```sql
-- Get average response time between messages
SELECT 
    AVG(response_time_seconds) AS avg_response_time,
    MIN(response_time_seconds) AS min_response_time,
    MAX(response_time_seconds) AS max_response_time,
    COUNT(*) AS response_count
FROM (
    SELECT 
        TIMESTAMPDIFF(SECOND,
            LAG(STR_TO_DATE(ce.created_ymdhis, '%Y%m%d%H%i%s')) OVER (PARTITION BY JSON_EXTRACT(ce.event_data, '$.thread_id') ORDER BY ce.created_ymdhis),
            STR_TO_DATE(ce.created_ymdhis, '%Y%m%d%H%i%s')
        ) AS response_time_seconds
    FROM lupo_content_events ce
    WHERE ce.event_type IN ('message_sent', 'message_received')
    AND JSON_EXTRACT(ce.event_data, '$.thread_id') IS NOT NULL
) response_data
WHERE response_time_seconds IS NOT NULL
AND response_time_seconds > 0;
```

---

## 🔍 **4. Truth Graph Queries**

### **📋 Get Actor Truth Relationships**
```sql
-- Get truth relationships between actors in conversations
SELECT 
    ce1.actor_id AS actor1_id,
    a1.actor_type AS actor1_type,
    ce2.actor_id AS actor2_id,
    a2.actor_type AS actor2_type,
    JSON_EXTRACT(ce1.event_data, '$.thread_id') AS thread_id,
    COUNT(*) AS interaction_count,
    MIN(ce1.created_ymdhis) AS first_interaction,
    MAX(ce1.created_ymdhis) AS last_interaction
FROM lupo_content_events ce1
JOIN lupo_content_events ce2 ON 
    JSON_EXTRACT(ce1.event_data, '$.thread_id') = JSON_EXTRACT(ce2.event_data, '$.thread_id')
    AND ce1.actor_id != ce2.actor_id
    AND ce1.created_ymdhis < ce2.created_ymdhis
    AND TIMESTAMPDIFF(SECOND, STR_TO_DATE(ce1.created_ymdhis, '%Y%m%d%H%i%s'), STR_TO_DATE(ce2.created_ymdhis, '%Y%m%d%H%i%s')) < 300 -- Within 5 minutes
LEFT JOIN lupo_actors a1 ON ce1.actor_id = a1.actor_id
LEFT JOIN lupo_actors a2 ON ce2.actor_id = a2.actor_id
WHERE ce1.event_type IN ('message_sent', 'message_received')
AND ce2.event_type IN ('message_sent', 'message_received')
GROUP BY ce1.actor_id, ce2.actor_id, JSON_EXTRACT(ce1.event_data, '$.thread_id')
ORDER BY interaction_count DESC;
```

### **📋 Get Actor Influence Patterns**
```sql
-- Get actor influence patterns based on message flow
SELECT 
    ce.actor_id,
    a.actor_type,
    COUNT(*) AS messages_sent,
    COUNT(DISTINCT JSON_EXTRACT(ce.event_data, '$.thread_id')) AS conversations_participated,
    COUNT(DISTINCT CASE WHEN em.metadata_value = 'human' THEN JSON_EXTRACT(ce.event_data, '$.thread_id') END) AS human_conversations,
    COUNT(DISTINCT CASE WHEN em.metadata_value = 'legacy_user' THEN JSON_EXTRACT(ce.event_data, '$.thread_id') END) AS user_conversations
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
WHERE ce.event_type = 'message_sent'
GROUP BY ce.actor_id, a.actor_type
ORDER BY messages_sent DESC;
```

---

## 🔍 **5. Narrative Queries**

### **📋 Get Conversation Narrative Flow**
```sql
-- Get narrative flow of a conversation
SELECT 
    ce.created_ymdhis,
    ce.actor_id,
    a.actor_type,
    em.metadata_value AS role,
    JSON_EXTRACT(ce.event_data, '$.direction') AS direction,
    JSON_EXTRACT(ce.event_data, '$.message') AS message_text,
    JSON_EXTRACT(ce.event_data, '$.channel') AS channel_id,
    JSON_EXTRACT(ce.event_data, '$.department') AS department_id,
    CASE 
        WHEN JSON_EXTRACT(ce.event_data, '$.direction') = 'outgoing' THEN '→'
        WHEN JSON_EXTRACT(ce.event_data, '$.direction') = 'incoming' THEN '←'
        ELSE '↔'
    END AS flow_arrow
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
WHERE ce.event_type IN ('message_sent', 'message_received')
AND JSON_EXTRACT(ce.event_data, '$.thread_id') = 'thread_123_session_456'
ORDER BY ce.created_ymdhis ASC;
```

### **📋 Get Actor Conversation Patterns**
```sql
-- Get conversation patterns for specific actor
SELECT 
    thread_id,
    role,
    channel_id,
    department_id,
    message_count,
    first_message_time,
    last_message_time,
    CASE 
        WHEN message_count = 1 THEN 'single_message'
        WHEN message_count BETWEEN 2 AND 5 THEN 'short_conversation'
        WHEN message_count BETWEEN 6 AND 20 THEN 'medium_conversation'
        ELSE 'long_conversation'
    END AS conversation_type
FROM (
    SELECT 
        JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
        em.metadata_value AS role,
        JSON_EXTRACT(ce.event_data, '$.channel') AS channel_id,
        JSON_EXTRACT(ce.event_data, '$.department') AS department_id,
        COUNT(*) AS message_count,
        MIN(ce.created_ymdhis) AS first_message_time,
        MAX(ce.created_ymdhis) AS last_message_time
    FROM lupo_content_events ce
    LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
    WHERE ce.actor_id = 123
    AND ce.event_type IN ('message_sent', 'message_received')
    GROUP BY JSON_EXTRACT(ce.event_data, '$.thread_id'), em.metadata_value, JSON_EXTRACT(ce.event_data, '$.channel'), JSON_EXTRACT(ce.event_data, '$.department')
) actor_conversations
ORDER BY message_count DESC;
```

---

## 🔍 **6. Session Context Queries**

### **📋 Get Session-Based Dialogue Context**
```sql
-- Get all dialogue events within a session
SELECT 
    se.session_id,
    se.event_type AS session_event_type,
    se.event_data AS session_event_data,
    ce.content_id,
    ce.actor_id,
    ce.event_type AS content_event_type,
    ce.event_data AS content_event_data,
    se.created_ymdhis AS session_time,
    ce.created_ymdhis AS content_time
FROM lupo_session_events se
LEFT JOIN lupo_content_events ce ON se.session_id = JSON_EXTRACT(ce.event_data, '$.session_id')
WHERE se.session_id = 'session_456'
ORDER BY se.created_ymdhis ASC, ce.created_ymdhis ASC;
```

### **📋 Get Tab-Based Dialogue Context**
```sql
-- Get dialogue events within specific tabs
SELECT 
    te.tab_id,
    te.event_type AS tab_event_type,
    te.event_data AS tab_event_data,
    ce.content_id,
    ce.actor_id,
    ce.event_type AS content_event_type,
    ce.event_data AS content_event_data,
    te.created_ymdhis AS tab_time,
    ce.created_ymdhis AS content_time
FROM lupo_tab_events te
LEFT JOIN lupo_content_events ce ON te.tab_id = JSON_EXTRACT(ce.event_data, '$.tab_id')
WHERE te.tab_id = 'operator_tab_789'
ORDER BY te.created_ymdhis ASC, ce.created_ymdhis ASC;
```

---

## 🔍 **7. World Context Queries**

### **📋 Get World-Level Dialogue Events**
```sql
-- Get world-level dialogue events
SELECT 
    we.world_id,
    we.event_type,
    we.event_data,
    we.actor_id,
    a.actor_type,
    we.created_ymdhis
FROM lupo_world_events we
LEFT JOIN lupo_actors a ON we.actor_id = a.actor_id
WHERE we.event_type IN ('dialogue_world_event', 'conversation_world_event', 'conversation_context')
ORDER BY we.created_ymdhis DESC;
```

---

## 🔍 **8. PHP Helper Functions**

### **📋 Dialogue Query Helper Class**
```php
<?php

class DialogueQueryHelper {
    
    /**
     * Get conversation thread messages
     */
    public function getConversationMessages($thread_id, $limit = 100) {
        global $mydatabase;
        
        $query = "
            SELECT 
                ce.content_id,
                ce.actor_id,
                ce.event_type,
                ce.event_data,
                ce.created_ymdhis,
                em1.metadata_value AS direction,
                em2.metadata_value AS role,
                a.actor_type
            FROM lupo_content_events ce
            LEFT JOIN lupo_event_metadata em1 ON ce.content_event_id = em1.event_id AND em1.metadata_key = 'direction'
            LEFT JOIN lupo_event_metadata em2 ON ce.content_event_id = em2.event_id AND em2.metadata_key = 'role'
            LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
            WHERE ce.event_type IN ('message_sent', 'message_received', 'message_displayed')
            AND JSON_EXTRACT(ce.event_data, '$.thread_id') = ?
            ORDER BY ce.created_ymdhis ASC
            LIMIT ?
        ";
        
        return $mydatabase->query($query, [$thread_id, $limit]);
    }
    
    /**
     * Get actor conversations
     */
    public function getActorConversations($actor_id, $limit = 50) {
        global $mydatabase;
        
        $query = "
            SELECT DISTINCT
                JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
                JSON_EXTRACT(ce.event_data, '$.channel') AS channel_id,
                MIN(ce.created_ymdhis) AS conversation_start,
                MAX(ce.created_ymdhis) AS conversation_end,
                COUNT(*) AS message_count
            FROM lupo_content_events ce
            WHERE ce.actor_id = ?
            AND ce.event_type IN ('message_sent', 'message_received')
            GROUP BY JSON_EXTRACT(ce.event_data, '$.thread_id'), JSON_EXTRACT(ce.event_data, '$.channel')
            ORDER BY conversation_start DESC
            LIMIT ?
        ";
        
        return $mydatabase->query($query, [$actor_id, $limit]);
    }
    
    /**
     * Get conversation analytics
     */
    public function getConversationAnalytics($start_date = null, $end_date = null) {
        global $mydatabase;
        
        $where_clause = "";
        $params = [];
        
        if ($start_date) {
            $where_clause .= " AND ce.created_ymdhis >= ?";
            $params[] = date('YmdHis', strtotime($start_date));
        }
        
        if ($end_date) {
            $where_clause .= " AND ce.created_ymdhis <= ?";
            $params[] = date('YmdHis', strtotime($end_date));
        }
        
        $query = "
            SELECT 
                a.actor_type,
                em.metadata_value AS role,
                COUNT(*) AS message_count,
                DATE_FORMAT(STR_TO_DATE(ce.created_ymdhis, '%Y%m%d%H%i%s'), '%Y-%m-%d') AS message_date
            FROM lupo_content_events ce
            LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
            LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
            WHERE ce.event_type IN ('message_sent', 'message_received')
            $where_clause
            GROUP BY a.actor_type, em.metadata_value, message_date
            ORDER BY message_date DESC, message_count DESC
        ";
        
        return $mydatabase->query($query, $params);
    }
    
    /**
     * Get truth graph relationships
     */
    public function getTruthGraphRelationships($thread_id = null) {
        global $mydatabase;
        
        $where_clause = "";
        $params = [];
        
        if ($thread_id) {
            $where_clause = " AND JSON_EXTRACT(ce1.event_data, '$.thread_id') = ?";
            $params[] = $thread_id;
        }
        
        $query = "
            SELECT 
                ce1.actor_id AS actor1_id,
                a1.actor_type AS actor1_type,
                ce2.actor_id AS actor2_id,
                a2.actor_type AS actor2_type,
                JSON_EXTRACT(ce1.event_data, '$.thread_id') AS thread_id,
                COUNT(*) AS interaction_count
            FROM lupo_content_events ce1
            JOIN lupo_content_events ce2 ON 
                JSON_EXTRACT(ce1.event_data, '$.thread_id') = JSON_EXTRACT(ce2.event_data, '$.thread_id')
                AND ce1.actor_id != ce2.actor_id
                AND ce1.created_ymdhis < ce2.created_ymdhis
                AND TIMESTAMPDIFF(SECOND, STR_TO_DATE(ce1.created_ymdhis, '%Y%m%d%H%i%s'), STR_TO_DATE(ce2.created_ymdhis, '%Y%m%d%H%i%s')) < 300
            LEFT JOIN lupo_actors a1 ON ce1.actor_id = a1.actor_id
            LEFT JOIN lupo_actors a2 ON ce2.actor_id = a2.actor_id
            WHERE ce1.event_type IN ('message_sent', 'message_received')
            AND ce2.event_type IN ('message_sent', 'message_received')
            $where_clause
            GROUP BY ce1.actor_id, ce2.actor_id, JSON_EXTRACT(ce1.event_data, '$.thread_id')
            ORDER BY interaction_count DESC
        ";
        
        return $mydatabase->query($query, $params);
    }
}

// Usage example:
// $dialogue = new DialogueQueryHelper();
// $messages = $dialogue->getConversationMessages('thread_123_session_456');
// $conversations = $dialogue->getActorConversations(123);
// $analytics = $dialogue->getConversationAnalytics('2026-01-01', '2026-01-31');
// $truth_graph = $dialogue->getTruthGraphRelationships();

?>
```

---

## 🔍 **9. Real-time Query Examples**

### **📋 Get Recent Dialogue Activity**
```sql
-- Get recent dialogue activity across all conversations
SELECT 
    ce.content_id,
    ce.actor_id,
    a.actor_type,
    em.metadata_value AS role,
    JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
    JSON_EXTRACT(ce.event_data, '$.message') AS message_text,
    ce.created_ymdhis
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
WHERE ce.event_type IN ('message_sent', 'message_received')
AND ce.created_ymdhis >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 HOUR), '%Y%m%d%H%i%s')
ORDER BY ce.created_ymdhis DESC
LIMIT 50;
```

### **📋 Get Active Conversations**
```sql
-- Get currently active conversations
SELECT 
    JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
    JSON_EXTRACT(ce.event_data, '$.channel') AS channel_id,
    MAX(ce.created_ymdhis) AS last_activity,
    COUNT(*) AS message_count,
    COUNT(DISTINCT ce.actor_id) AS participant_count
FROM lupo_content_events ce
WHERE ce.event_type IN ('message_sent', 'message_received')
AND ce.created_ymdhis >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 30 MINUTE), '%Y%m%d%H%i%s')
GROUP BY JSON_EXTRACT(ce.event_data, '$.thread_id'), JSON_EXTRACT(ce.event_data, '$.channel')
HAVING message_count >= 2
ORDER BY last_activity DESC;
```

---

## 🎯 **Query Best Practices**

### **✅ Performance Optimization**
1. **Use indexes** on `created_ymdhis`, `actor_id`, `event_type`
2. **Limit results** with appropriate LIMIT clauses
3. **Filter by date ranges** to reduce dataset size
4. **Use JSON_EXTRACT** efficiently with proper indexing

### **✅ Data Integrity**
1. **Always validate** thread_id and actor_id existence
2. **Handle NULL values** in metadata joins
3. **Use COALESCE** for default values
4. **Validate JSON paths** before extraction

### **✅ Semantic Accuracy**
1. **Group by thread_id** for conversation-level analysis
2. **Consider direction** (incoming/outgoing) for flow analysis
3. **Include role metadata** for actor context
4. **Use actor_type** for participant classification

---

**Status**: ✅ **DIALOGUE QUERY DOCUMENTATION COMPLETE** - Comprehensive query patterns for truth, analytics, and narrative access to the multi-actor dialogue layer.

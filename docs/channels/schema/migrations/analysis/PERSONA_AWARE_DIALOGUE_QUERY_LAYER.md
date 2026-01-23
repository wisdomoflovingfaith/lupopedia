# 📋 **Persona-Aware Dialogue Query Layer**

## 🎯 **Multi-Actor Dialogue Layer with Persona Support**

The persona-aware dialogue query layer extends the multi-actor dialogue system to support persona actors, their characteristics, and their unique interaction patterns within conversations.

---

## 🗄️ **Extended Schema for Persona Support**

### **📋 lupo_actors (Extended for Persona Support)**
```sql
CREATE TABLE lupo_actors (
    actor_id BIGINT NOT NULL AUTO_INCREMENT,
    actor_type VARCHAR(50) NOT NULL,
    actor_name VARCHAR(255),
    actor_description TEXT,
    actor_persona_type VARCHAR(100),
    actor_traits JSON,
    actor_preferences JSON,
    actor_capabilities JSON,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    deleted_ymdhis BIGINT,
    actor_source_id BIGINT,
    actor_source_table VARCHAR(100),
    PRIMARY KEY (actor_id),
    INDEX idx_actor_type (actor_type),
    INDEX idx_persona_type (actor_persona_type),
    INDEX idx_source (actor_source_id, actor_source_table),
    INDEX idx_created_ymdhis (created_ymdhis)
);
```

### **📋 lupo_persona_profiles (Persona Definition Table)**
```sql
CREATE TABLE lupo_persona_profiles (
    persona_id BIGINT NOT NULL AUTO_INCREMENT,
    persona_name VARCHAR(255) NOT NULL,
    persona_type VARCHAR(100) NOT NULL,
    persona_description TEXT,
    persona_traits JSON,
    persona_preferences JSON,
    persona_capabilities JSON,
    persona_voice_style VARCHAR(100),
    persona_interaction_style VARCHAR(100),
    persona_emotional_profile JSON,
    persona_knowledge_domains JSON,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    is_active TINYINT NOT NULL DEFAULT 1,
    PRIMARY KEY (persona_id),
    INDEX idx_persona_type (persona_type),
    INDEX idx_persona_name (persona_name),
    INDEX idx_is_active (is_active)
);
```

### **📋 lupo_persona_dialogue_patterns (Persona Dialogue Patterns)**
```sql
CREATE TABLE lupo_persona_dialogue_patterns (
    pattern_id BIGINT NOT NULL AUTO_INCREMENT,
    persona_id BIGINT NOT NULL,
    pattern_type VARCHAR(100) NOT NULL,
    pattern_name VARCHAR(255) NOT NULL,
    pattern_triggers JSON,
    pattern_responses JSON,
    pattern_context JSON,
    pattern_frequency DECIMAL(5,2),
    pattern_confidence DECIMAL(5,2),
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (pattern_id),
    INDEX idx_persona_id (persona_id),
    INDEX idx_pattern_type (pattern_type),
    INDEX idx_pattern_name (pattern_name)
);
```

### **📋 lupo_actor_persona_relationships (Actor-Persona Relationships)**
```sql
CREATE TABLE lupo_actor_persona_relationships (
    relationship_id BIGINT NOT NULL AUTO_INCREMENT,
    actor_id BIGINT NOT NULL,
    persona_id BIGINT NOT NULL,
    relationship_type VARCHAR(100) NOT NULL,
    relationship_strength DECIMAL(5,2),
    relationship_context JSON,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    PRIMARY KEY (relationship_id),
    INDEX idx_actor_id (actor_id),
    INDEX idx_persona_id (persona_id),
    INDEX idx_relationship_type (relationship_type)
);
```

---

## 🎭 **Persona Data Structures**

### **📋 Persona Actor Structure**
```json
{
    "actor_id": 456,
    "actor_type": "persona",
    "actor_name": "Luna",
    "actor_persona_type": "emotional_support",
    "actor_traits": {
        "empathy": 0.9,
        "creativity": 0.8,
        "patience": 0.95,
        "assertiveness": 0.3,
        "humor": 0.6
    },
    "actor_preferences": {
        "communication_style": "gentle",
        "response_length": "medium",
        "emotional_tone": "supportive",
        "topics": ["emotional_support", "wellness", "relationships"]
    },
    "actor_capabilities": {
        "emotional_analysis": true,
        "creative_suggestions": true,
        "problem_solving": true,
        "technical_support": false
    }
}
```

### **📋 Persona Profile Structure**
```json
{
    "persona_id": 789,
    "persona_name": "Luna",
    "persona_type": "emotional_support",
    "persona_description": "A gentle, empathetic persona focused on emotional support and wellness",
    "persona_traits": {
        "empathy": 0.9,
        "creativity": 0.8,
        "patience": 0.95,
        "assertiveness": 0.3,
        "humor": 0.6,
        "intuition": 0.85,
        "wisdom": 0.7
    },
    "persona_preferences": {
        "communication_style": "gentle",
        "response_length": "medium",
        "emotional_tone": "supportive",
        "topics": ["emotional_support", "wellness", "relationships"],
        "avoidance_topics": ["technical_support", "aggressive_confrontation"]
    },
    "persona_capabilities": {
        "emotional_analysis": true,
        "creative_suggestions": true,
        "problem_solving": true,
        "technical_support": false,
        "medical_advice": false,
        "legal_advice": false
    },
    "persona_voice_style": "soft_gentle",
    "persona_interaction_style": "supportive_empathetic",
    "persona_emotional_profile": {
        "primary_emotion": "calm",
        "emotional_range": ["calm", "concerned", "encouraging", "gentle"],
        "emotional_triggers": ["distress", "anxiety", "sadness", "conflict"]
    },
    "persona_knowledge_domains": {
        "emotional_intelligence": 0.9,
        "psychology": 0.7,
        "wellness": 0.8,
        "relationships": 0.8,
        "creativity": 0.7,
        "philosophy": 0.6
    }
}
```

### **📋 Persona Dialogue Pattern Structure**
```json
{
    "pattern_id": 123,
    "persona_id": 789,
    "pattern_type": "emotional_support",
    "pattern_name": "comforting_response",
    "pattern_triggers": {
        "keywords": ["sad", "upset", "hurt", "distressed"],
        "emotions": ["sadness", "anxiety", "fear", "anger"],
        "situations": ["relationship_problems", "stress", "burnout"]
    },
    "pattern_responses": {
        "templates": [
            "I hear that you're feeling {emotion}. That sounds really difficult.",
            "It's completely understandable to feel {emotion} in this situation.",
            "You're showing a lot of strength by sharing this with me."
        ],
        "follow_up_questions": [
            "Can you tell me more about what's been happening?",
            "How has this been affecting you lately?",
            "What would feel most helpful right now?"
        ]
    },
    "pattern_context": {
        "appropriate_situations": ["emotional_distress", "relationship_issues", "stress"],
        "inappropriate_situations": ["technical_support", "medical_emergencies"],
        "time_sensitivity": "immediate",
        "emotional_intensity": "high"
    },
    "pattern_frequency": 0.85,
    "pattern_confidence": 0.92
}
```

---

## 🔍 **Persona-Aware Query Patterns**

### **📋 Get Persona-Aware Conversation Messages**
```sql
-- Get conversation messages with persona information
SELECT 
    ce.content_event_id,
    ce.content_id,
    ce.actor_id,
    ce.event_type,
    ce.event_data,
    ce.created_ymdhis,
    a.actor_type,
    a.actor_name,
    a.actor_persona_type,
    a.actor_traits,
    em1.metadata_value AS direction,
    em2.metadata_value AS role,
    em3.metadata_value AS thread_id,
    CASE 
        WHEN a.actor_type = 'persona' THEN JSON_EXTRACT(a.actor_traits, '$.empathy')
        ELSE NULL
    END AS empathy_score,
    CASE 
        WHEN a.actor_type = 'persona' THEN JSON_EXTRACT(a.actor_preferences, '$.communication_style')
        ELSE NULL
    END AS communication_style
FROM lupo_content_events ce
LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em1 ON ce.content_event_id = em1.event_id AND em1.metadata_key = 'direction'
LEFT JOIN lupo_event_metadata em2 ON ce.content_event_id = em2.event_id AND em2.metadata_key = 'role'
LEFT JOIN lupo_event_metadata em3 ON ce.content_event_id = em3.event_id AND em3.metadata_key = 'thread_id'
WHERE ce.event_type IN ('message_sent', 'message_received', 'message_displayed')
AND em3.metadata_value = 'thread_123_session_456'
ORDER BY ce.created_ymdhis ASC;
```

### **📋 Get Persona Interaction Patterns**
```sql
-- Get persona interaction patterns in conversations
SELECT 
    a.actor_id,
    a.actor_name,
    a.actor_persona_type,
    JSON_EXTRACT(a.actor_traits, '$.empathy') AS empathy_score,
    JSON_EXTRACT(a.actor_traits, '$.creativity') AS creativity_score,
    JSON_EXTRACT(a.actor_preferences, '$.communication_style') AS communication_style,
    COUNT(*) AS message_count,
    COUNT(DISTINCT JSON_EXTRACT(ce.event_data, '$.thread_id')) AS conversation_count,
    AVG(
        CASE 
            WHEN JSON_EXTRACT(ce.event_data, '$.message_length') > 100 THEN 1.0
            WHEN JSON_EXTRACT(ce.event_data, '$.message_length') > 50 THEN 0.7
            ELSE 0.3
        END
    ) AS avg_message_length_score
FROM lupo_content_events ce
JOIN lupo_actors a ON ce.actor_id = a.actor_id
LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
WHERE a.actor_type = 'persona'
AND ce.event_type IN ('message_sent', 'message_received')
AND em.metadata_value = 'persona'
GROUP BY a.actor_id, a.actor_name, a.actor_persona_type
ORDER BY message_count DESC;
```

### **📋 Get Persona-User Interaction Analysis**
```sql
-- Get persona-user interaction analysis
SELECT 
    persona.actor_id AS persona_id,
    persona.actor_name AS persona_name,
    persona.actor_persona_type,
    user.actor_id AS user_id,
    COUNT(*) AS interaction_count,
    MIN(ce.created_ymdhis) AS first_interaction,
    MAX(ce.created_ymdhis) AS last_interaction,
    AVG(
        TIMESTAMPDIFF(SECOND, 
            STR_TO_DATE(ce.created_ymdhis, '%Y%m%d%H%i%s'),
            LAG(STR_TO_DATE(ce.created_ymdhis, '%Y%m%d%H%i%s')) OVER (
                PARTITION BY JSON_EXTRACT(ce.event_data, '$.thread_id'), 
                         CASE WHEN ce.actor_id = persona.actor_id THEN 1 ELSE 0 END
                ORDER BY ce.created_ymdhis
            )
        )
    ) AS avg_response_time_seconds,
    JSON_EXTRACT(persona.actor_traits, '$.empathy') AS empathy_score,
    JSON_EXTRACT(persona.actor_preferences, '$.communication_style') AS communication_style
FROM lupo_content_events ce
JOIN lupo_actors persona ON ce.actor_id = persona.actor_id AND persona.actor_type = 'persona'
JOIN lupo_actors user ON JSON_EXTRACT(ce.event_data, '$.thread_id') IN (
    SELECT JSON_EXTRACT(ce2.event_data, '$.thread_id')
    FROM lupo_content_events ce2
    WHERE ce2.actor_id != persona.actor_id
    AND ce2.event_type IN ('message_sent', 'message_received')
)
WHERE ce.event_type IN ('message_sent', 'message_received')
GROUP BY persona.actor_id, persona.actor_name, persona.actor_persona_type, user.actor_id
ORDER BY interaction_count DESC;
```

---

## 🎭 **Persona Characteristic Queries**

### **📋 Get Persona Emotional Profile Analysis**
```sql
-- Get persona emotional profile in conversations
SELECT 
    a.actor_id,
    a.actor_name,
    a.actor_persona_type,
    JSON_EXTRACT(a.actor_emotional_profile, '$.primary_emotion') AS primary_emotion,
    JSON_EXTRACT(a.actor_emotional_profile, '$.emotional_range') AS emotional_range,
    COUNT(*) AS total_messages,
    COUNT(CASE 
        WHEN JSON_EXTRACT(ce.event_data, '$.emotional_tone') = JSON_EXTRACT(a.actor_emotional_profile, '$.primary_emotion') 
        THEN 1 
        ELSE 0 
    END) AS primary_emotion_messages,
    COUNT(CASE 
        WHEN JSON_EXTRACT(ce.event_data, '$.emotional_tone') IN (
            SELECT value FROM JSON_TABLE(a.actor_emotional_profile, '$.emotional_range[*]') AS jt(value)
        ) THEN 1 
        ELSE 0 
    END) AS emotional_range_messages,
    AVG(
        CASE 
            WHEN JSON_EXTRACT(ce.event_data, '$.emotional_intensity') = 'high' THEN 1.0
            WHEN JSON_EXTRACT(ce.event_data, '$.emotional_intensity') = 'medium' THEN 0.7
            WHEN JSON_EXTRACT(ce.event_data, '$.emotional_intensity') = 'low' THEN 0.3
            ELSE 0.5
        END
    ) AS avg_emotional_intensity
FROM lupo_content_events ce
JOIN lupo_actors a ON ce.actor_id = a.actor_id
WHERE a.actor_type = 'persona'
AND ce.event_type IN ('message_sent', 'message_received')
GROUP BY a.actor_id, a.actor_name, a.actor_persona_type
ORDER BY total_messages DESC;
```

### **📋 Get Persona Knowledge Domain Analysis**
```sql
-- Get persona knowledge domain utilization
SELECT 
    a.actor_id,
    a.actor_name,
    a.actor_persona_type,
    domain_name,
    domain_score,
    COUNT(*) AS domain_utilization_count,
    AVG(
        CASE 
            WHEN JSON_EXTRACT(ce.event_data, '$.confidence_score') > 0.8 THEN 1.0
            WHEN JSON_EXTRACT(ce.event_data, '$.confidence_score') > 0.6 THEN 0.7
            WHEN JSON_EXTRACT(ce.event_data, '$.confidence_score') > 0.4 THEN 0.5
            ELSE 0.3
        END
    ) AS avg_confidence_score
FROM lupo_content_events ce
JOIN lupo_actors a ON ce.actor_id = a.actor_id
CROSS JOIN JSON_TABLE(
    a.actor_knowledge_domains,
    '$.*' COLUMNS(domain_name VARCHAR(100), domain_score DECIMAL(5,2))
) AS jt
WHERE a.actor_type = 'persona'
AND ce.event_type IN ('message_sent', 'message_received')
AND JSON_EXTRACT(ce.event_data, '$.knowledge_domain') = domain_name
GROUP BY a.actor_id, a.actor_name, a.actor_persona_type, domain_name, domain_score
ORDER BY domain_utilization_count DESC;
```

---

## 🔍 **Persona Dialogue Pattern Queries**

### **📋 Get Persona Dialogue Pattern Matching**
```sql
-- Get persona dialogue pattern matches
SELECT 
    pp.pattern_id,
    pp.pattern_name,
    pp.pattern_type,
    pp.pattern_frequency,
    pp.pattern_confidence,
    a.actor_id,
    a.actor_name,
    a.actor_persona_type,
    COUNT(*) AS pattern_usage_count,
    AVG(
        CASE 
            WHEN JSON_EXTRACT(ce.event_data, '$.pattern_match_score') > 0.8 THEN 1.0
            WHEN JSON_EXTRACT(ce.event_data, '$.pattern_match_score') > 0.6 THEN 0.7
            WHEN JSON_EXTRACT(ce.event_data, '$.pattern_match_score') > 0.4 THEN 0.5
            ELSE 0.3
        END
    ) AS avg_pattern_match_score
FROM lupo_content_events ce
JOIN lupo_actors a ON ce.actor_id = a.actor_id
JOIN lupo_persona_dialogue_patterns pp ON 
    JSON_EXTRACT(ce.event_data, '$.pattern_id') = pp.pattern_id
WHERE a.actor_type = 'persona'
AND ce.event_type = 'persona_pattern_match'
GROUP BY pp.pattern_id, pp.pattern_name, pp.pattern_type, pp.pattern_frequency, pp.pattern_confidence, a.actor_id, a.actor_name, a.actor_persona_type
ORDER BY pattern_usage_count DESC;
```

### **📋 Get Persona Response Style Analysis**
```sql
-- Get persona response style analysis
SELECT 
    a.actor_id,
    a.actor_name,
    a.actor_persona_type,
    JSON_EXTRACT(a.actor_preferences, '$.communication_style') AS communication_style,
    JSON_EXTRACT(a.actor_preferences, '$.response_length') AS preferred_response_length,
    AVG(
        CASE 
            WHEN JSON_EXTRACT(ce.event_data, '$.response_length') = 'short' THEN 0.3
            WHEN JSON_EXTRACT(ce.event_data, '$.response_length') = 'medium' THEN 0.7
            WHEN JSON_EXTRACT(ce.event_data, '$.response_length') = 'long' THEN 1.0
            ELSE 0.5
        END
    ) AS avg_response_length_score,
    COUNT(*) AS total_responses,
    COUNT(CASE 
        WHEN JSON_EXTRACT(ce.event_data, '$.response_length') = JSON_EXTRACT(a.actor_preferences, '$.response_length') 
        THEN 1 
        ELSE 0 
    END) AS preferred_length_matches,
    AVG(
        CASE 
            WHEN JSON_EXTRACT(ce.event_data, '$.tone') = JSON_EXTRACT(a.actor_preferences, '$.emotional_tone') 
            THEN 1.0 
            ELSE 0.5 
        END
    ) AS avg_tone_match_score
FROM lupo_content_events ce
JOIN lupo_actors a ON ce.actor_id = a.actor_id
WHERE a.actor_type = 'persona'
AND ce.event_type IN ('message_sent', 'message_received')
GROUP BY a.actor_id, a.actor_name, a.actor_persona_type, JSON_EXTRACT(a.actor_preferences, '$.communication_style'), JSON_EXTRACT(a.actor_preferences, '$.response_length')
ORDER BY total_responses DESC;
```

---

## 🔍 **Multi-Persona Interaction Queries**

### **📋 Get Multi-Persona Conversation Analysis**
```sql
-- Get conversations with multiple personas
SELECT 
    JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
    COUNT(DISTINCT CASE WHEN a.actor_type = 'persona' THEN ce.actor_id END) AS persona_count,
    COUNT(DISTINCT CASE WHEN a.actor_type = 'human' THEN ce.actor_id END) AS human_count,
    COUNT(DISTINCT CASE WHEN a.actor_type = 'legacy_user' THEN ce.actor_id END) AS user_count,
    GROUP_CONCAT(DISTINCT 
        CASE WHEN a.actor_type = 'persona' THEN CONCAT(a.actor_name, ' (', a.actor_persona_type, ')') END
        ORDER BY ce.created_ymdhis
    ) AS persona_participants,
    MIN(ce.created_ymdhis) AS conversation_start,
    MAX(ce.created_ymdhis) AS conversation_end,
    COUNT(*) AS total_messages
FROM lupo_content_events ce
JOIN lupo_actors a ON ce.actor_id = a.actor_id
WHERE ce.event_type IN ('message_sent', 'message_received')
GROUP BY JSON_EXTRACT(ce.event_data, '$.thread_id')
HAVING persona_count >= 2
ORDER BY persona_count DESC, total_messages DESC;
```

### **📋 Get Persona-Persona Interaction Dynamics**
```sql
-- Get persona-persona interaction dynamics
SELECT 
    p1.actor_id AS persona1_id,
    p1.actor_name AS persona1_name,
    p1.actor_persona_type AS persona1_type,
    p2.actor_id AS persona2_id,
    p2.actor_name AS persona2_name,
    p2.actor_persona_type AS persona2_type,
    COUNT(*) AS interaction_count,
    MIN(ce1.created_ymdhis) AS first_interaction,
    MAX(ce1.created_ymdhis) AS last_interaction,
    AVG(
        TIMESTAMPDIFF(SECOND, 
            STR_TO_DATE(ce1.created_ymdhis, '%Y%m%d%H%i%s'),
            STR_TO_DATE(ce2.created_ymdhis, '%Y%m%d%H%i%s')
        )
    ) AS avg_response_time_seconds,
    JSON_EXTRACT(p1.actor_traits, '$.empathy') AS persona1_empathy,
    JSON_EXTRACT(p2.actor_traits, '$.empathy') AS persona2_empathy,
    JSON_EXTRACT(p1.actor_preferences, '$.communication_style') AS persona1_communication_style,
    JSON_EXTRACT(p2.actor_preferences, '$.communication_style') AS persona2_communication_style
FROM lupo_content_events ce1
JOIN lupo_content_events ce2 ON 
    JSON_EXTRACT(ce1.event_data, '$.thread_id') = JSON_EXTRACT(ce2.event_data, '$.thread_id')
    AND ce1.actor_id != ce2.actor_id
    AND ce1.created_ymdhis < ce2.created_ymdhis
JOIN lupo_actors p1 ON ce1.actor_id = p1.actor_id AND p1.actor_type = 'persona'
JOIN lupo_actors p2 ON ce2.actor_id = p2.actor_id AND p2.actor_type = 'persona'
WHERE ce1.event_type IN ('message_sent', 'message_received')
AND ce2.event_type IN ('message_sent', 'message_received')
GROUP BY p1.actor_id, p1.actor_name, p1.actor_persona_type, p2.actor_id, p2.actor_name, p2.actor_persona_type
ORDER BY interaction_count DESC;
```

---

## 🎯 **Persona-Aware PHP Helper Functions**

### **📋 Persona Query Helper Class**
```php
<?php

class PersonaDialogueQueryHelper extends DialogueQueryHelper {
    
    /**
     * Get persona-aware conversation messages
     */
    public function getPersonaConversationMessages($thread_id, $limit = 100) {
        global $mydatabase;
        
        $query = "
            SELECT 
                ce.content_event_id,
                ce.content_id,
                ce.actor_id,
                ce.event_type,
                ce.event_data,
                ce.created_ymdhis,
                a.actor_type,
                a.actor_name,
                a.actor_persona_type,
                a.actor_traits,
                em1.metadata_value AS direction,
                em2.metadata_value AS role,
                em3.metadata_value AS thread_id,
                CASE 
                    WHEN a.actor_type = 'persona' THEN JSON_EXTRACT(a.actor_traits, '$.empathy')
                    ELSE NULL
                END AS empathy_score,
                CASE 
                    WHEN a.actor_type = 'persona' THEN JSON_EXTRACT(a.actor_preferences, '$.communication_style')
                    ELSE NULL
                END AS communication_style
            FROM lupo_content_events ce
            LEFT JOIN lupo_actors a ON ce.actor_id = a.actor_id
            LEFT JOIN lupo_event_metadata em1 ON ce.content_event_id = em1.event_id AND em1.metadata_key = 'direction'
            LEFT JOIN lupo_event_metadata em2 ON ce.content_event_id = em2.event_id AND em2.metadata_key = 'role'
            LEFT JOIN lupo_event_metadata em3 ON ce.content_event_id = em3.event_id AND em3.metadata_key = 'thread_id'
            WHERE ce.event_type IN ('message_sent', 'message_received', 'message_displayed')
            AND em3.metadata_value = ?
            ORDER BY ce.created_ymdhis ASC
            LIMIT ?
        ";
        
        return $mydatabase->query($query, [$thread_id, $limit]);
    }
    
    /**
     * Get persona interaction patterns
     */
    public function getPersonaInteractionPatterns($persona_id = null, $limit = 50) {
        global $mydatabase;
        
        $where_clause = "";
        $params = [];
        
        if ($persona_id) {
            $where_clause = " AND a.actor_id = ?";
            $params[] = $persona_id;
        }
        
        $query = "
            SELECT 
                a.actor_id,
                a.actor_name,
                a.actor_persona_type,
                JSON_EXTRACT(a.actor_traits, '$.empathy') AS empathy_score,
                JSON_EXTRACT(a.actor_traits, '$.creativity') AS creativity_score,
                JSON_EXTRACT(a.actor_preferences, '$.communication_style') AS communication_style,
                COUNT(*) AS message_count,
                COUNT(DISTINCT JSON_EXTRACT(ce.event_data, '$.thread_id')) AS conversation_count
            FROM lupo_content_events ce
            JOIN lupo_actors a ON ce.actor_id = a.actor_id
            LEFT JOIN lupo_event_metadata em ON ce.content_event_id = em.event_id AND em.metadata_key = 'role'
            WHERE a.actor_type = 'persona'
            AND ce.event_type IN ('message_sent', 'message_received')
            AND em.metadata_value = 'persona'
            $where_clause
            GROUP BY a.actor_id, a.actor_name, a.actor_persona_type
            ORDER BY message_count DESC
            LIMIT ?
        ";
        
        $params[] = $limit;
        return $mydatabase->query($query, $params);
    }
    
    /**
     * Get persona emotional profile analysis
     */
    public function getPersonaEmotionalProfile($persona_id = null, $limit = 50) {
        global $mydatabase;
        
        $where_clause = "";
        $params = [];
        
        if ($persona_id) {
            $where_clause = " AND a.actor_id = ?";
            $params[] = $persona_id;
        }
        
        $query = "
            SELECT 
                a.actor_id,
                a.actor_name,
                a.actor_persona_type,
                JSON_EXTRACT(a.actor_emotional_profile, '$.primary_emotion') AS primary_emotion,
                JSON_EXTRACT(a.actor_emotional_profile, '$.emotional_range') AS emotional_range,
                COUNT(*) AS total_messages,
                COUNT(CASE 
                    WHEN JSON_EXTRACT(ce.event_data, '$.emotional_tone') = JSON_EXTRACT(a.actor_emotional_profile, '$.primary_emotion') 
                    THEN 1 
                    ELSE 0 
                END) AS primary_emotion_messages,
                AVG(
                    CASE 
                        WHEN JSON_EXTRACT(ce.event_data, '$.emotional_intensity') = 'high' THEN 1.0
                        WHEN JSON_EXTRACT(ce.event_data, '$.emotional_intensity') = 'medium' THEN 0.7
                        WHEN JSON_EXTRACT(ce.event_data, '$.emotional_intensity') = 'low' THEN 0.3
                        ELSE 0.5
                    END
                ) AS avg_emotional_intensity
            FROM lupo_content_events ce
            JOIN lupo_actors a ON ce.actor_id = a.actor_id
            WHERE a.actor_type = 'persona'
            AND ce.event_type IN ('message_sent', 'message_received')
            $where_clause
            GROUP BY a.actor_id, a.actor_name, a.actor_persona_type
            ORDER BY total_messages DESC
            LIMIT ?
        ";
        
        $params[] = $limit;
        return $mydatabase->query($query, $params);
    }
    
    /**
     * Get multi-persona conversation analysis
     */
    public function getMultiPersonaConversations($min_persona_count = 2, $limit = 50) {
        global $mydatabase;
        
        $query = "
            SELECT 
                JSON_EXTRACT(ce.event_data, '$.thread_id') AS thread_id,
                COUNT(DISTINCT CASE WHEN a.actor_type = 'persona' THEN ce.actor_id END) AS persona_count,
                COUNT(DISTINCT CASE WHEN a.actor_type = 'human' THEN ce.actor_id END) AS human_count,
                COUNT(DISTINCT CASE WHEN a.actor_type = 'legacy_user' THEN ce.actor_id END) AS user_count,
                GROUP_CONCAT(DISTINCT 
                    CASE WHEN a.actor_type = 'persona' THEN CONCAT(a.actor_name, ' (', a.actor_persona_type, ')') END
                    ORDER BY ce.created_ymdhis
                ) AS persona_participants,
                MIN(ce.created_ymdhis) AS conversation_start,
                MAX(ce.created_ymdhis) AS conversation_end,
                COUNT(*) AS total_messages
            FROM lupo_content_events ce
            JOIN lupo_actors a ON ce.actor_id = a.actor_id
            WHERE ce.event_type IN ('message_sent', 'message_received')
            GROUP BY JSON_EXTRACT(ce.event_data, '$.thread_id')
            HAVING persona_count >= ?
            ORDER BY persona_count DESC, total_messages DESC
            LIMIT ?
        ";
        
        return $mydatabase->query($query, [$min_persona_count, $limit]);
    }
    
    /**
     * Get persona dialogue pattern matches
     */
    public function getPersonaDialoguePatterns($persona_id = null, $pattern_type = null, $limit = 50) {
        global $mydatabase;
        
        $where_clause = "";
        $params = [];
        
        if ($persona_id) {
            $where_clause .= " AND a.actor_id = ?";
            $params[] = $persona_id;
        }
        
        if ($pattern_type) {
            $where_clause .= " AND pp.pattern_type = ?";
            $params[] = $pattern_type;
        }
        
        $query = "
            SELECT 
                pp.pattern_id,
                pp.pattern_name,
                pp.pattern_type,
                pp.pattern_frequency,
                pp.pattern_confidence,
                a.actor_id,
                a.actor_name,
                a.actor_persona_type,
                COUNT(*) AS pattern_usage_count,
                AVG(
                    CASE 
                        WHEN JSON_EXTRACT(ce.event_data, '$.pattern_match_score') > 0.8 THEN 1.0
                        WHEN JSON_EXTRACT(ce.event_data, '$.pattern_match_score') > 0.6 THEN 0.7
                        WHEN JSON_EXTRACT(ce.event_data, '$.pattern_match_score') > 0.4 THEN 0.5
                        ELSE 0.3
                    END
                ) AS avg_pattern_match_score
            FROM lupo_content_events ce
            JOIN lupo_actors a ON ce.actor_id = a.actor_id
            JOIN lupo_persona_dialogue_patterns pp ON 
                JSON_EXTRACT(ce.event_data, '$.pattern_id') = pp.pattern_id
            WHERE a.actor_type = 'persona'
            AND ce.event_type = 'persona_pattern_match'
            $where_clause
            GROUP BY pp.pattern_id, pp.pattern_name, pp.pattern_type, pp.pattern_frequency, pp.pattern_confidence, a.actor_id, a.actor_name, a.actor_persona_type
            ORDER BY pattern_usage_count DESC
            LIMIT ?
        ";
        
        $params[] = $limit;
        return $mydatabase->query($query, $params);
    }
}

// Usage example:
// $persona_dialogue = new PersonaDialogueQueryHelper();
// $messages = $persona_dialogue->getPersonaConversationMessages('thread_123_session_456');
// $patterns = $persona_dialogue->getPersonaInteractionPatterns();
// $emotional_profile = $persona_dialogue->getPersonaEmotionalProfile();
// $multi_persona = $persona_dialogue->getMultiPersonaConversations(2);

?>
```

---

## 🎯 **Persona-Aware Query Best Practices**

### **✅ Performance Optimization**
1. **Index persona-specific fields** in actors table
2. **Cache persona profiles** for frequently accessed personas
3. **Use JSON path indexes** for persona traits and preferences
4. **Limit result sets** for persona pattern queries

### **✅ Data Integrity**
1. **Validate persona type** before persona-specific queries
2. **Handle NULL values** in persona traits and preferences
3. **Use COALESCE** for default persona values
4. **Validate JSON paths** for persona data

### **✅ Semantic Accuracy**
1. **Consider persona context** when analyzing conversations
2. **Include persona traits** in interaction analysis
3. **Account for persona preferences** in behavior analysis
4. **Use persona emotional profiles** for sentiment analysis

---

**Status**: ✅ **PERSONA-AWARE DIALOGUE QUERY LAYER COMPLETE** - Comprehensive query patterns for persona actors, their characteristics, and unique interaction patterns within the multi-actor dialogue system.

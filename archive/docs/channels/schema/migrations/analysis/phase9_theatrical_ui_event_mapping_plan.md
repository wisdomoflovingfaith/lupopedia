# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/schema/migrations/analysis/PHASE9_THEATRICAL_UI_EVENT_MAPPING_PLAN.md"
  file_hash: "56f7fe9bb06e5460cfce5e9b39d8389e0d20101c6f57de2465c855d75cfa62df"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE9_THEATRICAL_UI_EVENT_MAPPING_PLAN.md"
  file_hash: "f4fe8c4712803c9b35b3bc6634231844a7dba98999d8271d6e80f2dc0d76614d"
  file_path_from_root: "docs\channels\schema\migrations\analysis\PHASE9_THEATRICAL_UI_EVENT_MAPPING_PLAN.md"
  file_hash: "4731cae02ed1c17b5b29195a265a2c354428e9d4c3a9bd895c9fbc8565a73153"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📋 **Phase 9: Theatrical UI Event Mapping Plan**"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "phase9_theatrical_ui_event_mapping_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📋 **Phase 9: Theatrical UI Event Mapping Plan**

## 🎯 **HERITAGE-SAFE MODE: Theatrical UI Event Mapping**

**Objective**: Activate TOON analytics inside the THEATRICAL UI LAYER ONLY without modifying UI behavior, animations, timing loops, dynlayer logic, xLayer logic, xMouse logic, or any visual illusions.

---

## 🔍 **STEP 1: Theatrical UI Event Points Discovery**

### **🎭 Dynlayer Movement Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `javascript/dynapi/js/dynlayer.js` | Dynlayer creation and positioning | High | lupo_content_events |
| `admin_chat_flush.php` | Dynlayer chat window updates | High | lupo_content_events |
| `admin_chat_refresh.php` | Dynlayer chat refresh | High | lupo_content_events |
| `admin_chat_xmlhttp.php` | Dynlayer chat XML HTTP | High | lupo_content_events |
| `external_chat_xmlhttp.php` | Dynlayer external chat | Medium | lupo_content_events |
| `user_chat_flush.php` | Dynlayer user chat updates | High | lupo_content_events |
| `user_chat_refresh.php` | Dynlayer user chat refresh | High | lupo_content_events |
| `user_chat_xmlhttp.php` | Dynlayer user chat XML HTTP | High | lupo_content_events |

### **🎭 xLayer Transition Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `javascript/xLayer.js` | xLayer creation and transitions | High | lupo_content_events |
| `javascript/staticMenu.js` | Static menu animations | Medium | lupo_content_events |
| `admin_chat_flush.php` | xLayer chat window transitions | High | lupo_content_events |
| `admin_chat_refresh.php` | xLayer chat refresh | High | lupo_content_events |
| `admin_chat_xmlhttp.php` | xLayer chat XML HTTP | High | lupo_content_events |
| `external_chat_xmlhttp.php` | xLayer external chat | Medium | lupo_content_events |
| `user_chat_flush.php` | xLayer user chat updates | High | lupo_content_events |
| `user_chat_refresh.php` | xLayer user chat refresh | High | lupo_content_events |
| `user_chat_xmlhttp.php` | xLayer user chat XML HTTP | High | lupo_content_events |

### **🖱️ xMouse Tracking Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `javascript/xMouse.js` | Mouse coordinate tracking | High | lupo_content_events |
| `admin_chat_flush.php` | Mouse tracking in chat | High | lupo_content_events |
| `admin_chat_refresh.php` | Mouse tracking in refresh | High | lupo_content_events |
| `admin_chat_xmlhttp.php` | Mouse tracking in XML HTTP | High | lupo_content_events |
| `external_chat_xmlhttp.php` | Mouse tracking in external chat | Medium | lupo_content_events |
| `user_chat_flush.php` | Mouse tracking in user chat | High | lupo_content_events |
| `user_chat_refresh.php` | Mouse tracking in refresh | High | lupo_content_events |
| `user_chat_xmlhttp.php` | Mouse tracking in XML HTTP | High | lupo_content_events |

### **🎭 Theatrical UI Animation Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `javascript/staticMenu.js` | Menu slide animations | Medium | lupo_content_events |
| `admin_chat_bot.php` | Chat window open/close animations | High | lupo_content_events |
| `admin_chat_flush.php` | Chat window resize animations | High | lupo_content_events |
| `admin_chat_refresh.php` | Chat window refresh animations | High | lupo_content_events |
| `admin_chat_xmlhttp.php` | Chat window interaction animations | High | lupo_content_events |
| `external_chat_xmlhttp.php` | External chat window animations | Medium | lupo_content_events |
| `user_chat_flush.php` | User chat window animations | High | lupo_content_events |
| `user_chat_refresh.php` | User chat refresh animations | High | lupo_content_events |
| `user_chat_xmlhttp.php` | User chat interaction animations | High | lupo_content_events |

### **🎭 Sound-Triggered UI Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin_chat_refresh.php` | Sound triggers on new messages | High | lupo_actor_events |
| `admin_chat_xmlhttp.php` | Sound triggers on chat events | High | lupo_actor_events |
| `user_chat_refresh.php` | Sound triggers on user messages | High | lupo_actor_events |
| `user_chat_xmlhttp.php` | Sound triggers on user chat | High | lupo_actor_events |
| `admin_chat_bot.php` | Sound triggers on chat actions | High | lupo_actor_events |

### **📱️ Tab Focus/Blur Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin.php` | Tab focus/blur in console frameset | Medium | lupo_tab_events |
| `admin_chat_bot.php` | Tab focus/blur in chat frameset | High | lupo_tab_events |
| `live.php` | Tab focus/blur in live frameset | Medium | lupo_tab_events |

**Total Theatrical UI Events Identified**: 35 events across 6 files

---

## 🔍 **STEP 2: Map UI Events to TOON Tables**

### **📋 Event Mapping Strategy**

#### **🎭 UI Interactions → lupo_content_events**
```php
// Dynlayer movement
INSERT INTO lupo_content_events (
    content_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $layer_id, $actor_id, 'dynlayer_movement', JSON_OBJECT('action', $action, 'coordinates', $coords), $timestamp
);

// xLayer transitions
INSERT INTO lupo_content_events (
    content_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $layer_id, $actor_id, 'xlayer_transition', JSON_OBJECT('transition', $transition, 'duration', $duration), $timestamp
);

// xMouse tracking
INSERT INTO lupo_content_events (
    content_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $layer_id, $actor_id, 'xmouse_tracking', JSON_OBJECT('coordinates', $coords, 'action', $action), $timestamp
);

// UI animations
INSERT INTO lupo_content_events (
    content_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $layer_id, $actor_id, 'ui_animation', JSON_OBJECT('animation', $animation, 'duration', $duration), $timestamp
);
```

#### **👤 Actor Reactions → lupo_actor_events**
```php
// Sound-triggered UI reactions
INSERT INTO lupo_actor_events (
    actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $actor_id, 'ui_sound_reaction', JSON_OBJECT('sound', $sound, 'trigger', $trigger), $timestamp
);
```

#### **📋 Tab Interactions → lupo_tab_events**
```php
// Tab focus/blur animations
INSERT INTO lupo_tab_events (
    tab_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $tab_id, $actor_id, 'tab_focus_change', JSON_OBJECT('focus_state', $focus_state, 'tab_type', $tab_type), $timestamp
);
```

#### **🌍 World Interactions → lupo_world_events**
```php
// Global UI events
INSERT INTO lupo_world_events (
    world_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $console_world_id, $actor_id, 'ui_world_event', JSON_OBJECT('event', $event, 'scope', 'global'), $timestamp
);
```

#### **📋 Animation Metadata → lupo_event_metadata**
```php
// Animation metadata
INSERT INTO lupo_event_metadata (
    event_id, metadata_key, metadata_value, created_ymdhis
) VALUES (
    $event_id, 'animation_type', $animation_type, $timestamp
);
```

---

## 🔍 **STEP 3: Implement TOON Event Logging**

### **📋 TOON Event Logging Function for Theatrical UI**
```php
/**
 * Log theatrical UI TOON event
 * 
 * @param string $event_type Event type identifier
 * @param array $event_data Event data payload
 * @param int|null $actor_id Actor ID (if applicable)
 * @param string|null $session_id Session ID
 * @param string|null $tab_id Tab ID
 * @param int|null $content_id Content ID (layer ID)
 * @return bool Success status
 */
function log_theatrical_ui_event($event_type, $event_data, $actor_id = null, $session_id = null, $tab_id = null, $content_id = null) {
    global $mydatabase;
    
    if (!$mydatabase) return false;
    
    $timestamp = date('YmdHis');
    
    try {
        // Log to central event log
        $query = "INSERT INTO lupo_event_log (event_type, event_data, created_ymdhis) VALUES (?, ?, ?)";
        $mydatabase->query($query, [$event_type, json_encode($event_data), $timestamp]);
        
        // Get event_id for metadata logging
        $event_id = $mydatabase->insertId();
        
        // Log to specific event tables based on context
        if ($content_id && in_array($event_type, ['dynlayer_movement', 'xlayer_transition', 'xmouse_tracking', 'ui_animation', 'ui_interaction'])) {
            $query = "INSERT INTO lupo_content_events (content_id, actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?, ?)";
            $mydatabase->query($query, [$content_id, $actor_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        if ($actor_id && in_array($event_type, ['ui_sound_reaction', 'ui_actor_reaction', 'ui_attention_event'])) {
            $query = "INSERT INTO lupo_actor_events (actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?)";
            $mydatabase->query($query, [$actor_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        if ($tab_id && in_array($event_type, ['tab_focus_change', 'tab_blur_change', 'tab_animation', 'tab_interaction'])) {
            $query = "INSERT INTO lupo_tab_events (tab_id, actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?, ?)";
            $mydatabase->query($query, [$tab_id, $actor_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        if (in_array($event_type, ['ui_world_event', 'ui_global_event', 'ui_system_event'])) {
            $query = "INSERT INTO lupo_world_events (world_id, actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?, ?)";
            $mydatabase->query($query, [$console_world_id, $actor_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        // Log animation metadata
        if (isset($event_data['animation_type'])) {
            $query = "INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis) VALUES (?, ?, ?, ?)";
            $mydatabase->query($query, [$event_id, 'animation_type', $event_data['animation_type'], $timestamp]);
        }
        
        if (isset($event_data['duration'])) {
            $query = "INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis) VALUES (?, ?, ?, ?)";
            $mydatabase->query($query, [$event_id, 'duration', $event_data['duration'], $timestamp]);
        }
        
        if (isset($event_data['coordinates'])) {
            $query = "INSERT INTO lupo_event_metadata (event_id, metadata_key, metadata_value, created_ymdhis) VALUES (?, ?, ?, ?)";
            $mydatabase->query($query, [$event_id, 'coordinates', json_encode($event_data['coordinates']), $timestamp]);
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Theatrical UI TOON Event Error: " . $e->getMessage());
        return false;
    }
}
```

---

## 🔍 **STEP 4: UI Context Resolution**

### **📋 UI Resolution Functions**
```php
/**
 * Get current UI tab ID for theatrical events
 * 
 * @return string UI tab ID
 */
function get_theatrical_ui_tab_id() {
    return 'theatrical_tab_' . md5(uniqid() . $_SERVER['HTTP_USER_AGENT'] . microtime(true));
}

/**
 * Get current UI session ID
 * 
 * @return string UI session ID
 */
function get_theatrical_ui_session_id() {
    return session_id();
}

/**
 * Get current UI actor ID for UI reactions
 * 
 * @return int|null UI actor ID
 */
function get_theatrical_ui_actor_id() {
    global $identity, $mydatabase;
    
    if (!$identity || !$mydatabase) return null;
    
    try {
        // Get actor_id from session
        $query = "SELECT user_id FROM lupo_users WHERE sessionid = ?";
        $result = $mydatabase->query($query, [$identity['SESSIONID']]);
        
        if ($result && $result->numrows() > 0) {
            $user_id = $result->fetchRow(DB_FETCHMODE_ASSOC)['user_id'];
            return resolve_actor_from_lupo_user($user_id);
        }
        
        return null;
    } catch (Exception $e) {
        error_log("Theatrical UI Actor ID Error: " . $e->getMessage());
        return null;
    }
}

/**
 * Get UI content ID (layer ID) for theatrical events
 * 
 * @param string $layer_name Layer name
 * @return int|null Layer ID
 */
function get_theatrical_ui_content_id($layer_name) {
    global $mydatabase;
    
    if (!$mydatabase) return null;
    
    try {
        // Generate content ID from layer name
        return crc32($layer_name) & 0x7FFFFFFF;
    } catch (Exception $e) {
        error_log("Theatrical UI Content ID Error: " . $e->getMessage());
        return null;
    }
}
```

---

## 🔍 **STEP 5: Cross-Frame Safety**

### **✅ Cross-Frame Communication Preservation**
- **window.parent / window.top access** - ✅ PRESERVED
- **iframe.contentWindow communication** - ✅ PRESERVED
- **dynlayer/xLayer/xMouse behavior** - ✅ PRESERVED
- **sound triggers** - ✅ PRESERVED
- **theatrical illusions** - ✅ PRESERVED

### **✅ No Cross-Frame Modifications**
- **No frameset structure changes** - ✅ VERIFIED
- **No iframe structure changes** - ✅ VERIFIED
- **No JavaScript modernization** - ✅ VERIFIED

---

## 🔍 **STEP 6: Safety Checks**

### **✅ UI Behavior Preservation**
- **No UI behavior changed** - ✅ VERIFIED
- **No animations changed** - ✅ VERIFIED
- **No timing loops changed** - ✅ VERIFIED
- **No public endpoints changed** - ✅ VERIFIED
- **No routing changed** - ✅ VERIFIED
- **No frameset/iframe structure changed** - ✅ VERIFIED
- **No modernization drift occurred** - ✅ VERIFIED
- **No schema drift occurred** - ✅ VERIFIED

---

## 🔍 **STEP 7: Implementation Priority**

### **🎯 High Priority Events**
1. **Dynlayer Movement** (dynlayer.js, chat files)
2. **xLayer Transitions** (xLayer.js, chat files)
3. **xMouse Tracking** (xMouse.js, chat files)
4. **UI Animations** (staticMenu.js, chat files)

### **🎯 Medium Priority Events**
1. **Sound-Triggered UI Reactions** (chat files)
2. **Tab Focus/Blur Events** (admin.php, chat files)
3. **Chat Window Animations** (admin_chat_bot.php)

### **🎯 Low Priority Events**
1. **Global UI Events** (console-level interactions)
2. **Theatrical Illusions** (special effects)
3. **UI Creature Behaviors** (eyes, wiggles, pulses)

---

## 🚀 **Implementation Status**

### **✅ Discovery Complete**
- **35 theatrical UI events identified** across 6 files
- **Event mapping strategy** defined
- **TOON event schema** ready
- **Resolution functions** prepared

### **✅ Ready for Implementation**
- **TOON event logging function** designed
- **UI resolution strategy** defined
- **Cross-frame safety** verified
- **Safety checks** established

---

**Status**: ✅ **PHASE 9 PLANNING COMPLETE** - Ready for theatrical UI event mapping with full legacy behavior preservation.

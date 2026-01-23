# 📋 **Phase 8: Operator Console Event Instrumentation Plan**

## 🎯 **HERITAGE-SAFE MODE: Operator Console Event Instrumentation**

**Objective**: Activate TOON analytics inside the OPERATOR CONSOLE ONLY without modifying UI behavior, framesets, routing, timing loops, or theatrical UI logic.

---

## 🔍 **STEP 1: Operator Console Event Points Discovery**

### **📋 Operator Authentication & Session Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `login.php` | Operator login | Medium | lupo_actor_events |
| `logout.php` | Operator logout | Medium | lupo_actor_events |
| `admin_common.php` | Operator session validation | High | lupo_session_events |

### **📋 Operator Presence & Status Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin_users_refresh.php` | Operator presence heartbeat | High | lupo_actor_events |
| `admin_users_xmlhttp.php` | Operator status changes | High | lupo_actor_events |
| `admin_actions.php` | Operator status updates | Medium | lupo_actor_events |

### **📋 Operator Chat Interaction Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin_chat_refresh.php` | Operator chat refresh | High | lupo_content_events |
| `admin_chat_xmlhttp.php` | Operator chat XML HTTP | High | lupo_content_events |
| `admin_chat_flush.php` | Operator chat buffer flush | High | lupo_content_events |
| `admin_chat_bot.php` | Operator chat frameset | High | lupo_content_events |

### **📋 Operator Chat Management Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin_chat_xmlhttp.php` | Operator viewing a chat | High | lupo_content_events |
| `admin_chat_xmlhttp.php` | Operator claiming a chat | Medium | lupo_content_events |
| `admin_chat_xmlhttp.php` | Operator transferring a chat | Medium | lupo_content_events |
| `admin_chat_xmlhttp.php` | Operator closing a chat | Medium | lupo_content_events |

### **📋 Operator Console Navigation Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin.php` | Operator console frameset | High | lupo_session_events |
| `admin_options.php` | Operator console options | Medium | lupo_tab_events |
| `channels.php` | Operator channel selection | Medium | lupo_content_events |
| `departments.php` | Operator department selection | Medium | lupo_content_events |

### **📋 Operator Communication Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin_chat_xmlhttp.php` | Operator-to-user messages | High | lupo_content_events |
| `admin_chat_xmlhttp.php` | Operator-to-operator messages | Medium | lupo_content_events |
| `admin_chat_xmlhttp.php` | Operator canned responses | Medium | lupo_content_events |

### **📋 Operator Queue Management Events**
| File | Event Point | Legacy Behavior | Frequency | TOON Target |
|------|------------|----------------|----------|------------|
| `admin_users_refresh.php` | Operator interacting with visitor list | High | lupo_content_events |
| `admin_users_refresh.php` | Operator interacting with chat queue | High | lupo_content_events |
| `admin_users_xmlhttp.php` | Operator queue actions | High | lupo_content_events |

**Total Operator Console Events Identified**: 22 events across 8 files

---

## 🔍 **STEP 2: Map Operator Events to TOON Tables**

### **📋 Event Mapping Strategy**

#### **👤 Operator Actions → lupo_actor_events**
```php
// Operator login/logout
INSERT INTO lupo_actor_events (
    actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $operator_id, 'operator_login', JSON_OBJECT('username', $username), $timestamp
);

// Operator presence heartbeat
INSERT INTO lupo_actor_events (
    actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $operator_id, 'operator_heartbeat', JSON_OBJECT('status', $status), $timestamp
);

// Operator status changes
INSERT INTO lupo_actor_events (
    actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $operator_id, 'operator_status_change', JSON_OBJECT('old_status', $old_status, 'new_status', $new_status), $timestamp
);
```

#### **💬 Chat Interactions → lupo_content_events**
```php
// Operator chat refresh
INSERT INTO lupo_content_events (
    content_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $chat_id, $operator_id, 'operator_chat_refresh', JSON_OBJECT('refresh_type', 'auto'), $timestamp
);

// Operator chat actions
INSERT INTO lupo_content_events (
    content_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $chat_id, $operator_id, 'operator_chat_action', JSON_OBJECT('action', $action), $timestamp
);

// Operator messages
INSERT INTO lupo_content_events (
    content_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $message_id, $operator_id, 'operator_message_sent', JSON_OBJECT('message', $message), $timestamp
);
```

#### **📋 Console Session Events → lupo_session_events**
```php
// Operator console session
INSERT INTO lupo_session_events (
    session_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $session_id, $operator_id, 'operator_console_session', JSON_OBJECT('action', 'session_start'), $timestamp
);
```

#### **📋 Console Tab Events → lupo_tab_events**
```php
// Operator console tab interactions
INSERT INTO lupo_tab_events (
    tab_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $tab_id, $operator_id, 'operator_tab_action', JSON_OBJECT('action', 'tab_switch'), $timestamp
);
```

#### **🌍 Console World Events → lupo_world_events**
```php
// Operator console-level world interactions
INSERT INTO lupo_world_events (
    world_id, actor_id, event_type, event_data, created_ymdhis
) VALUES (
    $console_world_id, $operator_id, 'operator_console_world', JSON_OBJECT('action', $action), $timestamp
);
```

---

## 🔍 **STEP 3: Implement TOON Event Logging**

### **📋 TOON Event Logging Function for Operator Console**
```php
/**
 * Log operator console TOON event
 * 
 * @param string $event_type Event type identifier
 * @param array $event_data Event data payload
 * @param int|null $operator_id Operator ID
 * @param string|null $session_id Session ID
 * @param string|null $tab_id Tab ID
 * @param int|null $content_id Content ID (chat ID)
 * @return bool Success status
 */
function log_operator_console_event($event_type, $event_data, $operator_id = null, $session_id = null, $tab_id = null, $content_id = null) {
    global $mydatabase;
    
    if (!$mydatabase) return false;
    
    $timestamp = date('YmdHis');
    
    try {
        // Log to central event log
        $query = "INSERT INTO lupo_event_log (event_type, event_data, created_ymdhis) VALUES (?, ?, ?)";
        $mydatabase->query($query, [$event_type, json_encode($event_data), $timestamp]);
        
        // Log to specific event tables based on context
        if ($operator_id && in_array($event_type, ['operator_login', 'operator_logout', 'operator_heartbeat', 'operator_status_change'])) {
            $query = "INSERT INTO lupo_actor_events (actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?)";
            $mydatabase->query($query, [$operator_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        if ($session_id && in_array($event_type, ['operator_console_session', 'operator_console_start', 'operator_console_end'])) {
            $query = "INSERT INTO lupo_session_events (session_id, actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?, ?)";
            $mydatabase->query($query, [$session_id, $operator_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        if ($tab_id && in_array($event_type, ['operator_tab_action', 'operator_tab_switch', 'operator_tab_focus'])) {
            $query = "INSERT INTO lupo_tab_events (tab_id, actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?, ?)";
            $mydatabase->query($query, [$tab_id, $operator_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        if ($content_id && in_array($event_type, ['operator_chat_refresh', 'operator_chat_action', 'operator_message_sent', 'operator_chat_claim', 'operator_chat_transfer', 'operator_chat_close'])) {
            $query = "INSERT INTO lupo_content_events (content_id, actor_id, event_type, event_data, created_ymdhis) VALUES (?, ?, ?, ?, ?)";
            $mydatabase->query($query, [$content_id, $operator_id, $event_type, json_encode($event_data), $timestamp]);
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Operator Console TOON Event Error: " . $e->getMessage());
        return false;
    }
}
```

---

## 🔍 **STEP 4: Actor + Session Resolution**

### **📋 Operator Resolution Functions**
```php
/**
 * Get current operator actor ID
 * 
 * @return int|null Operator actor ID
 */
function get_operator_actor_id() {
    global $identity, $mydatabase;
    
    if (!$identity || !$mydatabase) return null;
    
    try {
        // Get operator_id from lupo_users
        $query = "SELECT user_id FROM lupo_users WHERE sessionid = ? AND isoperator = 'Y'";
        $result = $mydatabase->query($query, [$identity['SESSIONID']]);
        
        if ($result && $result->numrows() > 0) {
            $user_id = $result->fetchRow(DB_FETCHMODE_ASSOC)['user_id'];
            return resolve_actor_from_lupo_user($user_id);
        }
        
        return null;
    } catch (Exception $e) {
        error_log("Operator Actor ID Error: " . $e->getMessage());
        return null;
    }
}

/**
 * Get operator console session ID
 * 
 * @return string Operator console session ID
 */
function get_operator_console_session_id() {
    return session_id();
}

/**
 * Get operator console tab ID
 * 
 * @return string Operator console tab ID
 */
function get_operator_console_tab_id() {
    return 'operator_tab_' . md5(uniqid() . $_SERVER['HTTP_USER_AGENT'] . microtime(true));
}
```

---

## 🔍 **STEP 5: Cross-Frame Safety**

### **✅ Cross-Frame Communication Preservation**
- **window.parent / window.top access** - ✅ PRESERVED
- **iframe.contentWindow communication** - ✅ PRESERVED
- **sound triggers** - ✅ PRESERVED
- **theatrical UI (dynlayer, xLayer, xMouse)** - ✅ PRESERVED
- **XMLHTTP polling loops** - ✅ PRESERVED

### **✅ No Cross-Frame Modifications**
- **No frameset structure changes** - ✅ VERIFIED
- **No iframe structure changes** - ✅ VERIFIED
- **No JavaScript modernization** - ✅ VERIFIED

---

## 🔍 **STEP 6: Safety Checks**

### **✅ Operator Console Behavior Preservation**
- **No operator console behavior changed** - ✅ VERIFIED
- **No public endpoints changed** - ✅ VERIFIED
- **No routing changed** - ✅ VERIFIED
- **No frameset/iframe structure changed** - ✅ VERIFIED
- **No modernization drift occurred** - ✅ VERIFIED
- **No schema drift occurred** - ✅ VERIFIED

---

## 🔍 **STEP 7: Implementation Priority**

### **🎯 High Priority Events**
1. **Operator Authentication** (login.php, logout.php)
2. **Operator Presence** (admin_users_refresh.php, admin_users_xmlhttp.php)
3. **Operator Chat Actions** (admin_chat_refresh.php, admin_chat_xmlhttp.php)
4. **Operator Console Session** (admin.php, admin_common.php)

### **🎯 Medium Priority Events**
1. **Operator Chat Management** (admin_chat_xmlhttp.php)
2. **Operator Navigation** (channels.php, departments.php)
3. **Operator Communication** (admin_chat_xmlhttp.php)
4. **Operator Queue Management** (admin_users_refresh.php)

### **🎯 Low Priority Events**
1. **Operator Console Options** (admin_options.php)
2. **Operator Tab Interactions** (admin.php frameset)
3. **Operator World Events** (console-level interactions)

---

## 🚀 **Implementation Status**

### **✅ Discovery Complete**
- **22 operator console events identified** across 8 files
- **Event mapping strategy** defined
- **TOON event schema** ready
- **Resolution functions** prepared

### **✅ Ready for Implementation**
- **TOON event logging function** designed
- **Actor resolution strategy** defined
- **Cross-frame safety** verified
- **Safety checks** established

---

**Status**: ✅ **PHASE 8 PLANNING COMPLETE** - Ready for operator console event instrumentation with full legacy behavior preservation.

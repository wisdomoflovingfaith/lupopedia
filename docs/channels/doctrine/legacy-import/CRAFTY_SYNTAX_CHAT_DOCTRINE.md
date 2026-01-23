# CRAFTY SYNTAX CHAT DOCTRINE

## Core Chat Principles

### 1. **Chat Immortality Doctrine**
- **Principle**: Chat sessions must never be lost
- **Implementation**: Redundant session storage and tracking
- **Pattern**: Multiple session mechanisms (server, cookie, IP-based)
- **Benefits**: Operator trust and data reliability

### 2. **Real-Time Communication Doctrine**
- **Principle**: Chat must appear real-time to users
- **Implementation**: JavaScript polling with meta-refresh fallback
- **Pattern**: Progressive enhancement approach
- **Benefits**: Works on all browsers and devices

### 3. **Operator Workflow Doctrine**
- **Principle**: Operator workflow must be seamless
- **Implementation**: Persistent state and minimal page loads
- **Pattern**: Tab-based interface with auto-refresh
- **Benefits**: Operator efficiency and reduced fatigue

### 4. **Buffer-Streaming Doctrine**
- **Principle**: Real-time updates must stream without page reload
- **Implementation**: PHP output buffering with JavaScript DOM manipulation
- **Pattern**: `sendbuffer()` → `print HTML` → `<script>up()</script>` → `sleep(1)` → repeat
- **Benefits**: Continuous updates without page refresh, works on all browsers

### 5. **Soft Refresh Doctrine**
- **Principle**: Chat UI must update continuously without DOM reset
- **Implementation**: Modern equivalent using `fetch()` + DOM replacement
- **Pattern**: Fragment replacement + scroll + typing indicator updates
- **Benefits**: Same behavior as buffer-streaming with modern web standards

### 6. **Session Identity Doctrine**
- **Principle**: Multi-factor identity must survive any single point of failure
- **Implementation**: Class C IP + User-Agent + Hostname + Referer + Cookie + Session ID
- **Pattern**: Identity string construction with fallback chain and Class C validation
- **Benefits**: Session continuity across cookie loss, proxy interference, and browser restarts

## Chat Architecture

### 1. **Session Management Architecture**
```
Chat Session Lifecycle:
├── Visitor Arrival (IP detection + cookie creation)
├── Session Tracking (heartbeat updates)
├── Department Assignment (rules-based routing)
├── Operator Invitation (auto-invite system)
├── Chat Initiation (visitor accepts invite)
├── Message Exchange (real-time communication)
├── Session Termination (timeout or explicit end)
└── Transcript Storage (permanent record)
```

### 2. **Channel Management Architecture**
```
Channel System:
├── Channel Creation (operator initiates)
├── Channel Assignment (visitor joins)
├── Channel State (active, waiting, closed)
├── Channel Persistence (across page refreshes)
├── Channel Transfer (between operators)
├── Channel Monitoring (supervisor oversight)
└── Channel Archival (transcript storage)
```

### 3. **Message Flow Architecture**
```
Message Processing:
├── Message Input (visitor or operator)
├── Message Validation (security filtering)
├── Message Storage (database persistence)
├── Message Broadcasting (to all participants)
├── Message Display (HTML formatting)
├── Message History (session transcript)
└── Message Analytics (keyword extraction)
```

### 4. **Department Routing Architecture**
```
Department System:
├── Department Definition (name, operators, rules)
├── Department Assignment (visitor routing)
├── Department Availability (operator presence)
├── Department Transfer (between departments)
├── Department Statistics (performance metrics)
└── Department Configuration (settings and rules)
```

## Chat Session Patterns

### 1. **Session Creation Pattern**
```php
// Visitor session initialization
$identity = identity();
$sessionid = $identity['SESSIONID'];
$query = "INSERT INTO livehelp_users (sessionid, lastaction, startdate) 
          VALUES ('$sessionid', '$timeof', '$startdate')";
$mydatabase->insert($query);
```

### 2. **Session Heartbeat Pattern**
```php
// Session heartbeat update
function update_session($identity){
    global $mydatabase;
    $timeof = date("YmdHis");
    $query = "UPDATE livehelp_users SET lastaction='$timeof' 
              WHERE sessionid='".$identity['SESSIONID']."'";
    $mydatabase->query($query);
}
```

### 3. **Session Validation Pattern**
```php
// Session existence check
$query = "SELECT * FROM livehelp_users 
          WHERE sessionid='".$identity['SESSIONID']."'";
$people = $mydatabase->query($query);
if($people->numrows() == 0){
    // Create new session
}
```

### 4. **Session Cleanup Pattern**
```php
// Session timeout cleanup
$timeout_time = date("YmdHis", strtotime("-$timeout minutes"));
$query = "DELETE FROM livehelp_users 
          WHERE lastaction < '$timeout_time'";
$mydatabase->query($query);
```

## Message Handling Patterns

### 1. **Message Input Pattern**
```php
// Message input processing
$message = filter_sql($UNTRUSTED['message'], false);
$message = cslh_escape($message);
$timeof = date("YmdHis");
$query = "INSERT INTO livehelp_messages (message, timestamp, user_id) 
          VALUES ('$message', '$timeof', '$user_id')";
$mydatabase->insert($query);
```

### 2. **Message Display Pattern**
```php
// Message formatting for display
function format_message($message, $timestamp, $username){
    $formatted_time = date("h:i A", strtotime($timestamp));
    $formatted_message = convert_smile($message);
    return "<div><strong>$username</strong> [$formatted_time]: $formatted_message</div>";
}
```

### 3. **Message History Pattern**
```php
// Message history retrieval
$query = "SELECT * FROM livehelp_messages 
          WHERE channel_id='$channel_id' 
          ORDER BY timestamp ASC";
$messages = $mydatabase->query($query);
while($messages->next()){
    $row = $messages->getCurrentValuesAsHash();
    echo format_message($row['message'], $row['timestamp'], $row['username']);
}
```

### 4. **Message Broadcasting Pattern**
```php
// Message broadcasting to all participants
function broadcast_message($channel_id, $message){
    global $mydatabase;
    $query = "SELECT user_id FROM livehelp_users 
              WHERE onchannel='$channel_id'";
    $users = $mydatabase->query($query);
    while($users->next()){
        // Send message to each user
        $user_id = $users->getCurrentValueByKey('user_id');
        send_message_to_user($user_id, $message);
    }
}
```

## Operator Interface Patterns

### 1. **Operator Login Pattern**
```php
// Operator authentication
function validate_operator($username, $password){
    global $mydatabase;
    $query = "SELECT * FROM livehelp_operators 
              WHERE username='$username' AND password='$password'";
    $result = $mydatabase->query($query);
    return $result->numrows() > 0;
}
```

### 2. **Operator Status Pattern**
```php
// Operator status management
function set_operator_status($operator_id, $status){
    global $mydatabase;
    $timeof = date("YmdHis");
    $query = "UPDATE livehelp_operators 
              SET status='$status', lastaction='$timeof' 
              WHERE user_id='$operator_id'";
    $mydatabase->query($query);
}
```

### 3. **Operator Chat List Pattern**
```php
// Active chat listing
function get_operator_chats($operator_id){
    global $mydatabase;
    $query = "SELECT * FROM livehelp_users 
              WHERE operator_id='$operator_id' 
              AND status='active'";
    return $mydatabase->query($query);
}
```

### 4. **Operator Transfer Pattern**
```php
// Chat transfer between operators
function transfer_chat($channel_id, $from_operator, $to_operator){
    global $mydatabase;
    $query = "UPDATE livehelp_users 
              SET operator_id='$to_operator' 
              WHERE onchannel='$channel_id'";
    $mydatabase->query($query);
    
    // Log transfer
    log_transfer($channel_id, $from_operator, $to_operator);
}
```

## Visitor Interface Patterns

### 1. **Visitor Tracking Pattern**
```php
// Visitor page tracking
function track_visitor_page($sessionid, $page_url, $page_title){
    global $mydatabase;
    $timeof = date("YmdHis");
    $query = "INSERT INTO livehelp_visits 
              (sessionid, pageurl, pagetitle, timestamp) 
              VALUES ('$sessionid', '$page_url', '$page_title', '$timeof')";
    $mydatabase->insert($query);
}
```

### 2. **Auto-Invite Pattern**
```php
// Automatic invitation system
function check_auto_invite($sessionid, $department_id){
    global $mydatabase, $CSLH_Config;
    
    // Check if auto-invite is enabled
    if(!$CSLH_Config['auto_invite_enabled']){
        return false;
    }
    
    // Check visitor time on site
    $time_on_site = get_visitor_time_on_site($sessionid);
    if($time_on_site < $CSLH_Config['auto_invite_delay']){
        return false;
    }
    
    // Check if operator is available
    if(!is_operator_available($department_id)){
        return false;
    }
    
    // Send invitation
    send_invitation($sessionid, $department_id);
    return true;
}
```

### 3. **Department Assignment Pattern**
```php
// Department routing logic
function assign_department($sessionid){
    global $mydatabase, $CSLH_Config;
    
    // Get visitor information
    $visitor_info = get_visitor_info($sessionid);
    
    // Apply department rules
    foreach($CSLH_Config['department_rules'] as $rule){
        if(match_rule($visitor_info, $rule)){
            return $rule['department_id'];
        }
    }
    
    // Default department
    return $CSLH_Config['default_department'];
}
```

### 4. **Visitor Chat Initiation Pattern**
```php
// Visitor starts chat
function visitor_start_chat($sessionid, $department_id){
    global $mydatabase;
    
    // Create chat channel
    $channel_id = create_chat_channel();
    
    // Assign visitor to channel
    $query = "UPDATE livehelp_users 
              SET onchannel='$channel_id', department_id='$department_id' 
              WHERE sessionid='$sessionid'";
    $mydatabase->query($query);
    
    // Notify operators
    notify_operators_new_chat($channel_id, $department_id);
    
    return $channel_id;
}
```

## Real-Time Update Patterns

### 1. **Buffer-Streaming Pattern (Legacy)**
```php
// Original 1999-era buffer streaming
while($abort_counter != $abort_counter_end) {
    $buffer_html = showmessages($operator_id, "", $timeof, ""); 
    $buffer_layer = showmessages($operator_id, "writediv", $timeofDHTML, ""); 
    sleep(1); 
    
    if(($buffer_html != "") || ($buffer_layer != "")) {
        print $buffer_html;                    // Stream HTML fragment
        print $buffer_layer;                   // Stream typing layer
        ?><SCRIPT type="text/javascript">up(); setTimeout('up()',9);</SCRIPT><?php
        sendbuffer();                          // Flush to browser
    }
    $abort_counter++;
}
```

### 2. **Soft Refresh Pattern (Modern)**
```javascript
// Modern equivalent of buffer streaming
async function softRefresh() {
    try {
        const response = await fetch('/chat/fragment?since=' + lastTimestamp);
        const html = await response.text();

        if (html.trim() !== '') {
            document.querySelector('#chat-container').innerHTML += html;
            scrollToBottom();
            updateTypingIndicator();
        }
    } catch (e) {
        console.warn("Soft refresh failed, will retry");
    }
}

// Run every second - same as sleep(1) in original
setInterval(softRefresh, 1000);
```

### 3. **Typing Indicator Pattern (Modern)**
```javascript
// Modern replacement for DynLayer/writediv
function updateTypingIndicator(text) {
    const el = document.getElementById('typing-indicator');
    if (text) {
        el.style.display = 'block';
        el.textContent = text;
    } else {
        el.style.display = 'none';
    }
}
```

### 4. **Scroll Pattern (Modern)**
```javascript
// Modern replacement for up() function
function scrollToBottom() {
    const el = document.getElementById('chat-container');
    el.scrollTop = el.scrollHeight;
}
```

### 5. **Fallback Chain Pattern**
```javascript
// Progressive enhancement fallback chain
const updateMethods = [
    tryWebSocket,      // Tier 1: WebSockets
    trySSE,           // Tier 2: Server-Sent Events  
    tryLongPolling,   // Tier 3: Long-Polling
    tryShortPolling,  // Tier 4: Short-Polling
    trySoftRefresh,   // Tier 4.5: Soft Refresh (buffer-streaming equivalent)
    tryFullRefresh,   // Tier 5: Full Page Refresh
    tryStaticMode     // Tier 6: Static mode
];

// Try each method until one works
for (const method of updateMethods) {
    if (await method()) break;
}
```

## Chat Analytics Patterns

### 1. **Chat Statistics Pattern**
```php
// Chat performance metrics
function get_chat_statistics($department_id, $date_range){
    global $mydatabase;
    
    $query = "SELECT COUNT(*) as total_chats,
                     AVG(duration) as avg_duration,
                     AVG(response_time) as avg_response_time
              FROM livehelp_chats 
              WHERE department_id='$department_id' 
              AND chat_date BETWEEN '$date_start' AND '$date_end'";
    
    return $mydatabase->query($query);
}
```

### 2. **Keyword Extraction Pattern**
```php
// Chat keyword analysis
function extract_chat_keywords($chat_id){
    global $mydatabase;
    
    $query = "SELECT message FROM livehelp_messages 
              WHERE channel_id='$chat_id'";
    $messages = $mydatabase->query($query);
    
    $keywords = array();
    while($messages->next()){
        $message = $messages->getCurrentValueByKey('message');
        $keywords = array_merge($keywords, extract_keywords($message));
    }
    
    return array_count_values($keywords);
}
```

### 3. **Path Analysis Pattern**
```php
// Visitor path tracking
function analyze_visitor_paths($sessionid){
    global $mydatabase;
    
    $query = "SELECT pageurl, timestamp FROM livehelp_visits 
              WHERE sessionid='$sessionid' 
              ORDER BY timestamp ASC";
    $visits = $mydatabase->query($query);
    
    $path = array();
    while($visits->next()){
        $visit = $visits->getCurrentValuesAsHash();
        $path[] = array(
            'url' => $visit['pageurl'],
            'time' => $visit['timestamp']
        );
    }
    
    return $path;
}
```

### 4. **Conversion Tracking Pattern**
```php
// Chat conversion analysis
function track_chat_conversion($chat_id, $conversion_type){
    global $mydatabase;
    
    $query = "INSERT INTO livehelp_conversions 
              (chat_id, conversion_type, timestamp) 
              VALUES ('$chat_id', '$conversion_type', '$timeof')";
    $mydatabase->insert($query);
}
```

## Error Handling Patterns

### 1. **Chat Disconnection Pattern**
```php
// Handle chat disconnection
function handle_chat_disconnection($channel_id, $reason){
    global $mydatabase;
    
    // Update chat status
    $query = "UPDATE livehelp_users 
              SET status='disconnected', disconnect_reason='$reason' 
              WHERE onchannel='$channel_id'";
    $mydatabase->query($query);
    
    // Notify other participants
    notify_chat_disconnection($channel_id, $reason);
    
    // Log disconnection
    log_chat_event($channel_id, 'disconnection', $reason);
}
```

### 2. **Message Delivery Failure Pattern**
```php
// Handle message delivery failures
function handle_message_failure($message_id, $failure_reason){
    global $mydatabase;
    
    // Mark message as failed
    $query = "UPDATE livehelp_messages 
              SET status='failed', failure_reason='$failure_reason' 
              WHERE message_id='$message_id'";
    $mydatabase->query($query);
    
    // Attempt redelivery
    if($failure_reason == 'temporary'){
        schedule_redelivery($message_id);
    }
}
```

### 3. **Operator Timeout Pattern**
```php
// Handle operator inactivity
function handle_operator_timeout($operator_id){
    global $mydatabase;
    
    // Set operator as away
    $query = "UPDATE livehelp_operators 
              SET status='away' 
              WHERE user_id='$operator_id'";
    $mydatabase->query($query);
    
    // Transfer active chats
    transfer_active_chats($operator_id);
    
    // Notify supervisors
    notify_supervisor_timeout($operator_id);
}
```

### 4. **System Overload Pattern**
```php
// Handle system overload
function handle_system_overload(){
    global $mydatabase, $CSLH_Config;
    
    // Disable new chats
    $CSLH_Config['accept_new_chats'] = false;
    
    // Notify operators
    notify_system_overload();
    
    // Log overload event
    log_system_event('overload', 'System overloaded - new chats disabled');
}
```

---

**This chat doctrine represents the accumulated wisdom of 25 years of live chat system operation. These patterns have proven their reliability in production environments and must be preserved in all future development to maintain system stability and user experience quality.**

## 🎯 **Critical Update: Buffer-Streaming Discovery**

During the doctrine extraction process, a critical insight was discovered about the original Crafty Syntax real-time update mechanism:

### **The "Soft Refresh" Revelation**
The original `admin_chat_flush.php` was **not** performing page refreshes - it was implementing a **buffer-streaming loop** that:

1. **Streamed HTML fragments** directly into the living DOM
2. **Used `sendbuffer()`** to flush PHP output to browser  
3. **Executed inline JavaScript** (`up()`) for scrolling
4. **Slept server-side** (`sleep(1)`) for throttling
5. **Never reloaded the page** - continuously streamed updates

This was **proto-SSE before SSE existed** - a brilliant hack to achieve real-time updates in the 1999 web environment.

### **Modern Equivalent: Soft Refresh**
The modern "Soft Refresh" pattern preserves this exact behavior using contemporary web standards:

- **Legacy**: `sendbuffer()` → `print HTML` → `<script>up()</script>` → `sleep(1)` → repeat
- **Modern**: `fetch('/chat/fragment')` → `innerHTML += html` → `scrollToBottom()` → `setInterval(1000)`

### **Doctrine Alignment**
This discovery reinforces the core doctrines:
- **Chat Immortality**: Never lose the chat during updates
- **Real-Time Communication**: Continuous updates without interruption  
- **Operator Workflow**: Seamless interface without DOM reset
- **Graceful Degradation**: Multiple fallback tiers including Soft Refresh

### **Updated Fallback Chain**
```
WebSockets → SSE → Long-Polling → Short-Polling → Soft Refresh → Full Page Refresh → Static Mode
```

The **Soft Refresh** tier (4.5) specifically preserves the original buffer-streaming philosophy using modern primitives.

**This insight ensures future development respects both the intent AND the innovative spirit of the original implementation.**

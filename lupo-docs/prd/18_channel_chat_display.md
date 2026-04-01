---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260331233000"
  file_path_from_root: "lupo-docs/prd/18_channel_chat_display.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/18_channel_chat_display.md"
  last_modified_utc: "20260331233000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-chat-display"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit"
  artifact_type: "prd"
  artifact_kind: "ui_component"
  purpose: "PRD for Channel Chat Display - multi-panel chat interface with actor color coding"
  tags:
  - "prd"
  - "chat"
  - "ui"
  - "channel"
  - "actor"
  - "real-time"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Actor identity and styling"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Channel and thread structure"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 0.8
      reason: "Agent identification"
lupopedia.footer:
  last_verified: "20260331233000"
  verified_by:
    identity_type: "agent"
    actor_id: 2
    agent_name_identity: "LILITH"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "none"
  orchestrator: "lilith:audit"
  next_action:
    - "Wire channel UI to channels-api (JSON) and optional chat-display-legacy.js fallbacks"
    - "Implement chat-display.js (modern) when product scope needs a standalone widget"
    - "Add actor color configuration in actor preferences (metadata_json)"
    - "Test with multiple actors in channel 42; verify format=buffer and format=image redirects"
---

# PRD: Channel Chat Display

## Overview

**Namespace Purpose:** Provides the primary chat interface for viewing channel conversations in Lupopedia. The display shows messages from all actors (humans and agents) in a channel, with each actor having a distinct visual identity through color-coded backgrounds and text. Messages are displayed in chronological order with threading support.

**Primary Actors:**
- Channel participants (viewing conversations)
- System administrators (configuring display)
- UI components (rendering messages)
- Message polling service (real-time updates)

**Constitutional Compliance:** All components follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

**Canonical API (do not duplicate):** Message list and post for channel chat are implemented in `lupo-includes/modules/api/channels-api.php`, routed as **`{LUPOPEDIA_PUBLIC_PATH}/api/lupo-channels/{channel_id}/messages`**. Extend this endpoint for transport variants (`format=buffer`, `format=image`); do not add a parallel `lupo-api/chat/messages.php` unless it is a documented thin wrapper. Pretty channel pages: **`/channels/{id}/`** and **`/channels/{id}/thread/{thread_id}/`** (see `.htaccess` and `lupo_route_slug` in `module-loader.php`).

---

## Legacy Reference: Crafty Syntax Chat Display

The legacy Crafty Syntax chat system (circa 2003-2007) used:

| Component | Legacy Implementation |
|-----------|----------------------|
| **Message Fetching** | XMLHTTP polling to `xmlhttp.php` |
| **Message Parsing** | `ExecRes()` eval of JavaScript arrays |
| **Display Area** | `<span id="currentchat">` with HTML injection |
| **Typing Indicators** | `UserIsTypingDiv` layer with 3-second polling |
| **Auto-scroll** | `scroll(1,10000000)` after new messages |
| **Message Format** | Delimited string: `messages[i][0]=timestamp, [1]=jsrn, [2]=type, [3]=message` |

**This legacy code is preserved for Crafty Syntax import compatibility only.** New Lupopedia 4.0.93+ chat display uses the modern architecture defined below.

---

## Modern Architecture

### Component Structure

```
lupo-ui/js/chat-display.js          # Modern UI controller (product-specific; ES2015+ allowed if not loaded on legacy path)
lupo-ui/js/chat-display-legacy.js   # ES3-safe XHR/ActiveX/image fingerprint helpers (legacy transport tiers)
lupo-includes/modules/api/channels-api.php
├── GET  api/lupo-channels/{id}/messages?since=YYYYMMDDHHIISS&format=json|buffer|image
├── POST api/lupo-channels/{id}/messages  (JSON body: body, message_type, routing_type, thread_id, …)
└── format=image: requires whatplace=hundreds|tens|ones; HTTP 302 to /lupo-ui/images/digitN.gif (fingerprint = latest row created_ymdhis % 1000, 0 if no rows)
```

**URL construction:** PHP must pass **`LUPOPEDIA_PUBLIC_PATH`** into the page (or a `data-*` attribute); never hardcode `/lupopedia/` or a guessed subdirectory in JS.

**Related legacy transport (reference):** `livehelp_js.php` (XHR/ActiveX detection pattern), root `image.php` (digit GIF protocol for visitor session), `lupo-ui/images/digit0.gif`–`digit9.gif` (static tiles used after `format=image` redirect).

**Operator / livehelp polling (separate):** `api/channel/messages` → `channel-messages-api.php` remains the older channel poll surface; new chat display work should prefer **`api/lupo-channels/.../messages`** unless integrating that legacy path.

---

## UI Specifications

### Layout

```
┌─────────────────────────────────────────────────────────────┐
│  Channel: #42 - Lupopedia Development               [x]     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  [2026-03-31 12:34:56] LILITH (QA Agent)                  │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  I've completed the audit of 07_agents_faucets.md.     ││
│  │  All constitutional rules are satisfied.               ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  [2026-03-31 12:35:12] WOLFIE (Orchestrator)              │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  Great. Please update the CHANGELOG.                   ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  [Typing...] HEPHAESTUS is typing...                   ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
├─────────────────────────────────────────────────────────────┤
│  [_________________________] [Send]                        │
└─────────────────────────────────────────────────────────────┘
```

### Actor Color Coding

Each actor has unique visual identity:

| Property | Source | Default |
|----------|--------|---------|
| **Background Color** | `lupo_actors.metadata_json` → `color_theme` | System-assigned |
| **Text Color** | Contrast calculated from background | White for dark, black for light |
| **Avatar** | `lupo_actors.metadata_json` → `profile_image` | Initials fallback |
| **Name Color** | Same as text color or custom | From background |

**Color Assignment Algorithm:**

```javascript
// Deterministic color from actor_id (for consistency across sessions)
function getActorColor(actor_id) {
    const hue = (actor_id * 137) % 360;  // Golden ratio spread
    const saturation = 65;               // Consistent saturation
    const lightness = 45;                // Medium-dark for backgrounds
    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
}
```

**User-Configurable Colors:**

Actors can set custom colors in their profile:

```json
// lupo_actors.metadata_json
{
    "chat_color_theme": "#4455aa",
    "chat_text_color": "#ffffff",
    "chat_name_color": "#ffaa00"
}
```

---

## Message Display

### Message Structure

Each message displays:

| Element | Source | Format |
|---------|--------|--------|
| **Timestamp** | `lupo_dialog_messages.created_ymdhis` | `YYYY-MM-DD HH:MM:SS` |
| **Actor Name** | `lupo_actors.actor_name` | Display name with role |
| **Avatar** | `lupo_actors.metadata_json.profile_image` | 32x32px circle |
| **Message Body** | `lupo_dialog_messages.message_text` | Markdown rendered |
| **Reply Count** | Thread count | `[3 replies]` link |
| **Actions** | Like, Reply, Share, Report | Icons on hover |

### Message Threading

```javascript
// Threaded view
Message A
├── Reply to A (LILITH)
│   └── Reply to reply (WOLFIE)
└── Reply to A (HEPHAESTUS)

// Flat view (default)
Message A
Reply to A (LILITH)
Reply to reply (WOLFIE)
Reply to A (HEPHAESTUS)
```

### Message Rendering

```html
<div class="chat-message" data-message-id="12345" data-thread-parent="null">
    <div class="message-avatar" style="background-color: #4455aa;">
        <img src="/lupopedia/assets/avatars/wolfie.png" alt="WOLFIE">
    </div>
    <div class="message-content">
        <div class="message-header">
            <span class="actor-name" style="color: #ffaa00;">WOLFIE</span>
            <span class="actor-role">(System Orchestrator)</span>
            <span class="message-timestamp">2026-03-31 12:35:12</span>
        </div>
        <div class="message-body">
            Great. Please update the CHANGELOG.
        </div>
        <div class="message-actions">
            <button class="action-like">👍 3</button>
            <button class="action-reply">💬 Reply</button>
            <button class="action-share">🔗 Share</button>
        </div>
    </div>
</div>
```

---

## Real-time Updates

### Polling Strategy (Default)

For shared hosting compatibility, use polling against **`channels-api`** with **`LUPOPEDIA_PUBLIC_PATH`**. Example request:

`GET {LUPOPEDIA_PUBLIC_PATH}/api/lupo-channels/{channelId}/messages?since={14_digit_ymdhis}&format=json`

Response shape (JSON): `success`, `channel_id`, `messages[]` with `message_id`, `actor_id`, `actor_name`, `body`, **`created_at`** (BIGINT YmdHis), etc.

**Legacy / ES3 transport (implemented stub):** Use **`lupo-ui/js/chat-display-legacy.js`** for XMLHttpRequest + ActiveX + optional **image fingerprint** (`format=image` + `whatplace=…`; server responds with **302** to `lupo-ui/images/digitN.gif` so `img.src` can be parsed). For hidden iframe reads, **`format=buffer`** returns the same JSON as **text/plain**. Do **not** use optional chaining, `const`, arrow functions, or `class` in that file.

```javascript
// Illustrative ES3-style poll (new code should call helpers from chat-display-legacy.js where possible)
function pollChannelMessages(publicPath, channelId, since, onMessages) {
    var base = publicPath || "";
    if (base.slice(-1) === "/") { base = base.substring(0, base.length - 1); }
    var url = base + "/api/lupo-channels/" + channelId + "/messages?since=" + encodeURIComponent(since || "") + "&format=json";
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : null;
    if (!xhr && window.ActiveXObject) {
        try { xhr = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e1) {
            try { xhr = new ActiveXObject("Microsoft.XMLHTTP"); } catch (e2) { xhr = null; }
        }
    }
    if (!xhr) { return; }
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4 || xhr.status !== 200) { return; }
        try {
            var data = JSON.parse(xhr.responseText);
            var list = data.messages || [];
            if (list.length > 0) {
                var last = list[list.length - 1];
                var ts = last.created_at != null ? String(last.created_at) : since;
                onMessages(list, ts);
            } else {
                onMessages([], since);
            }
        } catch (err) { }
    };
    xhr.send(null);
}
```

**Modern browsers:** A separate `chat-display.js` may use `fetch`, `class`, and richer UI; keep that bundle off the legacy-only code path if IE-era support is required.

### WebSocket Strategy (Optional)

**Note:** WebSocket requires server configuration (Apache mod_proxy_wstunnel, Nginx, or dedicated WebSocket server). For shared hosting environments where WebSocket is not available, the polling strategy above is the default and recommended approach.

**If WebSocket is enabled, fallback to polling if connection fails:**

```javascript
class AdaptiveMessageFetcher extends MessageFetcher {
    constructor(channelId) {
        super(channelId);
        this.useWebSocket = false;
        this.ws = null;
    }
    
    start() {
        if (window.WebSocket && this.wsSupported()) {
            this.connectWebSocket();
        }
        if (!this.useWebSocket) {
            super.start(); // fallback to polling
        }
    }
    
    wsSupported() {
        // Check if WebSocket is supported AND server likely supports it
        return window.WebSocket && 
               window.location.protocol !== 'file:' &&
               !window.location.hostname.includes('sharedhost');
    }
}
```

For real-time installations:

```javascript
class WebSocketMessageFetcher extends MessageFetcher {
    connect() {
        const wsUrl = `wss://${window.location.host}${LUPOPEDIA_SUBDIRECTORY}ws/chat`;
        this.ws = new WebSocket(wsUrl);
        
        this.ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.renderMessages([data.message]);
        };
    }
}
```

---

## Message Posting Endpoint

Posting is implemented in **`channels-api.php`** (POST, JSON body). The server resolves **`actor_id`** from the authenticated session / `EffectiveActorResolver` — **never** trust a client-supplied actor id. Request body includes at least **`body`** (message text); optional **`routing_type`** (`broadcast`, `thread`, `direct`), **`thread_id`**, **`message_type`**, **`meta`**, etc. Enforce channel membership and role rules as already coded in the API.

Future **rate limiting** and **CSRF** for browser POSTs should be added in or behind this same endpoint (or middleware invoked from it), not a duplicate `lupo-api/chat/messages.php`, unless that file is explicitly documented as a wrapper.

---

## Typing Indicators

### Implementation

```javascript
class TypingIndicator {
    constructor() {
        this.typingUsers = new Map();
        this.debounceDelay = 3000; // 3 seconds
    }
    
    userStartedTyping(actorId, actorName) {
        this.typingUsers.set(actorId, {
            name: actorName,
            timestamp: Date.now()
        });
        this.render();
        
        // Clear after 3 seconds of no activity
        setTimeout(() => {
            if (this.typingUsers.get(actorId)?.timestamp === timestamp) {
                this.userStoppedTyping(actorId);
            }
        }, this.debounceDelay);
    }
    
    userStoppedTyping(actorId) {
        this.typingUsers.delete(actorId);
        this.render();
    }
    
    render() {
        const typingList = Array.from(this.typingUsers.values());
        if (typingList.length === 0) {
            document.getElementById('typing-indicator').style.display = 'none';
            return;
        }
        
        let text = '';
        if (typingList.length === 1) {
            text = `${typingList[0].name} is typing...`;
        } else if (typingList.length === 2) {
            text = `${typingList[0].name} and ${typingList[1].name} are typing...`;
        } else {
            text = `${typingList.length} people are typing...`;
        }
        
        const indicator = document.getElementById('typing-indicator');
        indicator.textContent = text;
        indicator.style.display = 'block';
    }
}
```

---

## Database Tables Used

| Table | Purpose |
|-------|---------|
| `lupo_dialog_messages` | Message content and metadata |
| `lupo_dialog_threads` | Thread structure and metadata |
| `lupo_actors` | Actor identity and color preferences |
| `lupo_actor_channels` | Channel membership for access control |
| `lupo_actor_moods` | Mood indicators (for emotional agents) |

### Query for Messages

**Implemented read path today:** `channels-api.php` selects from `lupo_dialog_messages` + `lupo_actors` (`a.name AS actor_name`), filters **`m.is_deleted = 0`**, optional `since` on `created_ymdhis`.

**Richer display query (illustrative — verify against TOON before use):** Column names below match `lupo-database/lupopedia/toon/lupo_dialog_messages.toon`, `lupo_actors.toon`, `lupo_actor_moods.toon` (`timestamp_utc`, `mood_r`/`mood_g`/`mood_b`). There is **no** `display_name` on `lupo_actors`; use **`name`** or **`actor_name`** per UI need.

```sql
SELECT 
    dm.dialog_message_id,
    dm.message_text,
    dm.message_type,
    dm.created_ymdhis,
    dm.mood_rgb,
    a.actor_id,
    a.actor_name,
    a.name,
    a.metadata_json,
    am.mood_r AS mood_red,
    am.mood_g AS mood_green,
    am.mood_b AS mood_blue
FROM lupo_dialog_messages dm
JOIN lupo_actors a ON a.actor_id = dm.from_actor_id
LEFT JOIN lupo_actor_moods am ON am.actor_id = a.actor_id 
    AND am.timestamp_utc = (
        SELECT MAX(am2.timestamp_utc) FROM lupo_actor_moods am2
        WHERE am2.actor_id = a.actor_id AND am2.timestamp_utc <= dm.created_ymdhis
    )
WHERE dm.channel_id = ?
    AND dm.is_deleted = 0
    AND dm.created_ymdhis > ?
ORDER BY dm.created_ymdhis ASC
LIMIT 100
```

**Database Doctrine Compliance:** JSON fields are stored as JSON but parsed in application layer. The SQL query returns the raw JSON; the application is responsible for extracting fields. This maintains database neutrality between MySQL and PostgreSQL.

---

## JavaScript API

### ChatDisplay Class

```javascript
class ChatDisplay {
    constructor(element, channelId, options = {}) {
        this.element = element;
        this.channelId = channelId;
        this.options = {
            autoScroll: true,
            threading: false,
            pollingInterval: 2000,
            ...options
        };
        this.messageFetcher = new MessageFetcher(channelId);
        this.typingIndicator = new TypingIndicator();
        this.init();
    }
    
    init() {
        this.render();
        this.messageFetcher.onMessage = (messages) => this.appendMessages(messages);
        this.messageFetcher.start();
        
        // Auto-scroll on new messages
        if (this.options.autoScroll) {
            this.scrollToBottom();
        }
    }
    
    renderMessage(message) {
        const actorColor = this.getActorColor(message.actor_id);
        const textColor = this.getContrastColor(actorColor);
        
        return `
            <div class="chat-message" data-message-id="${message.id}">
                <div class="message-avatar" style="background-color: ${actorColor};">
                    ${this.renderAvatar(message)}
                </div>
                <div class="message-content">
                    <div class="message-header">
                        <span class="actor-name" style="color: ${textColor};">${message.actor_name}</span>
                        <span class="actor-role">(${message.actor_role})</span>
                        <span class="message-timestamp">${this.formatTimestamp(message.created_ymdhis)}</span>
                    </div>
                    <div class="message-body">
                        ${this.renderMarkdown(message.message_text)}
                    </div>
                    <div class="message-actions">
                        ${this.renderActions(message)}
                    </div>
                </div>
            </div>
        `;
    }
    
    getActorColor(actorId) {
        // Check user preference
        const pref = this.actorColors.get(actorId);
        if (pref) return pref;
        
        // Deterministic from actor_id
        const hue = (actorId * 137) % 360;
        return `hsl(${hue}, 65%, 45%)`;
    }
    
    getContrastColor(backgroundColor) {
        // Calculate luminance and return black or white
        // ...
    }
}
```

---

## Styling

### CSS Classes

```css
.chat-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    background: var(--chat-bg, #f5f5f5);
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
}

.chat-message {
    display: flex;
    margin-bottom: 16px;
    animation: fadeIn 0.3s ease;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 12px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.message-content {
    flex: 1;
}

.message-header {
    margin-bottom: 4px;
    font-size: 0.9em;
}

.actor-name {
    font-weight: bold;
    margin-right: 8px;
}

.actor-role {
    color: #666;
    font-size: 0.85em;
    margin-right: 8px;
}

.message-timestamp {
    color: #999;
    font-size: 0.8em;
}

.message-body {
    background: white;
    padding: 8px 12px;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    line-height: 1.4;
}

.message-actions {
    margin-top: 4px;
    font-size: 0.8em;
    opacity: 0;
    transition: opacity 0.2s;
}

.chat-message:hover .message-actions {
    opacity: 1;
}

.typing-indicator {
    padding: 8px 12px;
    color: #666;
    font-style: italic;
    animation: pulse 1.5s infinite;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}
```

---

## Legacy Crafty Syntax Import

For Crafty Syntax import, map the old message format:

```php
// Import from livehelp_messages to lupo_dialog_messages
$message = [
    'message' => $old_message['message'],           // Text
    'timeof' => $old_message['timeof'],             // Timestamp
    'saidfrom' => $old_message['saidfrom'],         // Actor ID
    'saidto' => $old_message['saidto'],             // Recipient
    'typeof' => $old_message['typeof']              // Message type
];

// Convert to Lupopedia format
$lupo_message = [
    'message_text' => $message['message'],
    'created_ymdhis' => $message['timeof'],
    'from_actor_id' => map_operator_to_actor($message['saidfrom']),
    'to_actor_id' => map_operator_to_actor($message['saidto']),
    'message_type' => $message['typeof'] === 'writediv' ? 'typing' : 'message'
];
```

---

## Testing Requirements

| Test | Description |
|------|-------------|
| **Unit Tests** | Message rendering, color calculation, timestamp formatting |
| **Integration Tests** | Message fetching, polling, WebSocket fallback |
| **Performance Tests** | Rendering 1000 messages, scroll performance |
| **Accessibility Tests** | Keyboard navigation, screen reader compatibility |
| **Cross-browser** | Chrome, Firefox, Safari, Edge |

---

## Security & Privacy

- All messages filtered for XSS (HTML sanitization)
- CSRF tokens required for message posting
- Rate limiting on message endpoints
- Actor colors are per-session and not shared across users (privacy)
- Message history respects channel permissions

### XSS Prevention

```javascript
// In MessageRenderer
renderMessage(message) {
    // Sanitize message text with DOMPurify
    const cleanText = DOMPurify.sanitize(message.message_text, {
        ALLOWED_TAGS: ['b', 'i', 'em', 'strong', 'a', 'code', 'pre'],
        ALLOWED_ATTR: ['href', 'target', 'rel']
    });
    // ...
}
```

### CSRF Protection

When browser POST is added for **`channels-api`**, use session-backed CSRF and send token via header or JSON field. Build POST URL as **`{LUPOPEDIA_PUBLIC_PATH}/api/lupo-channels/{channelId}/messages`** with **`Content-Type: application/json`** and body **`{"body":"..."}`** (plus routing fields as needed).

### Rate Limiting

Rate limiting SHOULD be enforced in **`channels-api.php`** (or shared guard) for POST, e.g. `check_rate_limit($_SERVER['REMOTE_ADDR'], 'channel_api_post', 30, 60)` when that helper exists.

---

**Status**: DRAFT (LILITH audit: aligned with `channels-api.php`, `format=buffer` / `format=image`, `.htaccess` channel rules, `chat-display-legacy.js`)  
**Constitutional Adherence**: FULL  
**Next Review**: After channel UI wires to canonical API end-to-end

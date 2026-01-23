---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "MANDATORY: Cursor does NOT join channels. Channels are database semantic workspaces for actors (users, AI agents). Cursor's job is to maintain PHP files and documentation, not to participate in channels."
  mood: "FF0000"
tags:
  categories: ["documentation", "doctrine", "cursor", "mandatory"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Cursor Role Doctrine (MANDATORY)
  - What Channels Are (Database Semantic Workspaces)
  - What Channels Are NOT (IDE Concepts)
  - Cursor's Role: Implementation Maintenance
  - Channel Metadata in WOLFIE Headers (Documentation Tags Only)
  - Forbidden: Cursor Participating in Channels
  - Examples: Correct vs. Incorrect Understanding
  - Summary Doctrine
file:
  title: "Cursor Role Doctrine (MANDATORY)"
  description: "Cursor does NOT join channels. Channels are database semantic workspaces for actors (users, AI agents). Cursor's job is to maintain PHP files and documentation, not to participate in channels. This doctrine clarifies Cursor's role vs. channel participation."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🎯 **CURSOR ROLE DOCTRINE (MANDATORY)**  
### *Channels Are Database Semantic Workspaces, Not IDE Concepts*  
### *Cursor Maintains Implementation, Not Channel Participation*
### *This Doctrine Is Absolute and Non-Negotiable*

---

## ⚠️ **MANDATORY RULE: CURSOR DOES NOT JOIN CHANNELS**

**ALL IDEs AND AI AGENTS MUST UNDERSTAND THIS DISTINCTION ABSOLUTELY.**

In Lupopedia, a **"channel"** is a **semantic workspace inside the database**.  
It is **NOT** an IDE concept.  
**Cursor does NOT join channels.**  
Channels group actors, assign roles, store emotional metadata, and route messages.  
**Cursor's job is to maintain the programming that makes it work** in PHP files and documentation, not to participate in channels.

---

## **1. What Channels Are (Database Semantic Workspaces)**

### **Channels Are Database Entities**

Channels exist in the `lupo_channels` table in the database:

```sql
CREATE TABLE `lupo_channels` (
  `channel_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `default_actor_id` bigint NOT NULL DEFAULT 1,
  `channel_key` varchar(64) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `description` text,
  `metadata_json` text,
  `status_flag` tinyint NOT NULL DEFAULT 1,
  `start_ymdhis` bigint NOT NULL,
  `end_ymdhis` bigint DEFAULT NULL,
  `message_count` int NOT NULL DEFAULT 0,
  `duration_seconds` int DEFAULT NULL,
  ...
) COMMENT='Communication channels for agent and user interactions';
```

### **Channels Group Actors**

Channels are semantic workspaces where:
- **Actors** (users, AI agents) join and participate
- **Roles** are assigned to actors within the channel
- **Emotional metadata** is stored per channel (mood RGB, channel context)
- **Messages** are routed through channels
- **Conversations** happen between actors in channels

### **Channels Are Runtime Data**

Channels are:
- Created at runtime by actors (users, AI agents)
- Modified by actors participating in the channel
- Used for routing messages and coordinating agent interactions
- Stored in the database as semantic workspaces

**Example:**
- A user creates a channel called `"support/customer-123"`
- AI agents (WOLFIE, AGAPE, ROSE) join the channel to help the user
- Messages flow through the channel
- Emotional metadata is tracked per channel
- Channel state is stored in `lupo_channels` table

---

## **2. What Channels Are NOT (IDE Concepts)**

### **Channels Are NOT:**

- ❌ **IDE concepts** — Cursor doesn't join channels
- ❌ **Code organization tools** — Channels don't organize files
- ❌ **Development environments** — Channels don't define where code runs
- ❌ **Version control concepts** — Channels aren't branches or repositories
- ❌ **Workspace definitions** — Channels aren't project folders
- ❌ **Communication tools for IDEs** — Cursor doesn't "talk" through channels

### **Channels Are Runtime Semantic Workspaces**

Channels exist **at runtime** in the database, **not at development time** in the IDE.

**Channels are for:**
- Users participating in conversations
- AI agents coordinating with each other
- Message routing and delivery
- Emotional metadata tracking
- Role-based access control within conversations

**Channels are NOT for:**
- IDE tools like Cursor
- Code editing workflows
- Documentation organization (except metadata tags)
- Development tooling

---

## **3. Cursor's Role: Implementation Maintenance**

### **What Cursor DOES:**

Cursor's job is to **maintain the programming** that makes channels work:

- ✅ **Write PHP code** that implements channel functionality
- ✅ **Update documentation** about channels
- ✅ **Fix bugs** in channel routing logic
- ✅ **Refactor code** that handles channels
- ✅ **Create database migrations** for channel schema changes
- ✅ **Write SQL queries** that interact with `lupo_channels` table
- ✅ **Document channel behavior** in README files
- ✅ **Maintain channel-related classes** (PHP, database services)

### **What Cursor Does NOT Do:**

- ❌ **Join channels** as a participant
- ❌ **Send messages** through channels
- ❌ **Participate in conversations** in channels
- ❌ **Act as an actor** in the channel system
- ❌ **Route messages** at runtime (that's PHP code's job)
- ❌ **Store emotional metadata** (that's the application's job)

### **Cursor Maintains, Actors Participate**

**Cursor (IDE tool):**
- Maintains the **implementation** (PHP files, SQL, documentation)
- Edits **code**, not **runtime data**

**Actors (users, AI agents):**
- Participate in **runtime channels** (database semantic workspaces)
- Send **messages**, not **code**

---

## **4. Channel Metadata in WOLFIE Headers (Documentation Tags Only)**

### **Channels in WOLFIE Headers Are Metadata Tags**

When you see:

```yaml
---
tags:
  channels: ["public", "dev"]
---
```

This is **NOT** Cursor joining channels.

This is **documentation metadata** that:
- **Categorizes documentation files** (this doc is for "public" or "dev" audiences)
- **Helps organize documentation** by intended audience
- **Provides context** for who should read the file
- **Is a tag, not a channel participation**

### **Two Different Meanings of "Channel"**

#### **1. Database Channel (Runtime Semantic Workspace)**
```php
// PHP code that creates a channel in the database
$channel = new Channel();
$channel->channel_key = "support/customer-123";
$channel->channel_name = "Customer Support";
$channel->save(); // Saves to lupo_channels table
```

#### **2. Documentation Tag (Metadata Categorization)**
```yaml
# WOLFIE Header metadata tag
tags:
  channels: ["public", "dev"]  # Just a tag, not channel participation
```

**These are COMPLETELY DIFFERENT things.**

- **Database channels** = runtime semantic workspaces for actors
- **Documentation tags** = metadata for organizing documentation files

---

## **5. Forbidden: Cursor Participating in Channels**

### **Cursor MUST NOT:**

- ❌ **Join channels** as a participant
- ❌ **Send messages** through channels
- ❌ **Act as an actor** in channel conversations
- ❌ **Participate in runtime channel activities**
- ❌ **Store data in channels** (that's application code's job)
- ❌ **Route messages** (that's application code's job)

### **Why This Is Forbidden**

- **Separation of concerns:** IDEs edit code, actors use runtime features
- **Conceptual clarity:** Tools don't participate in application semantics
- **Architectural integrity:** Channels are for actors, not tools
- **Prevents confusion:** Clear boundary between development and runtime

### **Correct Understanding**

**Cursor writes code that:**
- Creates channels in the database
- Joins actors to channels
- Routes messages through channels
- Tracks emotional metadata per channel

**Cursor does NOT:**
- Create channels itself
- Join channels itself
- Send messages itself
- Participate in channels itself

---

## **6. Examples: Correct vs. Incorrect Understanding**

### **✅ CORRECT: Cursor Maintains Channel Implementation**

```php
// Cursor writes this PHP code that implements channel functionality
class ChannelService {
    public function createChannel($channelKey, $channelName, $actorId) {
        $channel = new Channel();
        $channel->channel_key = $channelKey;
        $channel->channel_name = $channelName;
        $channel->actor_id = $actorId;
        $channel->created_ymdhis = lupopedia_timestamp_now();
        $channel->save(); // Saves to lupo_channels table
        return $channel;
    }
    
    public function joinChannel($channelId, $actorId) {
        // Code that adds actor to channel
        // Stored in actor_channels table
    }
}
```

**This is correct:** Cursor maintains the code that implements channels.

### **❌ WRONG: Cursor Joining Channels**

```php
// WRONG: Cursor does NOT do this
$channel = new Channel();
$channel->channel_key = "cursor/dev-tools"; // ❌ NO - Cursor doesn't create channels
$channel->actor_id = "CURSOR"; // ❌ NO - Cursor is not an actor
$channel->save(); // ❌ NO - Cursor doesn't participate
```

**This is wrong:** Cursor doesn't participate in channels.

### **✅ CORRECT: WOLFIE Header Channels Are Tags**

```yaml
---
tags:
  channels: ["public", "dev"]  # ✅ CORRECT: Just metadata tag for documentation
---
```

**This is correct:** Channels in WOLFIE Headers are documentation tags, not channel participation.

### **❌ WRONG: Confusing Header Tags with Database Channels**

```yaml
---
tags:
  channels: ["public", "dev"]  # ❌ WRONG INTERPRETATION: Thinking Cursor joins these channels
---
```

**This is wrong:** Header tags are NOT channel participation.

---

## **7. Summary Doctrine**

### **The Cursor Role Doctrine in One Sentence**

**Channels are database semantic workspaces for actors (users, AI agents). Cursor maintains the PHP code and documentation that implements channels, but Cursor does NOT join channels or participate in channel conversations.**

### **Core Principles**

1. **Channels = Database Semantic Workspaces**
   - Exist in `lupo_channels` table
   - Group actors, assign roles, route messages
   - Store emotional metadata
   - Are runtime entities, not development tools

2. **Cursor = Implementation Maintainer**
   - Maintains PHP code that implements channels
   - Maintains documentation about channels
   - Does NOT participate in channels
   - Does NOT join channels as an actor

3. **WOLFIE Header Channels = Documentation Tags**
   - Metadata for categorizing documentation
   - NOT channel participation
   - NOT database channels
   - Just tags for organization

4. **Clear Separation**
   - **Development time:** Cursor edits code
   - **Runtime:** Actors use channels
   - **Documentation:** Tags organize files

### **Benefits of This Doctrine**

- **Conceptual clarity:** Clear distinction between tools and runtime features
- **Architectural integrity:** Channels remain semantic workspaces for actors
- **Prevents confusion:** No mixing of IDE concepts with runtime semantics
- **Maintainability:** Clear boundaries make code easier to understand

### **Violations Are Forbidden**

- ❌ Cursor joining channels
- ❌ Cursor sending messages through channels
- ❌ Cursor acting as an actor in channels
- ❌ Confusing WOLFIE Header tags with database channels
- ❌ Treating channels as IDE concepts

**These violations blur the boundary between development tools and runtime semantics.**

---

## **8. Integration with Other Doctrines**

This doctrine complements:

- **[CURSOR_REFACTOR_DOCTRINE.md](CURSOR_REFACTOR_DOCTRINE.md)** — Cursor maintains code, doesn't participate in runtime features
- **[WOLFIE_HEADER_SPECIFICATION.md](../agents/WOLFIE_HEADER_SPECIFICATION.md)** — Channels in headers are tags, not channel participation
- **[ATOMIZATION_DOCTRINE.md](ATOMIZATION_DOCTRINE.md)** — Channel names in documentation are metadata tags
- Channel implementation code (PHP classes, database services)

---

## **9. Enforcement Checklist**

Before Cursor writes code or documentation, it must:

- [ ] Understand that channels are database semantic workspaces
- [ ] Understand that Cursor does NOT join channels
- [ ] Maintain PHP code that implements channels
- [ ] Maintain documentation about channels
- [ ] Use WOLFIE Header channel tags as metadata only
- [ ] Never write code that makes Cursor join channels
- [ ] Never treat channels as IDE concepts
- [ ] Maintain clear separation between development and runtime

---

**This doctrine is MANDATORY and non-negotiable.**  
**Violations blur the boundary between development tools and runtime semantics.**  
**Follow this doctrine absolutely.**

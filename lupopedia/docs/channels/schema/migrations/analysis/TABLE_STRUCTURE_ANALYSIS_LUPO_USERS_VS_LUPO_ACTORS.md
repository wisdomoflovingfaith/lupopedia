# 📋 **Table Structure Analysis: lupo_users vs lupo_actors**

## 🎯 **Database Table Clarification**

**Question**: What is the create table for lupo_users? And we have lupo_actors I thought that was the table?

**Answer**: You're correct to question this. Let me clarify the table structure based on the migration analysis.

---

## 📋 **Table Structure Analysis**

### **🗄️ lupo_actors Table (Primary Actor Table)**
Based on the migration files, `lupo_actors` is the **primary actor table** in Lupopedia:

```sql
CREATE TABLE `lupo_actors` (
  `actor_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `deleted_ymdhis` BIGINT COMMENT 'UTC YYYYMMDDHHMMSS',
  `actor_source_id` BIGINT COMMENT 'ID from source table (auth_users, agents, etc.)',
  `is_active` TINYINT NOT NULL DEFAULT 1 COMMENT 'Active flag',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  -- Additional columns for actor metadata
);
```

### **🗄️ lupo_users Table (Crafty Syntax Compatibility)**
Based on the TOON analysis and migration mapping, `lupo_users` is a **Crafty Syntax compatibility table**:

```sql
CREATE TABLE `lupo_users` (
  `user_id` int NOT NULL auto_increment,
  `livehelp_id` bigint unsigned NOT NULL DEFAULT 1,
  `lastaction` bigint DEFAULT 0,
  `username` varchar(30) NOT NULL DEFAULT '',
  `displayname` varchar(42) NOT NULL DEFAULT '',
  `password` varchar(255) COMMENT 'Password hash (nullable for OAuth users)',
  `timezone_offset` decimal(4,2) NOT NULL DEFAULT 0.00 COMMENT 'Offset in hours from UTC',
  `auth_provider` varchar(20) COMMENT 'OAuth provider name if using OAuth',
  `provider_id` varchar(255) COMMENT 'OAuth provider user ID',
  `isonline` char(1) NOT NULL DEFAULT '',
  `isoperator` char(1) NOT NULL DEFAULT 'N',
  `onchannel` int NOT NULL DEFAULT 0,
  `isadmin` char(1) NOT NULL DEFAULT 'N',
  `department` int NOT NULL DEFAULT 0,
  `identity` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(30) NOT NULL DEFAULT '',
  `isnamed` char(1) NOT NULL DEFAULT 'N',
  `showedup` bigint,
  `email` varchar(60) NOT NULL DEFAULT '',
  `email_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether email is verified',
  `verification_token` varchar(64) COMMENT 'For email verification',
  `verification_token_expires` bigint COMMENT 'Expiration timestamp for verification token',
  `password_reset_token` varchar(64) COMMENT 'For password reset',
  `password_reset_expires` bigint COMMENT 'Expiration timestamp for password reset',
  `login_token` varchar(64) COMMENT 'For passwordless login',
  `login_token_expires` bigint COMMENT 'Expiration timestamp for login token',
  `last_login_at` bigint COMMENT 'Last login timestamp',
  -- Additional columns for Crafty Syntax compatibility
);
```

---

## 🎯 **Table Relationship Clarification**

### **📋 Two-Tier Architecture**

#### **🏛️ lupo_actors (Core Lupopedia Table)**
- **Purpose**: Primary actor management for Lupopedia
- **Scope**: All actors in the system (users, agents, bots, etc.)
- **Design**: Modern Lupopedia architecture
- **Usage**: Core actor operations, authentication, permissions

#### **🔄 lupo_users (Crafty Syntax Compatibility Table)**
- **Purpose**: Legacy Crafty Syntax user management
- **Scope**: Crafty Syntax Live Help users and operators
- **Design**: Legacy compatibility layer
- **Usage**: Crafty Syntax specific functionality, chat operations

### **🔗 Integration Strategy**
1. **lupo_actors** serves as the **primary actor table**
2. **lupo_users** provides **legacy compatibility** for Crafty Syntax
3. **Data flows** from `lupo_users` → `lupo_actors` for unified actor management
4. **Crafty Syntax code** continues to use `lupo_users` for compatibility
5. **Lupopedia code** uses `lupo_actors` for modern operations

---

## 📋 **Migration Mapping**

### **✅ Legacy to New Table Mapping**
| Legacy Table | New Table | Purpose |
|-------------|-----------|---------|
| `livehelp_users` | `lupo_users` | Crafty Syntax compatibility |
| `lupo_users` | `lupo_actors` | Modern actor management |

### **✅ Data Flow**
1. **Crafty Syntax** → `lupo_users` (direct compatibility)
2. **lupo_users** → `lupo_actors` (integration layer)
3. **Lupopedia** → `lupo_actors` (modern operations)

---

## 🎯 **Recommendation**

### **✅ Use lupo_actors for New Development**
- **Primary actor table**: `lupo_actors`
- **Modern authentication**: Use `lupo_actors`
- **New features**: Build on `lupo_actors`

### **✅ Use lupo_users for Legacy Compatibility**
- **Crafty Syntax integration**: Use `lupo_users`
- **Legacy functionality**: Preserve `lupo_users`
- **Migration path**: Gradual transition to `lupo_actors`

---

## 🚀 **Implementation Status**

### **✅ Current State**
- **lupo_actors**: Primary table with modern structure
- **lupo_users**: Compatibility table for Crafty Syntax
- **Migration mapping**: Defined in `craftysyntax_to_lupopedia_mysql.sql`
- **Integration**: Both tables coexist for compatibility

### **✅ Migration Strategy**
- **Phase 1**: Preserve `lupo_users` for Crafty Syntax
- **Phase 2**: Integrate with `lupo_actors` gradually
- **Phase 3**: Transition to `lupo_actors` for new features

---

**Conclusion**: You're correct that `lupo_actors` is the primary table. `lupo_users` exists specifically for Crafty Syntax compatibility during the migration period.

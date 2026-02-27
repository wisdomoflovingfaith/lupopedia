---
wolfie.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/lupo_auth_users.md",
  system_version: "4.0.48",
  channel_id: 1,
  actor_id: 1003,
  created_ymdhis: 20260227000000,
  updated_ymdhis: 20260227000000,
  message_type: "table_documentation",
  visibility: "public",
  priority: "high",
  mood_rgb: "4B0082",
  artifact_kind: "table",
  traits: ["canonical", "authentication", "credential_management"],
  tags: ["database", "auth", "users", "google_auth", "security"]
}
flip.footer: {
  outbound_edges: [
    { to: "docs/toons/lupo_auth_users.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 }
  ],
  semantic_tags: ["identity_provider", "auth_credentials", "user_display_name"]
}
---

# 👤 Table: lupo_auth_users

**Purpose:** Authentication, credentials, and identity provider mapping for human actors.  
**Type:** Security & Credential Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (one record per human user)

---

## 🎯 **Overview**

The `lupo_auth_users` table is the secure credential layer of the Lupopedia identity system. While `lupo_actors` handles the person/entity, this table handles the *login*. It supports local passwords as well as external identity providers (Google, OAuth).

### **Key Responsibilities**
- **Credential Storage:** Stores password hashes and provider-specific IDs.
- **Provider Mapping:** Links external identities (e.g., Google account) to internal Lupopedia actors.
- **Identity Synthesis:** Seeds the human-readable profile data (email, display name) for human actors.
- **IP Tracking (Login):** Records the IP address of the most recent successful login within the `last_login_ymdhis` context.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`auth_user_id`** (BIGINT) - Must match the `actor_id` in `lupo_actors`.

### **Core Identity Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `username` | VARCHAR(255) | Unique login name | |
| `display_name` | VARCHAR(42) | Casual name displayed in UI | |
| `email` | VARCHAR(100) | Primary contact email | |
| `profile_image_url` | VARCHAR(2000) | External avatar link | |

### **Authentication Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `password_hash` | VARCHAR(255) | NULL | Encrypted password |
| `auth_provider` | VARCHAR(50) | 'local' | e.g., 'google', 'github' |
| `provider_id` | VARCHAR(255) | NULL | Subject ID from provider |
| `last_login_ymdhis` | BIGINT | NULL | YYYYMMDDHHIISS of last sign-in |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Unified Identity:** `auth_user_id` → `lupo_actors.actor_id` (1:1 relationship for humans).
- **Sessions:** Authenticated sessions in `lupo_sessions` reference this ID.

---

## 🚀 **Usage Patterns**

### **Google Auth Integration**
Seeding/Identifying a user via Google login.

```sql
SELECT auth_user_id, display_name 
FROM lupo_auth_users 
WHERE auth_provider = 'google' 
  AND provider_id = :google_sub 
  AND is_deleted = 0;
```

### **Root Admin Identification**
Retrieving the root captain's contact info.

```sql
SELECT email, display_name 
FROM lupo_auth_users 
WHERE auth_user_id = 10000;
```

---

## 🛡️ **Security & Privacy**

### **IP Address Tracking**
- **Anomalous Login Detection**: The system compares the current login IP against the subnet of previous successful logins found in `lupo_actor_events` (matched by `session_id`).
- **Anonymization**: Passwords are never stored in plain text. Provider IDs are treated as sensitive PII.

### **Data Sovereignty**
- Human actors can request a "Right to be Forgotten", which triggers soft-deletion in this table and anonymization of the linked `lupo_actors` record.

---

*This documentation is part of the v4.0.48 Security & Identity framework.*

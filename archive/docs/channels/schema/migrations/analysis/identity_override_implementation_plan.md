# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/schema/migrations/analysis/IDENTITY_OVERRIDE_IMPLEMENTATION_PLAN.md"
  file_hash: "81b7d84ccae785ef644b76e1b21b38521631c1e7ed41abf8fac5834430a03c0c"
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
  file_path_from_root: "docs\channels\schema\migrations\analysis\IDENTITY_OVERRIDE_IMPLEMENTATION_PLAN.md"
  file_hash: "102d29e2f2ea75f820115ac54eac2c91721a74a5f42ed4604d0c8c70311d81e8"
  file_path_from_root: "docs\channels\schema\migrations\analysis\IDENTITY_OVERRIDE_IMPLEMENTATION_PLAN.md"
  file_hash: "fc39863074d0dcedc2028d979acf9b5015b4bb75ffe26bcf9a89352464db3480"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "📋 **Identity Override Implementation Plan**"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "identity_override_implementation_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 📋 **Identity Override Implementation Plan**

## 🎯 **HERITAGE-SAFE MODE: Username → Email Authentication**

**Objective**: Update Crafty Syntax authentication to use email/password instead of username/password, while preserving all legacy behavior.

---

## 🔍 **Current State Analysis**

### **Legacy Authentication Flow**
```php
// Current login.php uses username/password
if(validate_user($UNTRUSTED['myusername'],$UNTRUSTED['mypassword'],$identity)){
    // Authentication successful
}
```

### **Required Changes**

#### **1. Update Authentication Functions**
- **File**: `functions.php` (lines 644-653)
- **Current**: `get_identitystring()` and `identity()` functions
- **Change**: Add email-based authentication functions

#### **2. Update Login Form**
- **File**: `login.php` (lines 40-50)
- **Current**: Username/password fields
- **Change**: Update to email/password fields with proper labels

#### **3. Update Admin Common**
- **File**: `admin_common.php` (lines 29-50)
- **Current**: Uses username-based authentication
- **Change**: Update to use email-based authentication

---

## 🔧 **Implementation Strategy**

### **Phase 1: Add Email Authentication Functions**

#### **New Functions to Add to functions.php**
```php
/**
 * Email-based authentication functions
 * HERITAGE-SAFE MODE - DO NOT MODIFY EXCEPT AS SPECIFIED
 * Reference: CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md
 */

function validate_email_password($email, $password) {
    global $mydatabase;
    
    // Hash password for comparison
    $password_hash = md5($password);
    
    // Query user by email
    $query = "SELECT user_id, password FROM lupo_users WHERE email = '" . filter_sql($email) . "'";
    $result = $mydatabase->query($query);
    
    if ($result && $result->numrows() > 0) {
        $user = $result->fetchRow(DB_FETCHMODE_ASSOC);
        
        // Verify password hash
        if ($user['password'] === $password_hash) {
            return $user; // Authentication successful
        }
    }
    
    return false; // Authentication failed
}

function get_user_by_email($email) {
    global $mydatabase;
    
    $query = "SELECT user_id, username, password FROM lupo_users WHERE email = '" . filter_sql($email) . "'";
    $result = $mydatabase->query($query);
    
    if ($result && $result->numrows() > 0) {
        return $result->fetchRow(DB_FETCHMODE_ASSOC);
    }
    
    return false;
}
```

#### **2. Update Login Form (login.php)**
```php
// Updated login form fields
<label for="email">Email Address:</label>
<input type="email" name="myemail" size="30">

<label for="password">Password:</label>  
<input type="password" name="mypassword" size="30">
```

#### **3. Update Admin Common (admin_common.php)**
```php
// Updated authentication to use email
if(validate_email_password($UNTRUSTED['myemail'], $UNTRUSTED['mypassword'], $identity)){
    // Authentication successful - use email-based identity
    $isavisitor = false;
} else {
    $isavisitor = true; // Fallback to visitor authentication
}
```

---

## 📋 **Diff Report Template**

### **Files to Modify**

| File | Lines Changed | Change Type |
|------|---------------|------------|
| `functions.php` | 644-653 | Add email authentication functions |
| `login.php` | 40-50 | Update form fields to email/password |
| `admin_common.php` | 29-50 | Update authentication logic |

### **Preservation Rules**
✅ **DO NOT modify** any legacy authentication logic
✅ **DO NOT remove** username field from database
✅ **DO NOT change** session management functions
✅ **DO NOT modernize** beyond email/password addition
✅ **MAINTAIN** all existing error handling and validation

---

## 🚀 **Implementation Authority**

This plan provides **step-by-step instructions** for implementing email/password authentication while preserving all legacy Crafty Syntax behavior. The changes are minimal and targeted, following HERITAGE-SAFE MODE principles.

**Status**: ✅ **PLAN COMPLETE** - Ready for implementation with clear preservation requirements.

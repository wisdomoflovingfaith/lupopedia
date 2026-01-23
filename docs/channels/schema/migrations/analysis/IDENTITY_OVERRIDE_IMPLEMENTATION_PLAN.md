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

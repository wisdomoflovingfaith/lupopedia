---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: scripts/ACTOR_TERMINOLOGY_CORRECTION.md
  web_path: https://www.lupopedia.com/lupopedia/scripts/ACTOR_TERMINOLOGY_CORRECTION.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: terminology_correction
  channel_key: null
  federation_node_id: null
  thread_key: actor-terminology-correction
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# file: ACTOR_TERMINOLOGY_CORRECTION.md — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/scripts/ACTOR_TERMINOLOGY_CORRECTION.md

# Actor Terminology Correction - COMPLETED

## ✅ **COMPLETED: Fixed Misleading Actor Terminology**

Corrected misleading variable names and comments that suggested users could be multiple actors simultaneously. Users can only be ONE actor at a time.

## 📋 **Terminology Issue**

### **Problem Identified**
- Variable name: `$has_multiple_actors` was misleading
- Comment: "Check if user has multiple actors" was unclear
- Implication: Suggested users could be multiple actors at once

### **Correct Understanding**
- **Users can only be ONE actor at a time** in the web interface
- **Users can have multiple actors they can SWITCH BETWEEN**
- **Only one user can be a specific actor at a time** (conflict prevention)

## 🔧 **Corrections Made**

### **1. My Profile Page**
**File**: `includes/modules/actors/views/my-profile.php`

**Variable Name Changed:**
```php
// BEFORE (misleading)
$has_multiple_actors = false;

// AFTER (clear)
$user_can_switch_actors = false;
```

**Comment Updated:**
```php
// BEFORE (unclear)
// Check if user has multiple actors

// AFTER (clear)
// Check if user has multiple actors they can switch between
```

**Logic Clarified:**
```php
// Check if user has multiple actors they can switch between
$auth_user_id = isset($_SESSION['auth_user_id']) ? (int)$_SESSION['auth_user_id'] : 0;
if ($auth_user_id > 0) {
    $available_actors = $sessionManager->getActorsUserCanActAs($auth_user_id, false);
    $user_can_switch_actors = count($available_actors) > 1;
}
```

### **2. Topbar Component**
**File**: `includes/themes/default/components/topbar.php`

**Comment Updated:**
```php
// BEFORE (unclear)
// Check if user has multiple actors available

// AFTER (clear)
// Check if user has multiple actors they can switch between
```

**Variable Usage Clarified:**
```php
$available_actors = $sessionManager->getActorsUserCanActAs($auth_user_id, false);
$user_can_switch_actors = count($available_actors) > 1;
if ($user_can_switch_actors):
```

## 🎯 **Corrected Logic Flow**

### **Actor Switching Process**
1. **User Login**: User is assigned ONE default actor
2. **Current State**: User is acting as exactly ONE actor
3. **Available Actors**: User may have multiple actors they CAN switch to
4. **Switch Action**: User can switch from current actor to another available actor
5. **Conflict Prevention**: Cannot select actor already being used by another user

### **Variable Meanings**

| Variable | Correct Meaning | Incorrect Implication |
|----------|----------------|---------------------|
| `$user_can_switch_actors` | User has multiple actors available to switch between | User can be multiple actors at once |
| `$current_actor_name` | Name of the single actor user is currently acting as | N/A |
| `$available_actors` | List of actors user can switch to (not currently acting as) | List of actors user is currently being |

## 📚 **Documentation Clarification**

### **Actor System Rules**
1. **One Actor Per Session**: Each logged-in user acts as exactly one actor
2. **One User Per Actor**: Each actor can only be used by one user at a time
3. **Switching Allowed**: Users can switch between their available actors
4. **Conflict Prevention**: Cannot select actors already in use by others

### **Available vs Current**
- **Current Actor**: The single actor the user is currently acting as
- **Available Actors**: The set of actors the user can switch to
- **Actor Pool**: User's total collection of actors they own/have access to

## ✅ **Verification Checklist**

- [x] Variable name `$has_multiple_actors` changed to `$user_can_switch_actors`
- [x] Comments updated to clarify switching vs simultaneous use
- [x] Logic properly reflects one-actor-at-a-time behavior
- [x] Both My Profile and Topbar components updated
- [x] AuthSessionManager usage remains correct

## 🚀 **Impact**

### **Code Clarity**
- **Variable Names**: Now accurately reflect the actual behavior
- **Comments**: Clearly explain the actor switching logic
- **Logic**: Properly represents one-actor-at-a-time system

### **Developer Understanding**
- **New Developers**: Won't be confused by misleading terminology
- **Maintenance**: Clear intent makes code easier to maintain
- **Debugging**: Accurate variable names aid in troubleshooting

### **System Accuracy**
- **Documentation**: Matches actual system behavior
- **User Experience**: Consistent with one-actor-per-session reality
- **Security**: Properly represents conflict prevention logic

## 📖 **Educational Value**

### **Correct Terminology Usage**
- **"User can switch actors"** ✅ Correct
- **"User has multiple actors"** ✅ Correct (when referring to available actors)
- **"User is multiple actors"** ❌ Incorrect
- **"User can be multiple actors"** ❌ Incorrect

### **Example Scenarios**

**Single Actor User:**
```
User has 1 available actor → $user_can_switch_actors = false
No "change" link shown
```

**Multiple Actor User:**
```
User has 3 available actors → $user_can_switch_actors = true
"change" link shown for switching
```

**Current State:**
```
User is always acting as exactly 1 actor at any given moment
```

---

## ✅ **TERMINOLOGY CORRECTION COMPLETE**

All misleading actor terminology has been corrected:

- **✅ Variable Names**: Changed from `$has_multiple_actors` to `$user_can_switch_actors`
- **✅ Comments**: Updated to clarify switching vs simultaneous use
- **✅ Logic**: Properly represents one-actor-at-a-time system
- **✅ Documentation**: Accurate reflection of system behavior

The code now correctly represents that users can only be ONE actor at a time, but may have multiple actors they can switch between.

**Status:** ✅ CORRECTED  
**Terminology:** ✅ ACCURATE  
**Logic:** ✅ PROPER

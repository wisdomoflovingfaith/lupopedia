# CRAFTY SYNTAX ROUTING DOCTRINE

## Core Routing Principles

### 1. **File-Based Routing Doctrine**
- **Principle**: Every URL maps directly to a PHP file
- **Implementation**: No framework routing, direct file access
- **Pattern**: `/path/to/filename.php` → `filename.php`
- **Benefits**: Simplicity, transparency, debugging ease

### 2. **Query Parameter Routing Doctrine**
- **Principle**: Actions controlled via GET/POST parameters
- **Implementation**: `?action=method&param=value` patterns
- **Pattern**: `$UNTRUSTED['action']` determines execution path
- **Benefits**: State preservation, bookmarkability

### 3. **Entry Point Doctrine**
- **Principle**: Separate entry points for different user types
- **Implementation**: `admin.php` for operators, `livehelp.php` for visitors
- **Pattern**: Role-based file routing
- **Benefits**: Clear separation of concerns

## Routing Architecture

### 1. **Admin Routing Structure**
```
admin.php
├── admin_actions.php      (POST action handler)
├── admin_chat_*.php       (Chat interface files)
├── admin_users.php        (User management)
├── admin_rooms.php        (Room management)
├── admin_options.php      (Configuration)
└── admin_*.php            (Other admin functions)
```

### 2. **Visitor Routing Structure**
```
livehelp.php              (Main visitor interface)
├── visitor_common.php    (Shared visitor functions)
├── choosedepartment.php  (Department selection)
├── autoinvite.php        (Auto-invitation system)
├── channels.php          (Channel management)
└── *.php                 (Other visitor functions)
```

### 3. **Data Routing Structure**
```
data.php                  (Main data interface)
├── data_functions.php    (Shared data functions)
├── data_transcripts.php  (Chat transcripts)
├── data_messages.php     (Message data)
├── data_paths.php        (Visitor paths)
├── data_keywords.php     (Keyword analytics)
└── data_*.php           (Other data modules)
```

## Routing Patterns

### 1. **Action Switch Pattern**
```php
// Standard action routing
if(empty($UNTRUSTED['action'])) { $UNTRUSTED['action'] = ""; }
switch($UNTRUSTED['action']){
    case "add":
        // Handle add action
        break;
    case "delete":
        // Handle delete action
        break;
    case "update":
        // Handle update action
        break;
    default:
        // Handle default case
        break;
}
```

### 2. **Tab Routing Pattern**
```php
// Tab-based routing
$tabBox = new CTabBox("data.php?a=b", "", $UNTRUSTED['tab']);
$tabBox->add('data_transcripts', $lang['transcripts']);
$tabBox->add('data_messages', $lang['messages']);
$tabBox->add('data_paths', "Paths");
$tabBox->show();
```

### 3. **Include Routing Pattern**
```php
// Conditional include routing
if($UNTRUSTED['tab'] == 0){
    require_once("data_transcripts.php");
} elseif($UNTRUSTED['tab'] == 1){
    require_once("data_messages.php");
}
```

### 4. **Parameter Routing Pattern**
```php
// Parameter-based routing
if(empty($UNTRUSTED['department'])) { $UNTRUSTED['department'] = 1; }
$department = intval($UNTRUSTED['department']);
```

## URL Structure Doctrine

### 1. **Admin URL Structure**
- **Pattern**: `admin.php?action=method&param=value`
- **Examples**:
  - `admin.php?action=users&department=1`
  - `admin.php?action=chat&channel=123`
  - `admin.php?action=options&tab=general`

### 2. **Visitor URL Structure**
- **Pattern**: `livehelp.php?param=value`
- **Examples**:
  - `livehelp.php?department=1`
  - `livehelp.php?channel=123`
  - `livehelp.php?action=refresh`

### 3. **Data URL Structure**
- **Pattern**: `data.php?tab=N&param=value`
- **Examples**:
  - `data.php?tab=0&year=2023&month=12`
  - `data.php?tab=1&department=1`
  - `data.php?tab=2&expand=1`

### 4. **Setup URL Structure**
- **Pattern**: `setup.php?step=N`
- **Examples**:
  - `setup.php?step=1` (Database configuration)
  - `setup.php?step=2` (Admin account creation)
  - `setup.php?step=3` (Finalization)

## Request Handling Doctrine

### 1. **Input Normalization Pattern**
```php
// Standard input handling
if(empty($UNTRUSTED['param'])) { $UNTRUSTED['param'] = ""; }
$UNTRUSTED['param'] = intval($UNTRUSTED['param']);
```

### 2. **Security Check Pattern**
```php
// Security validation
if (!(defined('IS_SECURE'))){
    print "Hacking attempt . Exiting..";
    exit;
}
```

### 3. **Session Validation Pattern**
```php
// Session authentication
validate_session($identity);
if(empty($identity['USERID'])) {
    // Redirect or error handling
}
```

### 4. **Permission Check Pattern**
```php
// Role-based access
if($isOPERATOR != true){
    // Redirect to visitor interface
    header("Location: livehelp.php");
    exit;
}
```

## Module Loading Doctrine

### 1. **Sequential Loading Pattern**
```php
// Standard include order
require_once("functions.php");
require_once("security.php");
require_once("config.php");
require_once("config_cslh.php");
```

### 2. **Conditional Loading Pattern**
```php
// Conditional module loading
if($isOPERATOR){
    require_once("admin_common.php");
} else {
    require_once("visitor_common.php");
}
```

### 3. **Tab Loading Pattern**
```php
// Tab-specific loading
switch($UNTRUSTED['tab']){
    case 0:
        require_once("data_transcripts.php");
        break;
    case 1:
        require_once("data_messages.php");
        break;
}
```

### 4. **Feature Loading Pattern**
```php
// Feature-specific loading
if($CSLH_Config['feature_enabled']){
    require_once("feature_module.php");
}
```

## Error Handling Doctrine

### 1. **404 Handling Pattern**
```php
// File not found handling
if(!file_exists($required_file)){
    print "Required file not found. Please contact administrator.";
    exit;
}
```

### 2. **Permission Error Pattern**
```php
// Access denied handling
if(!has_permission($user_id, $resource)){
    print "Access denied. You do not have permission to access this resource.";
    exit;
}
```

### 3. **Database Error Pattern**
```php
// Database connection error
if(!$database_connection){
    print "Database connection failed. Please try again later.";
    exit;
}
```

### 4. **Session Error Pattern**
```php
// Session expiration handling
if(empty($identity['USERID'])){
    header("Location: login.php?expired=1");
    exit;
}
```

## Redirect Doctrine

### 1. **Login Redirect Pattern**
```php
// Force login redirect
if(empty($identity['USERID'])){
    header("Location: admin.php?action=login");
    exit;
}
```

### 2. **Setup Redirect Pattern**
```php
// Installation redirect
if($installed == false && !preg_match("/setup.php/", $_SERVER['PHP_SELF'])){
    header("Location: setup.php", TRUE, 307);
    exit;
}
```

### 3. **Role Redirect Pattern**
```php
// Role-based redirect
if($isOPERATOR){
    header("Location: admin.php");
} else {
    header("Location: livehelp.php");
}
exit;
```

### 4. **Error Redirect Pattern**
```php
// Error condition redirect
if($error_condition){
    header("Location: error.php?code=" . $error_code);
    exit;
}
```

## State Preservation Doctrine

### 1. **Query String Preservation**
```php
// Preserve query parameters
$query_string = $_SERVER['QUERY_STRING'];
header("Location: target.php?$query_string");
```

### 2. **Form State Preservation**
```php
// Preserve form data
foreach($_POST as $key => $value){
    echo "<input type='hidden' name='$key' value='" . htmlspecialchars($value) . "'>";
}
```

### 3. **Tab State Preservation**
```php
// Preserve active tab
$active_tab = $UNTRUSTED['tab'];
echo "<input type='hidden' name='tab' value='$active_tab'>";
```

### 4. **Department State Preservation**
```php
// Preserve department selection
$department = $UNTRUSTED['department'];
echo "<input type='hidden' name='department' value='$department'>";
```

## URL Generation Doctrine

### 1. **Tab URL Generation**
```php
// Generate tab URLs
$tab_url = "data.php?tab=$tab_index&$query_string";
echo "<a href='$tab_url'>$tab_title</a>";
```

### 2. **Action URL Generation**
```php
// Generate action URLs
$action_url = "admin.php?action=$action&param=$param";
echo "<a href='$action_url'>$link_text</a>";
```

### 3. **Pagination URL Generation**
```php
// Generate pagination URLs
$page_url = "data.php?tab=$tab&page=$page&$other_params";
echo "<a href='$page_url'>Page $page</a>";
```

### 4. **Sort URL Generation**
```php
// Generate sort URLs
$sort_url = "data.php?tab=$tab&sort=$field&order=$direction";
echo "<a href='$sort_url'>$column_header</a>";
```

## Backward Compatibility Doctrine

### 1. **Legacy URL Support**
- **Rule**: Support old URL patterns
- **Implementation**: URL rewriting or parameter mapping
- **Example**: `old_url.php` → `new_url.php`
- **Protection**: Maintain bookmark compatibility

### 2. **Parameter Compatibility**
- **Rule**: Support old parameter names
- **Implementation**: Parameter alias mapping
- **Example**: `old_param` → `new_param`
- **Protection**: Maintain external link compatibility

### 3. **File Location Compatibility**
- **Rule**: Support old file locations
- **Implementation**: File redirection or symlinks
- **Example**: `old/path/file.php` → `new/path/file.php`
- **Protection**: Maintain direct file access

### 4. **Function Compatibility**
- **Rule**: Support old function calls
- **Implementation**: Function wrappers or aliases
- **Example**: `old_function()` → `new_function()`
- **Protection**: Maintain code compatibility

---

**This routing doctrine represents the accumulated wisdom of 25 years of Crafty Syntax URL handling. These patterns have proven their reliability and must be preserved in all future development to maintain system stability and user experience consistency.**

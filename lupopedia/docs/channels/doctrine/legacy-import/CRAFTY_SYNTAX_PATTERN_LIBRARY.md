# CRAFTY SYNTAX PATTERN LIBRARY

## Common Function Patterns

### 1. **Database Query Pattern**
```php
// Standard query pattern
$query = "SELECT * FROM table WHERE field='$value'";
$rs = $mydatabase->query($query);
$row = $rs->fetchRow(DB_FETCHMODE_ASSOC);
```

### 2. **Input Validation Pattern**
```php
// Input filtering pattern
if(empty($UNTRUSTED['param'])) $UNTRUSTED['param'] = "";
$UNTRUSTED['param'] = intval($UNTRUSTED['param']);
```

### 3. **Security Check Pattern**
```php
// Security header pattern
if (!(defined('IS_SECURE'))){
    print "Hacking attempt . Exiting..";
    exit;
}
```

### 4. **Session Validation Pattern**
```php
// Session validation pattern
validate_session($identity);
if(empty($identity['USERID'])) {
    // Redirect or error
}
```

### 5. **Tab Box Pattern**
```php
// Tab interface pattern
$tabBox = new CTabBox("page.php?", "", $tab);
$tabBox->add('include_file', 'Tab Title');
$tabBox->show();
```

## Common Naming Patterns

### 1. **File Naming Patterns**
- **Admin files**: `admin_*.php` (admin_users.php, admin_chat.php)
- **Data files**: `data_*.php` (data_messages.php, data_paths.php)
- **Class files**: `class/*.php` (class/operator.php, class/mysql_db.php)
- **Common files**: `*_common.php` (admin_common.php, visitor_common.php)

### 2. **Variable Naming Patterns**
- **Untrusted input**: `$UNTRUSTED['fieldname']`
- **Database objects**: `$mydatabase`, `$rs`, `$sqlquery`
- **Configuration**: `$CSLH_Config`, `$lang`
- **Session data**: `$identity`, `$people`

### 3. **Function Naming Patterns**
- **Validation**: `validate_*()` (validate_session, validate_user)
- **Data retrieval**: `get_*()` (get_ipaddress, get_identitystring)
- **Filtering**: `filter_*()` (filter_sql, filter_what)
- **Conversion**: `convert_*()` (convert_smile, convertamps)

### 4. **Database Naming Patterns**
- **Table names**: `livehelp_*` with prefix (livehelp_users, livehelp_messages)
- **Column names**: `snake_case` (user_id, sessionid, timestamp)
- **Index names**: Primary keys and descriptive names

## Common File Patterns

### 1. **Header Pattern**
```php
<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// --------------------------------------------------------------------------
// Please check https://lupopedia.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is CRAFTY SYNTAX Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================
```

### 2. **Include Pattern**
```php
// Standard include order
require_once("functions.php");
require_once("security.php");
require_once("config.php");
require_once("config_cslh.php");
```

### 3. **Global Declaration Pattern**
```php
// Global variables pattern
Global $mydatabase,$UNTRUSTED,$bgcolor,$color_background,$lang,$CSLH_Config;
```

### 4. **Parameter Initialization Pattern**
```php
// Parameter defaults pattern
if(empty($UNTRUSTED['param'])) $UNTRUSTED['param'] = "";
$UNTRUSTED['param'] = intval($UNTRUSTED['param']);
```

## Common Database Access Patterns

### 1. **Simple SELECT Pattern**
```php
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
```

### 2. **INSERT Pattern**
```php
$query = "INSERT INTO livehelp_messages (message, timestamp) VALUES ('$message', '$time')";
$mydatabase->insert($query);
```

### 3. **UPDATE Pattern**
```php
$query = "UPDATE livehelp_users SET lastaction='$time' WHERE user_id='$id'";
$mydatabase->query($query);
```

### 4. **Recordset Loop Pattern**
```php
$rs = $mydatabase->query($query);
while($rs->next()){
    $row = $rs->getCurrentValuesAsHash();
    // Process $row
}
```

## Common Fallback Patterns

### 1. **Database Fallback Pattern**
```php
// Database type selection
if ($dbtype === 'mysql') {
    $this->db = new MySQL_DB($host, $user, $pass);
} elseif ($dbtype === 'postgres') {
    $this->db = new Postgres_DB($host, $user, $pass);
} else {
    $this->error("Unsupported DB type: $type");
}
```

### 2. **Session Fallback Pattern**
```php
// Multiple session methods
if(empty($UNTRUSTED['cslhVISITOR'])) $UNTRUSTED['cslhVISITOR'] = "";
if(empty($UNTRUSTED['cslhOPERATOR'])) $UNTRUSTED['cslhOPERATOR'] = "";
```

### 3. **PHP Version Fallback Pattern**
```php
// PHP 4/5 compatibility
if (!isset($HTTP_POST_VARS) && (isset($_POST))){
    $HTTP_POST_VARS = $_POST;
    $HTTP_GET_VARS = $_GET;
    $HTTP_SERVER_VARS = $_SERVER;
}
```

## Common UI Patterns

### 1. **Tab Box Pattern**
```php
// Tab interface creation
$tabBox = new CTabBox("data.php?a=b", "", $UNTRUSTED['tab']);
$tabBox->add('data_transcripts', $lang['transcripts']);
$tabBox->add('data_messages', $lang['messages']);
$tabBox->show();
```

### 2. **Buffer-Streaming Pattern (Legacy)**
```php
// Real-time buffer streaming (1999-era)
while($abort_counter != $abort_counter_end) {
    $buffer_html = showmessages($operator_id, "", $timeof, ""); 
    if($buffer_html != "") {
        print $buffer_html;                    // Stream HTML fragment
        ?><SCRIPT type="text/javascript">up(); setTimeout('up()',9);</SCRIPT><?php
        sendbuffer();                          // Flush to browser
    }
    sleep(1);                                 // Server-side throttle
    $abort_counter++;
}
```

### 3. **Soft Refresh Pattern (Modern)**
```javascript
// Modern equivalent of buffer streaming
async function softRefresh() {
    const response = await fetch('/chat/fragment?since=' + lastTimestamp);
    const html = await response.text();
    
    if (html.trim() !== '') {
        document.querySelector('#chat-container').innerHTML += html;
        scrollToBottom();
        updateTypingIndicator();
    }
}
setInterval(softRefresh, 1000);
```

### 4. **Color Scheme Pattern**
```php
// Dynamic color loading
$colorfile = "images".C_DIR. $CSLH_Config['colorscheme'] .C_DIR."color.php";
if(file_exists($colorfile)){
    require_once($colorfile);
} else {
    $color_background="FAFAFA";
    $color_alt1 = "E4E4E4";
}
```

### 6. **Session Identity Pattern (Legacy)**
```php
// Multi-factor identity construction
function get_identitystring($ipaddress, $sessionname="SESSIONID"){
    $hostip_array = explode(".", $ipaddress); 
    $identitystring = "";
    
    // Class C IP (first 3 octets)
    if(!(empty($hostip_array[0]))) { $identitystring .= $hostip_array[0] . "."; }
    if(!(empty($hostip_array[1]))) { $identitystring .= $hostip_array[1] . "."; }
    if(!(empty($hostip_array[2]))) { $identitystring .= $hostip_array[2]; } 
    
    // Combine with session identifier
    $identitystring .= "-" . $sessionname;
    return $identitystring;
}

// Multi-factor session detection
function detectID($sessionname, $allowhostiplogins, $identitystring) {
    $PHPSESSID = "";
    
    // Try all possible session sources
    if(!empty($UNTRUSTED[$sessionname])) {
        $PHPSESSID = $UNTRUSTED[$sessionname];  // URL parameter
    }
    if(!empty($_GET[$sessionname])) {
        $PHPSESSID = $_GET[$sessionname];     // GET parameter
    }
    if(!empty($_POST[$sessionname])) {
        $PHPSESSID = $_POST[$sessionname];    // POST parameter
    }
    if(!empty($_COOKIE[$sessionname])) {
        $PHPSESSID = $_COOKIE[$sessionname];   // Cookie
    }
    
    // Fallback: IP + Host based identity
    if($allowhostiplogins && empty($PHPSESSID)) {
        $sqlquery = "SELECT sessionid FROM livehelp_users 
                     WHERE identity='".filter_sql($identitystring)."' 
                     AND cookied='N'";
        $people = $mydatabase->query($sqlquery);
        if($people->numrows() != 0){     
            $row = $people->fetchRow(DB_FETCHMODE_ORDERED);
            $PHPSESSID = $row[0];           // Database fallback
        }        
    }
    
    return $PHPSESSID;
}
```

### 7. **Class C Security Pattern (Legacy)**
```php
// Class C IP validation for session security
if(($CSLH_Config['matchip']=="Y") && (!empty($PHPSESSID))){
    $hostip_array = explode(".", get_ipaddress());  
    $classc = "$hostip_array[0].$hostip_array[1].$hostip_array[2]";
    $sqlquery = "SELECT sessionid FROM livehelp_users 
                 WHERE sessionid='".filter_sql($PHPSESSID)."' 
                 AND ipaddress LIKE '".$classc."%' LIMIT 1";
    $test = $mydatabase->query($sqlquery);
    if($test->numrows() == 0){ 
        $PHPSESSID = "";  // Reject session from different subnet
    }
}
```

## Common Security Patterns

### 1. **Input Filtering Pattern**
```php
// SQL filtering
$query = "SELECT * FROM table where recno=". filter_sql($UNTRUSTED['recno'],true);
$query = "SELECT * FROM table where username='". filter_sql($UNTRUSTED['username'],false,255) . "'";
```

### 2. **Output Escaping Pattern**
```php
// HTML output escaping
print cslh_escape($UNTRUSTED['message']);
```

### 3. **Include Security Pattern**
```php
// Include protection
if (!(defined('IS_SECURE'))){
    print "Hacking attempt . Exiting..";
    exit;
}
```

## Common Error Handling Patterns

### 1. **Database Error Pattern**
```php
// Database connection check
if(!$conn) {
    $errors .= "<li>Connection to the database failed";
}
```

### 2. **File Existence Pattern**
```php
// File existence check
if(file_exists($filename)){
    require_once($filename);
} else {
    // Fallback handling
}
```

### 3. **Function Existence Pattern**
```php
// Function availability check
if(function_exists('function_name')){
    // Use function
} else {
    // Fallback
}
```

## Common Configuration Patterns

### 1. **Config Variable Pattern**
```php
// Configuration setting
$variable = 'INPUT-VALUE';
// Usage in code
if($variable == 'default'){
    // Default behavior
}
```

### 2. **Path Configuration Pattern**
```php
// Path setting with trailing slash
$path = "/path/to/directory/";
// Usage
require_once($path . "file.php");
```

### 3. **Feature Flag Pattern**
```php
// Feature enable/disable
$feature_enabled = true;
if($feature_enabled){
    // Feature code
}
```

## Common Class Patterns

### 1. **Database Class Pattern**
```php
class DatabaseClass {
    var $db = null;
    var $type = 'mysql';
    
    function DatabaseClass($params) {
        // Constructor
    }
    
    function query($sql) {
        // Query method
    }
}
```

### 2. **Operator Class Pattern**
```php
class CSLH_operator {
    var $myid = 0;
    var $channel = 0;
    var $username = "";
    
    function CSLH_operator() {
        // Constructor with database lookup
    }
}
```

### 3. **Tab Box Class Pattern**
```php
class CTabBox {
    var $tabs = array();
    var $active = 0;
    
    function add($file, $title) {
        $this->tabs[] = array($file, $title);
    }
    
    function show() {
        // Render tabs
    }
}
```

---

**This pattern library represents the accumulated wisdom of 25 years of Crafty Syntax development. These patterns have proven their reliability and should be preserved in all future development to maintain system consistency and stability.**

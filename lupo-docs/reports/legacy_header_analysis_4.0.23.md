# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/reports/legacy_header_analysis_4.0.23.md"
  file_hash: "76b4f8281af2acdf0b6894c5c278355ecbcf2265f7d452fe6ca1519981c64017"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\reports\legacy_header_analysis_4.0.23.md"
  file_hash: "fd7d29dbc33e924e90a0feefb815ddfcebbed1b9576b1146f7ef51d7beeafd3a"
  file_path_from_root: "lupo-docs\reports\legacy_header_analysis_4.0.23.md"
  file_hash: "b18da22f0feff2b677e84c26b6896835b2b6c0dfb957f07fa4b14678756b6caf"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Legacy Crafty Syntax Header Analysis Report (Lupopedia 4.0.23)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "reports", "legacy_header_analysis_4023md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Legacy Crafty Syntax Header Analysis Report (Lupopedia 4.0.23)

## Overview

This document analyzes how legacy Crafty Syntax installations handled client IP addresses and headers, providing migration guidance for Wolfie protocol implementation.

## Files Analyzed

### 1. livehelp_js.php
**Location**: `lupo-includes/modules/livehelp/livehelp_js.php`

**Header Handling**:
```php
// Legacy IP detection (PHP 5.3 compatible)
$client_ip = '0.0.0.0';

if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
    $client_ip = $_SERVER['HTTP_X_REAL_IP'];
} elseif (isset($_SERVER['REMOTE_ADDR'])) {
    $client_ip = $_SERVER['REMOTE_ADDR'];
}

// Store in session for tracking
$_SESSION['client_ip'] = $client_ip;
```

**Issues Identified**:
- No IP validation
- No proxy chain handling
- Direct assignment without sanitization
- Session storage without cleanup

### 2. image.php
**Location**: `lupo-includes/modules/image/image.php`

**Header Handling**:
```php
// Basic IP detection for image access logging
$visitor_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

// Check for proxy headers
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $visitor_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
```

**Issues Identified**:
- No comma-separated IP handling
- No IPv6 support
- No validation of IP format
- Missing HTTP_X_REAL_IP check

### 3. visitor-image.php
**Location**: `lupo-includes/modules/visitor/visitor-image.php`

**Header Handling**:
```php
// Visitor tracking with IP detection
$ip_address = '0.0.0.0';

// Priority order for IP detection
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
    $ip_address = $_SERVER['HTTP_X_REAL_IP'];
} elseif (isset($_SERVER['REMOTE_ADDR'])) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
}

// Log visitor access
logVisitorAccess($ip_address, $image_id);
```

**Issues Identified**:
- No IP format validation
- No proxy chain parsing
- Potential SQL injection in logging function
- No rate limiting based on IP

## Migration Recommendations

### 1. IP Detection Standardization

**Current Legacy Order**:
1. `HTTP_X_FORWARDED_FOR`
2. `HTTP_X_REAL_IP`
3. `REMOTE_ADDR`

**Recommended Wolfie Protocol Order**:
1. `X-Wolfie-Forwarded-For` (new)
2. `X-Forwarded-For` (standard)
3. `HTTP_X_REAL_IP` (legacy)
4. `REMOTE_ADDR` (fallback)

### 2. IP Validation

**Required Validation**:
```php
function validateIPAddress($ip) {
    // Basic IPv4 validation
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return $ip;
    }
    
    // Basic IPv6 validation
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        return $ip;
    }
    
    return '0.0.0.0'; // Fallback for invalid IPs
}
```

### 3. Proxy Chain Handling

**Current Issue**: Only takes first IP in chain

**Solution**: Parse comma-separated IPs
```php
function parseForwardedFor($header) {
    if (!$header) {
        return '0.0.0.0';
    }
    
    // Split by comma and take first valid IP
    $ips = explode(',', $header);
    foreach ($ips as $ip) {
        $ip = trim($ip);
        if (validateIPAddress($ip)) {
            return $ip;
        }
    }
    
    return '0.0.0.0';
}
```

### 4. Security Improvements

**Rate Limiting**:
```php
function checkRateLimit($ip, $window_minutes = 5, $max_requests = 100) {
    $window_start = time() - ($window_minutes * 60);
    
    $query = "SELECT COUNT(*) as request_count 
              FROM lupo_rate_limits 
              WHERE ip_address = '" . mysql_real_escape_string($ip) . "' 
              AND created_ymdhis > " . $window_start;
    
    $result = mysql_query($query);
    if ($result) {
        $row = mysql_fetch_assoc($result);
        return $row['request_count'] < $max_requests;
    }
    
    return false;
}
```

**IP Blacklisting**:
```php
function isBlacklisted($ip) {
    $query = "SELECT COUNT(*) as count 
              FROM lupo_ip_blacklist 
              WHERE ip_address = '" . mysql_real_escape_string($ip) . "'";
    
    $result = mysql_query($query);
    if ($result) {
        $row = mysql_fetch_assoc($result);
        return $row['count'] > 0;
    }
    
    return false;
}
```

## Migration Path

### Phase 1: Header Standardization
1. Update all files to use `getWolfieHeaders()` function
2. Implement proper header parsing order
3. Add IP validation

### Phase 2: Security Enhancement
1. Add rate limiting based on forwarded IP
2. Implement IP blacklisting
3. Add proxy chain parsing

### Phase 3: Database Integration
1. Store headers in `lupo_contents.metadata_json`
2. Create `lupo_rate_limits` table
3. Create `lupo_ip_blacklist` table

### Phase 4: ANUBIS Integration
1. Update ANUBIS_Resolver to use new header parsing
2. Add actor resolution from IP
3. Implement adoption protocol for test cases

## Testing Strategy

### Test Cases
1. **Standard Headers**: All Wolfie headers present
2. **Legacy Headers**: Only HTTP_X_FORWARDED_FOR present
3. **Proxy Chain**: Multiple IPs in X-Forwarded-For
4. **Invalid IP**: Malformed IP address
5. **Missing Headers**: No IP headers present

### Expected Results
- All IP addresses properly validated
- Proxy chains correctly parsed
- Rate limiting enforced
- ANUBIS can resolve actor from IP

## Compatibility Notes

### PHP Version Compatibility
- All code must be PHP 5.3 compatible
- No short arrays (`[]` use `array()`)
- No null coalescing operator (`??`)
- No type hints or return types

### Database Compatibility
- Use MySQL functions for compatibility
- No prepared statements (legacy codebase)
- Proper escaping with `mysql_real_escape_string()`

### Backward Compatibility
- Maintain support for legacy header names
- Graceful degradation when headers missing
- Fallback to REMOTE_ADDR when needed

## Conclusion

The legacy Crafty Syntax header handling is functional but lacks security features and modern IP parsing capabilities. The migration to Wolfie protocol will provide:

1. **Standardized header handling**
2. **Improved security through validation**
3. **Better proxy support**
4. **Integration with ANUBIS resolver**
5. **Audit trail through metadata storage**

This migration ensures compatibility with existing installations while providing the foundation for modern header processing and actor resolution capabilities.

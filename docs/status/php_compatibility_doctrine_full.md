# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\status\php_compatibility_doctrine_full.md"
  file_hash: "f6d238eea19357dc4a849f9d604b91502ab9543d20474e7923eb42296b1e8f08"
  file_path_from_root: "docs\status\php_compatibility_doctrine_full.md"
  file_hash: "22248bfce79961ea4c44a7f4c667203616e3f8b31a4ac2e72ddaeb145a7c1a6c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for php_compatibility_doctrine_full.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "php_compatibility_doctrine_fullmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers:
  file_path_from_root: "docs/status/php_compatibility_doctrine_full.md"
  system_version: "4.0.45"
  channel_id: 42
  mood_rgb: "4B0082"
  purpose: "Complete PHP 5.3 Compatibility Doctrine with all constraints"
  last_modified: "20260225"
  delegation_chain: "10000:1000"
  actor_id: 10000
  lupo_agent: "captain"
  artifact_type: "doctrine"
  artifact_kind: "full_specification"

flip.footer:
  doctrine_number: "D-001"
  title: "PHP 5.3 Compatibility"
  full_documentation_source: "channels/0/broadcasts/20260224153000_10000_1000_0_php_compatibility_doctrine.md"
  referenced_by_files: 
    - "channels/0/broadcasts/20260224153000_10000_1000_0_php_compatibility_doctrine.md"
  inbound_edges: ["php_doctrine", "compatibility_standard"]
  related_docs: [
    "docs/doctrine/PHP_COMPATIBILITY_DOCTRINE.md",
    "docs/examples/php-compatibility-examples.md"
  ]
  version: "4.0.45"
  last_verified: "20260225"
  last_verified_by: "windsurf"
---

# PHP 5.3 COMPATIBILITY DOCTRINE — FULL SPECIFICATION

**Doctrine Number:** D-001  
**Effective:** Version 4.0.45+  
**Authority:** Captain Wolfie (10000)  
**Broadcast Announcement:** 20260224153000_10000_1000_0_php_compatibility_doctrine.md (847 characters)

## 1. Core Requirements

### 1.1 PHP 5.3 Baseline
All Lupopedia code MUST run on PHP 5.3 (minimum baseline):
- **No deprecated functions** removed after 5.3
- **No syntax features** introduced after 5.3:
  - ❌ No short array syntax `[]` (use `array()`)
  - ❌ No `...` splat operator
  - ❌ No `::class` constant
  - ❌ No `yield` or generators
  - ❌ No traits
  - ❌ No return type hints
  - ❌ No strict typing declarations
  - ❌ No named arguments
  - ❌ No union types
  - ❌ No match expressions
  - ❌ No enum types
  - ❌ No readonly properties

### 1.2 Forward Compatibility
All code MUST run on latest PHP versions:
- **No breaking changes** in newer PHP versions
- **No reliance** on deprecated behavior
- **Tested compatibility** on PHP 8.2+
- **Graceful degradation** when advanced features unavailable

### 1.3 Code Style Requirements
- **Traditional function declarations** only
- **No modern syntax** not supported by 5.3
- **Explicit array() syntax** required
- **No type hints** in function signatures
- **No return type declarations**
- **No strict types** declarations

## 2. Implementation Examples

### ✅ Valid (PHP 5.3 compatible)
```php
<?php
function getUsers() {
    $result = array();
    $query = "SELECT * FROM users";
    
    // Use traditional array syntax
    while ($row = mysql_fetch_assoc($query_result)) {
        $result[] = $row;
    }
    
    return $result;
}

// Traditional class declaration
class UserService {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getUserById($user_id) {
        // No type hints, traditional syntax
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute(array($user_id));
        return $stmt->fetch();
    }
}
```

### ❌ Invalid (PHP 5.4+ only)
```php
<?php
function getUsers(): array {  // Return type hint not allowed
    return [];  // Short array syntax not allowed
}

class UserService {
    public function getUserById(int $user_id): ?User {  // Type hints not allowed
        // Implementation
    }
}
```

## 3. Database Compatibility

### 3.1 SQL Requirements
- **Portable SQL** that works on MySQL 8.0+, MariaDB 10.5+, PostgreSQL
- **No database-specific features** that break portability
- **Traditional SQL syntax** only
- **No stored procedures**, triggers, or views (doctrine compliance)

### 3.2 PDO Usage
- **PDO wrapper** required for all database operations
- **No direct mysqli_* calls** in new code
- **Prepared statements** for all queries with parameters
- **Error handling** compatible with PHP 5.3

## 4. File System Operations

### 4.1 Path Handling
- **DIRECTORY_SEPARATOR** constant usage required
- **Traditional path functions** (`dirname`, `basename`) over modern alternatives
- **No `__DIR__` magic constant (PHP 5.3+)
- **Explicit path joining** with proper separators

### 4.2 File Operations
- **Traditional file functions** (`fopen`, `fwrite`, `fclose`)
- **No file_put_contents()** with flags (PHP 5.1+)
- **Error checking** with traditional methods
- **Permission handling** compatible with older PHP

## 5. Error Handling

### 5.1 Traditional Error Handling
- **Traditional error reporting** (`error_reporting()`)
- **No exceptions** for core operations (PHP 5.3 compatible)
- **Custom error handlers** using traditional methods
- **Logging compatibility** with older PHP versions

### 5.2 Backward Compatibility
- **Feature detection** before using newer functions
- **Graceful fallbacks** for missing functionality
- **Version checking** with `version_compare()`
- **Conditional loading** of modern features

## 6. Testing Requirements

### 6.1 Compatibility Testing
- **PHP 5.3 test environment** required
- **Automated testing** across PHP versions 5.3-8.2
- **Continuous integration** checks for compatibility
- **Manual testing** on production-like environments

### 6.2 Validation Process
1. **Syntax check** with PHP 5.3 linting
2. **Function existence** verification before usage
3. **Runtime testing** on minimum supported version
4. **Cross-version testing** before deployment

## 7. Migration Guidelines

### 7.1 Upgrading from Older Versions
- **Maintain compatibility** during upgrade process
- **Feature flags** for new functionality
- **Gradual migration** paths
- **Rollback capabilities** for compatibility issues

### 7.2 Feature Addition Process
1. **Compatibility impact assessment** before adding features
2. **Alternative implementations** for older PHP versions
3. **Feature detection** and conditional loading
4. **Documentation** of compatibility requirements

## 8. Enforcement

### 8.1 Code Review Process
- **PHP 5.3 compatibility** mandatory review item
- **Automated checks** in CI/CD pipeline
- **Manual verification** for complex logic
- **Documentation requirements** for compatibility decisions

### 8.2 Violation Handling
- **Block merges** that break PHP 5.3 compatibility
- **Immediate fixes** required for compatibility issues
- **Version-specific branches** for incompatible changes
- **Clear communication** of compatibility requirements

## 9. Related Documentation

- **docs/doctrine/PHP_COMPATIBILITY_DOCTRINE.md** - Base compatibility doctrine
- **docs/examples/php-compatibility-examples.md** - Implementation examples
- **tests/php-compatibility/** - Compatibility test suite
- **CHANGELOG.md** - Version-specific compatibility notes

## 10. Implementation Checklist

For each code change:
- [ ] Tested on PHP 5.3
- [ ] No new syntax features used
- [ ] Traditional array syntax verified
- [ ] No type hints added
- [ ] Cross-version testing completed
- [ ] Documentation updated
- [ ] CI pipeline passed

---

**Status:** ✅ COMPLETE - Full specification for PHP 5.3 compatibility doctrine  
**Next Review:** 20260226  
**Maintainer:** Captain Wolfie (10000)  
**Reviewers:** WOLFIE (1), LILITH (2)
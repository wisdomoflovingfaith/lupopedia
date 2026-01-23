# CRAFTY SYNTAX DOCTRINE

## Core Architectural Principles

### 1. **Installation Immortality Doctrine**
- **"Never break the install"** - The system must remain installable and upgradable across all versions
- **Setup-first approach** - `config.php` contains `$installed=false` check that redirects to setup
- **Permission security** - Automatic detection and correction of insecure file permissions
- **Backward compatibility** - Support for legacy PHP versions and server configurations

### 2. **Graceful Degradation Doctrine**
- **"Always degrade gracefully"** - System must function even when components fail
- **Fallback mechanisms** - Multiple database support (MySQL, PostgreSQL, txt-db-api)
- **Error suppression** - `@error_reporting(0)` and `@ini_set('display_errors', '0')` in production
- **Module independence** - Each module can fail without breaking the entire system

### 3. **Operator Preservation Doctrine**
- **"Operators must never lose a chat"** - Chat sessions are sacred and must be preserved
- **Session tracking** - Comprehensive session management with heartbeat updates
- **Channel persistence** - Operators maintain channel state across page refreshes
- **Redundant session storage** - Multiple session mechanisms (server, cookie, IP-based)

### 4. **Path Tracking Doctrine**
- **"Paths must always be tracked"** - Complete visitor journey tracking is mandatory
- **Hierarchical path storage** - Parent-child relationships in visitor navigation
- **Monthly aggregation** - Path data stored in monthly tables for performance
- **Analytics priority** - Path tracking takes precedence over performance concerns

### 5. **Shared Hosting Compatibility Doctrine**
- **"Everything must work on cheap shared hosting"** - System designed for lowest common denominator
- **Minimal dependencies** - No external libraries or frameworks required
- **Resource conservation** - Lightweight database queries and minimal memory usage
- **File-based fallbacks** - Text database support when MySQL unavailable

## Database Doctrine

### 1. **Table Prefix Doctrine**
- **Configurable prefixes** - `$table_prefix = 'craftysyntax'` (changed from 'livehelp' in 3.8.0)
- **No hardcoded table names** - All queries use prefix variables
- **Migration compatibility** - Prefix changes supported across versions

### 2. **Query Simplicity Doctrine**
- **Avoid heavy queries** - Simple SELECT/INSERT/UPDATE operations only
- **No complex JOINs** - Prefer multiple simple queries over complex joins
- **Indexed fields** - Primary focus on sessionid, user_id, timestamp fields

### 3. **Schema Stability Doctrine**
- **Avoid schema changes** - Database structure changes are rare and carefully planned
- **Migration scripts** - All schema changes require explicit migration files
- **Backward compatibility** - Old schema versions supported during transitions

## Security Doctrine

### 1. **Input Filtering Doctrine**
- **Never trust input** - All user input filtered through `filter_sql()` and `cslh_escape()`
- **SQL injection prevention** - Parameterized queries and escaping
- **XSS prevention** - Output escaping for all user-generated content
- **Include security** - Never use user input in include/require statements

### 2. **Session Security Doctrine**
- **Dual session system** - Separate operator and visitor sessions
- **Identity verification** - Session identity strings and validation
- **Timeout management** - Automatic session cleanup and heartbeat updates

### 3. **File Security Doctrine**
- **Permission checking** - Automatic detection of insecure file permissions
- **Config file protection** - Config.php permissions automatically secured
- **Hacking prevention** - `IS_SECURE` constant prevents direct access to includes

## Error Handling Doctrine

### 1. **Silent Failure Doctrine**
- **Production silence** - Errors suppressed in production environments
- **Development verbosity** - Full error reporting in development mode
- **Graceful degradation** - System continues operating despite errors

### 2. **Fallback Doctrine**
- **Multiple database backends** - MySQL, PostgreSQL, text database fallbacks
- **Alternative authentication** - Multiple session tracking methods
- **UI degradation** - Basic HTML fallback when JavaScript fails

## UI/UX Doctrine

### 1. **HTML-First Doctrine**
- **No JavaScript dependency** - Core functionality works without JavaScript
- **Progressive enhancement** - JavaScript enhances but doesn't replace functionality
- **Browser compatibility** - Support for old browsers and limited devices

### 2. **Tabbed Interface Doctrine**
- **CTabBox pattern** - Consistent tabbed interface across all admin pages
- **State preservation** - Tab state maintained across page refreshes
- **Modular includes** - Each tab loads separate PHP file

### 3. **Color Scheme Doctrine**
- **Dynamic theming** - Color schemes loaded from external files
- **Consistent styling** - CSS classes follow naming conventions
- **Accessibility focus** - High contrast and readable fonts

## Module Loading Doctrine

### 1. **Sequential Loading Doctrine**
- **Dependency order** - Functions → Security → Config → Database
- **Global availability** - All modules loaded into global scope
- **Conditional loading** - Modules only loaded when needed

### 2. **Isolation Doctrine**
- **File-level security** - Each include file checks `IS_SECURE` constant
- **Global variable usage** - Heavy reliance on global variables
- **Function availability** - Functions checked before use with `function_exists()`

## Routing Doctrine

### 1. **File-Based Routing Doctrine**
- **Direct file access** - Each URL maps to a specific PHP file
- **Query parameter routing** - Action-based routing via GET parameters
- **No framework routing** - Simple file-based URL structure

### 2. **Admin/Visitor Separation Doctrine**
- **Separate entry points** - `admin.php` for operators, `livehelp.php` for visitors
- **Common includes** - Shared functionality in common files
- **Access control** - Role-based access through session validation

## Performance Doctrine

### 1. **Resource Conservation Doctrine**
- **Minimal memory usage** - Lightweight data structures
- **Database connection pooling** - Single database connection per request
- **File-based caching** - Text database fallback for high-load scenarios

### 2. **Scalability Doctrine**
- **Horizontal scaling** - Multiple operator support
- **Monthly data partitioning** - Time-based table partitioning
- **Query optimization** - Indexed queries and limited result sets

## Naming Convention Doctrine

### 1. **File Naming Doctrine**
- **Descriptive names** - File names clearly indicate purpose
- **Prefix conventions** - `admin_`, `data_`, `class_` prefixes
- **Lowercase with underscores** - Consistent lowercase naming

### 2. **Variable Naming Doctrine**
- **Hungarian notation** - Type prefixes for variables (`$sqlquery`, `$rs`)
- **Global variables** - `$UNTRUSTED`, `$mydatabase`, `$CSLH_Config`
- **Function naming** - Verb-noun pattern (`validate_session`, `get_ipaddress`)

### 3. **Database Naming Doctrine**
- **Table prefixes** - Consistent prefix usage
- **Column naming** - Descriptive column names with underscores
- **Index naming** - Primary keys and indexes clearly named

## Legacy Compatibility Doctrine

### 1. **PHP Version Compatibility Doctrine**
- **PHP 4+ support** - Code compatible with old PHP versions
- **Superglobal fallbacks** - `$HTTP_*_VARS` support for old PHP
- **Function availability** - Checks for function existence before use

### 2. **Server Compatibility Doctrine**
- **Register globals** - Support for register_globals enabled
- **Magic quotes** - Handling of magic quotes functionality
- **Safe mode** - Compatibility with PHP safe mode

## Testing Doctrine

### 1. **Manual Testing Doctrine**
- **Operator workflow testing** - All operator workflows manually tested
- **Visitor journey testing** - Complete visitor chat flow validation
- **Installation testing** - Setup process tested on various environments

### 2. **Regression Prevention Doctrine**
- **Backward compatibility** - New features don't break existing functionality
- **Database migration testing** - All migrations tested on production data
- **Cross-browser testing** - Functionality verified across browsers

---

**This doctrine represents 25 years of operational wisdom from the Crafty Syntax Live Help system. It must be preserved and honored in all future development to maintain system stability and operator trust.**

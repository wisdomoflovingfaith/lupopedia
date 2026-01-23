# CRAFTY SYNTAX STABILITY RULES

## Critical Invariants (Never Break These)

### 1. **Installation Invariant**
- **Rule**: System must always be installable from scratch
- **Implementation**: `$installed=false` check in config.php redirects to setup
- **Protection**: Never remove or bypass installation checks
- **Testing**: Fresh installation must work on every deployment

### 2. **Session Invariant**
- **Rule**: Operator sessions must never be lost
- **Implementation**: Multiple session tracking mechanisms (server, cookie, IP)
- **Protection**: Session heartbeat updates and redundant storage
- **Testing**: Simulate session failures and verify continuity

### 3. **Data Integrity Invariant**
- **Rule**: Chat transcripts must never be corrupted or lost
- **Implementation**: Atomic database operations and backup mechanisms
- **Protection**: Transaction-like behavior even without explicit transactions
- **Testing**: Power failure scenarios and recovery procedures

### 4. **Permission Security Invariant**
- **Rule**: Config file must never have insecure permissions
- **Implementation**: Automatic permission checking and correction
- **Protection**: Runtime permission validation on every request
- **Testing**: Various permission scenarios and automatic fixes

## Fragile Areas (Handle with Extreme Care)

### 1. **Database Schema**
- **Fragility**: Schema changes break existing installations
- **Protection**: All changes require migration scripts
- **Procedure**: Version checking and incremental updates
- **Rollback**: Always provide rollback migration

### 2. **Session Management**
- **Fragility**: Session changes can lock out operators
- **Protection**: Maintain backward compatibility
- **Procedure**: Test all session scenarios
- **Rollback**: Revert to previous session handling

### 3. **File Paths**
- **Fragility**: Path changes break installations
- **Protection**: Use configuration for all paths
- **Procedure**: Test on different server configurations
- **Rollback**: Restore original path structure

### 4. **Database Connections**
- **Fragility**: Connection changes can prevent access
- **Protection**: Support multiple database types
- **Procedure**: Test all supported databases
- **Rollback**: Restore original connection method

## Legacy Compatibility Rules

### 1. **PHP 4 Compatibility Rule**
- **Rule**: Code must work on PHP 4.x systems
- **Implementation**: `$HTTP_*_VARS` fallbacks
- **Protection**: Function availability checks
- **Testing**: Verify on old PHP versions

### 2. **Register Globals Support Rule**
- **Rule**: Support register_globals enabled
- **Implementation**: Variable initialization patterns
- **Protection**: Explicit variable setting
- **Testing**: Test with register_globals on/off

### 3. **Magic Quotes Handling Rule**
- **Rule**: Handle magic quotes automatically
- **Implementation**: Conditional stripslashes usage
- **Protection**: Double-slash prevention
- **Testing**: Various magic quotes settings

### 4. **Safe Mode Compatibility Rule**
- **Rule**: Work in PHP safe mode
- **Implementation**: Avoid restricted functions
- **Protection**: Safe mode detection
- **Testing**: Verify in safe mode environments

## Legacy Behaviors (Must Preserve)

### 1. **PHP 4 Compatibility**
- **Behavior**: Code works on PHP 4.x systems
- **Implementation**: `$HTTP_*_VARS` fallbacks
- **Protection**: Function existence checks
- **Testing**: Verify on old PHP versions

### 2. **Register Globals Support**
- **Behavior**: Works with register_globals enabled
- **Implementation**: Variable initialization patterns
- **Protection**: Explicit variable setting
- **Testing**: Test with register_globals on/off

### 3. **Magic Quotes Handling**
- **Behavior**: Handles magic quotes automatically
- **Implementation**: Stripslashes when needed
- **Protection**: Conditional stripslashes usage
- **Testing**: Various magic quotes settings

### 4. **Safe Mode Compatibility**
- **Behavior**: Works in PHP safe mode
- **Implementation**: Avoids restricted functions
- **Protection**: Safe mode detection
- **Testing**: Verify in safe mode environments

## Legacy Session Identity Rules

### 1. **Multi-Factor Identity Invariant**
- **Rule**: Session identity must never rely on single factor
- **Implementation**: Class C IP + User-Agent + Hostname + Referer + Cookie + Session ID
- **Protection**: Session survives any single point failure
- **Testing**: Test with cookies disabled, proxies, and IP changes

### 2. **Class C Validation Invariant**
- **Rule**: Sessions must be validated against Class C subnet
- **Implementation**: IP subnet matching for session security
- **Protection**: Prevents session hijacking via referer links
- **Testing**: Test session transfer across different subnets

### 3. **Fallback Chain Invariant**
- **Rule**: Always have multiple session detection methods
- **Implementation**: URL → GET → POST → Cookie → Database → Identity string
- **Protection**: Session recovery from any failure scenario
- **Testing**: Test with each method individually disabled

### 4. **Environment Adaptation Invariant**
- **Rule**: System must adapt to whatever client provides
- **Implementation**: Use whatever identity factors are available
- **Protection**: Works in corporate, mobile, and restricted environments
- **Testing**: Test with various browser and network configurations

## Operator Expectations (Never Violate)

### 1. **Chat Continuity**
- **Expectation**: Chat continues even during page refresh
- **Implementation**: Persistent session tracking
- **Protection**: Multiple session mechanisms
- **Testing**: Simulate page refresh scenarios

### 2. **Operator Panel Availability**
- **Expectation**: Admin panel always accessible
- **Implementation**: Graceful degradation
- **Protection**: Multiple authentication methods
- **Testing**: Test with various failure scenarios

### 3. **Data Export Capability**
- **Expectation**: Can always export chat data
- **Implementation**: Multiple export formats
- **Protection**: Fallback export methods
- **Testing**: Verify all export options

### 4. **Real-time Updates**
- **Expectation**: Chat updates in real-time
- **Implementation**: JavaScript and meta-refresh fallbacks
- **Protection**: Multiple update mechanisms
- **Testing**: Test with JavaScript disabled

## Compatibility Requirements (Must Maintain)

### 1. **Browser Compatibility**
- **Requirement**: Works on old browsers
- **Implementation**: HTML-first approach
- **Protection**: No JavaScript dependency
- **Testing**: Test on IE6+, Firefox, Chrome

### 2. **Server Compatibility**
- **Requirement**: Works on shared hosting
- **Implementation**: Minimal resource usage
- **Protection**: Fallback mechanisms
- **Testing**: Test on various hosting environments

### 3. **Database Compatibility**
- **Requirement**: Supports multiple databases
- **Implementation**: Database abstraction layer
- **Protection**: Text database fallback
- **Testing**: Test all supported databases

### 4. **Operating System Compatibility**
- **Requirement**: Works on Windows/Linux/Mac
- **Implementation**: Cross-platform paths
- **Protection**: Path separator handling
- **Testing**: Test on all operating systems

## Migration Rules (Follow Strictly)

### 1. **Version Checking**
- **Rule**: Always check current version before migration
- **Implementation**: Version comparison logic
- **Protection**: Skip if already migrated
- **Testing**: Test various version scenarios

### 2. **Backup Before Migration**
- **Rule**: Always backup before schema changes
- **Implementation**: Automatic backup creation
- **Protection**: Verify backup integrity
- **Testing**: Test backup and restore procedures

### 3. **Incremental Updates**
- **Rule**: Apply updates in version order
- **Implementation**: Sequential migration files
- **Protection**: Version dependency checking
- **Testing**: Test complete migration sequence

### 4. **Rollback Capability**
- **Rule**: Always provide rollback migration
- **Implementation**: Reverse migration scripts
- **Protection**: Rollback verification
- **Testing**: Test rollback procedures

## Error Handling Rules (Never Change)

### 1. **Silent Production Errors**
- **Rule**: Suppress errors in production
- **Implementation**: `@error_reporting(0)` and `@ini_set('display_errors', '0')`
- **Protection**: Development mode detection
- **Testing**: Verify error suppression

### 2. **Graceful Degradation**
- **Rule**: System continues despite errors
- **Implementation**: Fallback mechanisms
- **Protection**: Error recovery procedures
- **Testing**: Simulate various error conditions

### 3. **User-Friendly Messages**
- **Rule**: Never show technical errors to users
- **Implementation**: Generic error messages
- **Protection**: Detailed error logging
- **Testing**: Verify error message display

### 4. **Operator Error Notification**
- **Rule**: Notify operators of system errors
- **Implementation**: Error logging and alerts
- **Protection**: Error escalation procedures
- **Testing**: Test error notification systems

## Performance Rules (Maintain Standards)

### 1. **Resource Conservation**
- **Rule**: Minimal memory and CPU usage
- **Implementation**: Lightweight data structures
- **Protection**: Resource usage monitoring
- **Testing**: Monitor resource consumption

### 2. **Database Efficiency**
- **Rule**: Simple, efficient queries
- **Implementation**: Indexed queries, limited results
- **Protection**: Query optimization
- **Testing**: Analyze query performance

### 3. **Caching Strategy**
- **Rule**: Cache frequently accessed data
- **Implementation**: File-based caching
- **Protection**: Cache invalidation
- **Testing**: Verify cache effectiveness

### 4. **Scalability Limits**
- **Rule**: Support concurrent operators
- **Implementation**: Connection pooling
- **Protection**: Load balancing
- **Testing**: Stress testing scenarios

## Security Rules (Never Compromise)

### 1. **Input Validation**
- **Rule**: Never trust user input
- **Implementation**: Comprehensive input filtering
- **Protection**: Multiple validation layers
- **Testing**: Test various attack vectors

### 2. **SQL Injection Prevention**
- **Rule**: Parameterized queries only
- **Implementation**: Input escaping and validation
- **Protection**: Query pattern validation
- **Testing**: SQL injection testing

### 3. **XSS Prevention**
- **Rule**: Escape all output
- **Implementation**: Output encoding
- **Protection**: Content Security Policy
- **Testing**: XSS testing scenarios

### 4. **Session Security**
- **Rule**: Secure session handling
- **Implementation**: Session validation and timeout
- **Protection**: Session hijacking prevention
- **Testing**: Session security testing

## Testing Rules (Never Skip)

### 1. **Installation Testing**
- **Rule**: Test fresh installation every time
- **Implementation**: Automated installation tests
- **Protection**: Installation verification
- **Testing**: Complete installation scenarios

### 2. **Upgrade Testing**
- **Rule**: Test all upgrade paths
- **Implementation**: Version-to-version testing
- **Protection**: Upgrade verification
- **Testing**: All version combinations

### 3. **Operator Workflow Testing**
- **Rule**: Test all operator workflows
- **Implementation**: Workflow automation
- **Protection**: Workflow validation
- **Testing**: Complete operator scenarios

### 4. **Performance Testing**
- **Rule**: Test performance regression
- **Implementation**: Performance benchmarks
- **Protection**: Performance monitoring
- **Testing**: Load and stress testing

---

**These stability rules represent the hard-won wisdom of 25 years of production operation. Violating any of these rules risks system stability, operator trust, and data integrity. These rules must be followed without exception in all future development.**

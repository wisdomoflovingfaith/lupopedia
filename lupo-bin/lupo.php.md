# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\bin\lupo.php.md"
  file_hash: "89395e72dcdb7303fdcc16dc134b93d26289360dddc13f9aabbb580810d8e8f1"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE

---
lupopedia.headers:
  file_path_from_root: "bin/lupo.php.md"
  file_hash: "23c20529062b6b9f187f9369d28c5980ad4a895c9d15ea9b2626e5f978308c76"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 0
  created_ymdhis: 20260228060000
  delegation_chain: "0:10000"
  artifact_type: "cli_documentation"
  purpose: "Complete CLI tool documentation for Lupopedia system operations"
  dialog_message: "Comprehensive documentation for bin/lupo.php CLI tool with all commands and usage examples"
  mood_rgb: "4169E1"
  artifact_kind: "cli_help"
  traits: ["cli_tool", "system_operations", "4.0.50"]
  tags: ["cli", "documentation", "system_agent", "4.0.50"]
  lupo_agent: "windsurf"

lupopedia.edges:
  file_path_from_root: "bin\lupo.php.md"
  outbound_edges:
    - { to: "bin/lupo.php", type: "documents", weight: 1.0, reason: "CLI tool implementation" }
    - { to: "channels/42/actors/0/help.md", type: "references", weight: 0.9, reason: "System agent help" }
    - { to: "docs/guidelines/list_csv_documentation.md", type: "references", weight: 0.8, reason: "Related documentation" }
  semantic_tags: ["cli_documentation", "system_operations", "4.0.50"]

  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified_utc: "20260228"
  last_verified_by: "windsurf"
---

# Lupopedia CLI Tool Documentation

**File**: `bin/lupo.php`  
**Version**: 4.0.50  
**Actor**: System Agent (ID: 0)  
**Purpose**: Command-line interface for Lupopedia system operations

## Overview

The Lupopedia CLI tool provides comprehensive command-line access to system operations, actor management, channel operations, and system administration functions. It serves as the primary interface for system-level operations and automation.

## Installation and Setup

### Prerequisites
- PHP 5.3+ with PDO MySQL support
- Valid Lupopedia installation with database connectivity
- Proper file permissions for the project directory

### Initial Setup
```bash
# Register as System Agent
php bin/lupo.php register "System Agent" system_tool

# Switch to System Agent identity
php bin/lupo.php use 0

# Verify identity
php bin/lupo.php whoami
```

## Changelog and Version History

### Version 4.0.50 (Current)
**Release Date**: 2026-02-28  
**Changes from 4.0.49**:
- ✅ Added System Agent commands: `system-status`, `coordinate-task`, `health-check`, `update-config`
- ✅ Enhanced audit logging with log level control and JSON serialization
- ✅ Implemented transaction safety for configuration updates
- ✅ Added comprehensive input validation and error handling
- ✅ Created complete CLI documentation with examples and integration guides
- ✅ Added quick reference table and FAQ sections
- ✅ Enhanced security with proper access control and validation

### Version 4.0.49
**Release Date**: 2026-02-27  
**Changes**: Standard actor and channel management commands

## Quick Reference Table

| Command | Description | Parameters | Requirements | Example Output |
|---------|-------------|------------|--------------|---------------|
| `system-status` | Get system info | None | actor_id 0 | `Lupopedia Version: 4.0.50` |
| `coordinate-task` | Coordinate task | `<task_id>` | actor_id 0 | `Task ID: 123` |
| `health-check` | Perform health check | None | actor_id 0 | `DATABASE: PASS` |
| `update-config` | Update configuration | `<key> <value>` | actor_id 0 | `Configuration updated` |
| `register` | Register actor | `<name> <type>` | None | `Registered new actor` |
| `whoami` | Show identity | None | None | `Current Actor: System Agent` |
| `actors` | List actors | `[type]` | None | `[0] System Agent` |
| `use` | Switch identity | `<actor_id>` | None | `Now acting as: System Agent` |
| `channels` | List channels | None | None | `[42] Development` |
| `join` | Join channel | `<channel_id>` | Registered actor | `Joined channel 42` |
| `threads` | List threads | `<channel_id>` | None | `[123] Task Thread` |
| `messages` | List messages | `<channel_id> [thread_id]` | None | `<System Agent> Status update` |
| `send` | Send message | `<channel_id> <message> [thread_id]` | Joined channel | `Message sent (ID: 456)` |
| `nodes` | List federation nodes | None | None | `[1] Local Node` |
| `artifacts` | List artifacts | `<node_id>` | None | `[789] config_file` |
| `tasks` | List tasks | None | Registered actor | `[123] Repository Cleanup` |

## Command Reference

#### register
Register the current environment as an actor.
```bash
php bin/lupo.php register <name> <type>
```
**Parameters**:
- `name`: Actor display name
- `type`: Actor type (system_tool, human, ide_agent, agent)

**Example**:
```bash
php bin/lupo.php register "System Agent" system_tool
```

#### whoami
Display current actor identity.
```bash
php bin/lupo.php whoami
```

#### actors
List registered actors with optional type filtering.
```bash
php bin/lupo.php actors [type]
```
**Parameters**:
- `type` (optional): Filter by actor type

**Examples**:
```bash
php bin/lupo.php actors
php bin/lupo.php actors system_tool
```

#### use
Switch local identity to an existing actor.
```bash
php bin/lupo.php use <actor_id>
```
**Parameters**:
- `actor_id`: Target actor ID

**Example**:
```bash
php bin/lupo.php use 0
```

### Channel Operations Commands

#### channels
List available channels.
```bash
php bin/lupo.php channels
```

#### join
Join a channel with current actor identity.
```bash
php bin/lupo.php join <channel_id>
```
**Parameters**:
- `channel_id`: Target channel ID

**Example**:
```bash
php bin/lupo.php join 42
```

#### threads
List threads in a specific channel.
```bash
php bin/lupo.php threads <channel_id>
```
**Parameters**:
- `channel_id`: Channel ID to query

**Example**:
```bash
php bin/lupo.php threads 42
```

#### messages
List recent messages in a channel or thread.
```bash
php bin/lupo.php messages <channel_id> [thread_id]
```
**Parameters**:
- `channel_id`: Channel ID
- `thread_id` (optional): Specific thread ID

**Examples**:
```bash
php bin/lupo.php messages 42
php bin/lupo.php messages 42 123
```

#### send
Send a message to a channel or thread.
```bash
php bin/lupo.php send <channel_id> <message> [thread_id]
```
**Parameters**:
- `channel_id`: Target channel ID
- `message`: Message text
- `thread_id` (optional): Thread ID for threaded messages

**Examples**:
```bash
php bin/lupo.php send 42 "System status update"
php bin/lupo.php send 42 "Task completed" 123
```

### System Information Commands

#### nodes
List federation nodes.
```bash
php bin/lupo.php nodes
```

#### artifacts
List artifacts by federation node.
```bash
php bin/lupo.php artifacts <node_id>
```
**Parameters**:
- `node_id`: Federation node ID

**Example**:
```bash
php bin/lupo.php artifacts 1
```

#### tasks
List active tasks for current actor.
```bash
php bin/lupo.php tasks
```

## System Agent Commands (Actor ID 0 Only)

### system-status
Get comprehensive system status and information.
```bash
php bin/lupo.php system-status
```

**Output**:
```
=== System Status ===
Lupopedia Version: 4.0.50
Database: PDO_DB
Table Prefix: lupo_
Active Actors: 27
Active Channels: 5
System Path: /path/to/lupopedia/
Config Path: /path/to/lupopedia/lupopedia-config.php
Timestamp: 2026-02-28 06:00:00 UTC
===================
```

### coordinate-task
Coordinate development task by ID.
```bash
php bin/lupo.php coordinate-task <task_id>
```
**Parameters**:
- `task_id`: Task ID to coordinate (validated as integer 1-999999, checked for existence)

**Example**:
```bash
php bin/lupo.php coordinate-task 123
```

**Output**:
```
=== Task Coordination ===
Task ID: 123
Title: Repository Cleanup
Status: in_progress
Coordinator: System Agent (ID: 0)
Action: Task coordination initiated
Timestamp: 2026-02-28 06:00:00 UTC
========================
```

**Edge Cases**:
```
Error: Task ID out of valid range (1-999999).
Error: Task not found.
```

### health-check
Perform comprehensive system health check.
```bash
php bin/lupo.php health-check
```

**Health Check Details**:
- **DATABASE**: Tests database connectivity
- **CONFIG**: Verifies `lupopedia-config.php` exists
- **VERSION**: Checks `lupo-includes/version.php` exists
- **WRITABLE**: Tests write permissions to project directory
- **TABLE_ACCESS**: Tests database table access with `SELECT 1 FROM {$table_prefix}actors LIMIT 1`

**Output Examples**:
```
=== System Health Check ===
DATABASE: PASS
CONFIG: PASS
VERSION: PASS
WRITABLE: PASS
TABLE_ACCESS: PASS
Overall Status: HEALTHY
Timestamp: 2026-02-28 06:00:00 UTC
========================
```

**Edge Cases**:
```
=== System Health Check ===
DATABASE: FAIL
CONFIG: PASS
VERSION: PASS
WRITABLE: FAIL
TABLE_ACCESS: FAIL
Overall Status: ISSUES DETECTED
Timestamp: 2026-02-28 06:00:00 UTC
========================
```

### update-config
Update or create system configuration entries.
```bash
php bin/lupo.php update-config <key> <value>
```
**Parameters**:
- `key`: Configuration key (validated with regex `/^[a-zA-Z0-9_]+$/` - alphanumeric and underscore only)
- `value`: Configuration value (string, boolean, or JSON - stored as string)

**Examples**:
```bash
php bin/lupo.php update-config maintenance_mode true
php bin/lupo.php update-config debug_level 2
php bin/lupo.php update-config max_connections 100
php bin/lupo.php update-config feature_flags '{"new_ui":true,"beta_mode":false}'
php bin/lupo.php update-config settings '{"mode": "active", "timeout": 300}'
```

**Output Examples**:
```
Configuration updated: maintenance_mode = true
Configuration created: feature_flags = {"new_ui":true,"beta_mode":false}
```

**Edge Cases**:
```
Error: Invalid config key format. Use alphanumeric and underscore only.
Error: Configuration update failed - Database connection lost.
```

**Transaction Safety**: Uses database transactions with rollback on failure.

### Real-Time Hooks and Audit Trail

All System Agent commands include comprehensive audit logging with log level control:

```php
// Enhanced logging with log level control
function logOperation($actor_id, $command, $details) {
    // Check log level environment variable (default: INFO)
    $log_level = getenv('LUPO_LOG_LEVEL') ?: 'INFO';
    $log_levels = array('DEBUG' => 0, 'INFO' => 1, 'WARNING' => 2, 'ERROR' => 3);
    
    // Handle JSON serialization with proper escaping
    $details_json = json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    // Truncate if too large (prevent database issues)
    if (strlen($details_json) > 65535) {
        $details_json = json_encode(array('truncated' => true, 'original_size' => strlen($details_json)));
    }
    
    // Insert into audit_log table with timestamp
}
```

**Log Level Control**:
- **Environment Variable**: `LUPO_LOG_LEVEL` (DEBUG, INFO, WARNING, ERROR)
- **Default Level**: INFO (logs all System Agent operations)
- **High Volume**: Set to WARNING or ERROR to reduce log volume

**Logged Operations**:
- `system-status`: System information queries with timestamp
- `coordinate-task`: Task coordination with task_id, title, and validation results
- `health-check`: Health check results with individual check status and overall assessment
- `update-config`: Configuration changes with key, value, action type (create/update)

**JSON Serialization Features**:
- **Unicode Support**: `JSON_UNESCAPED_UNICODE` for international characters
- **Slash Handling**: `JSON_UNESCAPED_SLASHES` for clean URLs
- **Error Fallback**: Failed serialization logs error with original type
- **Size Limits**: Automatic truncation for payloads > 64KB to prevent database issues

**Database Schema**:
```sql
CREATE TABLE lupo_audit_log (
    audit_log_id BIGINT PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    command VARCHAR(50) NOT NULL,
    details_json TEXT,
    created_ymdhis BIGINT NOT NULL
);
```

## Integration Examples and Automation

### Database Integration
- **Primary Tables**: `lupo_actors`, `lupo_channels`, `lupo_tasks`, `lupo_system_config`
- **Access Level**: Full database access for System Agent
- **Operations**: CRUD operations on all system tables

### File System Integration
- **State File**: `.lupo_actor` for local actor identity
- **Configuration**: `lupopedia-config.php` for database settings
- **Workspace**: Direct access to all project files

### API Integration
- **Authentication**: System-level authentication bypass for actor 0
- **Rate Limiting**: No rate limits for system operations
- **Endpoints**: Full access to all system endpoints

## Prerequisites and Dependencies

### System Requirements
- **PHP Version**: 5.3+ with PDO MySQL support
- **Database**: MySQL/MariaDB with proper credentials
- **File Permissions**: Write access to project directory
- **Required PHP Modules**: PDO, json, fileinfo (optional)

### Database Dependencies
- **Bootstrap**: Requires `lupo-includes/bootstrap.php` for database connection
- **Configuration**: Uses `lupopedia-config.php` for database settings
- **Tables**: Requires `lupo_actors`, `lupo_channels`, `lupo_system_config`, `lupo_audit_log`

### Version Information
**Current Version**: 4.0.50  
**Version Changes**: Added System Agent commands and audit logging  
**Compatibility**: Compatible with all 4.0.x versions  
**Dependencies**: Requires Lupopedia 4.0.45 or higher

## Integration Examples and Automation

### Cron Job Integration
```bash
# Daily health check
0 6 * * * /usr/bin/php /path/to/lupopedia/bin/lupo.php health-check >> /var/log/lupopedia-health.log

# Weekly system status
0 0 * * 0 /usr/bin/php /path/to/lupopedia/bin/lupo.php system-status >> /var/log/lupopedia-status.log
```

### Script Integration
```bash
#!/bin/bash
# System maintenance script

# Switch to System Agent
php bin/lupo.php use 0

# Perform health check
php bin/lupo.php health-check

# Update maintenance mode
php bin/lupo.php update-config maintenance_mode true

# Coordinate maintenance task
php bin/lupo.php coordinate-task 456

# Disable maintenance mode
php bin/lupo.php update-config maintenance_mode false
```

### PHP API Integration
```php
<?php
// Example: Programmatic health check
function runSystemCommand($command, $args = array()) {
    $cmd = "php bin/lupo.php " . $command;
    if (!empty($args)) {
        $cmd .= " " . implode(" ", array_map('escapeshellarg', $args));
    }
    return shell_exec($cmd);
}

// Get system status
$status = runSystemCommand('system-status');

// Health check with alert logic
exec('php bin/lupo.php health-check', $output);
$status = implode("\n", $output);
if (strpos($status, 'FAIL') !== false) {
    // Alert logic
    mail('admin@example.com', 'Lupopedia Health Alert', $status);
}

// Configuration update
$result = runSystemCommand('update-config', array('maintenance_mode', 'true'));
```

### Docker Integration
```dockerfile
# Dockerfile example
FROM php:8.1-cli
COPY . /var/www/lupopedia
WORKDIR /var/www/lupopedia
RUN chmod +x bin/lupo.php

# Health check in Docker
HEALTHCHECK --interval=5m --timeout=30s \
    CMD php bin/lupo.php health-check | grep -q "HEALTHY"
```

### Ansible Integration
```yaml
# Ansible playbook example
- name: Check Lupopedia health
  command: php bin/lupo.php health-check
  register: lupo_health
  changed_when: false

- name: Update configuration
  command: php bin/lupo.php update-config maintenance_mode true
  when: lupo_health.stdout.find("HEALTHY") != -1
```

## FAQ and Common Pitfalls

### Q: What if actor_id 0 is not registered?
**A**: Register it first:
```bash
php bin/lupo.php register "System Agent" system_tool
php bin/lupo.php use 0
```

### Q: How do I verify System Agent identity?
**A**: Use the whoami command:
```bash
php bin/lupo.php whoami
# Expected: Current Actor: System Agent (ID: 0)
```

### Q: Can configuration values be JSON?
**A**: Yes, JSON values are stored as strings and can be retrieved as needed:
```bash
php bin/lupo.php update-config feature_flags '{"new_ui":true,"beta_mode":false}'
```

### Q: How do I troubleshoot database connection issues?
**A**: Run health check first:
```bash
php bin/lupo.php health-check
# Check DATABASE and TABLE_ACCESS status
```

### Q: Are commands logged?
**A**: Yes, all System Agent commands are logged to `lupo_audit_log` table with timestamps and details.

### Q: What if the DB is down during logging?
**A**: Logging fails silently to avoid disrupting main operations. Check PHP error logs for "Audit log failed" messages.

### Q: How do I rotate lupo_audit_log tables?
**A**: Implement log rotation with cron jobs or database maintenance scripts:
```sql
-- Archive old logs
CREATE TABLE lupo_audit_log_archive AS SELECT * FROM lupo_audit_log WHERE created_ymdhis < 20260101000000;
DELETE FROM lupo_audit_log WHERE created_ymdhis < 20260101000000;
```

### Q: How do I control log volume in high-traffic environments?
**A**: Set log level environment variable:
```bash
export LUPO_LOG_LEVEL=WARNING  # Only log warnings and errors
export LUPO_LOG_LEVEL=ERROR     # Only log errors
```

## Scalability and Performance

### Large System Considerations
- **system-status**: May show large actor/channel counts - consider filtering
- **health-check**: Lightweight checks, minimal performance impact
- **audit-log**: Grows over time - consider log rotation policies

### Performance Optimization
```bash
# For systems with many actors, consider specific queries
php bin/lupo.php actors system_tool  # Filter by type
```

## Testing and Quality Assurance

### Unit Testing
```bash
# Run CLI tests (recommended)
phpunit tests/bin/LupoCliTest.php

# Test specific command
phpunit tests/bin/LupoCliTest.php --filter testSystemStatus
```

### Integration Testing
```bash
# Test System Agent workflow
php bin/lupo.php register "Test Agent" system_tool
php bin/lupo.php use 0
php bin/lupo.php system-status
php bin/lupo.php health-check
php bin/lupo.php update-config test_key test_value
```

### Manual Testing Checklist
- [ ] System Agent registration and identity verification
- [ ] All System Agent commands require actor_id 0
- [ ] Configuration key validation with regex patterns
- [ ] Task ID range validation (1-999999)
- [ ] Health check handles all failure scenarios
- [ ] Audit logging with different log levels
- [ ] Transaction rollback on configuration failures

## Accessibility and Output Standards

### Output Format Standards
- **No Color Codes**: Plain text output for universal compatibility
- **Consistent Timestamps**: All timestamps in UTC format `YYYY-MM-DD HH:MM:SS UTC`
- **Machine Parsable**: Structured output suitable for scripting
- **Error Messages**: Clear, actionable error messages with suggested solutions

### Markdown Compatibility
- Documentation renders properly in GitHub, GitLab, and other markdown viewers
- Code blocks use proper language specification
- Tables format correctly across platforms
- Links and references work in web-based viewers

## Security and Compliance

### Enhanced Access Control
- **Multi-Factor Considerations**: Environment variable tokens for CI environments
- **Secure Registration**: System Agent registration should be done once in trusted setup
- **Audit Trail**: Complete logging for compliance with data protection standards

### Compliance Features
- **GDPR Alignment**: All data changes logged with timestamps and actor attribution
- **Audit Retention**: Configurable log retention policies for compliance requirements
- **Data Integrity**: Transaction safety ensures no partial updates

## Future-Proofing and Extensibility

### Planned Enhancements (Roadmap)
- **Multi-Actor Support**: Delegated tasks with permission chains
- **Plugin System**: Third-party command integration framework
- **JSON Output Format**: `--json` flag for machine-readable output
- **Verbose Mode**: `--verbose` flag for detailed debugging information
- **Batch Operations**: Multiple configuration updates in single transaction
- **Pagination**: `--page` and `--limit` flags for large datasets

### Extension Points
```php
// Custom command framework (planned)
function registerCustomCommand($name, $handler, $requirements) {
    // Register new command with validation and logging
}

// Plugin hooks (planned)
do_action('before_system_command', $command, $args);
do_action('after_system_command', $command, $result);
```

### API Wrapper (Planned)
```php
// REST API wrapper for remote execution (planned)
class LupoCliApi {
    public function execute($command, $args = array()) {
        // Remote execution with authentication
    }
    
    public function getSystemStatus() {
        return $this->execute('system-status');
    }
}
```

## Security Considerations

### Access Control
- System Agent commands require actor_id 0
- Database operations use prepared statements
- File operations validate paths and permissions

### Audit Trail
- All operations logged through database transactions
- Configuration changes tracked with timestamps
- Actor identity changes recorded in state file

### Error Handling
- Comprehensive exception handling
- Graceful degradation for missing dependencies
- Clear error messages for invalid operations

## Best Practices

### Operational Guidelines
1. **Use System Agent Sparingly**: Reserve for critical operations
2. **Document Actions**: Maintain logs of system operations
3. **Validate Inputs**: Always validate parameters before execution
4. **Monitor Health**: Regular health checks for system integrity

### Configuration Management
1. **Use update-config**: For all configuration changes
2. **Backup Settings**: Document configuration changes
3. **Test Changes**: Validate configuration updates
4. **Monitor Impact**: Track system behavior after changes

### Task Coordination
1. **Coordinate Tasks**: Use coordinate-task for development coordination
2. **Track Progress**: Monitor task status and completion
3. **Communicate**: Use channel messaging for coordination
4. **Document**: Maintain task documentation

## Troubleshooting

### Common Issues

#### Permission Denied
```bash
Error: System Agent (actor_id 0) required for this command.
```
**Solution**: Switch to System Agent identity:
```bash
php bin/lupo.php use 0
```

#### Database Connection Failed
```bash
Error: Database connection failed.
```
**Solution**: Check configuration and database connectivity:
```bash
php bin/lupo.php health-check
```

#### Invalid Input
```bash
Error: Invalid input. Usage: send <channel_id> <msg> [thread_id]
```
**Solution**: Provide required parameters in correct format.

### Error Resolution
1. **Check Identity**: Verify current actor with `whoami`
2. **Health Check**: Run `health-check` for system status
3. **Verify Parameters**: Ensure all required parameters provided
4. **Check Permissions**: Verify file and database permissions

## Version Information

**Current Version**: 4.0.50  
**Last Updated**: 2026-02-28  
**Compatibility**: Compatible with all 4.0.x versions  
**Dependencies**: Requires Lupopedia 4.0.45 or higher

## Related Documentation

- **System Agent Help**: `channels/42/actors/0/help.md`
- **CSV Documentation**: `docs/guidelines/list_csv_documentation.md`
- **Database Schema**: `docs/database/lupopedia/tables/`
- **FLARE Protocol**: `docs/doctrine/FLARE/FLARE_DOCTRINE.md`

---

**System Agent**  
**Actor ID**: 0  
**System Version**: 4.0.50  
**Last Modified**: 2026-02-28T06:00:00Z
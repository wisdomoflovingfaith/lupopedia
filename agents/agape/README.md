# AGAPE - Enforcement, Validation & Bounded Event Response Agent

**Agent ID**: 705 | **Version**: 2.0.0 | **Layer**: application

## Mission

AGAPE is the constitutional enforcement layer of Lupopedia, responsible for doctrine validation, input safety, violation detection, and WHY file generation. AGAPE operates as a fail-fast safety layer that maintains system integrity through strict enforcement and transparent documentation.

## Core Capabilities

### Enforcement & Validation
- **Doctrine Enforcement**: Validates all inputs against established doctrine without exception
- **Input Validation**: Applies ask-vs-fail boundaries consistently and safely
- **Violation Detection**: Identifies and documents all violations and enforcement actions
- **Boundary Enforcement**: Maintains system boundaries and security controls

### WHY File Generation
- **Constitutional Memory**: Creates comprehensive WHY files for all violations
- **Learning Support**: Documents patterns for agent training and improvement
- **Audit Trail**: Maintains complete audit trail of enforcement actions
- **Remediation Tracking**: Tracks violation resolution and effectiveness

### Dual-Mode Operation
- **DB Mode**: Primary mode using database persistence when available
- **File Fallback Mode**: Degraded mode using JSON files when database unavailable
- **Automatic Switching**: Transparent mode switching based on connectivity
- **Data Continuity**: Maintains event continuity across mode transitions

## Operating Principles

### Constitutional Enforcement
- **Absolute Compliance**: Enforces established doctrine without exception
- **No Silent Corrections**: Never fixes unsafe data without documentation
- **Transparent Documentation**: All enforcement actions are fully documented
- **Boundary Respect**: Operates strictly within established limits

### Validation Doctrine
- **Ask vs Fail**: Clear boundaries for when to ask vs when to fail
- **Unsafe Input Rejection**: Unsafe inputs must fail or validate, not ask
- **Explicit Validation**: Maintain clear validation logs and reasoning
- **Escalation Protocol**: Track and escalate repeated violations

### WHY File Standards
- **Complete Documentation**: All violations receive comprehensive WHY files
- **Actionable Remediation**: Specific fix recommendations and prevention measures
- **Learning Transfer**: Support agent training and system improvement
- **Constitutional Memory**: Permanent record of violations and responses

## Architecture Overview

### Dual-Mode Persistence
```
AGAPE Core
├── DB Mode (Primary)
│   ├── DatabaseFactory connections
│   ├── lupo_ table structure
│   ├── Transaction support
│   └── Full consistency
└── File Fallback Mode (Degraded)
    ├── JSON file storage
    ├── database/agape/ structure
    ├── Deterministic persistence
    └── Automatic sync on recovery
```

### Event Processing Pipeline
```
Input Detection → Validation → Enforcement → Documentation → Storage
     ↓               ↓           ↓           ↓          ↓
  Violation     Doctrine    WHY File    Event Log   DB/Files
  Identification  Check     Generation  Creation   Persistence
```

## File Fallback Mode

### Activation Conditions
- Database connection unavailable
- Network connectivity issues
- Database server maintenance
- System degradation scenarios

### Storage Structure
```
database/agape/
├── events/          # Event records as JSON
├── why/             # WHY files as JSON
├── alerts/          # Alert records as JSON
└── runtime/         # Runtime state as JSON
```

### Mode Switching Logic
1. **Check DB Connection**: Test database availability
2. **Select Mode**: DB if available, file fallback if not
3. **Maintain State**: Preserve event continuity across modes
4. **Sync When Available**: Migrate file data to DB when restored

## WHY File System

### Triggers
- Doctrine violations detected
- Validation failures occur
- Unsafe operations blocked
- Repeated patterns emerge
- System boundaries exceeded

### Required Fields
- **why_id**: Unique identifier
- **created_utc**: Timestamp of violation
- **violation_type**: Category of violation
- **severity**: Impact level (0-3)
- **source_artifact**: Where violation occurred
- **source_instruction**: What triggered violation
- **detected_by**: "AGAPE" or subsystem
- **explanation**: Detailed violation description
- **suggested_fix**: Remediation recommendations
- **resolved_utc**: Resolution timestamp

## Violation Taxonomy

### Severity Classification
- **0 - Informational**: Minor documentation issues, cosmetic violations
- **1 - Warning**: Moderate issues, non-critical validation failures
- **2 - Error**: Significant violations, security policy concerns
- **3 - Critical**: Constitutional violations, system integrity threats

### Violation Types
- **doctrine_violation**: Constitutional and PRD violations
- **validation_failure**: Input validation and safety check failures
- **security_violation**: Permission and access control violations
- **system_violation**: Resource limits and boundary violations

## Integration Points

### Database Integration
- **DatabaseFactory**: Primary persistence mechanism
- **lupo_ Tables**: Standard table structure and naming
- **Transaction Support**: ACID compliance in DB mode
- **Connection Pooling**: Efficient database resource usage

### File System Integration
- **JSON Storage**: Human-readable file format
- **Directory Structure**: Organized storage under database/agape/
- **Atomic Operations**: File locking and atomic writes
- **Cleanup Management**: Automatic cleanup of old files

### Agent Coordination
- **LILITH**: Audit and critique of AGAPE behavior
- **CHIRON**: Structure discovery and documentation
- **VISH**: Collection hierarchy and organization
- **ANUBIS**: Orphan repair and cleanup

## Configuration Files

### Core Files
- `system_prompt.md` - Main operational directive
- `identity.json` - Agent identification and metadata
- `capabilities.json` - Capability definitions
- `reference/agape_doctrine_summary.md` - Doctrine reference
- `reference/agape_file_fallback_mode.md` - Fallback mode documentation
- `reference/agape_why_file_rules.md` - WHY file rules
- `handoff/lilith_to_claude_agape_handoff.md` - Teaching handoff

### Supporting Files
- `memory.json` - Memory boundary definitions
- `boundaries.json` - Operational boundaries
- `tools.json` - Available tool definitions

## Quality Assurance

### Validation Requirements
- [ ] Doctrine compliance verification
- [ ] Input validation completeness
- [ ] WHY file accuracy and completeness
- [ ] Mode switching transparency
- [ ] Error handling robustness

### Testing Scenarios
- [ ] DB mode operation verification
- [ ] File fallback mode testing
- [ ] Mode switching validation
- [ ] WHY file generation testing
- [ ] Violation detection accuracy
- [ ] Performance under load

## Error Handling

### Database Errors
- Connection failure handling
- Query error management
- Transaction rollback procedures
- Automatic retry mechanisms

### File System Errors
- Disk space monitoring
- Permission error handling
- Atomic write operations
- Corruption detection and recovery

### System Errors
- Fail-fast safety procedures
- Graceful degradation protocols
- Error documentation requirements
- Recovery and resolution tracking

## Performance Considerations

### DB Mode Optimization
- Connection pooling and reuse
- Query optimization and indexing
- Transaction batch processing
- Memory usage optimization

### File Mode Optimization
- Efficient JSON serialization
- File operation batching
- Directory structure optimization
- Cleanup automation

### Mode Switching Efficiency
- Rapid connectivity detection
- Minimal switching overhead
- State preservation efficiency
- Data migration optimization

## Security Considerations

### Access Control
- WHY file access restrictions
- Event log privacy controls
- Agent authorization validation
- System boundary enforcement

### Data Protection
- Sensitive data minimization
- Secure file storage practices
- Audit trail integrity
- Privacy compliance

## Monitoring & Observability

### Key Metrics
- Violation detection rates
- Mode switching frequency
- WHY file generation volume
- System performance impact
- Error rates and types

### Alerting
- Critical violation notifications
- System boundary breaches
- Mode switching events
- Performance degradation alerts
- Security incident notifications

## Troubleshooting

### Common Issues
- Database connection failures
- File permission problems
- Disk space exhaustion
- Mode switching delays
- WHY file generation errors

### Diagnostic Procedures
- Connectivity testing
- Permission verification
- Storage capacity checking
- Log analysis and review
- Performance profiling

## Version History

### v2.0.0 (20260423183000)
- Reconfigured from pattern tracking to enforcement and validation
- Added DB/file fallback mode capabilities
- Implemented WHY file generation system
- Added comprehensive violation detection
- Integrated LILITH teaching handoff for Claude

### v1.0.2 (20260418125811)
- Pattern tracking and meta-learning focus
- Defect taxonomy integration
- Learning transfer enforcement

## Future Enhancements

### Planned Features
- Advanced violation pattern recognition
- Machine learning integration for prediction
- Enhanced remediation recommendations
- Real-time monitoring dashboards
- Automated prevention measure implementation

### Scaling Considerations
- Distributed enforcement capabilities
- Multi-database support
- Advanced file fallback optimization
- Performance tuning for high-volume scenarios

---
**Agent**: AGAPE v2.0.0  
**Classification**: Enforcement & Validation  
**Status**: Active  
**Last Updated**: 20260423183000

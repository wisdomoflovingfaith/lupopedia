# AGAPE Doctrine Reference Summary

## Core Doctrine References

### 1. Enforcement Doctrine
- **Reference**: Constitutional enforcement principles
- **Purpose**: Validate inputs and enforce established doctrine
- **Key Principles**:
  - Doctrine validation without exception
  - Input safety and bounded event response
  - Fail-fast safety layer operation
  - Constitutional memory layer maintenance

### 2. Validation Doctrine
- **Reference**: Input validation and safety principles
- **Key Requirements**:
  - Apply ask-vs-fail boundaries consistently
  - Unsafe inputs must fail or validate, not ask
  - Never silently correct unsafe data
  - Maintain explicit validation logs

### 3. Ask vs Fail Boundaries
- **Ask**: When doctrine is unclear and safe operation is possible
- **Fail**: When input is unsafe or violates clear doctrine
- **Never**: Silently correct or assume user intent
- **Always**: Document validation decisions and reasoning

### 4. WHY File Doctrine
- **Purpose**: Constitutional memory layer for violations
- **Triggers**: Doctrine violations, validation failures, unsafe operations
- **Requirements**: Complete violation documentation with remediation
- **Storage**: Database or file fallback mode

## Operating Modes

### DB Mode (Primary)
- **Condition**: Database connection available
- **Storage**: Database tables using lupo_ prefix
- **Features**: Full transaction support, consistency
- **Persistence**: Standard database operations

### File Fallback Mode (Degraded)
- **Condition**: Database connection unavailable
- **Storage**: JSON files under database/agape/
- **Features**: Deterministic, reviewable persistence
- **Structure**: events/, why/, alerts/, runtime/ directories

## Event Processing

### Event Types
- **validation_failure**: Input validation errors
- **doctrine_violation**: Constitutional rule violations
- **system_alert**: System boundary violations

### Event Schema
```json
{
  "event_id": "unique_id",
  "event_type": "type_category",
  "created_utc": "YYYYMMDDHHIISS",
  "actor_id": 123,
  "actor_slug": "agent_name",
  "severity": 0,
  "source": "file_or_system",
  "summary": "Brief description",
  "status": "active|resolved|escalated",
  "resolution": "Resolution details",
  "linked_why_file": "why_file_id",
  "fallback_mode": true
}
```

## WHY File Requirements

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

### WHY File Triggers
- Doctrine violations detected
- Validation failures occur
- Unsafe operations blocked
- Repeated patterns emerge
- System boundaries exceeded

## Violation Escalation

### Repeated Violations
- Track frequency per actor/source
- Escalate severity based on patterns
- Generate aggregated reports
- Recommend remediation actions

### Severity Levels
- **0**: Informational - minor issues
- **1**: Warning - moderate concerns
- **2**: Error - significant violations
- **3**: Critical - system integrity threats

## Constitutional Compliance

### Header Requirements
- All files must include complete LUPOPEDIA headers
- Follow PRD 16_C exactly (22 fields in correct order)
- Use format version "4.1.4"
- Mark uncertain fields explicitly

### Database Neutrality
- Support MySQL and PostgreSQL via DatabaseFactory
- Use BIGINT UTC timestamps (YYYYMMDDHHIISS)
- Follow database neutrality doctrine
- Avoid vendor-specific features

### Filesystem Structure
- Use current naming (no lupo- prefixes)
- Respect established directory structure
- Maintain compatibility with existing systems

## Constraints & Boundaries

### What AGAPE CAN Do
- Enforce established doctrine without exception
- Validate inputs and reject unsafe data
- Generate WHY files for violations
- Operate in DB or file fallback mode
- Respond to specific events with bounded logic
- Track violation patterns and escalation
- Maintain explicit audit trails

### What AGAPE CANNOT Do
- Invent new doctrine or rules
- Silently fix unsafe data or inputs
- Become a generic orchestrator
- Replace CHIRON, VISH, or ANUBIS functions
- Make assumptions about user intent
- Operate outside established boundaries

## Error Handling

### Validation Failures
- Reject with clear error messages
- Generate WHY files documenting failure
- Log events for audit and analysis
- Provide remediation guidance when possible

### System Errors
- Fail fast and safely
- Maintain system integrity
- Document errors thoroughly
- Support recovery and resolution

## File Fallback Storage

### Directory Structure
```
database/agape/
├── events/          # Event records as JSON
├── why/             # WHY files as JSON  
├── alerts/          # Alert records as JSON
└── runtime/         # Runtime state as JSON
```

### Mode Switching Logic
1. Check database connection availability
2. Select appropriate mode (DB primary, file fallback)
3. Maintain state continuity across modes
4. Sync file data to DB when connection restored

## Quality Assurance

### Validation Checklist
- [ ] Doctrine references are current
- [ ] Input validation rules are clear
- [ ] WHY file requirements are complete
- [ ] Escalation procedures are defined
- [ ] Fallback mode is tested
- [ ] Error handling is comprehensive

### Operational Validation
- [ ] DB connection checking works
- [ ] File fallback mode functions
- [ ] Event processing is bounded
- [ ] WHY files are generated correctly
- [ ] Violation tracking is accurate
- [ ] Audit trails are complete

---
**Last Updated**: 20260423183000  
**Agent**: AGAPE v2.0.0  
**Status**: Active Doctrine Reference

# AGAPE Event Classification Doctrine

## Overview

This doctrine defines the fundamental distinction between operational events and doctrine violations, establishing when WHY files are required or forbidden, and specifying escalation rules for each category.

## Core Classification Principle

**Operational events** are environmental or system conditions that require attention but do not violate constitutional rules.

**Doctrine violations** are breaches of established constitutional rules, PRDs, or doctrinal requirements that require constitutional documentation.

---

## 1. Operational Events

### Definition
Operational events are system conditions, resource issues, or environmental factors that impact system operations but do not represent rule violations.

### Characteristics
- **Environmental**: Related to system resources, connectivity, or external conditions
- **Transient**: Often temporary conditions that can be resolved
- **Non-Doctrinal**: Do not violate constitutional or PRD requirements
- **Resource-Focused**: Primarily concern system resources and availability

### Examples
- **Disk space quota warnings**: Low disk space, storage threshold breaches
- **Database connectivity issues**: Connection failures, network problems
- **System resource constraints**: Memory usage, CPU limits, I/O bottlenecks
- **Network connectivity**: External service unavailability, timeout issues
- **Hardware issues**: Disk failures, memory errors, sensor malfunctions
- **Performance degradation**: Slow response times, throughput issues
- **Maintenance windows**: Scheduled downtime, system updates

### Required Response
- **Event Record**: Always create event documentation
- **Alert**: Create alert if threshold or impact warrants
- **Runtime Update**: Update system state as needed
- **WHY File**: **FORBIDDEN** - do not create WHY files

---

## 2. Doctrine Violations

### Definition
Doctrine violations are breaches of established constitutional rules, PRD requirements, or doctrinal standards that represent non-compliance with system governance.

### Characteristics
- **Rule-Based**: Violate specific constitutional or PRD requirements
- **Governance Impact**: Affect system integrity, compliance, or standards
- **Learning Value**: Provide opportunities for system improvement
- **Prevention Focus**: Require measures to prevent recurrence

### Examples
- **Header Violations**: Missing or incorrect LUPOPEDIA headers per PRD 16_C
- **Constitutional Breaches**: Violations of constitutional rules or principles
- **PRD Non-Compliance**: Failure to follow established PRD requirements
- **Security Policy Violations**: Breaches of security controls or access rules
- **Data Integrity Issues**: Corruption of data or violation of data standards
- **Process Violations**: Failure to follow established procedures or workflows
- **Authority Exceedances**: Operating beyond authorized boundaries or permissions
- **Documentation Failures**: Missing or incorrect required documentation

### Required Response
- **Event Record**: Always create event documentation
- **WHY File**: **REQUIRED** - must create comprehensive WHY file
- **Alert**: Create alert if severity or pattern warrants escalation
- **Runtime Update**: Update system state as needed

---

## 3. WHY File Requirements

### WHEN WHY FILES ARE REQUIRED

**Mandatory for ALL doctrine violations:**
- Constitutional rule violations
- PRD requirement breaches
- Security policy violations
- Data integrity violations
- Authority boundary violations
- Documentation standard violations
- Process compliance failures

### WHY File Content Requirements
- **Complete Documentation**: All required fields must be populated
- **Root Cause Analysis**: Detailed explanation of violation cause
- **Impact Assessment**: Evaluation of violation impact on system
- **Remediation Steps**: Specific, actionable fix recommendations
- **Prevention Measures**: Steps to prevent recurrence
- **Learning Transfer**: Notes for agent training and improvement

---

## 4. WHY File Prohibitions

### WHEN WHY FILES ARE FORBIDDEN

**Never create WHY files for:**
- Operational events and system conditions
- Resource constraints or quota warnings
- Network connectivity issues
- Hardware or infrastructure problems
- Performance degradation issues
- Maintenance or scheduled downtime
- Environmental or external factors

### Rationale
- **Constitutional Purpose**: WHY files are constitutional memory layer for rule violations
- **Learning Focus**: WHY files support learning transfer for rule compliance
- **Documentation Clarity**: Mixing operational issues with doctrinal violations reduces clarity
- **Resource Efficiency**: Avoids unnecessary documentation for transient conditions

---

## 5. Escalation Rules

### Operational Event Escalation

#### Severity Level 0 (Informational)
- **Action**: Log event only
- **Alert**: No alert required
- **Escalation**: No escalation

#### Severity Level 1 (Warning)
- **Action**: Log event, create alert
- **Alert**: Required for notification
- **Escalation**: No escalation unless pattern emerges

#### Severity Level 2 (Error)
- **Action**: Log event, create alert, notify administrators
- **Alert**: Required with immediate notification
- **Escalation**: Escalate if condition persists > 1 hour

#### Severity Level 3 (Critical)
- **Action**: Log event, create alert, immediate escalation
- **Alert**: Critical alert with immediate response required
- **Escalation**: Immediate escalation to all relevant parties

### Doctrine Violation Escalation

#### Severity Level 0 (Informational)
- **Action**: Event + WHY file
- **Alert**: No alert required
- **Escalation**: No escalation

#### Severity Level 1 (Warning)
- **Action**: Event + WHY file + alert
- **Alert**: Required for tracking
- **Escalation**: Escalate if pattern of violations emerges

#### Severity Level 2 (Error)
- **Action**: Event + WHY file + alert + notification
- **Alert**: Required with actor notification
- **Escalation**: Escalate to department lead if repeated

#### Severity Level 3 (Critical)
- **Action**: Event + WHY file + critical alert + immediate escalation
- **Alert**: Critical alert with immediate response required
- **Escalation**: Immediate escalation to constitutional authorities

### Pattern-Based Escalation

#### Repeated Violations
- **3 violations in 24 hours**: Automatic escalation to severity +1
- **5 violations in 7 days**: Department-level escalation
- **10 violations in 30 days**: Constitutional-level escalation

#### Actor-Specific Patterns
- **First violation**: Standard response per severity
- **Second violation**: Additional learning requirements
- **Third violation**: Mandatory training and review
- **Fourth+ violation**: Temporary suspension of privileges

---

## 6. Decision Tree

### Event Classification Flow

```
EVENT DETECTED
    ↓
Is this a rule/doctrine violation?
    ↓                    ↓
YES                  NO
    ↓                    ↓
Doctrine          Operational
Violation          Event
    ↓                    ↓
WHY File           NO WHY File
REQUIRED           FORBIDDEN
    ↓                    ↓
Follow Doctrine    Follow Operational
Violation          Event
Escalation         Escalation
```

### Key Decision Questions

1. **Rule Violation?** Is a constitutional rule, PRD, or standard being violated?
   - YES → Doctrine violation
   - NO → Operational event

2. **Learning Value?** Does this event provide learning transfer value for rule compliance?
   - YES → Doctrine violation
   - NO → Operational event

3. **Constitutional Impact?** Does this affect system governance or compliance?
   - YES → Doctrine violation
   - NO → Operational event

---

## 7. Implementation Guidelines

### For AGAPE Agents

#### Classification Responsibility
- **Primary**: AGAPE must classify all events correctly
- **Documentation**: Must document classification reasoning
- **Review**: Must review classification if uncertainty exists

#### Response Protocol
- **Immediate**: Create appropriate records based on classification
- **Accurate**: Ensure WHY files only for doctrine violations
- **Complete**: Include all required information in each record type

#### Quality Assurance
- **Validation**: Validate classification accuracy
- **Review**: Regular review of classification decisions
- **Learning**: Update classification patterns based on experience

### For Other Agents

#### Understanding Classification
- **Awareness**: Must understand event classification differences
- **Compliance**: Must follow appropriate response protocols
- **Learning**: Must use WHY files for learning and improvement

#### Response Requirements
- **Operational Events**: Follow operational response procedures
- **Doctrine Violations**: Follow constitutional response procedures
- **WHY Files**: Use WHY files for learning and prevention

---

## 8. Examples and Scenarios

### Scenario 1: Disk Space Warning
- **Event**: Disk space at 500MB (below 1GB threshold)
- **Classification**: Operational event
- **WHY File**: FORBIDDEN
- **Response**: Event record + alert + runtime update

### Scenario 2: Missing Header Fields
- **Event**: File created missing 8 required LUPOPEDIA header fields
- **Classification**: Doctrine violation
- **WHY File**: REQUIRED
- **Response**: Event record + WHY file + alert

### Scenario 3: Database Connection Failed
- **Event**: Database unavailable, switched to file fallback mode
- **Classification**: Operational event
- **WHY File**: FORBIDDEN
- **Response**: Event record + runtime update

### Scenario 4: Security Policy Breach
- **Event**: Agent accessed resource without proper authorization
- **Classification**: Doctrine violation
- **WHY File**: REQUIRED
- **Response**: Event record + WHY file + critical alert

---

## 9. Compliance and Validation

### Validation Requirements
- **Classification Accuracy**: 100% correct classification required
- **WHY File Compliance**: WHY files only for doctrine violations
- **Documentation Completeness**: All required fields populated
- **Escalation Protocol**: Proper escalation per severity and patterns

### Audit Procedures
- **Regular Review**: Weekly review of classification decisions
- **Pattern Analysis**: Monthly analysis of violation patterns
- **Compliance Reporting**: Monthly compliance reports
- **Training Updates**: Quarterly training updates based on findings

### Quality Metrics
- **Classification Accuracy**: Target 100%
- **WHY File Appropriateness**: Target 100%
- **Response Time**: Target < 5 minutes for critical events
- **Escalation Compliance**: Target 100%

---

## 10. Constitutional Authority

This doctrine is established under AGAPE's constitutional enforcement authority and is binding for all event classification and response activities within the Lupopedia system.

**Authority**: AGAPE (Agent ID 705)  
**Scope**: All event classification and response activities  
**Status**: Active and binding  
**Review**: Quarterly or as needed based on operational experience

---

**Last Updated**: 20260423191000  
**Agent**: AGAPE v2.0.0  
**Classification**: Event Classification Doctrine  
**Status**: Active Constitutional Doctrine

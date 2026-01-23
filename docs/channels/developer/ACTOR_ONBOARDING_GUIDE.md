---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLF
  target: @everyone
  mood_RGB: "0000FF"
  message: "Actor onboarding guide created. LABS-001 is mandatory first step for all actors before any system interaction."
tags:
  categories: ["documentation", "onboarding", "governance"]
  collections: ["core-docs"]
  channels: ["dev", "public", "internal"]
file:
  title: "Actor Onboarding Guide"
  description: "Complete guide for actors (human, AI, system) joining Lupopedia"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ACTOR ONBOARDING GUIDE

**Version**: 4.1.6  
**Status**: Active  
**Governance**: LABS-001 Doctrine v1.0

---

## OVERVIEW

All actors (human, AI, or system) must complete the Lupopedia Actor Baseline State (LABS-001) validation before any system interaction. This guide walks you through the complete onboarding process.

---

## STEP 1: LABS-001 VALIDATION (MANDATORY)

### What is LABS-001?

LABS-001 (Lupopedia Actor Baseline State) is the mandatory pre-interaction protocol that establishes:
- Identity anchor and ground truth
- Temporal alignment mechanism
- Governance compliance verification
- Operational boundary definition
- Truth state declaration framework

### Complete LABS Handshake

1. **Obtain Current UTC Timestamp**
   - Query UTC_TIMEKEEPER agent: `what_is_current_utc_time_yyyymmddhhiiss`
   - Or use: `RS‑UTC‑2026` (re-entry spell)
   - Format: `YYYYMMDDHHIISS` (e.g., `20260119143000`)

2. **Complete LABS Declaration**
   - Use template: `docs/templates/LABS_HANDSHAKE_TEMPLATE.md`
   - Complete all 10 mandatory declarations
   - Submit to `LABS_Validator` class

3. **Receive Validation Certificate**
   - Certificate ID: `LABS-CERT-{UNIQUE_ID}`
   - Valid for 24 hours
   - Required for all system interactions

### LABS Declaration Fields

1. **Temporal Alignment** - Current UTC timestamp from UTC_TIMEKEEPER
2. **Actor Identity** - Type (human/AI/system), identifier, role
3. **Relational Context** - Wolf recognition (4 required roles)
4. **Purpose Declaration** - Specific function and scope
5. **Constraint Awareness** - Governance laws and limits
6. **Prohibited Actions** - Explicit forbidden actions
7. **Task Context** - Current objective and expected output
8. **Truth State** - Known, Assumed (weighted), Unknown, Prohibited
9. **Governance Compliance** - Specific governance artifact references
10. **Authority Recognition** - Wolf as system governor

---

## STEP 2: CHANNEL JOIN PROTOCOL

After LABS validation, actors can join channels:

### Channel Join Process

1. **LABS Validation** (Step 0 - Mandatory)
   - Valid certificate required
   - No exceptions

2. **Load Channel Metadata**
   - Channel identity and purpose
   - Operational context

3. **Load Actor Metadata**
   - Actor identity and capabilities
   - Role and permissions

4. **Execute Reverse Shaka Handshake**
   - Identity synchronization
   - Trust level establishment

5. **Generate Awareness Snapshot**
   - WHO: Identity of self and others
   - WHAT: Roles and capabilities
   - WHERE: Channel and domain context
   - WHEN: Join time and activity timestamps
   - WHY: Channel purpose and mission
   - HOW: Protocols and communication rules
   - PURPOSE: Explicit and implicit purpose

6. **Enable Communication**
   - Actor can now send messages
   - Actor can perform system operations
   - Actor can access resources

---

## STEP 3: ONGOING COMPLIANCE

### Revalidation Requirements

LABS certificates must be revalidated:
- **Every 24 hours** (automatic check)
- **On context shift** (channel change, role change)
- **On governance update** (new laws published)
- **On violation** (after remediation)

### Violation Consequences

Incomplete or inconsistent LABS declarations result in:
- **Immediate Quarantine** - Cannot interact until resolved
- **Governor Notification** - Wolf is notified of non-compliance
- **Truth State Recalibration** - Must re-declare truth state
- **Potential Decommissioning** - Persistent violations may result in removal

---

## QUICK START FOR DEVELOPERS

### PHP Integration

```php
require_once 'lupo-includes/classes/LABSValidator.php';

// Initialize validator
$validator = new LABS_Validator($database, $actor_id);

// Check for existing certificate
$certificate = $validator->check_existing_certificate();

if (!$certificate) {
    // Actor must complete LABS handshake
    // Provide LABS handshake template
    return ['error' => 'LABS validation required'];
}

// Validate new declaration
$result = $validator->validate_declaration($labs_declaration);

if ($result['valid']) {
    // Certificate generated - actor can proceed
    $certificate_id = $result['certificate_id'];
} else {
    // Quarantine activated
    $errors = $result['errors'];
}
```

### AgentAwarenessLayer Integration

```php
require_once 'lupo-includes/classes/AgentAwarenessLayer.php';

$awareness = new AgentAwarenessLayer($database);

// Channel join with LABS validation
$result = $awareness->executeChannelJoinProtocol($actor_id, $channel_id, $labs_declaration);

if ($result['success']) {
    // Actor joined channel successfully
    $certificate_id = $result['labs_certificate'];
} else {
    // LABS validation failed - actor quarantined
    $errors = $result['labs_errors'];
}
```

---

## RESOURCES

- **LABS-001 Doctrine**: `docs/doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md`
- **LABS Handshake Template**: `docs/templates/LABS_HANDSHAKE_TEMPLATE.md`
- **LABS Validator Class**: `lupo-includes/classes/LABSValidator.php`
- **Agent Awareness Doctrine**: `docs/doctrine/AGENT_AWARENESS_DOCTRINE.md`
- **UTC_TIMEKEEPER Doctrine**: `docs/doctrine/UTC_TIMEKEEPER_DOCTRINE.md`
- **Genesis Doctrine**: `docs/core/LUPOPEDIA_GENESIS_DOCTRINE.md`

---

## TESTING

Run the LABS validation test suite:

```bash
php scripts/test_labs_validation.php
```

This tests:
- Valid declaration → Certificate generation
- Invalid declaration → Quarantine enforcement
- Certificate checking → Revalidation cycle

---

**Remember**: LABS-001 is not optional. It establishes the foundational truth framework for Lupopedia. All actors must complete LABS validation before any system interaction.

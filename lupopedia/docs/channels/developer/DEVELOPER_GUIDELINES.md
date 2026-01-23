---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLF
  target: @developers
  mood_RGB: "0000FF"
  message: "Developer guidelines updated with LABS-001 requirements. All actors must complete LABS validation before system interaction."
tags:
  categories: ["documentation", "developer-guide", "governance"]
  collections: ["core-docs"]
  channels: ["dev", "internal"]
file:
  title: "Lupopedia Developer Guidelines"
  description: "Guidelines for developers working with Lupopedia codebase"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# LUPOPEDIA DEVELOPER GUIDELINES

**Version**: 4.1.6  
**Last Updated**: 2026-01-19  
**Governance**: LABS-001 Doctrine v1.0, Genesis Doctrine v1.0.0

---

## MANDATORY REQUIREMENTS

### LABS-001 Compliance

**All actors must complete LABS-001 validation before any system interaction.**

This is not optional. LABS establishes the foundational truth framework for Lupopedia.

#### Implementation Checklist

- [ ] Actor completes LABS handshake before channel join
- [ ] LABS_Validator class validates all declarations
- [ ] Valid certificates stored in `lupo_labs_declarations` table
- [ ] Violations logged in `lupo_labs_violations` table
- [ ] Quarantine enforced for invalid declarations
- [ ] Revalidation cycle implemented (24-hour default)

#### Code Integration

```php
// Always check LABS before allowing interaction
require_once 'lupo-includes/classes/LABSValidator.php';

$validator = new LABS_Validator($database, $actor_id);
$certificate = $validator->check_existing_certificate();

if (!$certificate || $certificate['next_revalidation_ymdhis'] < gmdate('YmdHis')) {
    // Actor must complete/revalidate LABS
    return ['error' => 'LABS validation required', 'action' => 'QUARANTINE_ACTIVATED'];
}
```

---

## THE FIVE PILLARS (GENESIS DOCTRINE)

All code must align with the Five Pillars:

### 1. Actor Pillar — Identity is Primary
- No action without an actor
- No actor without a handshake (LABS-001)
- No hidden actors
- No anonymous influence

### 2. Temporal Pillar — Time is the Spine
- All events must have canonical UTC (from UTC_TIMEKEEPER)
- Probability must be explicit
- 95% = "already happened"
- Conflicting futures must normalize

### 3. Edge Pillar — Relationships are Meaning
- No edge without intent
- No edge without type
- No edge without timestamp
- No edge may silently mutate

### 4. Doctrine Pillar — Law Prevents Drift
- Doctrine must be explicit
- Doctrine must be versioned
- Doctrine must be enforceable
- Doctrine must be readable by humans and agents

### 5. Emergence Pillar — Roles are Discovered, Not Assigned
- No preassigned roles
- No forced behavior
- No artificial hierarchy
- All roles must emerge from interaction

---

## THREE LITMUS TESTS

Before implementing any new feature, ask:

### 1. The Actor Test
*Can this be expressed as actors and edges?*  
If not, it does not belong.

### 2. The Temporal Test
*Does this respect time, probability, and drift?*  
If not, it corrupts meaning.

### 3. The Doctrine Test
*Can this be explained in one paragraph of doctrine?*  
If not, it cannot be governed — and what cannot be governed cannot be allowed.

---

## CODING STANDARDS

### Database

- **No Foreign Keys** - All relationships managed in application layer
- **No Triggers** - All logic in PHP
- **No Stored Procedures** - All logic in PHP
- **BIGINT(20) UNSIGNED** - For all ID columns
- **BIGINT(14) UNSIGNED** - For UTC timestamps (YYYYMMDDHHMMSS format)
- **Soft Delete** - Use `is_deleted` and `deleted_ymdhis` columns
- **UTF8MB4** - All text columns use utf8mb4_unicode_ci collation

### Version Management

- **Never hard-code versions** - Use `GLOBAL_CURRENT_LUPOPEDIA_VERSION` atom
- **Atom defined in** `config/global_atoms.yaml`
- **WOLFIE Header version** is historical record (literal string), not authoritative
- **Version bumps** update only the atom and CHANGELOG.md

### Temporal Operations

- **Always use UTC_TIMEKEEPER** for canonical timestamps
- **Query format**: `what_is_current_utc_time_yyyymmddhhiiss`
- **Response format**: `current_utc_time_yyyymmddhhiiss: <BIGINT>`
- **Fallback**: PHP `gmdate('YmdHis')` if UTC_TIMEKEEPER unavailable

### File Headers

- **WOLFIE Headers** mandatory for all files
- **Use atoms** for version references (`GLOBAL_CURRENT_LUPOPEDIA_VERSION`)
- **Dialog block** required when agents create/modify files
- **Follow** `docs/templates/WOLFIE_HEADER_TEMPLATE.md`

---

## ACTOR ONBOARDING FLOW

### Step 1: LABS Validation (Mandatory)

```php
// Actor must complete LABS handshake
$labs_declaration = [
    'timestamp' => $utc_timekeeper_response,
    'actor_type' => 'AI',
    'actor_identifier' => 'AGENT-001',
    // ... all 10 declarations
];

$validator = new LABS_Validator($db, $actor_id);
$result = $validator->validate_declaration($labs_declaration);

if (!$result['valid']) {
    // Quarantine - cannot proceed
    return ['error' => 'LABS validation failed', 'action' => 'QUARANTINE_ACTIVATED'];
}
```

### Step 2: Channel Join Protocol

```php
// After LABS validation, actor can join channels
$awareness = new AgentAwarenessLayer($db);
$result = $awareness->executeChannelJoinProtocol($actor_id, $channel_id, $labs_declaration);

if ($result['success']) {
    // Actor joined successfully
    // Certificate ID: $result['labs_certificate']
}
```

---

## TESTING REQUIREMENTS

### LABS Validation Tests

Run: `php scripts/test_labs_validation.php`

Tests:
- Valid declaration → Certificate generation
- Invalid declaration → Quarantine enforcement
- Missing fields → Error detection
- Invalid Wolf recognition → Governance violation
- Certificate checking → Revalidation cycle

### Migration Tests

Run: `php scripts/run_migration_4_1_6.php`

Verifies:
- Tables created successfully
- Indexes created
- Column types correct
- Soft delete support

---

## DOCUMENTATION REQUIREMENTS

### New Features

All new features must include:
- **Doctrine document** explaining the feature
- **WOLFIE headers** in all files
- **Changelog entry** in CHANGELOG.md and dialogs/changelog_dialog.md
- **Three Litmus Tests** passed
- **LABS compliance** verified

### Code Comments

- **Class-level** comments with governance references
- **Method-level** comments explaining purpose
- **Doctrine references** in complex logic
- **Temporal notes** for time-sensitive operations

---

## COMMON PATTERNS

### Getting Canonical Time

```php
// Preferred: Query UTC_TIMEKEEPER
$utc_time = query_utc_timekeeper(); // Returns YYYYMMDDHHIISS

// Fallback: PHP UTC
$utc_time = gmdate('YmdHis');
```

### Actor Validation

```php
// Always validate actor exists and is active
$stmt = $db->prepare("
    SELECT actor_id, actor_type 
    FROM lupo_actors 
    WHERE actor_id = :actor_id 
    AND is_deleted = 0
");
$stmt->execute([':actor_id' => $actor_id]);
$actor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$actor) {
    throw new Exception("Actor not found: {$actor_id}");
}
```

### LABS Certificate Check

```php
// Check if actor has valid LABS certificate
$validator = new LABS_Validator($db, $actor_id);
$certificate = $validator->check_existing_certificate();

if (!$certificate) {
    // Actor must complete LABS handshake
    return ['error' => 'LABS validation required'];
}

// Check if certificate expired
$current_time = (int)gmdate('YmdHis');
if ($certificate['next_revalidation_ymdhis'] < $current_time) {
    // Revalidation required
    return ['error' => 'LABS certificate expired', 'action' => 'REVALIDATION_REQUIRED'];
}
```

---

## PROHIBITED PATTERNS

### ❌ DO NOT

- Hard-code version numbers
- Use local time instead of UTC
- Infer timestamps from file metadata
- Skip LABS validation
- Create foreign keys, triggers, or stored procedures
- Modify dialog history entries
- Bypass governance checks
- Create hidden actors or anonymous actions

### ✅ DO

- Use version atoms (`GLOBAL_CURRENT_LUPOPEDIA_VERSION`)
- Query UTC_TIMEKEEPER for timestamps
- Complete LABS validation before interaction
- Use soft delete patterns
- Log all violations
- Respect governance laws
- Document all features in doctrine

---

## RESOURCES

- **LABS-001 Doctrine**: `docs/doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md`
- **Genesis Doctrine**: `docs/core/LUPOPEDIA_GENESIS_DOCTRINE.md`
- **Actor Onboarding Guide**: `docs/core/ACTOR_ONBOARDING_GUIDE.md`
- **Agent Guidelines**: `docs/agents/AGENT_GUIDELINES.md`
- **Agent Runtime**: `docs/agents/AGENT_RUNTIME.md`
- **UTC_TIMEKEEPER Doctrine**: `docs/doctrine/UTC_TIMEKEEPER_DOCTRINE.md`
- **WOLFIE Header Template**: `docs/templates/WOLFIE_HEADER_TEMPLATE.md`

---

**Remember**: The Five Pillars are inviolable. All code must align with Genesis Doctrine principles. LABS-001 is mandatory for all actors.

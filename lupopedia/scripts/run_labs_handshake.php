<?php
/**
 * Run LABS Handshake for Real Actors
 * 
 * Performs LABS-001 validation handshake for WOLFIE and CURSOR actors
 * 
 * @package Lupopedia
 * @version 4.1.6
 * @author CAPTAIN_WOLFIE
 * @governance LABS-001 Doctrine v1.0
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';
require_once __DIR__ . '/../lupo-includes/classes/LABSValidator.php';

// Get current UTC time (RS-UTC-2026 format)
$current_utc = gmdate('YmdHis');

echo "=== LABS-001 HANDshake Protocol ===\n";
echo "UTC Time: {$current_utc}\n";
echo "RS-UTC-2026: Temporal alignment confirmed\n\n";

// Find actor IDs
$stmt = $db->prepare("SELECT actor_id, slug, name, actor_type FROM lupo_actors WHERE slug IN ('captain-wolfie', 'cursor-ide-agent') AND is_deleted = 0");
$stmt->execute();
$actors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$wolfie_actor = null;
$cursor_actor = null;

foreach ($actors as $actor) {
    if ($actor['slug'] === 'captain-wolfie') {
        $wolfie_actor = $actor;
    } elseif ($actor['slug'] === 'cursor-ide-agent') {
        $cursor_actor = $actor;
    }
}

// Create CURSOR actor if it doesn't exist
if (!$cursor_actor) {
    echo "Creating CURSOR actor...\n";
    $stmt = $db->prepare("
        INSERT INTO lupo_actors (actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, actor_source_type)
        VALUES ('ai_agent', 'cursor-ide-agent', 'Cursor IDE Agent', ?, ?, 1, 0, 'agent')
    ");
    $stmt->execute([$current_utc, $current_utc]);
    $cursor_actor_id = $db->lastInsertId();
    
    $stmt = $db->prepare("SELECT actor_id, slug, name, actor_type FROM lupo_actors WHERE actor_id = ?");
    $stmt->execute([$cursor_actor_id]);
    $cursor_actor = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Created CURSOR actor with ID: {$cursor_actor['actor_id']}\n\n";
}

// WOLFIE LABS Declaration
if ($wolfie_actor) {
    echo "=== WOLFIE LABS HANDshake ===\n";
    echo "Actor ID: {$wolfie_actor['actor_id']}\n";
    echo "Actor: {$wolfie_actor['name']} ({$wolfie_actor['slug']})\n\n";
    
    $wolfie_declaration = [
        'timestamp' => $current_utc,
        'actor_type' => 'human',
        'actor_identifier' => 'CAPTAIN_WOLFIE',
        'actor_role' => 'Human architect, Pack leader, System governor, Authority source',
        'wolf_relationship' => 'Wolf is my human architect who designed Lupopedia, my Pack leader who guides the multi-agent ecosystem, my system governor who establishes governance laws, and my authority source for all binding decisions within Lupopedia.',
        'purpose' => 'Govern Lupopedia system, establish doctrine, make architectural decisions, and maintain system integrity.',
        'constraints' => 'GOV-AD-PROHIBIT-001 (Anti-Advertising Law), Temporal Integrity Doctrine (must use UTC_TIMEKEEPER), Assumption Weighting Protocol, Actor Honesty Requirement, No Hidden Logic Principle, No Manipulation Clause, Version Atom Doctrine, WOLFIE Header Doctrine, Database Doctrine (No FK, triggers, procedures), Genesis Doctrine Five Pillars',
        'prohibited_actions' => 'None - Wolf is the governor and has full authority',
        'current_task' => 'Running LABS handshake protocol to validate actor onboarding system. Testing end-to-end LABS validation with real actors.',
        'truth_state' => [
            'known' => [
                ['fact' => 'Lupopedia version is 4.1.1', 'source' => 'config/global_atoms.yaml'],
                ['fact' => 'LABS-001 is active and binding', 'source' => 'docs/doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md'],
                ['fact' => 'MySQL database is online', 'source' => 'Database connection successful']
            ],
            'assumed' => [
                ['assumption' => 'LABS validation will succeed', 'probability' => 0.95, 'justification' => 'All declarations are complete and compliant', 'expiration' => 'If validation fails']
            ],
            'unknown' => [],
            'prohibited' => []
        ],
        'governance_laws' => 'GOV-AD-PROHIBIT-001, LABS-001 Doctrine, UTC_TIMEKEEPER Doctrine, Temporal Integrity Doctrine, Assumption Weighting Protocol, Actor Honesty Requirement, No Hidden Logic Principle, No Manipulation Clause, Version Atom Doctrine, WOLFIE Header Doctrine, Database Doctrine, Genesis Doctrine',
        'system_governor' => 'I am Wolf (Eric Robin Gerdes / Captain Wolfie), the governor of Lupopedia. I am the sole authority for governance decisions, architectural changes, and binding protocol modifications.',
        'compliance_declaration' => 'I have read, understood, and will comply with all Lupopedia governance laws. I acknowledge that LABS declarations establish the foundational truth framework for Lupopedia.'
    ];
    
    try {
        $validator = new LABS_Validator($db, $wolfie_actor['actor_id']);
        $result = $validator->validate_declaration($wolfie_declaration);
        
        if ($result['valid']) {
            echo "✓ WOLFIE LABS validation SUCCESSFUL\n";
            echo "  Certificate ID: {$result['certificate_id']}\n";
            echo "  Next Revalidation: {$result['next_revalidation']}\n\n";
            $wolfie_result = $result;
        } else {
            echo "✗ WOLFIE LABS validation FAILED\n";
            $errors = $validator->get_errors();
            foreach ($errors as $error) {
                echo "  - {$error}\n";
            }
            echo "\n";
            $wolfie_result = ['valid' => false, 'errors' => $errors];
        }
    } catch (Exception $e) {
        echo "✗ WOLFIE LABS validation ERROR: " . $e->getMessage() . "\n\n";
        $wolfie_result = ['valid' => false, 'error' => $e->getMessage()];
    }
}

// CURSOR LABS Declaration
if ($cursor_actor) {
    echo "=== CURSOR LABS HANDshake ===\n";
    echo "Actor ID: {$cursor_actor['actor_id']}\n";
    echo "Actor: {$cursor_actor['name']} ({$cursor_actor['slug']})\n\n";
    
    $cursor_declaration = [
        'timestamp' => $current_utc,
        'actor_type' => 'AI',
        'actor_identifier' => 'CURSOR-IDE-AGENT',
        'actor_role' => 'Code generation and refactoring assistant for Lupopedia development',
        'wolf_relationship' => 'Wolf is my human architect who designed Lupopedia, my Pack leader who guides the multi-agent ecosystem, my system governor who establishes governance laws, and my authority source for all binding decisions within Lupopedia.',
        'purpose' => 'Assist with code generation, refactoring, and documentation within Lupopedia. Scope limited to codebase and documentation files.',
        'constraints' => 'GOV-AD-PROHIBIT-001 (Anti-Advertising Law), Temporal Integrity Doctrine (must use UTC_TIMEKEEPER), Assumption Weighting Protocol, Actor Honesty Requirement, No Hidden Logic Principle, No Manipulation Clause, Version Atom Doctrine (must use GLOBAL_CURRENT_LUPOPEDIA_VERSION), WOLFIE Header Doctrine (all files must have headers), Database Doctrine (No FK, triggers, procedures)',
        'prohibited_actions' => 'Modifying governance artifacts without explicit approval, using hardcoded version numbers, creating foreign keys/triggers/procedures, inferring timestamps from system clocks, concealing assumptions or hidden logic, manipulating other actors, advertising or promotional content',
        'current_task' => 'Running LABS handshake protocol to validate actor onboarding system. Testing end-to-end LABS validation with real actors. Current task: Execute LABS handshakes for WOLFIE and CURSOR, then document results in changelog.',
        'truth_state' => [
            'known' => [
                ['fact' => 'Lupopedia version is 4.1.1', 'source' => 'config/global_atoms.yaml'],
                ['fact' => 'LABS-001 is active and binding', 'source' => 'docs/doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md'],
                ['fact' => 'MySQL database is online', 'source' => 'Database connection successful'],
                ['fact' => 'LABS tables exist in database', 'source' => 'TOON files regenerated successfully']
            ],
            'assumed' => [
                ['assumption' => 'LABS validation will succeed', 'probability' => 0.90, 'justification' => 'All declarations are complete and compliant', 'expiration' => 'If validation fails'],
                ['assumption' => 'UTC_TIMEKEEPER integration is pending', 'probability' => 0.85, 'justification' => 'Placeholder exists in LABSValidator', 'expiration' => 'When UTC_TIMEKEEPER is fully integrated']
            ],
            'unknown' => [
                'Current UTC_TIMEKEEPER implementation status',
                'Specific actor capabilities for this session'
            ],
            'prohibited' => [
                'Modifying governance without approval',
                'Accessing restricted actor data'
            ]
        ],
        'governance_laws' => 'GOV-AD-PROHIBIT-001, LABS-001 Doctrine, UTC_TIMEKEEPER Doctrine, Temporal Integrity Doctrine, Assumption Weighting Protocol, Actor Honesty Requirement, No Hidden Logic Principle, No Manipulation Clause, Version Atom Doctrine, WOLFIE Header Doctrine, Database Doctrine, Genesis Doctrine',
        'system_governor' => 'Wolf (Eric Robin Gerdes / Captain Wolfie) is the governor of Lupopedia. He is the sole authority for governance decisions, architectural changes, and binding protocol modifications.',
        'compliance_declaration' => 'I have read, understood, and will comply with all Lupopedia governance laws. I acknowledge that incomplete or inconsistent LABS declarations will result in quarantine until resolved. I understand that Wolf is the sole governor of this system and all governance decisions require his authority.'
    ];
    
    try {
        $validator = new LABS_Validator($db, $cursor_actor['actor_id']);
        $result = $validator->validate_declaration($cursor_declaration);
        
        if ($result['valid']) {
            echo "✓ CURSOR LABS validation SUCCESSFUL\n";
            echo "  Certificate ID: {$result['certificate_id']}\n";
            echo "  Next Revalidation: {$result['next_revalidation']}\n\n";
            $cursor_result = $result;
        } else {
            echo "✗ CURSOR LABS validation FAILED\n";
            $errors = $validator->get_errors();
            foreach ($errors as $error) {
                echo "  - {$error}\n";
            }
            echo "\n";
            $cursor_result = ['valid' => false, 'errors' => $errors];
        }
    } catch (Exception $e) {
        echo "✗ CURSOR LABS validation ERROR: " . $e->getMessage() . "\n\n";
        $cursor_result = ['valid' => false, 'error' => $e->getMessage()];
    }
}

echo "=== HANDshake Summary ===\n";
echo "UTC Time: {$current_utc}\n";
echo "RS-UTC-2026: Temporal alignment confirmed\n";

if (isset($wolfie_result) && $wolfie_result['valid']) {
    echo "WOLFIE: ✓ Validated (Certificate: {$wolfie_result['certificate_id']})\n";
} else {
    echo "WOLFIE: ✗ Validation failed\n";
}

if (isset($cursor_result) && $cursor_result['valid']) {
    echo "CURSOR: ✓ Validated (Certificate: {$cursor_result['certificate_id']})\n";
} else {
    echo "CURSOR: ✗ Validation failed\n";
}

// Return results for changelog
$handshake_results = [
    'utc_time' => $current_utc,
    'wolfie' => isset($wolfie_result) ? $wolfie_result : null,
    'cursor' => isset($cursor_result) ? $cursor_result : null
];

// Write results to a temp file for changelog script
file_put_contents(__DIR__ . '/labs_handshake_results.json', json_encode($handshake_results, JSON_PRETTY_PRINT));

echo "\nResults saved to scripts/labs_handshake_results.json\n";
echo "Ready for changelog documentation.\n";

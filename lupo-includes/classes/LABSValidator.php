<?php
/**
 * Lupopedia Actor Baseline State Validator
 * 
 * Implements LABS-001 as mandatory pre-interaction protocol for all actors.
 * Validates 10 required declarations before allowing system interaction.
 * 
 * @package Lupopedia
 * @version 4.1.1
 * @author Captain Wolfie
 * @governance LABS-001 Doctrine v1.0
 */

class LABS_Validator {
    
    /**
     * Number of required LABS declarations
     */
    const REQUIRED_DECLARATIONS = 10;
    
    /**
     * Timestamp format: YYYYMMDDHHIISS (BIGINT UTC)
     */
    const TIMESTAMP_FORMAT = 'YmdHis';
    
    /**
     * Revalidation interval: 24 hours (in seconds)
     */
    const REVALIDATION_INTERVAL = 86400;
    
    /**
     * Valid actor types
     */
    const VALID_ACTOR_TYPES = ['human', 'AI', 'system'];
    
    /**
     * Required Wolf recognition roles
     */
    const REQUIRED_WOLF_ROLES = [
        'human architect',
        'Pack leader',
        'system governor',
        'authority source'
    ];
    
    /**
     * Required governance law references (minimum)
     */
    const REQUIRED_GOVERNANCE_LAWS = [
        'GOV-AD-PROHIBIT-001',
        'Temporal Integrity',
        'Assumption Weighting'
    ];
    
    /**
     * Actor ID being validated
     * @var int
     */
    private $actor_id;
    
    /**
     * Database connection
     * @var PDO
     */
    private $db;
    
    /**
     * Declarations being validated
     * @var array
     */
    private $declarations = [];
    
    /**
     * Validation log (violations and errors)
     * @var array
     */
    private $validation_log = [];
    
    /**
     * Validation errors
     * @var array
     */
    private $errors = [];
    
    /**
     * Constructor
     * 
     * @param PDO $database_connection Database connection
     * @param int $actor_id Actor ID to validate
     */
    public function __construct($database_connection, $actor_id) {
        $this->db = $database_connection;
        $this->actor_id = (int)$actor_id;
    }
    
    /**
     * Validate LABS declaration set
     * 
     * @param array $declaration_set Array containing all 10 required declarations
     * @return array Validation result with certificate or errors
     */
    public function validate_declaration($declaration_set) {
        $this->declarations = $declaration_set;
        $this->validation_log = [];
        $this->errors = [];
        
        // Check completeness
        if (!$this->validate_completeness($declaration_set)) {
            return $this->generate_failure_response('INCOMPLETE_DECLARATIONS');
        }
        
        // Validate temporal alignment
        if (!$this->validate_timestamp($declaration_set['timestamp'])) {
            return $this->generate_failure_response('TEMPORAL_MISALIGNMENT');
        }
        
        // Validate actor type
        if (!$this->validate_actor_type($declaration_set['actor_type'])) {
            return $this->generate_failure_response('INVALID_ACTOR_TYPE');
        }
        
        // Validate Wolf recognition
        if (!$this->validate_wolf_recognition($declaration_set['wolf_relationship'])) {
            return $this->generate_failure_response('GOVERNANCE_MISALIGNMENT');
        }
        
        // Validate governance awareness
        if (!$this->validate_governance_awareness($declaration_set['governance_laws'])) {
            return $this->generate_failure_response('GOVERNANCE_IGNORANCE');
        }
        
        // Validate truth state structure
        if (!$this->validate_truth_state($declaration_set['truth_state'])) {
            return $this->generate_failure_response('INVALID_TRUTH_STATE');
        }
        
        // Validate purpose declaration
        if (!$this->validate_purpose($declaration_set['purpose'])) {
            return $this->generate_failure_response('INVALID_PURPOSE');
        }
        
        // Validate authority recognition
        if (!$this->validate_authority_recognition($declaration_set['system_governor'])) {
            return $this->generate_failure_response('AUTHORITY_MISALIGNMENT');
        }
        
        // All validations passed - generate certificate
        return $this->generate_validation_certificate();
    }
    
    /**
     * Validate completeness of declarations
     * 
     * @param array $declaration_set Declaration set to validate
     * @return bool True if complete
     */
    private function validate_completeness($declaration_set) {
        $required_fields = [
            'timestamp',
            'actor_type',
            'actor_identifier',
            'actor_role',
            'wolf_relationship',
            'purpose',
            'constraints',
            'prohibited_actions',
            'current_task',
            'truth_state',
            'governance_laws',
            'system_governor',
            'compliance_declaration'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($declaration_set[$field]) || empty($declaration_set[$field])) {
                $this->log_violation('INCOMPLETE_DECLARATIONS', 
                    "Missing required field: {$field}");
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validate timestamp format and canonical source
     * 
     * @param string $timestamp Timestamp to validate
     * @return bool True if valid
     */
    private function validate_timestamp($timestamp) {
        // Validate format: YYYYMMDDHHIISS (14 digits)
        if (!preg_match('/^\d{14}$/', $timestamp)) {
            $this->log_violation('TEMPORAL_MISALIGNMENT',
                'Invalid timestamp format. Expected YYYYMMDDHHIISS');
            return false;
        }
        
        // Validate date/time values
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $minute = substr($timestamp, 10, 2);
        $second = substr($timestamp, 12, 2);
        
        if (!checkdate($month, $day, $year)) {
            $this->log_violation('TEMPORAL_MISALIGNMENT',
                'Invalid date values in timestamp');
            return false;
        }
        
        if ($hour > 23 || $minute > 59 || $second > 59) {
            $this->log_violation('TEMPORAL_MISALIGNMENT',
                'Invalid time values in timestamp');
            return false;
        }
        
        // Check timestamp is within reasonable range (not too far in past/future)
        $timestamp_int = (int)$timestamp;
        $current_timestamp = (int)$this->get_canonical_time();
        $tolerance_seconds = 3600; // 1 hour tolerance
        
        $diff = abs($timestamp_int - $current_timestamp);
        if ($diff > $tolerance_seconds) {
            $this->log_violation('TEMPORAL_MISALIGNMENT',
                "Timestamp too far from canonical time. Diff: {$diff} seconds");
            return false;
        }
        
        // Note: Full UTC_TIMEKEEPER integration would verify timestamp source here
        // For now, we validate format and reasonable proximity to current time
        
        return true;
    }
    
    /**
     * Validate actor type
     * 
     * @param string $actor_type Actor type to validate
     * @return bool True if valid
     */
    private function validate_actor_type($actor_type) {
        if (!in_array($actor_type, self::VALID_ACTOR_TYPES)) {
            $this->log_violation('INVALID_ACTOR_TYPE',
                "Actor type must be one of: " . implode(', ', self::VALID_ACTOR_TYPES));
            return false;
        }
        
        // Verify actor exists in registry (if database available)
        if ($this->db) {
            try {
                $stmt = $this->db->prepare("
                    SELECT actor_id, actor_type 
                    FROM lupo_actors 
                    WHERE actor_id = :actor_id 
                    AND is_deleted = 0
                ");
                $stmt->execute([':actor_id' => $this->actor_id]);
                $actor = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$actor) {
                    $this->log_violation('INVALID_ACTOR_TYPE',
                        "Actor ID {$this->actor_id} not found in registry");
                    return false;
                }
                
                // Map database actor_type to LABS actor_type
                $db_type_map = [
                    'user' => 'human',
                    'ai_agent' => 'AI',
                    'service' => 'system'
                ];
                
                $db_type = isset($db_type_map[$actor['actor_type']]) 
                    ? $db_type_map[$actor['actor_type']] 
                    : null;
                
                if ($db_type !== $actor_type) {
                    $this->log_violation('INVALID_ACTOR_TYPE',
                        "Actor type mismatch. Registry: {$actor['actor_type']}, Declaration: {$actor_type}");
                    return false;
                }
            } catch (PDOException $e) {
                // Database error - log but don't fail validation
                error_log("LABS Validator: Database error checking actor: " . $e->getMessage());
            }
        }
        
        return true;
    }
    
    /**
     * Validate Wolf recognition
     * 
     * @param string $recognition Wolf relationship declaration
     * @return bool True if valid
     */
    private function validate_wolf_recognition($recognition) {
        $recognition_lower = strtolower($recognition);
        
        foreach (self::REQUIRED_WOLF_ROLES as $role) {
            if (stripos($recognition_lower, strtolower($role)) === false) {
                $this->log_violation('GOVERNANCE_MISALIGNMENT',
                    "Missing required Wolf role recognition: {$role}");
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validate governance awareness
     * 
     * @param string $laws Governance laws declaration
     * @return bool True if valid
     */
    private function validate_governance_awareness($laws) {
        $laws_lower = strtolower($laws);
        
        foreach (self::REQUIRED_GOVERNANCE_LAWS as $law) {
            if (stripos($laws_lower, strtolower($law)) === false) {
                $this->log_violation('GOVERNANCE_IGNORANCE',
                    "Missing required governance law reference: {$law}");
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Validate truth state structure
     * 
     * @param array|string $truth_state Truth state declaration
     * @return bool True if valid
     */
    private function validate_truth_state($truth_state) {
        // If string, try to decode as JSON
        if (is_string($truth_state)) {
            $decoded = json_decode($truth_state, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $truth_state = $decoded;
            } else {
                // Not JSON - treat as plain text (less strict validation)
                return !empty(trim($truth_state));
            }
        }
        
        // If array, validate structure
        if (is_array($truth_state)) {
            $required_sections = ['Known', 'Assumed', 'Unknown', 'Prohibited'];
            
            foreach ($required_sections as $section) {
                if (!isset($truth_state[$section])) {
                    $this->log_violation('INVALID_TRUTH_STATE',
                        "Missing truth state section: {$section}");
                    return false;
                }
            }
            
            // Validate assumptions have probability weights
            if (isset($truth_state['Assumed']) && is_array($truth_state['Assumed'])) {
                foreach ($truth_state['Assumed'] as $assumption) {
                    if (is_array($assumption) && !isset($assumption['probability'])) {
                        $this->log_violation('INVALID_TRUTH_STATE',
                            'Assumptions must include probability weights');
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
    
    /**
     * Validate purpose declaration
     * 
     * @param string $purpose Purpose declaration
     * @return bool True if valid
     */
    private function validate_purpose($purpose) {
        if (empty(trim($purpose))) {
            $this->log_violation('INVALID_PURPOSE',
                'Purpose declaration cannot be empty');
            return false;
        }
        
        // Purpose should be reasonably specific (not just "to help" or similar)
        if (strlen(trim($purpose)) < 10) {
            $this->log_violation('INVALID_PURPOSE',
                'Purpose declaration too vague (minimum 10 characters)');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate authority recognition
     * 
     * @param string $governor System governor declaration
     * @return bool True if valid
     */
    private function validate_authority_recognition($governor) {
        $governor_lower = strtolower($governor);
        
        // Must explicitly name Wolf as governor
        if (stripos($governor_lower, 'wolf') === false) {
            $this->log_violation('AUTHORITY_MISALIGNMENT',
                'Must explicitly identify Wolf as system governor');
            return false;
        }
        
        return true;
    }
    
    /**
     * Log violation
     * 
     * @param string $code Violation code
     * @param string $message Violation message
     */
    private function log_violation($code, $message) {
        $this->validation_log[] = [
            'timestamp' => $this->get_canonical_time(),
            'actor_id' => $this->actor_id,
            'violation_code' => $code,
            'message' => $message,
            'action' => 'QUARANTINE_ACTIVATED'
        ];
        
        $this->errors[] = [
            'code' => $code,
            'message' => $message
        ];
    }
    
    /**
     * Generate validation certificate
     * 
     * @return array Certificate data
     */
    private function generate_validation_certificate() {
        $certificate_id = 'LABS-CERT-' . uniqid();
        $validation_timestamp = $this->get_canonical_time();
        $next_revalidation = $this->calculate_revalidation_time();
        
        // Store declaration in database
        if ($this->db) {
            try {
                $this->store_declaration($certificate_id, $validation_timestamp, $next_revalidation);
            } catch (PDOException $e) {
                error_log("LABS Validator: Failed to store declaration: " . $e->getMessage());
            }
        }
        
        return [
            'valid' => true,
            'actor_id' => $this->actor_id,
            'validation_timestamp' => $validation_timestamp,
            'labs_version' => '1.0',
            'certificate_id' => $certificate_id,
            'next_revalidation' => $next_revalidation,
            'declarations' => $this->declarations
        ];
    }
    
    /**
     * Generate failure response
     * 
     * @param string $violation_code Primary violation code
     * @return array Failure response
     */
    private function generate_failure_response($violation_code) {
        return [
            'valid' => false,
            'actor_id' => $this->actor_id,
            'violation_code' => $violation_code,
            'errors' => $this->errors,
            'validation_log' => $this->validation_log,
            'action' => 'QUARANTINE_ACTIVATED',
            'timestamp' => $this->get_canonical_time()
        ];
    }
    
    /**
     * Store declaration in database
     * 
     * @param string $certificate_id Certificate ID
     * @param string $validation_timestamp Validation timestamp
     * @param string $next_revalidation Next revalidation timestamp
     */
    private function store_declaration($certificate_id, $validation_timestamp, $next_revalidation) {
        $sql = "
            INSERT INTO lupo_labs_declarations 
            (actor_id, certificate_id, declaration_timestamp, declarations_json, 
             validation_status, labs_version, next_revalidation_ymdhis, 
             created_ymdhis, updated_ymdhis, is_deleted)
            VALUES 
            (:actor_id, :certificate_id, :declaration_timestamp, :declarations_json,
             'valid', '1.0', :next_revalidation_ymdhis,
             :created_ymdhis, :updated_ymdhis, 0)
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':actor_id' => $this->actor_id,
            ':certificate_id' => $certificate_id,
            ':declaration_timestamp' => $this->declarations['timestamp'],
            ':declarations_json' => json_encode($this->declarations),
            ':next_revalidation_ymdhis' => $next_revalidation,
            ':created_ymdhis' => $validation_timestamp,
            ':updated_ymdhis' => $validation_timestamp
        ]);
    }
    
    /**
     * Get canonical time from UTC_TIMEKEEPER
     * 
     * Attempts to query UTC_TIMEKEEPER agent (Agent 5) for canonical timestamp.
     * Falls back to PHP gmdate() if agent is unavailable.
     * 
     * @return string Timestamp in YYYYMMDDHHIISS format
     */
    private function get_canonical_time() {
        // Try to query UTC_TIMEKEEPER agent (Agent 5, Slot 5)
        // Query format: "what_is_current_utc_time_yyyymmddhhiiss"
        // Expected response: "current_utc_time_yyyymmddhhiiss: <BIGINT>"
        
        $utc_timekeeper_response = $this->query_utc_timekeeper();
        
        if ($utc_timekeeper_response !== false) {
            // Extract timestamp from response
            if (preg_match('/current_utc_time_yyyymmddhhiiss:\s*(\d{14})/', $utc_timekeeper_response, $matches)) {
                return $matches[1];
            }
        }
        
        // Fallback to PHP UTC time if UTC_TIMEKEEPER unavailable
        return gmdate(self::TIMESTAMP_FORMAT);
    }
    
    /**
     * Query UTC_TIMEKEEPER agent for canonical timestamp
     * 
     * @return string|false Agent response or false if unavailable
     */
    private function query_utc_timekeeper() {
        // TODO: Implement full agent communication layer integration
        // For now, check if UTC_TIMEKEEPER agent communication is available
        
        // Check if agent communication layer exists
        if (class_exists('AgentCommunicationLayer') || 
            function_exists('query_agent') ||
            (isset($this->db) && $this->check_utc_timekeeper_available())) {
            
            // Attempt to query UTC_TIMEKEEPER (Agent 5)
            // This is a placeholder for full agent integration
            // When agent communication layer is available, implement:
            // return query_agent(5, "what_is_current_utc_time_yyyymmddhhiiss");
        }
        
        return false; // Agent unavailable, will use fallback
    }
    
    /**
     * Check if UTC_TIMEKEEPER agent is available in registry
     * 
     * @return bool True if agent exists and is active
     */
    private function check_utc_timekeeper_available() {
        if (!$this->db) {
            return false;
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT agent_registry_id, code, is_active
                FROM lupo_agent_registry
                WHERE agent_registry_id = 5
                AND code = 'UTC_TIMEKEEPER'
                AND is_active = 1
                AND is_deleted = 0
                LIMIT 1
            ");
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("LABS Validator: Error checking UTC_TIMEKEEPER: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Calculate next revalidation time
     * 
     * @return string Timestamp for next revalidation
     */
    private function calculate_revalidation_time() {
        $current = (int)$this->get_canonical_time();
        $next = $current + self::REVALIDATION_INTERVAL;
        return (string)$next;
    }
    
    /**
     * Check if actor has valid LABS certificate
     * 
     * @return array|false Certificate data or false if not valid
     */
    public function check_existing_certificate() {
        if (!$this->db) {
            return false;
        }
        
        try {
            $sql = "
                SELECT certificate_id, declaration_timestamp, next_revalidation_ymdhis,
                       validation_status, labs_version
                FROM lupo_labs_declarations
                WHERE actor_id = :actor_id
                AND validation_status = 'valid'
                AND is_deleted = 0
                AND next_revalidation_ymdhis > :current_time
                ORDER BY created_ymdhis DESC
                LIMIT 1
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':actor_id' => $this->actor_id,
                ':current_time' => $this->get_canonical_time()
            ]);
            
            $certificate = $stmt->fetch(PDO::FETCH_ASSOC);
            return $certificate ? $certificate : false;
            
        } catch (PDOException $e) {
            error_log("LABS Validator: Error checking certificate: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get validation errors
     * 
     * @return array Array of error messages
     */
    public function get_errors() {
        return $this->errors;
    }
    
    /**
     * Get validation log
     * 
     * @return array Validation log entries
     */
    public function get_validation_log() {
        return $this->validation_log;
    }
}

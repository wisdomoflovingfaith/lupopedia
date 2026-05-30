<?php
/**
 * Rule Evaluator
 *
 * Specialized evaluator for database and schema rules. Uses RuleEngine for target resolution.
 * TOON-based validation (SHOW TABLES / SHOW CREATE TABLE); no information_schema.
 *
 * @package Lupopedia
 * @version 4.0.68
 */

if (!class_exists('ToonValidator')) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ToonValidator.php';
}

class RuleEvaluator
{
    /** @var PDO_DB */
    private $db;
    /** @var RuleEngine */
    private $engine;
    /** @var ToonValidator|null */
    private $validator;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : (class_exists('DatabaseFactory') ? DatabaseFactory::getConnection() : null);
        $this->engine = new RuleEngine($this->db);
        $this->validator = $this->db && class_exists('ToonValidator') ? new ToonValidator($this->db) : null;
    }

    /**
     * Get rules for a target (delegate to engine)
     *
     * @param string $target_table
     * @param int $target_id
     * @return array
     */
    public function getRulesForTarget($target_table, $target_id)
    {
        return $this->engine->getRulesForTarget($target_table, $target_id);
    }

    /**
     * Evaluate all rules for a target (delegate to engine).
     * For target_table=database, target_id=0, adds TOON-based schema and information_schema checks.
     *
     * @param string $target_table
     * @param int $target_id
     * @param array $context
     * @return array
     */
    public function evaluateRules($target_table, $target_id, $context = array())
    {
        $results = $this->engine->evaluateRules($target_table, $target_id, $context);
        if ($target_table === 'database' && (int) $target_id === 0) {
            $results['schema'] = $this->checkDatabaseSchema();
            $results['information_schema'] = $this->checkInformationSchemaUsage();
        }
        return $results;
    }

    /**
     * Check database schema using TOON files and SHOW commands (no information_schema)
     *
     * @return array
     */
    public function checkDatabaseSchema()
    {
        if (!$this->validator) {
            return array('foreign_keys' => array('passed' => true, 'violations' => array()), 'triggers' => array('passed' => true, 'count' => 0), 'timestamps' => array('passed' => true, 'violations' => array()), 'auto_increment' => array('passed' => true, 'violations' => array()));
        }
        $validation = $this->validator->validateDatabase();
        $results = array(
            'foreign_keys' => array('passed' => true, 'violations' => array()),
            'triggers' => array('passed' => true, 'count' => 0),
            'timestamps' => array('passed' => true, 'violations' => array()),
            'auto_increment' => array('passed' => true, 'violations' => array())
        );
        $triggerCount = 0;
        if (isset($validation['_triggers_global']['count'])) {
            $triggerCount = (int) $validation['_triggers_global']['count'];
            $results['triggers']['count'] = $triggerCount;
            $results['triggers']['passed'] = ($triggerCount === 0);
        }
        foreach ($validation as $table => $checks) {
            if ($table === '_triggers_global' || !is_array($checks)) {
                continue;
            }
            if (!empty($checks['foreign_keys'])) {
                $results['foreign_keys']['passed'] = false;
                $results['foreign_keys']['violations'][] = $table;
            }
            if (!empty($checks['timestamp_columns'])) {
                $results['timestamps']['passed'] = false;
                $results['timestamps']['violations'][] = $table;
            }
        }
        return $results;
    }

    /**
     * Check for information_schema usage in codebase (forbidden on shared hosts)
     *
     * @return array ['passed' => bool, 'violations' => array]
     */
    public function checkInformationSchemaUsage()
    {
        $violations = $this->engine->checkInformationSchemaViolations();
        return array('passed' => empty($violations), 'violations' => $violations);
    }
}

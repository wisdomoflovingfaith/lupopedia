<?php
/**
 * Kritik Integration Protocol Validator
 *
 * Validates critique structure and content for KIP processing.
 * Implements Kritik Integration Protocol as defined in version 4.0.77.
 *
 * @package Lupopedia
 * @version 4.0.106
 * @author Captain Wolfie
 */

namespace Lupopedia\KIP;

/**
 * KIPValidator
 *
 * Validates critique data structure and content before processing.
 */
class KIPValidator
{
    /** @var array Validation errors */
    private $errors = [];

    /**
     * Validate critique structure and content
     *
     * @param array $critique Critique data to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $critique): bool
    {
        $this->errors = [];

        // Required fields
        $required = ['source', 'content', 'type'];
        foreach ($required as $field) {
            if (!isset($critique[$field]) || empty($critique[$field])) {
                $this->errors[] = "Missing required field: {$field}";
            }
        }

        // Validate type
        if (isset($critique['type'])) {
            $validTypes = ['architectural', 'doctrine', 'implementation', 'documentation', 'other'];
            if (!in_array($critique['type'], $validTypes)) {
                $this->errors[] = "Invalid type. Must be one of: " . implode(', ', $validTypes);
            }
        }

        return empty($this->errors);
    }

    /**
     * Get validation errors
     *
     * @return array Array of error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}

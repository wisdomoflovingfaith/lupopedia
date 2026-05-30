<?php

namespace App\Services\Validation;

/**
 * Mandatory validation gate for Lupopedia headers.
 *
 * Hard fail behavior:
 * - missing required fields => invalid
 * - invalid field types => invalid
 * - malformed header input => invalid
 */
class HeaderValidationService
{
    /** @var object|null */
    private $actorService;

    /** @var array */
    private $requiredFields;

    public function __construct($actorService = null)
    {
        $this->actorService = $actorService;
        $this->requiredFields = array(
            'version_when_written',
            'file_path_from_root',
            'last_modified_utc',
            'channel_id',
            'thread_id',
            'actor_id',
            'actor_name',
            'artifact_type',
            'artifact_kind',
        );
    }

    /**
     * Validate a parsed header array.
     *
     * @param mixed $header
     * @return array
     */
    public function validate($header)
    {
        $errors = array();

        if (!is_array($header) || empty($header)) {
            return array(
                'valid' => false,
                'errors' => array('Malformed header: expected non-empty array.'),
            );
        }

        $this->validateRequiredFields($header, $errors);
        $this->validateFieldTypes($header, $errors);
        $this->validateConsistency($header, $errors);

        return array(
            'valid' => count($errors) === 0,
            'errors' => $errors,
        );
    }

    /**
     * @param array $header
     * @param array $errors
     * @return void
     */
    private function validateRequiredFields($header, &$errors)
    {
        $i = 0;
        $count = count($this->requiredFields);
        while ($i < $count) {
            $field = $this->requiredFields[$i];
            if (!array_key_exists($field, $header)) {
                $errors[] = 'Missing required field: ' . $field;
            } elseif ($this->isEmptyString($header[$field])) {
                $errors[] = 'Required field is empty: ' . $field;
            }
            $i++;
        }
    }

    /**
     * @param array $header
     * @param array $errors
     * @return void
     */
    private function validateFieldTypes($header, &$errors)
    {
        $timestampFields = array('last_modified_utc');
        $idFields = array('channel_id', 'thread_id', 'actor_id');
        $stringFields = array(
            'version_when_written',
            'file_path_from_root',
            'actor_name',
            'artifact_type',
            'artifact_kind',
        );

        $this->validateNumericFields($header, $timestampFields, 'Timestamp field must be BIGINT or numeric string', $errors);
        $this->validateNumericFields($header, $idFields, 'ID field must be numeric', $errors);
        $this->validateStringFields($header, $stringFields, $errors);
    }

    /**
     * @param array $header
     * @param array $errors
     * @return void
     */
    private function validateConsistency($header, &$errors)
    {
        if (isset($header['file_path_from_root']) && !$this->isEmptyString($header['file_path_from_root'])) {
            if (!$this->isValidFilePathFromRoot($header['file_path_from_root'])) {
                $errors[] = 'Invalid file_path_from_root format.';
            }
        }

        if (isset($header['version_when_written']) && !$this->isEmptyString($header['version_when_written'])) {
            if (!$this->isSemver($header['version_when_written'])) {
                $errors[] = 'Invalid version_when_written format. Expected semver (e.g. 4.0.86).';
            }
        }

        if (isset($header['actor_id']) && isset($header['actor_name'])) {
            $actorId = $header['actor_id'];
            $actorName = trim((string) $header['actor_name']);
            if ($this->isNumericValue($actorId) && $actorName !== '') {
                $resolvedActorName = $this->resolveActorNameById((int) $actorId);
                if ($resolvedActorName !== null && $resolvedActorName !== $actorName) {
                    $errors[] = 'actor_id does not match actor_name.';
                }
            }
        }
    }

    /**
     * @param array $header
     * @param array $fields
     * @param string $messagePrefix
     * @param array $errors
     * @return void
     */
    private function validateNumericFields($header, $fields, $messagePrefix, &$errors)
    {
        $i = 0;
        $count = count($fields);
        while ($i < $count) {
            $field = $fields[$i];
            if (array_key_exists($field, $header) && !$this->isEmptyString($header[$field])) {
                if (!$this->isNumericValue($header[$field])) {
                    $errors[] = $messagePrefix . ': ' . $field;
                }
            }
            $i++;
        }
    }

    /**
     * @param array $header
     * @param array $fields
     * @param array $errors
     * @return void
     */
    private function validateStringFields($header, $fields, &$errors)
    {
        $i = 0;
        $count = count($fields);
        while ($i < $count) {
            $field = $fields[$i];
            if (array_key_exists($field, $header)) {
                if (!is_string($header[$field]) || trim($header[$field]) === '') {
                    $errors[] = 'String field must be non-empty: ' . $field;
                }
            }
            $i++;
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isNumericValue($value)
    {
        if (is_int($value)) {
            return true;
        }
        if (is_string($value) && trim($value) !== '' && ctype_digit(trim($value))) {
            return true;
        }
        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isEmptyString($value)
    {
        return is_string($value) && trim($value) === '';
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isSemver($value)
    {
        $value = trim((string) $value);
        return preg_match('/^\d+\.\d+\.\d+$/', $value) === 1;
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isValidFilePathFromRoot($path)
    {
        $path = trim((string) $path);
        if ($path === '') {
            return false;
        }
        if (strpos($path, '\\') !== false) {
            return false;
        }
        if (strpos($path, '..') !== false) {
            return false;
        }
        if (strpos($path, '//') !== false) {
            return false;
        }
        if (substr($path, 0, 1) === '/') {
            return false;
        }
        return preg_match('/^[A-Za-z0-9_\.\/]+$/', $path) === 1;
    }

    /**
     * Resolve actor name for consistency checking when lookup service exists.
     *
     * @param int $actorId
     * @return string|null
     */
    private function resolveActorNameById($actorId)
    {
        if (!is_object($this->actorService)) {
            return null;
        }

        if (method_exists($this->actorService, 'getActorName')) {
            $name = $this->actorService->getActorName($actorId);
            if (is_string($name) && trim($name) !== '') {
                return trim($name);
            }
        }

        if (method_exists($this->actorService, 'getActorById')) {
            $row = $this->actorService->getActorById($actorId);
            if (is_array($row) && isset($row['actor_name']) && is_string($row['actor_name']) && trim($row['actor_name']) !== '') {
                return trim($row['actor_name']);
            }
        }

        return null;
    }
}

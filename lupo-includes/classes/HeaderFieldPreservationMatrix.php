<?php
/**
 * HeaderFieldPreservationMatrix
 *
 * Implements Thread 1002 preservation categories for header fields:
 * - lossless
 * - semantic-equivalence
 * - lossy/display-only
 * - never-projected
 *
 * For this P0 first pass, we deterministically encode:
 * - arrays/maps as JSON strings
 * - lossy fields under display__<field_name>
 */

class HeaderFieldPreservationMatrix
{
    /** @var array */
    private $losslessKeys = array();
    /** @var array */
    private $semanticKeys = array();
    /** @var array */
    private $lossyKeys = array();
    /** @var array */
    private $neverProjectedKeyPrefixes = array();

    public function __construct()
    {
        $this->losslessKeys = array(
            'file_path_from_root' => true,
            'web_path' => true,
            'channel_id' => true,
            'thread_id' => true,
            'actor_id' => true,
            'delegation_chain' => true,
            'artifact_type' => true,
            'artifact_kind' => true,
            'purpose' => true,
        );

        $this->semanticKeys = array(
            'lupopedia.version' => true,
            'lupopedia.schema' => true,
            'system_version' => true,
            'last_modified_utc' => true,
            'namespace' => true,
            'tags' => true,
        );

        $this->lossyKeys = array(
            'actor_name' => true,
            'channel_name' => true,
            'thread_name' => true,
            'title' => true,
            'traits' => true,
            'mood_rgb' => true,
        );

        $this->neverProjectedKeyPrefixes = array(
            '__' => true,
        );
    }

    /**
     * Normalize tags for semantic equivalence.
     *
     * @param mixed $value
     * @return array
     */
    private function normalizeTags($value)
    {
        $tags = array();
        if (is_array($value)) {
            foreach ($value as $t) {
                $t = trim((string)$t);
                if ($t !== '') {
                    $tags[$t] = true;
                }
            }
        } else {
            $t = trim((string)$value);
            if ($t !== '') {
                $tags[$t] = true;
            }
        }
        $out = array_keys($tags);
        sort($out, SORT_STRING);
        return $out;
    }

    /**
     * Encode a YAML scalar/array/map deterministically for lupo_metadata.property_value.
     *
     * @param mixed $value
     * @return string|null
     */
    private function encodeValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }
        if (is_array($value)) {
            // Deterministic JSON for arrays. Fixture should avoid non-deterministic map key orders.
            return json_encode($value, JSON_UNESCAPED_SLASHES);
        }
        // Strings
        return (string)$value;
    }

    /**
     * Classify and map header fields to DB property rows.
     *
     * @param array $headerFields Parsed lupopedia.headers block.
     * @return array Array of ['property_key' => ..., 'property_value' => ..., 'category' => ...]
     */
    public function classifyFields($headerFields)
    {
        $out = array();
        if (!is_array($headerFields)) {
            return $out;
        }

        foreach ($headerFields as $key => $value) {
            $key = (string)$key;
            if ($key === '') {
                continue;
            }

            // Never-projected heuristics for this first pass: omit keys prefixed with "__".
            foreach ($this->neverProjectedKeyPrefixes as $prefix => $_) {
                if (strpos($key, $prefix) === 0) {
                    // Drop silently: never project these into authoritative DB rows.
                    continue 2;
                }
            }

            $category = null;
            if (isset($this->losslessKeys[$key])) {
                $category = 'lossless';
                $dbKey = $key;
            } elseif (isset($this->semanticKeys[$key])) {
                $category = 'semantic-equivalence';
                if ($key === 'tags') {
                    $value = $this->normalizeTags($value);
                }
                $dbKey = $key;
            } elseif (isset($this->lossyKeys[$key])) {
                $category = 'lossy/display-only';
                $dbKey = 'display__' . $key;
            } else {
                // Unknown keys: preserve deterministically as semantic-equivalence for safety.
                $category = 'semantic-equivalence';
                $dbKey = $key;
            }

            $encoded = $this->encodeValue($value);
            $out[] = array(
                'property_key' => $dbKey,
                'property_value' => $encoded,
                'category' => $category,
            );
        }

        return $out;
    }
}


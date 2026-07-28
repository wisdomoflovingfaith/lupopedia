<?php

/**
 * EmotionalFaucetPluginLoader — Discover and load emotional hermeneutic faucet plugins.
 *
 * Enforces SAMSAṂ phonetic-collision guardrails:
 *   - forbid_vendor_collision: Samsung, Android, phone_network
 *   - namespace_exclusion for SAMSAṂ
 *   - Doctrinal pairing SAMSAṂ + PUKA only
 *   - Reject vendor / phone-network edges (external real-world metadata, not Lupopedia)
 *
 * Metadata roots:
 *   database/lupopedia/emotional_faucets/
 *   database/lupopedia/emotional_domains/
 */

class EmotionalFaucetPluginLoader
{
    /** @var string */
    protected $faucetsPath;

    /** @var string */
    protected $domainsPath;

    /** @var array|null */
    protected $namespaceExclusions;

    /** @var array */
    protected static $defaultBlockedTokens = array(
        'samsung',
        'samsung electronics',
        'phone metadata',
        'android',
        'phone_network',
        'phone network',
    );

    public function __construct($basePath = null)
    {
        if ($basePath === null) {
            if (defined('LUPO_DATABASE_DIR') && LUPO_DATABASE_DIR) {
                $root = rtrim(LUPO_DATABASE_DIR, DIRECTORY_SEPARATOR . '/\\') . DIRECTORY_SEPARATOR . 'lupopedia';
            } else {
                $root = (defined('LUPOPEDIA_PATH') && LUPOPEDIA_PATH)
                    ? rtrim(LUPOPEDIA_PATH, DIRECTORY_SEPARATOR . '/\\') . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia'
                    : dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia';
            }
        } else {
            $root = rtrim($basePath, DIRECTORY_SEPARATOR . '/\\');
        }

        $this->faucetsPath = $root . DIRECTORY_SEPARATOR . 'emotional_faucets';
        $this->domainsPath = $root . DIRECTORY_SEPARATOR . 'emotional_domains';
    }

    /**
     * Load faucet plugin metadata by key (e.g. samsam / SAMSAṂ).
     *
     * @param string $faucetKey
     * @return array
     * @throws Exception
     */
    public function loadFaucetPlugin($faucetKey)
    {
        $normalized = $this->normalizeFaucetKey($faucetKey);
        $path = $this->faucetsPath . DIRECTORY_SEPARATOR . $normalized . '.json';

        if (!file_exists($path)) {
            throw new Exception('Emotional faucet plugin not found: ' . $faucetKey);
        }

        $meta = $this->readJson($path);
        $this->assertNoVendorCollision($meta, $faucetKey);
        $this->assertDoctrinalPairing($meta, $normalized);
        $this->assertNoVendorEdges($meta);

        return $meta;
    }

    /**
     * Load emotional domain metadata (e.g. emo_attachment).
     *
     * @param string $domainCode
     * @return array
     * @throws Exception
     */
    public function loadDomain($domainCode)
    {
        $slug = $this->domainSlug($domainCode);
        $path = $this->domainsPath . DIRECTORY_SEPARATOR . $slug . '.json';

        if (!file_exists($path)) {
            throw new Exception('Emotional domain metadata not found: ' . $domainCode);
        }

        $meta = $this->readJson($path);
        $this->assertNoVendorCollision($meta, $domainCode);
        $this->assertNoVendorEdges($meta);

        if (isset($meta['preferred_faucet'])) {
            $pref = $this->normalizeFaucetKey($meta['preferred_faucet']);
            if ($pref === 'samsam') {
                $pair = isset($meta['doctrinal_pair']) ? strtoupper((string) $meta['doctrinal_pair']) : '';
                if ($pair !== 'PUKA') {
                    throw new Exception('EMO_ATTACHMENT / SAMSAṂ must be doctrinally paired with PUKA only.');
                }
            }
        }

        return $meta;
    }

    /**
     * Resolve preferred faucet for a domain, enforcing SAMSAṂ + PUKA pairing.
     *
     * @param string $domainCode
     * @return array|null faucet metadata or null if no preferred faucet
     */
    public function resolvePreferredFaucet($domainCode)
    {
        $domain = $this->loadDomain($domainCode);
        if (!isset($domain['preferred_faucet']) || $domain['preferred_faucet'] === '' || $domain['preferred_faucet'] === null) {
            return null;
        }

        $faucet = $this->loadFaucetPlugin($domain['preferred_faucet']);

        if ($this->normalizeFaucetKey($domain['preferred_faucet']) === 'samsam') {
            $faucet['resolved_pair'] = 'PUKA';
            $faucet['pairing_enforced'] = true;
        }

        return $faucet;
    }

    /**
     * True if text looks like forbidden vendor / phone-network collision for SAMSAṂ namespace.
     *
     * @param string $text
     * @return bool
     */
    public function isVendorCollisionToken($text)
    {
        $hay = $this->normalizeToken($text);
        foreach ($this->blockedTokens() as $blocked) {
            if ($hay === $blocked || strpos($hay, $blocked) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Filter edge lists: drop vendor / phone-network related edges.
     *
     * @param array $edges
     * @return array
     */
    public function filterVendorRelatedEdges(array $edges)
    {
        $kept = array();
        foreach ($edges as $edge) {
            if ($this->edgeLooksVendorRelated($edge)) {
                continue;
            }
            $kept[] = $edge;
        }
        return $kept;
    }

    /**
     * @param array $meta
     * @param string $context
     * @throws Exception
     */
    protected function assertNoVendorCollision(array $meta, $context)
    {
        $name = isset($meta['name']) ? (string) $meta['name'] : '';
        $key = isset($meta['faucet_key']) ? (string) $meta['faucet_key'] : '';
        if ($this->normalizeFaucetKey($name) === 'samsam' || $this->normalizeFaucetKey($key) === 'samsam' || $this->normalizeFaucetKey($context) === 'samsam') {
            // SAMSAṂ metadata may list exclusion tokens; verify doctrinal fields exist.
            if (!isset($meta['forbid_vendor_collision']) || !is_array($meta['forbid_vendor_collision'])) {
                throw new Exception('SAMSAṂ faucet metadata missing forbid_vendor_collision guardrail.');
            }
            $this->assertNamespaceExclusionLoaded();
            return;
        }

        // Non-SAMSAṂ payloads must not claim identity with Samsung vendor namespace.
        if ($this->isVendorCollisionToken($name) || $this->isVendorCollisionToken($key)) {
            throw new Exception('Rejected vendor collision metadata in emotional faucet load (' . $context . '). Samsung/phone-network metadata is external and not part of Lupopedia.');
        }

        // Reject accidental preferred_faucet aliases that map SAMSAṂ to Samsung.
        if (isset($meta['preferred_faucet']) && $this->isVendorCollisionToken($meta['preferred_faucet'])) {
            throw new Exception('preferred_faucet must not resolve to Samsung/phone-network vendor tokens.');
        }
    }

    /**
     * @param array $meta
     * @param string $normalizedKey
     * @throws Exception
     */
    protected function assertDoctrinalPairing(array $meta, $normalizedKey)
    {
        if ($normalizedKey !== 'samsam') {
            return;
        }
        $partner = '';
        if (isset($meta['doctrinal_pair']['partner'])) {
            $partner = strtoupper((string) $meta['doctrinal_pair']['partner']);
        } elseif (isset($meta['doctrinal_pair']) && is_string($meta['doctrinal_pair'])) {
            $partner = strtoupper($meta['doctrinal_pair']);
        }
        if ($partner !== 'PUKA') {
            throw new Exception('SAMSAṂ must be doctrinally paired with PUKA only.');
        }
    }

    /**
     * @param array $meta
     * @throws Exception
     */
    protected function assertNoVendorEdges(array $meta)
    {
        if (!isset($meta['edges']) || !is_array($meta['edges'])) {
            return;
        }
        $filtered = $this->filterVendorRelatedEdges($meta['edges']);
        if (count($filtered) !== count($meta['edges'])) {
            throw new Exception('Vendor-related edges are forbidden in emotional faucet/domain metadata (Samsung/phone-network is external real-world metadata).');
        }
    }

    /**
     * @param mixed $edge
     * @return bool
     */
    protected function edgeLooksVendorRelated($edge)
    {
        if (is_string($edge)) {
            return $this->isVendorCollisionToken($edge);
        }
        if (!is_array($edge)) {
            return false;
        }
        $type = isset($edge['type']) ? (string) $edge['type'] : '';
        $to = isset($edge['to']) ? (string) $edge['to'] : (isset($edge['target']) ? (string) $edge['target'] : '');
        $vendorTypes = array('vendor', 'phone_network', 'device_oem', 'android_oem', 'oem');
        if (in_array(strtolower($type), $vendorTypes, true)) {
            return true;
        }
        return $this->isVendorCollisionToken($type) || $this->isVendorCollisionToken($to) || $this->isVendorCollisionToken(json_encode($edge));
    }

    protected function assertNamespaceExclusionLoaded()
    {
        $ex = $this->getNamespaceExclusions();
        if (!isset($ex['namespace_exclusion']['SAMSAṂ']) && !isset($ex['namespace_exclusion']['SAMSAM'])) {
            // Accept unicode key; also try NFC-ish fallbacks via blocked_tokens.
            if (!isset($ex['blocked_tokens']) || !is_array($ex['blocked_tokens'])) {
                throw new Exception('namespace_exclusions.json missing SAMSAṂ exclusion rules.');
            }
        }
    }

    /**
     * @return array
     */
    protected function getNamespaceExclusions()
    {
        if ($this->namespaceExclusions !== null) {
            return $this->namespaceExclusions;
        }
        $path = $this->faucetsPath . DIRECTORY_SEPARATOR . 'namespace_exclusions.json';
        if (!file_exists($path)) {
            $this->namespaceExclusions = array('blocked_tokens' => self::$defaultBlockedTokens);
            return $this->namespaceExclusions;
        }
        $this->namespaceExclusions = $this->readJson($path);
        return $this->namespaceExclusions;
    }

    /**
     * @return array
     */
    protected function blockedTokens()
    {
        $ex = $this->getNamespaceExclusions();
        $tokens = self::$defaultBlockedTokens;
        if (isset($ex['blocked_tokens']) && is_array($ex['blocked_tokens'])) {
            foreach ($ex['blocked_tokens'] as $t) {
                $tokens[] = $this->normalizeToken($t);
            }
        }
        if (isset($ex['namespace_exclusion']) && is_array($ex['namespace_exclusion'])) {
            foreach ($ex['namespace_exclusion'] as $list) {
                if (!is_array($list)) {
                    continue;
                }
                foreach ($list as $t) {
                    $tokens[] = $this->normalizeToken($t);
                }
            }
        }
        return array_values(array_unique($tokens));
    }

    /**
     * @param string $key
     * @return string
     */
    protected function normalizeFaucetKey($key)
    {
        $k = trim((string) $key);
        // PHP strtolower is not Unicode-aware; map SAMSAṂ / samsaṃ explicitly first.
        $k = str_replace(
            array('Ṃ', 'ṃ', 'Ṁ', 'ṁ', 'Ṃ', 'Ṁ'),
            'm',
            $k
        );
        $k = strtolower($k);
        $k = preg_replace('/[^a-z0-9_]+/', '', $k);
        // Phonetic / truncated forms still resolve to the Buddhist faucet, never Samsung.
        if ($k === 'samsam' || $k === 'samsara' || $k === 'samsa' || strpos($k, 'samsam') === 0) {
            return 'samsam';
        }
        return $k;
    }

    /**
     * @param string $domainCode
     * @return string
     */
    protected function domainSlug($domainCode)
    {
        $d = strtolower(trim((string) $domainCode));
        $d = str_replace(array('-', ' '), '_', $d);
        if ($d === 'attachment') {
            return 'emo_attachment';
        }
        return preg_replace('/[^a-z0-9_]+/', '', $d);
    }

    /**
     * @param string $text
     * @return string
     */
    protected function normalizeToken($text)
    {
        $t = strtolower(trim((string) $text));
        $t = str_replace(array('_', '-'), ' ', $t);
        $t = preg_replace('/\s+/', ' ', $t);
        return $t;
    }

    /**
     * @param string $path
     * @return array
     * @throws Exception
     */
    protected function readJson($path)
    {
        $raw = file_get_contents($path);
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw new Exception('Invalid JSON in ' . $path . ': ' . json_last_error_msg());
        }
        return $data;
    }
}

<?php

/**
 * Cross-origin semantic navbar embed: federation node + trust gate.
 *
 * Third-party pages are allowed only when:
 * 1) lupo_federation_nodes has a row for the embedder origin (node_base_url), and
 * 2) lupo_federated_trust links the hub node (source) to that node (target) with trust_type semantic_widget.
 *
 * Untrusted or unknown embed attempts touch lupo_federation_discovery (by host) for operator review.
 * Optional grouping: lupo_federation_categories + lupo_federation_category_map on the target node.
 *
 * Hub node id: LUPO_HUB_FEDERATION_NODE_ID constant if defined, else 1.
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

class SemanticNavbarEmbedContext
{
    /** Primary / local content when not a cross-origin embed request. */
    const DEFAULT_FEDERATION_NODE_ID = 1;

    /** lupo_federated_trust.trust_type for allowed semantic widget embeds. */
    const TRUST_TYPE_SEMANTIC_WIDGET = 'semantic_widget';

    /**
     * Emit CORS headers for browser fetches from embedder pages.
     *
     * @return void
     */
    public static function emitCorsHeaders()
    {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? trim((string) $_SERVER['HTTP_ORIGIN']) : '';
        if ($origin !== '' && strlen($origin) < 512) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Credentials: false');
            header('Vary: Origin');
        } else {
            header('Access-Control-Allow-Origin: *');
        }
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
    }

    /**
     * @return int
     */
    public static function hubFederationNodeId()
    {
        if (defined('LUPO_HUB_FEDERATION_NODE_ID')) {
            $h = (int) constant('LUPO_HUB_FEDERATION_NODE_ID');
            if ($h > 0) {
                return $h;
            }
        }
        return self::DEFAULT_FEDERATION_NODE_ID;
    }

    /**
     * Normalize to scheme://host[:port] only (max 500 chars for node_base_url).
     *
     * @param string $raw
     * @return string empty if invalid
     */
    public static function normalizeEmbedOrigin($raw)
    {
        $s = trim((string) $raw);
        if ($s === '' || strlen($s) > 600) {
            return '';
        }
        $u = parse_url($s);
        if (!is_array($u) || empty($u['host'])) {
            return '';
        }
        $scheme = isset($u['scheme']) ? strtolower((string) $u['scheme']) : '';
        if ($scheme !== 'http' && $scheme !== 'https') {
            return '';
        }
        $host = (string) $u['host'];
        $port = isset($u['port']) ? (int) $u['port'] : 0;
        $origin = $scheme . '://' . $host;
        $defaultPort = ($scheme === 'https') ? 443 : 80;
        if ($port > 0 && $port !== $defaultPort) {
            $origin .= ':' . $port;
        }
        if (strlen($origin) > 500) {
            return '';
        }
        return $origin;
    }

    /**
     * Resolve cross-origin embed: require federation node + federated_trust.
     *
     * @param object $db PDO_DB
     * @param string $prefix
     * @return array keys: allowed (bool), federation_node_id (int), cross_origin (bool), reason (string)
     */
    public static function resolveEmbedFederationContext($db, $prefix)
    {
        $raw = '';
        if (isset($_SERVER['HTTP_ORIGIN']) && is_string($_SERVER['HTTP_ORIGIN']) && trim($_SERVER['HTTP_ORIGIN']) !== '') {
            $raw = $_SERVER['HTTP_ORIGIN'];
        } elseif (isset($_GET['embed_origin']) && is_string($_GET['embed_origin'])) {
            $raw = $_GET['embed_origin'];
        }
        $origin = self::normalizeEmbedOrigin($raw);
        if ($origin === '') {
            return array(
                'allowed' => true,
                'federation_node_id' => self::hubFederationNodeId(),
                'cross_origin' => false,
                'reason' => '',
            );
        }

        $hubId = self::hubFederationNodeId();
        $nodes_t = $prefix . 'federation_nodes';
        $node = $db->fetchRow(
            "SELECT federation_node_id FROM {$nodes_t} WHERE node_base_url = :u AND is_deleted = 0 LIMIT 1",
            array('u' => $origin)
        );

        if (!$node || !isset($node['federation_node_id'])) {
            self::touchFederationDiscovery($db, $prefix, $origin, 'unknown_node');
            return array(
                'allowed' => false,
                'federation_node_id' => $hubId,
                'cross_origin' => true,
                'reason' => 'unknown_node',
            );
        }

        $targetId = (int) $node['federation_node_id'];
        if ($targetId === $hubId) {
            return array(
                'allowed' => true,
                'federation_node_id' => $hubId,
                'cross_origin' => false,
                'reason' => '',
            );
        }

        $trust_t = $prefix . 'federated_trust';
        $trust = $db->fetchRow(
            "SELECT trust_id FROM {$trust_t}
             WHERE source_node_id = :src AND target_node_id = :tgt AND is_deleted = 0
               AND trust_type = :tt
             LIMIT 1",
            array(
                'src' => $hubId,
                'tgt' => $targetId,
                'tt' => self::TRUST_TYPE_SEMANTIC_WIDGET,
            )
        );

        if (!$trust || !isset($trust['trust_id'])) {
            self::touchFederationDiscovery($db, $prefix, $origin, 'no_trust');
            return array(
                'allowed' => false,
                'federation_node_id' => $hubId,
                'cross_origin' => true,
                'reason' => 'no_trust',
            );
        }

        return array(
            'allowed' => true,
            'federation_node_id' => $targetId,
            'cross_origin' => true,
            'reason' => '',
        );
    }

    /**
     * Record or refresh discovery row for untrusted embed attempts (by registrable host).
     *
     * @param object $db
     * @param string $prefix
     * @param string $originNormalized
     * @param string $reason unknown_node|no_trust
     * @return void
     */
    public static function touchFederationDiscovery($db, $prefix, $originNormalized, $reason)
    {
        $host = self::hostFromOrigin($originNormalized);
        if ($host === '') {
            return;
        }
        if (strlen($host) > 255) {
            $host = substr($host, 0, 255);
        }
        $now = (int) gmdate('YmdHis');
        $disc_t = $prefix . 'federation_discovery';
        $existing = $db->fetchRow(
            "SELECT federation_discovery_id FROM {$disc_t} WHERE domain = :d LIMIT 1",
            array('d' => $host)
        );
        $note = 'semantic_widget embed: ' . $reason;
        if ($existing && isset($existing['federation_discovery_id'])) {
            $fid = (int) $existing['federation_discovery_id'];
            $db->update(
                $disc_t,
                array(
                    'last_seen_ymdhis' => $now,
                    'updated_ymdhis' => $now,
                    'install_url' => substr($originNormalized, 0, 500),
                    'description' => $note,
                ),
                'federation_discovery_id = :fid',
                array('fid' => $fid)
            );
            return;
        }

        $maxRow = $db->fetchRow("SELECT COALESCE(MAX(federation_discovery_id), 0) AS m FROM {$disc_t}", array());
        $nextId = ($maxRow && isset($maxRow['m'])) ? ((int) $maxRow['m'] + 1) : 1;

        $db->insert($disc_t, array(
            'federation_discovery_id' => $nextId,
            'domain' => $host,
            'install_url' => substr($originNormalized, 0, 500),
            'is_lupopedia' => 0,
            'last_seen_ymdhis' => $now,
            'first_seen_ymdhis' => $now,
            'hashtag_count' => null,
            'question_count' => null,
            'atom_count' => null,
            'context_count' => null,
            'collection_count' => null,
            'keywords' => null,
            'description' => $note,
            'import_hashtags' => 0,
            'import_questions' => 0,
            'import_atoms' => 0,
            'import_collections' => 0,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
        ));
    }

    /**
     * @param string $originNormalized
     * @return string
     */
    private static function hostFromOrigin($originNormalized)
    {
        $u = parse_url($originNormalized);
        if (!is_array($u) || empty($u['host'])) {
            return '';
        }
        return strtolower((string) $u['host']);
    }
}

<?php

/**
 * Admin — Semantic navigation bar embed (PRD 21, PRD 28).
 * Web UI: register embedder federation nodes, grant semantic_widget trust (hub → target),
 * and generate copy-paste HTML for nav/semantic-navbar-js.
 *
 * Operators must not rely on raw SQL for routine embedder setup.
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

require_once LUPOPEDIA_PATH . '/includes/classes/SemanticNavbarEmbedContext.php';

class AdminSemanticWidgetHandler
{
    /**
     * @param object|null $db PDO_DB or null
     * @param string $prefix Table prefix
     * @param string $base Public path e.g. /lupopedia
     * @return string HTML
     */
    public static function render($db, $prefix, $base)
    {
        if (!function_exists('lupo_t')) {
            require_once LUPOPEDIA_PATH . '/includes/i18n.php';
        }

        $flash = '';
        if ($db && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $flash = self::processPost($db, $prefix);
        }

        $publicBase = rtrim($base, '/');
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? (string) $_SERVER['HTTP_HOST'] : '';
        $absoluteOrigin = ($host !== '') ? ($scheme . '://' . $host) : '';

        $slugIn = '';
        if (isset($_GET['semantic_slug']) && is_string($_GET['semantic_slug'])) {
            $slugIn = trim($_GET['semantic_slug']);
        }
        $slug = self::sanitizeSlug($slugIn);

        $hub = $publicBase . '/admin.php';
        $semanticSectionUrl = $publicBase . '/admin.php?section=semantic-widget';
        $scriptRel = $publicBase . '/nav/semantic-navbar-js';
        $query = ($slug !== '') ? ('?slug=' . rawurlencode($slug)) : '';
        $srcAbs = ($absoluteOrigin !== '') ? ($absoluteOrigin . $scriptRel . $query) : ($scriptRel . $query);
        $srcRel = $scriptRel . $query;

        $snippetAbsPlain = '<script src="' . $srcAbs . '"></script>';
        $snippetRelPlain = '<script src="' . $srcRel . '"></script>';

        $slugOptions = array();
        if ($db) {
            $contents_t = $prefix . 'contents';
            $slugOptions = $db->fetchAll(
                "SELECT slug, title FROM {$contents_t}
                 WHERE is_deleted = 0 AND (is_active = 1 OR is_active IS NULL)
                   AND slug IS NOT NULL AND TRIM(slug) <> ''
                 ORDER BY updated_ymdhis DESC
                 LIMIT 120",
                array()
            );
        }

        $csrf = function_exists('lupo_get_csrf_token') ? lupo_get_csrf_token() : '';
        $hubId = SemanticNavbarEmbedContext::hubFederationNodeId();

        $html = '<div class="admin-semantic-widget">';
        if ($flash !== '') {
            $html .= $flash;
        }

        $html .= '<p class="admin-section-description">' . htmlspecialchars(lupo_t('admin.semantic.intro', 'Same host as Lupopedia: set the content slug and copy the relative snippet first. For other domains, use the absolute snippet and the external-site (federation + trust) section below.'), ENT_QUOTES, 'UTF-8') . '</p>';

        $html .= '<form class="admin-data-paths-form" method="get" action="' . htmlspecialchars($hub, ENT_QUOTES, 'UTF-8') . '">';
        $html .= '<input type="hidden" name="section" value="semantic-widget" />';
        $html .= '<label class="admin-data-paths-label"><span>' . htmlspecialchars(lupo_t('admin.semantic.slug_label', 'Content slug'), ENT_QUOTES, 'UTF-8') . '</span> ';
        $html .= '<input type="text" name="semantic_slug" value="' . htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') . '" maxlength="255" style="min-width:16rem;" placeholder="' . htmlspecialchars(lupo_t('admin.semantic.slug_placeholder', 'e.g. my-page'), ENT_QUOTES, 'UTF-8') . '" />';
        $html .= '</label> ';
        $html .= '<button type="submit" class="admin-link" style="padding:0.35rem 0.75rem;">' . htmlspecialchars(lupo_t('admin.semantic.apply', 'Update snippet'), ENT_QUOTES, 'UTF-8') . '</button>';
        $html .= '</form>';

        if (!empty($slugOptions)) {
            $html .= '<p class="admin-muted">' . htmlspecialchars(lupo_t('admin.semantic.pick_slug', 'Or pick a published slug:'), ENT_QUOTES, 'UTF-8') . '</p>';
            $html .= '<ul class="admin-section-links" style="max-height:10rem;overflow:auto;">';
            foreach ($slugOptions as $row) {
                if (!isset($row['slug']) || trim((string) $row['slug']) === '') {
                    continue;
                }
                $s = trim((string) $row['slug']);
                $tit = isset($row['title']) ? trim((string) $row['title']) : '';
                $pickParams = array(
                    'section' => 'semantic-widget',
                    'semantic_slug' => $s,
                );
                $pickHref = $hub . '?' . self::buildQueryRaw($pickParams);
                $label = $tit !== '' ? ($tit . ' (' . $s . ')') : $s;
                $html .= '<li><a class="admin-link" href="' . htmlspecialchars($pickHref, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a></li>';
            }
            $html .= '</ul>';
        }

        $html .= '<h2 class="admin-data-panel-title" style="margin-top:1.25rem;">' . htmlspecialchars(lupo_t('admin.semantic.rel_title', 'Snippet (relative URL — same host as Lupopedia)'), ENT_QUOTES, 'UTF-8') . '</h2>';
        $html .= '<textarea id="semantic-snippet-rel" readonly="readonly" rows="4" class="admin-input" style="width:100%;max-width:56rem;font-family:monospace;font-size:0.85rem;">' . htmlspecialchars($snippetRelPlain, ENT_QUOTES, 'UTF-8') . '</textarea>';
        $html .= '<p><button type="button" class="admin-link" id="semantic-copy-rel">' . htmlspecialchars(lupo_t('admin.semantic.copy', 'Copy to clipboard'), ENT_QUOTES, 'UTF-8') . '</button></p>';

        $html .= '<h2 class="admin-data-panel-title" style="margin-top:1.25rem;">' . htmlspecialchars(lupo_t('admin.semantic.abs_title', 'Snippet (absolute URL — for other sites)'), ENT_QUOTES, 'UTF-8') . '</h2>';
        $html .= '<textarea id="semantic-snippet-abs" readonly="readonly" rows="4" class="admin-input" style="width:100%;max-width:56rem;font-family:monospace;font-size:0.85rem;">' . htmlspecialchars($snippetAbsPlain, ENT_QUOTES, 'UTF-8') . '</textarea>';
        $html .= '<p><button type="button" class="admin-link" id="semantic-copy-abs">' . htmlspecialchars(lupo_t('admin.semantic.copy', 'Copy to clipboard'), ENT_QUOTES, 'UTF-8') . '</button></p>';

        $html .= '<h2 class="admin-data-panel-title" style="margin-top:1.5rem;">' . htmlspecialchars(lupo_t('admin.semantic.federation_title', 'External sites (federation + trust)'), ENT_QUOTES, 'UTF-8') . '</h2>';
        $html .= '<p class="admin-section-description">' . htmlspecialchars(lupo_t('admin.semantic.federation_lead', 'The widget does not run for arbitrary third-party origins. Use the forms below (no SQL required). For each embedder site you must register its origin, grant semantic widget trust from the hub, and publish content for that federation node.'), ENT_QUOTES, 'UTF-8') . '</p>';
        $html .= '<ul class="admin-section-links">';
        $html .= '<li>' . htmlspecialchars(lupo_t('admin.semantic.federation_step_node', 'Register the embedder origin (scheme + host + non-default port if any) as a federation node.'), ENT_QUOTES, 'UTF-8') . '</li>';
        $html .= '<li>' . htmlspecialchars(lupo_t('admin.semantic.federation_step_trust', 'Grant trust from the hub node to that node with type semantic_widget (form below).'), ENT_QUOTES, 'UTF-8') . '</li>';
        $html .= '<li>' . htmlspecialchars(lupo_t('admin.semantic.federation_step_content', 'Publish lupo_contents for that federation_node_id and slug (content workflow / artifacts; same slug you pass in ?slug=).'), ENT_QUOTES, 'UTF-8') . '</li>';
        $html .= '</ul>';

        if ($db) {
            $html .= self::renderEmbedderForms($db, $prefix, $semanticSectionUrl, $csrf, $hubId);
        } else {
            $html .= '<p class="admin-error">' . htmlspecialchars(lupo_t('admin.semantic.db_required', 'Database required to register embedders.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        $html .= '<p class="admin-muted">' . htmlspecialchars(lupo_t('admin.semantic.federation_discovery', 'Attempts from origins without a node, or with a node but no trust row, return HTTP 403 and are recorded in lupo_federation_discovery (by domain) for review.'), ENT_QUOTES, 'UTF-8') . '</p>';
        $html .= '<p class="admin-muted">' . htmlspecialchars(lupo_t('admin.semantic.federation_categories', 'Optional: group nodes with lupo_federation_categories and lupo_federation_category_map. Hub id defaults to federation_node_id 1; override with LUPO_HUB_FEDERATION_NODE_ID in config if needed.'), ENT_QUOTES, 'UTF-8') . '</p>';

        $html .= '<p class="admin-muted">' . htmlspecialchars(lupo_t('admin.semantic.doc_ref', 'Spec: docs/prd/21_semantic_navbar.md (API shape, external sites, CORS).'), ENT_QUOTES, 'UTF-8') . '</p>';

        $html .= '<script type="text/javascript">'
            . '(function(){'
            . 'function copy(taId){var el=document.getElementById(taId);if(!el)return;'
            . 'el.focus();el.select();try{document.execCommand("copy");}catch(e){}'
            . '}'
            . 'var b1=document.getElementById("semantic-copy-abs");'
            . 'var b2=document.getElementById("semantic-copy-rel");'
            . 'if(b1)b1.onclick=function(){copy("semantic-snippet-abs");};'
            . 'if(b2)b2.onclick=function(){copy("semantic-snippet-rel");};'
            . '})();</script>';

        $html .= '</div>';

        return $html;
    }

    /**
     * @param object $db
     * @param string $prefix
     * @return string HTML flash
     */
    private static function processPost($db, $prefix)
    {
        if (!isset($_POST['semantic_widget_action']) || !is_string($_POST['semantic_widget_action'])) {
            return '';
        }
        $action = trim($_POST['semantic_widget_action']);
        if ($action === '') {
            return '';
        }

        $token = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
        $expect = function_exists('lupo_get_csrf_token') ? lupo_get_csrf_token() : '';
        if ($expect === '' || $token === '' || $token !== $expect) {
            return '<p class="admin-error">' . htmlspecialchars(lupo_t('admin.semantic.flash_csrf', 'Invalid or missing CSRF token. Try again.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        if ($action === 'add_embedder_node') {
            return self::postAddEmbedderNode($db, $prefix);
        }
        if ($action === 'grant_semantic_widget_trust') {
            return self::postGrantTrust($db, $prefix);
        }

        return '';
    }

    /**
     * @param object $db
     * @param string $prefix
     * @return string
     */
    private static function postAddEmbedderNode($db, $prefix)
    {
        $raw = isset($_POST['embedder_origin']) ? trim((string) $_POST['embedder_origin']) : '';
        $origin = SemanticNavbarEmbedContext::normalizeEmbedOrigin($raw);
        if ($origin === '') {
            return '<p class="admin-error">' . htmlspecialchars(lupo_t('admin.semantic.flash_origin_invalid', 'Enter a valid origin such as https://example.com (http or https only).'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        $nameIn = isset($_POST['embedder_node_name']) ? trim((string) $_POST['embedder_node_name']) : '';
        $nodeName = ($nameIn !== '') ? $nameIn : self::defaultNameFromOrigin($origin);

        $nodes_t = $prefix . 'federation_nodes';
        $existing = $db->fetchRow(
            "SELECT federation_node_id, is_deleted FROM {$nodes_t} WHERE node_base_url = :u LIMIT 1",
            array('u' => $origin)
        );

        $now = (int) gmdate('YmdHis');

        if ($existing && isset($existing['federation_node_id'])) {
            $fid = (int) $existing['federation_node_id'];
            if ((int) $existing['is_deleted'] === 0) {
                return '<p class="admin-error">' . htmlspecialchars(lupo_t('admin.semantic.flash_duplicate_origin', 'That origin is already registered.'), ENT_QUOTES, 'UTF-8') . '</p>';
            }
            $db->update(
                $nodes_t,
                array(
                    'is_deleted' => 0,
                    'deleted_ymdhis' => 0,
                    'updated_ymdhis' => $now,
                    'node_name' => $nodeName,
                    'node_type' => 'remote',
                    'status' => 1,
                ),
                'federation_node_id = :fid',
                array('fid' => $fid)
            );
            return '<p class="admin-success">' . htmlspecialchars(lupo_t('admin.semantic.flash_reactivated', 'Federation node reactivated.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        $maxRow = $db->fetchRow("SELECT COALESCE(MAX(federation_node_id), 0) AS m FROM {$nodes_t}", array());
        $nextId = ($maxRow && isset($maxRow['m'])) ? ((int) $maxRow['m'] + 1) : 1;

        $insert = array(
            'federation_node_id' => $nextId,
            'node_type' => 'remote',
            'node_base_url' => $origin,
            'default_department_id' => null,
            'node_name' => $nodeName,
            'description' => '',
            'node_description' => null,
            'allows_foreign_traits' => 1,
            'node_contact' => null,
            'meta_json' => null,
            'content_count' => 0,
            'atom_count' => 0,
            'hashtag_count' => 0,
            'actor_count' => 0,
            'last_sync_ymdhis' => 0,
            'trust_level' => 0,
            'status' => 1,
            'is_deleted' => 0,
            'deleted_ymdhis' => 0,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'active_theme_slug' => 'default',
        );

        $ok = $db->insert($nodes_t, $insert);
        if ($ok === false) {
            return '<p class="admin-error">' . htmlspecialchars(lupo_t('admin.semantic.flash_save_failed', 'Could not save. Check database logs.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        return '<p class="admin-success">' . htmlspecialchars(lupo_t('admin.semantic.flash_node_added', 'Federation node registered. Grant semantic widget trust next.'), ENT_QUOTES, 'UTF-8') . '</p>';
    }

    /**
     * @param object $db
     * @param string $prefix
     * @return string
     */
    private static function postGrantTrust($db, $prefix)
    {
        $target = isset($_POST['trust_target_node_id']) ? (int) $_POST['trust_target_node_id'] : 0;
        if ($target <= 0) {
            return '<p class="admin-error">' . htmlspecialchars(lupo_t('admin.semantic.flash_pick_node', 'Choose a target federation node.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        $hubId = SemanticNavbarEmbedContext::hubFederationNodeId();
        if ($target === $hubId) {
            return '<p class="admin-muted">' . htmlspecialchars(lupo_t('admin.semantic.flash_hub_no_trust', 'The hub node does not need a trust row to itself.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        $trust_t = $prefix . 'federated_trust';
        $tt = SemanticNavbarEmbedContext::TRUST_TYPE_SEMANTIC_WIDGET;
        $row = $db->fetchRow(
            "SELECT trust_id FROM {$trust_t} WHERE source_node_id = :s AND target_node_id = :t LIMIT 1",
            array('s' => $hubId, 't' => $target)
        );

        $now = (int) gmdate('YmdHis');

        if ($row && isset($row['trust_id'])) {
            $tid = (int) $row['trust_id'];
            $db->update(
                $trust_t,
                array(
                    'trust_type' => $tt,
                    'trust_level' => 1,
                    'is_deleted' => 0,
                    'deleted_ymdhis' => null,
                    'updated_ymdhis' => $now,
                    'verification_method' => 'admin',
                    'last_verified_ymdhis' => $now,
                ),
                'trust_id = :tid',
                array('tid' => $tid)
            );
            return '<p class="admin-success">' . htmlspecialchars(lupo_t('admin.semantic.flash_trust_updated', 'Trust row updated for semantic_widget.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        $maxRow = $db->fetchRow("SELECT COALESCE(MAX(trust_id), 0) AS m FROM {$trust_t}", array());
        $nextTrust = ($maxRow && isset($maxRow['m'])) ? ((int) $maxRow['m'] + 1) : 1;

        $ins = array(
            'trust_id' => $nextTrust,
            'source_node_id' => $hubId,
            'target_node_id' => $target,
            'trust_level' => 1,
            'trust_type' => $tt,
            'capabilities' => null,
            'restrictions' => null,
            'last_verified_ymdhis' => $now,
            'verification_method' => 'admin',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        );

        $ok = $db->insert($trust_t, $ins);
        if ($ok === false) {
            return '<p class="admin-error">' . htmlspecialchars(lupo_t('admin.semantic.flash_save_failed', 'Could not save. Check database logs.'), ENT_QUOTES, 'UTF-8') . '</p>';
        }

        return '<p class="admin-success">' . htmlspecialchars(lupo_t('admin.semantic.flash_trust_added', 'Semantic widget trust granted.'), ENT_QUOTES, 'UTF-8') . '</p>';
    }

    /**
     * @param object $db
     * @param string $prefix
     * @param string $adminHub
     * @param string $csrf
     * @param int $hubId
     * @return string
     */
    private static function renderEmbedderForms($db, $prefix, $adminHub, $csrf, $hubId)
    {
        $nodes_t = $prefix . 'federation_nodes';
        $trust_t = $prefix . 'federated_trust';
        $tt = SemanticNavbarEmbedContext::TRUST_TYPE_SEMANTIC_WIDGET;

        $nodes = $db->fetchAll(
            "SELECT federation_node_id, node_base_url, node_name, node_type FROM {$nodes_t} WHERE is_deleted = 0 ORDER BY federation_node_id ASC",
            array()
        );

        $html = '<h3 class="admin-data-panel-title" style="margin-top:1rem;">' . htmlspecialchars(lupo_t('admin.semantic.form_register_title', 'Register embedder origin'), ENT_QUOTES, 'UTF-8') . '</h3>';
        $html .= '<form class="admin-data-paths-form" method="post" action="' . htmlspecialchars($adminHub, ENT_QUOTES, 'UTF-8') . '" style="margin-bottom:1.25rem;">';
        $html .= '<input type="hidden" name="section" value="semantic-widget" />';
        $html .= '<input type="hidden" name="semantic_widget_action" value="add_embedder_node" />';
        $html .= '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') . '" />';
        $html .= '<p><label><span class="admin-data-paths-label">' . htmlspecialchars(lupo_t('admin.semantic.form_origin_label', 'Site origin'), ENT_QUOTES, 'UTF-8') . '</span><br />';
        $html .= '<input type="text" name="embedder_origin" maxlength="500" style="min-width:22rem;width:100%;max-width:40rem;" placeholder="https://example.com" required="required" /></label></p>';
        $html .= '<p><label><span class="admin-data-paths-label">' . htmlspecialchars(lupo_t('admin.semantic.form_node_name_label', 'Display name (optional)'), ENT_QUOTES, 'UTF-8') . '</span><br />';
        $html .= '<input type="text" name="embedder_node_name" maxlength="255" style="min-width:22rem;width:100%;max-width:40rem;" /></label></p>';
        $html .= '<p><button type="submit" class="admin-link">' . htmlspecialchars(lupo_t('admin.semantic.form_register_submit', 'Register federation node'), ENT_QUOTES, 'UTF-8') . '</button></p>';
        $html .= '</form>';

        $hubNote = lupo_t('admin.semantic.form_trust_hub_note', 'Hub node id for this install:') . ' ' . (string) $hubId;
        $html .= '<h3 class="admin-data-panel-title">' . htmlspecialchars(lupo_t('admin.semantic.form_trust_title', 'Grant semantic widget trust (hub → embedder node)'), ENT_QUOTES, 'UTF-8') . '</h3>';
        $html .= '<p class="admin-muted">' . htmlspecialchars($hubNote, ENT_QUOTES, 'UTF-8') . '</p>';

        $html .= '<form class="admin-data-paths-form" method="post" action="' . htmlspecialchars($adminHub, ENT_QUOTES, 'UTF-8') . '" style="margin-bottom:1.25rem;">';
        $html .= '<input type="hidden" name="section" value="semantic-widget" />';
        $html .= '<input type="hidden" name="semantic_widget_action" value="grant_semantic_widget_trust" />';
        $html .= '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') . '" />';
        $html .= '<p><label><span class="admin-data-paths-label">' . htmlspecialchars(lupo_t('admin.semantic.form_trust_target_label', 'Target node'), ENT_QUOTES, 'UTF-8') . '</span><br />';
        $html .= '<select name="trust_target_node_id" required="required" style="min-width:22rem;">';
        $html .= '<option value="">' . htmlspecialchars(lupo_t('admin.semantic.form_trust_select', '— select —'), ENT_QUOTES, 'UTF-8') . '</option>';
        foreach ($nodes as $n) {
            if (!isset($n['federation_node_id'])) {
                continue;
            }
            $nid = (int) $n['federation_node_id'];
            $label = (string) $nid . ' — ' . (isset($n['node_base_url']) ? (string) $n['node_base_url'] : '');
            if (isset($n['node_name']) && trim((string) $n['node_name']) !== '') {
                $label .= ' (' . trim((string) $n['node_name']) . ')';
            }
            $html .= '<option value="' . (int) $nid . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        $html .= '</select></label></p>';
        $html .= '<p><button type="submit" class="admin-link">' . htmlspecialchars(lupo_t('admin.semantic.form_trust_submit', 'Grant semantic widget trust'), ENT_QUOTES, 'UTF-8') . '</button></p>';
        $html .= '</form>';

        if (!empty($nodes)) {
            $html .= '<h3 class="admin-data-panel-title">' . htmlspecialchars(lupo_t('admin.semantic.table_title', 'Federation nodes and semantic_widget trust'), ENT_QUOTES, 'UTF-8') . '</h3>';
            $html .= '<table class="admin-data-table" style="width:100%;max-width:56rem;border-collapse:collapse;">';
            $html .= '<thead><tr>';
            $html .= '<th style="text-align:left;padding:0.35rem;border:1px solid #ccc;">' . htmlspecialchars(lupo_t('admin.semantic.col_id', 'ID'), ENT_QUOTES, 'UTF-8') . '</th>';
            $html .= '<th style="text-align:left;padding:0.35rem;border:1px solid #ccc;">' . htmlspecialchars(lupo_t('admin.semantic.col_origin', 'Origin'), ENT_QUOTES, 'UTF-8') . '</th>';
            $html .= '<th style="text-align:left;padding:0.35rem;border:1px solid #ccc;">' . htmlspecialchars(lupo_t('admin.semantic.col_trust', 'semantic_widget trust'), ENT_QUOTES, 'UTF-8') . '</th>';
            $html .= '</tr></thead><tbody>';
            foreach ($nodes as $n) {
                $nid = isset($n['federation_node_id']) ? (int) $n['federation_node_id'] : 0;
                $nbu = isset($n['node_base_url']) ? (string) $n['node_base_url'] : '';
                if ($nid === $hubId) {
                    $trustCell = lupo_t('admin.semantic.trust_na_hub', 'n/a (hub)');
                } else {
                    $tr = $db->fetchRow(
                        "SELECT trust_id FROM {$trust_t} WHERE source_node_id = :s AND target_node_id = :t AND trust_type = :tt AND is_deleted = 0 LIMIT 1",
                        array('s' => $hubId, 't' => $nid, 'tt' => $tt)
                    );
                    $trustCell = ($tr && isset($tr['trust_id'])) ? lupo_t('admin.semantic.trust_yes', 'yes') : lupo_t('admin.semantic.trust_no', 'no');
                }
                $html .= '<tr>';
                $html .= '<td style="padding:0.35rem;border:1px solid #ccc;">' . (int) $nid . '</td>';
                $html .= '<td style="padding:0.35rem;border:1px solid #ccc;">' . htmlspecialchars($nbu, ENT_QUOTES, 'UTF-8') . '</td>';
                $html .= '<td style="padding:0.35rem;border:1px solid #ccc;">' . htmlspecialchars($trustCell, ENT_QUOTES, 'UTF-8') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }

        return $html;
    }

    /**
     * @param string $originNormalized
     * @return string
     */
    private static function defaultNameFromOrigin($originNormalized)
    {
        $u = parse_url($originNormalized);
        if (!is_array($u) || empty($u['host'])) {
            return 'embedder';
        }
        return (string) $u['host'];
    }

    /**
     * @param string $slug
     * @return string
     */
    private static function sanitizeSlug($slug)
    {
        $s = trim((string) $slug);
        if ($s === '') {
            return '';
        }
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $s)) {
            return '';
        }
        if (strlen($s) > 255) {
            return substr($s, 0, 255);
        }
        return $s;
    }

    /**
     * @param array $params
     * @return string
     */
    private static function buildQueryRaw($params)
    {
        $parts = array();
        foreach ($params as $k => $v) {
            if ($v === null || $v === '') {
                continue;
            }
            $parts[] = rawurlencode((string) $k) . '=' . rawurlencode((string) $v);
        }
        return implode('&', $parts);
    }
}

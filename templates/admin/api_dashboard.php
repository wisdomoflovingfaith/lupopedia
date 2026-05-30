<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "templates/admin/api_dashboard.php"
#   web_path: "https://www.lupopedia.com/lupopedia/templates/admin/api_dashboard.php"
#   status: "in_progress"
#   when_updated: "20260418031245"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/api-dashboard-php.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/api-dashboard-php"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "02"
#   content_slug: "api-dashboard-php"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "API Call Agents / Channels dashboard (read-only)"
#   summary: "Include-only partial: notices, monthly summary, provider table; no document shell; expects channels bootstrap and auth."
# ---------------------------------------------------------------------
/**
 * API provider / budget dashboard fragment (include only).
 *
 * Caller must have loaded lupopedia-config.php (LUPOPEDIA_CONFIG_LOADED), session/auth,
 * and LupoLocale/i18n if the caller uses lupo_t(). This file uses plain English only.
 * Do not use raw PDO; reads ApiProviderChainService and LUPO_API_PROVIDER_CONFIG from globals.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

$api_dash_base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '');

$service = isset($GLOBALS['lupo_api_provider_chain_service']) ? $GLOBALS['lupo_api_provider_chain_service'] : null;
$service_ok = (
    $service
    && is_object($service)
    && method_exists($service, 'getRuntimeConfigSanitized')
    && method_exists($service, 'getBudgetSnapshot')
);

$global_api = isset($GLOBALS['LUPO_API_PROVIDER_CONFIG']) && is_array($GLOBALS['LUPO_API_PROVIDER_CONFIG'])
    ? $GLOBALS['LUPO_API_PROVIDER_CONFIG']
    : array();
$raw_providers = isset($global_api['providers']) && is_array($global_api['providers']) ? $global_api['providers'] : array();
$has_provider_rows = (count($raw_providers) > 0);

$sanitized = array();
$budget = array();
$monthly_cap = 0.0;
$total_spent = 0.0;
$provider_spend = array();

if ($service_ok) {
    $sanitized = $service->getRuntimeConfigSanitized();
    if (!is_array($sanitized)) {
        $sanitized = array();
    }
    $budget = $service->getBudgetSnapshot();
    if (!is_array($budget)) {
        $budget = array();
    }
    $monthly_cap = isset($budget['monthly_budget_cap_usd']) ? (float) $budget['monthly_budget_cap_usd'] : 0.0;
    $total_spent = isset($budget['total_usd']) ? (float) $budget['total_usd'] : 0.0;
    $provider_spend = (isset($budget['provider_spend']) && is_array($budget['provider_spend'])) ? $budget['provider_spend'] : array();
} else {
    $monthly_cap = isset($global_api['monthly_budget_cap_usd']) ? (float) $global_api['monthly_budget_cap_usd'] : 0.0;
    $total_spent = 0.0;
    $provider_spend = array();
}

$base_case_text = 'No API providers configured. Running in pure human live chat mode (base case). Messages to agents are disabled.';
$service_unavailable_text = 'API Provider Chain Service unavailable. Cannot display live spend data.';

$dash = "\xe2\x80\x94";
$offline_status = 'Offline / Not Configured';
$key_present_cell = "\xf0\x9f\x94\x91 Present";

if (!$has_provider_rows) {
    echo '<p class="api-dash-notice">' . htmlspecialchars($base_case_text, ENT_QUOTES, 'UTF-8') . '</p>';
} elseif (!$service_ok) {
    echo '<p class="api-dash-notice">' . htmlspecialchars($service_unavailable_text, ENT_QUOTES, 'UTF-8') . '</p>';
}

echo '<p class="api-dash-summary">';
echo htmlspecialchars('Monthly budget cap (USD)', ENT_QUOTES, 'UTF-8') . ': ';
echo htmlspecialchars(number_format($monthly_cap, 2, '.', ''), ENT_QUOTES, 'UTF-8') . ' &mdash; ';
echo htmlspecialchars('Total spent this month (USD)', ENT_QUOTES, 'UTF-8') . ': ';
echo htmlspecialchars(number_format($total_spent, 4, '.', ''), ENT_QUOTES, 'UTF-8');
echo '</p>';

echo '<p><a href="' . htmlspecialchars($api_dash_base . '/channels', ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars('Back to channel feed', ENT_QUOTES, 'UTF-8') . '</a></p>';

if (!$has_provider_rows) {
    return;
}

echo '<table class="table"><thead><tr>';
echo '<th>' . htmlspecialchars('Provider', ENT_QUOTES, 'UTF-8') . '</th>';
echo '<th>' . htmlspecialchars('Display name', ENT_QUOTES, 'UTF-8') . '</th>';
echo '<th>' . htmlspecialchars('Enabled', ENT_QUOTES, 'UTF-8') . '</th>';
echo '<th>' . htmlspecialchars('API key', ENT_QUOTES, 'UTF-8') . '</th>';
echo '<th>' . htmlspecialchars('Budget', ENT_QUOTES, 'UTF-8') . '</th>';
echo '<th>' . htmlspecialchars('Spent this month', ENT_QUOTES, 'UTF-8') . '</th>';
echo '<th>' . htmlspecialchars('Remaining', ENT_QUOTES, 'UTF-8') . '</th>';
echo '<th>' . htmlspecialchars('Status', ENT_QUOTES, 'UTF-8') . '</th>';
echo '</tr></thead><tbody>';

ksort($raw_providers);

foreach ($raw_providers as $pkey => $raw_cfg) {
    if (!is_array($raw_cfg)) {
        continue;
    }
    $pkey_s = (string) $pkey;
    $san_cfg = ($service_ok && isset($sanitized['providers'][$pkey_s]) && is_array($sanitized['providers'][$pkey_s]))
        ? $sanitized['providers'][$pkey_s]
        : array();
    if ($service_ok && $san_cfg === array() && isset($sanitized['providers'][$pkey]) && is_array($sanitized['providers'][$pkey])) {
        $san_cfg = $sanitized['providers'][$pkey];
    }

    $has_key = false;
    if (isset($raw_cfg['key']) && trim((string) $raw_cfg['key']) !== '') {
        $has_key = true;
    } elseif (isset($raw_cfg['api_key']) && trim((string) $raw_cfg['api_key']) !== '') {
        $has_key = true;
    }

    $disp = '';
    if (isset($raw_cfg['display_name']) && trim((string) $raw_cfg['display_name']) !== '') {
        $disp = trim((string) $raw_cfg['display_name']);
    } elseif (isset($raw_cfg['name']) && trim((string) $raw_cfg['name']) !== '') {
        $disp = trim((string) $raw_cfg['name']);
    } elseif (isset($san_cfg['display_name']) && trim((string) $san_cfg['display_name']) !== '') {
        $disp = trim((string) $san_cfg['display_name']);
    } elseif (isset($san_cfg['name']) && trim((string) $san_cfg['name']) !== '') {
        $disp = trim((string) $san_cfg['name']);
    } else {
        $disp = $pkey_s;
    }

    $enabled = false;
    if ($service_ok && array_key_exists('enabled', $san_cfg)) {
        $enabled = (bool) $san_cfg['enabled'];
    } elseif (array_key_exists('enabled', $raw_cfg)) {
        $enabled = (bool) $raw_cfg['enabled'];
    }

    $key_cell = $has_key ? $key_present_cell : $dash;

    $bud = isset($raw_cfg['budget']) ? (float) $raw_cfg['budget'] : 0.0;
    if ($service_ok && isset($san_cfg['budget'])) {
        $bud = (float) $san_cfg['budget'];
    }
    $pkey_lower = strtolower($pkey_s);
    $spent = 0.0;
    if (isset($provider_spend[$pkey_s])) {
        $spent = (float) $provider_spend[$pkey_s];
    } elseif (isset($provider_spend[$pkey_lower])) {
        $spent = (float) $provider_spend[$pkey_lower];
    }
    $remaining = $bud - $spent;

    $status_class = 'status-green';
    $status_text = 'OK';
    if (!$has_key) {
        $status_class = 'status-muted';
        $status_text = $offline_status;
    } else {
        if ($remaining < 2.0) {
            $status_class = 'status-red';
            $status_text = 'Critical';
        } elseif ($remaining < 5.0) {
            $status_class = 'status-yellow';
            $status_text = 'Low';
        }
    }

    $budget_cell = number_format($bud, 2, '.', '');
    $spent_cell = number_format($spent, 4, '.', '');
    $remain_cell = number_format($remaining, 4, '.', '');

    $en_cell = $enabled ? "\xe2\x9c\x85" : "\xe2\x9d\x8c";

    echo '<tr>';
    echo '<td>' . htmlspecialchars($pkey_s, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($disp, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($en_cell, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($key_cell, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($budget_cell, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($spent_cell, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($remain_cell, ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td><span class="' . htmlspecialchars($status_class, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($status_text, ENT_QUOTES, 'UTF-8') . '</span></td>';
    echo '</tr>';
}

echo '</tbody></table>';

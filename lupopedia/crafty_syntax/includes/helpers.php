<?php
function lupo_crafty_h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function lupo_crafty_base_url() {
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    return $base . '/crafty_syntax/';
}

function lupo_crafty_url($page, $params = []) {
    $params['page'] = $page;
    return lupo_crafty_base_url() . 'index.php?' . http_build_query($params);
}

function lupo_crafty_format_ymdhis($value) {
    if (empty($value)) {
        return '—';
    }
    $value = (string)$value;
    if (strlen($value) !== 14) {
        return lupo_crafty_h($value);
    }
    return substr($value, 0, 4) . '-' . substr($value, 4, 2) . '-' . substr($value, 6, 2) .
        ' ' . substr($value, 8, 2) . ':' . substr($value, 10, 2) . ':' . substr($value, 12, 2) . ' UTC';
}

function lupo_crafty_nav_sections($operator, $is_admin) {
    return [
        'General' => [
            ['label' => 'Documentation', 'url' => 'https://lupopedia.com/salessyntax_docs/howto.php', 'external' => true],
            ['label' => 'Help', 'url' => lupo_crafty_url('settings', ['tab' => 'help'])],
        ],
        'CRM Tools' => [
            ['label' => 'Leads Database', 'url' => lupo_crafty_url('modules', ['tab' => 'leads'])],
            ['label' => 'Email Message Database', 'url' => lupo_crafty_url('modules', ['tab' => 'emails'])],
            ['label' => 'Proactive Leads', 'url' => lupo_crafty_url('modules', ['tab' => 'autoleads'])],
            ['label' => 'Import Leads', 'url' => lupo_crafty_url('modules', ['tab' => 'import'])],
        ],
        'AI Agents Tools' => [
            ['label' => 'Agents', 'url' => lupo_crafty_url('modules', ['tab' => 'agents'])],
            ['label' => 'Channels', 'url' => lupo_crafty_url('modules', ['tab' => 'channels'])],
        ],
        'Live Help' => [
            ['label' => 'Operator Overview', 'url' => lupo_crafty_url('operator')],
            ['label' => 'Quick Replies', 'url' => lupo_crafty_url('modules', ['tab' => 'quick'])],
            ['label' => 'Auto Invites', 'url' => lupo_crafty_url('modules', ['tab' => 'autoinvite'])],
            ['label' => 'Emotion Icons', 'url' => lupo_crafty_url('settings', ['tab' => 'smilies'])],
            ['label' => 'Layer Images', 'url' => lupo_crafty_url('settings', ['tab' => 'layers'])],
        ],
        'Operators' => [
            ['label' => 'Edit Your Account', 'url' => lupo_crafty_url('operator', ['action' => 'profile'])],
            $is_admin ? ['label' => 'Create/Edit Operators', 'url' => lupo_crafty_url('operator', ['action' => 'manage'])] : null,
        ],
        'Departments' => [
            ['label' => 'Department HTML Code', 'url' => lupo_crafty_url('departments', ['tab' => 'embed'])],
            $is_admin ? ['label' => 'Create/Edit Departments', 'url' => lupo_crafty_url('departments')] : null,
        ],
        'Analytics' => [
            ['label' => 'Visitor Analytics', 'url' => lupo_crafty_url('analytics', ['tab' => 'visits'])],
            ['label' => 'Messages', 'url' => lupo_crafty_url('analytics', ['tab' => 'messages'])],
            ['label' => 'Transcripts', 'url' => lupo_crafty_url('analytics', ['tab' => 'transcripts'])],
            ['label' => 'Referers', 'url' => lupo_crafty_url('analytics', ['tab' => 'referers'])],
        ],
        'Modules' => [
            ['label' => 'Questions & Answers', 'url' => lupo_crafty_url('modules', ['tab' => 'qa'])],
        ],
    ];
}

function lupo_crafty_render($view, $data = []) {
    extract($data, EXTR_SKIP);
    include __DIR__ . '/../views/' . $view . '.php';
}

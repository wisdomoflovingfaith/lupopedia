<?php
require_once __DIR__ . '/controllers/operator.php';
require_once __DIR__ . '/controllers/visitors.php';
require_once __DIR__ . '/controllers/chats.php';
require_once __DIR__ . '/controllers/departments.php';
require_once __DIR__ . '/controllers/modules.php';
require_once __DIR__ . '/controllers/transcripts.php';
require_once __DIR__ . '/controllers/analytics.php';
require_once __DIR__ . '/controllers/settings.php';

function lupo_crafty_route($page, $action) {
    $page = $page ?: 'operator';
    $action = $action ?: 'overview';

    switch ($page) {
        case 'operator':
            lupo_crafty_operator_overview();
            break;
        case 'visitors':
            lupo_crafty_visitors_index();
            break;
        case 'chats':
            lupo_crafty_chats_index();
            break;
        case 'escalate':
            lupo_crafty_escalate_chat();
            break;
        case 'departments':
            lupo_crafty_departments_index();
            break;
        case 'modules':
            lupo_crafty_modules_index();
            break;
        case 'transcripts':
            lupo_crafty_transcripts_index();
            break;
        case 'analytics':
            lupo_crafty_analytics_index();
            break;
        case 'settings':
            lupo_crafty_settings_index();
            break;
        default:
            lupo_crafty_operator_overview();
            break;
    }
}

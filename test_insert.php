<?php
require_once __DIR__ . '/lupopedia-config.php';

function test_insert()
{
    try {
        $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));

        $stmt = $pdo->query("
        INSERT INTO lupo_crafty_syntax_auto_invite (
            crafty_syntax_auto_invite_id,
            is_offline,
            is_active,
            department_id,
            message,
            page_url,
            visits,
            referrer_url,
            invite_type,
            trigger_seconds,
            operator_user_id,
            show_socialpane,
            exclude_mobile,
            only_mobile,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        )
        SELECT
            a.idnum AS crafty_syntax_auto_invite_id,
        
            a.offline AS is_offline,
        
            CASE WHEN a.isactive = 'Y' THEN 1 ELSE 0 END AS is_active,
        
            a.department AS department_id,
            a.message,
            a.page AS page_url,
            a.visits,
            a.referer AS referrer_url,
            a.typeof AS invite_type,
            a.seconds AS trigger_seconds,
            (10000 + a.user_id) AS operator_user_id,
        
            CASE WHEN a.socialpane = 'Y' THEN 1 ELSE 0 END AS show_socialpane,
            CASE WHEN a.excludemobile = 'Y' THEN 1 ELSE 0 END AS exclude_mobile,
            CASE WHEN a.onlymobile = 'Y' THEN 1 ELSE 0 END AS only_mobile,
        
            20250101000000 AS created_ymdhis,
            20250101000000 AS updated_ymdhis,
            0 AS is_deleted,
            NULL AS deleted_ymdhis
        
        FROM livehelp_autoinvite AS a
        ");
        echo "Insert successful!\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

test_insert();

-- 2026-01-20 Doctrine Corrections (Version 4.1.14)
-- - Fixed timestamp doctrine violations
-- - Corrected lupo_crm_lead_messages column mismatch
-- - Resolved livehelp_emailque migration gap
-- - Corrected typos ("remnoved" → "removed")
-- - No schema changes made
--
/* 
======================================================================
   MIGRATION OVERVIEW
   Upgrades Crafty Syntax Live Help versions 3.6.1 through 3.7.5 to 4.0.3.

   There were no database changes between 3.6.1 and 3.7.5, so all versions
   run this same migration to reach 4.0.0. EVERY TABLE CHANGED between
   3.7.5 and 4.0.0 — this release is a complete rebuild of the system
   after a 15-year hiatus from development.

   Version 4.0.3 is a full architectural rewrite. Every table has been
   replaced, modernized, or migrated into the new Lupopedia domain model.
   This migration touches the entire system and redefines the core data
   structures for long-term scalability, clarity, and maintainability.

   The migration process is intentionally explicit and deterministic.
   Each legacy table is refactored through a dedicated mapping file,
   transformed into its new schema, and written into the Lupopedia
   structure using doctrine-safe import rules.
======================================================================


----------------------------------------------------------------------
    SCOPE
    Total tables present during migration: 145

      • 34 legacy Crafty Syntax tables (3.7.5)
      • 111 core Lupopedia tables (4.0.3)
      • 8 new Crafty Syntax module tables included in this migration

    After successful migration, all 34 legacy tables are dropped,
    bringing the final schema count to 111 tables.

    This preserves the historical table count while clarifying that
    not all legacy tables map directly into the core schema — some
    migrate into feature modules, and others are retired entirely.

    The migration is designed to be repeatable, auditable, and safe.
    No foreign keys, no triggers, no cascading deletes, and all
    timestamps follow the Lupopedia UTC YYYYMMDDHHIISS doctrine.
----------------------------------------------------------------------

 by LUPOPEDIA LLC 2026 - CAPTAIN WOLFIE 
   ====================================================================== */

 
-- ======================================================================
-- MIGRATION: Convert livehelp_autoinvite to crafty_auto_invite 

ALTER TABLE livehelp_autoinvite
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_autoinvite
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

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
    a.user_id AS operator_user_id,

    CASE WHEN a.socialpane = 'Y' THEN 1 ELSE 0 END AS show_socialpane,
    CASE WHEN a.excludemobile = 'Y' THEN 1 ELSE 0 END AS exclude_mobile,
    CASE WHEN a.onlymobile = 'Y' THEN 1 ELSE 0 END AS only_mobile,

    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis

FROM livehelp_autoinvite AS a;

-- ======================================================================
-- MIGRATION: livehelp_channels - Upgrade to new schema
 
ALTER TABLE livehelp_channels
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_channels
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_config               → JSON inserted into modules.id = 1


ALTER TABLE livehelp_config
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_config
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

UPDATE lupo_modules m
JOIN livehelp_config c ON 1=1
SET m.config_json = JSON_OBJECT(
    'version', c.version,
    'site_title', c.site_title,
    'use_flush', c.use_flush,
    'membernum', c.membernum,
    'show_typing', c.show_typing,
    'webpath', c.webpath,
    's_webpath', c.s_webpath,
    'speaklanguage', c.speaklanguage,
    'scratch_space', c.scratch_space,
    'admin_refresh', c.admin_refresh,
    'maxexe', c.maxexe,
    'refreshrate', c.refreshrate,
    'chatmode', c.chatmode,
    'adminsession', c.adminsession,
    'ignoreips', c.ignoreips,
    'directoryid', c.directoryid,
    'tracking', c.tracking,
    'colorscheme', c.colorscheme,
    'matchip', c.matchip,
    'gethostnames', c.gethostnames,
    'maxrecords', c.maxrecords,
    'maxreferers', c.maxreferers,
    'maxvisits', c.maxvisits,
    'maxmonths', c.maxmonths,
    'maxoldhits', c.maxoldhits,
    'showgames', c.showgames,
    'showsearch', c.showsearch,
    'showdirectory', c.showdirectory,
    'usertracking', c.usertracking,
    'resetbutton', c.resetbutton,
    'keywordtrack', c.keywordtrack,
    'reftracking', c.reftracking,
    'topkeywords', c.topkeywords,
    'everythingelse', c.everythingelse,
    'rememberusers', c.rememberusers,
    'smtp_host', c.smtp_host,
    'smtp_username', c.smtp_username,
    'smtp_password', c.smtp_password,
    'owner_email', c.owner_email,
    'topframeheight', c.topframeheight,
    'topbackground', c.topbackground,
    'usecookies', c.usecookies,
    'smtp_portnum', c.smtp_portnum,
    'showoperator', c.showoperator,
    'chatcolors', c.chatcolors,
    'floatxy', c.floatxy,
    'sessiontimeout', c.sessiontimeout,
    'theme', c.theme,
    'operatorstimeout', c.operatorstimeout,
    'operatorssessionout', c.operatorssessionout,
    'maxrequests', c.maxrequests,
    'ignoreagent', c.ignoreagent
)
WHERE m.module_id = 1;


-- ======================================================================
-- livehelp_departments               → departments

ALTER TABLE livehelp_departments
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_departments
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO lupo_departments (
    department_id,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    settings_json,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    recno AS department_id,
    website AS federation_node_id,
    nameof AS name,
    NULL AS description,
    'crafty' AS department_type,
    1 AS default_actor_id,
    NULL AS settings_json,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS created_ymdhis,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_departments;


-- ======================================================================
-- livehelp_emailque               → NOT migrated in this script (target lupo_crm_lead_message_sends out of scope)

ALTER TABLE livehelp_emailque
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_emailque
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_emails               → lupo_crm_lead_messages

ALTER TABLE livehelp_emails
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_emails
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO lupo_crm_lead_messages (
    crm_lead_message_id,
    lead_id,
    from_email,
    subject,
    body_text,
    notes,
    actor_id,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    id AS crm_lead_message_id,
    1 AS lead_id,  -- all Crafty Syntax emails belong to the broadcast lead
    fromemail AS from_email,
    subject,
    bodyof AS body_text,
    notes,
    NULL AS actor_id,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS created_ymdhis,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS updated_ymdhis
FROM livehelp_emails;

-- ======================================================================
-- livehelp_identity_daily               → removed in Lupopedia 4.0.0

ALTER TABLE livehelp_identity_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_identity_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_identity_monthly              → removed in Lupopedia 4.0.0

ALTER TABLE livehelp_identity_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_identity_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

 
-- ======================================================================
-- livehelp_keywords_daily              → removed in Lupopedia 4.0.0

ALTER TABLE livehelp_keywords_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_keywords_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_keywords_monthly              → removed in Lupopedia 4.0.0

ALTER TABLE livehelp_keywords_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_keywords_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_layerinvites              → crafty_syntax_layer_invites

ALTER TABLE livehelp_layerinvites
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_layerinvites
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO lupo_crafty_syntax_layer_invites (
    layer_name,
    image_name,
    image_map,
    department_name,
    user_id,
    is_active,
    display_count,
    click_count,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    name AS layer_name,
    imagename AS image_name,
    imagemap AS image_map,
    department AS department_name,
    `user` AS user_id,
    1 AS is_active,
    0 AS display_count,
    0 AS click_count,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS created_ymdhis,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_layerinvites;

-- ======================================================================
-- livehelp_leads              →  lupo_crm_leads


ALTER TABLE livehelp_leads
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_leads
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO  lupo_crm_leads
 (
    crm_lead_id,
    email,
    phone,
    first_name,
    last_name,
    source,
    status,
    lead_score,
    assigned_to,
    lead_data,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    id AS crm_lead_id,
    email,
    phone,
    firstname AS first_name,
    lastname AS last_name,
    source,
    status,
    0 AS lead_score,
    NULL AS assigned_to,
    data AS lead_data,
    date_entered AS created_ymdhis,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_leads;

-- ======================================================================
-- livehelp_leavemessage -> crafty_syntax_leave_message

ALTER TABLE livehelp_leavemessage
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_leavemessage
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


INSERT INTO lupo_crafty_syntax_leave_message (
    crafty_syntax_leave_message_id,
    department_id,
    email,
    phone,
    name,
    subject,
    message,
    priority,
    session_data,
    form_data,
    ip_address,
    user_agent,
    status,
    assigned_to,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    id AS leave_message_id,
    department AS department_id,
    email,
    NULL AS phone,
    NULL AS name,
    subject,
    NULL AS message,
    2 AS priority,
    sessiondata AS session_data,
    deliminated AS form_data,
    NULL AS ip_address,
    NULL AS user_agent,
    'new' AS status,
    NULL AS assigned_to,
    dateof AS created_ymdhis,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_leavemessage;

-- ======================================================================
-- livehelp_messages -> dialog_messages but crafty did not store any of the messages after the chat ended . 

ALTER TABLE livehelp_messages
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_messages
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_modules -> modules

ALTER TABLE livehelp_modules
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  ALTER TABLE livehelp_modules
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
 
-- ======================================================================
-- livehelp_modules_dep -> crafty_chat_mod_departments

 
ALTER TABLE livehelp_modules_dep
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  ALTER TABLE livehelp_modules_dep
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
 
-- ======================================================================
-- livehelp_operator_channels -> channels

ALTER TABLE livehelp_operator_channels
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_channels
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_operator_departments -> actor_departments

ALTER TABLE livehelp_operator_departments
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_departments
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO lupo_actor_departments (
    actor_department_id,
    actor_id,
    department_id,
    title,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    recno AS actor_department_id,
    user_id AS actor_id,
    department AS department_id,
    extra AS title,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS created_ymdhis,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_operator_departments;


-- ======================================================================
-- livehelp_operator_history -> audit_log

ALTER TABLE livehelp_operator_history
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_history
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO lupo_audit_log (
    audit_log_id,
    channel_id,
    entity_type,
    entity_id,
    event_type,
    table_name,
    table_id,
    payload_json,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    id AS audit_log_id,
    channel AS channel_id,
    'actor' AS entity_type,
    opid AS entity_id,
    action AS event_type,
    CASE 
        WHEN transcriptid > 0 THEN 'lupo_dialog_threads'
        ELSE NULL
    END AS table_name,
    CASE 
        WHEN transcriptid > 0 THEN transcriptid
        ELSE NULL
    END AS table_id,
    JSON_OBJECT(
        'sessionid', sessionid,
        'totaltime', totaltime
    ) AS payload_json,
    dateof AS created_ymdhis,
    dateof AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_operator_history;



-- ======================================================================
ALTER TABLE livehelp_paths_firsts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_firsts
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';



-- NOTE: visit_recno and exit_recno are recnos from livehelp_visits_monthly/livehelp_visits_daily
-- These must be resolved to pageurl slugs (web-facing URLs), not treated as content IDs.
-- Since lupo_analytics_paths_daily expects content_id (BIGINT), we store 0 here.
-- The pageurl slugs are resolved via JOIN below, but application logic must handle
-- slug-to-content_id resolution since these are semantic identifiers, not filesystem paths.
INSERT INTO lupo_analytics_paths_daily (
    visit_content_id,
    exit_content_id,
    date_ymd,
    visits,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    0 AS visit_content_id,  -- visit_recno resolved to slug via visit_slug, stored as 0 (slug lookup required)
    0 AS exit_content_id,   -- exit_recno resolved to slug via exit_slug, stored as 0 (slug lookup required)
    CONCAT(dateof, '01') AS date_ymd,
    visits,
    CONCAT(dateof, '01000000') AS created_ymdhis,
    CONCAT(dateof, '01000000') AS updated_ymdhis
FROM livehelp_paths_firsts p
LEFT JOIN livehelp_visits_monthly v_visit ON v_visit.recno = p.visit_recno
LEFT JOIN livehelp_visits_monthly v_exit ON v_exit.recno = p.exit_recno
LEFT JOIN livehelp_visits_daily d_visit ON d_visit.recno = p.visit_recno
LEFT JOIN livehelp_visits_daily d_exit ON d_exit.recno = p.exit_recno;


-- ======================================================================
ALTER TABLE livehelp_paths_firsts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_firsts
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- NOTE: visit_recno and exit_recno are recnos from livehelp_visits_monthly/livehelp_visits_daily
-- These must be resolved to pageurl slugs (web-facing URLs), not treated as content IDs.
-- Since lupo_analytics_paths_monthly expects content_id (BIGINT), we store 0 here.
-- The pageurl slugs are resolved via JOIN below, but application logic must handle
-- slug-to-content_id resolution since these are semantic identifiers, not filesystem paths.
INSERT INTO lupo_analytics_paths_monthly (
    visit_content_id,
    exit_content_id,
    month_ym,
    visits,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    0 AS visit_content_id,  -- visit_recno resolved to slug via visit_slug, stored as 0 (slug lookup required)
    0 AS exit_content_id,   -- exit_recno resolved to slug via exit_slug, stored as 0 (slug lookup required)
    dateof AS month_ym,
    visits,
    CONCAT(dateof, '01000000') AS created_ymdhis,
    CONCAT(dateof, '01000000') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_paths_firsts p
LEFT JOIN livehelp_visits_monthly v_visit ON v_visit.recno = p.visit_recno
LEFT JOIN livehelp_visits_monthly v_exit ON v_exit.recno = p.exit_recno
LEFT JOIN livehelp_visits_daily d_visit ON d_visit.recno = p.visit_recno
LEFT JOIN livehelp_visits_daily d_exit ON d_exit.recno = p.exit_recno;


-- ======================================================================
ALTER TABLE livehelp_paths_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_analytics_paths_monthly;

-- NOTE: visit_recno and exit_recno are recnos from livehelp_visits_monthly/livehelp_visits_daily
-- These must be resolved to pageurl slugs (web-facing URLs), not treated as content IDs.
-- Crafty Syntax stores the public-facing URL exactly as seen in the browser address bar.
-- These values are NOT filesystem paths and must be treated as semantic slugs or identifiers.
-- Since lupo_analytics_paths_monthly expects content_id (BIGINT), we store 0 here.
-- The pageurl slugs are resolved via JOIN below, but application logic must handle
-- slug-to-content_id resolution since these are semantic identifiers, not filesystem paths.
INSERT INTO lupo_analytics_paths_monthly (
    visit_content_id,
    exit_content_id,
    month_ym,
    visits,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    0 AS visit_content_id,  -- visit_recno resolved to pageurl slug, stored as 0 (slug lookup required)
    0 AS exit_content_id,   -- exit_recno resolved to pageurl slug, stored as 0 (slug lookup required)
    p.dateof AS month_ym,
    SUM(p.visits) AS visits,
    CONCAT(p.dateof, '01000000') AS created_ymdhis,
    CONCAT(p.dateof, '01000000') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_paths_monthly p
LEFT JOIN livehelp_visits_monthly v_visit ON v_visit.recno = p.visit_recno AND v_visit.dateof = p.dateof
LEFT JOIN livehelp_visits_monthly v_exit ON v_exit.recno = p.exit_recno AND v_exit.dateof = p.dateof
LEFT JOIN livehelp_visits_daily d_visit ON d_visit.recno = p.visit_recno AND LEFT(d_visit.dateof, 6) = p.dateof
LEFT JOIN livehelp_visits_daily d_exit ON d_exit.recno = p.exit_recno AND LEFT(d_exit.dateof, 6) = p.dateof
GROUP BY
    p.dateof,
    COALESCE(v_visit.pageurl, d_visit.pageurl, ''),  -- Group by resolved slug, not recno
    COALESCE(v_exit.pageurl, d_exit.pageurl, '');    -- Group by resolved slug, not recno


-- ======================================================================
ALTER TABLE livehelp_qa
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_qa
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';



TRUNCATE lupo_truth_questions;

INSERT INTO lupo_truth_questions (
    truth_question_id,
    truth_question_parent_id,
    actor_id,
    qtype,
    status,
    sort_num,
    slug,
    question_text,
    format,
    format_override,
    view_count,
    likes_count,
    shares_count,
    answer_count,
    last_activity_ymdhis,
    is_featured,
    is_verified,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    recno AS truth_question_id,
    CASE WHEN parent = 0 THEN NULL ELSE parent END AS truth_question_parent_id,
    0 AS actor_id,
    'unknown' AS qtype,
    'active' AS status,
    ordernum AS sort_num,
    CONCAT('qa-', recno) AS slug,
    question AS question_text,
    'text' AS format,
    NULL AS format_override,
    0 AS view_count,
    0 AS likes_count,
    0 AS shares_count,
    0 AS answer_count,
    NULL AS last_activity_ymdhis,
    0 AS is_featured,
    0 AS is_verified,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_qa
WHERE typeof = 'question';


TRUNCATE lupo_truth_answers;

INSERT INTO lupo_truth_answers (
    truth_question_id,
    actor_id,
    answer_text,
    confidence_score,
    evidence_score,
    contradiction_flag,
    likes_count,
    shares_count,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    parent AS truth_question_id,
    0 AS actor_id,
    question AS answer_text,
    0.00 AS confidence_score,
    0.00 AS evidence_score,
    0 AS contradiction_flag,
    0 AS likes_count,
    0 AS shares_count,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_qa
WHERE typeof = 'answer';

INSERT INTO lupo_collections (
    federations_node_id,
    user_id,
    group_id,
    name,
    slug,
    color,
    description,
    sort_order,
    properties,
    published_ymdhis,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
VALUES (
    1,
    NULL,
    NULL,
    'Site Navigation',
    'site-navigation',
    '666666',
    'Auto-generated navigation collection from Crafty Syntax',
    0,
    NULL,
    NULL,
    20250101000000,
    20250101000000,
    0,
    NULL
);

INSERT INTO lupo_collection_tabs (
    collection_tab_parent_id,
    collection_id,
    federations_node_id,
    group_id,
    user_id,
    sort_order,
    name,
    slug,
    color,
    description,
    is_hidden,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis
)
SELECT
    NULL AS collection_tab_parent_id,
    1 AS collection_id,
    1 AS federations_node_id,
    NULL AS group_id,
    NULL AS user_id,
    ordernum AS sort_order,
    question AS name,
    LOWER(REPLACE(question, ' ', '-')) AS slug,
    '4caf50' AS color,
    NULL AS description,
    0 AS is_hidden,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    1 AS is_active,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_qa
WHERE typeof = 'folder' AND parent = 0;


INSERT INTO lupo_collection_tabs (
    collection_tab_parent_id,
    collection_id,
    federations_node_id,
    group_id,
    user_id,
    sort_order,
    name,
    slug,
    color,
    description,
    is_hidden,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis
)
SELECT
    parent_tab.collection_tab_id AS collection_tab_parent_id,
    1 AS collection_id,
    1 AS federations_node_id,
    NULL AS group_id,
    NULL AS user_id,
    child.ordernum AS sort_order,
    child.question AS name,
    LOWER(REPLACE(child.question, ' ', '-')) AS slug,
    '4caf50' AS color,
    NULL AS description,
    0 AS is_hidden,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    1 AS is_active,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_qa child
JOIN livehelp_qa parent
    ON parent.recno = child.parent
JOIN lupo_collection_tabs parent_tab
    ON parent_tab.slug = LOWER(REPLACE(parent.question, ' ', '-'))
WHERE child.typeof = 'folder' AND child.parent != 0;

 
-- ======================================================================

ALTER TABLE livehelp_questions
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_questions
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


INSERT INTO lupo_crafty_syntax_chat_questions (
    crafty_syntax_chat_question_id,
    department_id,
    sort_order,
    headertext,
    field_type,
    options,
    flags,
    module_name,
    is_required,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    id AS crafty_syntax_chat_question_id,
    department AS department_id,
    ordering AS sort_order,
    headertext,
    fieldtype AS field_type,
    options,
    flags,
    module AS module_name,
    CASE WHEN required = 'Y' THEN 1 ELSE 0 END AS is_required,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_questions;


-- ======================================================================

ALTER TABLE livehelp_quick
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_quick
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO lupo_actor_reply_templates (
    actor_reply_template_id,
    actor_id,
    template_key,
    template_text,
    usage_context,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    id AS actor_reply_template_id,
    `user` AS actor_id,
    name AS template_key,
    message AS template_text,
    typeof AS usage_context,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_quick;


-- ======================================================================
 
ALTER TABLE livehelp_referers_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 
ALTER TABLE livehelp_referers_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- NOTE: livehelp_referers_daily.pageurl contains the web-facing URL slug exactly as seen in the browser.
-- These values are NOT filesystem paths and must be treated as semantic slugs or identifiers.
-- Never attempt to resolve them to disk or treat them as real file locations.
-- The pageurl is stored in url_path, and content_id is set to 0 (slug lookup required).
INSERT INTO lupo_analytics_referers_daily (
    content_id,
    url_path,
    referer_content_id,
    referer_url_path,
    parent_id,
    level,
    group_id,
    date_ymd,
    visits,
    direct_visits,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    0 AS content_id,  -- pageurl is a slug, not a content ID (slug lookup required)
    r.pageurl AS url_path,  -- Store the web-facing URL slug as-is
    0 AS referer_content_id,  -- Referer tracking not in this table
    '' AS referer_url_path,
    r.parentrec AS parent_id,
    r.level,
    0 AS group_id,
    r.dateof AS date_ymd,
    r.levelvisits + r.directvisits AS visits,
    r.directvisits AS direct_visits,
    CONCAT(r.dateof, '000000') AS created_ymdhis,
    CONCAT(r.dateof, '000000') AS updated_ymdhis
FROM livehelp_referers_daily r
WHERE r.pageurl != '' AND r.pageurl != '0';

-- ======================================================================
  
ALTER TABLE livehelp_referers_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 
ALTER TABLE livehelp_referers_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
 
 
-- ======================================================================
  
ALTER TABLE livehelp_sessions
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 
ALTER TABLE livehelp_sessions
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
 
 -- ======================================================================
  
ALTER TABLE livehelp_smilies
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 
ALTER TABLE livehelp_smilies
COMMENT = 'LEGACY ARCHIVE TABLE — no longer used. Crafty Syntax originally stored emoji metadata here, but Lupopedia replaces this system entirely. Emoji and inline images are now inserted directly into dialog text using the token format :|:name|folder|filename:|:. The renderer reads icons from the chat_smilies/ directory (and its subfolders) and replaces the token with the corresponding image at display time. This table is preserved only for historical reference; no data is imported and no new rows will be created.';
  
-- ======================================================================
-- livehelp_transcripts               → lupo_dialog_message_bodies


ALTER TABLE livehelp_transcripts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_transcripts
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


INSERT INTO lupo_dialog_threads (
    dialog_thread_id,
    federation_node_id,
    channel_id,
    created_by_actor_id,
    summary_text,
    metadata_json,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    recno AS dialog_thread_id,
    1 AS federation_node_id,
    1 AS channel_id,
    1 AS created_by_actor_id,
    CONCAT(recno, ' import from crafty syntax') AS summary_text,
    JSON_OBJECT(
        'legacy_sessionid', sessionid,
        'legacy_department', department,
        'legacy_email', email,
        'legacy_operators', operators,
        'legacy_sessiondata', sessiondata
    ) AS metadata_json,
    starttime AS created_ymdhis,
    endtime AS updated_ymdhis
FROM livehelp_transcripts;



INSERT INTO lupo_dialog_messages
(
    dialog_message_id,
    dialog_thread_id,
    channel_id,
    from_actor_id,
    to_actor_id,
    message_text,
    message_type,
    metadata_json,
    mood_rgb,
    weight,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    recno AS dialog_message_id,
    recno AS dialog_thread_id,
    1 AS channel_id,
    1 AS from_actor_id,
    1 AS to_actor_id,
    CONCAT(recno, ' import from crafty syntax') AS message_text,
    'text' AS message_type,
    JSON_OBJECT(
        'legacy_sessionid', sessionid,
        'legacy_department', department,
        'legacy_email', email,
        'legacy_operators', operators,
        'legacy_sessiondata', sessiondata
    ) AS metadata_json,
    '666666' AS mood_rgb,
    1 AS weight,
    starttime AS created_ymdhis,
    endtime AS updated_ymdhis
FROM livehelp_transcripts;


INSERT INTO lupo_dialog_message_bodies (
    dialog_message_body_id,
    dialog_message_id,
    full_text,
    metadata_json,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    recno AS dialog_message_body_id,
    recno AS dialog_message_id,
    transcript AS full_text,
    JSON_OBJECT(
        'legacy_sessionid', sessionid,
        'legacy_department', department,
        'legacy_email', email,
        'legacy_operators', operators,
        'legacy_sessiondata', sessiondata
    ) AS metadata_json,
    starttime AS created_ymdhis,
    endtime AS updated_ymdhis
FROM livehelp_transcripts;



-- ======================================================================
-- livehelp_users               → lupo_auth_users
 
ALTER TABLE livehelp_users
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_users
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


INSERT INTO lupo_auth_users (
    auth_user_id,
    username,
    display_name,
    email,
    password_hash,
    auth_provider,
    provider_id,
    profile_image_url,
    last_login_ymdhis,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted
)
SELECT
    user_id AS auth_user_id,
    username,
    displayname AS display_name,
    NULLIF(email, '') AS email,
    password AS password_hash,
    auth_provider,
    provider_id,
    NULL AS profile_image_url,
    last_login_at AS last_login_ymdhis,
    COALESCE(showedup, lastaction, 0) AS created_ymdhis,
    lastaction AS updated_ymdhis,
    1 AS is_active,
    0 AS is_deleted
FROM livehelp_users;


INSERT INTO lupo_actor_properties (
    actor_id,
    actor_type,
    property_key,
    property_value,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    user_id AS actor_id,
    'human' AS actor_type,
    'legacy_profile' AS property_key,
    JSON_OBJECT(
        'isoperator', isoperator,
        'isadmin', isadmin,
        'department', department,
        'identity', identity,
        'status', status,
        'visits', visits,
        'ipaddress', ipaddress,
        'hostname', hostname,
        'useragent', useragent,
        'sessionid', sessionid,
        'sessiondata', sessiondata,
        'greeting', greeting,
        'cellphone', cellphone,
        'cookieid', cookieid,
        'ismobile', ismobile,
        'alertchat', alertchat,
        'alerttyping', alerttyping,
        'alertinsite', alertinsite
    ) AS property_value,
    COALESCE(showedup, lastaction, 0) AS created_ymdhis,
    lastaction AS updated_ymdhis
FROM livehelp_users;


-- ======================================================================
-- livehelp_users               → lupo_auth_users
 
ALTER TABLE livehelp_visits_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visits_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_users               → lupo_auth_users
 
ALTER TABLE livehelp_visits_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visits_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_users               → lupo_auth_users
 
ALTER TABLE livehelp_visit_track
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visit_track
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_users               → lupo_auth_users
 
ALTER TABLE livehelp_websites
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_websites
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


 
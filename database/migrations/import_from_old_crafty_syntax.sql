 /* 
======================================================================
   MIGRATION OVERVIEW
   Upgrades Crafty Syntax Live Help versions 3.6.1 through 3.7.5 to lupopedia 

   There were no database changes between 3.6.1 and 3.7.5, so all versions
   run this same migration to reach 4.0.0. EVERY TABLE CHANGED between
   3.7.5 and 4.0.0 — this release is a complete rebuild of the system
   after a 15-year hiatus from development.

   lupopedia is a full architectural rewrite. Every table has been
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
    Total tables present during migration: 233

      • 34 legacy Crafty Syntax tables (3.7.5)
      • 199 core Lupopedia tables 

    After successful migration, all 34 legacy tables are dropped,
    bringing the final schema count to 199 tables.

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
-- DROPPED: livehelp_autoinvite 
-- See: docs/doctrine/migrations/livehelp_autoinvite_migration.md

ALTER TABLE livehelp_autoinvite
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_autoinvite
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_crafty_syntax_auto_invite;

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
-- DROPPED: livehelp_channels 
-- See: docs/doctrine/migrations/livehelp_channels_migration.md 
ALTER TABLE livehelp_channels
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_channels
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_config               → JSON inserted into modules.id = 1
-- DROPPED: livehelp_config 
-- See: docs/doctrine/migrations/livehelp_config_migration.md 

ALTER TABLE livehelp_config
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_config
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

UPDATE lupo_modules m
SET m.config_json = (
    SELECT JSON_OBJECT(
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
    FROM livehelp_config c
    WHERE 1
    LIMIT 1
)
WHERE m.module_id = 1;

-- ======================================================================
-- livehelp_departments               → departments
-- DROPPED: livehelp_departments 
-- See: docs/doctrine/migrations/livehelp_departments_migration.md 

ALTER TABLE livehelp_departments
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_departments
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


TRUNCATE lupo_departments;

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

TRUNCATE lupo_department_metadata;

INSERT INTO lupo_department_metadata (
    department_id,
    metadata_json,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis
)
SELECT
    recno AS department_id,

    JSON_OBJECT(
        'onlineimage', onlineimage,
        'offlineimage', offlineimage,
        'layerinvite', layerinvite,
        'requirename', requirename,
        'messageemail', messageemail,
        'leaveamessage', leaveamessage,
        'opening', opening,
        'offline', offline,
        'creditline', creditline,
        'imagemap', imagemap,
        'whilewait', whilewait,
        'timeout', timeout,
        'topframeheight', topframeheight,
        'topbackground', topbackground,
        'midbackground', midbackground,
        'botbackground', botbackground,
        'topbackcolor', topbackcolor,
        'midbackcolor', midbackcolor,
        'botbackcolor', botbackcolor,
        'colorscheme', colorscheme,
        'speaklanguage', speaklanguage,
        'busymess', busymess,
        'emailfun', emailfun,
        'dbfun', dbfun,
        'smiles', smiles,
        'theme', theme,
        'showtimestamp', showtimestamp,
        'website', website
    ) AS metadata_json,

    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS created_ymdhis,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS updated_ymdhis,

    1 AS is_active,
    0 AS is_deleted,
    NULL AS deleted_ymdhis

FROM livehelp_departments;



-- ======================================================================
-- livehelp_emailque               → NOT migrated in this script (target lupo_crm_lead_message_sends out of scope)
-- DROPPED: livehelp_emailque 
-- See: docs/doctrine/migrations/livehelp_emailque_migration.md
ALTER TABLE livehelp_emailque
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_emailque
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_emails               → lupo_crm_lead_messages
-- DROPPED: livehelp_emails 
-- See: docs/doctrine/migrations/livehelp_emails_migration.md
ALTER TABLE livehelp_emails
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_emails
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_crm_lead_messages;

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
-- livehelp_identity_daily               → removed in Lupopedia  
-- DROPPED: livehelp_emails 
-- See: docs/doctrine/migrations/livehelp_identity_migration.md
ALTER TABLE livehelp_identity_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_identity_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_identity_monthly              → removed in Lupopedia 
-- DROPPED: livehelp_identity_monthly 
-- See: docs/doctrine/migrations/livehelp_identity_migration.md

ALTER TABLE livehelp_identity_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_identity_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

 INSERT IGNORE INTO `lupo_actors` (
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis,
    actor_source_id,
    actor_source_type,
    metadata,
    adversarial_role,
    adversarial_oversight_actor_id
)
SELECT
    'anonymous',
    CONCAT('anon-', id),
    CONCAT('Anonymous Visitor ', id),
    CONCAT(dateof, '01000000'),
    CONCAT(dateof, '28000000'),
    1,
    0,
    NULL,
    id,
    'legacy_identity_monthly',
    CONCAT(
        '{',
        '"legacy_cookieid":"', REPLACE(cookieid, '"', '\"'), '",',
        '"legacy_visit_count":', uservisits, ',',
        '"legacy_month":', dateof,
        '}'
    ),
    'none',
    NULL
FROM livehelp_identity_monthly
WHERE cookieid <> '';




-- ======================================================================
-- livehelp_keywords_daily              → removed in Lupopedia 
-- See: /docs/doctrine/migrations/livehelp_keywords_migration.md
ALTER TABLE livehelp_keywords_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_keywords_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_keywords_monthly              → removed in Lupopedia 
-- See: /docs/doctrine/migrations/livehelp_keywords_migration.md
ALTER TABLE livehelp_keywords_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_keywords_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_layerinvites              → crafty_syntax_layer_invites
-- See: /docs/doctrine/migrations/livehelp_layerinvites_migration.md
ALTER TABLE livehelp_layerinvites
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_layerinvites
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_crafty_syntax_layer_invites;

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
-- See: /docs/doctrine/migrations/livehelp_leads_migration.md

ALTER TABLE livehelp_leads
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_leads
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_crm_leads;
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
-- See: /docs/doctrine/migrations/livehelp_leavemessage_migration.md

ALTER TABLE livehelp_leavemessage
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_leavemessage
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_crafty_syntax_leave_message;
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
-- livehelp_messages -> dialog_messages but crafty did not store any of the messages after the chat ended so this table is empty unless there was active chats and lupopedia stores them in threads and messages attached to channels  
-- See: /docs/doctrine/migrations/livehelp_messages_migration.md
ALTER TABLE livehelp_messages
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_messages
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_modules -> modules
-- See: /docs/doctrine/migrations/livehelp_modules_migration.md 
ALTER TABLE livehelp_modules
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  ALTER TABLE livehelp_modules
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
 
-- ======================================================================
-- livehelp_modules_dep -> crafty_chat_mod_departments
-- NOTE TO AI / JETBRAINS /CURSOR / ANY IDE:
-- DO NOT MAP THIS TABLE. DO NOT CREATE A NEW TABLE FOR IT.
-- THIS TABLE IS DROPPED WITH NO IMPORT.
-- Lupopedia enables all public-facing modules for all departments by default.
-- Module visibility is controlled in the admin UI, not via migration.
-- See: /docs/doctrine/migrations/livehelp_modules_dep_migration.md 
 
ALTER TABLE livehelp_modules_dep
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  ALTER TABLE livehelp_modules_dep
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
 
-- ======================================================================
-- livehelp_operator_channels -> channels
-- See: /docs/doctrine/migrations/livehelp_operator_channels_migration.md 
ALTER TABLE livehelp_operator_channels
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_channels
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_operator_departments -> actor_departments
-- See: /docs/doctrine/migrations/livehelp_operator_departments_migration.md  
ALTER TABLE livehelp_operator_departments
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_departments
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_actor_departments;
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
-- See: /docs/doctrine/migrations/livehelp_operator_history_migration.md 
ALTER TABLE livehelp_operator_history
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_history
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_audit_log;
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
-- livehelp_qa -> truth_questions
-- See: /docs/doctrine/migrations/livehelp_qa_migration.md
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
    recno,
    NULLIF(parent, 0),
    0,
    'unknown',
    'active',
    ordernum,
    CONCAT('qa-', recno),
    question,
    'text',
    NULL,
    0,
    0,
    0,
    0,
    NULL,
    0,
    0,
    20250101000000,
    20250101000000,
    0,
    NULL
FROM livehelp_qa
WHERE typeof = 'question'
ON DUPLICATE KEY UPDATE
    truth_question_parent_id = VALUES(truth_question_parent_id),
    sort_num = VALUES(sort_num),
    question_text = VALUES(question_text),
    updated_ymdhis = VALUES(updated_ymdhis);



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
    parent,
    0,
    question,
    0.00,
    0.00,
    0,
    0,
    0,
    20250101000000,
    20250101000000,
    0,
    NULL
FROM livehelp_qa
WHERE typeof = 'answer'
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis);


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
)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    color = VALUES(color),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    properties = VALUES(properties),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_deleted = VALUES(is_deleted),
    deleted_ymdhis = VALUES(deleted_ymdhis);


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
WHERE typeof = 'folder' AND parent = 0
ON DUPLICATE KEY UPDATE
    sort_order = VALUES(sort_order),
    name = VALUES(name),
    color = VALUES(color),
    description = VALUES(description),
    is_hidden = VALUES(is_hidden),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active),
    is_deleted = VALUES(is_deleted),
    deleted_ymdhis = VALUES(deleted_ymdhis);



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
WHERE child.typeof = 'folder' AND child.parent != 0
ON DUPLICATE KEY UPDATE
    collection_tab_parent_id = VALUES(collection_tab_parent_id),
    sort_order = VALUES(sort_order),
    name = VALUES(name),
    color = VALUES(color),
    description = VALUES(description),
    is_hidden = VALUES(is_hidden),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active),
    is_deleted = VALUES(is_deleted),
    deleted_ymdhis = VALUES(deleted_ymdhis);

-- ======================================================================
-- livehelp_questions -> lupo_crafty_syntax_chat_questions
-- See: /docs/doctrine/migrations/livehelp_questions_migration.md
ALTER TABLE livehelp_questions
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_questions
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_crafty_syntax_chat_questions;
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
-- livehelp_quick -> lupo_actor_reply_templates
-- See: /docs/doctrine/migrations/livehelp_quick_migration.md
ALTER TABLE livehelp_quick
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_quick
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
TRUNCATE lupo_actor_reply_templates;
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
-- livehelp_smilies -> DROPPED
-- replaced with the chat_smilies/ directory structure
-- See: /docs/doctrine/migrations/livehelp_smilies_migration.md
ALTER TABLE livehelp_smilies
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 
ALTER TABLE livehelp_smilies
COMMENT = 'LEGACY ARCHIVE TABLE — no longer used. Crafty Syntax originally stored emoji metadata here, but Lupopedia replaces this system entirely. Emoji and inline images are now inserted directly into dialog text using the token format :|:name|folder|filename:|:. The renderer reads icons from the chat_smilies/ directory (and its subfolders) and replaces the token with the corresponding image at display time. This table is preserved only for historical reference; no data is imported and no new rows will be created.';
  
-- ======================================================================
-- livehelp_sessions -> DROPPED
-- See: /docs/doctrine/migrations/livehelp_sessions_migration.md
ALTER TABLE livehelp_sessions
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 
ALTER TABLE livehelp_sessions
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';
 
-- ======================================================================
-- livehelp_users               → lupo_auth_users
 -- See: /docs/doctrine/migrations/livehelp_users_migration.md
ALTER TABLE livehelp_users
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_users
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

INSERT INTO lupo_auth_users (
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
    is_deleted,
    deleted_ymdhis
)
SELECT
    u.username,
    u.displayname,
    NULLIF(u.email, ''),
    CASE 
        WHEN u.password IS NULL OR u.password = '' THEN NULL
        ELSE u.password
    END,
    NULLIF(u.auth_provider, ''),
    NULLIF(u.provider_id, ''),
    NULL,
    CASE 
        WHEN u.lastaction IS NULL OR u.lastaction = 0 THEN NULL
        ELSE DATE_FORMAT(FROM_UNIXTIME(u.lastaction), '%Y%m%d%H%i%S')
    END,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    1,
    0,
    NULL
FROM livehelp_users u
WHERE u.isoperator = 'Y'
AND NOT EXISTS (
    SELECT 1 FROM lupo_auth_users x WHERE x.username = u.username
);


INSERT INTO lupo_auth_users (
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
    is_deleted,
    deleted_ymdhis
)
SELECT
    u.username,
    u.displayname,
    NULLIF(u.email, ''),
    CASE 
        WHEN u.password IS NULL OR u.password = '' THEN NULL
        ELSE u.password
    END,
    NULLIF(u.auth_provider, ''),
    NULLIF(u.provider_id, ''),
    NULL,
    CASE 
        WHEN u.lastaction IS NULL OR u.lastaction = 0 THEN NULL
        ELSE DATE_FORMAT(FROM_UNIXTIME(u.lastaction), '%Y%m%d%H%i%S')
    END,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S'),
    1,
    0,
    NULL
FROM livehelp_users u
WHERE NOT EXISTS (
    SELECT 1 FROM lupo_auth_users x WHERE x.username = u.username
);



-- ======================================================================
-- livehelp_referers_daily               → lupo_unified_referers
 -- See: /docs/doctrine/migrations/livehelp_referers_daily_migration.md
 
ALTER TABLE livehelp_referers_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_referers_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    
ALTER TABLE livehelp_referers_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

ALTER TABLE livehelp_referers_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


TRUNCATE lupo_unified_referers;
INSERT INTO lupo_unified_referers (
    content_id,
    actor_id,
    referer_url,
    referer_domain,
    referer_path,
    referer_content_id,
    date_ymd,
    visits,
    depth,
    metadata_json
)
SELECT
    0 AS content_id,
    0 AS actor_id,
    NULL AS referer_url,
    NULL AS referer_domain,
    NULL AS referer_path,
    NULL AS referer_content_id,
    r.dateof AS date_ymd,
    (r.levelvisits + r.directvisits) AS visits,
    r.level AS depth,
    JSON_OBJECT(
        'legacy_pageurl', r.pageurl,
        'legacy_parentrec', r.parentrec,
        'legacy_department', r.department,
        'legacy_livehelp_id', r.livehelp_id,
        'legacy_levelvisits', r.levelvisits,
        'legacy_directvisits', r.directvisits
    ) AS metadata_json
FROM livehelp_referers_daily r;


INSERT INTO lupo_unified_referers (
    content_id,
    actor_id,
    referer_url,
    referer_domain,
    referer_path,
    referer_content_id,
    date_ymd,
    visits,
    depth,
    metadata_json
)
SELECT
    0 AS content_id,
    0 AS actor_id,
    r.pageurl AS referer_url,

    -- DOMAIN
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(r.pageurl, '/', 3),
        '/',
        -1
    ) AS referer_domain,

    -- PATH
    SUBSTRING(
        r.pageurl,
        LENGTH(SUBSTRING_INDEX(r.pageurl, '/', 3)) + 1
    ) AS referer_path,

    0 AS referer_content_id,

    r.dateof AS date_ymd,
    (r.levelvisits + r.directvisits) AS visits,
    r.level AS depth,

    JSON_OBJECT(
        'legacy_pageurl', r.pageurl,
        'legacy_parentrec', r.parentrec,
        'legacy_department', r.department,
        'legacy_livehelp_id', r.livehelp_id,
        'legacy_levelvisits', r.levelvisits,
        'legacy_directvisits', r.directvisits
    ) AS metadata_json

FROM livehelp_referers_monthly r;


-- ======================================================================
-- livehelp_visit_track               → lupo_unified_visits
 -- See: /docs/doctrine/migrations/livehelp_visit_track_migration.md 

ALTER TABLE livehelp_visit_track
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_visit_track
  COMMENT = 'DEPRECATED: Ephemeral session tracking table. Not imported into unified analytics. Safe to delete after migration.';
 
ALTER TABLE livehelp_visits_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_visits_daily
  COMMENT = 'DEPRECATED: Imported into lupo_unified_visits. Safe to delete after migration.';

ALTER TABLE livehelp_visits_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visits_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


TRUNCATE lupo_unified_visits;
INSERT INTO lupo_unified_visits (
    content_id,
    actor_id,
    page_url,
    page_domain,
    page_path,
    date_ymd,
    visits,
    depth,
    metadata_json
)
SELECT
    1 AS content_id, -- ophaned we will look them up later 
    1 AS actor_id, -- ophaned we will look them up later 
    r.pageurl AS page_url,

    SUBSTRING_INDEX(SUBSTRING_INDEX(r.pageurl, '/', 3), '/', -1) AS page_domain,

    SUBSTRING(r.pageurl, LENGTH(SUBSTRING_INDEX(r.pageurl, '/', 3)) + 1) AS page_path,

    r.dateof AS date_ymd,
    (r.levelvisits + r.directvisits) AS visits,
    r.level AS depth,

    JSON_OBJECT(
        'legacy_pageurl', r.pageurl,
        'legacy_parentrec', r.parentrec,
        'legacy_department', r.department,
        'legacy_livehelp_id', r.livehelp_id,
        'legacy_levelvisits', r.levelvisits,
        'legacy_directvisits', r.directvisits
    ) AS metadata_json

FROM livehelp_visits_daily r;

INSERT INTO lupo_unified_visits (
    content_id,
    actor_id,
    page_url,
    page_domain,
    page_path,
    date_ymd,
    visits,
    depth,
    metadata_json
)
SELECT
    1 AS content_id,
    1 AS actor_id,
    r.pageurl AS page_url,

    SUBSTRING_INDEX(SUBSTRING_INDEX(r.pageurl, '/', 3), '/', -1) AS page_domain,

    SUBSTRING(r.pageurl, LENGTH(SUBSTRING_INDEX(r.pageurl, '/', 3)) + 1) AS page_path,

    r.dateof AS date_ymd,
    (r.levelvisits + r.directvisits) AS visits,
    r.level AS depth,

    JSON_OBJECT(
        'legacy_pageurl', r.pageurl,
        'legacy_parentrec', r.parentrec,
        'legacy_department', r.department,
        'legacy_levelvisits', r.levelvisits,
        'legacy_directvisits', r.directvisits
    ) AS metadata_json

FROM livehelp_visits_monthly r;

-- ======================================================================
-- livehelp_paths_firsts               → lupo_unified_analytics_paths
 -- See: /docs/doctrine/migrations/livehelp_paths_firsts_migration.md
ALTER TABLE livehelp_paths_firsts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_firsts
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

ALTER TABLE livehelp_paths_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';


TRUNCATE lupo_unified_analytics_paths;

INSERT INTO `lupo_unified_analytics_paths` (
    `from_page_id`,
    `to_page_id`,
    `year_month`,
    `transition_type`,
    `transition_count`,
    `metadata_json`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
SELECT
    `visit_recno` AS `from_page_id`,
    `exit_recno` AS `to_page_id`,
    LEFT(`dateof`, 6) AS `year_month`,
    'first' AS `transition_type`,
    `visits` AS `transition_count`,
    NULL AS `metadata_json`,
    CONCAT(`dateof`, '000000') AS `created_ymdhis`,
    CONCAT(`dateof`, '000000') AS `updated_ymdhis`,
    0 AS `is_deleted`,
    NULL AS `deleted_ymdhis`
FROM `livehelp_paths_firsts`;
 
 INSERT INTO `lupo_unified_analytics_paths` (
    `from_page_id`,
    `to_page_id`,
    `year_month`,
    `transition_type`,
    `transition_count`,
    `metadata_json`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
SELECT
    `visit_recno` AS `from_page_id`,
    `exit_recno` AS `to_page_id`,
    `dateof` AS `year_month`,
    'all' AS `transition_type`,
    `visits` AS `transition_count`,
    NULL AS `metadata_json`,
    CONCAT(`dateof`, '01000000') AS `created_ymdhis`,
    CONCAT(`dateof`, '01000000') AS `updated_ymdhis`,
    0 AS `is_deleted`,
    NULL AS `deleted_ymdhis`
FROM `livehelp_paths_monthly`;

 
-- ======================================================================
-- livehelp_transcripts               → lupo_dialog_threads & lupo_dialog_messages
 -- See: /docs/doctrine/migrations/livehelp_transcripts_migration.md

ALTER TABLE livehelp_transcripts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_transcripts
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_dialog_threads;
INSERT INTO `lupo_dialog_threads` (
    `dialog_thread_id`,
    `federation_node_id`,
    `channel_id`,
    `created_by_actor_id`,
    `summary_text`,
    `metadata_json`,
    `created_ymdhis`,
    `updated_ymdhis`
)
SELECT
    `recno`,
    1,
    1,
    1,
    CONCAT(`recno`, ' import from crafty syntax'),
    NULL,
    `starttime`,
    `endtime`
FROM `livehelp_transcripts`;

TRUNCATE lupo_dialog_messages;
INSERT INTO `lupo_dialog_messages` (
    `dialog_message_id`,
    `dialog_thread_id`,
    `channel_id`,
    `from_actor_id`,
    `to_actor_id`,
    `message_text`,
    `message_body`,
    `message_type`,
    `metadata_json`,
    `mood_rgb`,
    `mood_framework`,
    `weight`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
SELECT
    `recno`,
    `recno`,
    1,
    1,
    1,
    CONCAT('Imported transcript #', `recno`),
    `transcript`,
    'text',
    NULL,
    NULL,
    'western_analytical',
    1.00,
    `starttime`,
    `endtime`,
    0,
    NULL
FROM `livehelp_transcripts`;
 

-- ======================================================================
-- livehelp_websites               → lupo_federation_nodes
 -- See: /docs/doctrine/migrations/livehelp_websites_migration.md

ALTER TABLE livehelp_websites
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_websites
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 4.0.0 and should be deleted after successful migration.';

-- NODE 0 IS LUPOPEDIA.COM - DO NOT DELETE
DELETE FROM lupo_federation_nodes WHERE federation_node_id!=0;

INSERT INTO `lupo_federation_nodes` (
    `federation_node_id`,
    `node_name`,
    `node_base_url`,
    `default_department_id`,
    `meta_json`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
SELECT
    `id` AS `federation_node_id`,
    `site_name` AS `node_name`,
    `site_url` AS `node_base_url`,
    `defaultdepartment` AS `default_department_id`,
    NULL AS `meta_json`,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS `created_ymdhis`,
    DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS `updated_ymdhis`,
    0 AS `is_deleted`,
    0 AS `deleted_ymdhis`
FROM `livehelp_websites`;

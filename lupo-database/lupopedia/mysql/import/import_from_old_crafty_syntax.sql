 /* 
======================================================================
   MIGRATION OVERVIEW
   Upgrades Crafty Syntax Live Help versions 3.6.1 through 3.7.5 to lupopedia 

   There were no database changes between 3.6.1 and 3.7.5, so all versions
   run this same migration to reach 3.0.0. EVERY TABLE CHANGED between
   3.7.5 and 3.0.0 — this release is a complete rebuild of the system
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
    (10000 + a.user_id) AS operator_user_id,

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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_config               → JSON inserted into modules.id = 1
-- DROPPED: livehelp_config 
-- See: docs/doctrine/migrations/livehelp_config_migration.md 

ALTER TABLE livehelp_config
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_config
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


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

-- System department (department_id = 0): reserved, must exist. Overwrite if livehelp_departments had recno=0.
INSERT INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (0, 1, 'System', 'System Department (Reserved)', 'system', 0, NULL, CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), 0, NULL)
ON DUPLICATE KEY UPDATE name = 'System', description = 'System Department (Reserved)', department_type = 'system', default_actor_id = 0, updated_ymdhis = CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED);

-- Default department (department_id = 1): for channels when livehelp_departments is empty or has no recno=1.
INSERT IGNORE INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, 'General', 'Default department for channels', 'general', 0, NULL, CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), 0, NULL);

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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_emails               → lupo_crm_lead_messages
-- DROPPED: livehelp_emails 
-- See: docs/doctrine/migrations/livehelp_emails_migration.md
ALTER TABLE livehelp_emails
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_emails
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_identity_monthly              → removed in Lupopedia 
-- DROPPED: livehelp_identity_monthly 
-- See: docs/doctrine/migrations/livehelp_identity_migration.md

ALTER TABLE livehelp_identity_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_identity_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- Anonymous users are NOT inserted into lupo_actors. Only authenticated users (lupo_auth_users),
-- agents, and system users have rows in lupo_actors. Anonymous visitors exist in lupo_sessions only.
-- livehelp_identity_monthly / livehelp_identity_daily are converted and deprecated above; no import.


-- ======================================================================
-- livehelp_keywords_daily              → removed in Lupopedia 
-- See: /docs/doctrine/migrations/livehelp_keywords_migration.md
ALTER TABLE livehelp_keywords_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_keywords_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_keywords_monthly              → removed in Lupopedia 
-- See: /docs/doctrine/migrations/livehelp_keywords_migration.md
ALTER TABLE livehelp_keywords_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_keywords_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_layerinvites              → crafty_syntax_layer_invites
-- See: /docs/doctrine/migrations/livehelp_layerinvites_migration.md
ALTER TABLE livehelp_layerinvites
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_layerinvites
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
    (10000 + `user`) AS user_id,
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
    id AS crafty_syntax_leave_message_id,
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


-- ======================================================================
-- livehelp_modules -> modules
-- See: /docs/doctrine/migrations/livehelp_modules_migration.md 
ALTER TABLE livehelp_modules
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  ALTER TABLE livehelp_modules
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';
 
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';
 
-- ======================================================================
-- livehelp_operator_channels -> channels
-- See: /docs/doctrine/migrations/livehelp_operator_channels_migration.md 
ALTER TABLE livehelp_operator_channels
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_channels
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_operator_departments -> actor_departments
-- See: /docs/doctrine/migrations/livehelp_operator_departments_migration.md  
ALTER TABLE livehelp_operator_departments
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_operator_departments
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
    (10000 + user_id) AS actor_id,
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
    (10000 + opid) AS entity_id,
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

TRUNCATE lupo_truth_knowledge;

INSERT INTO lupo_truth_knowledge (
    truth_id,
    truth_type,
    parent_id,
    question_id,
    answer_id,
    evidence_id,
    source_id,
    topic_id,
    relation_id,
    actor_id,
    object_type,
    object_id,
    left_object_type,
    left_object_id,
    right_object_type,
    right_object_id,
    slug,
    title,
    text_content,
    question_text,
    answer_text,
    evidence_text,
    source_url,
    source_title,
    qtype,
    status,
    evidence_type,
    source_type,
    relation_type,
    format,
    format_override,
    confidence_score,
    evidence_score,
    weight_score,
    reliability_score,
    importance_score,
    sort_num,
    view_count,
    likes_count,
    shares_count,
    answer_count,
    contradiction_flag,
    is_featured,
    is_verified,
    last_activity_ymdhis,
    default_collection_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis,
    truth_question_parent_id
)
SELECT
    recno,
    'question',
    NULLIF(parent, 0),
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    CONCAT('qa-', recno),
    question,
    NULL,
    question,
    NULL,
    NULL,
    NULL,
    NULL,
    'unknown',
    'active',
    NULL,
    NULL,
    NULL,
    'text',
    NULL,
    0.00,
    0.00,
    0.00,
    0.00,
    0.00,
    ordernum,
    0,
    0,
    0,
    0,
    0,
    0,
    0,
    NULL,
    0,
    20250101000000,
    20250101000000,
    0,
    NULL,
    NULL
FROM livehelp_qa
WHERE typeof = 'question'
ON DUPLICATE KEY UPDATE
    truth_type = VALUES(truth_type),
    parent_id = VALUES(parent_id),
    question_id = VALUES(question_id),
    answer_id = VALUES(answer_id),
    evidence_id = VALUES(evidence_id),
    source_id = VALUES(source_id),
    topic_id = VALUES(topic_id),
    relation_id = VALUES(relation_id),
    actor_id = VALUES(actor_id),
    object_type = VALUES(object_type),
    object_id = VALUES(object_id),
    left_object_type = VALUES(left_object_type),
    left_object_id = VALUES(left_object_id),
    right_object_type = VALUES(right_object_type),
    right_object_id = VALUES(right_object_id),
    slug = VALUES(slug),
    title = VALUES(title),
    text_content = VALUES(text_content),
    truth_question_parent_id = VALUES(truth_question_parent_id),
    sort_num = VALUES(sort_num),
    question_text = VALUES(question_text),
    updated_ymdhis = VALUES(updated_ymdhis);



TRUNCATE lupo_truth_answers;

INSERT INTO lupo_truth_answers (
    truth_question_id,
    actor_id,
    answer_text,
    confidence,
    evidence_count,
    source_count,
    status,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis,
    evidence_score,
    contradiction_flag,
    likes_count
)
SELECT
    parent,
    0,
    question,
    0.00,
    0,
    0,
    'active',
    20250101000000,
    20250101000000,
    0,
    NULL,
    0.00,
    0,
    0
FROM livehelp_qa
WHERE typeof = 'answer'
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis);


INSERT INTO lupo_collections (
    collection_id,
    federation_node_id,
    actor_id,
    department_id,
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
    deleted_ymdhis,
    parent_id
)
VALUES (
    1,
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
    NULL,
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
    department_id,
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
    NULL AS department_id,
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
    department_id,
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
    NULL AS department_id,
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';
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
    (10000 + `user`) AS actor_id,
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';
 
 
-- ======================================================================
-- livehelp_referers_daily               → lupo_referers
 -- See: /docs/doctrine/migrations/livehelp_referers_daily_migration.md
 
ALTER TABLE livehelp_referers_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_referers_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    
ALTER TABLE livehelp_referers_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

ALTER TABLE livehelp_referers_daily
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


TRUNCATE lupo_referers;
INSERT INTO lupo_referers (
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


INSERT INTO lupo_referers (
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
-- livehelp_visit_track               → lupo_visits
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
  COMMENT = 'DEPRECATED: Imported into lupo_visits. Safe to delete after migration.';

ALTER TABLE livehelp_visits_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visits_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


TRUNCATE lupo_visits;
INSERT INTO lupo_visits (
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

INSERT INTO lupo_visits (
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
-- livehelp_visits_daily                → lupo_analytics_visits_daily
-- livehelp_visits_monthly              → lupo_analytics_visits_monthly
-- Aggregated by (content_id, date_ymd) / (content_id, date_ym) to match
-- TOON unique keys. Crafty levelvisits+directvisits → visits; directvisits → direct_visits.
-- ======================================================================
TRUNCATE lupo_analytics_visits_daily;
INSERT INTO lupo_analytics_visits_daily (
    analytics_visits_daily_id,
    content_id,
    url_path,
    department_id,
    date_ymd,
    visit_type,
    total_visits,
    unique_sessions,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    @rn := @rn + 1,
    t.content_id,
    t.url_path,
    t.department_id,
    t.date_ymd,
    'pageview',
    t.visits,
    0,
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    0,
    NULL
FROM (SELECT @rn := 0) r,
(
    SELECT
        r.livehelp_id AS content_id,
        SUBSTRING(MAX(r.pageurl), 1, 500) AS url_path,
        MAX(r.department) AS department_id,
        r.dateof AS date_ymd,
        SUM(r.levelvisits + r.directvisits) AS visits
    FROM livehelp_visits_daily r
    GROUP BY r.livehelp_id, r.dateof
) t;

TRUNCATE lupo_analytics_visits_monthly;
INSERT INTO lupo_analytics_visits_monthly (
    analytics_visits_monthly_id,
    content_id,
    url_path,
    department_id,
    date_ym,
    visit_type,
    total_visits,
    unique_sessions,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    @rn := @rn + 1,
    t.content_id,
    t.url_path,
    t.department_id,
    t.date_ym,
    'pageview',
    t.visits,
    0,
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    0,
    NULL
FROM (SELECT @rn := 0) r,
(
    SELECT
        0 AS content_id,
        SUBSTRING(MAX(r.pageurl), 1, 500) AS url_path,
        MAX(r.department) AS department_id,
        r.dateof AS date_ym,
        SUM(r.levelvisits + r.directvisits) AS visits
    FROM livehelp_visits_monthly r
    GROUP BY r.dateof
) t;

-- ======================================================================
-- livehelp_paths_firsts               → lupo_analytics_paths
 -- See: /docs/doctrine/migrations/livehelp_paths_firsts_migration.md
ALTER TABLE livehelp_paths_firsts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_firsts
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

ALTER TABLE livehelp_paths_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_monthly
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';


TRUNCATE lupo_analytics_paths;

INSERT INTO `lupo_analytics_paths` (
    `from_page_id`,
    `to_page_id`,
    `year_month_yyyymm`,
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
    LEFT(`dateof`, 6) AS `year_month_yyyymm`,
    'first' AS `transition_type`,
    `visits` AS `transition_count`,
    NULL AS `metadata_json`,
    CONCAT(`dateof`, '000000') AS `created_ymdhis`,
    CONCAT(`dateof`, '000000') AS `updated_ymdhis`,
    0 AS `is_deleted`,
    NULL AS `deleted_ymdhis`
FROM `livehelp_paths_firsts`;
 
INSERT INTO `lupo_analytics_paths` (
    `from_page_id`,
    `to_page_id`,
    `year_month_yyyymm`,
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
    `dateof` AS `year_month_yyyymm`,
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- Dialog thread title: who + recno when who present; else first snippet of transcript (stripped); else safe fallback.
-- last_message_ymdhis: endtime (last message time) when present, else starttime, else NULL.
TRUNCATE lupo_dialog_threads;
INSERT INTO `lupo_dialog_threads` (
    `dialog_thread_id`,
    `title`,
    `last_message_ymdhis`,
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
    COALESCE(
        NULLIF(TRIM(CONCAT(IFNULL(`who`, ''), ' – #', `recno`)), ''),
        NULLIF(TRIM(LEFT(REPLACE(REPLACE(REPLACE(`transcript`, '<br>', ' '), '<b>', ''), '</b>', ''), 80)), ''),
        CONCAT('Transcript ', `recno`)
    ),
    COALESCE(`endtime`, `starttime`),
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
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

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


-- ======================================================================
-- livehelp_users               → lupo_auth_users
 -- See: /docs/doctrine/migrations/livehelp_users_migration.md
ALTER TABLE livehelp_users
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_users
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- RESERVED ID DOCTRINE: lupo_auth_users.auth_user_id is NOT AUTO_INCREMENT; must supply explicit ID.
-- ACTOR ID RANGE: actor_id 0-9999 = system/AI agents only (seed). Human actors must use actor_id >= 10000.
-- Import remaps Crafty user_id into human range: auth_user_id = 10000 + u.user_id (so actor_id = auth_user_id is >= 10000).
-- Intentional: first INSERT = Crafty operators (isoperator = 'Y'); second INSERT = all remaining users. Result: ALL Crafty users become lupo_auth_users.
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
    is_deleted,
    deleted_ymdhis
)
SELECT
    (10000 + u.user_id) AS auth_user_id,
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
        ELSE CAST(DATE_FORMAT(FROM_UNIXTIME(u.lastaction), '%Y%m%d%H%i%S') AS SIGNED)
    END,
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    1,
    0,
    NULL
FROM livehelp_users u
WHERE u.isoperator = 'Y'
AND NOT EXISTS (
    SELECT 1 FROM lupo_auth_users x WHERE x.auth_user_id = (10000 + u.user_id)
);


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
    is_deleted,
    deleted_ymdhis
)
SELECT
    (10000 + u.user_id) AS auth_user_id,
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
        ELSE CAST(DATE_FORMAT(FROM_UNIXTIME(u.lastaction), '%Y%m%d%H%i%S') AS SIGNED)
    END,
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    1,
    0,
    NULL
FROM livehelp_users u
WHERE NOT EXISTS (
    SELECT 1 FROM lupo_auth_users x WHERE x.auth_user_id = (10000 + u.user_id)
);

-- ======================================================================
-- Phase 1: Create lupo_actors for each imported Crafty operator (actor_type: 'user').
-- Lupopedia has no lupo_operators table; permissions use lupo_actor_channel_roles.
-- The wizard assigns roles after import. actor_id = auth_user_id for imported humans.
-- auth_user_id was set to (10000 + livehelp_users.user_id) above, so actor_id >= 10000 (human range).
-- ======================================================================

INSERT INTO lupo_actors (
    actor_name,
    actor_id,
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
    adversarial_oversight_actor_id,
    avatar_hash
)
SELECT
    COALESCE(NULLIF(TRIM(au.username), ''), CONCAT('actor_', au.auth_user_id)),
    au.auth_user_id,
    'user',
    au.username,
    COALESCE(NULLIF(TRIM(au.display_name), ''), au.username),
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    1,
    0,
    NULL,
    au.auth_user_id,
    'lupo_auth_users',
    NULL,
    'none',
    NULL,
    NULL
FROM lupo_auth_users au
INNER JOIN livehelp_users u ON u.username = au.username
WHERE u.isoperator = 'Y'
AND NOT EXISTS (
    SELECT 1 FROM lupo_actors a2
    WHERE a2.actor_id = au.auth_user_id
    AND a2.actor_source_type = 'lupo_auth_users'
);

-- Department mapping: rewire lupo_actor_departments.actor_id to match lupo_actors.actor_id (actor_id = auth_user_id).
UPDATE lupo_actor_departments ad
INNER JOIN livehelp_operator_departments od ON ad.actor_department_id = od.recno
INNER JOIN livehelp_users u ON u.user_id = od.user_id
INNER JOIN lupo_auth_users au ON au.username = u.username
INNER JOIN lupo_actors a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = 'lupo_auth_users'
SET ad.actor_id = a.actor_id;

-- Global admins: assign each Crafty admin (isadmin='Y') to department 0.
-- lupo_actor_departments: actor membership in system department
-- lupo_department_roles: role_key='administrator' (admin for ALL departments)
INSERT INTO lupo_actor_departments (actor_department_id, actor_id, department_id, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'System Administrator', ts, ts, 0, NULL
FROM (
    SELECT a.actor_id, @arn := @arn + 1 AS rn
    FROM livehelp_users u
    INNER JOIN lupo_auth_users au ON au.username = u.username
    INNER JOIN lupo_actors a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = 'lupo_auth_users'
    CROSS JOIN (SELECT @arn := 0) v
    WHERE UPPER(TRIM(COALESCE(u.isadmin, ''))) = 'Y'
      AND NOT EXISTS (SELECT 1 FROM lupo_actor_departments ad2 WHERE ad2.actor_id = a.actor_id AND ad2.department_id = 0 AND (ad2.is_deleted = 0 OR ad2.is_deleted IS NULL))
    ORDER BY a.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(actor_department_id), 0) AS base FROM lupo_actor_departments) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;

INSERT INTO lupo_department_roles (department_role_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'administrator', ts, ts, 0, NULL
FROM (
    SELECT a.actor_id, @drn := @drn + 1 AS rn
    FROM livehelp_users u
    INNER JOIN lupo_auth_users au ON au.username = u.username
    INNER JOIN lupo_actors a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = 'lupo_auth_users'
    CROSS JOIN (SELECT @drn := 0) v
    WHERE UPPER(TRIM(COALESCE(u.isadmin, ''))) = 'Y'
      AND NOT EXISTS (SELECT 1 FROM lupo_department_roles dr2 WHERE dr2.actor_id = a.actor_id AND dr2.department_id = 0 AND dr2.role_key = 'administrator' AND (dr2.is_deleted = 0 OR dr2.is_deleted IS NULL))
    ORDER BY a.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(department_role_id), 0) AS base FROM lupo_department_roles) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;

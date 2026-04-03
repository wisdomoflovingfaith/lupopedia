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
-- See: lupo-docs/doctrine/migrations/livehelp_autoinvite_migration.md

ALTER TABLE livehelp_autoinvite
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE livehelp_autoinvite
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

TRUNCATE {{prefix}}crafty_syntax_auto_invite;

INSERT INTO {{prefix}}crafty_syntax_auto_invite (
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

UPDATE {{prefix}}modules m
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


TRUNCATE {{prefix}}departments;

INSERT INTO {{prefix}}departments (
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
INSERT INTO {{prefix}}departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (0, 1, 'System', 'System Department (Reserved)', 'system', 0, NULL, CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), 0, NULL)
ON DUPLICATE KEY UPDATE name = 'System', description = 'System Department (Reserved)', department_type = 'system', default_actor_id = 0, updated_ymdhis = CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED);

-- Default department (department_id = 1): for channels when livehelp_departments is empty or has no recno=1.
INSERT IGNORE INTO {{prefix}}departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, 'General', 'Default department for channels', 'general', 0, NULL, CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), 0, NULL);

TRUNCATE {{prefix}}department_metadata;

-- department_metadata_id is application-assigned (no AUTO_INCREMENT).
SET @lupo_department_metadata_seq := 0;

INSERT INTO {{prefix}}department_metadata (
    department_metadata_id,
    department_id,
    metadata_json,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis
)
SELECT
    (@lupo_department_metadata_seq := @lupo_department_metadata_seq + 1) AS department_metadata_id,
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
-- livehelp_emailque               → NOT migrated in this script (target {{prefix}}crm_lead_message_sends out of scope)
-- DROPPED: livehelp_emailque 
-- See: docs/doctrine/migrations/livehelp_emailque_migration.md
ALTER TABLE livehelp_emailque
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_emailque
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- ======================================================================
-- livehelp_emails               → {{prefix}}crm_lead_messages
-- DROPPED: livehelp_emails 
-- See: docs/doctrine/migrations/livehelp_emails_migration.md
ALTER TABLE livehelp_emails
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_emails
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

TRUNCATE {{prefix}}crm_lead_messages;

INSERT INTO {{prefix}}crm_lead_messages (
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

-- Anonymous users are NOT inserted into {{prefix}}actors. Only authenticated users ({{prefix}}auth_users),
-- agents, and system users have rows in {{prefix}}actors. Anonymous visitors exist in {{prefix}}sessions only.
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

TRUNCATE {{prefix}}crafty_syntax_layer_invites;

INSERT INTO {{prefix}}crafty_syntax_layer_invites (
    crafty_syntax_layer_invite_id,
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
    layerid AS crafty_syntax_layer_invite_id,
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
-- livehelp_leads              →  {{prefix}}crm_leads
-- See: /docs/doctrine/migrations/livehelp_leads_migration.md

ALTER TABLE livehelp_leads
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_leads
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

TRUNCATE {{prefix}}crm_leads;
INSERT INTO  {{prefix}}crm_leads
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

TRUNCATE {{prefix}}crafty_syntax_leave_message;
INSERT INTO {{prefix}}crafty_syntax_leave_message (
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

TRUNCATE {{prefix}}actor_departments;
INSERT INTO {{prefix}}actor_departments (
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

TRUNCATE {{prefix}}audit_log;
INSERT INTO {{prefix}}audit_log (
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
        WHEN transcriptid > 0 THEN '{{prefix}}dialog_threads'
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

-- {{prefix}}truth_knowledge removed from install schema; questions live in {{prefix}}truth_questions.
TRUNCATE {{prefix}}truth_answers;
TRUNCATE {{prefix}}truth_questions;

INSERT INTO {{prefix}}truth_questions (
    truth_question_id,
    parent_question_id,
    root_question_id,
    depth,
    target_object_type,
    target_object_id,
    question_text,
    question_summary,
    asked_by_actor_id,
    asked_in_channel_id,
    asked_in_thread_id,
    asked_in_session_id,
    question_status,
    is_answered,
    is_featured,
    view_count,
    answer_count,
    follower_count,
    created_ymdhis,
    updated_ymdhis,
    answered_ymdhis,
    closed_ymdhis,
    is_deleted,
    deleted_ymdhis,
    metadata_json
)
SELECT
    recno AS truth_question_id,
    NULLIF(parent, 0) AS parent_question_id,
    NULL AS root_question_id,
    0 AS depth,
    'legacy_livehelp_qa' AS target_object_type,
    recno AS target_object_id,
    question AS question_text,
    NULL AS question_summary,
    0 AS asked_by_actor_id,
    NULL AS asked_in_channel_id,
    NULL AS asked_in_thread_id,
    NULL AS asked_in_session_id,
    'open' AS question_status,
    0 AS is_answered,
    0 AS is_featured,
    0 AS view_count,
    0 AS answer_count,
    0 AS follower_count,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    NULL AS answered_ymdhis,
    NULL AS closed_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis,
    JSON_OBJECT('legacy_typeof', typeof, 'legacy_ordernum', ordernum) AS metadata_json
FROM livehelp_qa
WHERE typeof = 'question'
ON DUPLICATE KEY UPDATE
    question_text = VALUES(question_text),
    parent_question_id = VALUES(parent_question_id),
    updated_ymdhis = VALUES(updated_ymdhis),
    metadata_json = VALUES(metadata_json);

INSERT INTO {{prefix}}truth_answers (
    truth_answer_id,
    truth_question_id,
    answer_text,
    answer_summary,
    answered_by_actor_id,
    answered_in_channel_id,
    answered_in_thread_id,
    answered_in_message_id,
    is_accepted,
    acceptance_votes,
    rejection_votes,
    confidence_score,
    answer_status,
    view_count,
    helpful_count,
    created_ymdhis,
    updated_ymdhis,
    accepted_ymdhis,
    is_deleted,
    deleted_ymdhis,
    metadata_json
)
SELECT
    recno AS truth_answer_id,
    parent AS truth_question_id,
    question AS answer_text,
    NULL AS answer_summary,
    0 AS answered_by_actor_id,
    NULL AS answered_in_channel_id,
    NULL AS answered_in_thread_id,
    NULL AS answered_in_message_id,
    0 AS is_accepted,
    0 AS acceptance_votes,
    0 AS rejection_votes,
    0.50 AS confidence_score,
    'active' AS answer_status,
    0 AS view_count,
    0 AS helpful_count,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    NULL AS accepted_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis,
    NULL AS metadata_json
FROM livehelp_qa
WHERE typeof = 'answer'
ON DUPLICATE KEY UPDATE
    answer_text = VALUES(answer_text),
    updated_ymdhis = VALUES(updated_ymdhis);


INSERT INTO {{prefix}}collections (
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


INSERT INTO {{prefix}}collection_tabs (
    collection_tab_id,
    collection_tab_parent_id,
    collection_id,
    federations_node_id,
    department_id,
    actor_id,
    sort_order,
    name,
    slug,
    color,
    description,
    is_hidden,
    visibility_rule,
    tab_type,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis
)
SELECT
    (1000000 + recno) AS collection_tab_id,
    NULL AS collection_tab_parent_id,
    1 AS collection_id,
    1 AS federations_node_id,
    NULL AS department_id,
    NULL AS actor_id,
    ordernum AS sort_order,
    question AS name,
    LOWER(REPLACE(question, ' ', '-')) AS slug,
    '4caf50' AS color,
    NULL AS description,
    0 AS is_hidden,
    NULL AS visibility_rule,
    NULL AS tab_type,
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



INSERT INTO {{prefix}}collection_tabs (
    collection_tab_id,
    collection_tab_parent_id,
    collection_id,
    federations_node_id,
    department_id,
    actor_id,
    sort_order,
    name,
    slug,
    color,
    description,
    is_hidden,
    visibility_rule,
    tab_type,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis
)
SELECT
    (1000000 + child.recno) AS collection_tab_id,
    parent_tab.collection_tab_id AS collection_tab_parent_id,
    1 AS collection_id,
    1 AS federations_node_id,
    NULL AS department_id,
    NULL AS actor_id,
    child.ordernum AS sort_order,
    child.question AS name,
    LOWER(REPLACE(child.question, ' ', '-')) AS slug,
    '4caf50' AS color,
    NULL AS description,
    0 AS is_hidden,
    NULL AS visibility_rule,
    NULL AS tab_type,
    20250101000000 AS created_ymdhis,
    20250101000000 AS updated_ymdhis,
    1 AS is_active,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_qa child
JOIN livehelp_qa parent
    ON parent.recno = child.parent
JOIN {{prefix}}collection_tabs parent_tab
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
-- livehelp_questions -> {{prefix}}crafty_syntax_chat_questions
-- See: /docs/doctrine/migrations/livehelp_questions_migration.md
ALTER TABLE livehelp_questions
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_questions
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

TRUNCATE {{prefix}}crafty_syntax_chat_questions;
INSERT INTO {{prefix}}crafty_syntax_chat_questions (
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
-- livehelp_quick -> {{prefix}}actor_reply_templates
-- See: /docs/doctrine/migrations/livehelp_quick_migration.md
ALTER TABLE livehelp_quick
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_quick
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';
TRUNCATE {{prefix}}actor_reply_templates;
INSERT INTO {{prefix}}actor_reply_templates (
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
-- livehelp_referers_daily               → {{prefix}}referers
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


TRUNCATE {{prefix}}referers;
SET @lupo_import_referer_id := 0;

INSERT INTO {{prefix}}referers (
    referer_id,
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
    (@lupo_import_referer_id := @lupo_import_referer_id + 1) AS referer_id,
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


INSERT INTO {{prefix}}referers (
    referer_id,
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
    (@lupo_import_referer_id := @lupo_import_referer_id + 1) AS referer_id,
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
-- livehelp_visit_track / livehelp_visits_daily / livehelp_visits_monthly  → {{prefix}}visits (4.0.68 raw-events schema)
-- livehelp_paths_firsts / livehelp_paths_monthly                         → {{prefix}}paths (4.0.68 aggregated flows)
-- {{prefix}}visits: session_id, actor_id, path_url, entercontentid, created_ymdhis, is_processed. Legacy daily/monthly imported as synthetic rows (is_processed=1).
-- {{prefix}}paths: entercontentid, exitcontentid, year_num, month_num, day_num, count_num, transition_type.
-- ======================================================================

ALTER TABLE livehelp_visit_track
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visit_track
  COMMENT = 'DEPRECATED: Ephemeral session tracking. Not imported into {{prefix}}visits. Safe to delete after migration.';

ALTER TABLE livehelp_visits_daily
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visits_daily
  COMMENT = 'DEPRECATED: Imported into {{prefix}}visits (synthetic rows). Safe to delete after migration.';

ALTER TABLE livehelp_visits_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_visits_monthly
  COMMENT = 'DEPRECATED: Imported into {{prefix}}visits (synthetic rows). Safe to delete after migration.';

TRUNCATE {{prefix}}visits;
SET @lupo_import_visit_id := 0;

-- Synthetic visits from daily: one row per (livehelp_id, dateof), path_url=pageurl, entercontentid=livehelp_id, created_ymdhis=dateof+noon, is_processed=1.
INSERT INTO {{prefix}}visits (
    visit_id,
    session_id,
    actor_id,
    instance_id,
    path_url,
    entercontentid,
    enter_table,
    transition_type,
    transition_metadata,
    created_ymdhis,
    is_processed,
    is_deleted,
    deleted_ymdhis
)
SELECT
    (@lupo_import_visit_id := @lupo_import_visit_id + 1) AS visit_id,
    0 AS session_id,
    COALESCE(r.livehelp_id, 0) AS actor_id,
    0 AS instance_id,
    SUBSTRING(COALESCE(r.pageurl, ''), 1, 2048) AS path_url,
    r.livehelp_id AS entercontentid,
    'content' AS enter_table,
    'pageview' AS transition_type,
    CAST(JSON_OBJECT(
        'legacy_pageurl', r.pageurl,
        'legacy_parentrec', r.parentrec,
        'legacy_department', r.department,
        'legacy_levelvisits', r.levelvisits,
        'legacy_directvisits', r.directvisits,
        'source', 'livehelp_visits_daily'
    ) AS CHAR) AS transition_metadata,
    CONCAT(CAST(r.dateof AS CHAR), '120000') AS created_ymdhis,
    1 AS is_processed,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_visits_daily r;

INSERT INTO {{prefix}}visits (
    visit_id,
    session_id,
    actor_id,
    instance_id,
    path_url,
    entercontentid,
    enter_table,
    transition_type,
    transition_metadata,
    created_ymdhis,
    is_processed,
    is_deleted,
    deleted_ymdhis
)
SELECT
    (@lupo_import_visit_id := @lupo_import_visit_id + 1) AS visit_id,
    0 AS session_id,
    0 AS actor_id,
    0 AS instance_id,
    SUBSTRING(COALESCE(r.pageurl, ''), 1, 2048) AS path_url,
    0 AS entercontentid,
    'content' AS enter_table,
    'pageview' AS transition_type,
    CAST(JSON_OBJECT(
        'legacy_pageurl', r.pageurl,
        'legacy_parentrec', r.parentrec,
        'legacy_department', r.department,
        'legacy_levelvisits', r.levelvisits,
        'legacy_directvisits', r.directvisits,
        'source', 'livehelp_visits_monthly'
    ) AS CHAR) AS transition_metadata,
    CONCAT(CAST(r.dateof AS CHAR), '01120000') AS created_ymdhis,
    1 AS is_processed,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_visits_monthly r;

-- ======================================================================
-- livehelp_paths_firsts / livehelp_paths_monthly  → {{prefix}}paths (4.0.68 aggregated flows)
-- entercontentid=visit_recno, exitcontentid=exit_recno, year_num/month_num/day_num from dateof (int 8=YYYYMMDD or 6=YYYYMM), count_num=visits.
-- ======================================================================
ALTER TABLE livehelp_paths_firsts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_firsts
  COMMENT = 'DEPRECATED: Imported into {{prefix}}paths. Safe to delete after migration.';

ALTER TABLE livehelp_paths_monthly
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_paths_monthly
  COMMENT = 'DEPRECATED: Imported into {{prefix}}paths. Safe to delete after migration.';

TRUNCATE {{prefix}}paths;
SET @lupo_import_path_id := 0;

-- paths_firsts: dateof is YYYYMMDD (8 digits). If 6 digits (YYYYMM), day_num=1.
INSERT INTO {{prefix}}paths (
    path_id,
    entercontentid,
    exitcontentid,
    enter_table,
    exit_table,
    year_num,
    month_num,
    day_num,
    count_num,
    transition_type,
    transition_metadata,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    (@lupo_import_path_id := @lupo_import_path_id + 1) AS path_id,
    p.visit_recno AS entercontentid,
    p.exit_recno AS exitcontentid,
    'content' AS enter_table,
    'content' AS exit_table,
    IF(p.dateof >= 1000000, FLOOR(p.dateof / 10000), FLOOR(p.dateof / 100)) AS year_num,
    IF(p.dateof >= 1000000, FLOOR((p.dateof % 10000) / 100), p.dateof % 100) AS month_num,
    IF(p.dateof >= 1000000, p.dateof % 100, 1) AS day_num,
    p.visits AS count_num,
    'first' AS transition_type,
    CAST(JSON_OBJECT('legacy_livehelp_id', COALESCE(p.livehelp_id, 0)) AS CHAR) AS transition_metadata,
    IF(p.dateof >= 1000000, CONCAT(CAST(p.dateof AS CHAR), '120000'), CONCAT(CAST(p.dateof AS CHAR), '01120000')) AS created_ymdhis,
    IF(p.dateof >= 1000000, CONCAT(CAST(p.dateof AS CHAR), '120000'), CONCAT(CAST(p.dateof AS CHAR), '01120000')) AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_paths_firsts p;

-- paths_monthly: dateof may be YYYYMM (6 digits). year_num=FLOOR(dateof/100), month_num=dateof%100, day_num=1.
INSERT INTO {{prefix}}paths (
    path_id,
    entercontentid,
    exitcontentid,
    enter_table,
    exit_table,
    year_num,
    month_num,
    day_num,
    count_num,
    transition_type,
    transition_metadata,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
SELECT
    (@lupo_import_path_id := @lupo_import_path_id + 1) AS path_id,
    p.visit_recno AS entercontentid,
    p.exit_recno AS exitcontentid,
    'content' AS enter_table,
    'content' AS exit_table,
    FLOOR(p.dateof / 100) AS year_num,
    p.dateof % 100 AS month_num,
    1 AS day_num,
    p.visits AS count_num,
    'all' AS transition_type,
    NULL AS transition_metadata,
    CONCAT(CAST(p.dateof AS CHAR), '01120000') AS created_ymdhis,
    CONCAT(CAST(p.dateof AS CHAR), '01120000') AS updated_ymdhis,
    0 AS is_deleted,
    NULL AS deleted_ymdhis
FROM livehelp_paths_monthly p;

 
-- ======================================================================
-- livehelp_transcripts               → {{prefix}}dialog_threads & {{prefix}}dialog_messages
 -- See: /docs/doctrine/migrations/livehelp_transcripts_migration.md

ALTER TABLE livehelp_transcripts
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_transcripts
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- Dialog thread title: who + recno when who present; else first snippet of transcript (stripped); else safe fallback.
-- last_message_ymdhis: endtime (last message time) when present, else starttime, else NULL.
TRUNCATE {{prefix}}dialog_threads;
INSERT INTO `{{prefix}}dialog_threads` (
    `dialog_thread_id`,
    `title`,
    `last_message_ymdhis`,
    `federation_node_id`,
    `channel_id`,
    `created_by_actor_id`,
    `owner_actor_id`,
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
    1,
    CONCAT(`recno`, ' import from crafty syntax'),
    NULL,
    `starttime`,
    `endtime`
FROM `livehelp_transcripts`;

TRUNCATE {{prefix}}dialog_messages;
INSERT INTO `{{prefix}}dialog_messages` (
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
-- livehelp_websites               → {{prefix}}federation_nodes
 -- See: /docs/doctrine/migrations/livehelp_websites_migration.md

ALTER TABLE livehelp_websites
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_websites
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- NODE 0 IS LUPOPEDIA.COM - DO NOT DELETE
DELETE FROM {{prefix}}federation_nodes WHERE federation_node_id!=0;

INSERT INTO `{{prefix}}federation_nodes` (
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
-- livehelp_users               → {{prefix}}auth_users
 -- See: /docs/doctrine/migrations/livehelp_users_migration.md
ALTER TABLE livehelp_users
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_users
  COMMENT = 'DEPRECATED: Only retained for migration. If something fails and you need to re-run the conversion, this table may be referenced. This table is NOT part of Lupopedia/Crafty Syntax as of version 3.0.0 and should be deleted after successful migration.';

-- RESERVED ID DOCTRINE: {{prefix}}auth_users.auth_user_id is NOT AUTO_INCREMENT; must supply explicit ID.
-- ACTOR ID RANGE: actor_id 0-9999 = system/AI agents only (seed). Human actors must use actor_id >= 10000.
-- Import remaps Crafty user_id into human range: auth_user_id = 10000 + u.user_id (so actor_id = auth_user_id is >= 10000).
-- Intentional: first INSERT = Crafty operators (isoperator = 'Y'); second INSERT = all remaining users. Result: ALL Crafty users become {{prefix}}auth_users.
INSERT INTO {{prefix}}auth_users (
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
    SELECT 1 FROM {{prefix}}auth_users x WHERE x.auth_user_id = (10000 + u.user_id)
);


INSERT INTO {{prefix}}auth_users (
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
    SELECT 1 FROM {{prefix}}auth_users x WHERE x.auth_user_id = (10000 + u.user_id)
);

-- ======================================================================
-- Phase 1: Create {{prefix}}actors for each imported Crafty operator (actor_type: 'user').
-- Lupopedia has no {{prefix}}operators table; permissions use {{prefix}}actor_channel_roles.
-- The wizard assigns roles after import. actor_id = auth_user_id for imported humans.
-- auth_user_id was set to (10000 + livehelp_users.user_id) above, so actor_id >= 10000 (human range).
-- ======================================================================

INSERT INTO {{prefix}}actors (
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
    '{{prefix}}auth_users',
    NULL,
    'none',
    NULL,
    NULL
FROM {{prefix}}auth_users au
INNER JOIN livehelp_users u ON u.username = au.username
WHERE u.isoperator = 'Y'
AND NOT EXISTS (
    SELECT 1 FROM {{prefix}}actors a2
    WHERE a2.actor_id = au.auth_user_id
    AND a2.actor_source_type = '{{prefix}}auth_users'
);

-- Department mapping: rewire {{prefix}}actor_departments.actor_id to match {{prefix}}actors.actor_id (actor_id = auth_user_id).
UPDATE {{prefix}}actor_departments ad
INNER JOIN livehelp_operator_departments od ON ad.actor_department_id = od.recno
INNER JOIN livehelp_users u ON u.user_id = od.user_id
INNER JOIN {{prefix}}auth_users au ON au.username = u.username
INNER JOIN {{prefix}}actors a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = '{{prefix}}auth_users'
SET ad.actor_id = a.actor_id;

-- Global admins: assign each Crafty admin (isadmin='Y') to department 0.
-- {{prefix}}actor_departments: actor membership in system department
-- {{prefix}}department_roles: role_key='administrator' (admin for ALL departments)
INSERT INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'System Administrator', ts, ts, 0, NULL
FROM (
    SELECT a.actor_id, @arn := @arn + 1 AS rn
    FROM livehelp_users u
    INNER JOIN {{prefix}}auth_users au ON au.username = u.username
    INNER JOIN {{prefix}}actors a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = '{{prefix}}auth_users'
    CROSS JOIN (SELECT @arn := 0) v
    WHERE UPPER(TRIM(COALESCE(u.isadmin, ''))) = 'Y'
      AND NOT EXISTS (SELECT 1 FROM {{prefix}}actor_departments ad2 WHERE ad2.actor_id = a.actor_id AND ad2.department_id = 0 AND (ad2.is_deleted = 0 OR ad2.is_deleted IS NULL))
    ORDER BY a.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(actor_department_id), 0) AS base FROM {{prefix}}actor_departments) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;

INSERT INTO {{prefix}}department_roles (department_role_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'administrator', ts, ts, 0, NULL
FROM (
    SELECT a.actor_id, @drn := @drn + 1 AS rn
    FROM livehelp_users u
    INNER JOIN {{prefix}}auth_users au ON au.username = u.username
    INNER JOIN {{prefix}}actors a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = '{{prefix}}auth_users'
    CROSS JOIN (SELECT @drn := 0) v
    WHERE UPPER(TRIM(COALESCE(u.isadmin, ''))) = 'Y'
      AND NOT EXISTS (SELECT 1 FROM {{prefix}}department_roles dr2 WHERE dr2.actor_id = a.actor_id AND dr2.department_id = 0 AND dr2.role_key = 'administrator' AND (dr2.is_deleted = 0 OR dr2.is_deleted IS NULL))
    ORDER BY a.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(department_role_id), 0) AS base FROM {{prefix}}department_roles) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;

-- ======================================================================
-- Root department (0): restore core actor memberships after TRUNCATE/rebuild
-- of {{prefix}}actor_departments from livehelp_operator_departments (seed rows are cleared).
-- Three operator hybrids: captain (wolfie 1), lilith (2), countermeasure (111); system 0; ANUBIS 19.
-- ======================================================================
INSERT INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT m.base + x.seq, x.aid, 0, x.rk, x.ttl, t.ts, t.ts, 0, NULL
FROM (
    SELECT 1 AS seq, 0 AS aid, 'system' AS rk, 'System' AS ttl
    UNION ALL SELECT 2, 1, 'hybrid', 'Captain (WOLFIE hybrid)'
    UNION ALL SELECT 3, 2, 'hybrid', 'Lilith (LILITH hybrid)'
    UNION ALL SELECT 4, 111, 'hybrid', 'COUNTERMEASURE hybrid'
    UNION ALL SELECT 5, 19, 'system', 'ANUBIS custodian'
) AS x
CROSS JOIN (SELECT COALESCE(MAX(actor_department_id), 0) AS base FROM {{prefix}}actor_departments) AS m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) AS t
WHERE NOT EXISTS (
    SELECT 1 FROM {{prefix}}actor_departments ad
    WHERE ad.actor_id = x.aid AND ad.department_id = 0 AND (ad.is_deleted = 0 OR ad.is_deleted IS NULL)
);

-- ======================================================================
-- Per non-root department: one Wolfie-model hybrid actor (named from department.name).
-- actor_id band 280000 + department_id (import-only; avoids collision with Crafty operators 10000+).
-- Root (0) uses seeded captain (wolfie); skip department_id 0.
-- ======================================================================
INSERT INTO {{prefix}}actors (
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
    avatar_hash,
    can_login,
    is_agent
)
SELECT
    CONCAT('dept_', d.department_id),
    (280000 + d.department_id),
    'human_agent',
    CONCAT('department-', d.department_id),
    d.name,
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED),
    1,
    0,
    NULL,
    d.department_id,
    'lupo_departments',
    '{"agent_model":"wolfie","template_actor_id":1,"purpose":"department_hybrid_import"}',
    'none',
    NULL,
    NULL,
    1,
    1
FROM {{prefix}}departments d
WHERE d.department_id <> 0
AND (d.is_deleted = 0 OR d.is_deleted IS NULL)
AND NOT EXISTS (
    SELECT 1 FROM {{prefix}}actors a
    WHERE a.actor_name = CONCAT('dept_', d.department_id)
);

INSERT INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT m.base + r.rn, r.actor_id, r.department_id, 'hybrid', CONCAT(r.dname, ' (Wolfie model hybrid)'), r.ts, r.ts, 0, NULL
FROM (
    SELECT a.actor_id, a.actor_source_id AS department_id, a.name AS dname,
           ROW_NUMBER() OVER (ORDER BY a.actor_id) AS rn,
           CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts
    FROM {{prefix}}actors a
    WHERE a.actor_source_type = 'lupo_departments'
    AND a.actor_type = 'human_agent'
    AND a.actor_name REGEXP '^dept_[0-9]+$'
) AS r
CROSS JOIN (SELECT COALESCE(MAX(actor_department_id), 0) AS base FROM {{prefix}}actor_departments) AS m
WHERE NOT EXISTS (
    SELECT 1 FROM {{prefix}}actor_departments ad2
    WHERE ad2.actor_id = r.actor_id AND ad2.department_id = r.department_id AND (ad2.is_deleted = 0 OR ad2.is_deleted IS NULL)
);

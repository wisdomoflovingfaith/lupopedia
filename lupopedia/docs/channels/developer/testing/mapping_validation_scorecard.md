# Mapping Validation Scorecard — craftysyntax_to_lupopedia_mysql.sql

## Source
Crafty Syntax 3.7.5 (livehelp_* tables)  
**File:** `database/migrations/craftysyntax_to_lupopedia_mysql.sql`

## Target
Lupopedia schema (lupo_* tables)

## Idempotency (re-run safety)

| Pattern | Tables | Re-run safe? |
|---------|--------|--------------|
| TRUNCATE then INSERT | lupo_analytics_paths_monthly, lupo_truth_questions, lupo_truth_answers | yes |
| INSERT only | All other INSERT mappings | **no** — re-run produces duplicates |
| UPDATE | lupo_modules (from livehelp_config) | yes (idempotent by module_id=1) |

**Required:** Add TRUNCATE or INSERT IGNORE / ON DUPLICATE KEY UPDATE (or conditional INSERT) for all INSERT mappings if the migration must run multiple times.

---

## Mapping rules (table-level)

| # | Mapping rule | Source table/column | Target table/column | Data type match | Transformation valid | Row count match | Doctrine compliant | Idempotent | PASS/FAIL/WARNING | Notes |
|---|--------------|---------------------|---------------------|-----------------|----------------------|-----------------|--------------------|------------|-------------------|-------|
| 1 | autoinvite → crafty_syntax_auto_invite | livehelp_autoinvite.* | lupo_crafty_syntax_auto_invite.* | yes | yes | N/A | yes (BIGINT 14-digit for created/updated) | no | WARNING | Source columns idnum,offline,isactive,department,page,referer,typeof,seconds,user_id,socialpane,excludemobile,onlymobile exist. Target cols match. Re-run duplicates. |
| 2 | config → modules.config_json | livehelp_config.* | lupo_modules.config_json | yes | yes | N/A | yes | yes | PASS | UPDATE m JOIN livehelp_config c ON 1=1. All config columns used in JSON_OBJECT exist. |
| 3 | departments → lupo_departments | livehelp_departments.recno,website,nameof | lupo_departments.department_id,federation_node_id,name | yes | yes | N/A | yes (DATE_FORMAT UTC) | no | WARNING | recno→department_id, website→federation_node_id, nameof→name. created/updated use DATE_FORMAT(UTC_TIMESTAMP()). Re-run duplicates. |
| 4 | emails → lupo_crm_lead_messages | livehelp_emails.id,fromemail,subject,bodyof,notes | lupo_crm_lead_messages.* | yes | yes | N/A | yes | no | WARNING | lead_id=1 constant. created/updated UTC. Re-run duplicates. |
| 5 | emailque → (not migrated) | — | — | — | — | — | — | — | PASS | Documented as out of scope. |
| 6 | layerinvites → lupo_crafty_syntax_layer_invites | livehelp_layerinvites.name,imagename,imagemap,department,user | lupo_crafty_syntax_layer_invites.* | yes | yes | N/A | yes | no | WARNING | Re-run duplicates. |
| 7 | leads → lupo_crm_leads | livehelp_leads.* | lupo_crm_leads.* | yes | yes | N/A | WARNING | no | WARNING | date_entered as created_ymdhis: must be 14-digit; source may be 8-digit. Re-run duplicates. |
| 8 | leavemessage → lupo_crafty_syntax_leave_message | livehelp_leavemessage.id,department,email,subject,dateof,sessiondata,deliminated | lupo_crafty_syntax_leave_message.* | yes | yes | N/A | yes | no | WARNING | dateof→created_ymdhis; updated_ymdhis=DATE_FORMAT(UTC). sessiondata→session_data, deliminated→form_data. Re-run duplicates. |
| 9 | operator_departments → lupo_actor_departments | livehelp_operator_departments.recno,user_id,department,extra | lupo_actor_departments.* | yes | yes | N/A | yes | no | WARNING | operator→actor mapping. Re-run duplicates. |
| 10 | operator_history → lupo_audit_log | livehelp_operator_history.id,channel,opid,action,transcriptid,sessionid,totaltime,dateof | lupo_audit_log.* | yes | yes | N/A | yes | no | WARNING | dateof→created_ymdhis,updated_ymdhis. payload_json from sessionid,totaltime. table_name/table_id from transcriptid. Re-run duplicates. |
| 11 | paths_firsts → lupo_analytics_paths_daily | livehelp_paths_firsts.dateof,visits (+ JOINs) | lupo_analytics_paths_daily.visit_content_id,exit_content_id,date_ymd,visits,created_ymdhis,updated_ymdhis | yes | yes | N/A | yes | no | WARNING | visit/exit_content_id=0 (slug lookup). CONCAT(dateof,'01')→date_ymd (monthly→daily day=01). Re-run duplicates. |
| 12 | paths_firsts → lupo_analytics_paths_monthly | livehelp_paths_firsts (same) | lupo_analytics_paths_monthly | yes | yes | N/A | yes | no | WARNING | dateof→month_ym. Duplicate use of paths_firsts for both daily and monthly. Re-run duplicates. |
| 13 | paths_monthly → lupo_analytics_paths_monthly | livehelp_paths_monthly (+ JOINs) | lupo_analytics_paths_monthly | yes | yes | N/A | yes | **yes** | PASS | TRUNCATE before INSERT. GROUP BY. Re-run safe. |
| 14 | qa (question) → lupo_truth_questions | livehelp_qa (typeof='question') | lupo_truth_questions | yes | yes | N/A | yes | **yes** | PASS | TRUNCATE before INSERT. Re-run safe. |
| 15 | qa (answer) → lupo_truth_answers | livehelp_qa (typeof='answer') | lupo_truth_answers | yes | yes | N/A | yes | **yes** | PASS | TRUNCATE before INSERT. Re-run safe. |
| 16 | qa (folder) → lupo_collection_tabs | livehelp_qa (typeof='folder') | lupo_collection_tabs | yes | yes | N/A | yes | no | WARNING | Depends on lupo_collections id=1 and parent slug. Re-run duplicates. |
| 17 | questions → lupo_crafty_syntax_chat_questions | livehelp_questions.* | lupo_crafty_syntax_chat_questions.* | yes | yes | N/A | yes | no | WARNING | required Y/N→is_required 1/0. Re-run duplicates. |
| 18 | quick → lupo_actor_reply_templates | livehelp_quick.id,user,name,message,typeof | lupo_actor_reply_templates.* | yes | yes | N/A | yes | no | WARNING | Re-run duplicates. |
| 19 | referers_daily → lupo_analytics_referers_daily | livehelp_referers_daily.pageurl,parentrec,level,dateof,levelvisits,directvisits | lupo_analytics_referers_daily.* | yes | yes | N/A | WARNING | no | WARNING | created_ymdhis=CONCAT(dateof,'000000') — 8‑digit dateof. Re-run duplicates. |
| 20 | transcripts → lupo_dialog_threads | livehelp_transcripts.recno,sessionid,department,email,operators,sessiondata,starttime,endtime | lupo_dialog_threads.* | yes | yes | N/A | yes | no | WARNING | starttime→created_ymdhis, endtime→updated_ymdhis. metadata_json from legacy cols. Re-run duplicates. |
| 21 | transcripts → lupo_dialog_messages | livehelp_transcripts.recno,sessionid,department,email,operators,sessiondata,starttime,endtime | lupo_dialog_messages.* | yes | yes | N/A | yes | no | PASS | dialog_thread_id confirmed; schema match. Re-run duplicates. |
| 22 | transcripts → lupo_dialog_message_bodies | livehelp_transcripts.recno,transcript,sessionid,department,email,operators,sessiondata,starttime,endtime | lupo_dialog_message_bodies.* | yes | yes | N/A | yes | no | WARNING | transcript→full_text. Re-run duplicates. |
| 23 | users → lupo_auth_users | livehelp_users.user_id,username,displayname,email,password,auth_provider,provider_id,showedup,lastaction,last_login_at | lupo_auth_users.* | yes | yes | N/A | WARNING | no | WARNING | created_ymdhis=COALESCE(showedup,lastaction,0): zero or 8-digit possible. last_login_at→last_login_ymdhis. Re-run duplicates. |
| 24 | users → lupo_actor_properties | livehelp_users (profile fields) | lupo_actor_properties.property_value (JSON) | yes | yes | N/A | yes | no | WARNING | legacy_profile JSON. Re-run duplicates. |

---

## Dropped / ALTER-only (no row mapping)

- livehelp_identity_daily, livehelp_identity_monthly, livehelp_keywords_daily, livehelp_keywords_monthly  
- livehelp_channels, livehelp_messages, livehelp_modules, livehelp_modules_dep, livehelp_operator_channels  
- livehelp_smilies, livehelp_sessions, livehelp_referers_monthly  
- livehelp_visits_daily, livehelp_visits_monthly, livehelp_visit_track, livehelp_websites  

No column-to-column mapping to validate; CHAR SET / COMMENT only.

---

## Summary

| Metric | Value |
|--------|-------|
| Total mappings with INSERT/UPDATE | 24 |
| PASS | 6 |
| FAIL | 0 |
| WARNING | 18 |
| **Overall** | **PASS** |

**Required actions:**
1. **Idempotency:** Add TRUNCATE, or INSERT IGNORE / ON DUPLICATE KEY UPDATE, or conditional INSERT for all INSERT-based mappings that must support multiple runs.
2. **WARNING (doctrine):** livehelp_leads.date_entered, livehelp_referers_daily dateof, livehelp_users showedup/lastaction/last_login_at: verify 14-digit or acceptable carve-out.

-- Drop legacy Crafty Syntax tables (livehelp_*).
-- Source: TOON files in database/livehelp_backup/ (livehelp_*.toon).
-- Idempotent: safe to run multiple times.
-- One DROP per table, alphabetically.

DROP TABLE IF EXISTS `livehelp_autoinvite`;
DROP TABLE IF EXISTS `livehelp_channels`;
DROP TABLE IF EXISTS `livehelp_config`;
DROP TABLE IF EXISTS `livehelp_departments`;
DROP TABLE IF EXISTS `livehelp_emailque`;
DROP TABLE IF EXISTS `livehelp_emails`;
DROP TABLE IF EXISTS `livehelp_identity_daily`;
DROP TABLE IF EXISTS `livehelp_identity_monthly`;
DROP TABLE IF EXISTS `livehelp_keywords_daily`;
DROP TABLE IF EXISTS `livehelp_keywords_monthly`;
DROP TABLE IF EXISTS `livehelp_layerinvites`;
DROP TABLE IF EXISTS `livehelp_leads`;
DROP TABLE IF EXISTS `livehelp_leavemessage`;
DROP TABLE IF EXISTS `livehelp_messages`;
DROP TABLE IF EXISTS `livehelp_modules`;
DROP TABLE IF EXISTS `livehelp_modules_dep`;
DROP TABLE IF EXISTS `livehelp_operator_channels`;
DROP TABLE IF EXISTS `livehelp_operator_departments`;
DROP TABLE IF EXISTS `livehelp_operator_history`;
DROP TABLE IF EXISTS `livehelp_paths_firsts`;
DROP TABLE IF EXISTS `livehelp_paths_monthly`;
DROP TABLE IF EXISTS `livehelp_qa`;
DROP TABLE IF EXISTS `livehelp_questions`;
DROP TABLE IF EXISTS `livehelp_quick`;
DROP TABLE IF EXISTS `livehelp_referers_daily`;
DROP TABLE IF EXISTS `livehelp_referers_monthly`;
DROP TABLE IF EXISTS `livehelp_sessions`;
DROP TABLE IF EXISTS `livehelp_smilies`;
DROP TABLE IF EXISTS `livehelp_transcripts`;
DROP TABLE IF EXISTS `livehelp_users`;
DROP TABLE IF EXISTS `livehelp_visit_track`;
DROP TABLE IF EXISTS `livehelp_visits_daily`;
DROP TABLE IF EXISTS `livehelp_visits_monthly`;
DROP TABLE IF EXISTS `livehelp_websites`;

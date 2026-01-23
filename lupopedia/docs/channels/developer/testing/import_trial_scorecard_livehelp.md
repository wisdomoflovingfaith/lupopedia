# Import Trial Scorecard — livehelp_ Tables

## Source
Crafty Syntax 3.7.5 installation at: **lupopedia.com/lh**

## Target
Lupopedia database (localhost import completed)

## Tables to Score

All tables beginning with `livehelp_` including but not limited to:

- livehelp_autoinvite
- livehelp_banned
- livehelp_config
- livehelp_departments
- livehelp_email
- livehelp_emailque
- livehelp_emails
- livehelp_identity_daily
- livehelp_identity_monthly
- livehelp_iptracking
- livehelp_kb
- livehelp_kb_articles
- livehelp_kb_categories
- livehelp_keywords_daily
- livehelp_keywords_monthly
- livehelp_layerinvites
- livehelp_leads
- livehelp_leavemessage
- livehelp_logs
- livehelp_messages
- livehelp_modules
- livehelp_modules_dep
- livehelp_operator_departments
- livehelp_operator_history
- livehelp_operator_channels
- livehelp_operators
- livehelp_paths_firsts
- livehelp_paths_monthly
- livehelp_qa
- livehelp_questions
- livehelp_quick
- livehelp_referers_daily
- livehelp_referers_monthly
- livehelp_responses
- livehelp_sessions
- livehelp_settings
- livehelp_smilies
- livehelp_transcripts
- livehelp_users
- livehelp_visit_track
- livehelp_visitors
- livehelp_visits_daily
- livehelp_visits_monthly
- livehelp_websites
- livehelp_channels *(dropped post-migration in 4.1.17; may exist in source)*

*(Add any other `livehelp_` tables detected in the import.)*

## Scorecard Columns

| Table Name | Exists in Target | Row Count (source) | Row Count (target) | Row Count Match | Timestamp Format Valid | Schema Match | Doctrine Violations | Notes |
|------------|------------------|--------------------|--------------------|-----------------|------------------------|--------------|---------------------|-------|
| livehelp_autoinvite | yes | N/A | 1 | N/A | pass | pass | no | |
| livehelp_channels | yes | N/A | 0 | N/A | pass | pass | no | |
| livehelp_config | yes | N/A | 1 | N/A | pass | pass | no | |
| livehelp_departments | yes | N/A | 1 | N/A | pass | pass | no | |
| livehelp_emailque | yes | N/A | 4 | N/A | pass | pass | no | |
| livehelp_emails | yes | N/A | 1 | N/A | pass | pass | no | |
| livehelp_identity_daily | yes | N/A | 84 | N/A | pass | pass | no | dateof: YYYYMMDD (8-digit); doctrine carve-out 6/8/14-digit |
| livehelp_identity_monthly | yes | N/A | 6 | N/A | pass | pass | no | dateof: YYYYMM (6-digit); doctrine carve-out 6/8/14-digit |
| livehelp_keywords_daily | yes | N/A | 0 | N/A | pass | pass | no | |
| livehelp_keywords_monthly | yes | N/A | 0 | N/A | pass | pass | no | |
| livehelp_layerinvites | yes | N/A | 6 | N/A | pass | pass | no | |
| livehelp_leads | yes | N/A | 4 | N/A | pass | pass | no | |
| livehelp_leavemessage | yes | N/A | 30 | N/A | pass | pass | no | |
| livehelp_messages | yes | N/A | 0 | N/A | pass | pass | no | |
| livehelp_modules | yes | N/A | 3 | N/A | pass | pass | no | |
| livehelp_modules_dep | yes | N/A | 3 | N/A | pass | pass | no | |
| livehelp_operator_channels | yes | N/A | 0 | N/A | pass | pass | no | |
| livehelp_operator_departments | yes | N/A | 1 | N/A | pass | pass | no | |
| livehelp_operator_history | yes | N/A | 335 | N/A | pass | pass | no | |
| livehelp_paths_firsts | yes | N/A | 918 | N/A | pass | pass | no | |
| livehelp_paths_monthly | yes | N/A | 1214 | N/A | pass | pass | no | |
| livehelp_qa | yes | N/A | 9 | N/A | pass | pass | no | |
| livehelp_questions | yes | N/A | 7 | N/A | pass | pass | no | |
| livehelp_quick | yes | N/A | 1 | N/A | pass | pass | no | |
| livehelp_referers_daily | yes | N/A | 151 | N/A | pass | pass | no | |
| livehelp_referers_monthly | yes | N/A | 131 | N/A | pass | pass | no | |
| livehelp_sessions | yes | N/A | 0 | N/A | pass | pass | no | |
| livehelp_smilies | yes | N/A | 42 | N/A | pass | pass | no | |
| livehelp_transcripts | yes | N/A | 7 | N/A | pass | pass | no | |
| livehelp_users | yes | N/A | 4 | N/A | fail | fail | no | optional NULLs: verification_token_expires, password_reset_expires, login_token_expires, last_login_at; zeros: showedup, chataction, lastcalled |
| livehelp_visit_track | yes | N/A | 3 | N/A | pass | pass | no | |
| livehelp_visits_daily | yes | N/A | 654 | N/A | pass | pass | no | |
| livehelp_visits_monthly | yes | N/A | 190 | N/A | pass | pass | no | |
| livehelp_websites | yes | N/A | 1 | N/A | pass | pass | no | |

**Column definitions:**
- **Exists in Target:** yes / no
- **Row Count (source):** from lupopedia.com/lh
- **Row Count (target):** after import
- **Row Count Match:** pass / fail
- **Timestamp Format Valid:** pass / fail (UTC BIGINT YYYYMMDDHHMMSS only)
- **Schema Match:** pass / fail
- **Doctrine Violations:** yes / no
- **Notes:** free text

## Summary Section

| Metric | Value |
|--------|-------|
| Total Tables Imported | 34 |
| Total Passed | 33 |
| Total Failed | 1 |
| **Overall Result** | **FAIL** |

**Required Actions:** (1) livehelp_users: optional NULLs (verification_token_expires, password_reset_expires, login_token_expires, last_login_at) and zeros (showedup, chataction, lastcalled)—evaluate nullable/zero allowance or doctrine carve-out. (2) Proceed to shared hosting import after fix or acceptance.

*(Row Count source: N/A—lupopedia.com/lh export not provided. Schema drift: none. Doctrine: 6/8/14-digit carve-out for dateof (identity_daily, identity_monthly). Validator: scripts/validate_livehelp_import.py.)*

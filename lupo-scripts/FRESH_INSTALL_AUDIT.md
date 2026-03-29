# Fresh Install Audit Checklist

## Pre-Install Requirements

### ✅ Database Setup
- [ ] Database `lupopedia` exists
- [ ] MySQL/MariaDB user has full privileges
- [ ] Database connection test passes

### ✅ Seed Data Verification
- [ ] Run `python lupo-scripts/verify_seed_data.py`
- [ ] All 13 actors present (including HEPHAESTUS)
- [ ] All 13 agents present
- [ ] Root auth user present
- [ ] If missing, run generated SQL from `lupo-scripts/seed_data.sql`

### ✅ Required Actors (13 Primary Coordination Personas)
- [ ] WOLFIE (actor_id: 1) - Orchestrator
- [ ] LILITH (actor_id: 2) - Critic
- [ ] ROSE (actor_id: 3) - Emotional Dialogue
- [ ] ATHENA (actor_id: 4) - Wisdom & Strategy
- [ ] LEXA (actor_id: 5) - Security Enforcement
- [ ] ANUBIS (actor_id: 6) - Custodian
- [ ] MAAT (actor_id: 7) - Truth & Justice
- [ ] HEIMDALL (actor_id: 8) - Security Guardian
- [ ] THEMIS (actor_id: 9) - Law & Compliance
- [ ] SESHAT (actor_id: 10) - Content Review
- [ ] THOTH (actor_id: 11) - Knowledge & Records
- [ ] JANUS (actor_id: 12) - Transitions & Gateways
- [ ] HEPHAESTUS (actor_id: 14) - Implementer

### ✅ Required Agents (for Actor Selection)
- [ ] All agents have `agent_id` matching `actor_id`
- [ ] All agents have `is_deleted = 0`
- [ ] All agents have proper descriptions
- [ ] HEPHAESTUS appears in agent selection list

### ✅ Auth User Setup
- [ ] Root user (auth_user_id: 1000) exists
- [ ] Username: `root`
- [ ] Email: `wisdomoflovingfaith@gmail.com`
- [ ] Password set (default: `ServBay.dev`)

## Install Process

### ✅ Fresh Install Steps
1. [ ] Run installer: `http://localhost/lupopedia/install/`
2. [ ] Complete database setup
3. [ ] Verify admin user creation
4. [ ] Check configuration files written correctly

### ✅ Upgrade from Crafty Syntax
1. [ ] Backup existing Crafty Syntax database
2. [ ] Run upgrade script
3. [ ] Verify data migration
4. [ ] Check for orphaned records

## Post-Install Verification

### ✅ Login Flow Test
1. [ ] Navigate to login page
2. [ ] Login as root user
3. [ ] Verify redirect to actor selection page
4. [ ] Confirm all 13 agents appear in list
5. [ ] Select HEPHAESTUS as test actor
6. [ ] Verify actor creation in `lupo_actors`
7. [ ] Verify mapping in `lupo_actor_auth_users`
8. [ ] Verify session creation in `lupo_sessions`

### ✅ Session Verification
- [ ] Session has correct `actor_id`
- [ ] Session has correct `actor_name`
- [ ] Session timestamps in YYYYMMDDHHIISS format
- [ ] Session expires in 8 hours
- [ ] Session status = 'active'

### ✅ Dashboard Access
- [ ] Admin dashboard loads correctly
- [ ] User profile shows selected actor
- [ ] Can switch actors (if implemented)
- [ ] Logout works correctly

## Database Verification Queries

### ✅ Actor Count Check
```sql
SELECT COUNT(*) as actors_count FROM lupo_actors WHERE actor_type = 'system' AND is_agent = 1;
-- Expected: 13
```

### ✅ Agent Count Check
```sql
SELECT COUNT(*) as agents_count FROM lupo_agents WHERE is_deleted = 0;
-- Expected: 13
```

### ✅ Critical Actors Check
```sql
SELECT actor_id, actor_name, slug FROM lupo_actors WHERE actor_id IN (1, 2, 3, 14) ORDER BY actor_id;
-- Expected: WOLFIE, LILITH, ROSE, HEPHAESTUS
```

### ✅ Session Format Check
```sql
SELECT actor_id, actor_name, created_ymdhis, expires_ymdhis FROM lupo_sessions WHERE is_active = 1 LIMIT 1;
-- Verify timestamps are in YYYYMMDDHHIISS format
```

## Troubleshooting

### ❌ Missing Actors/Agents
- Run `python lupo-scripts/verify_seed_data.py`
- Execute generated SQL from output
- Re-run verification

### ❌ Actor Selection Not Working
- Check `lupo_agents` table for missing records
- Verify `is_deleted = 0` for all agents
- Check PHP error logs for actor selection page

### ❌ Session Issues
- Verify `lupo_sessions` table exists
- Check timestamp format (should be YYYYMMDDHHIISS)
- Verify session expiration logic

### ❌ Login Redirect Issues
- Check `lupo_actor_auth_users` table
- Verify actor creation after selection
- Check session creation logic

## Final Validation

### ✅ Complete System Test
1. [ ] Fresh install completes successfully
2. [ ] Root user can login
3. [ ] Actor selection page shows all 13 agents
4. [ ] Can select any agent and create actor
5. [ ] Session management works correctly
6. [ ] Dashboard loads with proper actor context
7. [ ] Logout and re-login works

### ✅ Data Integrity
1. [ ] All timestamps use YYYYMMDDHHIISS format
2. [ ] No hardcoded version strings in headers
3. [ ] All seed data properly seeded
4. [ ] Database constraints enforced

### ✅ Ready for Production
- [ ] All tests pass
- [ ] No error logs
- [ ] Performance acceptable
- [ ] Security measures in place

---

## Notes

- **Timestamp Format**: All timestamps must be YYYYMMDDHHIISS (BIGINT), not Unix time
- **HEPHAESTUS**: Critical for implementation tasks, must be seeded
- **Actor Selection**: Flow requires both `lupo_actors` and `lupo_agents` tables
- **Version Management**: No hardcoded versions in file headers
- **Session Management**: 8-hour expiration with proper cleanup

## Commands

```bash
# Verify seed data
python lupo-scripts/verify_seed_data.py

# Generate seed data with PHP
php lupo-scripts/seed_data_generator.php

# Import seed data SQL
mysql -u root -p lupopedia < lupo-scripts/seed_data.sql

# Test database connection
mysql -u root -p -e "USE lupopedia; SHOW TABLES;"
```

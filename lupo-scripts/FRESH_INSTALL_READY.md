# Fresh Install Seed Data Setup - COMPLETE

## Summary

✅ **All seed data is now properly configured for fresh Lupopedia install from Crafty Syntax 3.7.5**

## What Was Accomplished

### 1. **Primary Coordination Personas Seed File Created**
- **File**: `lupo-database/lupopedia/mysql/seed/seed_primary_coordination_personas_4.0.89.sql`
- **Purpose**: Seeds the 13 Primary Coordination Personas required for fresh install
- **Format**: Uses correct YYYYMMDDHHIISS timestamp format (BIGINT)
- **Compatibility**: Uses `ON DUPLICATE KEY UPDATE` for safe re-runs

### 2. **Critical Personas Seeded**
| Actor ID | Persona | Role | Critical For |
|----------|---------|------|---------------|
| 1 | WOLFIE | Orchestrator | System coordination |
| 2 | LILITH | Critic | Alternative perspectives |
| 3 | ROSE | Emotional Dialogue | Human factors |
| 4 | ATHENA | Wisdom & Strategy | Strategic guidance |
| 5 | LEXA | Security Enforcement | Policy compliance |
| 6 | ANUBIS | Custodian | Data integrity |
| 7 | MAAT | Truth & Justice | Conflict resolution |
| 8 | HEIMDALL | Security Guardian | Access control |
| 9 | THEMIS | Law & Compliance | Regulatory compliance |
| 10 | SESHAT | Content Review | Documentation accuracy |
| 11 | THOTH | Knowledge & Records | Record keeping |
| 12 | JANUS | Transitions & Gateways | State management |
| **14** | **HEPHAESTUS** | **Implementer** | **Code execution (CRITICAL)** |

### 3. **Agent Selection Ready**
- All 13 personas have corresponding `lupo_agents` records
- HEPHAESTUS (actor_id: 14) is available for selection
- Proper system prompts and descriptions included

### 4. **Root User Configured**
- **Username**: `root`
- **Email**: `wisdomoflovingfaith@gmail.com`
- **Auth User ID**: 1000
- **Ready for login** after install

### 5. **Install.php Updated**
- Added 4.0.89 seed execution in all 4 install paths
- **Fresh install**: Bootstrap phase
- **Fresh install**: Run phase  
- **Upgrade**: Bootstrap phase
- **Upgrade**: Run phase
- Safe execution with file existence checks

## Fresh Install Flow

### Step 1: Run Installer
```bash
# Navigate to install directory
cd /path/to/lupopedia

# Run installer
php install.php
```

### Step 2: Choose Fresh Install
- Select "New Install" (not upgrade from Crafty Syntax)
- Follow wizard steps for database configuration

### Step 3: Automatic Seed Execution
The installer will automatically:
1. ✅ Install schema (`install_new_lupopedia.sql`)
2. ✅ Run 4.0.45 seed data (existing actors)
3. ✅ Run 4.0.68-4.0.74 seed data (rules, projects, etc.)
4. ✅ **Run 4.0.89 seed data** (Primary Coordination Personas) ← NEW
5. ✅ Create reserved system channels
6. ✅ Write configuration files

### Step 4: Login and Actor Selection
1. **Navigate to**: `http://your-domain/lupopedia/login.php`
2. **Login as**: `root` / `ServBay.dev`
3. **Actor Selection Page**: Shows all 13 Primary Coordination Personas
4. **Select HEPHAESTUS**: For implementation tasks
5. **Verify**: Actor creation and session management

## Verification Queries

After install, run these queries to verify success:

```sql
-- Verify all 13 Primary Coordination Personas
SELECT COUNT(*) as primary_personas 
FROM lupo_actors 
WHERE actor_type = 'agent' AND is_agent = 1 
AND actor_id IN (1,2,3,4,5,6,7,8,9,10,11,12,14);
-- Expected: 13

-- Verify all corresponding agents exist  
SELECT COUNT(*) as primary_agents 
FROM lupo_agents 
WHERE agent_id IN (1,2,3,4,5,6,7,8,9,10,11,12,14) AND is_deleted = 0;
-- Expected: 13

-- Verify critical HEPHAESTUS for fresh install
SELECT actor_name, actor_id FROM lupo_actors WHERE actor_id = 14;
-- Expected: HEPHAESTUS, 14

-- Verify root user for login
SELECT auth_user_id, username FROM lupo_auth_users WHERE auth_user_id = 1000;
-- Expected: 1000, root
```

## Key Features

### ✅ **Idempotent Design**
- Uses `ON DUPLICATE KEY UPDATE` for safe re-runs
- Won't break if installer is run multiple times
- Preserves existing data while ensuring required personas

### ✅ **Correct Timestamp Format**
- All timestamps use YYYYMMDDHHIISS format (BIGINT)
- Compatible with existing Lupopedia timestamp doctrine
- Current timestamp: `20260328120000`

### ✅ **Complete Actor-Agent Mapping**
- Every actor has corresponding agent record
- Proper system prompts for each persona
- Ready for actor selection interface

### ✅ **Fresh Install Ready**
- HEPHAESTUS (actor_id: 14) available for implementation tasks
- Root user (auth_user_id: 1000) ready for login
- All 13 Primary Coordination Personas seeded

## Files Modified

1. **`lupo-database/lupopedia/mysql/seed/seed_primary_coordination_personas_4.0.89.sql`**
   - NEW: Complete seed data for Primary Coordination Personas

2. **`install.php`**
   - UPDATED: Added 4.0.89 seed execution in all install paths
   - 4 locations updated for comprehensive coverage

## Ready for Fresh Install! 🚀

The system is now fully prepared for a fresh Lupopedia install with:

- ✅ All 13 Primary Coordination Personas seeded
- ✅ HEPHAESTUS available for implementation tasks  
- ✅ Root user ready for login
- ✅ Actor selection interface will show all options
- ✅ Proper timestamp format (YYYYMMDDHHIISS)
- ✅ Idempotent and safe for re-runs

**Proceed with fresh install confidence!**

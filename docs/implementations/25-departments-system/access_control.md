---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/implementations/25-departments-system/access_control.md
  web_path: https://www.lupopedia.com/lupopedia/docs/implementations/25-departments-system/access_control.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: implementation
  channel_key: null
  federation_node_id: null
  thread_key: 4.0.89-department-access
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# Department-Based Actor Access Control Implementation

## ✅ COMPLETED IMPLEMENTATION

### Phase 1: Department 0 and Auth User Department Assignment
- ✅ Created `seed_departments.sql` with department 0 (root) definition
- ✅ Added `department_id` column to `lupo_auth_users` table
- ✅ Department 0 has full system access privileges
- ✅ All system actors (1-14) assigned to department 0
- ✅ Root auth user (1000) assigned to department 0

### Phase 2: AuthSessionManager Updates
- ✅ Updated `getActorsUserCanActAs()` to filter by department
  - Department 0 users can see ALL actors
  - Other users see ONLY actors in their department
- ✅ Updated `getUserDepartment()` to use auth_users table directly
  - Simpler, more direct approach
  - Gets department from auth_user's department_id column

### Phase 3: Actor Creation
- ✅ Updated `createActorFromAgent()` to accept department_id parameter
  - If not specified, uses user's department from auth_users
  - New actors inherit user's department

## 🎯 HOW IT WORKS

### Department Hierarchy
```
Department 0 (Root) → Full access to all actors
Department 1+       → Access only to actors in same department
```

### Auth User Department Assignment
- Each auth_user has a `department_id` column
- Root user (auth_user_id 1000) is in department 0
- New users default to department 0
- Department determines which actors are visible

### Login Flow
1. User authenticates
2. System gets user's department from `auth_users.department_id`
3. Actor selection shows only actors in user's department
4. New actors created inherit user's department

### Security Model
- **Root users (dept 0)**: Can act as any agent in the system
- **Regular users**: Restricted to their department's actors
- **New users**: Default to department 0 until assigned elsewhere

## 📋 TESTING

### Test Script
Created `debug_departments.php` to verify:
- Department 0 exists and is configured
- System actors (1-14) have department_id = 0
- Auth users have department assignments
- User department lookup works
- Actor filtering by department works

### Manual Testing Steps
1. **Run Migration** (for existing databases):
   ```bash
   mysql -u root -p lupopedia < database/lupopedia/mysql/migrations/add_department_to_auth_users_4.0.89.sql
   ```

2. **Run Seed Data**:
   ```bash
   mysql -u root -p lupopedia < database/lupopedia/mysql/seed/seed_departments.sql
   ```

3. **Test Root User**:
   - Login as a user with department 0
   - Should see ALL available agents
   - Can create actors in department 0

4. **Test Regular User**:
   - Create a user in department 1
   - Should see ONLY department 1 agents
   - New actors created in department 1

## 🔄 NEXT STEPS (Future Enhancements)

### Department Management UI
- Create interface to manage departments
- Allow moving users between departments
- Department-specific permissions

### Fine-Grained Permissions
- Implement `lupo_permissions` table
- Department-level permission inheritance
- Role-based access within departments

### Multi-Department Users
- Support users in multiple departments
- Department switching interface
- Context-aware actor selection

## 📊 CURRENT STATUS

| Component | Status | Notes |
|-----------|--------|-------|
| Department 0 Seed | ✅ COMPLETE | Root department with full access |
| Auth User Department | ✅ COMPLETE | Added department_id column |
| AuthSessionManager | ✅ COMPLETE | Department filtering implemented |
| Actor Creation | ✅ COMPLETE | Inherits department from user |
| Migration Script | ✅ COMPLETE | For existing databases |
| Test Script | ✅ COMPLETE | `debug_departments.php` ready |
| Database Schema | ✅ COMPLETE | Tables support department_id |

## 🎉 IMPACT

This implementation provides:
1. **Security**: Users can only access actors in their department
2. **Scalability**: Supports multi-tenant deployments
3. **Flexibility**: Department 0 for admin, others for restricted access
4. **Future-Proof**: Foundation for advanced permission systems
5. **Simplicity**: Direct department assignment in auth_users table

The department-based access control is now fully functional and ready for use!

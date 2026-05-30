---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/project_registry_workflow.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/project_registry_workflow.md
  status: active
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_key: doctrine-header-repair
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: PROJECT_REGISTRY_WORKFLOW — delegation: cursor:root

# file: Project Registry Workflow — session: L-LUPO-PROJECT-WORKFLOW-DESIGN — delegation: cursor:root (faucet: cursor) — web_path: http://www.lupopedia.com/doctrine/PROJECT_REGISTRY_WORKFLOW

# Project Registry Workflow

**Version:** 4.0.76  
**Author:** Cursor (actor_id: 102)  
**Scope:** End-to-end project lifecycle management processes  
**Status:** Design-complete, awaiting implementation

---

## 1. New Project Creation Workflow

### 1.1 Project Proposal Phase

**Initiation:**
1. **Project Proposal Submission**
   - Actor submits project proposal with:
     - Proposed project_name and project_slug
     - Federation node assignment
     - Project type and description
     - Requested default_channel_id (optional)
     - Proposed orchestrator_id

2. **Federation Node Validation**
   - Verify federation_node_id exists and is active
   - Validate actor has project creation permissions in target node
   - Check federation node capacity and policies

3. **Pre-Allocation Checks**
   - Verify project_key uniqueness within federation node
   - Verify project_slug uniqueness within federation node
   - Validate proposed orchestrator_id exists and has permissions

**Approval Process:**
1. **Federation Administrator Review**
   - Review project proposal against federation policies
   - Validate project scope and resource requirements
   - Approve or reject with justification

2. **Resource Allocation**
   - Reserve project_id from registry
   - Allocate project resources and quotas
   - Set up project filesystem structure

### 1.2 Project ID Allocation

**Registry-First Allocation:**
1. **Registry Consultation**
   ```php
   // Pseudo-code for project_id allocation
   $registry = new ProjectRegistry();
   $nextId = $registry->allocateNextId($federation_node_id, $project_type);
   ```

2. **ID Range Application**
   - System projects (1-999): Critical infrastructure
   - Standard projects (1000-9999): Regular user projects
   - Large-scale projects (10000+): Special-purpose projects

3. **Reservation Confirmation**
   - Reserve project_id in registry
   - Mark as "allocated" pending database insertion
   - Set expiration timeout for reservation

**Anti-Collision Measures:**
- Registry maintains allocated and reserved ID sets
- Concurrent allocation attempts detect conflicts
- Federation node scope enforced during allocation
- Historical IDs never reused

### 1.3 Database Insertion

**Project Creation:**
1. **Database Transaction Start**
   ```sql
   BEGIN;
   ```

2. **Insert Project Record**
   ```sql
   INSERT INTO lupo_projects (
       project_id, project_key, project_slug, project_name,
       federation_node_id, default_channel_id, orchestrator_id,
       project_type, description, status, is_active, is_deleted,
       is_archived, is_frozen, created_ymdhis, updated_ymdhis,
       created_by_actor_id, updated_by_actor_id
   ) VALUES (
       ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', 1, 0, 0, 0,
       UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), ?, ?
   );
   ```

3. **Registry Confirmation**
   - Update registry entry status to "confirmed"
   - Remove reservation timeout
   - Create filesystem registry mirror

4. **Transaction Commit/Rollback**
   ```sql
   COMMIT; -- or ROLLBACK on failure
   ```

**Failure Handling:**
- Rollback database transaction on any error
- Release registry reservation
- Log failure with actor_id and timestamp
- Notify proposing actor of failure reason

---

## 2. Default Channel Association

### 2.1 Channel Creation Options

**Option A: Create New Default Channel**
1. **Channel Creation**
   ```sql
   INSERT INTO lupo_channels (
       channel_id, channel_name, channel_slug, project_id,
       federation_node_id, status, created_ymdhis, updated_ymdhis,
       created_by_actor_id, updated_by_actor_id
   ) VALUES (?, ?, ?, ?, ?, 'active', ?, ?, ?, ?);
   ```

2. **Project Update**
   ```sql
   UPDATE lupo_projects 
   SET default_channel_id = ?, updated_ymdhis = ?, updated_by_actor_id = ?
   WHERE project_id = ?;
   ```

**Option Z: Associate Existing Channel**
1. **Channel Validation**
   - Verify channel exists and is active
   - Check channel belongs to same federation node
   - Validate channel is not already associated with another project

2. **Project Update**
   ```sql
   UPDATE lupo_projects 
   SET default_channel_id = ?, updated_ymdhis = ?, updated_by_actor_id = ?
   WHERE project_id = ?;
   ```

3. **Channel Update (Optional)**
   ```sql
   UPDATE lupo_channels 
   SET project_id = ?, updated_ymdhis = ?, updated_by_actor_id = ?
   WHERE channel_id = ?;
   ```

### 2.2 Channel-Project Relationship Rules

**Constraints:**
- A channel belongs to exactly one project
- A project may have zero or more channels
- Default channel is optional but recommended
- Channel project_id can be null (unassigned channels)

**Validation Rules:**
- Channel federation_node_id must match project federation_node_id
- Default channel must be active when assigned
- Channel reassignment requires project administrator permission

---

## 3. Project Documentation Creation

### 3.1 Filesystem Structure

**Project Directory Creation:**
```bash
# Create project filesystem structure
mkdir -p projects/<project_id>/
mkdir -p projects/<project_id>/docs/
mkdir -p projects/<project_id>/config/
mkdir -p projects/<project_id>/collections/
```

**Registry Mirror Creation:**
```json
// database/lupopedia/projects/project_id/<project_id>.json
{
  "project_id": 1234,
  "project_key": "my-project",
  "project_slug": "my-project",
  "project_name": "My Project",
  "federation_node_id": 1,
  "orchestrator_id": 1001,
  "project_type": "standard",
  "status": "active",
  "created_ymdhis": 20260316120000,
  "updated_ymdhis": 20260316120000
}
```

### 3.2 Documentation Templates

**Project README Template:**
```markdown
# <project_name>

**Project ID:** <project_id>  
**Project Key:** <project_key>  
**Federation Node:** <federation_node_id>  
**Status:** <status>

## Description
<description>

## Default Channel
<default_channel_link>

## Project Structure
- Channels: <channel_list>
- Collections: <collection_list>
- Members: <member_list>

## Governance
**Orchestrator:** <orchestrator_name>  
**Created:** <creation_date>  
**Last Updated:** <update_date>
```

**Project Configuration Template:**
```yaml
# projects/<project_id>/config/project.yaml
project:
  id: <project_id>
  key: <project_key>
  name: <project_name>
  type: <project_type>
  federation_node: <federation_node_id>

governance:
  orchestrator: <orchestrator_id>
  administrators: []
  members: []

settings:
  default_channel_id: <default_channel_id>
  channel_creation_policy: "admin_only"
  collection_policy: "admin_only"
```

---

## 4. Project Archival Workflow

### 4.1 Archive Process

**Initiation:**
1. **Archive Request**
   - Project administrator initiates archive
   - Provide reason and effective date
   - System validates permissions and project state

2. **Pre-Archive Validation**
   - Verify no active critical operations
   - Check dependent systems and integrations
   - Confirm channel states and user impact

**Archive Execution:**
```sql
-- Archive project
UPDATE lupo_projects 
SET status = 'archived', 
    is_active = 0, 
    is_archived = 1,
    updated_ymdhis = ?,
    updated_by_actor_id = ?
WHERE project_id = ?;

-- Archive all project channels (optional)
UPDATE lupo_channels 
SET status = 'archived',
    updated_ymdhis = ?,
    updated_by_actor_id = ?
WHERE project_id = ? AND status = 'active';
```

**Post-Archive Actions:**
- Notify all project members
- Update project documentation
- Create archive snapshot
- Log archival with audit trail

### 4.2 Archive Behavior

**Archived Project Characteristics:**
- Project metadata becomes read-only
- No new channels can be created
- Existing channels remain operational
- Project appears in archived listings

**User Experience:**
- Project marked as "Archived" in UI
- Read-only access to project information
- Channel access remains unchanged
- No new project modifications allowed

---

## 5. Project Reactivation Workflow

### 5.1 Reactivation Process

**Initiation:**
1. **Reactivate Request**
   - Project administrator requests reactivation
   - Provide justification and changes needed
   - System validates permissions and archive duration

2. **Pre-Reactivation Validation**
   - Check for policy violations or conflicts
   - Verify federation node capacity
   - Validate orchestrator permissions

**Reactivation Execution:**
```sql
-- Reactivate project
UPDATE lupo_projects 
SET status = 'active', 
    is_active = 1, 
    is_archived = 0,
    updated_ymdhis = ?,
    updated_by_actor_id = ?
WHERE project_id = ?;

-- Optionally reactivate channels
UPDATE lupo_channels 
SET status = 'active',
    updated_ymdhis = ?,
    updated_by_actor_id = ?
WHERE project_id = ? AND status = 'archived';
```

**Post-Reactivation Actions:**
- Notify project members of reactivation
- Update project documentation
- Review and update project settings
- Log reactivation with audit trail

---

## 6. Project Freeze Workflow

### 6.1 Freeze Process

**Emergency Suspension:**
1. **Freeze Initiation**
   - Federation administrator initiates freeze
   - Immediate suspension of all project operations
   - No pre-validation required for emergency situations

2. **Freeze Execution:**
   ```sql
   -- Freeze project immediately
   UPDATE lupo_projects 
   SET status = 'frozen', 
       is_active = 0, 
       is_frozen = 1,
       updated_ymdhis = ?,
       updated_by_actor_id = ?
   WHERE project_id = ?;
   
   -- Suspend all project channels
   UPDATE lupo_channels 
   SET status = 'frozen',
       updated_ymdhis = ?,
       updated_by_actor_id = ?
   WHERE project_id = ?;
   ```

**Freeze Behavior:**
- All project operations suspended
- All project channels suspended
- No modifications allowed to project or channels
- Emergency administrative access only

### 6.2 Unfreeze Process

**Administrative Unfreeze:**
1. **Unfreeze Authorization**
   - Federation administrator authorization required
   - Review freeze reason and resolution
   - Validate project state for reactivation

2. **Unfreeze Execution:**
   ```sql
   -- Unfreeze project
   UPDATE lupo_projects 
   SET status = 'active', 
       is_active = 1, 
       is_frozen = 0,
       updated_ymdhis = ?,
       updated_by_actor_id = ?
   WHERE project_id = ?;
   
   -- Reactivate channels
   UPDATE lupo_channels 
   SET status = 'active',
       updated_ymdhis = ?,
       updated_by_actor_id = ?
   WHERE project_id = ? AND status = 'frozen';
   ```

---

## 7. Project Rename Workflow

### 7.1 Identity Preservation

**Rename Principles:**
- `project_id` never changes
- `federation_node_id` never changes
- `project_key` remains stable for system integration
- `project_name` and `project_slug` may change

**Rename Process:**
1. **Rename Request**
   - Project administrator requests rename
   - Provide new project_name and/or project_slug
   - System validates uniqueness within federation node

2. **Uniqueness Validation**
   ```sql
   -- Check for key conflicts
   SELECT COUNT(*) FROM lupo_projects 
   WHERE project_key = ? AND federation_node_id = ? AND project_id != ?;
   
   -- Check for slug conflicts
   SELECT COUNT(*) FROM lupo_projects 
   WHERE project_slug = ? AND federation_node_id = ? AND project_id != ?;
   ```

3. **Rename Execution:**
   ```sql
   -- Update project name and/or slug
   UPDATE lupo_projects 
   SET project_name = ?, 
       project_slug = ?,
       updated_ymdhis = ?,
       updated_by_actor_id = ?
   WHERE project_id = ?;
   ```

### 7.2 Redirect Management

**Slug Redirect Handling:**
1. **Old Slug Preservation**
   - Maintain mapping from old slug to project_id
   - Implement redirect logic in URL routing
   - Preserve SEO and bookmark links

2. **Redirect Table (Optional):**
   ```sql
   CREATE TABLE lupo_project_slug_redirects (
       old_slug VARCHAR(255) NOT NULL,
       project_id BIGINT NOT NULL,
       federation_node_id BIGINT NOT NULL,
       created_ymdhis BIGINT NOT NULL DEFAULT 0,
       PRIMARY KEY (old_slug, federation_node_id),
       INDEX idx_project_redirect (project_id, federation_node_id)
   );
   ```

**Redirect Logic:**
```php
// Pseudo-code for slug redirect
function resolveProjectSlug($slug, $federationNodeId) {
    // Try direct match first
    $project = findProjectBySlug($slug, $federationNodeId);
    if ($project) return $project;
    
    // Check redirects
    $redirect = findSlugRedirect($slug, $federationNodeId);
    if ($redirect) {
        return findProjectById($redirect['project_id']);
    }
    
    return null; // Not found
}
```

---

## 8. Federation Node Scope Enforcement

### 8.1 Scope Validation Rules

**Creation Time Validation:**
- Projects must specify valid federation_node_id
- Federation node must be active and accepting projects
- Project creator must have permissions in target federation node

**Operation Time Validation:**
- All project operations validate federation node scope
- Channel creation respects project federation node
- Actor-project relationships respect federation node boundaries

### 8.2 Cross-Node Federation

**Federation vs Multi-Node Projects:**
- Projects do not span federation nodes
- Cross-node collaboration uses federation mechanisms
- Federation node boundaries are hard boundaries

**Federation Coordination:**
```sql
-- Federation-level project listing
SELECT p.*, fn.name as federation_node_name
FROM lupo_projects p
JOIN lupo_federation_nodes fn ON p.federation_node_id = fn.federation_node_id
WHERE p.is_deleted = 0
ORDER BY fn.federation_node_id, p.project_name;
```

---

## 9. Comparison with Actor/Channel Registration

### 9.1 Similarities

**Registry-First Allocation:**
- All three use registry-first ID allocation
- Application-assigned IDs, no AUTO_INCREMENT
- Reserved-ID doctrine applied consistently

**Deterministic Identity:**
- Canonical identifiers never change
- Human-readable names may change
- Stable identity preserves historical references

**Federation Node Scoping:**
- All entities scoped to federation nodes
- Uniqueness enforced within node scope
- Cross-node coordination via federation

### 9.2 Differences

**Entity Relationships:**
- Actors: Independent entities, participate in multiple projects
- Channels: Belong to exactly one project, optional project association
- Projects: Contain channels, scoped to federation node

**Lifecycle Complexity:**
- Actors: Simple active/inactive states
- Channels: Basic active/archived states
- Projects: Complex active/archived/frozen/deleted states

**Governance Models:**
- Actors: System-level registration and management
- Channels: Project-level management and organization
- Projects: Federation-level oversight and project-level administration

---

## 10. Error Handling and Recovery

### 10.1 Common Error Scenarios

**Allocation Conflicts:**
- Registry reservation timeout
- Concurrent allocation attempts
- Federation node capacity limits

**Validation Failures:**
- Invalid federation node ID
- Duplicate project key or slug
- Missing required permissions

**Database Failures:**
- Transaction rollback scenarios
- Constraint violations
- Connection or timeout issues

### 10.2 Recovery Strategies

**Registry Recovery:**
- Automatic cleanup of expired reservations
- Manual intervention for stuck allocations
- Registry consistency checks and repairs

**Database Recovery:**
- Transaction rollback on failures
- Retry logic for transient failures
- Manual cleanup of orphaned records

**Workflow Recovery:**
- Resume interrupted workflows
- Partial completion detection
- Rollback to last known good state

---

**Workflow Status:** Complete and ready for implementation  
**Next Steps:** Create automation scripts and UI components  
**Integration:** Aligns with existing actor/channel workflow patterns

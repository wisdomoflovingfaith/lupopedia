# Remaining TODO for Version 4.0.76

**Version:** 4.0.76  
**Source:** plan.md, tasks.md, CHANGELOG.md  
**Purpose:** Single list of what remains to be done for 4.0.76  

---

## What Is Already Done (4.0.76)

- **Phase 1 — Documentation:** Complete. Executive Summary "Projects as Semantic Universes," doctrine/channels.md project context, cross-references validated. Windsurf handoff and Cursor Phase 1 status artifact in place.
- **Design package:** PROJECT_REGISTRY_DOCTRINE, PROJECT_REGISTRY_SCHEMA_DESIGN, PROJECT_REGISTRY_WORKFLOW, create_lupo_projects.sql.md (draft), PROJECTS.md, PROJECTS_API.md, ROOTRULES_EXTERNAL_ACTOR, ACTOR_REGISTRY, ACTOR_REGISTRATION_CHECKLIST, README, EXECUTIVE_SUMMARY all updated.
- **Agent Registry doctrine:** AGENT_REGISTRY.md and cross-references.
- **Cascade integration:** propagate_agent_rules.php --target=cascade, cascade_rules_enforcement test, onboarding/registration docs.
- **Install SQL — lupo_projects table:** Added to install_new_lupopedia.sql per PROJECT_REGISTRY_SCHEMA_DESIGN (schema correct; no project_id in lupo_channels yet).
- **Recurring (CHANGELOG):** Fresh install and Crafty 3.7.5 upgrade validation continue as regression under 4.0.76.

---

## Phase 2 — Schema (Remaining)

### 2.1 Production install SQL — partial ✅ / remaining

- [x] lupo_projects table in install_new_lupopedia.sql (schema aligned with docs).
- [ ] Update table count in documentation (211 tables including lupo_projects).
- [ ] Run TOON generation pipeline (e.g. generate_toon_from_sql.py or generate_toon_files.py).
- [ ] Validate new TOON files; add lupo_projects.toon.json to required tables / docs if applicable.

### 2.2 Seed data — not started

- [ ] Create `lupo-database/lupopedia/mysql/seed/seed_projects.sql`.
- [ ] Insert default Lupopedia development project (e.g. project_id 1, project_key, project_slug, project_name, federation_node_id 1, orchestrator_id, status active).
- [ ] Align with existing actors/channels (default_channel_id if needed).
- [ ] Ensure reserved-ID doctrine: explicit project_id, no AUTO_INCREMENT.
- [ ] Test seed insertion (e.g. run seed after install).

### 2.3 Channel table — project_id column — not started

- [ ] Add `project_id bigint DEFAULT NULL` to `lupo_channels` in install_new_lupopedia.sql.
- [ ] Add index for project-based channel queries (e.g. lupo_channels_idx_project_id).
- [ ] Preserve backward compatibility (existing channels with project_id NULL).
- [ ] Update table docs / TOON for lupo_channels after change.

---

## Phase 3 — Application (Blocked until Phase 2 complete)

- [ ] **3.1** Create ProjectService (`app/Services/ProjectService.php`): createProject, getProjectById, getProjectByKey, getProjectBySlug, updateProject, archiveProject, freezeProject; registry allocation; PHP 5.6+ compatible; PDO_DB only.
- [ ] **3.2** Registry integration: project registry dir structure (e.g. lupo-database/lupopedia/projects/), project_id/registry.json mirrors, federation node project listings, allocation logic, conflict detection, sync methods.
- [ ] **3.3** Project-aware API endpoints: GET /projects/list, GET /projects/{id}, POST /projects/create, PUT /projects/{id}, POST /projects/{id}/archive, POST /projects/{id}/freeze; project context validation; BIGINT timestamps; external actor checks per ROOTRULES_EXTERNAL_ACTOR.

---

## Phase 4 — Testing (Blocked until Phase 3 complete)

- [ ] **4.1** Unit tests: test_project_creation, test_project_allocation, test_project_registry, test_project_uniqueness, test_project_lifecycle, test_project_federation_scope, test_project_service; coverage >90%.
- [ ] **4.2** Integration tests: project API endpoints, project-channel integration, external actor compliance, cross-project operations, registry sync.
- [ ] **4.3** Migration tests: fresh install with projects, upgrade from 4.0.75, channel migration with data, registry sync; rollback scenarios.

---

## Risk Mitigation (from tasks.md)

- [ ] Verify table count after schema changes (211/222 ceiling).
- [ ] Review all project-related SQL against DATABASE_DOCTRINE before deployment.
- [ ] Test backward compatibility with existing data (channels without project_id).
- [ ] Benchmark new project queries and indexes (optional).
- [ ] Test registry–DB consistency (optional).

---

## Quality Gates

- **Gate 1 Documentation:** ✅ Complete.
- **Gate 2 Schema:** [ ] Production SQL validated; [ ] Seed data; [ ] Channel project_id; [ ] TOONs generated; [ ] Table count documented.
- **Gate 3 Application:** [ ] ProjectService; [ ] Registry integration; [ ] API endpoints; [ ] Doctrine compliance.
- **Gate 4 Production:** [ ] Unit tests passing; [ ] Integration tests; [ ] Migration tests; [ ] Performance/security as required.

---

## Success Metrics (to be validated when implemented)

- Project creation success rate >99%; registry allocation accuracy 100%; API response time <200ms for project operations; zero data corruption.
- Test coverage >90%; documentation and doctrine compliance.
- Project resolution <100ms; registry allocation <50ms; channel–project joins indexed.

---

## Non–Project-System 4.0.76 (from CHANGELOG)

- Recurring: fresh install and Crafty Syntax 3.7.5 upgrade validation (regression under 4.0.76).
- No Lupopedia→Lupopedia upgrade until 4.1.0; single install from Crafty 3.7.5.

---

## Suggested Order of Work

1. **Next (schema):** Finish 2.1 (TOON generation, table count doc), then 2.2 (seed_projects.sql), then 2.3 (project_id in lupo_channels, index, TOONs).
2. **Then:** Phase 3 (ProjectService, registry, API).
3. **Then:** Phase 4 (unit, integration, migration tests).
4. **Ongoing:** Run recurring install/upgrade validation when changing install or seed.

---

## References

- [plan.md](../../plan.md)
- [tasks.md](../../tasks.md)
- [CHANGELOG.md](../../CHANGELOG.md)
- [lupo-docs/status/CURSOR_PROJECT_SYSTEM_PHASE1_IMPLEMENTATION_4_0_76.md](CURSOR_PROJECT_SYSTEM_PHASE1_IMPLEMENTATION_4_0_76.md)
- [lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md](../doctrine/PROJECT_REGISTRY_DOCTRINE.md)
- [lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md](../database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md)

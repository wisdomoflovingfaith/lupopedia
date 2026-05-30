---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/implementation_getting_started.md
  web_path: https://www.lupopedia.com/lupopedia/docs/implementation_getting_started.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: implementation_guide
  artifact_kind: getting_started
  channel_key: null
  federation_node_id: null
  thread_key: 4.0.89-implementation-guide
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# Implementation Getting Started Guide

**Version**: 4.0.89  
**Date**: 2026-03-28  
**Purpose**: Bridge from documentation understanding to practical implementation  

---

## First 30 Minutes in Lupopedia

Welcome to implementation! This guide helps you move from understanding Lupopedia's architecture to actually building features.

### Prerequisites

Before starting, ensure you have:

1. **Read the foundational documentation**:
   - `README.md` - Project overview and current version
   - `ORGANIZATION.md` - System organization and directory structure
   - `docs/doctrine/LUPOPEDIA_HEADERS/README.md` - Metadata system
   - `docs/versions/4.0.89/README.md` - Current version scope

2. **Understand your current task**:
   - `docs/versions/4.0.89/TODO.md` - What needs to be done
   - `docs/versions/4.0.89/PLAN.md` - How and when to do it

3. **Development environment ready**:
   - ServBay stack running on Windows
   - Database access (MySQL/MariaDB/PostgreSQL)
   - PHP 7.4+ compatibility
   - Git workflow configured

---

## Understanding Lupopedia Architecture

### Database vs Filesystem Authority

**Database is authoritative for**:
- Runtime data (sessions, users, channels, messages)
- Identity and permissions (actors, agents, departments)
- Structured relationships (edges, contexts)
- Performance-critical queries

**Filesystem is authoritative for**:
- Documentation and doctrine
- Channel coordination artifacts
- Configuration and templates
- Development scripts and tools

**How they work together**:
1. Database schema defined in `install_new_lupopedia.sql`
2. TOON/JSON exports provide machine-readable schema snapshots
3. Table docs in `docs/database/lupopedia/tables/active/` provide human-readable reference
4. Channel artifacts in `channels/` coordinate multi-agent work
5. Scripts synchronize changes between database and filesystem

### Finding Your First Task

**For new contributors**: Start with Priority 1 tasks from TODO.md
**For maintainers**: Check your assigned tasks in current version docs
**For feature development**: Reference Crafty Syntax backlog for specific features

**Example workflow**:
1. Pick task from `docs/versions/4.0.89/TODO.md`
2. Read related table docs in `docs/database/lupopedia/tables/active/`
3. Check existing implementation in relevant `includes/` files
4. Follow LUPOPEDIA HEADERS standards for your documentation
5. Test your changes
6. Update relevant documentation

---

## Common Implementation Patterns

### Database Changes

**When modifying database schema**:
1. Update `install_new_lupopedia.sql` first
2. Regenerate TOON files: `python scripts/generate_toon_files.py`
3. Update affected table docs in `docs/database/lupopedia/tables/active/`
4. Test with existing data
5. Update migration scripts if needed

### File-Based Artifacts

**When creating coordination artifacts**:
1. Use proper filename format: `YYYYMMDD_HHIISS_actor_type_purpose.md`
2. Include complete LUPOPEDIA HEADERS
3. Add meaningful `lupopedia.edges` for navigation
4. Place in correct channel directory (`channels/<channel>/`)
5. Update relevant indexes and registries

### Feature Implementation

**When building new features**:
1. Check existing patterns in `includes/`
2. Follow established coding standards
3. Consider impact on existing database schema
4. Add appropriate table documentation
5. Update relevant TODO and PLAN entries

---

## Where to Find Help

### Documentation Questions
- **LUPOPEDIA HEADERS**: `docs/doctrine/LUPOPEDIA_HEADERS/README.md`
- **Table Documentation**: `docs/database/lupopedia/tables/active/`
- **Organization**: `ORGANIZATION.md`

### Implementation Examples
- **Existing Features**: Look at similar implementations in `includes/`
- **Database Patterns**: Check existing SQL in `database/lupopedia/mysql/`
- **Channel Examples**: Review `channels/42/` for coordination patterns

### Testing and Validation
- **Unit Tests**: `tests/unit/`
- **Integration Tests**: `tests/integration/`
- **Database Validation**: `python scripts/verify_db_against_toons.py`

---

## Development Workflow

### 1. Task Selection
Choose from current priorities:
- **Priority 1**: Context model implementation
- **Priority 2**: Crafty Syntax critical features
- **Priority 3**: System enhancement
- **Priority 4**: Documentation clarity (this guide!)

### 2. Implementation
Follow established patterns:
1. Read relevant documentation
2. Understand existing implementation
3. Make changes following standards
4. Test thoroughly
5. Update documentation

### 3. Coordination
Use channel-based coordination:
1. Create thread in appropriate channel
2. Document decisions and progress
3. Coordinate with other actors via channels
4. Update artifacts as work progresses

### 4. Quality Assurance
Ensure quality through:
1. LUPOPEDIA HEADERS compliance
2. Database schema validation
3. Code review and testing
4. Documentation updates

---

## Common Mistakes to Avoid

### Documentation Mistakes
- **Don't** skip LUPOPEDIA HEADERS in new files
- **Don't** mix version references
- **Don't** create orphaned documentation
- **Don't** forget to update edges

### Implementation Mistakes
- **Don't** modify database without updating schema
- **Don't** break existing PHP compatibility (7.4+)
- **Don't** ignore channel coordination patterns
- **Don't** create features without table documentation

### Coordination Mistakes
- **Don't** work without channel artifacts
- **Don't** ignore existing threads
- **Don't** skip documentation updates
- **Don't** create conflicting artifacts

---

## Getting Help

### When Stuck
1. **Check existing issues**: Look for similar problems in `channels/`
2. **Ask in channels**: Create thread asking for guidance
3. **Review documentation**: Check if you missed relevant information
4. **Check patterns**: Look for existing solutions in codebase

### Best Practices
1. **Start small**: Begin with simple, well-defined tasks
2. **Test incrementally**: Validate each step before proceeding
3. **Document decisions**: Keep clear records in channels
4. **Follow standards**: Use established patterns and conventions
5. **Ask early**: Don't hesitate to request guidance

---

## Success Criteria

You're successfully implementing when:

1. **Understanding**: You can explain what you're building and why
2. **Navigation**: You can find relevant documentation and code examples
3. **Standards**: Your work follows LUPOPEDIA HEADERS and project patterns
4. **Quality**: Your changes are tested and documented
5. **Coordination**: Your work is visible and coordinated through channels

---

**ATHENA (actor_id 12)** — Implementation bridge established. Start building!

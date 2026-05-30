# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/actor_help_documentation_validation

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "Documentation for actor_help_documentation_validation.md"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/actor_help_documentation_validation.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:11Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/tasks/completed/actor_help_documentation_validation.md"
  file_hash: "43d1f47e3cb116e69de4dc5a0a821e482181618350427151a73e5a0f39ed4feb"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for actor_help_documentation_validation.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "tasks"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/actor_help_documentation_validation.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/completed/actor_help_documentation_validation"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

lupopedia.headers:
  file_path_from_root: "lupo-channels/42/tasks/active/actor_help_documentation_validation.md"
  file_hash: "<?php echo hash_file('lupo-channels/42/tasks/active/actor_help_documentation_validation.md'); ?>"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 10000
  last_modified_utc: "<?php echo gmdate('YmdHis'); ?>"
  delegation_chain: "10000:1002"
  artifact_type: "task"
  purpose: "Validate all actor help documentation completeness across the repository"
  mood_vector: "FF6B35"
  artifact_kind: "validation_task"
  traits: ["critical", "documentation", "validation", "v4.0.50"]
  tags: ["actor_help", "documentation", "validation", "completeness"]
  lupo_agent: "windsurf"

lupopedia.edges:
  file_path_from_root: "lupo-channels/42/tasks/active/actor_help_documentation_validation.md"
  outbound_edges:
    - { to: "lupo-actors/", type: "references", weight: 1.0 }
    - { to: "lupo-channels/42/actors/", type: "references", weight: 0.9 }
    - { to: "bin/validate_faucets.php", type: "references", weight: 0.7 }
    - { to: "CHANGELOG.md", type: "references", weight: 0.5 }
  semantic_tags: ["actor_help", "documentation", "validation", "completeness"]

  last_updated_utc: "<?php echo gmdate('YmdHis'); ?>"
  system_version: "4.0.50"
lupopedia.footer:
  last_verified_utc: "<?php echo gmdate('YmdHis'); ?>"
  last_verified_by: "windsurf"
---

# Task: Actor Help Documentation Validation

**Created**: 2026-02-28  
**Assigned to**: Windsurf (1002)  
**Priority**: High  
**Due**: 2026-02-28  
**Status**: 🔄 IN PROGRESS  

## Objective

Validate all actor help documentation completeness across the repository to ensure comprehensive coverage and consistency for all registered actors.

## Scope

### Actor Coverage Analysis
- **Review all actor directories** under `lupo-actors/` for help documentation presence
- **Validate channel-specific help files** under `lupo-channels/*/actors/*/`
- **Check help completeness** against standardized documentation requirements
- **Identify gaps** in documentation coverage for core agents

### Validation Criteria

#### Required Documentation Elements
1. **Actor Identity Information**
   - Basic actor profile (README.md or profile.md)
   - Capabilities and faucets documentation
   - Contact/communication information

2. **Technical Documentation**
   - API integration examples
   - Configuration requirements
   - Troubleshooting guide

3. **Usage Documentation**
   - Quick reference guide
   - Command examples
   - Integration patterns

4. **Channel-Specific Documentation**
   - Channel role and responsibilities
   - Channel-specific capabilities
   - Inter-agent coordination patterns

### Actors to Validate

**Priority Actors** (High Priority):
- Actor 0 (System Agent)
- Actor 1 (Captain Wolfie)
- Actor 1000 (KIRO IDE)
- Actor 10000 (Captain)
- Actor 2035 (ANUBIS)

**Secondary Actors** (Medium Priority):
- All other registered actors in `lupo-actors/` directory

### Validation Methodology

1. **Automated Scanning**: Use scripts to check for required files
2. **Content Analysis**: Validate documentation completeness and quality
3. **Cross-Reference Checking**: Ensure consistency between related documents
4. **Gap Identification**: Document missing documentation elements
5. **Compliance Scoring**: Rate documentation completeness percentage

### Deliverables

1. **Validation Report**: Comprehensive analysis of current state
2. **Gap Analysis**: Detailed list of missing documentation elements
3. **Recommendations**: Specific actions to achieve 100% coverage
4. **Updated Help Files**: Create missing documentation where needed
5. **Final Validation**: Re-run validation after improvements

### Success Metrics

- **Coverage Target**: 100% of priority actors have complete documentation
- **Quality Threshold**: All required elements present for core actors
- **Consistency Score**: No contradictions between related documents

### Dependencies

- Access to `lupo-actors/` directory structure
- Integration with existing faucet definitions
- Coordination with channel 42 task management

### Timeline

**Phase 1** (2 hours): Automated scanning and initial analysis
**Phase 2** (3 hours): Content validation and gap identification  
**Phase 3** (2 hours): Documentation creation and updates
**Phase 4** (1 hour): Final validation and reporting

### Notes

This task supports the 4.0.50 development cycle by ensuring all actor documentation meets enterprise standards for production deployment. Focus on priority actors first, then address secondary actors as time permits.

---

**Last Updated**: <?php echo gmdate('Y-m-d H:i:s'); ?>  
**System Version**: 4.0.50

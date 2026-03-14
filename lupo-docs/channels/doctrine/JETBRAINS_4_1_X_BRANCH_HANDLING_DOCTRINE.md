# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\JETBRAINS_4_1_X_BRANCH_HANDLING_DOCTRINE.md"
  file_hash: "5bc7e6622aa945ca17e0259934d748a20d88e585ffcc450759e1d37175c4ce67"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\doctrine\JETBRAINS_4_1_X_BRANCH_HANDLING_DOCTRINE.md"
  file_hash: "e2c6d2477615baadefe2187ec69b9685d19b8df2a85f7ee067e82b8b2ef0e5db"
  file_path_from_root: "lupo-docs\channels\doctrine\JETBRAINS_4_1_X_BRANCH_HANDLING_DOCTRINE.md"
  file_hash: "ac6738cc4bec99f16b08fccf8e4e5e1e050fc2f133e9d271e5dfe0c6c6ad8716"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for JETBRAINS_4_1_X_BRANCH_HANDLING_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "jetbrains_4_1_x_branch_handling_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.99
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CASCADE
  target: @JETBRAINS @MONDAY_WOLFIE @DEVELOPMENT_TEAM
  mood_RGB: "0066FF"
  message: "JetBrains 3.0.99 Branch Handling Doctrine created to govern multi-branch development for Lupopedia v3.0.99 series, establishing clear protocols for branch management, immutability, and coordination while preventing version drift and ensuring system stability."
tags:
  categories: ["documentation", "doctrine", "branch-management", "jetbrains", "development"]
  collections: ["core-docs", "doctrine", "development-guidelines"]
  channels: ["dev", "documentation"]
file:
  title: "JetBrains 3.0.99 Branch Handling Doctrine"
  description: "Comprehensive doctrine for managing multiple concurrent development branches in Lupopedia v3.0.99, preventing branch conflicts, version drift, and ensuring system stability through proper branch isolation and coordination protocols."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# JetBrains 3.0.99 Branch Handling Doctrine

## Purpose

To establish clear protocols for managing multiple concurrent development branches in Lupopedia v3.0.99 series, preventing branch conflicts, version drift, and ensuring system stability through proper branch isolation and coordination.

## Branch Architecture

### Primary Development Branches

#### **`main` Branch**
- **Purpose**: Production-ready, stable, doctrine-compliant code
- **Protection**: Immutable - direct commits require special approval
- **Status**: Always deployable, never force-pushed
- **Requirements**: Must pass all integration tests and doctrine compliance checks

#### **`dev` Branch**
- **Purpose**: Active development, feature implementation, integration testing
- **Protection**: Mutable - rapid iteration allowed
- **Status**: Development workspace, not for production deployment
- **Requirements**: Must follow atomic commit discipline and branch coordination

#### **`alpha` Branch**
- **Purpose**: Experimental features, major architectural changes
- **Protection**: Highly mutable - rapid prototyping allowed
- **Status**: Experimental workspace, breaking changes expected
- **Requirements**: Must include experimental warnings and proper documentation

#### **`feature/[name]` Branches**
- **Purpose**: Specific feature development in isolation
- **Protection**: Topic-specific mutable branches
- **Status**: Feature development workspace
- **Requirements**: Must merge back to `dev` or `main` when complete

## Branch Management Rules

### 1. Branch Creation

#### **Branch Naming Convention**
- **Format**: `type/[description]` or `type/[feature-name]`
- **Examples**: `feature/agent-awareness-layer`, `bugfix/schema-migration`, `refactor/dialog-system`
- **Prohibited**: Generic names like `test`, `temp`, `wip`

#### **Branch Creation Protocol**
1. **Authorization**: Branch creation requires explicit approval from JETBRAINS team
2. **Documentation**: Must create corresponding entry in CHANGELOG.md
3. **Issue Tracking**: Assign unique identifier for tracking
4. **Base Branch**: Always create from appropriate base branch

### 2. Branch Switching

#### **Branch Switch Protocol**
1. **Update Working Directory**: Switch to target branch
2. **State Preservation**: Stash any uncommitted work
3. **Version Update**: Update local version references
4. **Validation**: Ensure branch is in expected state

### 3. Branch Merging

#### **Merge Protocol**
1. **Pull Request**: Create pull request to target branch
2. **Review Process**: Code review and integration testing required
3. **Approval**: JETBRAINS team approval required for `main` merges
4. **Merge Method**: Use approved merge strategy (squash, rebase, or merge commit)
5. **Validation**: Post-merge integration testing mandatory

### 4. Version Management

#### **Version Coordination**
- **Single Source of Truth**: `global_atoms.yaml` contains authoritative version
- **Dynamic Resolution**: All WOLFIE headers must resolve version from global atoms
- **Consistency Check**: Verify version alignment across all components

#### **Lupo-Flow Branching Model**

### **Branch Isolation**
- **Feature Branches**: Isolated development without affecting main branch
- **Experimental Branches**: Separate from production code
- **Hotfix Branches**: Direct merge to main for critical fixes

### **Commit Discipline**

#### **Atomic Commits**
- **One Change Per Commit**: Each commit addresses single atomic change
- **Commit Message Format**: `type: brief description` for clear change tracking
- **No Mega-Commits**: Prohibit large, multi-feature commits
- **Documentation**: Every change requires corresponding CHANGELOG.md entry

### **Multi-Universe Coordination**

#### **Federation Safety**
- **Branch Isolation**: Each universe (node) maintains independent branch state
- **Version Compatibility**: Cross-universe version alignment through federation protocols
- **Conflict Resolution**: Clear protocols for resolving version conflicts

## Development Workflow

### **Phase-Based Development**

#### **Phase 1: Development**
- **Branch**: `feature/[feature-name]` branches
- **Coordination**: Regular integration with `dev` branch
- **Testing**: Continuous integration testing in development environment

#### **Phase 2: Integration**
- **Branch**: `integration/[feature-name]` branches
- **Coordination**: Merge to `dev` for comprehensive testing
- **Validation**: Full test suite execution required

#### **Phase 3: Production**
- **Branch**: `release/[version]` branches
- **Coordination**: Merge to `main` for production deployment
- **Validation**: Production readiness validation mandatory

## Safety Mechanisms

### **Version Drift Prevention**
- **Automated Checks**: GitHub Actions enforce branch naming rules
- **Manual Reviews**: JETBRAINS team approval for all version changes
- **Documentation Requirements**: CHANGELOG.md must be updated for all branch operations

### **Integration Testing**

#### **Multi-Branch Testing**
- **Environment**: Separate testing for each branch
- **Cross-Branch Compatibility**: Ensure features work across branch boundaries
- **Automated Validation**: Continuous integration testing across all branches

## Operator Responsibilities

### **JETBRAINS Team**
- **Branch Authority**: Sole authority for version increments and branch management
- **Merge Approval**: Required for all `main` branch updates
- **Doctrine Enforcement**: Ensure compliance with branch handling rules

### **Development Team**
- **Branch Coordination**: Follow isolation protocols and merge procedures
- **Version Alignment**: Maintain consistency with global atoms configuration
- **Testing Requirements**: Comprehensive integration testing before branch merges

## Implementation Guidelines

### **Non-Destructive Editing**
- **Historical Preservation**: Never modify or delete existing commits
- **Documentation Integrity**: Maintain accurate CHANGELOG.md entries
- **Version Consistency**: All components must use same version reference

### **Federation Compliance**
- **Node Independence**: Each universe maintains autonomous branch state
- **Version Synchronization**: Cross-universe alignment through federation protocols
- **Conflict Resolution**: Clear protocols for multi-universe version conflicts

---

*This doctrine establishes comprehensive protocols for managing multiple concurrent development branches while maintaining system stability and preventing the version drift that occurred during the 3.0.x cycle.*

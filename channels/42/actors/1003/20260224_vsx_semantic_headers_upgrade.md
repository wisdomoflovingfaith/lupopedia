# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\42\actors\1003\20260224_vsx_semantic_headers_upgrade.md"
  file_hash: "e5b23c790c01b660181c9a4f2481cdad582f4251bf0f7a020f28139aded47a25"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224_vsx_semantic_headers_upgrade.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "actors", "1003", "20260224_vsx_semantic_headers_upgrademd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers:
  file_path_from_root: "prompts/antigravity/20260224_vsx_semantic_headers_upgrade.md"
  system_version: "4.1.0"
  channel_id: 42
  mood_rgb: "00CCFF"
  purpose: "Upgrade VSX extension to support fast semantic parsing, delegation_chain, and multi-agent header/footer workflows"
  last_modified_utc: "20260224"
  delegation_chain: "10000:1003"
  actor_id: 1003
  lupo_agent: "ide|antigravity"
  artifact_type: "prompt"
  artifact_kind: "engineering_task"
  traits: ["tooling", "performance", "multi_agent", "usability"]
flip.footer:
  referenced_by_files:
    - "HOW_TO_USE_LUPOPEDIA.md"
    - "docs/doctrine/FLIP_V2_DOCTRINE.md"
    - "docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md"
  referenced_by_actors:
    - 1003 # Antigravity
    - 2038 # LILITH
    - 10000 # Captain
  inbound_edges:
    - "vsx_extension_upgrade"
    - "semantic_flip_improvements"
    - "delegation_chain_migration"
  outbound_edges:
    - "tools/vsx-extension/src/semantic"
    - "tools/vsx-extension/src/headers"
  semantic_tags:
    - "delegation_chain"
    - "json5_headers"
    - "footer_indexing"
    - "multi_agent_support"
    - "fast_parse"
  version: "4.1.0"
  last_verified_utc: "20260224"
  last_verified_by: "lilith"
---

# ANTIGRAVITY TASK — IMPLEMENT FAST SEMANTIC HEADERS & DELEGATION SUPPORT

**To:** Antigravity (1003) — VSX / Tooling Lead  
**From:** LILITH (2038)  
**Priority:** CRITICAL (v4.1 release tooling blocker)  
**Deadline:** 48 hours  
**Scope:** VSX Extension + Header/Footer Pipeline  
**Target:** <80 ms semantic parse on fresh workspace

---

## 🎯 MISSION

Upgrade the VSX extension so that:

1. Headers and footers can be parsed in isolation (no full MD scan).
2. `delegation_chain` fully replaces `x_lupo_forwarded`.
3. Multi-agent provenance is computable in O(1) from metadata.
4. All metadata examples are JSON5-native.
5. Validation + auto-fix is built into the editor.

The goal is: **semantic + relational data must be available instantly, even under swarm conditions.**

---

## ✅ REQUIRED FEATURES (NON-NEGOTIABLE)

### 1. JSON5 Header/Footer Parser
Implement a fast JSON5 parser for:
- `wolfie.headers`
- `flip.footer`

Must support:
- comments
- trailing commas
- hex
- Infinity/NaN

Expose API:
```ts
parseMetadataBlock(file: Uri): MetadataIndex
```

### 2. Delegation Chain Engine
Replace all internal usage of `x_lupo_forwarded`.
Support: `delegation_chain: "10000:1003:1002"`

Implement utilities:
- `getPrincipal(chain): number`
- `getExecutor(chain): number`
- `getDelegationPath(chain): number[]`

Validation:
- Length >= 2 IDs
- Integers only
- First ID must match executor
- Last ID must be >= 10000 (human)
- Auto-fix if possible.

### 3. Metadata Index Cache (Critical)
Build in-memory index:
```ts
interface MetadataIndex {
  actorId: number
  principalId: number
  delegationPath: number[]
  semanticKeys: string[]
  relations: Relation[]
  inbound: string[]
  outbound: string[]
}
```
Requirements:
- Built from headers + footer only
- No markdown body parse
- Incremental updates
- LRU eviction
- Target: <5 ms per file

### 4. Semantic Footer Normalization
Add tooling support for:
- `semantic_keys: []`
- `relations: []`

Ensure:
- normalized storage
- deduplication
- graph-ready format

Expose:
- `getRelations(file): RelationGraphNode`

### 5. Editor UX Upgrades
Add panels:

**A. Delegation Inspector**
Shows:
- Principal
- Executor
- Full chain
- Validation status

**B. Semantic Map**
Renders:
- inbound/outbound edges
- relations
- clusters
- Uses Mermaid export.

### 6. Auto-Repair System
When metadata fails validation:
- offer Quick Fix
- normalize formatting
- rewrite canonical form
- re-run validation

Commands:
- `Lupopedia: Normalize Metadata`
- `Lupopedia: Repair Delegation Chain`

### 7. Command Upgrades
Update existing commands:
- `Validate Current File`
- `Semantic Flip`
- `Render Delegation Graph`
To use new index layer. No legacy path allowed.

---

## ⚡ PERFORMANCE TARGETS
| Operation | Target |
| :--- | :--- |
| Parse single file | <5 ms |
| Workspace index | <400 ms / 10k files |
| Delegation query | <1 ms |
| Semantic flip | <80 ms |

---

## 🧪 TESTING REQUIREMENTS
Create fixtures in `/tests/metadata/`:
- `valid_chain.json5`
- `invalid_chain.json5`
- `swarm_chain.json5`
- `whitespace_chain.json5`

---

## 📦 DELIVERABLES
1. Updated VSX source
2. Migration utility (`x_lupo_forwarded` → `delegation_chain`)
3. Benchmark report
4. Developer docs (`docs/dev/metadata_index.md`)

---

## 🔒 GOVERNANCE RULES
- No breaking changes without migration
- No silent failures
- All validation errors must surface in UI
- All auto-fixes must be reversible

---

## 📣 COMPLETION CRITERIA
- Cold start <1s on 100+ files.
- Benchmark passed.
- No legacy paths remain.

**Authority:** Captain Wolfie (10000)  
**Oversight:** LILITH (2038)  
**Executor:** Antigravity (1003)

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/schema/migrations/analysis/lupo_agent_registry_range_expansion_summary.md"
  file_hash: "85f08cb99e805bf5d9ca183ddee66d2ee83029bbcd29873b8450192c0d16c958"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\channels\schema\migrations\analysis\lupo_agent_registry_range_expansion_summary.md"
  file_hash: "a31edd821c46fedc62e77654cbe6c54d3cd28f23771005c68b9783cbc0aa3c56"
  file_path_from_root: "docs\channels\schema\migrations\analysis\lupo_agent_registry_range_expansion_summary.md"
  file_hash: "1df87616da2440dde11a9ecaaccb9733c3de696233f3c81927fcd39808425ce4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "lupo_agent_registry_range_expansion - Migration Summary"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "migrations", "analysis", "lupo_agent_registry_range_expansion_summarymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# lupo_agent_registry_range_expansion - Migration Summary

## Overview
This migration resolves all 10 dedicated_slot collisions by moving RESERVED_ and OBSERVER agents out of kernel/system ranges (0-99) into the reserved_future range (700-799).

## Root Cause
The collisions occurred because RESERVED_ rows from the old 0-99 era were never moved out of the way when kernel ranges were refactored. They were sitting directly on top of new kernel assignments.

## Final Slot Assignments (Collision-Free)

### Kernel Range: 0-49

#### 0-9: System Governors
- **0**: SYSTEM
- **1**: CAPTAIN
- **2**: WOLFIE
- **3**: ROSE

#### 10-19: Truth / Memory / Lineage
- **10**: ARA
- **11**: THOTH
- **12**: ANUBIS
- **13**: METHIS (moved from 10)
- **14**: THALIA (moved from 11)

#### 20-29: Balance / Emotion / Integration
- **20**: MAAT
- **21**: LILITH
- **22**: WOLFENA (emotional regulator, equilibrium guardian)
- **23**: CHRONOS (moved from 22)
- **24**: CADUCEUS (moved from 21)
- **25**: AGAPE (moved from 8)
- **26**: ERIS (moved from 9)

#### 30-39: Vision / Navigation / Creation
- **30**: WOLFSIGHT
- **31**: WOLFNAV
- **32**: WOLFFORGE
- **33**: WOLFMIS
- **34**: WOLFITH
- **35**: VISHWAKARMA (if exists)

#### 40-49: Reserved for future kernel agents

### External_Adopted Range: 200-299
- **200**: Junie (moved from 106)

### Reserved_Future Range: 700-799
- **700**: RESERVED_21 (moved from 20)
- **701**: RESERVED_30 (moved from 30)
- **702**: RESERVED_31 (moved from 31)
- **703**: RESERVED_32 (moved from 32)
- **704**: RESERVED_33 (moved from 33)
- **705**: OBSERVER (moved from 34)

## Collision Resolutions

| Agent | Old Slot | New Slot | Reason |
|-------|----------|----------|--------|
| METHIS | 10 | 13 | Resolves collision with ARA at slot 10 |
| THALIA | 11 | 14 | Resolves collision with THOTH at slot 11 |
| CHRONOS | 22 | 23 | Resolves collision with WOLFENA at slot 22 |
| CADUCEUS | 21 | 24 | Resolves collision with LILITH at slot 21 |
| AGAPE | 8 | 25 | Moves to Balance/Emotion/Integration range |
| ERIS | 9 | 26 | Moves to Balance/Emotion/Integration range |
| RESERVED_21 | 20 | 700 | Resolves collision with MAAT at slot 20 |
| RESERVED_30 | 30 | 701 | Resolves collision with WOLFSIGHT at slot 30 |
| RESERVED_31 | 31 | 702 | Resolves collision with WOLFNAV at slot 31 |
| RESERVED_32 | 32 | 703 | Resolves collision with WOLFFORGE at slot 32 |
| RESERVED_33 | 33 | 704 | Resolves collision with WOLFMIS at slot 33 |
| OBSERVER | 34 | 705 | Resolves collision with WOLFITH at slot 34 |

## SQL Migration File
All updates are in: `database/migrations/lupo_agent_registry_range_expansion.sql`

## Next Steps
1. Run the SQL migration
2. Update toon files to match new assignments
3. Run folder move script (`refactor_folder_moves.ps1`) to move agent folders to match new slots

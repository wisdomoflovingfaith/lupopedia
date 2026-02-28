# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\status\windsurf_flip_spec_snapshot_4_0_44.md"
  file_hash: "fbd699f9c5918bffc6ad69371db1b778f74adfe6fa2c86e42b91ff800d72fd7b"
  file_path_from_root: "docs\status\windsurf_flip_spec_snapshot_4_0_44.md"
  file_hash: "4dc60709ea8bc9e411cce8d828ca311779aa504e9ced035e858a322a8c64c136"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for windsurf_flip_spec_snapshot_4_0_44.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "windsurf_flip_spec_snapshot_4_0_44md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "docs/status/windsurf_flip_spec_snapshot_4_0_44.md",
  system_version: "4.0.44",
  channel_id: 42,
  actor_id: 1002,
  created_ymdhis: 20260224181000,
  updated_ymdhis: 20260224181000,
  message_type: "status_report",
  visibility: "system",
  priority: "critical",
  purpose: "FLIP specification snapshot based on implemented validators and doctrines"
}
flip.footer: {
  outbound_edges: [
    { to: "docs/doctrine/FLIP/FLIP_DOCTRINE.md", type: "documents", weight: 1.0 },
    { to: "docs/doctrine/FLIP_V2_DOCTRINE.md", type: "documents", weight: 1.0 },
    { to: "docs/doctrine/HEADERS/FLIP_FOOTER_DOCTRINE_4_0_31.md", type: "documents", weight: 1.0 },
    { to: "channels/0/broadcasts/20260224163100_0_10000_minimum_flip_header_requirements.md", type: "documents", weight: 1.0 },
    { to: "channels/0/broadcasts/20260224165300_0_10000_flip_v3_retrofit_doctrine.md", type: "documents", weight: 1.0 },
    { to: "app/Services/FlipHeaderValidatorService.php", type: "implements", weight: 0.9 }
  ],
  semantic_tags: ["flip_spec", "implementation_snapshot", "4_0_44", "validator_alignment"]
}
---

# FLIP Specification Snapshot — 4.0.44

**Agent:** Windsurf (1002)  
**Date:** 2026-02-24  
**Purpose:** Document FLIP specification as implemented in repository  
**Method:** Analysis of existing validators, doctrines, and examples

## 1. Header Format (Implementation-True)

### 1.1 Minimum Required Fields (Doctrine #12)

```yaml
---
wolfie.headers: {
  file_path_from_root: "<FULL_PATH_FROM_PROJECT_ROOT>",
  system_version: "<CURRENT_LUPOPEDIA_VERSION>",
  channel_id: <CURRENT_CHANNEL_ID>,
  actor_id: <AUTHOR_ACTOR_ID>,
  to_actor_id: <RECIPIENT_ACTOR_ID>,
  created_ymdhis: <UTC_BIGINT_TIMESTAMP>,
  updated_ymdhis: <UTC_BIGINT_TIMESTAMP>
}
---
```

### 1.2 Extended Header Fields (Observed in Implementation)

```yaml
wolfie.headers: {
  # Required minimum fields
  file_path_from_root: "path/from/root.md",
  system_version: "4.0.44",
  channel_id: 42,
  actor_id: 1002,
  created_ymdhis: 20260224181000,
  updated_ymdhis: 20260224181000,
  
  # Optional but commonly used
  message_type: "documentation|status_report|directive|broadcast",
  visibility: "public|system|private",
  priority: "high|medium|low",
  purpose: "Brief description of file purpose",
  to_actor_id: 10000,
  mood_rgb: "4B0082",
  delegation_chain: "1001:10000",
  artifact_type: "guide|spec|doctrine",
  artifact_kind: "documentation|implementation",
  traits: ["essential", "comprehensive"],
  hashtags: ["#flip", "#documentation"]
}
---
```

## 2. Footer Format (Implementation-True)

### 2.1 Standard Footer Structure

```yaml
flip.footer: {
  outbound_edges: [
    { to: "path/to/file.md", type: "references", weight: 1.0 },
    { to: "directory/", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["tag1", "tag2", "tag3"]
}
```

### 2.2 Extended Footer Fields (Doctrine #14)

```yaml
flip.footer: {
  # Bidirectional relationship tracking
  outbound_edges: [
    { to: "file.md", type: "references|governs|uses", weight: 1.0 },
    { to: "directory/", type: "references", weight: 0.8 }
  ],
  
  # Semantic categorization
  semantic_tags: ["flip", "documentation", "4_0_44"],
  
  # Legacy compatibility (observed in older files)
  referenced_by_files: ["file1.md", "file2.md"],
  referenced_by_channels: [{ channel_id: 42, channel_name: "development" }],
  referenced_by_threads: [{ channel_id: 42, thread_id: 105 }],
  referenced_by_actors: [{ actor_id: 10000, actor_name: "Captain Wolfie" }]
}
```

## 3. Timestamp Rules

### 3.1 Format Requirements
- **Format:** BIGINT UTC in `YYYYMMDDHHIISS` format
- **Example:** `20260224181000` (February 24, 2026, 18:10:00 UTC)
- **Field Names:** `created_ymdhis`, `updated_ymdhis`
- **Generation:** Use `gmdate('YmdHis')` in PHP

### 3.2 Validation (from FlipHeaderValidatorService.php)
- Timestamps must be exactly 14 digits
- Must represent valid UTC datetime
- No timezone offsets allowed
- Must be BIGINT compatible

## 4. Actor Identity Rules

### 4.1 Actor ID Resolution
- **Source of Truth:** `actors/registry.json`
- **Current Active IDs (4.0.44):**
  - Captain Wolfie: 10000
  - Windsurf: 1002
  - KIRO: 1001
  - Antigravity: 1003
  - LILITH: 8

### 4.2 Actor Validation
- All `actor_id` values must exist in registry
- Use `actors/aliases.csv` for alias resolution
- Legacy IDs (2000+) are deprecated
- No guessing or assumed IDs allowed

## 5. Channel Assignment Rules

### 5.1 Channel Mapping
- **Channel 0:** System doctrines and broadcasts
- **Channel 1:** Main documentation and guides
- **Channel 42:** Development threads and coordination
- **Channel 666:** Quarantine and deprecated content

### 5.2 Channel Validation
- `channel_id` must match directory structure
- Files in `channels/0/broadcasts/` must have `channel_id: 0`
- Files in `channels/42/threads/` must have `channel_id: 42`

## 6. Validator Implementation

### 6.1 FlipHeaderValidatorService.php
- **Location:** `app/Services/FlipHeaderValidatorService.php`
- **Version:** 4.0.29
- **Capabilities:**
  - Structure validation
  - Recipient validation
  - Routing determination
  - ANUBIS integration

### 6.2 Validation Rules
- YAML syntax validation
- Required field presence check
- Actor ID verification against registry
- Timestamp format validation
- Channel ID consistency check

## 7. Offline Fallback Behavior

### 7.1 Database Unavailability
- FLIP headers must contain all critical metadata
- No database queries required for basic file understanding
- Semantic relationships available via footers
- Actor resolution via registry.json (always available)

### 7.2 Fallback Priority
1. **Primary:** FLIP header metadata
2. **Secondary:** Registry.json lookups
3. **Tertiary:** Aliases.csv resolution
4. **Last Resort:** Error logging and halt

## 8. Known Limitations

### 8.1 Current Implementation Gaps
- No automatic FLIP header generation for new files
- Limited real-time validation during file creation
- Manual actor ID resolution required
- Footer semantic tags not standardized

### 8.2 TODO Items
- Automated FLIP header generation tools
- Enhanced validator with real-time checking
- Standardized semantic tag taxonomy
- Improved actor ID resolution automation

## 9. Implementation References

### 9.1 Core Doctrine Files
- `docs/doctrine/FLIP/FLIP_DOCTRINE.md` - Core FLIP protocol definition
- `docs/doctrine/FLIP_V2_DOCTRINE.md` - v2 extensions and collections
- `docs/doctrine/HEADERS/FLIP_FOOTER_DOCTRINE_4_0_31.md` - Footer specifications

### 9.2 Channel 0 Doctrines
- `20260224163100_0_10000_minimum_flip_header_requirements.md` - Minimum requirements
- `20260224165300_0_10000_flip_v3_retrofit_doctrine.md` - v3 retrofit specifications

### 9.3 Implementation Files
- `app/Services/FlipHeaderValidatorService.php` - Active validator service
- `app/Services/Initialization/FLIPHeaderParser.php` - Header parsing interface

---

**Windsurf (1002)**  
*FLIP specification snapshot complete - based on implemented reality*
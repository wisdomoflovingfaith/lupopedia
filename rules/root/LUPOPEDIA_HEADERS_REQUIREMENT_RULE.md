---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/LUPOPEDIA_HEADERS_REQUIREMENT_RULE.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/LUPOPEDIA_HEADERS_REQUIREMENT_RULE.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rule
  artifact_kind: header_requirement
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: rule
  prd_cluster: null
  title: null
  summary: null
---

# LUPOPEDIA Headers Requirement Rule

## Purpose

Establishes mandatory requirement for LUPOPEDIA headers on all files in the Lupopedia system. This is a critical rule that ensures traceability, version control, and proper metadata management.

## Rule Statement

**ALL FILES MUST HAVE COMPLETE LUPOPEDIA HEADERS**

Any file created or modified in Lupopedia MUST include:
- `lupopedia.headers` block
- `lupopedia.footer` block
- `lupopedia.edges` block (if applicable)

## Required Header Fields (v4.0.84+)

### lupopedia.headers

```yaml
lupopedia.headers:
  lupopedia.schema: [schema_type]        # Required
  file_path_from_root: [relative_path]   # Required
  web_path: [full_url]              # Required
  last_modified_utc: [YYYYMMDDHHIISS] # Required
  system_version: [version]             # Required for versioned files
  channel_id: [number]                # Required for channel artifacts
  thread_id: [identifier]             # Required for thread artifacts
  actor_id: [number]                 # Required
  actor_name: [name]                 # Required
  delegation_chain: [chain]             # Required
  artifact_type: [type]               # Required
  artifact_kind: [kind]               # Required
  purpose: [description]             # Required
  tags: [array]                     # Optional but recommended
```

### lupopedia.footer

```yaml
lupopedia.footer:
  version: [version]                 # Required
  last_verified: [YYYYMMDDHHIISS]   # Required
  verified_by:                       # Required
    identity_type: [actor|human]      # Required
    actor_id: [number]               # Required
    agent_name_identity: [name]          # Required
    department_id_delta: [number]        # Optional
  verified_via:                       # Required
    type: [faucet|cli|api]        # Required
    faucet_slug: [slug]                # Required if type=faucet
  orchestrator: [name]                # Required
  next_action: [array]                # Required
  approved_for_release: [version]        # For release artifacts
  approval_status: [status]             # For release artifacts
  approval_target_version: [version]     # For release artifacts
  approval_status_utc: [YYYYMMDDHHIISS] # For release artifacts
  approval_status_by: [name]           # For release artifacts
  approved_by_actor_id: [number]        # For release artifacts
  approved_utc: [YYYYMMDDHHIISS]       # For release artifacts
```

### lupopedia.edges

```yaml
lupopedia.edges:
  comment: [description]              # Optional
  outbound_edges:                      # Required for files with references
    - to: [file_path]              # Required
      type: [relationship_type]        # Required
      weight: [0.0-1.0]             # Required
      reason: [description]            # Optional but recommended
  inbound_edges:                       # Optional
    - from: [file_path]              # Optional
      type: [relationship_type]        # Optional
      weight: [0.0-1.0]             # Optional
      reason: [description]            # Optional but recommended
```

## Version Requirements

### Headers < 4.0.84
- **MUST BE REWRITTEN** - Automatic rewrite required
- **Validation:** Headers < 4.0.84 fail validation
- **Action:** Use `python scripts/validate_headers.py` to rewrite

### Headers >= 4.0.84
- **MUST BE COMPLETE** - All required fields present
- **Validation:** Headers >= 4.0.84 pass validation
- **Action:** Ensure all required fields are filled

## File Types Requiring Headers

### Required (All Files)
- **All doctrine files** - `rules/root/*.md`
- **All channel artifacts** - `channels/{channel_id}/**/*.md`
- **All version documents** - `docs/versions/{version}/**/*.md`
- **All status reports** - `docs/status/*.md`
- **All broadcast messages** - `channels/{channel_id}/broadcasts/*.md`

### Optional (But Recommended)
- **Configuration files** - `lupopedia-config.php`, `.env`
- **Documentation files** - `README.md`, `CHANGELOG.md`
- **Code files** - `.php`, `.js`, `.css` (for traceability)

## Enforcement

### Automated Validation
```bash
# Validate headers in directory
python scripts/validate_headers.py [directory]

# Validate single file
python scripts/validate_headers.py [file]
```

### Manual Review
- **SESHAT:** Content review and validation
- **THOTH:** Database analysis and wisdom
- **MAAT:** Truth and justice verification

### Rejection Criteria
Files without complete LUPOPEDIA headers MUST be rejected:
1. Missing `lupopedia.headers` block
2. Missing `lupopedia.footer` block
3. Missing required fields in headers
4. Headers < 4.0.84 without rewrite

## Exceptions

### Temporary Files
- Test files may omit some fields
- Draft files may have incomplete headers
- Must be marked as temporary/draft status

### Generated Files
- TOON files: Headers optional (generated from database)
- JSON exports: Headers optional (generated from database)
- Auto-generated documentation: May use simplified headers

## Implementation Guidelines

### Creating New Files
1. Start with complete header template
2. Fill all required fields
3. Add appropriate optional fields
4. Validate before committing

### Updating Existing Files
1. Check current header version
2. If < 4.0.84, rewrite automatically
3. Update `last_modified_utc` field
4. Validate changes

### Header Templates

#### Doctrine File Template
```yaml
---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: rules/root/[FILENAME].md
  web_path: http://www.lupopedia.com/rules/root/[FILENAME].md
  last_modified_utc: YYYYMMDDHHIISS
  system_version: 4.0.89
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: doctrine
  artifact_kind: [specific_kind]
  purpose: [description]
  tags:
  - [relevant_tags]
lupopedia.edges:
  outbound_edges:
    - to: [related_file]
      type: references
      weight: 1.0
lupopedia.footer:
  version: 4.0.89
  last_verified: YYYYMMDDHHIISS
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: WOLFIE
  verified_via:
    type: faucet
    faucet_slug: wolfie
  orchestrator: wolfie
  next_action:
    - [action_items]
---
```

#### Channel Artifact Template
```yaml
---
lupopedia.headers:
  lupopedia.schema: [artifact_type]
  file_path_from_root: channels/[channel_id]/[type]/[FILENAME].md
  web_path: http://www.lupopedia.com/channels/[channel_id]/[type]/[FILENAME].md
  last_modified_utc: YYYYMMDDHHIISS
  system_version: 4.0.89
  channel_id: [channel_id]
  thread_id: [thread_id]
  actor_id: [actor_id]
  actor_name: [actor_name]
  delegation_chain: [chain]
  artifact_type: [type]
  artifact_kind: [kind]
  purpose: [description]
  tags:
  - [relevant_tags]
lupopedia.edges:
  outbound_edges:
    - to: [related_file]
      type: [relationship]
      weight: [0.0-1.0]
lupopedia.footer:
  version: 4.0.89
  last_verified: YYYYMMDDHHIISS
  verified_by:
    identity_type: actor
    actor_id: [actor_id]
    agent_name_identity: [name]
  verified_via:
    type: faucet
    faucet_slug: [slug]
  orchestrator: [name]
  next_action:
    - [action_items]
---
```

## Validation Script

### validate_headers.py Usage
```bash
# Validate all files in directory
python scripts/validate_headers.py rules/root/

# Validate specific file
python scripts/validate_headers.py rules/root/README.md

# Validate channel artifacts
python scripts/validate_headers.py channels/42/

# Check validation results
python scripts/validate_headers.py --report rules/root/
```

---

## Rule Priority

**PRIORITY 1 - CRITICAL**
- All doctrine files MUST have complete headers
- All channel artifacts MUST have complete headers
- Headers < 4.0.84 MUST be rewritten

**PRIORITY 2 - HIGH**
- All version documents SHOULD have complete headers
- All status reports SHOULD have complete headers

**PRIORITY 3 - MEDIUM**
- Configuration files SHOULD have headers
- Documentation files SHOULD have headers

---

**lupo_schema:** rule  
**tags:** headers, validation, metadata, requirements, enforcement

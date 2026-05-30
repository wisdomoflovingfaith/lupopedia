---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "docs\doctrine\ANUBIS_FALLBACK_DOCTRINE.md"
  file_hash: "6e6ec741b6f74e29abab600dc2b4260ed84f7e5c90c60f681d7d8ee9d19844cd"
  file_path_from_root: "docs\doctrine\ANUBIS_FALLBACK_DOCTRINE.md"
  file_hash: "be658fbde38277a87f7b5e225551fbd34fa5a88b47900f42fa9a2ce00d31d525"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for ANUBIS_FALLBACK_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "anubis_fallback_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
lupopedia.headers: {
  file_path_from_root: "docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md",
  system_version: "4.0.39",
  channel_id: 42,
  mood_vector: "8B4513",
  purpose: "Doctrine for ANUBIS automated header detection, generation, and fallback system",
  last_modified_utc: "20260224",
  delegation_chain: "1001:19:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "doctrine",
  artifact_kind: "automation_rules",
  traits: ["mandatory", "automation", "anubis", "v4.0.39"],
  hashtags: ["#anubis", "#fallback", "#headers", "#automation", "#doctrine"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 2,
    outbound_count: 4,
    centrality_score: 0.88
  }
}

flip.footer: {
  inbound_edges: [
    { from: "docs/versions/4.0.39/TODO.md", type: "implements", weight: 1.0, hashtag: "#roadmap" },
    { from: "CHANGELOG.md", type: "documented_in", weight: 0.9, hashtag: "#versions" }
  ],
  outbound_edges: [
    { to: "includes/classes/AnubisHeaderFallback.php", type: "implements", weight: 1.0, hashtag: "#code" },
    { to: "docs/versions/4.0.39/PRIORITY_FILES.md", type: "references", weight: 0.9, hashtag: "#priority" },
    { to: "docs/doctrine/SUPPORTING_ACTOR_DOCTRINE.md", type: "extends", weight: 0.8, hashtag: "#actors" },
    { to: "docs/doctrine/FLIP_V2_DOCTRINE.md", type: "extends", weight: 0.9, hashtag: "#flip" }
  ],
  referenced_by_actors: [1001, 19, 10000],
  references: {
    by_files: ["docs/versions/4.0.39/TODO.md", "CHANGELOG.md"],
    by_actors: [1001, 19, 10000]
  },
  semantic_tags: ["anubis_fallback", "header_automation", "file_classification", "quality_gate"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.39",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# ANUBIS FALLBACK DOCTRINE (v4.0.39)

**Status:** MANDATORY  
**Effective:** Version 4.0.39+  
**Authority:** Captain Wolfie (10000)  
**Implemented By:** KIRO (1001) + ANUBIS (19)  
**Last Modified:** 2026-02-24

---

## 1. OVERVIEW

The ANUBIS Fallback System is the automated safety net for the entire Lupopedia repository. It ensures that every file has proper FLIP v3 headers, is correctly classified, and is either maintained or archived based on relevance.

**ANUBIS (Actor 19)** serves as the Orphan Resolver and Routing agent, responsible for:
- Detecting files missing headers
- Generating default FLIP v3 headers
- Classifying files by type and purpose
- Routing files for review or deletion
- Maintaining repository quality standards

---

## 2. CORE PRINCIPLES

### 2.1 Universal Coverage
**Every file in the repository MUST have a FLIP header.**

- No exceptions for "temporary" files
- No exceptions for "legacy" files
- No exceptions for "test" files

### 2.2 Automated Detection
**ANUBIS continuously scans for header violations.**

- Runs on file save (via IDE hooks)
- Runs on commit (via pre-commit hooks)
- Runs on schedule (daily batch scans)

### 2.3 Intelligent Classification
**ANUBIS infers file purpose from path and content.**

- Analyzes file path structure
- Analyzes file content patterns
- Assigns artifact_type and artifact_kind
- Generates appropriate traits

### 2.4 Human-in-the-Loop
**Unclear cases are routed to human review.**

- ANUBIS flags ambiguous files
- Captain or designated reviewer approves
- System learns from human decisions

---

## 3. DETECTION RULES

### 3.1 Missing Header Detection

**A file is considered "missing header" if:**
1. No FLIP header block exists (no `---` delimiters)
2. Header exists but missing required fields
3. Header exists but has invalid syntax

**Required Fields:**
- `file_path_from_root`
- `system_version`
- `delegation_chain`
- `actor_id`
- `artifact_type`

### 3.2 Outdated Header Detection

**A file is considered "outdated" if:**
1. `system_version` is more than 3 versions behind current
2. `delegation_chain` format is invalid
3. Missing FLIP v3 fields (engagement, graph_stats, hashtags)

### 3.3 Incomplete Header Detection

**A file is considered "incomplete" if:**
1. Missing engagement metrics
2. Missing hashtags
3. Missing graph statistics
4. Missing enrichment hooks
5. Footer missing typed edges

---

## 4. CLASSIFICATION LOGIC

### 4.1 Artifact Type Classification

**Based on file path patterns:**

| Path Pattern | artifact_type | artifact_kind |
|--------------|---------------|---------------|
| `docs/doctrine/*.md` | doctrine | [inferred from filename] |
| `docs/status/*.md` | status | activity_report |
| `channels/*/broadcasts/*.md` | broadcast | announcement |
| `prompts/*/*.md` | prompt | task_directive |
| `app/Services/*.php` | service | business_logic |
| `app/auth/*.php` | service | authentication |
| `tests/*.php` | test | unit_test |
| `database/migrations/*.sql` | migration | schema_change |
| `*.md` (root) | guide | documentation |

### 4.2 Trait Assignment

**Automatic trait detection:**

| Condition | Trait |
|-----------|-------|
| Path contains `/doctrine/` | `mandatory` |
| Path contains `/core/` | `critical` |
| Path contains `/experimental/` | `experimental` |
| Path contains `/deprecated/` | `deprecated` |
| Path contains `/archive/` | `archived` |
| Filename starts with `DRAFT_` | `draft` |
| Filename contains `_test` | `test` |

### 4.3 Hashtag Generation

**Based on file path and content:**

```
docs/doctrine/SECURITY_DOCTRINE.md
→ #doctrine #security #mandatory

app/Services/ActorService.php
→ #service #actors #core

channels/42/broadcasts/20260224_*.md
→ #broadcast #channel42 #coordination
```

---

## 5. HEADER GENERATION RULES

### 5.1 Default Header Template

```json5
{
  file_path_from_root: "[auto-detected]",
  system_version: "4.0.39",
  channel_id: 42,  // Default to dev channel
  mood_vector: "808080",  // Neutral gray for auto-generated
  purpose: "[inferred from path/content]",
  last_modified_utc: "[current timestamp]",
  delegation_chain: "19:10000",  // ANUBIS → Captain
  actor_id: 19,  // ANUBIS
  lupo_agent: "anubis",
  artifact_type: "[classified]",
  artifact_kind: "[classified]",
  traits: ["auto_generated", "[additional traits]"],
  hashtags: ["[generated from path]"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "[current timestamp]"
  },
  graph_stats: {
    inbound_count: 0,
    outbound_count: 0,
    centrality_score: 0.0
  }
}
```

### 5.2 Footer Template

```json5
flip.footer: {
  inbound_edges: [],  // To be populated by graph analysis
  outbound_edges: [],  // To be populated by content analysis
  referenced_by_actors: [19],  // ANUBIS
  references: {
    by_files: [],
    by_actors: [19]
  },
  semantic_tags: ["auto_generated"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.39",
  last_verified_utc: "[current timestamp]",
  last_verified_by: "anubis"
}
```

---

## 6. ROUTING RULES

### 6.1 Auto-Approve (Generate Header Immediately)

**Files that can be auto-processed:**
- Standard doctrine files in `/docs/doctrine/`
- Status reports in `/docs/status/`
- Broadcasts in `/channels/*/broadcasts/`
- Service files in `/app/Services/`
- Test files in `/tests/`

### 6.2 Human Review Required

**Files requiring Captain approval:**
- Root-level files (README.md, CHANGELOG.md, etc.)
- Core configuration files
- Database migration files
- Files with ambiguous purpose
- Files in unexpected locations

### 6.3 Deletion Routing

**Files routed to ANUBIS quarantine:**
- `system_version` more than 5 versions old
- Marked with `deprecated` trait
- In `/archive/` directory
- Duplicate files
- Empty or near-empty files

**Deletion requires:**
1. ANUBIS quarantine (move to `.anubis/quarantine/`)
2. Human approval (Captain or designated reviewer)
3. Archive backup before deletion
4. Audit log entry

---

## 7. INSERTION MECHANISM

### 7.1 Safe Insertion Rules

**ANUBIS MUST:**
1. Create backup before modification
2. Preserve all existing content
3. Insert header at file start (after shebang if present)
4. Validate inserted header syntax
5. Run post-insertion validation

### 7.2 File Type Handling

**Markdown (.md):**
```markdown
---
lupopedia.headers: { ... }
flip.footer: { ... }
---

# Original Content
...
```

**PHP (.php):**
```php
<?php
/**
 * @wolfie.headers { ... }
 * @flip.footer { ... }
 */

// Original code
...
```

**SQL (.sql):**
```sql
-- wolfie.headers: { ... }
-- flip.footer: { ... }

-- Original SQL
...
```

**JavaScript/TypeScript (.js, .ts):**
```javascript
/**
 * @wolfie.headers { ... }
 * @flip.footer { ... }
 */

// Original code
...
```

---

## 8. VALIDATION RULES

### 8.1 Post-Generation Validation

**Every generated header MUST pass:**
1. JSON5 syntax validation
2. Delegation chain validation (ends with >= 10000)
3. Required fields present
4. Actor ID valid (19 for ANUBIS)
5. System version current (4.0.39)

### 8.2 Validation Failures

**If validation fails:**
1. Do NOT insert header
2. Flag file for human review
3. Log error details
4. Notify in Channel 42

---

## 9. REVIEW QUEUE

### 9.1 Queue Structure

**Files awaiting review stored in:**
`.anubis/review_queue/[date]/[filename].json`

**Queue entry format:**
```json5
{
  file_path: "path/to/file.md",
  detected_date: "20260224",
  reason: "ambiguous_classification",
  suggested_header: { ... },
  anubis_confidence: 0.65,
  requires_human: true,
  assigned_to: 10000  // Captain
}
```

### 9.2 Review Workflow

1. ANUBIS detects file needing review
2. Creates queue entry
3. Notifies assigned reviewer (Channel 42 broadcast)
4. Reviewer approves/modifies/rejects
5. ANUBIS applies decision
6. System learns from decision

---

## 10. PERFORMANCE REQUIREMENTS

### 10.1 Detection Speed

- Single file scan: < 10ms
- Full repository scan: < 5 minutes (for 1000 files)
- Real-time detection (on save): < 50ms

### 10.2 Generation Speed

- Header generation: < 100ms per file
- Batch generation (100 files): < 10 seconds

### 10.3 Accuracy Targets

- Classification accuracy: > 95%
- False positive rate: < 2%
- Human review rate: < 10% of files

---

## 11. INTEGRATION POINTS

### 11.1 IDE Integration

**VSX Extension hooks:**
- On file save: Trigger ANUBIS scan
- On file create: Generate header immediately
- Show validation errors in Problems panel

### 11.2 Git Integration

**Pre-commit hook:**
```bash
#!/bin/bash
# Run ANUBIS validation before commit
php scripts/anubis_validate.php --staged
if [ $? -ne 0 ]; then
  echo "❌ ANUBIS validation failed. Fix headers before committing."
  exit 1
fi
```

### 11.3 CI/CD Integration

**GitHub Actions workflow:**
- Run ANUBIS scan on every PR
- Block merge if validation fails
- Auto-generate headers for approved files

---

## 12. MONITORING & REPORTING

### 12.1 Daily Reports

**Generated automatically:**
- Files scanned: [count]
- Headers generated: [count]
- Files in review queue: [count]
- Files in quarantine: [count]
- Validation errors: [count]

### 12.2 Weekly Summary

**Posted to Channel 42:**
- Total coverage: [percentage]
- Files remaining: [count]
- Average confidence: [score]
- Human review rate: [percentage]

---

## 13. VERSION 4.0.40 COMPLIANCE GATE

### 13.1 Enforcement Rules

**Starting version 4.0.40:**

Any file with `system_version >= 4.0.40` MUST have:
1. Complete FLIP v3 header
2. All required fields
3. Valid delegation chain
4. Typed edges in footer
5. Engagement metrics
6. Graph statistics

**Files failing compliance:**
- If outdated → ANUBIS deletes (with approval)
- If relevant → KIRO generates header
- If unclear → LILITH flags for review

### 13.2 Grace Period

**Versions 4.0.39:**
- Warning only (no blocking)
- ANUBIS generates headers
- Human review for critical files

**Version 4.0.40+:**
- Strict enforcement
- Blocking validation
- No commits without headers

---

## 14. EXCEPTIONS

### 14.1 Files Exempt from Headers

**Only these files are exempt:**
- `.gitignore`
- `.gitattributes`
- `LICENSE`
- `package.json`, `composer.json` (JSON format files)
- Binary files (.png, .jpg, .pdf, etc.)
- Generated files in `/vendor/`, `/node_modules/`

### 14.2 Legacy Files

**Files marked `legacy` trait:**
- Header required but simplified
- Minimal metadata acceptable
- Routed for eventual migration or archival

---

## 15. SECURITY CONSIDERATIONS

### 15.1 Backup Requirements

**Before any modification:**
1. Create timestamped backup
2. Store in `.anubis/backups/[date]/`
3. Retain for 30 days
4. Automatic cleanup after retention period

### 15.2 Audit Trail

**Every ANUBIS action logged:**
```json5
{
  timestamp: "20260224153045",
  action: "header_generated",
  file: "path/to/file.md",
  actor_id: 19,
  confidence: 0.92,
  human_approved: false
}
```

---

**Authority:** Captain Wolfie (10000)  
**Implemented By:** KIRO (1001) + ANUBIS (19)  
**Version:** 4.0.39  
**Status:** MANDATORY  
**Effective:** 2026-02-24

🐺 **The safety net is woven. Every file will have an identity. ANUBIS watches over all.**

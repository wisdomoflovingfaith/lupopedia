---
lupopedia.headers:
  lupopedia.version: "4.0.94"
  lupopedia.schema: answer
  when_updated: "20260405172914"
  file_path_from_root: "lupo-docs/versions/4.0.94/answers/20260405172914_ANSWER_channel_key_based_organization.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/answers/20260405172914_ANSWER_channel_key_based_organization.md"
  last_modified_utc: "20260405172914"
  federation_node_id: 0
  channel_id: 42
  thread_id: "4.0.94-help-content-organization"
  answer_id: "20260405172914"
  question_id: "20260405172914"
  title: "Use channel_key-based organization: lupo-content/{federation_node_id}/{channel_key}/{content_file}"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "answer"
  artifact_kind: "structural"
  purpose: "Answer explaining correct channel_key-based content organization"
  tags:
  - "answer"
  - "channel_key"
  - "content_organization"
  - "folder_structure"
  - "help_documentation"
lupopedia.edges:
  outbound_edges:
    - to: "questions/20260405172914_QUESTION_what_is_correct_help_content_structure.md"
      type: "answers"
      weight: 1.0
      reason: "Answers the question about help content structure"
    - to: "decisions/20260405172914_DECISION_APPROVED_help_content_organization_channel_key_structure.md"
      type: "implements"
      weight: 1.0
      reason: "Implements the channel_key-based organization decision"
    - to: "lupo-docs/prd/30_channel_usage_patterns.md"
      type: "references"
      weight: 1.0
      reason: "PRD 30 now documents help_documentation channel"
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: "references"
      weight: 1.0
      reason: "PRD 16 updated with channel_key path documentation"
lupopedia.footer:
  last_verified: "20260405172914"
  verified_by:
    type: "actor"
    id: 102
    name: "CURSOR"
  verified_via:
    type: "direct"
    faucet_slug: "cursor"
---

# Use channel_key-based organization: lupo-content/{federation_node_id}/{channel_key}/{content_file}

**Answer ID:** 20260405172914  
**Answering:** Question 20260405172914  
**Answered By:** CURSOR (actor_id 102)  
**Date:** 2026-04-05 17:29:14 UTC  

## Short Answer

Yes, you're absolutely correct. The proper structure should use `channel_key` instead of `channel_id`: 

**`lupo-content/{federation_node_id}/{channel_key}/{content_file}`**

For help documentation: **`lupo-content/0/help_documentation/{content_file}`**

## Detailed Explanation

### 1. Canonical Folder Structure

The correct canonical structure for content organization is:
```
lupo-content/{federation_node_id}/{channel_key}/{content_id}_{slug}.md
```

**Where:**
- **federation_node_id**: 0 for core system
- **channel_key**: Semantic string identifier (e.g., "help_documentation")
- **content_file**: {content_id}_{slug}.md format

### 2. Channel Key vs Channel ID

| Aspect | channel_id (Numeric) | channel_key (Semantic) |
|--------|---------------------|------------------------|
| **Example** | `42` | `help_documentation` |
| **Readability** | Poor - unclear meaning | Excellent - self-descriptive |
| **Stability** | May change | Stable by design |
| **Navigation** | Requires lookup | Intuitive |
| **Development** | `42` = development | `development` = development |

### 3. Help Documentation Channel

**Channel Definition:**
- **Channel Key:** `help_documentation`
- **Purpose:** User-facing help content and documentation
- **Content Types:** Help guides, FAQs, tutorials, user documentation
- **Structure:** `lupo-content/0/help_documentation/`

### 4. PRDs Updated

#### PRD 30 - Channel Usage Patterns
Added help_documentation channel definition:
```markdown
### Help Documentation Channel (channel_key: help_documentation)
- **Purpose**: User-facing help content and documentation
- **Expected Content**: Help guides, FAQs, tutorials, user documentation
- **Frequency**: As help content is created or updated
- **Content Organization**: Stored in `lupo-content/0/help_documentation/` with channel_key-based folder structure
```

#### PRD 16 - LUPOPEDIA Headers
Updated `file_path_from_root` documentation:
```markdown
| `file_path_from_root` | string | Yes | **Repo-relative** path from repository root — **no** leading `/` (matches `import_content._norm_path_repo` and `lib/header_validation._is_valid_relative_path`). For content: use `lupo-content/{federation_node_id}/{channel_key}/{content_id}_{slug}.md` format. |
```

### 5. Implementation Completed

#### Content Structure Created
```
lupo-content/0/help_documentation/
├── 1000001_getting-started-guide.md
├── 1000002_actors-agents-overview.md
├── 1000003_channels-discussions-guide.md
├── 1000004_content-documentation-guide.md
├── 1000005_edges-relationships-guide.md
└── README.md
```

#### Database Integration
- Updated seed file with correct `file_path_from_root` values
- Added `channel_key` field to content headers
- All content uses proper channel_key structure

#### Installation Integration
- Updated build script to include help content
- Generated consolidated seed file
- Integrated with installer workflow

### 6. Benefits of Channel Key Structure

#### Human-Readable Paths
- ❌ `lupo-content/0/0/1000001_getting-started-guide.md` (unclear)
- ✅ `lupo-content/0/help_documentation/1000001_getting-started-guide.md` (clear)

#### Semantic Organization
- Content grouped by purpose and audience
- Clear separation between development and user content
- Intuitive navigation for developers and users

#### Developer Experience
- Easier to locate and work with content
- Consistent with channel architecture principles
- Better code readability and maintenance

### 7. Available Channel Keys

Based on PRD 30, available channel keys include:
- `help_documentation` - User-facing help content
- `development` - Core development coordination  
- `security` - Security and compliance coordination
- `governance` - Rules and policies coordination
- `architecture` - System design coordination
- `organization` - Repo and docs organization
- `semantic` - Semantic and knowledge systems

### 8. Migration Status

**✅ COMPLETED:**
- PRDs updated with channel_key documentation
- Content moved to correct structure
- Database seed updated with proper paths
- Installation process integrated
- Old `lupo-content/0/0/` structure removed

## Conclusion

Your assessment was completely correct. The channel_key-based organization is now implemented and documented. The help content system uses the proper semantic structure, making it much more maintainable and user-friendly.

The pattern established here can be used for other content types, ensuring consistent organization across the entire Lupopedia system.

---
**Implementation Status:** COMPLETE  
**Documentation Updated:** Yes  
**Installation Ready:** Yes

---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260402160000"
  file_path_from_root: "docs/versions/4.0.94/prd/30_prd_development_guide.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/prd/30_prd_development_guide.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: prd
  artifact_kind: guide
  thread_id: "prd-development-guide"
  content_id: null
  pk_id: 30
  pk_slug: "prd_development_guide"
  title: "PRD Development Guide: 5W1H Framework with Embedded Timestamp"
  status: "rejected"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# PRD 30: PRD Development Guide: 5W1H Framework with Embedded Timestamp

**Working copy (4.0.94):** This file lives under `docs/versions/4.0.94/prd/` while it is rewritten as a PRD writing guide. Header `status` is `rejected` for 4.0.93 freeze purposes; treat content as draft input for 4.0.94, not as approved canon.

## Overview

This guide explains how to develop Product Requirements Documents (PRDs) using the **5W1H framework** (Who, What, Where, When, Why, How) where the **When** component is embedded directly in the filename using the `YYYYMMDD_HHIISS` timestamp convention.

## The 5W1H Framework

### Traditional 5W1H
- **Who**: Who is this for? (Stakeholders, actors, departments)
- **What**: What needs to be done? (Requirements, features, changes)
- **Where**: Where will this be implemented? (Files, systems, locations)
- **When**: When should this be done? (Timeline, milestones, release)
- **How**: How will this be accomplished? (Implementation approach, methods)

### Enhanced 5W1H for Lupopedia PRDs

The **When** component is enhanced by embedding it directly in the filename:

```
YYYYMMDD_HHIISS_TYPE_STATUS_TITLE.md
```

- **YYYYMMDD**: Target date or deadline (e.g., 20260415)
- **HHIISS**: Specific time (120000 for noon, 143000 for 2:30 PM)
- **TYPE**: Document type (prd, implementation, doctrine, discussion, etc.)
- **STATUS**: Current status (draft, review, approved, implemented)
- **TITLE**: Descriptive title (underscores instead of spaces)

## PRD Development Workflow

### 1. Initialization (Who + What)

```markdown
# PRD XX: [TITLE]

---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "YYYYMMDDHHIISS"  # Current timestamp
  file_path_from_root: "docs/prd/XX_[TITLE].md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/prd/XX_[TITLE].md"
  # ... other required fields ...
---
```

**Who Section** (Header metadata):
- `actor_id`: Who owns this PRD
- `actor_name`: Human-readable name
- `delegation_chain`: Reporting structure
- `department_id_delta`: Which department this affects

**What Section** (Document body):
- Clear problem statement
- Requirements definition
- Success criteria
- Technical specifications

## Where (Implementation Scope)

Implementation scope belongs in `edges.md` under the implementation folder, not in the PRD.

**See PRD 26 for WHERE documentation requirements.**

## When (Timeline and Decisions)

### Decision Documentation

Decisions are documented in two contexts:

| Context | Location | Purpose |
|---------|----------|---------|
| **PRD-scoped** | `docs/implementations/{id}_{slug}/decisions/` | Decisions specific to this PRD's implementation |
| **Version-scoped** | `docs/versions/{version}/decisions/` | System-wide decisions for a Lupopedia release |

### Decision Filename Format

```
YYYYMMDD_HHIISS_TYPE_TITLE.md
```

- **YYYYMMDD**: Date of decision
- **HHIISS**: Time of decision
- **TYPE**: `DECISION`, `QUESTION`, `ANSWER`, `DIALOG` 
- **TITLE**: Descriptive title (underscores instead of spaces)

### Example

PRD 16 decision:
```
docs/implementations/16_lupopedia_headers/decisions/20260402_143000_DECISION_author_field_structure.md
```

Version 4.0.93 decision:
```
docs/versions/4.0.93/decisions/20260402_120000_DECISION_adopt_five_layer_architecture.md
```

### Linking from PRD

PRDs SHOULD link to relevant decisions via `lupopedia.edges`:

```yaml
lupopedia.edges:
  outbound_edges:
    - to: "docs/implementations/16_lupopedia_headers/decisions/THREAD_INDEX.md"
      type: references
      weight: 1.0
      reason: "Implementation decisions"
```

**Note:** This does not replace PRD 26's structural rules. Decisions folders are for **content**, not for overriding documentation architecture.

## Why

```markdown
## Why

### Business Rationale
- Market need or problem being solved
- Business value or ROI
- Stakeholder impact

### Technical Rationale
- Why this approach over alternatives
- Architectural considerations
- Performance implications
```

## How

```markdown
## How

### Implementation Approach
- Step-by-step implementation plan
- Resource requirements
- Risk mitigation strategies
- Testing and validation approach

### Dependencies
- Required PRDs that must be completed first
- External systems or APIs needed
- Cross-team coordination requirements
```

## Using Discussions for PRD Development

Discussions MUST live under `implementations/{id}/discussions/` per PRD 26.

**See PRD 26 for discussion thread structure and THREAD_INDEX.md schema.**

## Status Tracking

PRD status is tracked in the `status` header field, not in the filename.

Valid status values: `draft`, `review`, `approved`, `implemented`, `deprecated` 

**See PRD 16 for header field requirements.**

## Best Practices

### DO's

- **DO** embed the full 5W1H in each document:
  - Clear who identification in headers
  - Comprehensive what definition in body
  - Specific where scope in edges.md (not PRD)
  - Explicit when in filename and timeline
  - Detailed how in implementation strategy

- **DO** use discussions for collaborative decisions:
  - Discussions live in `implementations/{id}/discussions/`
  - Follow PRD 26 for discussion structure
  - Reference related PRDs in resolution field

- **DO** update timestamps when content changes:
  - Update `when_updated` in header for content changes
  - Keep resolution links current

### DON'Ts

- **DON'T** embed implementation scope in PRD:
  - Use edges.md in implementation folder
  - Follow PRD 26 for WHERE documentation

- **DON'T** change filename based on status:
  - Status tracked in header field
  - Use PRD 16 status values

- **DON'T** create generic discussions:
  - Each discussion should have specific purpose
  - Follow PRD 26 structure

## Integration with Existing Documentation

### Linking to Decisions

When PRD development creates architectural decisions:

1. Create decision thread in decisions folder:
   `20260402_180000_DECISION_adoption_microservices_architecture.md`

2. Update version decisions THREAD_INDEX.md:
   - Add entry linking to new decision thread
   - Include impact assessment and rationale

3. Reference in PRD outbound_edges:
   ```yaml
   lupopedia.edges:
     outbound_edges:
       - to: "docs/versions/4.0.93/decisions/THREAD_INDEX.md"
         type: references
         weight: 0.8
         reason: "Related decisions"
   ```

## Example Complete PRD Development Cycle

1. **PRD Draft Created**
   - Initial draft with requirements
   - Status: `draft` in header field
   - Filename: `{id}_{slug}.md` per PRD 26

2. **Discussions in implementations/{id}/discussions/**
   - Create discussion threads per PRD 26
   - Review and feedback process
   - Decision threads created as needed

3. **PRD Updated**
   - Status changed to `approved` in header field
   - Filename unchanged (per PRD 26)
   - Content updated based on feedback

4. **Implementation in implementations/{id}/**
   - Create implementation folder
   - Add edges.md for implementation scope
   - Decisions documented in implementations/{id}/decisions/

5. **Decision Threads**
   - Version decisions in versions/{version}/decisions/
   - PRD decisions in implementations/{id}/decisions/
   - All decisions linked via lupopedia.edges

This structured approach ensures all 5W1H components are captured, with:
- **Timeline explicit** in decision filenames and header timestamps
- **Discussions tracked** in proper location per PRD 26
- **All 5W1H components** clearly documented
- **Collaboration transparent** through proper discussions
- **Structure compliant** with PRD 16 and 26 requirements

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "skill"
  file_path_from_root: "lupo-skills/lupopedia-headers/README.md"
  web_path: "http://www.lupopedia.com/skills/lupopedia-headers"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  skill_name: "lupopedia-headers"
  skill_version: "1.0"
  artifact_type: "skill"
  artifact_kind: "documentation"
  purpose: "Knowledge of LUPOPEDIA header format, structure, and usage"
  tags: ["skill", "headers", "lupopedia", "metadata"]
  lupo_agent: "cursor"

flare.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/RULES_DOCTRINE.md", type: "references", weight: 0.8 }

flare.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "cursor"
---
# file: Lupopedia Headers Skill — session: L-LUPO-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/skills/lupopedia-headers

**Version:** 1.0  
**Proficiency levels:** beginner, intermediate, expert, master

## Description

Knowledge of LUPOPEDIA header format, structure, and usage. This skill covers:

- Header YAML structure: first line of file is `---`, then YAML blocks, then closing `---`, then `# file: ...` as the first content line
- Required and optional fields
- Block order (init, headers, edges, footer, see, close)
- Session and delegation chain formatting
- Web path resolution
- Skill attachment via `lupopedia.skills`

## Capabilities

### Beginner
- Can read and understand existing headers
- Can identify required fields
- Can recognize basic header structure

### Intermediate
- Can create new headers from templates
- Can modify existing headers
- Understands delegation chains
- Can add `lupopedia.skills` references

### Expert
- Can design new header structures
- Can validate headers against doctrine
- Understands all optional fields
- Can troubleshoot header issues

### Master
- Can teach others
- Can extend the header system
- Understands header evolution
- Can design backward-compatible changes

## Usage

### Basic header (`---` first, then YAML, then `---`, then `# file: ...`)

```markdown
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "example.md"
  web_path: "http://www.lupopedia.com/example"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "guide"
  purpose: "Example header"
---
# file: Example — session: S1 — delegation: cursor:root — web_path: http://www.lupopedia.com/example
```

### With skills

```yaml
lupopedia.skills:
  - name: "lupopedia-headers"
    version: "1.0"
    proficiency: "expert"
  - name: "database-doctrine"
    version: "2.1"
    proficiency: "intermediate"
```

## Related skills

- YAML fundamentals
- Markdown authoring
- Database doctrine

## Resources

- [LUPOPEDIA HEADERS Plan](../../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md)
- [Examples directory](./examples/)

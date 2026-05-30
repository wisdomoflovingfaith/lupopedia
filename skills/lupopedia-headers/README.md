---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: skills/lupopedia-headers/README.md
  web_path: https://www.lupopedia.com/lupopedia/skills/lupopedia-headers/README.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: skill
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: skill
  prd_cluster: null
  title: null
  summary: null
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
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
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

- [LUPOPEDIA HEADERS Plan](../../docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md)
- [Examples directory](./examples/)

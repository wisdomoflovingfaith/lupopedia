---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/skills_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/skills_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: skills
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: Skills System Doctrine — session: L-LUPO-CURSOR — delegation: cursor:lilith:antigravity:root — web_path: http://www.lupopedia.com/doctrine/SKILLS_DOCTRINE

**Version:** 4.0.68  
**Status:** ACTIVE  
**Purpose:** Define how skills are documented and attached to actors.

## Overview

Skills are capabilities that actors possess. They are:

- Documented in Markdown files under `skills/`
- Attached to actors via the `lupopedia.skills` header
- Resolved at runtime by SkillService
- Can be referenced in rules, permissions, and capability checks

## Skill directory structure

```
skills/
  lupopedia-headers/
    README.md
    formatting.md
    validation.md
    examples/
  database-doctrine/
    README.md
    no-fks.md
    timestamps.md
  consensus-protocol/
    README.md
    lilith-themis-wolfie.md
```

## Skill MD file format (LUPOPEDIA header)

The **first line** of the file must be `---`. Then the YAML header blocks (e.g. `lupopedia.headers`, `lupopedia.edges`, `lupopedia.footer`), then a closing `---`. The first line of the **body** must be the formatted identity line: `# file: {title} — session: {session_name} — delegation: {delegation_chain} — web_path: {web_path}`. Then the rest of the content.

- Do **not** put the `# file: ...` line at the very top of the file; the top must be `---` and the YAML.
- Include `lupopedia.headers` (or `lupopedia.headers`) with `skill_name`, `skill_version`, `artifact_type: "skill"`, `artifact_kind: "documentation"`, `purpose`, `tags` as appropriate.
- Optional `lupopedia.skills` listing prerequisite or declared skills.

## Actor skills header

Actors declare skills in FLARE/LUPOPEDIA headers (inside the YAML block):

```yaml
lupopedia.skills:
  - name: "lupopedia-headers"
    version: "1.0"
    path: "skills/lupopedia-headers/"
    proficiency: "expert"
    acquired: "20260310"
    verified_by: "lilith"
```

Proficiency levels: `beginner`, `intermediate`, `expert`, `master`.

Multiple skills:

```yaml
lupopedia.skills:
  - name: "lupopedia-headers"
    version: "1.0"
    proficiency: "expert"
  - name: "database-doctrine"
    version: "2.1"
    proficiency: "master"
```

## Skill resolution

SkillService resolves skills from:

- Actor profile (`profile.md` or equivalent) with `lupopedia.skills` block
- Per-actor `skills/*.md` files under the actor directory (e.g. `actors/1/skills/` for WOLFIE)

```php
$skills = $skillService->getActorSkills(1);
if ($skillService->hasSkill(1, 'lupopedia-headers', 'expert')) {
    // Actor has skill at expert or higher
}
```

## Integration with rules

Skills can be referenced in rules (e.g. permission or behavior rules) via conditions such as `actor_has_skill('lupopedia-headers')` or minimum proficiency checks.

## See also

- [skills/](../../skills/) — Skill documentation
- [actors/1/](../../actors/1/) — WOLFIE (actor 1) actor directory
- [RULES_DOCTRINE.md](RULES_DOCTRINE.md) — Rules system

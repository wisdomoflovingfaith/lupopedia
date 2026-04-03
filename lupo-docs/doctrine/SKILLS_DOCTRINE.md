---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/SKILLS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/SKILLS_DOCTRINE"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  channel_id: 42
  actor_id: 1003
  actor_name: "cursor"
  delegation_chain: "cursor:lilith:antigravity:root"
  artifact_type: "doctrine"
  artifact_kind: "skills"
  purpose: "Define the skills system for actor capabilities"
  mood_rgb: "4169E1"
  traits: ["doctrine", "skills", "v4.0.68"]
  tags: ["skills", "capabilities", "actors"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/RULES_DOCTRINE.md", type: "extends", weight: 1.0 }
    - { to: "lupo-skills/", type: "references", weight: 0.9 }

    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "cursor"
---
# file: Skills System Doctrine — session: L-LUPO-CURSOR — delegation: cursor:lilith:antigravity:root — web_path: http://www.lupopedia.com/doctrine/SKILLS_DOCTRINE

**Version:** 4.0.68  
**Status:** ACTIVE  
**Purpose:** Define how skills are documented and attached to actors.

## Overview

Skills are capabilities that actors possess. They are:

- Documented in Markdown files under `lupo-skills/`
- Attached to actors via the `lupopedia.skills` header
- Resolved at runtime by SkillService
- Can be referenced in rules, permissions, and capability checks

## Skill directory structure

```
lupo-skills/
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
    path: "lupo-skills/lupopedia-headers/"
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
- Per-actor `skills/*.md` files under the actor directory (e.g. `lupo-actors/wolfie/skills/` or `lupo-actors/1/skills/`)

```php
$skills = $skillService->getActorSkills(1);
if ($skillService->hasSkill(1, 'lupopedia-headers', 'expert')) {
    // Actor has skill at expert or higher
}
```

## Integration with rules

Skills can be referenced in rules (e.g. permission or behavior rules) via conditions such as `actor_has_skill('lupopedia-headers')` or minimum proficiency checks.

## See also

- [lupo-skills/](../../lupo-skills/) — Skill documentation
- [lupo-actors/wolfie/](../../lupo-actors/wolfie/) — WOLFIE (actor 1) actor directory
- [RULES_DOCTRINE.md](RULES_DOCTRINE.md) — Rules system

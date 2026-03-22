---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-skills/README.md"
  web_path: "http://www.lupopedia.com/skills"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  artifact_type: "documentation"
  artifact_kind: "index"
  purpose: "Index of documented skills for actor capabilities"
  tags: ["skills", "index"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-skills/lupopedia-headers/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/SKILLS_DOCTRINE.md", type: "references", weight: 1.0 }

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "cursor"
---
# file: Lupopedia Skills Index — session: L-LUPO-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/skills

Skill documentation for actor capabilities. Actors declare skills via the `lupopedia.skills` header; SkillService resolves them from profile and `skills/*.md`.

## Skills

| Skill | Description | Path |
|-------|-------------|------|
| Lupopedia Headers | Header format, structure, and usage | [lupopedia-headers/](lupopedia-headers/) |
| Uploads | Canonical upload layout, date partitioning, hash-named files, auth_users namespace | [lupo-uploads/](../lupo-uploads) |

## See also

- [SKILLS_DOCTRINE.md](../lupo-docs/doctrine/SKILLS_DOCTRINE.md) — Skills system doctrine
- [lupo-actors/](../lupo-actors/) — Actor directories (skills in `skills/*.md`)

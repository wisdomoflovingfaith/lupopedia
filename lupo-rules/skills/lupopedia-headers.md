---
flare.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  flare.version: "1.0"
  flare.schema: "rule"
  file_path_from_root: "lupo-rules/skills/lupopedia-headers.md"
  web_path: "http://www.lupopedia.com/rules/skills/lupopedia-headers"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  rule_id: 1001
  rule_name: "Skill: Lupopedia Headers"
  rule_type: "skill"
  artifact_type: "rule"
  artifact_kind: "skill_rule"
  purpose: "Actor must have Lupopedia Headers skill at intermediate level or higher"
  tags: ["rule", "skill", "lupopedia-headers"]

lupopedia.rule:
  evaluation:
    type: "skill_check"
    skill: "lupopedia-headers"
    proficiency: "intermediate"
  description: "Skill rule for Lupopedia Headers capability"

flare.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Skill rule — Lupopedia Headers — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/skills/lupopedia-headers

## Skill: Lupopedia Headers

Actors evaluated against this rule must have the **lupopedia-headers** skill at **intermediate** proficiency or higher.

- **rule_id:** 1001
- **rule_type:** skill
- **skill_name:** lupopedia-headers
- **min_proficiency:** intermediate

See `lupo-skills/lupopedia-headers/README.md` and `lupo-docs/doctrine/SKILLS_DOCTRINE.md`.

---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/versioning_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/versioning_doctrine.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/versioning-doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/versioning-doctrine
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: doctrine
  prd_cluster: null
  title: Versioning Doctrine
  summary: 'Lupopedia versioning: release-only, fresh-install, 4.1.3 header freeze.'
---

# Versioning Doctrine (Release-Only, Fresh-Install Model, 4.1.3 Header Freeze)

## 1. Scope and Authority

This is the single, canonical versioning doctrine for Lupopedia. Only this file (`docs/doctrine/VERSIONING_DOCTRINE.md`) is authoritative. No duplicates, suffixes, or alternate versioning doctrine files are permitted.

## 2. Release-Only Versioning

Lupopedia version numbers represent **release states only**. Internal development, artifact changes, and header churn do **not** trigger version increments. Development progress is tracked by:

- `when_updated` timestamps
- Changelog entries (see `CHANGELOG.md` and versioned changelogs)
- Artifact and thread history
- Channel and handoff records

Internal iteration does **not** imply a version bump. Do **not** use version numbers to track internal or incremental changes.

## 3. No Pre-4.2.0 Upgrade Path

- Before 4.2.0 there is NO Lupopedia -> Lupopedia migration path.
- There is NO in-place upgrade path.
- All Lupopedia installs before 4.2.0 are fresh installs.
- The only meaningful import/transition path is Crafty Syntax 3.7.5 -> Lupopedia.

## 4. Current Release-Line Model

- **4.1.3** is the current baseline milestone.
- Later 4.1.x versions may exist only if justified by real release milestones.
- **4.2.0** is the first public Lupopedia release.

Do NOT imply that 4.1.5-4.1.9 are guaranteed, automatic, or expected just because they are numerically next. Internal development does not automatically yield a version bump.

## 5. Header Freeze and Versioning

- Lupopedia header format is frozen at 4.1.3.
- Header changes happen only if:
  1. the header format itself changes, OR
  2. a major milestone toward autoinstaller/public-release readiness requires it.
- Routine development does not justify header version changes.

## 6. Fresh-Install Architecture

Lupopedia development before 4.2.0 assumes **fresh install and run**. Schema changes are applied through fresh installs, not in-product patch migrations. Crafty Syntax 3.7.5 → Lupopedia remains the meaningful import path. Internal Lupopedia development lines are not a supported customer-facing migration ladder.

## 7. Normative Rule: Stop Version Inflation

Do **not** bump the version for minor internal changes. Do **not** create artificial patch churn. Use timestamps and changelog history to track development progress. Version numbers are reserved for release states only.

## 8. Historical Version References

Historical version numbers (e.g., 3.0.x, 4.0.1, 4.1.0 as a future marker) are frozen and must not be "fixed" or normalized. They remain as historical record only.

## 9. Crafty Import Path

The only supported upgrade/import path before 4.2.0 is from Crafty Syntax 3.7.5. No in-place Lupopedia → Lupopedia upgrades are supported until 4.2.0.

## 10. Summary Table

| Rule | Statement |
|------|-----------|
| **Single file** | Only `docs/doctrine/VERSIONING_DOCTRINE.md` is authoritative; no duplicates or suffixed copies. |
| **Release-only versioning** | Versions = release states; development tracked by timestamps and changelogs. |
| **No pre-4.2.0 upgrade** | No Lupopedia→Lupopedia upgrade path before 4.2.0; fresh install only. |
| **4.1.3 baseline** | 4.1.3 is the required human live-help baseline; header format frozen. |
| **Later 4.1.x** | May exist only if justified by real release milestones. |
| **4.2.0** | First public Lupopedia release; public release expectations attach here. |
| **Header freeze** | Header format frozen at 4.1.3; no version bump for header churn during freeze. |
| **No version inflation** | Do not bump version for minor/internal changes; use timestamps/changelog. |
| **Historical versions** | Historical version numbers are frozen and not to be normalized. |
| **Crafty import** | Crafty Syntax 3.7.5 → Lupopedia is the only supported import path pre-4.2.0. |

---

*End of versioning doctrine.*

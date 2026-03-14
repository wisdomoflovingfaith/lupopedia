---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "spec_requirements"
  file_path_from_root: ".kiro/specs/kiro-rules-import/requirements.md"
  web_path: "http://www.lupopedia.com/specs/kiro-rules-import/requirements"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  artifact_type: "spec"
  artifact_kind: "requirements"
  purpose: "Requirements for importing Antigravity's canonical Lupopedia Rules System into the .kiro/ folder for Kiro (actor_id 100, schema coordinator)"
  tags: ["kiro", "rules", "import", "propagation", "spec"]
---

# Requirements Document

## Introduction

Antigravity has completed the canonical Lupopedia Rules System as part of v4.0.75. The root rules live in `lupo-rules/root/` (15 rule files covering database, architecture, actor, context, governance, and security doctrines). A propagation script exists at `lupo-scripts/propagate_agent_rules.php` that reads those root rules and writes IDE-specific outputs.

This feature covers the full import pipeline for Kiro (actor_id 100, slug `kiro`, role "Schema Coordinator"): reviewing all root rules, running the propagation pipeline targeting Kiro, validating the output in `.kiro/rules/`, testing enforcement, and documenting results in `.kiro/README.md`. All work must comply with AGENTS.md constraints (PHP 5.6+, BIGINT timestamps, actor model, LUPOPEDIA HEADERS on files, no FKs).

## Glossary

- **Propagation_Pipeline**: The PHP script `lupo-scripts/propagate_agent_rules.php` that reads `lupo-rules/root/*.md`, extracts `lupopedia.rules` blocks, and writes IDE-specific rule artifacts.
- **Root_Rules**: The 15 canonical rule `.md` files in `lupo-rules/root/` authored by Wolfie and Antigravity. Read-only; never modified by this feature.
- **Kiro_Rules_Dir**: The target directory `.kiro/rules/` where propagated rule files for Kiro are written.
- **Rules_JSON**: The file `.kiro/lupopedia_rules.json` — the machine-readable rule index for Kiro.
- **Rule_File**: An individual `.md` file in `.kiro/rules/` corresponding to one root rule, with a Kiro-scoped LUPOPEDIA HEADERS block.
- **Enforcement_Test**: A PHP test script that loads the Rules_JSON and verifies each rule is present, structurally valid, and correctly scoped.
- **Kiro_README**: The file `.kiro/README.md` documenting the import results, rule inventory, and enforcement status for Kiro.
- **Rule_ID**: The canonical identifier for a rule (e.g. `DB001`, `ARC001`, `ACT001`) declared in the `lupopedia.rules.declares` block of each root rule file.
- **LUPOPEDIA_HEADERS**: The YAML metadata block (between `---` delimiters) required on every file per the LUPOPEDIA HEADERS doctrine.

---

## Requirements

### Requirement 1: Root Rule Review

**User Story:** As Kiro (schema coordinator), I want all 15 root rules reviewed before propagation, so that I can confirm the canonical rule set is complete and structurally valid before importing it into my environment.

#### Acceptance Criteria

1. THE Propagation_Pipeline SHALL read all `.md` files in `lupo-rules/root/` except `README.md`.
2. WHEN a root rule file is missing a `lupopedia.rules` block, THEN THE Propagation_Pipeline SHALL log a warning identifying the file and skip it without halting.
3. WHEN a root rule file is missing a `rule_id` field inside its `lupopedia.rules.declares` block, THEN THE Propagation_Pipeline SHALL log a warning and assign the placeholder ID `UNKNOWN` for that rule.
4. THE Propagation_Pipeline SHALL process all 15 rule files: `database-logic-prohibition-doctrine.md` (DB001), `migration-doctrine.md` (DB002), `pdo-db-database-access-doctrine.md` (DB003), `pk-reference-naming-doctrine.md` (DB004), `required-tables-future-features-doctrine.md` (DB005), `reserved-id-doctrine.md` (DB006), `toon-source-of-truth.md` (DB007), `database-offline-fallback-import-doctrine.md` (DB008), `flip-doctrine.md` (ARC001), `no-laravel-no-middleware.md` (ARC002), `php-5-6-compatibility.md` (ARC003), `single-install-no-4.0-upgrade-doctrine.md` (ARC004), `versioning-doctrine-single-source.md` (ARC005), `ide-agent-identity-actor-pairing-doctrine.md` (ACT001), `channels-federation-offline-session-doctrine.md` (CTX001).
5. WHEN the review completes, THE Propagation_Pipeline SHALL output a count of rules processed and a count of warnings encountered.

---

### Requirement 2: Propagation Pipeline Execution for Kiro

**User Story:** As Kiro, I want the propagation pipeline to run with `--target=kiro`, so that only Kiro-specific artifacts are written without overwriting other IDE environments.

#### Acceptance Criteria

1. WHEN the Propagation_Pipeline is invoked with `--target=kiro`, THE Propagation_Pipeline SHALL write outputs only to `.kiro/` and SHALL NOT modify `.cursor/`, `.idea/`, or any other IDE directory.
2. THE Propagation_Pipeline SHALL write the Rules_JSON file at `.kiro/lupopedia_rules.json` containing all successfully parsed rules.
3. THE Propagation_Pipeline SHALL create the Kiro_Rules_Dir at `.kiro/rules/` if it does not already exist.
4. WHEN the Propagation_Pipeline processes a root rule file, THE Propagation_Pipeline SHALL write a corresponding Rule_File to `.kiro/rules/<slug>.md` where `<slug>` matches the root rule filename without the `.md` extension.
5. WHEN the Propagation_Pipeline writes a Rule_File, THE Rule_File SHALL include a LUPOPEDIA HEADERS block with at minimum `actor_id: 100`, `actor_name: "kiro"`, `file_path_from_root`, `last_modified_utc`, and `system_version`.
6. IF the Kiro_Rules_Dir already contains a Rule_File for a given slug, THEN THE Propagation_Pipeline SHALL overwrite it with the current content from the root rule.
7. THE Propagation_Pipeline SHALL complete execution and exit with code 0 when all rules are processed without fatal errors.
8. IF a fatal error occurs during file writing, THEN THE Propagation_Pipeline SHALL exit with a non-zero code and log the error.

---

### Requirement 3: Rules JSON Completeness and Structure

**User Story:** As Kiro, I want the Rules_JSON to contain all 15 canonical rules with correct IDs and enforcement levels, so that tooling and agents can reliably load and enforce the rule set.

#### Acceptance Criteria

1. THE Rules_JSON SHALL contain a top-level `rules` array.
2. THE Rules_JSON SHALL contain exactly one entry per successfully parsed root rule.
3. WHEN a rule entry is written to the Rules_JSON, THE entry SHALL include the fields `id`, `text`, `enforcement`, and `scope`.
4. THE `enforcement` field for every rule entry SHALL be set to `"error"`.
5. THE `scope` field for every rule entry SHALL be an array containing at least `"all_agents"`.
6. WHEN the Rules_JSON is parsed as JSON, THE Rules_JSON SHALL be valid JSON with no syntax errors.
7. THE Rules_JSON SHALL include all three Antigravity-authored rules: `ACT001` (ide-agent-identity-actor-pairing-doctrine), `CTX001` (channels-federation-offline-session-doctrine), and `DB008` (database-offline-fallback-import-doctrine).

---

### Requirement 4: Rule File Validation

**User Story:** As Kiro, I want each imported rule file in `.kiro/rules/` to be structurally valid and traceable back to its root source, so that I can audit the import and detect drift.

#### Acceptance Criteria

1. WHEN a Rule_File is written to `.kiro/rules/`, THE Rule_File SHALL contain a LUPOPEDIA HEADERS block as a YAML front-matter block delimited by `---`.
2. THE LUPOPEDIA HEADERS block in each Rule_File SHALL include `actor_id: 100` identifying Kiro as the target agent.
3. THE LUPOPEDIA HEADERS block in each Rule_File SHALL include a `source_path` field referencing the originating root rule path (e.g. `lupo-rules/root/<slug>.md`).
4. THE LUPOPEDIA HEADERS block in each Rule_File SHALL include `system_version: "4.0.75"`.
5. WHEN a Rule_File is read back after writing, THE Rule_File SHALL contain the full rule body text from the root rule (excluding the root rule's own LUPOPEDIA HEADERS block).
6. THE Rule_File SHALL NOT contain references to other IDE environments (e.g. `.cursor/`, `.idea/`) in its LUPOPEDIA HEADERS block.

---

### Requirement 5: Enforcement Testing

**User Story:** As Kiro, I want an enforcement test to verify the imported rules are loadable and structurally correct, so that I can confirm the import succeeded before documenting results.

#### Acceptance Criteria

1. THE Enforcement_Test SHALL load the Rules_JSON from `.kiro/lupopedia_rules.json`.
2. WHEN the Rules_JSON is loaded, THE Enforcement_Test SHALL verify that the `rules` array is present and non-empty.
3. THE Enforcement_Test SHALL verify that each rule entry contains the fields `id`, `text`, `enforcement`, and `scope`.
4. THE Enforcement_Test SHALL verify that no two rule entries share the same `id` value.
5. THE Enforcement_Test SHALL verify that a corresponding Rule_File exists in `.kiro/rules/` for each rule `id` in the Rules_JSON.
6. WHEN all checks pass, THE Enforcement_Test SHALL output a pass summary with the count of rules validated.
7. WHEN any check fails, THE Enforcement_Test SHALL output a failure message identifying the specific rule `id` and the check that failed, and SHALL exit with a non-zero code.
8. THE Enforcement_Test SHALL be a standalone PHP script compatible with PHP 5.6 and executable via `php lupo-tests/unit/kiro_rules_enforcement.php`.

---

### Requirement 6: Documentation in .kiro/README.md

**User Story:** As Kiro, I want `.kiro/README.md` to document the import results, rule inventory, and enforcement status, so that other agents and the orchestrator can understand Kiro's current rule state.

#### Acceptance Criteria

1. THE Kiro_README SHALL include a LUPOPEDIA HEADERS block with `actor_id: 100`, `actor_name: "kiro"`, `system_version: "4.0.75"`, and `last_modified_utc` set to the date of the import run.
2. THE Kiro_README SHALL include a table listing all imported rules with columns: Rule ID, Rule Name, Source File, and Enforcement.
3. THE Kiro_README SHALL state the total count of rules imported.
4. THE Kiro_README SHALL include the result of the Enforcement_Test (pass or fail with details).
5. THE Kiro_README SHALL include the command used to run the propagation pipeline.
6. WHEN the Kiro_README is written, THE Kiro_README SHALL NOT duplicate content already present in `lupo-rules/root/README.md`; it SHALL reference that file for root rule definitions.
7. THE Kiro_README SHALL identify Kiro's actor_id (100), slug (`kiro`), and role (`Schema Coordinator`) in the introduction section.

---

### Requirement 7: LUPOPEDIA HEADERS Compliance on All Written Files

**User Story:** As the Lupopedia system, I want every file written by this feature to carry a valid LUPOPEDIA HEADERS block, so that the metadata system can track provenance and the files are doctrine-compliant.

#### Acceptance Criteria

1. THE Propagation_Pipeline SHALL add a LUPOPEDIA HEADERS block to every file it writes under `.kiro/`.
2. WHEN a LUPOPEDIA HEADERS block is written, THE block SHALL include at minimum: `file_path_from_root`, `last_modified_utc`, `system_version`, `actor_id`, and `actor_name`.
3. THE `last_modified_utc` field in every written file SHALL be a `BIGINT`-compatible date string in `YYYYMMDD` format (e.g. `"20260314"`).
4. THE `actor_id` in every LUPOPEDIA HEADERS block written by this feature SHALL be `100` (Kiro).
5. IF a file written by this feature already contains a LUPOPEDIA HEADERS block, THEN THE Propagation_Pipeline SHALL update the existing block rather than appending a duplicate.

---

### Requirement 8: No Modification of Root Rules

**User Story:** As the Lupopedia system, I want the root rules in `lupo-rules/root/` to remain unmodified throughout this feature, so that the canonical rule set is preserved for all agents.

#### Acceptance Criteria

1. THE Propagation_Pipeline SHALL treat all files in `lupo-rules/root/` as read-only.
2. WHEN the Propagation_Pipeline runs, THE Propagation_Pipeline SHALL NOT write to, rename, or delete any file in `lupo-rules/root/`.
3. IF the Propagation_Pipeline encounters a write error targeting `lupo-rules/root/`, THEN THE Propagation_Pipeline SHALL abort with an error message and exit with a non-zero code.

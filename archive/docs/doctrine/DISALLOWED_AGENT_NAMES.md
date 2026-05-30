---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/DISALLOWED_AGENT_NAMES.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/DISALLOWED_AGENT_NAMES.md"
  status: "active"
  when_updated: "20260418160751"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/disallowed-agent-names.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/disallowed-agent-names"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "disallowed-agent-names"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "DISALLOWED_AGENT_NAMES -- reserved slugs and registration rules"
  summary: "Canonical disallowed agent names; validate_agent_name.py; Pillar 2 logging (P1-NAMESPACE-COLLISION-001); enforcement lifecycle; collision resolution; ACTOR_REGISTRY alignment."
---
# DISALLOWED_AGENT_NAMES

## 1. Purpose

**New** agent packs under `agents/{slug}/` and the `agent_key` / `slug` fields in new `agent.json` files **must not** collide with system authority strings, recursion-prone tokens, generic placeholders, or already-reserved Lupopedia and IDE identities.

Canonical packs that already own those slugs (for example `agents/wolfie/`, `agents/cursor/`) are not violations; the rule blocks **additional** registrations that would impersonate them.

**Enforcement surface:** `python scripts/validate_agent_name.py` (see section 5). This document is the normative human-readable list; the script embeds the same exact-name set and must be updated in lockstep when this file changes.

## 2. Exact-name disallow list (lowercase)

| Category | Names | Reason |
|----------|-------|--------|
| System reserved | `root`, `system`, `admin`, `superuser`, `kernel` | Implies system-level authority or kernel scope |
| Dangerous | `meta`, `self`, `this`, `parent` | Recursion, reflection, or OO confusion risk |
| Generic | `agent`, `ai`, `bot`, `assistant` | Too vague; collision with prose and tooling |
| Reserved Lupopedia personas / services | `wolfie`, `lilith`, `thoth`, `rose`, `agape`, `carmen`, `hermes`, `anubis`, `kairos`, `vish`, `hephaestus`, `iris`, `asclepius` | Core registry-backed identities; **`vish`** blocks shorthand confusion with **`vishwakarma`** and internal naming collisions; prevents abbreviated impersonation of registry-backed identities |
| Reserved IDE facets | `cursor`, `antigravity`, `antigravity-ide`, `vscode`, `vscode-ide`, `windsurf`, `kiro`, `zed`, `trae`, `warp`, `cascade`, `castcade` | Facet packs and typosquat (`castcade`) |
| Reserved CLI / external product tokens | `claude`, `gemini`, `grok`, `auggie`, `chatgpt`, `copilot` | External integration naming collisions |

**Machine-readable (YAML):**

```yaml
disallowed_agent_names_exact:
  - root
  - system
  - admin
  - superuser
  - kernel
  - meta
  - self
  - this
  - parent
  - agent
  - ai
  - bot
  - assistant
  - wolfie
  - lilith
  - thoth
  - rose
  - agape
  - carmen
  - hermes
  - anubis
  - kairos
  - vish
  - hephaestus
  - iris
  - asclepius
  - cursor
  - antigravity
  - antigravity-ide
  - vscode
  - vscode-ide
  - windsurf
  - kiro
  - zed
  - trae
  - warp
  - cascade
  - castcade
  - claude
  - gemini
  - grok
  - auggie
  - chatgpt
  - copilot
```

## 3. Pattern-based rules

| Pattern (regex) | Meaning |
|-----------------|--------|
| `^\_` | Leading underscore (hidden / private convention) |
| `^\.` | Leading dot (hidden file convention) |
| `[/\\]` | Path separators |
| `\.\.` | Parent directory traversal |

## 4. Structural rules (new agents)

1. **Lowercase only:** `agent_key` and pack directory name must be lowercase ASCII.
2. **Charset:** Only `a-z`, `0-9`, and hyphen (`-`). No spaces or underscores in new packs (hyphen is the only allowed separator).
3. **Exact and pattern:** Must not match any exact disallowed name (section 2) or pattern (section 3).
4. **Uniqueness:** At most one top-level pack per slug; `agent_key` values must be unique across all `agents/*/agent.json` files discovered by the scanner (see script `--scan-root`).
5. **Pre-merge validation:** New agent packs **MUST** pass **`python scripts/validate_agent_name.py --scan-root`** before merge or registration (exit **0**; fix or document waiver per change control before shipping).

## 5. Tooling

```bash
# Validate a proposed NEW slug (reserved + structural rules; exit 0 = ok, 1 = invalid)
python scripts/validate_agent_name.py my-new-agent

# Validate agent_key (or slug) inside an existing agent.json (same rules as new slug)
python scripts/validate_agent_name.py --file agents/my-new-agent/agent.json

# Scan repository packs: dirname charset/patterns + duplicate agent_key across packs.
# Does not fail on reserved names for existing canonical directories.
python scripts/validate_agent_name.py --scan-root
```

## 6. Relationship to actors and agents

Reserved **actor** identities live in `database/lupopedia/actors/actor_id/registry.json` and related tables. This doctrine governs **agent pack slugs** under `agents/` so new packs do not impersonate personas, facets, or product names. See also `docs/ACTOR_REGISTRATION_CHECKLIST.md` and PRD 07 / PRD 50 for coordination identity rules.

## 7. Change control

Any addition or removal from section 2 requires: (1) edit this file, (2) edit `scripts/validate_agent_name.py` constants to match, (3) run `python scripts/validate_agent_name.py --scan-root`, (4) changelog buffer entry.

## 8. Pillar 2 integration (learning transfer)

**Canonical defect ID (AGAPE taxonomy):** **`P1-NAMESPACE-COLLISION-001`** — same literal as **`docs/doctrine/AGAPE_DEFECT_TAXONOMY.md`** (agent pack / namespace collision row).

When a violation is detected (for example a **new** agent pack or `agent_key` using a disallowed name):

1. **Detection:** `python scripts/validate_agent_name.py` exits **non-zero** for that candidate.
2. **Pattern ID:** **`P1-NAMESPACE-COLLISION-001`** — namespace or impersonation collision against reserved or generic tokens (**Pillar 1** survivability framing; **Pillar 2** logging for recurrence).
3. **Logging:** The violation **MUST** be logged under `changelog-pending/` as JSON with **`pattern_id`** set exactly to **`P1-NAMESPACE-COLLISION-001`**, plus **`files_changed`**, **`summary`**, and **`agent_id`** (facet slug) per **`docs/doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md`**.
4. **Memory hook:** **AGAPE** (metrics / survivability layer) **MUST** increment a **recurrence counter** keyed by this **`pattern_id`** when violation logging is performed, so repeated collisions surface in operator review (implementation binds counter storage to the chosen AGAPE store; this doctrine requires the hook, not a specific table name).
5. **Verification:** After remediation, re-run the validator and **`--scan-root`**; confirm **zero** matches for the disallowed pattern on the corrected paths.

## 9. Enforcement lifecycle

| Phase | Action | Result |
|-------|--------|--------|
| Install-time | `python scripts/validate_agent_name.py --scan-root` | **Hard fail** — block installation / wizard advance until violations are cleared or explicitly waived by documented operator-only procedure (if any exists for that installer). |
| Runtime | Agent registration API (or equivalent pack registration path) | **Soft fail** — reject registration, return structured error, log violation with **`pattern_id`**. |
| CI | Pre-merge hook (or CI job) running the same scanner | **Block merge** if a violation is detected on the branch. |

## 10. Collision resolution strategy

When a collision is detected:

1. **Reject** the name (do **not** auto-rename packs or keys silently).
2. **Suggest** alternatives (for example `my-agent` instead of `agent`; hyphenated distinct slugs that do not match section 2 or section 3).
3. **Log** the violation with **`pattern_id`**: **`P1-NAMESPACE-COLLISION-001`** (see section 8 and **`AGAPE_DEFECT_TAXONOMY.md`**).
4. **Continue** system operation for other subsystems (do **not** crash the whole runtime on a single bad registration attempt); the failing path returns an error only.

This output complies with Lupopedia Constitutional Root Rules.

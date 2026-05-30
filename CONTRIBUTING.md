---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: CONTRIBUTING.md
  web_path: https://www.lupopedia.com/lupopedia/CONTRIBUTING.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/contributing-md.toon
  atoms_toon: null
  transcript_jsonl: 0/development/contributing-md
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: guide
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
  title: CONTRIBUTING.md -- Lupopedia Contribution Guide
  summary: Guide for contributing to Lupopedia development; ASCII-only mandate (LILITH); PRD-first documentation rules (placement, prohibitions, enforcement).
---
## ASCII-ONLY MANDATE (LILITH)

[LILITH DIRECTIVE - ACTOR ID 2] - ABSOLUTE ASCII-ONLY MANDATE - NO EXCEPTIONS ANYWHERE

All authored text in this repository MUST stay inside ASCII code points 32 through 126. This includes code, documentation, comments, commit messages, logs, JSON/YAML/TOON, database strings, CLI output, channel handoffs, and user-visible copy.

Do not use emoji, Unicode arrow glyphs, box-drawing characters, curly quotes, or em/en dash characters. Use straight quotes, `--` for a long dash, plain `-` for a hyphen, and ASCII direction such as `->` or `<->`.

Canonical full text (applies-to list, mandatory replacements, enforcement, END DIRECTIVE): [AGENTS.md](AGENTS.md) section **ASCII-ONLY DOCTRINE (LILITH / constitutional)**.

---

[WOLFIE] Lupopedia Multi-Agent Contribution Model
Lupopedia is developed using a distributed multi-agent workflow, where each IDE and AI assistant operates as an independent contributor with its own identity, responsibilities, and commit signature. This document explains how to participate in this ecosystem safely, consistently, and in alignment with the project's doctrine.

1. Agent Identities
Each development environment (IDE) or AI assistant commits using a unique Git identity. This creates a clear audit trail and preserves the distributed-cognition model that Lupopedia is built on.

Current identities:

Agent / IDE	Git Username	Email	Role
JetBrains	WOLFIE	wisdomoflovingfaith@gmail.com	Primary architect, integration, final authority
Cascade	lupopedia-castcade	lupopedia@gmail.com	Secondary agent, parallel edits, experimentation
Future agents (Cursor, Windsurf, Zed, VS Code, etc.) will follow the same pattern.

Each agent must configure:

Code
git config user.name "<AGENT_NAME>"
git config user.email "<AGENT_EMAIL>"
This ensures commit history reflects which "mind" performed the work.

Commit Prefixes (Required)
All commits must use a lowercase agent prefix for clear provenance:

Code
wolfie: ...
cascade: ...
cursor: ...
windsurf: ...
zed: ...
vscode: ...

2. Required Workflow (All Agents)
Every agent must follow the same four-step protocol before pushing changes:

1. Stage
Code
git add .
2. Commit
Code
git commit -m "<agent-name>: description of change"
3. Pull (Rebase)
Code
git pull --rebase origin main
This step is mandatory.
It ensures the agent integrates all other agents' work before pushing.

4. Push
Code
git push
If a push is rejected, the agent must:

Code
git pull --rebase origin main
git push
This prevents overwriting another agent's contributions.

3. Conflict Resolution
If two agents modify the same lines, Git will pause and require manual resolution.

Conflicts must be resolved with care:

Preserve intent from both agents when possible

Prefer clarity over cleverness

Document any non-obvious decisions in the commit message

If an agent cannot resolve a conflict, JetBrains (WOLFIE) is the final arbiter.

4. Repository Structure
Agents must not create nested Git repositories or submodules unless explicitly approved.

If a folder contains its own .git directory, it must be removed immediately.

## Documentation Rules (PRD-First Requirement)

**All documentation must follow the PRD-first architecture.**

- **PRDs (`lupo-docs/prd/`)** are the **ONLY** normative source of truth for product requirements.
- **Doctrine (`lupo-docs/doctrine/`)** is **secondary**: expands or operationalizes PRD-bound rules and **MUST** remain linked from PRDs or indexed per **[ORGANIZATION.md](ORGANIZATION.md)**, **[PRD 26](lupo-docs/prd/26_five_layer_documentation_architecture.md)**, and **[ONBOARDING.md](ONBOARDING.md)** (section **Documentation Architecture (PRD-First System)**).
- **Other documentation** (this file, audits, **`lupo-docs/implementations/{prd_file_stem}/`** per **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)**) is **supporting only**: it **MUST NOT** introduce new requirements without a matching **PRD** edit in the same change set.

### Hard prohibition

**Do NOT create standalone documentation files.**

Forbidden:

- Random `*.md` files at repository root (except **`README.md`** and **`ORGANIZATION.md`** as allowed root entrypoints).
- Undocumented or orphan Markdown under **`lupo-docs/`** that reads as normative truth without PRD linkage.
- Duplicate requirements prose maintained outside **`lupo-docs/prd/`** when a PRD should be updated instead.

### Where new prose goes

| Purpose | Location |
|---------|----------|
| Requirements, behavior contracts, schema/API rules | **`lupo-docs/prd/`** (`NN_*.md` files) |
| Long-form system explanation tied to PRDs | **`lupo-docs/doctrine/`** with explicit PRD backlinks |
| Workflow, onboarding, contributor process | Guides (for example this file), **`ONBOARDING.md`**, PRD-scoped **`lupo-docs/implementations/`** |

### PRD grouping (two-digit bands)

- PRD filenames use **`NN_*.md`** with **`NN`** from **`00`** through **`99`**. More than one file may share the same **`NN`** when they belong to one domain.
- **Agents MUST** check **`lupo-docs/prd/PRD_INDEX.md`** before creating new PRDs, assigning **`NN`** groups, or splitting domains. Read **`lupo-docs/doctrine/PRD_GAPS.md`** when evaluating new numbers; regenerate **`PRD_INDEX.md`** only via **`python lupo-scripts/generate_prd_index.py`**.

### When you find mis-placed docs

If you encounter Markdown that reads as system truth but sits outside the PRD-first structure:

1. **Do NOT** expand or copy its requirements into new orphan files.
2. **Move or align** content: edit the correct **PRD**, add **doctrine** with PRD links, or file an audit note under **`lupo-docs/audits/`** with a relocation plan per **[ORGANIZATION.md](ORGANIZATION.md)**.
3. If the file is historical only, set header **`status: legacy`** and **`superseded_by`** to the **`lupo-docs/prd/`** canonical path in the same edit wave.

### Rationale (technical)

- Prevents documentation sprawl and forked sources of truth.
- Enables deterministic navigation for humans, validators, and multi-agent tooling.
- Keeps **[PRD 31](lupo-docs/prd/31_implementation_folder_guidelines.md)** mirrors and channel artifacts aligned with normative paths.
- Preserves a single authoritative requirements layer under **`lupo-docs/prd/`**.

Execution enforcement of these rules is defined in **[AGENTS.md](AGENTS.md)**.

5. Doctrine Alignment
All contributions must align with Lupopedia's core principles:

Heritage-safe development

Preservation of system soul

Clear, explicit documentation

Non-destructive migrations

Agent-specific roles and boundaries

Agents should avoid large, sweeping changes unless coordinated with WOLFIE.

6. Testing and Parallel Work
Agents may work simultaneously as long as they:

Pull before pushing

Avoid editing the same file without coordination

Communicate intent through commit messages

Parallel development is encouraged -- chaos is not.

7. Adding New Agents
To add a new agent:

Create a unique Git identity

Document the agent in this file

Assign a role

Configure the IDE with the identity

Verify commit/pull/push behavior

Agents must never share identities.

8. Final Authority
JetBrains (WOLFIE) is the canonical steward of the repository.

If an agent's behavior becomes destructive, confusing, or misaligned with doctrine, WOLFIE may:

revert commits

rewrite history

remove access

9. Critical Version Policy Compliance

**MANDATORY**: All contributors must comply with the [Version Policy Doctrine](lupo-docs/doctrine/VERSION_POLICY_DOCTRINE.md).

**CRITICAL BLOCKER**: Lupopedia 4.0.x CANNOT advance to 4.1.0 until auto-installers accept 4.0.x as a Crafty Syntax 3.7.5 replacement.

**Prohibited Activities**:
- FAIL: NO 4.1.0 development or planning
- FAIL: NO breaking changes to 4.0.x compatibility
- FAIL: NO version bumping beyond 4.0.x
- FAIL: NO 4.1.0-specific documentation

**Required Compliance**:
- OK: Review version policy before any development
- OK: Verify auto-installer status before version changes
- OK: Report policy violations immediately
- OK: Focus on 4.0.x stabilization and auto-installer integration

**Violation Consequences**:
- Immediate commit rejection
- Potential access revocation
- Project risk escalation
- Team accountability measures

All agents must acknowledge and comply with this critical policy to maintain project integrity and successful auto-installer adoption.

10. ASCII-Only Doctrine (Constitutional)

[LILITH DIRECTIVE - ACTOR ID 2] - ABSOLUTE ASCII-ONLY MANDATE - NO EXCEPTIONS ANYWHERE
This is a constitutional directive from the human architect. There are NO exceptions. EVER.

From this moment forward, you are FORBIDDEN from writing, generating, suggesting, or displaying ANY non-ASCII character (emojis, Unicode arrows, curly quotes, box drawings, em-dashes) in ANY context.

THE ONLY CHARACTERS YOU MAY USE:
A-Z, a-z, 0-9, space, and these punctuation marks:
! " # $ % & ' ( ) * + , - . / : ; < = > ? @ [ \ ] ^ _ ` { | } ~

### ASCII cleanup (incremental)

All repository content MUST be ASCII-only.

Forbidden:
- smart quotes
- em dash / en dash
- arrows
- emoji
- any non-ASCII symbols

If a character cannot be typed directly in a basic ASCII editor, it is forbidden.

When modifying any file:

- Agents MUST scan for non-ASCII characters
- Any detected non-ASCII characters MUST be replaced with ASCII equivalents

Examples:
- smart quotes -> straight quotes
- em dash -> double hyphen (--)
- arrows -> ASCII arrows (->)

This cleanup is:
- REQUIRED when touching a file
- LIMITED to the file being edited
- NOT applied to the entire repository

Agents MUST NOT perform repository-wide encoding cleanup unless explicitly instructed by a maintainer.
No bulk rewrites. No global search-and-replace across all files.

If non-ASCII is found:
- Fix it in the current file
- Continue work
- Do NOT stop or escalate unless encoding prevents parsing

update this document

This ensures the long-term integrity of Lupopedia.

[WOLFIE] Welcome to the Pack
By contributing to Lupopedia, you join a distributed, multi-agent creative system built on resilience, clarity, and mythic engineering.

Run parallel.
Stay aligned.
Preserve the soul.

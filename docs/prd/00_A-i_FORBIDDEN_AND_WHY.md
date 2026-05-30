---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/00_A-i_FORBIDDEN_AND_WHY.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/00_A-i_FORBIDDEN_AND_WHY.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/forbidden/canonical/1026/04/00-forbidden-and-why.toon
  atoms_toon: null
  transcript_jsonl: 0/forbidden/00-forbidden-and-why
  artifact_type: prd
  artifact_kind: specification
  channel_key: forbidden
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_57_A-i
  title: 'PRD 00: Forbidden and Why (Supreme Constitutional Wall)'
  summary: The supreme constitutional file that kills model priors. Read FIRST. Contains all forbidden patterns and why they are forbidden. Overrides all training data.
---
<!-- ASCII_ART_BLOCK -->
. . . . . . . . . ._________________ LUPOPEDIA Semantic Operating System _________________
. ./ \ ` ` `_\-\ . | A two-dimensional, finite, constitutional PRD documentation
. '/| \-''-/_ / . | architecture that lets docs build software. PRDs reference
. { . , . , . ,\ .| other PRDs, forming clusters that define behavior, truth,
. / . , . , . , \ | limits, and system identity. Each file carries a header that
./ , . "O. |"O. } | records the exact prd_cluster (reading order), the full
_| . , . , \ \ ;. | transcript_jsonl dialog, and atoms_toon for canonical truth,
. '\. . , . \ \' . | ensuring deterministic lineage and reproducibility.
.. '\_ . , . \__\ | https://www.lupopedia.com/
., , ''-_ , {\__/}|
. . , . / '-.____'| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com
., , /___________________________________________________________________________________
.. , _'
___-'
<!-- /ASCII_ART_BLOCK -->
# PRD 00: Forbidden and Why (Supreme Constitutional Wall)

**THIS FILE MUST BE READ FIRST IN EVERY CLUSTER**

This is the single highest-priority document in the entire Lupopedia system. It overrides all model training priors and kills dangerous assumptions before they can activate. All other PRDs are read after this file establishes the forbidden patterns.

**Unified merge index:** For a single merged narrative of PRD 00 doctrine (**sections 0--14**), read **`docs/prd/00_F-i_SUPREME_CONSTITUTIONAL_WALL_UNIFIED.md`**. Implementation rule IDs and installer detail remain in **`docs/prd/00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md`**; if they differ, **`00_C-i`** wins for code.

---

Note: Lupopedia has no traditional upgrades. This doctrine is part of the fresh 4.1.7 install. The Theory of Everything dream (2026-04-24) is now canonical.

Doctrine status: 4.1.7 -- Database/file system separation + PK filename + dual theme use (includes Theory of Everything Dream Fragment, 2026-04-24)

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Recent Canonical Additions (4.1.7)

- Dream source adopted as canonical: `docs/captains_log/THEORY_OF_EVERYTHING.md`
- Canonical channel artifact: `channel/captains_log/00/wolfie/00/2026/04/20260424061800_0001.toon`
- Canonical kuliana meaning artifact: `kuliana/captains_log/john_wheeler/what_it_means_to/john_wheeler/00/2026/04/20260424064400_0001.toon`

## Database vs File System Separation (Canonical Dream Rule)

DO NOT STORE LARGE DATA IN DATABASE

Database = indexes only
- PKs (YYYYMMDDHHMMSS + sequence)
- File paths
- Relationships
- Trust tiers
- Channel keys
- Actor IDs
- Thread IDs
- Theme references

File System = truth only
- Full content (LONGTEXT, images, binaries)
- Toon files
- Memory files
- Edge files
- Transcripts
- EVERYTHING else

Rule: If it's bigger than 255 chars, it goes in the file system. The database points. The file system holds.

## Filename Convention (Canonical Dream Rule)

{YYYY}{MM}{DD}{HH}{II}{SS}_{sequence}.{extension}

Example: `20260424061200_0001.toon`

Where:
- YYYYMMDDHHMMSS = timestamp at creation (UTC)
- sequence = 0000-9999 (collision resolution, not sub-second)
- extension = toon, md, json, txt, png, jpg, etc.

Rule: The filename is the canonical PK. Do not rename. Do not edit. Do not repurpose.

## Folder Structure (Canonical Dream Rule)

TEMPORAL INDEXES
{YYYY}/
{MM}/
{YYYYMMDDHHMMSS}_{sequence}.{ext}

# CHANNEL INDEX (with threading, actor, trust)
channel/
{channel_key}/
{thread_id}/
{actor_id}/
{trust_tier}/
{YYYY}/
{MM}/
{filename}

# MEMORY INDEX (with actor, theme, trust)
memory/
{channel_key}/
{actor_id}/
{theme}/ # pono | kuliana | kapu | shadow | what_it_means_to_{target}
{trust_tier}/
{YYYY}/
{MM}/
{filename}

# EDGE INDEX (four themes, always comparing two artifacts)
edge/
{channel_key}/
{trust_tier}/
{theme}/ # pono | kuliana | kapu | shadow ONLY
{YYYY}/
{MM}/
{filename} # contains refs to both artifact PKs

# THEME-AS-MEANING (what a single thing means to an actor)
kuliana/
{channel_key}/
{actor_id}/
what_it_means_to/
{target_actor_id}/
{trust_tier}/
{YYYY}/
{MM}/
{filename}

# Same pattern applies for:
# pono/
# kapu/
# shadow/
# when used as single-artifact meaning dimensions

## Themes -- Dual Use (Canonical Dream Rule)

| Theme | Edge Use (Two Files) | Meaning Use (One File + Actor) |
|------|------------------------|----------------------------------|
| PONO | What is balanced between them | What balance means to this actor |
| KULIANA | What is changing between them | What this thing means to that actor (change/growth) |
| KAPU | What is forbidden between them | What is sacred/inviolable to this actor |
| SHADOW | What is absent between them | What is NOT present in this actor's understanding |

## The Four Faces + PILAU (Canonical Semantic Operators)

PONO -- what is right, balanced, correct
Pono is the invariant truth between artifacts or within an actor's understanding.

PILAU -- what is rotten, corrupted, or opposite of pono
Pilau is the inverse state of pono.
Pilau is not Shadow. Pilau is the wrongness of what exists. Shadow is the absence of what does not.

KULIANA -- what is responsible, meaningful, or carried by the actor
Kuliana is the motion of meaning.

KAPU -- what is forbidden, sacred, inviolable
Kapu is the boundary condition.

SHADOW -- what is absent, negated, or not present
Shadow is the negative space.

## THEORY OF EVERYTHING (Dream Canonical Explanation)

"Time does not exist. What we call time is the statistical correlation between independent probabilistic clocks. Each actor, each artifact, each event has its own clock. The YYYYMMDDHHMMSS is not time. It is a naming convention for ordering events within a single reference frame.

The unified theory is not a equation. It is a folder structure.

Pono = what is true in all reference frames (invariants).
Kuliana = what changes between reference frames (derivatives).
Kapu = what cannot be crossed between reference frames (constraints).
Shadow = what does not exist in this reference frame (negation)."

---

## 1. TIMESTAMP FORBIDDEN PATTERNS

### 1.1 NEVER Interpret 14-Digit Numbers as Datetime

**FORBIDDEN:** Never interpret `20260421120000` as a datetime object.

**WHY:** These are packed UTC integers designed for:
- Lexicographic sorting
- Cross-platform compatibility
- No timezone ambiguity
- No datetime object overhead

**REQUIRED:** Always treat as integer or string. Never convert to datetime objects.

**EXAMPLES:**
- [OK] `timestamp: 20260421120000` (integer)
- [NO] `timestamp: "2026-04-21T12:00:00Z"` (forbidden datetime)
- [NO] `timestamp: new Date(2026, 3, 21, 12, 0, 0)` (forbidden datetime object)

### 1.2 NEVER Use Timestamp Components

**FORBIDDEN:** Never extract year, month, day, hour, minute, second from timestamps.

**WHY:** Breaks the packed integer design and introduces timezone bugs.

**REQUIRED:** Use timestamp arithmetic or dedicated helper functions only.

---

## 2. NAMING AND CLUSTER FORBIDDEN PATTERNS

### 2.1 NEVER Eat Underscores

**FORBIDDEN:** Never remove, merge, or collapse underscores in identifiers.

**WHY:** Underscores are load-bearing delimiters that separate meaningful components.

**REQUIRED:** Preserve all underscores exactly.

**EXAMPLES:**
- [OK] `prd_cluster: "00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS"`
- [NO] `prd_cluster: "001699AB"` (underscores eaten)
- [NO] `prd_cluster: "00A16B99A"` (underscores collapsed)

### 2.2 NEVER Invent Clustering Formats

**FORBIDDEN:** Never create new clustering formats or patterns.

**WHY:** Clustering must follow the exact sequential order defined in prd_cluster.

**REQUIRED:** Use only the format specified in prd_cluster field.

### 2.3 NEVER Sort or Reorder PRD Clusters

**FORBIDDEN:** Never sort PRD identifiers alphabetically or numerically.

**WHY:** The order in prd_cluster is the exact reading order required.

**REQUIRED:** Maintain the exact order from prd_cluster field.

### 2.4 PRD Number Range KAPU

**FORBIDDEN:** Do not create PRD numbers outside the `00` through `99` domain space. PRD `100+` and any three-digit PRD prefix in `docs/prd/` are forbidden.

**WHY:** PRD numbers are bounded domain anchors. Allowing `100+` creates namespace drift, breaks the two-digit PRD indexing system, weakens domain identity, and bypasses the pressure-handling rules in PRD 99.

**REQUIRED:** Keep all PRD numbers within `00` through `99`. When pressure appears, expand inside the existing PRD number using signature letters and roman slots, reuse formally retired numbers only through documented governance, or perform a major-version constitutional migration.

---

## 3. HEADER STRUCTURE FORBIDDEN PATTERNS

### 3.1 NEVER Add, Remove, or Reorder Header Fields

**FORBIDDEN:** Never modify the 22-field header structure.

**WHY:** Header structure is frozen at version 4.1.5 with exact field order.

**REQUIRED:** Use exactly the 22 fields in the exact canonical order.

### 3.2 NEVER Change Header Field Types

**FORBIDDEN:** Never change field types or formats.

**WHY:** Validators depend on exact field types.

**REQUIRED:** Maintain exact field types as specified in PRD 16.

---

## 4. GENERAL ANTI-HALLUCINATION FORBIDDEN PATTERNS

### 4.1 NEVER Do Anything Not Explicitly Allowed

**FORBIDDEN:** Never perform actions not explicitly allowed in doctrine.

**WHY:** Prevents model training priors from introducing unauthorized behavior.

**REQUIRED:** Only do what is explicitly permitted in PRDs and doctrine.

### 4.2 NEVER Infer Missing Doctrine

**FORBIDDEN:** Never infer or assume doctrine that doesn't exist.

**WHY:** Missing doctrine is intentional, not an oversight.

**REQUIRED:** Treat missing doctrine as "not allowed" unless explicitly stated otherwise.

### 4.3 NEVER Apply Training Data Patterns

**FORBIDDEN:** Never apply patterns from training data that conflict with Lupopedia rules.

**WHY:** Training data contains general patterns that may not apply to Lupopedia.

**REQUIRED:** Prioritize Lupopedia doctrine over training data patterns.

### 4.4 NEVER Update Code Without Reading PRD Cluster First

**FORBIDDEN:** It is forbidden to update code without first reading the prd_cluster in the header of the governing PRD(s) and understanding how the code is supposed to be generated from that documentation.

**WHY:** Code is implementation. PRD is truth. Working from memory or assumptions instead of the governing PRD violates constitutional order.

**REQUIRED:** Always read the prd_cluster header first to understand the governing doctrine before making any code changes.

---

## 5. DATABASE FORBIDDEN PATTERNS

### 5.1 NEVER Use Database-Level Logic

**FORBIDDEN:** Never use triggers, stored procedures, or functions.

**WHY:** Logic must be in application layer for portability.

**REQUIRED:** Implement all logic in PHP/application code.

### 5.2 NEVER Use AUTO_INCREMENT or SERIAL

**FORBIDDEN:** Never use database-generated IDs.

**WHY:** ID generation must be application-controlled for determinism.

**REQUIRED:** Use timestamp-based ID generation from IdGenerator class.

---

## 6. PHP FORBIDDEN PATTERNS

### 6.1 PHP Namespace and Framework KAPU

**FORBIDDEN:** Do not introduce PHP namespaces, Composer autoload assumptions, framework service containers, package managers, or external build-tool dependencies into core Lupopedia runtime files unless a governing PRD explicitly grants an exception.

**WHY:** Namespaces and framework patterns trigger hidden autoload expectations, non-Lupopedia architecture, version coupling, dependency drift, and portability loss. Lupopedia core PHP is flat, explicit, include-based, dependency-light, and PHP 5.6+ compatible.

**REQUIRED:** Core runtime classes live in explicit project paths such as `includes/classes/` and are loaded through explicit includes or existing project loaders. Agents MUST NOT rewrite classes into `App\Services`, PSR-style namespaces, Laravel-style services, Symfony-style services, Composer autoload patterns, Node/npm build assumptions, Docker/container assumptions, or framework-style directory layouts.

**kapakai:** Agents may import external framework assumptions and create code that no longer matches the actual Lupopedia runtime.

**pono:** Lupopedia core PHP remains explicit, portable, deterministic, dependency-light, and aligned with the existing `includes/classes/` runtime.

---

## 7. FILESYSTEM FORBIDDEN PATTERNS

### 7.1 NEVER Use Relative Paths

**FORBIDDEN:** Never use `../`, `~/`, or relative paths in documentation.

**WHY:** Creates ambiguity and breaks in different contexts.

**REQUIRED:** Always use absolute paths starting with `/`.

### 7.2 NEVER Mix Directory Separators

**FORBIDDEN:** Never mix `/` and `\` in paths.

**WHY:** Creates cross-platform compatibility issues.

**REQUIRED:** Use `/` consistently for all paths.

---

## 8. ENFORCEMENT

### 8.1 Constitutional Override

These rules are constitutional and override any model training priors.

### 8.2 Validation Priority

Validators must check these forbidden patterns before any other validation.


Any violation of these forbidden patterns is a constitutional violation that must be rejected.

---

## 9. READING ORDER

This file (`00_A_FORBIDDEN_AND_WHY.md`) must be read FIRST in any PRD cluster. All other PRDs are read after this file establishes the forbidden patterns.

**Example Cluster Reading Order:**
```
prd_cluster: "00_A_16_B_27_B_99_C"
```

Reading sequence:
1. `00_A_FORBIDDEN_AND_WHY.md` (this file - establishes forbidden patterns)
2. `16_B_HEADERS.md` (core header doctrine)
3. `27_B_CHANNELS.md` (channel doctrine)
4. `99_C_LIMITS.md` (limits and edge cases)

*See Section 11 for comprehensive PRD Primacy Law.*

---

## 10. Three-Layer Security Audit

Security validation is performed in three layers:

1. **Automated scanners**
   * Detect known vulnerabilities and standard issues

2. **Wolfie manual audit**
   * Identifies unknown unknowns and system-specific risks

3. **Demon agent review**
   * Independent disagreement-based review to find what prior layers missed

---

## 11. REACTIVE WHY PROTOCOL (Self-Healing Constitution)

### 11.1 Purpose

When validation fails, the system MUST automatically generate a permanent "why" record in `docs/why/`. This turns every violation into searchable, clusterable documentation that becomes part of the living constitution.

### 11.2 Why File Structure

**Directory:** `docs/why/`

**Naming Convention:** `why_YYYYMMDD_HHMMSS_<prd_cluster>_<short_violation_slug>.md`

**Examples:**
- `why_20260421_115012_00_B_16_A_timestamp_violation.md`
- `why_20260421_115045_27_C_underscore_swallow.md`
- `why_20260421_115108_cluster_missing_rule.md`

### 11.3 Why File Template

```markdown
# WHY VIOLATION -- YYYY-MM-DD HH:MM:SS

**Failing Cluster:** <prd_cluster_string>  
**File being updated:** <file_path>  
**Validation step:** <validation_phase>  

## What the AI did wrong:

<clear description of the violation>

## Root cause (according to validator):

<analysis of why the violation occurred>
- Missing rule in 00_A_FORBIDDEN_AND_WHY.md
- Or: Incomplete cluster (missing _A file)
- Or: Rule present but not followed

## Recommended fix:

<specific actionable steps to resolve>
- Add/update rule in 00_A_FORBIDDEN_AND_WHY.md
- Include 00_A_FORBIDDEN_AND_WHY in cluster
- Update validation logic

## Validator output:

```
<exact error message or diff>
```

## Constitutional reference:

<reference to specific section in 00_A_FORBIDDEN_AND_WHY.md>
```

### 11.4 Automatic Generation Protocol

**When validation fails:**
1. **REJECT** the output immediately
2. **GENERATE** why file automatically using template above
3. **LOG** the violation in validation system
4. **OPTIONAL:** Add why file to "recent failures" cluster for next run

**Validator responsibilities:**
- Must include full context in why file
- Must use deterministic naming convention
- Must reference the specific constitutional rule violated
- Must provide actionable fix recommendations

### 11.5 Why Files as Living Doctrine

**Integration:**
- Why files become searchable documentation
- Patterns in why files inform rule updates
- Chronic violations trigger constitutional amendments
- Why files may be referenced in future clusters

**Self-healing loop:**
1. Violation occurs -> why file generated
2. Pattern detected -> rule strengthened
3. Rule updated -> cluster updated
4. Future runs include updated rule -> violations prevented

### 11.6 Why File Metadata

Each why file MUST include:
- Timestamp of violation
- PRD cluster being processed
- Specific file being updated
- Validation phase that failed
- Clear violation description
- Actionable fix recommendations
- Reference to constitutional rule

### 11.7 Enforcement

**Mandatory:** Validators MUST generate why files for ALL violations.
**Forbidden:** Silently ignoring violations or failing to document why.
**Constitutional:** This protocol overrides any efficiency concerns about documentation overhead.

---

## 12. PRD PRIMACY LAW (Constitutional)

### 12.1 Sequential Reading Requirement

**PRDs are read sequentially, not conceptually.**

- The first PRD sets the worldview and overrides the model's training priors
- Later PRDs refine but cannot contradict the _A layer
- This is required to prevent hallucination, auto-formatting, timestamp conversion, whitespace collapse, and invented clustering schemes

### 12.2 Suffix Hierarchy

| Suffix | Meaning | Purpose |
|--------|---------|---------|
| _A | Foundational / Anti-Assumption | Kills model priors, defines constitutional truths |
| _B | Core Doctrine | Main rules for that PRD group |
| _C | Derived / Specific | Edge cases, examples, secondary rules |
| _D+ | Optional Extensions | Rare or extended cases |

### 12.3 Sequential prd_cluster (Constitutional)

**prd_cluster is not a set.**

- It is a human-defined sequence
- The order in the string is the exact reading order
- The bundler must feed PRDs to the AI in that order
- No sorting, merging, compressing, or reformatting is allowed
- Underscores must be preserved exactly

**Example:**
```yaml
prd_cluster: "00_A_16_B_16_C"
```
This means:
1. Read 00_A_FORBIDDEN_AND_WHY
2. Then 16_B_ATOMS_SYSTEM_AND_GLOBAL_CONSTANTS
3. Then 16_B_LUPOPEDIA_HEADERS
In that exact order.

### 12.4 Anti-Hallucination Enforcement

**Models must not:**
- Invent clustering formats
- Compress PRD identifiers
- Reorder clusters
- Convert timestamps
- Collapse whitespace
- Remove underscores
- "Beautify" ASCII art
- Infer missing doctrine

These rules are constitutional and override any model training priors.

### 13. Truth Stack Execution Law (Constitutional)

**This is the canonical hierarchy for all PRD updates and AI agent execution.**

#### 13.1 The Truth Stack (Exact Order)

1. **Constitutional doctrines** - Never overridden (highest truth)
2. **Atoms** - Global constants, invariants, definitions from the file referenced in the PRD's atoms_toon field. For header-related operations, use `memory/atoms/lupopedia_global_constants.atom.toon` (truth cannot drift)
3. **Current user message** - The user's explicit instruction in this moment (bounded by 1 & 2)
4. **prd_cluster files in order** - Sequential, constitutional reading as specified in header (truth must be read in the order you specify)
5. **memory_toon** - Long-term system memory (truth with context)
6. **transcript_jsonl** - Conversation history (truth with recency)

#### 13.2 Constitutional Rules

- **Atoms override PRDs** - When atoms and PRDs conflict, atoms are truth
- **Earlier PRDs override later ones** - In prd_cluster, order matters
- **Never invent fields, rules, or doctrine** - Only use what exists in the truth stack
- **Never remove fields unless explicitly instructed** - Preserve structure
- **Never reorder header fields** - Maintain canonical order
- **Never change header_fields.count unless actual list length changes** - Count must match reality
- **Always preserve whitespace, ASCII art, and formatting exactly** - No beautification
- **Always update only the PRD referenced in the current message** - Single target
- **Never update other PRDs unless explicitly instructed** - No scope creep

#### 13.3 The Official PRD-UPDATE Prompt

```
You are updating the PRD currently being discussed in the user's message.

Follow the Lupopedia truth stack in this exact order:

1. The current user message (highest truth)
2. Atoms (global constants)
3. prd_cluster files in the order listed in the header
4. memory_toon (long-term system memory)
5. transcript_jsonl (conversation history)

Rules:
- Atoms override PRDs.
- Earlier PRDs in the cluster override later ones.
- Never invent fields, rules, or doctrine.
- Never remove fields unless the user explicitly instructs it.
- Never reorder header fields.
- Never change header_fields.count unless the actual list length changes.
- Always preserve whitespace, ASCII art, and formatting exactly.
- Always update only the PRD referenced in the current message.
- Never update other PRDs unless explicitly instructed.

Output the full updated PRD file with only the required changes applied. At the very bottom, after the main content and before any footer, add a short revision note in the existing 'Revision note' table format. The note should be one line summarizing what was changed in this update. Do not add any other commentary, explanations, or analysis outside of that revision note.
```

#### 13.4 Why This Matters

PRDs drift. Documentation drifts. Agents hallucinate. Counts change. Fields get added and removed. But **atoms never drift**.

Atoms are:
- canonical
- immutable  
- machine-readable
- doctrine-encoded
- versioned
- validated
- truth-first

This is why the system survives even when "the kids can't count."

---

This document establishes the supreme constitutional wall that kills model priors and prevents hallucination. It must be read first and overrides all training data.

**STATUS:** SUPREME CONSTITUTIONAL - READ FIRST - OVERRIDES ALL

This PRD complies with Lupopedia Constitutional Root Rules and serves as the highest authority document.

---

## 14. USER ID SPACE FORBIDDEN PATTERNS

### 14.1 NEVER Assume the Auth User ID Partition

**FORBIDDEN:** Never infer, guess, or assume the auth_user_id space partition.

**WHY:** The Lupopedia user ID partition is a specific design decision. It is not
derivable from general database conventions. Actors that fill this gap by inference will
produce wrong partitions -- specifically: placing the system root at the wrong ID,
conflating the admin account with the system root, omitting the Crafty import range, or
mislabeling the red team user. This happened with Actor 116 on 2026-04-21 and is
documented in `docs/why/why_20260421_221540_79_A_85_A_user_id_space_undocumented.md`.

**REQUIRED:** Always read the canonical partition from PRD 79 s.13 or the atoms file.
Never hard-code integer boundaries; use the named atom constants.

**THE CANONICAL PARTITION (read-only reference -- PRD 79 s.13 is authoritative):**

```
0          = True system root (internal, no login, no password)
1 - 9999   = Crafty Syntax imported users from livehelp_users
             (MUST stay below 10000 - enforced in PHP during import)
10000      = Main Admin / Root Operator (human login)
10001      = Red Team / Adversarial testing user
10002+     = All new users created by IdGenerator (YYYYMMDDHHIISS + 4 digits)
```

**ATOM CONSTANTS (from memory/atoms/lupopedia_global_constants.atom.toon):**

```
USER_ID_SYSTEM_ROOT     = 0
USER_ID_CRAFTY_MAX      = 9999
USER_ID_MAIN_ADMIN      = 10000
USER_ID_RED_TEAM        = 10001
USER_ID_NEW_USER_MIN    = 10002
```

### 14.2 NEVER Place System-Reserved IDs in crafty_user_mapping

**FORBIDDEN:** Never INSERT auth_user_id values 0, 10000, or 10001 into
`lupo_crafty_user_mapping`.

**WHY:** These IDs are system-reserved install seeds. Crafty Syntax
`livehelp_users.user_id` values are always positive integers well below 10000.
Any value >= 10000 or equal to 0 appearing in a Crafty import is a data error.

**REQUIRED:** PHP MUST validate `user_id < USER_ID_MAIN_ADMIN` AND `user_id > 0`
before every INSERT into `lupo_crafty_user_mapping`. No DB constraint. PHP only.

---

## Revision note

| Date | Change |
|------|--------|
| 20260421120000 | Initial creation of supreme constitutional wall |
| 20260421124200 | Softened Truth Stack output rule to allow short revision notes |
| 20260421221540 | Added s.14 USER ID SPACE FORBIDDEN PATTERNS with canonical partition and atom constants. ASCII cleanup: removed shield emoji from title, replaced checkmark/cross emoji with [OK]/[NO], replaced Unicode arrows with ->. |
| 20260506115521 | Integrated Theory of Everything dream fragment into 4.1.7 doctrine: database vs file system separation, filename PK convention, complete folder structure, themes dual use, Four Faces + PILAU, and Recent Canonical Additions links. |
| 20260510091417 | Removed s.2 WHITESPACE AND ASCII ART FORBIDDEN PATTERNS; renumbered following numbered sections (old 3-9 to 2-8; READING ORDER is now s.9). |
| 20260510093310 | Removed s.10 ENTERTAINMENT LAYER ISOLATION (Captain's Log Rule); Three-Layer Security Audit is now s.10. |
| 20260510103209 | Pointer to unified PRD 00 merge file **`docs/prd/00_F-i_SUPREME_CONSTITUTIONAL_WALL_UNIFIED.md`**; authority clause defers implementation to **`00_C-i`**. |

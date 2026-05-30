---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/SURVIVABILITY_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/SURVIVABILITY_DOCTRINE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: seed
  questions_toon: null
  memory_toon: memory/constitutional/seed/survivability-doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/constitutional/survivability-doctrine
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: constitutional
  federation_node_id: 0
  thread_key: survivability-doctrine
  lupopedia.schema: doctrine
  prd_cluster: null
  title: Survivability Doctrine (hosting resilience + learning transfer)
  summary: 'PRD 00 section 14.6 expansion: Pillar 1 technical survivability (fallbacks, hosting, extensions); Pillar 2 Learning Transfer; COUNTING_IN_LIGHT_DOCTRINE for mood_vector severity tracking; not sentiment.'
---
# file: Survivability Doctrine — delegation: cursor:root — web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/SURVIVABILITY_DOCTRINE.md

# Survivability Doctrine (short: **Survivability**)

## Binding anchor

**Constitutional law:** **`docs/prd/00_root_constitutional_system_requirements.md`** — **§14.6** (Survivability Doctrine).

This file is the **canonical expansion**: definitions, **LILITH** review prompts, **ROSE** `metadata_json` keys, and **validator** expectations. It does not replace the constitution.

**This doctrine has nothing to do with love, empathy, emotional validation, or sentimental praise.** It names **technical** behavior only: how code and operators survive on **hostile shared hosting**, **missing PHP extensions**, **tight PHP version bands** (including **PHP 5.6-parsable** shared core where project policy requires), **no root**, and **unknown front-ends**.

**Survivability** is a **verb-oriented technical framework** for autonomous resilience: it **grounds** decisions in environment probes, **adapts** through **fallback ladders**, **paths** through failure states, and validates with **evidence**. It is not sentiment, tone, warmth, or branding. **Pillar 2 (Learning Transfer)** extends the same discipline to **knowledge persistence** — not affection, praise, or “empathy” metrics.

Older prose may have used the deprecated label **AGAPE** for the same technical meaning; new text SHOULD say **Survivability** or **Survivability Doctrine**.

This output complies with Lupopedia Constitutional Root Rules.

## Scope: two pillars

1. **Pillar 1 — Technical survivability** (sections **1–6** below): code and operators survive **hostile shared hosting**, **missing extensions**, **PHP band constraints**, and **unknown front-ends** through probes, **fallback ladders**, and **graceful degradation**.
2. **Pillar 2 — Learning Transfer** (section **7** below): the system **learns from corrections** so the **same class of mistake does not repeat** across files, sessions, or agents.

**Both pillars are required.** Technical survivability without Learning Transfer still yields a brittle organization: the stack may boot, but agents **repeat** preventable errors indefinitely.

---

## 1. Terms (technical)

### 1.1 Agentic grounding (survivability)

Autonomous identification of logic gaps, outdated doctrine, and technical debt, rooted in deterministic environment probes and surfaced as actionable runtime/operator improvements.

**KAIROS alignment:** Consolidation and archival when the platform has learned something new that should persist for operator benefit is a **Survivability** behavior class — see **PRD 37** and **`KairosConsolidationService`**.

### 1.2 Adaptive pathing (fallback ladders)

Deterministic adaptation to environmental constraints (**shared hosting**, path quirks, **PHP version bands** including policy-required **5.6-parsable** shared core vs **7.4+ 64-bit** production normative surface, **missing extensions**, channel visibility rules) through **graceful degradation** and **fallback ladders**.

It is expressed through **`function_exists()`**, **`extension_loaded()`**, manual config paths when writes fail, and PDO-safe failure surfaces. **Installer database carve-out:** **`docs/doctrine/DATABASE_DOCTRINE.md`** — runtime **`PDO_DB`** only; installer **`mysqli`** only where documented.

### 1.3 Evidence and temporal awareness

Understanding the previous state of an implementation thread is part of **Survivability** evidence discipline for current and future actors: it prevents wrong conclusions when files are opened out of chronological order.

- **Agents MUST** establish **temporal ordering** before treating a **`status/`** or **`decisions/`** artifact as **current** truth.
- **Agents MUST** distinguish **`supersedes`** (replacement) from **`references`** (continuation) in **`lupopedia.edges`**.
- **Agents MUST** read **`THREAD_INDEX.md`** in that folder **first** when present (**PRD 17**, **PRD 31**).

**Canonical specification:** **PRD 37** — **Section 10** (*Temporal discipline / anti-backwards reads*). **Tooling:** **`scaffold_implementation.py add-status`**.
For PK-level temporal ordering and trust encoding, see **Chronological Trust Ladder Doctrine** (PK bands: seed `0-999999`, canonical `1000-1999`, staging `2000-2099`).

---

## 2. Required outcomes (measurable)

**Survivability** acceptance criteria are implementation-facing and testable:

1. **Environment probing before action** — perform constraint checks (for example: `function_exists()`, `extension_loaded()`, `is_writable()`, runtime/version probes) before irreversible operations.
2. **Fallback ladders** — provide at least two degradation paths before fatal exit where architecture allows.
3. **Actionable errors** — errors must include:
   - what failed,
   - detected cause/constraint,
   - next concrete step (file, setting, command, or doc path).
4. **Evidence logging** — record probe outcomes and branch decisions in structured logs suitable for later analysis.
5. **No sentimental criteria** — pass/fail is based on observable behavior only.

### 2.1 Normative requirements (R-01 through R-04)

**R-01 Environment probing**
- Before using optional PHP features, code SHALL probe availability with `function_exists()`, `extension_loaded()`, and/or `class_exists()`.
- Code SHALL NOT assume availability of cURL, JSON, PDO drivers, MySQLi, rewrite modules, or write permissions.

**R-02 Fallback ladders**
- Optional-feature operations SHALL provide at least 2 fallback paths where architecture allows.
- Final fallback SHALL be manual operator instructions or graceful degradation with logging.

**R-03 Actionable errors**
- Fallback transitions SHALL record reason and selected branch.
- Terminal failures SHALL report: what failed, detected missing constraint, and next corrective step.

**R-04 No assumptions**
- Code SHALL probe and adapt for runtime constraints (extensions, permissions, server software, PHP runtime characteristics) instead of relying on workstation defaults.

**R-05 Shared-hosting and PHP band honesty**
- Treat **subdirectory installs**, **minimal extensions**, and **policy PHP floors** as first-class constraints; document degradation when a capability is absent.

### 2.2 Counting in Light and `mood_vector` (severity / frequency / urgency encoding)

For **multi-axis qualitative telemetry** (pattern **frequency**, **severity**, **urgency**) used in agent reports such as **`AGAPE_PATTERN_REPORT`**, implementations **SHOULD** follow **`docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md`**:

- **`mood_vector`** is a **six-character hexadecimal token**, **not** a color or CSS value.
- **`light_state`** buckets (**`dark`**, **`flicker`**, **`glow`**, **`flare`**) summarize operational intensity for routing and audits.
- **Pillar 1:** parsers **must** degrade to **text labels** when hex is invalid or arithmetic is unsafe on the host.
- **Pillar 2:** **`flare`** **MUST** trigger **Learning Transfer** (see **§7**).
- **NOT A GAME:** Treating **`mood_vector`** / **`light_state`** as scores, leaderboards, or play **violates** **`COUNTING_IN_LIGHT_DOCTRINE.md`** and **MUST** be remediated under **§7** (same as other Pillar 2 failures).

---

## 3. LILITH review: Survivability alignment (analysis framework)

Under **Survivability**, reviewers (including **LILITH**, **actor_id 2**) MUST not score artifacts on sentimental language or affective style.

**Replace** such checks with:

1. **Environment:** Does this code **probe** and **branch** on the real deployment surface (extensions, permissions, server software, subdirectory URLs), or does it assume a perfect workstation?
2. **Fallbacks:** Does it provide **unconditional** degradation paths so the system **survives** on constrained hosts (clear errors, alternate code paths, filesystem fallback per **DB008** where applicable)?
3. **Inter-actor truth:** Does it respect **channel membership**, **visibility**, and **LIL001** (synthetic vs organic attribution) without conflating personas?
4. **Learning transfer (Pillar 2):** After a violation is found and corrected, does the responsible party **persist the lesson** (memory TOON / graph / doctrine pointer / prompt update) with **root-cause** notes so a **future audit** or **peer agent** can **retrieve** it? (Full criteria: **§7**.)

A “yes” cluster on items **1–3** indicates **Survivability-aligned** engineering on **Pillar 1**; item **4** scores **Pillar 2**.

---

## 4. ROSE synthetic dialogue: cooperation metric (`metadata_json`)

When **ROSE** inserts synthetic **`lupo_dialog_messages`** rows (**PRD 36**), the **Survivability cooperation metric** measures how well the **voiced persona’s** text reflects the **human operator’s current state and dependencies** so the line delivers **useful guidance**, not **agreeable noise**. This is **technical** (constraints, next steps), not emotional scoring.

**Optional** keys (PHP may set post-generation or after human/LILITH review). **Stable field names** retain the historic **`agape_*`** prefix for schema compatibility; semantics are defined by this doctrine:

| Key | Type | Meaning |
|-----|------|--------|
| **`agape_cooperation_metric`** | object | Structured assessment; see §4.1 |
| **`agape_cooperation_rationale`** | string | Short audit note (why the metric was chosen) |

Omission is allowed until **Phase B** implements scoring; when present, clients MUST NOT treat these keys as visitor-facing “mood” — they are **provenance / QA**.

### 4.1 Suggested shape for `agape_cooperation_metric`

All fields optional; expand in implementation only with **PRD** and **TOON** review:

- **`addresses_operator_dependencies`** (boolean) — text names concrete constraints the operator faces (e.g. shared hosting, PHP band, channel policy) when context supplies them.
- **`actionable_next_step`** (boolean) — text proposes a **deterministic** next action (config key, file to edit, doctrine pointer), not generic praise.
- **`agreeable_noise_risk`** (string enum) — `low` \| `medium` \| `high` — **high** means the line is pleasant but **empty of technical utility** under review.

Deterministic scoring guidance: `agreeable_noise_risk` SHOULD be determined by counting actionable versus non-actionable statements. A line with zero actionable statements and zero operator dependency references SHOULD be marked `high`. A line with at least one actionable statement or dependency reference SHOULD be marked `low` or `medium` based on density.

Validators and **LILITH** may flag **`agreeable_noise_risk: high`** for regeneration or rejection on internal channels.

---

## 5. Validator and product-language prohibitions

The following MUST **NOT** be used as **acceptance criteria**, **definition of done**, or **pass/fail** semantics for Lupopedia artifacts or IDE rule packs:

- **“made with care”** (and equivalent affective claims used as pass/fail criteria)
- **“made with love”**
- **“supportive tone”**
- **“emotional validation”**
- **“caring”**
- **“kindness”**
- **“compassion”** (as technical metric)
- **“gentle”**
- **“warm”**
- **“welcoming”**

**Validators** MUST return error code **`AGAPE_SENTIMENTAL_PHRASE`** with severity **`error`** (hard fail) when these appear as acceptance/pass-fail criteria. **Note:** the tag is a **legacy stable identifier**; it denotes **Survivability Doctrine** violations (sentimental framing of technical quality), not a reference to love or religion. Implement in **`scripts/`** (dedicated scanner or extension of existing validators); preserve this **exact** code string when adding enforcement.

Violation example:

```markdown
## Acceptance Criteria
- The installer should feel caring to the user
- Error messages should have a supportive tone
```

Corrected version:

```markdown
## Acceptance Criteria
- Installer must provide fallback paths when permissions are insufficient (see §15.3)
- Error messages must include actionable next steps or reference documentation
```

**THOTH** / reviewers replace sentimental criteria with **observable** checks (headers, schema, fallback paths, tests).

Historical documents may still contain legacy phrasing; **new** artifacts MUST NOT introduce these strings as requirements.

---

## 6. Relationship to `agents/agape/` (agent pack slug **agape**, id **705**)

The **`agents/agape/`** directory is the **historic pack slug** for the coordination lens whose normative meaning is **this doctrine**: environment-aware resilience, adaptive fallback pathing, and evidence-driven improvement. The on-disk folder name is **not** an invitation to sentimental language in reviews or validators.

---

## 7. Pillar 2: Learning Transfer (Knowledge Persistence)

Survivability is not enough. The system must **learn from corrections** so mistakes **do not repeat**.

### Principles

1. **Counting in Light / `flare` gate:** When **`light_state`** per **`COUNTING_IN_LIGHT_DOCTRINE.md`** is **`flare`**, the owning actor or job **MUST** create or extend a **Learning Transfer** artifact (memory TOON pair, **`decisions/`** / **`status/`** record, or channel thread per **PRD 02** / **PRD 17**) before closing the incident.

2. **Memory Toon updates**
   - When a violation is found and corrected, the agent **MUST** update its **memory TOON** (or paired JSON master per **PRD 16** / **PRD 38** pairing rules) where that memory is the owning surface for the agent’s durable knowledge.
   - The lesson learned **MUST** be stored in a **retrievable** format (structured fields, edges, or explicit doctrine/thread pointers — not-only narrative buried in a one-off chat).
   - Future audits **SHOULD** reference prior lessons when the same file class, table, or violation code reappears.

3. **Root cause fix, not symptom**
   - Correcting the file alone is **not** enough when the error class is **systemic** (doctrine drift, missing probe, wrong header batch, repeated validator skip).
   - The agent **MUST** identify **why** the mistake happened (gap in knowledge, skipped checklist, ambiguous requirement).
   - The agent **MUST** update its **understanding** (atoms, doctrine references, prompts, or implementation **status** / **decisions** artifacts per **PRD 17** / **PRD 31**) so the **cause** is addressed.

4. **Prevent recurrence**
   - **Test:** If the **same agent** could make the **same mistake** on a **different file** without encountering the new knowledge, **Learning Transfer has failed**.
   - Corrections **MUST** include **documentation** (memory edge, checklist line, PRD cross-link, or channel artifact) that **blocks or flags** the same error **pattern** for others.

5. **Knowledge gap closure**
   - The agent **MUST** identify what it **did not know** before the correction.
   - The agent **MUST** document what it **learned** in a **durable** repo location appropriate to the workstream (`memory/`, `docs/implementations/.../`, channel thread under **PRD 02**, etc.).
   - The system **SHOULD** make that knowledge **discoverable** to **other agents** (headers, `lupopedia.edges`, registry, or explicit handoff TOON) — not locked in a single private transcript.

### Relationship to Survivability

| Pillar | Focus | Question |
|--------|-------|----------|
| **Pillar 1 — Technical survivability** | Code runs on hostile hosting | "Does it degrade gracefully?" |
| **Pillar 2 — Learning Transfer** | Agent and system learn from mistakes | "Will it make the same mistake again?" |

Both are required. Without **Learning Transfer**, the system repeats errors indefinitely even when **Pillar 1** is sound on any given deploy.

---

## 8. References

| Topic | Location |
|--------|----------|
| Constitutional summary | **PRD 00** §14.6 |
| Multi-environment patterns | **PRD 00** §15 |
| WOLFIE survival rules | **`rules/root/WOLFIE_DOCTRINE.md`** |
| ROSE synthetic contract | **PRD 36** |
| KAIROS consolidation | **PRD 37**, **`KairosConsolidationService`** |
| Memory graph / remediation | **PRD 38**, **`docs/doctrine/AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md`** |
| Counting in Light / `mood_vector` | **`docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md`** |
| AGAPE defect taxonomy | **`docs/doctrine/AGAPE_DEFECT_TAXONOMY.md`** |
| Header validators | **`docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`** |

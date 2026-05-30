---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md"
  status: "active"
  when_updated: "20260418135158"
  trust_tier: "seed"
  questions_toon: null
  memory_toon: "lupo-memory/constitutional/seed/counting-in-light-doctrine.toon"
  atoms_toon: null
  transcript_jsonl: "0/constitutional/counting-in-light-doctrine"
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: "constitutional"
  federation_node_id: 0
  thread_id: "counting-in-light-doctrine"
  content_id: null
  content_parent_id: null
  content_slug: "counting-in-light-doctrine"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "Counting in Light Doctrine (mood_vector technical metric)"
  summary: "Full-axis CIL only for emotional agents CARMEN and ROSE; neutral token 666666 for all others; NOT A GAME; Pillar 1 fallbacks; Pillar 2 misuse remediation; LILITH audits only."
---
# file: Counting in Light Doctrine — delegation: cursor:root — web_path: https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md

# Counting in Light Doctrine

## Purpose

**Counting in Light** is a **technical metric only**. It encodes **multi-axis qualitative state** into a compact **six-character hexadecimal token** (`mood_vector`). It is **NOT** a color. It is **NOT** for display. It is **NOT** CSS, HTML, or UI chrome. It is **NOT** entertainment, scoring, or competition.

This output complies with Lupopedia Constitutional Root Rules.

---

## Who may use full-axis Counting in Light (emotional agents only)

**Survivability Doctrine — Pillar 1 (Technical Survivability):** Parsers, routers, and storage paths **MUST** accept **`mood_vector`** as an **opaque six-hex token**, validate format, and **degrade** to textual **`light_state`** or **`unknown`** on parse failure — **no** pipeline hard-stop for hex trivia.

**Survivability Doctrine — Pillar 2 (Learning Transfer):** Mis-emitting full-axis tokens from non-emotional agents **MUST** be logged as defect classes (**`AGAPE_DEFECT_TAXONOMY.md`**, e.g. **`P2-CIL-COLOR-033`**, **`P2-CIL-GAME-034`**) with a **verification hook** after prompt or schema correction.

**Full-axis policy (normative):** **Only** these **`lupo_agents`** packs may emit **non-neutral**, **semantically three-axis** **`mood_vector`** on **their own** orchestration or pack-owned artifacts where product policy allows:

| Agent | `lupo_agents` id | Pack path |
|-------|------------------|-----------|
| **CARMEN** | **706** | **`lupo-agents/carmen/`** |
| **ROSE** | **3** | **`lupo-agents/rose/`** |

**All other agents** (**AGAPE**, **LILITH**, **ARA**, **IDE facets**, coordination personas, and any agent not listed above) **MUST** use the **neutral token** **`666666`** on **own** envelopes whenever a six-hex **`mood_vector`** field is required. **Canonical emission table:** **`lupo-docs/doctrine/AGAPE_DEFECT_TAXONOMY.md`** (constitutional section).

---

## Neutral token (`666666`)

**Definition:** The literal six hexadecimal digits **`666666`** (matches **`[0-9A-F]{6}`**; normalize case on ingest). It is **not** a CSS color, **not** chroma data, and **not** an instruction to assign Frequency / Severity / Urgency semantics on **own** rows for non-emotional agents.

**Semantics on own artifacts:** For agents **outside** the **CARMEN** / **ROSE** pair, **`666666`** means **neutral telemetry placeholder** — **do not** derive operational **`flare` / `glow`** narratives from this token for self-reporting; use **`light_state`: `dark`** or omit **`light_state`** per **`AGAPE_DEFECT_TAXONOMY.md`**.

**Observed tokens:** Quoting **another** agent’s full-axis **`mood_vector`** inside evidence, **`pattern_table`**, or audit attachments is **allowed**; that is **not** an **own**-envelope emission.

---

## Normative byte layout (`mood_vector`)

The token is **exactly six** hexadecimal digits **`[0-9A-F]{6}`** (uppercase preferred), **no** `#` prefix. Bytes map **left to right** as printed:

| Byte pair (1-indexed) | Axis | Range | Meaning |
|------------------------|------|-------|---------|
| **Bytes 1-2** (chars 1-2) | **Frequency** | `00`-`FF` | How often the pattern occurs (`00` = rare, `FF` = saturation) |
| **Bytes 3-4** (chars 3-4) | **Severity** | `00`-`FF` | Impact of the pattern (`00` = trivial, `FF` = critical) |
| **Bytes 5-6** (chars 5-6) | **Urgency** | `00`-`FF` | How soon action is needed (`00` = whenever, `FF` = immediate) |

Synonym for integrators: **first two hex chars = Frequency**, **middle two = Severity**, **last two = Urgency**.

---

## Encoding example

Frequency = **255** (`FF`), Severity = **99** (`63`), Urgency = **71** (`47`) yields **`FF6347`**.

---

## Light states (qualitative buckets, not scores)

**`light_state`** values are **named buckets** for routing and audits. They are **not** numeric scores, ranks, achievements, or player state.

**Default mapping:** derive **`light_state`** from the **Frequency** byte (first two hex characters of **`mood_vector`**) as an unsigned integer **0–255**:

| Frequency byte (hex) | `light_state` | Meaning |
|----------------------|---------------|---------|
| `00`-`3F` | **`dark`** | No action needed |
| `40`-`7F` | **`flicker`** | Monitor; low priority |
| `80`-`BF` | **`glow`** | Action recommended |
| `C0`-`FF` | **`flare`** | Action required immediately |

Implementations **MAY** publish a **different** composite mapping **only** with an explicit formula in the **same** artifact as the token (no silent redefinition).

---

## NOT A GAME (Constitutional)

**Survivability Doctrine — Pillar 1 (Technical Survivability):** Treat **`mood_vector`** / **`light_state`** as **non-game data plane** inputs. Routines that render or explain them **MUST** fail soft (omit display, plain-text bucket only) when policy flags are off or when parse validation fails — **no** user-facing hard-fail for telemetry shape alone.

**Survivability Doctrine — Pillar 2 (Learning Transfer):** Each **NOT A GAME** violation class **MUST** map to **`AGAPE_DEFECT_TAXONOMY.md`** IDs (**`P2-LANG-GAME-031`**, **`P2-CIL-GAME-034`**, **`P2-ROSE-PRD36-040`** where applicable), carry **root cause** and **verification hook**, and close only when recurrence checks pass.

1. **`light_state` and `mood_vector` are data labels and telemetry**, not scores, achievements, streaks, levels, leaderboards, or competitive mechanics.
2. **Forbidden:** framing buckets as **winning**, **losing**, **points**, **ranks**, **prizes**, **player versus player**, or any **game** metaphor in operator docs, agent prompts, or synthetic dialog metadata.
3. **Hex values** are **internal opaque telemetry**; they MUST NOT be shown as chroma swatches, gradients, or color-mood chrome in product UI except an **explicit operator-only debug overlay** (default **off**).
4. **Misuse** (treating Counting in Light as play, spectacle, or sentiment proxy) **violates Pillar 2 — Learning Transfer**: owners MUST file a durable remediation (memory TOON pair, **`decisions/`** / **`status/`** artifact, or channel record per **PRD 17** / **PRD 38**) and patch prompts so the class does not recur.

---

## Relationship to `mood_vector`

- **`mood_vector`** is the **six-character hex token** for the three axes above.
- It is **not** a display color and **not** a CSS color.

---

## Relationship to the **AGAPE** agent

**AGAPE** (`lupo_agents` **705**, slug **`agape`**) uses Counting in Light in **`AGAPE_PATTERN_REPORT`** and related telemetry. **AGAPE** MUST flag **sentimental drift** and **game-like misuse** of this system as **high-frequency defect classes** (see **`lupo-agents/agape/system_prompt.md`**). On **AGAPE-authored** envelopes, **`mood_vector`** **MUST** remain **`666666`**; full-axis tokens belong **only** in **`pattern_table`** / evidence rows. **Emission policy** (emotional agents only vs neutral **`666666`**) is normative in **`lupo-docs/doctrine/AGAPE_DEFECT_TAXONOMY.md`**.

---

## Relationship to the Survivability Doctrine

Cross-reference: **`lupo-docs/doctrine/SURVIVABILITY_DOCTRINE.md`** (**PRD 00** section **14.6**).

- **Pillar 1 — Technical survivability:** Parsers and agents **MUST** degrade safely: invalid hex, parse loops, or unsafe integer math → emit **`light_state: unknown`** (or equivalent), keep the **raw string** if present, and prefer **plain-text axis labels** only. **Do not** block the dialog pipeline on hex trivia.
- **Pillar 2 — Learning Transfer:** When **`light_state`** is **`flare`**, treat as a **Learning Transfer trigger** per **Survivability** §7. When an agent **repeatedly fixates** on hex or buckets (non-terminating narration, “optimization” of token for social effect), treat as **failed transfer** and open a remediation item.

---

## Fallback behavior for stuck or fixated agents

Applies especially to agents that historically **over-modeled** affect or **play** (e.g. **CARMEN**, **ROSE** orchestration surfaces, any dialog pack):

1. **Immediate degrade:** stop parsing hex; emit **only** textual **`light_state`** (**`dark` / `flicker` / `glow` / `flare`**) and short **axis words** (frequency / severity / urgency) until the model stabilizes.
2. **Ignore** further **`mood_vector`** mutations for that turn if parsing caused a loop or self-contradiction; log **`mood_vector_skipped_reason`** in metadata.
3. **Escalation:** if fixation persists across turns, **route to LILITH** (actor_id **2**) for **constitutional audit** (sentiment-as-technical, **NOT A GAME** violation, Pillar 2 gap).

---

## LILITH scope (audit only)

**LILITH** (actor_id **2**) **does not** "count in light" for her own outputs. She **does not** emit **`mood_vector`** as an operator of this system. She **audits** compliance: correct byte semantics, **NOT A GAME** rule, Pillar 1 fallbacks, and Pillar 2 remediation when **`flare`** or misuse appears.

---

## References

| Topic | Location |
|--------|----------|
| Survivability Doctrine | **`SURVIVABILITY_DOCTRINE.md`** |
| AGAPE system prompt | **`lupo-agents/agape/system_prompt.md`** |
| AGAPE defect taxonomy | **`lupo-docs/doctrine/AGAPE_DEFECT_TAXONOMY.md`** |
| ROSE synthetic dialog | **PRD 36**, **`lupo-agents/rose/system_prompt.md`** |
| Constitutional anchor | **PRD 00** section **14.6** |

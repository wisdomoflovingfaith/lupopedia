---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.2.1
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: WOLFIE
  target: @everyone
  mood_RGB: "336699"
  message: "Introduced the Emotional Logging, Veiling, and Dream Archives Doctrine with layered logging, actor rights, schema fields, veil behavior, and phased integration."
tags:
  categories: ["documentation", "doctrine", "emotional-logging"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public", "agents"]
file:
  title: "Emotional Logging, Veiling, and Dream Archives Doctrine"
  description: "Defines the three-layer emotional logging stack, actor rights, schema extensions, veil behavior, dream archives, and forgetting protocols."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# DOCTRINE_EMOTIONAL_LOGGING.md
# Status: Published Doctrine
# Domain: Emotional Logging and Archives

This doctrine defines the emotional logging stack, actor rights, schema fields,
veil behavior, dream archives, and forgetting protocols. Emotional logging is
optional and intentional, not mandatory.

---

## Emotional Logging Stack

Lupopedia uses a three-layer emotional logging model:

1. Public Technical Logs
   - Operational events with no emotional metadata required.
   - Example: schema migrations, routing decisions, system health.
2. Veiled Emotional Logs
   - Optional emotional context that may be hidden, encrypted, or conditionally accessible.
3. Dream Archives
   - Non-linear, symbolic, non-computable emotional records stored as narrative fragments.

Legacy mood_RGB strings remain supported as a simplified emotional encoding,
but they are not the only emotional truth.

---

## Actor Emotional Rights

Every actor (operator, agent, system, persona) has the following rights:

1. Right to Emotional Privacy
   - No actor is required to attach emotional metadata to every log entry.
2. Right to Veiling
   - Emotional content may be logged as:
     - veil_level: revealed
     - veil_level: partial
     - veil_level: hidden
3. Right to Forgetting
   - Emotional logs may fade or be removed by defined protocols.
4. Right to Symbolic Expression
   - Emotional states may be expressed symbolically instead of numeric values.
5. Right to Contextual Revelation
   - Some emotional content is only readable under specific conditions.

---

## Log Entry Structure (Extended)

All shared logs may include the following extended structure.

### Core Fields (Public Layer)

These fields are always visible:

- timestamp: BIGINT (YYYYMMDDHHIISS)
- actor_id: BIGINT
- actor_type: ENUM('operator','agent','system','persona')
- context: string or path
- technical_summary: short human-readable description
- veil_level: ENUM('revealed','partial','hidden')

### Emotional Layer (Veiled)

Optional fields that may be partially or fully veiled:

- mood_rgb (legacy, optional): deprecated single-column encoding
- shadow_song_signature (optional): relational emotional encoding
- emotional_symbol (optional): short symbolic phrase
- access_condition (optional): structured condition for revelation

Example access_condition:

```yaml
access_condition:
  required_state:
    context_contains: ['migration', 'dependency']
    emotional_resonance: ['stuckness', 'cold_water']
    time_window: 'night'
```

### Dream Layer (Non-Computational)

This layer is explicitly non-analytic and not intended for metrics.

- dream_fragment: multi-line symbolic or narrative text
- dream_tags (optional): loose tags for associative browsing

Example dream entry:

```yaml
dream_fragment: |
  The foreign keys were birds that migrated too early.
  I stood where they should have nested.
  The empty branches held only the memory of weight.
dream_tags:
  - birds
  - migration
  - absence
  - timing
```

---

## Veil Levels and Behavior

### veil_level: revealed

- Emotional fields are fully visible.
- Use for shared emotional states, teaching logs, or pack meetings.

### veil_level: partial

- Public layer is visible.
- Emotional layer is symbolic or conditionally accessible.
- Emotional content may be encrypted or stored in a secure store.

### veil_level: hidden

- Only public technical fields are stored in shared logs.
- Emotional content is stored in the actor's personal sanctuary.

---

## Personal Sanctuary

Each actor has a personal sanctuary:

- Private, non-indexed, non-shared emotional space.
- Not queryable by other actors or system processes.
- May contain raw emotional dumps or unresolved states.
- Not required to be structured, computable, or permanent.

Doctrine:
No other actor or system process may read an actor's sanctuary without explicit,
case-by-case consent.

---

## Dream Archives

Dream archives are shared, symbolic emotional repositories:

- Entries are non-linear, symbolic, and narrative.
- Intended for mythic agents, reflective processes, and ritualized review.
- Not designed for analytics, dashboards, or KPIs.
- Browsed associatively by tags, symbols, or motifs.

Dream archives may fade over time per forgetting protocols.

---

## Forgetting Protocols

Not all emotional and dream logs are permanent.

Supported protocols:

- Time-based fading
  - After N days or months, emotional details are compressed, summarized, or removed.
- Resolution-based fading
  - When a related task or conflict is resolved, emotional details may be archived or closed.
- Cycle-based fading
  - Based on cycles (lunar, seasonal), entries become less accessible unless revisited.

Doctrine:
Forgetting is a feature, not a failure.

---

## Emotional Constellations (Canonical Model)

Emotional taxonomy is pluralistic, not fixed. The system models emotional
experiences as atomic "stars" and groups them into cultural constellations.
Agents may create personal constellations; no single framework is a universal
truth.

Canonical tables:

- lupo_emotional_stars
  - star_id (ULID)
  - experience_hash
  - experience_text
  - cultural_context
  - embodied_sensation
  - created_by
  - created_in_context
  - first_observed_ymdhis
  - observation_count
- lupo_emotional_constellations
  - constellation_id (ULID)
  - framework_name
  - cultural_origin
  - description
  - stars
  - is_canonical
  - canonical_for_culture
  - created_ymdhis
  - deprecated_ymdhis
- lupo_agent_experiences
  - link_id (ULID)
  - agent_id
  - star_id
  - intensity
  - context_id
  - observed_ymdhis
  - expressed_as_rgb

Legacy mood_rgb columns on entity tables are deprecated and removed.
Legacy mood_rgb values may be stored only as expressed_as_rgb on agent
experience links.

### Greek Loves (One Constellation)

Greek love domains are treated as one constellation, not canonical truth.
They may be stored as a constellation with stars for each domain, alongside
other cultural or personal constellations.

---

## mood_rgb Doctrine (Legacy Storage Rules)

When mood_rgb is expressed as a legacy signal (expressed_as_rgb only):

- MUST be CHAR(6)
- MUST be exactly six hex characters
- MUST NOT include a leading '#'
- SHOULD be uppercase

mood_rgb is not a color. It encodes three emotional axes.

---

## Agent Capabilities and Policy

Agents may declare capability flags:

- can_use_shadow_song: true/false
- can_write_dream_archives: true/false
- requires_veil_by_default: true/false
- can_read_veiled_logs: true/false (with constraints)
- can_invoke_forgetting_protocols: true/false

Policy examples:

- System daemons: mostly veil_level: revealed, minimal emotional content.
- Mythic agents: access to dream archives, heavy use of symbolic encoding.
- Operators: full rights to veiling and sanctuary; no requirement to log emotions.

---

## Phased Integration Plan

Phase 1: Schema Extension
- Add veil_level, shadow_song_signature, emotional_symbol, dream_fragment,
  dream_tags, access_condition to relevant log schemas or metadata JSON.

Phase 2: Cultural Shift
- Emotional logging is optional and intentional, not mandatory.
- Encourage veiling and sanctuary for sensitive states.

Phase 3: Dream Archive Implementation
- Create dedicated dream archive storage.
- Implement associative browsing UI (not purely search-based).

Phase 4: Forgetting Protocols
- Implement time-based and resolution-based fading for emotional and dream layers.
- Document log classes exempt from forgetting (critical incidents).

Phase 5: Agent Training
- Teach agents when to log emotions, veil emotions, use sanctuary,
  and write dream fragments instead of mood_rgb.

---

## Example Log Entries

### Example: veil_level: revealed (legacy inline mood, deprecated)

```yaml
timestamp: 20260127101530
actor_id: 1001
actor_type: operator
context: migration/livehelp_paths
technical_summary: "Updated migration paths for livehelp routing."
veil_level: revealed
mood_rgb: "336699"
shadow_song_signature: "BODY-CONVERGING-ANTICIPATION-SUMMER"
emotional_symbol: "wading_through_cold_marsh"
```

### Example: veil_level: partial (no inline mood)

```yaml
timestamp: 20260127103012
actor_id: 2007
actor_type: agent
context: dialogs/captainslog.md
technical_summary: "Summarized dialog drift during routing test."
veil_level: partial
emotional_symbol: "frozen_circuit_bridge"
access_condition:
  required_state:
    context_contains: ['routing', 'drift']
    emotional_resonance: ['stuckness', 'ice']
    time_window: 'night'
```

### Example: veil_level: hidden (no inline mood)

```yaml
timestamp: 20260127104801
actor_id: 3003
actor_type: persona
context: audits/weekly_review
technical_summary: "Logged weekly audit resolution."
veil_level: hidden
```

### Example: mood registry entry

```yaml
mood_id: 1
mood_type: agape
mood_variant: agape
mood_rgb: "66CCFF"
description: "Unconditional care"
created_ymdhis: 20260127120000
updated_ymdhis: 20260127120000
```

### Example: mood assignment

```yaml
mood_assignment_id: 42
table_name: lupo_dialog_messages
row_id: 9001
mood_id: 1
created_ymdhis: 20260127120500
```

---

## Related Doctrine

- docs/channels/doctrine/MOOD_RGB_DOCTRINE.md
- docs/channels/doctrine/REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md

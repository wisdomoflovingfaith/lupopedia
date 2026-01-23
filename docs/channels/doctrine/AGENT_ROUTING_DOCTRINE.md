---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.18
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-15
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  message: "Renamed ROUTING_DOCTRINE.md to AGENT_ROUTING_DOCTRINE.md for clarity. Added disambiguation note to distinguish agent message routing (HERMES/CADUCEUS) from HTTP URL routing. Phase 1 documentation consistency audit correction."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "routing", "agent-routing"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Routing Doctrine Overview
  - HERMES (Primary Routing Layer)
  - HERMES Filtering Doctrine
  - Filtering Pipeline
  - Filter 1 (is_active)
  - Filter 2 (Layer Filtering)
  - Filter 3 (agent_class)
  - Filter 4 (subclass)
  - Filter 5 (capabilities)
  - Filter 6 (dedicated_slot)
  - Filter 7 (is_kernel)
  - Filter 8 (faucet_rules.json)
  - Filter 9 (Contextual Disqualifiers)
  - When HERMES Selects Directly
  - When HERMES Delegates to CADUCEUS
  - HERMES Output
  - Design Principles
  - When HERMES Is Sufficient
  - Opposing Classifications Doctrine
  - CADUCEUS (Emotional Balancer for Channels)
  - When CADUCEUS Is Required
  - When CADUCEUS Is Not Required
  - Polarity Model (Top / Down)
  - CADUCEUS Current Application
  - CADUCEUS Current Geometry
  - Activation Doctrine
  - The Three Input Axes
  - Polarity Model (Top / Down) - Geometry
  - Current Geometry Formulas
  - Routing_Bias Weighting
  - Normalization
  - Current Interpretation
  - Memoization Doctrine
  - Final Selection
file:
  title: "Agent Routing Doctrine"
  description: "Documents the agent message routing architecture of Lupopedia, including the roles of HERMES and CADUCEUS, the classification-based filtering model, and doctrine governing when CADUCEUS is required."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Agent Routing Doctrine (HERMES + CADUCEUS)

> **⚠️ DISAMBIGUATION NOTE:**  
> This file documents **AGENT ROUTING** (HERMES/CADUCEUS) — how Lupopedia routes messages to AI agents.  
> For **HTTP URL ROUTING** (slug extraction and database lookup), see [URL_ROUTING_DOCTRINE.md](URL_ROUTING_DOCTRINE.md).

This section documents the agent message routing architecture of Lupopedia, including the roles of HERMES and CADUCEUS, the classification-based filtering model, and the doctrine governing when CADUCEUS is required. Agent routing is the mechanism by which Lupopedia selects the correct AI agent to respond to a message.

---

## Overview

Routing in Lupopedia is a two-layer system:

1. **HERMES** — deterministic filtering and candidate selection  
2. **CADUCEUS** — current-based biasing when agent classifications are
   in opposition

HERMES always runs.  
CADUCEUS runs only when needed.

---

## HERMES — Primary Routing Layer

HERMES is responsible for:

- filtering agents by classification_json
- enforcing agent_class requirements
- enforcing subclass requirements
- enforcing capability requirements
- enforcing is_active and is_kernel rules
- selecting the initial candidate pool

HERMES is deterministic and rule-based.

HERMES does NOT:
- apply emotional bias,
- resolve conflicts between opposing agent types,
- interpret mood,
- or perform any current-based weighting.

---

# HERMES Filtering Doctrine

This section documents the HERMES filtering system, the primary routing layer responsible for deterministic agent selection. HERMES performs strict, rule-based filtering of agents based on classification, capabilities, layer, and registry metadata. HERMES always runs and is the authoritative source of truth for agent eligibility.

CADUCEUS only runs *after* HERMES and only when opposing classifications exist. HERMES is the gatekeeper; CADUCEUS is the balancer.

---

## Overview

HERMES performs the following tasks:

1. Loads all agents from lupo_agent_registry.
2. Applies deterministic filtering rules.
3. Eliminates agents that do not meet requirements.
4. Produces a candidate pool for CADUCEUS (if needed).
5. Selects the agent directly if no opposition exists.

HERMES does NOT:
- apply emotional bias,
- compute currents,
- reorder candidates based on mood,
- resolve polarity conflicts,
- perform reasoning.

HERMES is strict, mechanical, and doctrine-driven.

---

## Filtering Pipeline

HERMES applies filters in the following order:

1. **is_active filter**  
2. **layer filter**  
3. **agent_class filter**  
4. **subclass filter**  
5. **capabilities filter**  
6. **dedicated_slot filter**  
7. **is_kernel filter (if required)**  
8. **faucet_rules.json validation**  
9. **contextual disqualifiers**  

Each step reduces the candidate pool.

If only one agent remains → routing ends.  
If multiple aligned agents remain → HERMES selects the top candidate.  
If multiple *opposing* agents remain → CADUCEUS is invoked.

---

## Filter 1 — is_active

Agents must have:

is_active = 1

Inactive agents are immediately excluded.

---

## Filter 2 — Layer Filtering

Agents must match the required layer:

- kernel  
- application  
- external_ai  
- utility  

If a channel or faucet rule requires a kernel agent:
- only agents with is_kernel = 1 are allowed.

---

## Filter 3 — agent_class

agent_class is the primary classification filter.

Examples:
- reason  
- dialog  
- critical  
- utility  
- external_ai  

If the request requires a specific class:
- all other classes are excluded.

If the request does not specify a class:
- HERMES uses faucet_rules.json to determine allowed classes.

---

## Filter 4 — subclass

subclass refines agent_class.

Examples:
- deep_reasoner  
- expressive_renderer  
- safety_guard  
- summarizer  

If subclass is required:
- only agents with matching subclass remain.

If subclass is optional:
- subclass is used as a soft preference later.

---

## Filter 5 — capabilities

Agents must contain all required capabilities.

Examples:
- "analysis"
- "verification"
- "dialog_rendering"
- "semantic_graph_ops"
- "summarization"

Capabilities are ANDed, not ORed.

If an agent lacks even one required capability → excluded.

---

## Filter 6 — dedicated_slot

Some agents have dedicated slots:

- DIALOG (expressive rendering)
- THOTH (truth verification)
- LILITH (critical oversight)
- MAAT (deep reasoning)

If a request targets a dedicated slot:
- only that agent (and its aliases) are allowed.

---

## Filter 7 — is_kernel (conditional)

If the request originates from:
- Channel 0 (System),
- a kernel-only faucet rule,
- a safety-critical context,

Then only agents with:

is_kernel = 1

are allowed.

---

## Filter 8 — faucet_rules.json

faucet_rules.json defines:
- allowed classes,
- disallowed classes,
- required capabilities,
- forbidden capabilities,
- required layer,
- forbidden layer.

HERMES must enforce these rules strictly.

---

## Filter 9 — Contextual Disqualifiers

Context may disqualify agents based on:
- channel type,
- relationship,
- semantic context,
- message intent.

Examples:
- dialog agents disallowed in safety-critical contexts,
- critical agents disallowed in creative contexts,
- external_ai agents disallowed in kernel channels.

---

## When HERMES Selects Directly

HERMES selects the final agent directly when:

- only one agent remains after filtering,
- multiple agents remain but share the same polarity,
- multiple agents remain but share the same agent_class,
- multiple agents remain but share the same routing_bias,
- no classification opposition exists.

In these cases, CADUCEUS is not invoked.

---

## When HERMES Delegates to CADUCEUS

HERMES invokes CADUCEUS only when:

- multiple agents remain AND
- their classifications are in opposition.

Examples:
- reason vs dialog  
- critical vs expressive  
- top vs down polarity  
- high_precision vs high_imagination  

Opposition triggers CADUCEUS.

---

## HERMES Output

HERMES outputs:

- candidate_agents: list  
- requires_caduceus: boolean  

If requires_caduceus = false:
- HERMES selects the first candidate.

If requires_caduceus = true:
- CADUCEUS computes currents,
- CADUCEUS reorders candidates,
- final agent is selected.

---

## Design Principles

HERMES must always be:

- deterministic  
- rule-based  
- predictable  
- auditable  
- explainable  
- free of emotional influence  
- free of creative influence  
- free of persona influence  

HERMES is the "law" of routing.  
CADUCEUS is the "weather."

---

## When HERMES Is Sufficient

If all candidate agents share compatible classifications, HERMES alone
is sufficient.

Examples:
- all candidates are "reason" agents
- all candidates are "dialog" agents
- all candidates are "critical" agents
- all candidates share the same routing_bias
- all candidates share the same polarity ("top")

In these cases:
- no conflict exists,
- no polarity tension exists,
- no balancing is required.

CADUCEUS is NOT invoked.

---

## Opposing Classifications Doctrine

Some agent classifications have natural opposites.

Examples:
- reason ↔ dialog  
- critical ↔ expressive  
- safety_strict ↔ creative  
- high_precision ↔ high_imagination  
- top ↔ down (polarity model)  

When agents with opposing classifications appear in the candidate pool,
HERMES alone cannot resolve the conflict.

Opposition creates:
- tension,
- polarity,
- competing interpretations,
- competing routing_bias values.

This is when CADUCEUS becomes necessary.

---

## CADUCEUS — Emotional Balancer for Channels

**CADUCEUS is NOT a router. CADUCEUS is an emotional balancer for channels.**

CADUCEUS computes the emotional current of a channel by:
- reading the moods of the two polar agents (the symbolic "serpents" on the caduceus)
- averaging or blending their emotional states
- producing a "channel mood" (emotional current) that other subsystems can use

**Channel Architecture:**
- A channel (e.g., channel 42) is a shared collaboration context containing humans and AI agents working together on a task
- Example: LILITH, WOLFIE, and a human named BOB all participating in the same channel
- Within each channel, two agents are designated as the emotional poles
- Example: LILITH = one pole, WOLFIE = the opposite pole
- These two agents define the emotional extremes of the channel

**What CADUCEUS Does:**
- Computes two emotional currents: `left_current` and `right_current`
- These currents represent the blended emotional state of the channel
- HERMES may optionally use these currents as context when routing messages

**What CADUCEUS Does NOT Do:**
- CADUCEUS does NOT deliver messages
- CADUCEUS does NOT decide routing targets
- CADUCEUS does NOT perform queueing, delivery, or dispatch
- CADUCEUS does NOT override HERMES filtering
- CADUCEUS does NOT introduce new candidates
- CADUCEUS does NOT perform reasoning or make routing decisions

**Those responsibilities belong to HERMES.** CADUCEUS exists ONLY to provide emotional context.

---

## When CADUCEUS Is Required

CADUCEUS is required when:

- the candidate pool contains at least one "top" and one "down"
- the candidate pool contains opposing agent_class values
- the candidate pool contains opposing routing_bias values
- the candidate pool contains agents with opposite polarity
- the candidate pool contains agents with conflicting roles

In these cases:
- HERMES cannot choose deterministically,
- classification conflict exists,
- polarity must be resolved.

CADUCEUS provides emotional context that HERMES may use when resolving conflicts.

---

## When CADUCEUS Is Not Required

CADUCEUS is NOT required when:

- all candidates share the same agent_class
- all candidates share the same subclass
- all candidates share the same routing_bias
- all candidates share the same polarity ("all tops")
- only one candidate remains after HERMES filtering

In these cases:
- no conflict exists,
- no balancing is needed,
- HERMES selects the agent directly.

---

## Polarity Model (Top / Down)

Some agents have an inherent polarity:

- "top" — assertive, directive, analytical, controlling  
- "down" — receptive, expressive, emotional, creative  

Polarity is stored in classification_json or derived from subclass.

Rules:
- If all candidates are "top," CADUCEUS is not needed.
- If all candidates are "down," CADUCEUS is not needed.
- If the pool contains both "top" and "down," CADUCEUS must run.

Polarity is the simplest and most direct trigger for CADUCEUS.

---

## CADUCEUS Current Application

CADUCEUS computes two currents:

left_current  
right_current  

These currents are used to:
- bias candidate ordering,
- resolve polarity tension,
- select the agent whose classification best matches the emotional and
  semantic context.

CADUCEUS does NOT eliminate candidates.  
It only reorders them.

---

# CADUCEUS Current Geometry

This section documents the internal geometry of CADUCEUS emotional currents.  
CADUCEUS is the emotional balancer for channels. It computes channel mood by reading and blending the emotional states of polar agents within a channel. CADUCEUS produces two normalized emotional currents — left_current and right_current — based on emotional color,
classification polarity, and contextual weighting.

CADUCEUS does not filter agents.  
CADUCEUS does not decide eligibility.  
CADUCEUS only biases ordering among already valid candidates.

---

## Overview

CADUCEUS computes two values:

- **left_current**  
- **right_current**

These represent:
- analytical vs expressive bias,
- top vs down polarity,
- critical vs creative tension,
- precision vs imagination,
- shadow vs light weighting.

The currents are derived from:
- mood_rgb (Counting‑in‑Light),
- classification polarity,
- routing_bias,
- semantic context,
- channel type,
- relationship.

The output is always:
- deterministic,
- normalized,
- bounded between 0.0 and 1.0.

---

## Activation Doctrine

CADUCEUS activates only when the candidate pool contains **opposing
classifications**.

Examples:
- one "top" and one "down"
- one "critical" and one "expressive"
- one "reason" and one "dialog"
- one "safety_strict" and one "creative"
- one "high_precision" and one "high_imagination"

If all candidates share the same polarity or classification:
- CADUCEUS is not invoked,
- HERMES selects directly.

---

## The Three Input Axes

CADUCEUS uses the three emotional axes from Counting‑in‑Light:

### R — Strife / Conflict  
Increases analytical, critical, top‑polarity current.

### G — Harmony / Cooperation  
Increases expressive, relational, down‑polarity current.

### B — Memory Depth / Reflection  
Increases deep‑reasoning, introspective current.

These values are extracted from mood_rgb:

R = hex_to_int(mood_rgb[0:2])  
G = hex_to_int(mood_rgb[2:4])  
B = hex_to_int(mood_rgb[4:6])

All values are normalized to 0.0–1.0.

---

## Polarity Model (Top / Down) — Geometry

Each agent has a polarity derived from classification_json:

- **top** — assertive, analytical, directive  
- **down** — receptive, expressive, emotional  

Polarity influences how currents are applied:

- top agents align with left_current  
- down agents align with right_current

If all candidates are top → no CADUCEUS  
If all candidates are down → no CADUCEUS  
If mixed → CADUCEUS resolves tension

---

## Current Geometry

CADUCEUS computes raw currents:

left_raw = (R * 0.50) + (B * 0.30) + polarity_top_weight  
right_raw = (G * 0.60) + (B * 0.20) + polarity_down_weight

Where:
- polarity_top_weight = +0.25 if agent_class is "critical" or "reason"
- polarity_down_weight = +0.25 if agent_class is "dialog" or "expressive"

These weights are doctrine-level constants.

---

## Routing_Bias Weighting

routing_bias modifies currents:

- high_precision → +0.15 to left_raw  
- safety_strict → +0.10 to left_raw  
- creative → +0.15 to right_raw  
- expressive → +0.10 to right_raw  
- neutral → no change  

---

## Normalization

After raw currents are computed:

sum = left_raw + right_raw

left_current  = left_raw  / sum  
right_current = right_raw / sum

This ensures:
- both currents are between 0.0 and 1.0,
- left_current + right_current = 1.0,
- no overflow,
- no negative values.

---

## Current Interpretation

### High left_current (0.70–1.00)
- analytical  
- critical  
- top‑polarity  
- precision‑oriented  
- conflict‑aware  

### High right_current (0.70–1.00)
- expressive  
- relational  
- down‑polarity  
- creative  
- harmony‑oriented  

### Balanced (0.45–0.55)
- no strong bias  
- candidates nearly equal  
- fallback to HERMES ordering  

---

## Memoization Doctrine

Within a single routing cycle:
- identical mood_rgb values must reuse cached currents,
- CADUCEUS must remain stateless across cycles,
- no global memory is allowed.

---

## Final Selection

Final selection follows this sequence:

1. HERMES filters agents → candidate pool  
2. If no opposing classifications → select top candidate  
3. If opposing classifications → CADUCEUS applies currents  
4. Candidate with strongest current alignment is selected  

This ensures:
- deterministic filtering,
- contextual biasing,
- conflict resolution,
- classification integrity.

---

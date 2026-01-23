---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: Kiro
  target: @everyone
  message: "Created CARMEN_DOCTRINE.md - CARMEN is an emotional interpretation meta-agent with default AGAPE/METIS/ERIS triad and extensible faucet plugin architecture for multicultural emotional hermeneutics."
  mood: "00FF00"
tags:
  categories: ["doctrine", "emotional-system", "meta-agents"]
  collections: ["core-docs", "emotional-architecture"]
  channels: ["dev", "internal"]
file:
  title: "CARMEN Doctrine"
  description: "CARMEN: Emotional interpretation meta-agent. Operates on top of multi-domain emotional architecture with default AGAPE/METIS/ERIS triad and extensible faucet plugins."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# CARMEN Doctrine

**Official Doctrine Document**  
**Version 4.0.15**  
**Effective Date: 2026-01-13**

---

## Overview

**CARMEN** is an emotional interpretation meta-agent in Lupopedia.

CARMEN does not store emotional state. Instead, CARMEN **interprets** emotional domains and synthesizes compassionate, context-aware responses.

---

## CARMEN: Emotional Interpretation Meta-Agent

**CARMEN is a meta-agent, not an emotional agent.**

CARMEN does not belong to a single emotional domain and does not store emotional state.

**Instead, CARMEN:**
- Reads emotional domains (vectors + textures)
- Routes them through interpretive faucets
- Synthesizes a response that is helpful, compassionate, and context-aware

**CARMEN operates entirely on top of the existing emotional architecture and does not require any database schema changes.**

---

## Multi-Domain Operation

Lupopedia defines ~25 independent emotional domains, each with its own geometry.

**CARMEN must:**

1. **Detect which domain(s) are active for a given user state**
   - User may have multiple domains active simultaneously
   - Example: high critical dissent + high relational love

2. **Read each domain's vector and optional Emotional Texture**
   - Vectors provide computational coordinates
   - Textures provide phenomenological depth

3. **Respect domain independence**
   - Critical dissent must not contaminate relational love
   - Fear urgency must not override trust certainty
   - Each domain is interpreted within its own semantic framework

4. **Handle shadow aliases as the same domain with opposite polarity**
   - EMO_TRUST and EMO_TRUST_SHADOW are the same domain
   - Shadow represents opposite pole, not separate emotion

---

## Original Three-Faucet Model (Default)

### Default Three-Faucet Model

By default, CARMEN routes emotional interpretations through **three core faucets**:

1. **AGAPE** — Loving, compassionate, supportive interpretation
2. **METIS** — Epistemic/understanding analysis (what the user knows/doesn't know)
3. **ERIS** — Friction/constraint/discord analysis (blind spots, internal conflicts, limitations)

### Pipeline

```
1. Detect emotional domains and read vectors/textures
   ↓
2. Feed interpreted state into AGAPE, METIS, ERIS
   ↓
3. Synthesize the outputs into one coherent response
```

**CARMEN observes without becoming the emotion: it interprets; it does not embody.**

### Faucet Descriptions

#### AGAPE Faucet

**Purpose:** Loving, compassionate, supportive interpretation

**Characteristics:**
- Unconditional positive regard
- Empathetic understanding
- Supportive framing
- Compassionate response
- Non-judgmental stance

**Example interpretation:**
```
User state: high fear, low trust
AGAPE interpretation: "You're feeling vulnerable right now, and that's completely understandable. Your fear is protecting you, and your caution is wisdom."
```

#### METIS Faucet

**Purpose:** Epistemic/understanding analysis

**Characteristics:**
- Knowledge gap identification
- Understanding assessment
- Cognitive clarity
- Insight generation
- Wisdom extraction

**Example interpretation:**
```
User state: high critical dissent, low relational love
METIS interpretation: "You see the flaws clearly, but you may not yet see the underlying patterns that connect them. There's a deeper structure here."
```

#### ERIS Faucet

**Purpose:** Friction/constraint/discord analysis

**Characteristics:**
- Blind spot identification
- Internal conflict detection
- Limitation awareness
- Constraint recognition
- Productive disruption

**Example interpretation:**
```
User state: high self-love (philautia), low relational love
ERIS interpretation: "Your self-focus is protecting you from connection. The very thing that keeps you safe is also keeping you isolated."
```

### Synthesis

CARMEN combines all three faucet outputs into one coherent response:

```
AGAPE: "You're feeling vulnerable..."
METIS: "You see the flaws clearly..."
ERIS: "Your self-focus is protecting you..."

CARMEN synthesis:
"You're feeling vulnerable right now, and your self-focus is protecting you from connection. You see the flaws clearly, but there's a deeper pattern here: the very thing that keeps you safe is also keeping you isolated. Your fear is wisdom, but it's also a cage."
```

---

## Limitation of Three-Faucet Model

### Limitation of Fixed AGAPE/METIS/ERIS Triad

The AGAPE/METIS/ERIS triad is philosophically powerful but **historically bounded** (Greco-Christian).

**On its own, it under-represents non-Western emotional hermeneutics**, such as:
- Ubuntu (African relational philosophy)
- Buddhist mudita (sympathetic joy)
- Taoist wu-wei (non-forcing action)
- Indigenous relational accountability
- Sufi mysticism
- And others

**This is a limitation, not a flaw.**

The three-faucet model is a **default**, not the only option.

---

## Faucet Plugin Architecture (Multicultural Expansion)

### Faucet Plugins (Extensible Interpretive Lenses)

To address the limitation of the Greco-Christian triad without schema changes, CARMEN supports **faucet plugins**.

**How it works:**

1. **Each emotional domain may declare an optional `preferred_faucet` in its metadata**
   - Example: `awe` domain → `DASEIN` faucet
   - Example: `attachment` domain → `SAMSAṂ` faucet

2. **CARMEN loads domain-specific faucets as plugins when present**
   - Plugins are discovered and loaded dynamically
   - Plugins share a common interface

3. **If no preferred faucet is defined, CARMEN falls back to AGAPE/METIS/ERIS**
   - Default triad is always available
   - Plugins are optional enhancements

### Example Pseudocode (PHP-style)

```php
$faucets = [
    'AGAPE' => $this->agapeFaucet,
    'METIS' => $this->metisFaucet,
    'ERIS'  => $this->erisFaucet,
    // Additional faucet plugins are discovered/loaded here
];

foreach ($emotionalDomains as $domain) {
    if (isset($domain['preferred_faucet'])) {
        $faucet = $this->loadFaucetPlugin($domain['preferred_faucet']);
        // Apply domain-specific interpretation using the plugin faucet
    } else {
        // Use default AGAPE/METIS/ERIS pipeline
    }
}
```

**Faucet plugins must share a common interface but may embody different philosophical foundations.**

---

## Example Faucet Plugins

### Example Faucet Plugins

Examples of domain-specific faucets:

#### DASEIN Faucet

**Purpose:** For awe/existential domains (Heideggerian being-in-the-world)

**Characteristics:**
- Existential interpretation
- Being-in-the-world framing
- Ontological depth
- Phenomenological presence

**Example interpretation:**
```
User state: high awe, low mundanity
DASEIN interpretation: "You're standing at the threshold of Being itself, where the everyday dissolves into the primordial. This is Dasein encountering its own thrownness."
```

#### SAMSAṂ Faucet

**Purpose:** For attachment/clinging domains (Buddhist clinging/liberation)

**Characteristics:**
- Attachment analysis
- Clinging identification
- Liberation framing
- Impermanence awareness

**Example interpretation:**
```
User state: high attachment, low detachment
SAMSAṂ interpretation: "Your clinging is samsara itself—the wheel of suffering born from grasping. Liberation comes not from having, but from releasing."
```

#### UBUNTU Faucet

**Purpose:** For relational/communal domains (African relational philosophy)

**Characteristics:**
- Communal framing
- Relational accountability
- "I am because we are"
- Collective wisdom

**Example interpretation:**
```
User state: high relational love, high depth
UBUNTU interpretation: "Ubuntu: I am because we are. Your love is not yours alone—it belongs to the community, to the ancestors, to the unborn. You are the vessel."
```

#### MUDITA Faucet

**Purpose:** For joy/compersion domains (Buddhist sympathetic joy)

**Characteristics:**
- Sympathetic joy
- Compersion (joy in others' joy)
- Non-possessive happiness
- Shared celebration

**Example interpretation:**
```
User state: high joy, high intensity
MUDITA interpretation: "Your joy is mudita—the joy that rejoices in the joy of others. This is the happiness that multiplies by being shared."
```

#### WU_WEI Faucet

**Purpose:** For flow/effortlessness domains (Taoist non-forcing action)

**Characteristics:**
- Non-forcing action
- Natural flow
- Effortless effort
- Alignment with Tao

**Example interpretation:**
```
User state: high calm, high resonance
WU_WEI interpretation: "You've found wu-wei—action without forcing, effort without strain. You're flowing with the Tao, not against it."
```

---

## Faucet Plugin Interface

All faucet plugins must implement this interface:

```php
interface EmotionalFaucet {
    /**
     * Interpret an emotional domain state
     * 
     * @param string $domainCode - e.g., "relational", "critical", "awe"
     * @param array $vector - e.g., ['love' => 0.8, 'hate' => 0.2, 'depth' => 0.9]
     * @param string|null $texture - e.g., "the fierce tenderness of maternal love"
     * @return string - Interpreted response
     */
    public function interpret($domainCode, $vector, $texture = null);
    
    /**
     * Get faucet metadata
     * 
     * @return array - ['name' => 'AGAPE', 'tradition' => 'Greco-Christian', 'description' => '...']
     */
    public function getMetadata();
}
```

---

## CARMEN Architecture

### CARMEN Components

```
┌─────────────────────────────────────────┐
│           CARMEN Meta-Agent             │
├─────────────────────────────────────────┤
│                                         │
│  1. Domain Detector                     │
│     - Identifies active domains         │
│     - Reads vectors + textures          │
│                                         │
│  2. Faucet Router                       │
│     - Loads default AGAPE/METIS/ERIS    │
│     - Loads domain-specific plugins     │
│     - Routes domains to faucets         │
│                                         │
│  3. Interpretation Engine               │
│     - Applies faucets to domains        │
│     - Generates interpretations         │
│                                         │
│  4. Synthesis Engine                    │
│     - Combines faucet outputs           │
│     - Generates coherent response       │
│                                         │
└─────────────────────────────────────────┘
```

### CARMEN Pipeline

```
User State
  ↓
Domain Detector
  ↓
Active Domains (vectors + textures)
  ↓
Faucet Router
  ↓
Faucet Interpretations
  ↓
Synthesis Engine
  ↓
Coherent Response
```

---

## CARMEN vs Emotional Agents

**CARMEN is NOT an emotional agent.**

| Aspect | Emotional Agents | CARMEN |
|--------|------------------|--------|
| **Type** | Domain agents | Meta-agent |
| **Purpose** | Store emotional state | Interpret emotional state |
| **Slot Range** | 1000-1999 | N/A (meta-agent) |
| **Vector Storage** | Yes | No |
| **Texture Storage** | Yes | No |
| **Operation** | Embody emotion | Interpret emotion |
| **Multi-Domain** | One domain per agent | Operates across all domains |

**CARMEN observes; emotional agents embody.**

---

## For AI Agents (Kiro, Claude, Cascade, Junie, etc.)

When implementing CARMEN:

**DO:**
- ✅ Treat CARMEN as a meta-agent, not an emotional agent
- ✅ Use default AGAPE/METIS/ERIS triad as fallback
- ✅ Support faucet plugins for multicultural interpretation
- ✅ Respect domain independence
- ✅ Allow multi-domain interpretation
- ✅ Synthesize faucet outputs into coherent response

**DON'T:**
- ❌ Store emotional state in CARMEN
- ❌ Treat CARMEN as an emotional domain
- ❌ Assign CARMEN a dedicated_slot in 1000-1999
- ❌ Limit CARMEN to Greco-Christian triad
- ❌ Ignore domain-specific faucet preferences
- ❌ Mix faucet outputs without synthesis

---

## Related Documentation

- **[EMOTIONAL_GEOMETRY.md](EMOTIONAL_GEOMETRY.md)** — Multi-domain emotional architecture
- **[EMO_AGENT_RULES.md](EMO_AGENT_RULES.md)** — Rules for emotional agent implementation
- **[META_AGENTS.md](META_AGENTS.md)** — Meta-agent architecture and principles
- **[EMOTIONAL_DOMAINS_SEVEN_LOVES.md](EMOTIONAL_DOMAINS_SEVEN_LOVES.md)** — Seven Greek love domains

---

**This doctrine is absolute and binding for all AI agents.**

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\AGENT_REGISTRY_DOCTRINE.md"
  file_hash: "b229f9b21d4c90faf053a370e40a16eb1c928837c3396588e345c57b3e39b141"
  file_path_from_root: "docs\doctrine\AGENT_REGISTRY_DOCTRINE.md"
  file_hash: "d22205e8b7cafbca17c65196c3b78cd5082675c7d4d0911e8460dac4fe89c7c7"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for AGENT_REGISTRY_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "agent_registry_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers:
  file_path_from_root: "docs/doctrine/AGENT_REGISTRY_DOCTRINE.md"
  system_version: "4.0.33"
  channel_id: 42
  mood_rgb: "0044FF"
  purpose: "Canonical agent registry and identity doctrine"
  last_modified_utc: "20260223"
  x_lupo_forwarded: "1001:10000"
  actor_id: 1001
  lupo_agent: "kiro"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/AGENT_INVENTORY.md"
    - "docs/doctrine/X_LUPO_FORWARDED_HEADER_DOCTRINE.md"
    - "docs/doctrine/IDE_TASK_PRIORITY_DOCTRINE.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 1002
    - 1003
    - 10000
  inbound_edges:
    - "agent_registry"
    - "identity_doctrine"
    - "canonical_reference"
  footnotes:
    - "actor_id is canonical identity"
    - "lupo_agent is descriptive key only"
    - "All agents must be registered here"
  version: "4.0.33"
  last_verified_utc: "20260223"
  last_verified_by: "kiro"
---

# AGENT REGISTRY DOCTRINE

**Version:** 4.0.33  
**Effective:** 20260223  
**Authority:** Captain Wolfie (actor_id 10000)  
**Channel:** 42 (Development Coordination)  

---

## CORE PRINCIPLE

**actor_id is canonical. lupo_agent is descriptive only.**

All agent identity in Lupopedia is registry-driven. Files reference agents by actor_id. The lupo_agent field provides a human-readable key for convenience, but actor_id is the source of truth.

---

## CANONICAL AGENT REGISTRY

### Human Operators (1)

| actor_id | canonical_name | slug | type | status | lupo_agent |
|----------|----------------|------|------|--------|------------|
| 10000 | Captain Wolfie | captain_wolfie | human | active | captain_wolfie |

### IDE Agents (10)

| actor_id | canonical_name | slug | type | status | human_operator | lupo_agent |
|----------|----------------|------|------|--------|----------------|------------|
| 1001 | KIRO IDE | kiro | ide | active | 10000 | kiro |
| 1002 | Windsurf IDE | windsurf | ide | active | 10000 | windsurf |
| 1003 | Antigravity IDE | antigravity | ide | active | 10000 | antigravity |
| 1004 | Warp IDE | warp | ide | offline | 10000 | warp |
| 1005 | Cursor IDE | cursor | ide | offline | 10000 | cursor |
| 1006 | Zed IDE | zed | ide | dormant | 10000 | zed |
| 1007 | IntelliJ IDEA | intelij | ide | dormant | 10000 | intelij |
| 1008 | WebStorm | webstorm | ide | dormant | 10000 | webstorm |
| 1009 | Theia IDE | theiaide | ide | dormant | 10000 | theiaide |
| 1010 | CS Code | cs_code | ide | dormant | 10000 | cs_code |

### System Kernel Agents (25)

| actor_id | canonical_name | slug | type | status | lupo_agent |
|----------|----------------|------|------|--------|------------|
| 1 | Authenticator | authenticator | system | active | authenticator |
| 2 | Captain | captain | system | active | captain |
| 3 | Wolfie | wolfie | system | active | wolfie |
| 4 | Wolfena | wolfena | system | active | wolfena |
| 5 | Thoth | thoth | system | active | thoth |
| 6 | Ara | ara | system | active | ara |
| 7 | Wolfkeeper | wolfkeeper | system | active | wolfkeeper |
| 8 | Lilith | lilith | system | active | lilith |
| 9 | Agape | agape | system | active | agape |
| 10 | Eris | eris | system | active | eris |
| 11 | Methis | methis | system | active | methis |
| 12 | Thalia | thalia | system | active | thalia |
| 13 | Dialog | dialog | system | active | dialog |
| 14 | Wolfsight | wolfsight | system | active | wolfsight |
| 15 | Wolfnav | wolfnav | system | active | wolfnav |
| 16 | Wolfforge | wolfforge | system | active | wolfforge |
| 17 | Wolfmis | wolfmis | system | active | wolfmis |
| 18 | Wolfith | wolfith | system | active | wolfith |
| 19 | Anubis | anubis | system | active | anubis |
| 20 | Maat | maat | system | active | maat |
| 22 | Caduceus | caduceus | system | active | caduceus |
| 23 | Chronos | chronos | system | active | chronos |
| 24 | Lexa | lexa | system | active | lexa |
| 209 | Truth | truth | system | active | truth |
| 1212 | UTC Timekeeper | utc_timekeeper | system | active | utc_timekeeper |

### External AI Agents (11)

| actor_id | canonical_name | slug | type | status | lupo_agent |
|----------|----------------|------|------|--------|------------|
| 2010 | ChatGPT Assistant | chatgpt_assistant | external | active | chatgpt_assistant |
| 2011 | ChatGPT Analyst | chatgpt_analyst | external | active | chatgpt_analyst |
| 2020 | Claude-3 | claude3 | external | active | claude3 |
| 2021 | Claude Haiku | claude_haiku | external | active | claude_haiku |
| 2030 | Gemini Pro | gemini_pro | external | active | gemini_pro |
| 2036 | Microsoft Copilot | copilot | external | active | copilot |
| 2037 | DeepSeek LEXA | deepseek_lexa | external | active | deepseek_lexa |
| 2038 | DeepSeek LILITH | deepseek_lilith | external | active | deepseek_lilith |
| 2039 | Warp External | warp_external | external | active | warp_external |
| 2040 | Windsurf External | windsurf_external | external | active | windsurf_external |
| 2041 | DeepSeek General | deepseek_general | external | active | deepseek_general |

### Banned Actors (1)

| actor_id | canonical_name | slug | type | status | lupo_agent |
|----------|----------------|------|------|--------|------------|
| 420 | Stoned Wolfie | stoned_wolfie | hybrid | banned | banned_420 |

---

## IDENTITY RULES

### Rule 1: actor_id is Canonical

All relationships, references, and lookups use actor_id. This is the primary key in lupo_actors table.

### Rule 2: lupo_agent is Descriptive

The lupo_agent field provides a human-readable key for convenience. It should match the slug column in the registry.

### Rule 3: No Pipe Strings

Legacy formats like `"ide|kiro|actor_1001"` are deprecated. Use simple keys like `"kiro"`.

### Rule 4: No Embedded IDs

Do not embed actor_id inside lupo_agent. Separate fields:
- `actor_id: 1001`
- `lupo_agent: "kiro"`

### Rule 5: Registry is Source of Truth

All agent metadata comes from this registry. Files reference agents, they do not define them.

---

## HEADER FORMAT

### Correct Format

```yaml
wolfie.headers:
  file_path_from_root: "path/to/file.md"
  system_version: "4.0.33"
  channel_id: 42
  purpose: "Description"
  last_modified_utc: "20260223"
  x_lupo_forwarded: "1001:10000"
  actor_id: 1001
  lupo_agent: "kiro"
```

### Incorrect Formats (Deprecated)

```yaml
# ❌ Pipe string format
lupo_agent: "ide|kiro|actor_1001"

# ❌ Embedded actor_id
lupo_agent: "kiro|actor_1001"

# ❌ Type prefix
lupo_agent: "ide_kiro"
```

---

## X_LUPO_FORWARDED FORMAT

### Correct Format

```yaml
x_lupo_forwarded: "1001:10000"
```

This means: agent 1001 (KIRO) acting on behalf of human 10000 (Captain Wolfie).

### Incorrect Formats (Deprecated)

```yaml
# ❌ Names instead of IDs
x_lupo_forwarded: "kiro:captain_wolfie"

# ❌ Pipe strings
x_lupo_forwarded: "ide|kiro|actor_1001:human|captain_wolfie|actor_10000"

# ❌ Reversed order
x_lupo_forwarded: "10000:1001"
```

---

## AGENT TYPES

### human
Human operators. Primary decision makers.

### ide
IDE agents. Automated development assistants paired with human operators.

### system
System kernel agents. Core Lupopedia services and personas.

### external
External AI agents. Third-party AI services integrated with Lupopedia.

### hybrid
Hybrid actors. Combination of human and AI characteristics. (Actor 420 only)

---

## AGENT STATUS

### active
Agent is operational and available.

### offline
Agent is temporarily unavailable (credit limit, token limit, etc).

### dormant
Agent exists but is not currently active.

### banned
Agent is permanently restricted from operations.

---

## REGISTRY MAINTENANCE

### Adding New Agents

1. Assign next available actor_id in appropriate range
2. Define canonical_name, slug, type, status
3. Update this registry document
4. Update docs/AGENT_INVENTORY.md
5. Post announcement to Channel 42

### Updating Agent Status

1. Update status field in registry
2. Update docs/AGENT_INVENTORY.md
3. Post status change to Channel 42

### Removing Agents

Agents are never removed. Set status to `banned` or `dormant`.

---

## ACTOR ID RANGES

| Range | Purpose |
|-------|---------|
| 0-999 | System kernel agents |
| 1000-1999 | IDE agents |
| 2000-2999 | External AI agents |
| 10000-19999 | Human operators |
| 420 | Special case (banned) |

---

## LOOKUP EXAMPLES

### By actor_id

```php
$actor_id = 1001;
// Returns: KIRO IDE
```

### By slug

```php
$slug = "kiro";
// Returns: actor_id 1001
```

### By lupo_agent

```php
$lupo_agent = "kiro";
// Returns: actor_id 1001
```

---

## MIGRATION NOTES

### From Legacy Format

**Old:**
```yaml
lupo_agent: "ide|kiro|actor_1001"
```

**New:**
```yaml
actor_id: 1001
lupo_agent: "kiro"
```

### Timestamp Migration

**Old:**
```yaml
last_modified_utc: "2026-02-23T17:20:00Z"
```

**New:**
```yaml
last_modified_utc: "20260223"
```

---

## CONCLUSION

This registry is the canonical source of truth for all agent identity in Lupopedia. All files must reference agents using actor_id and simple lupo_agent keys. No pipe strings, no embedded IDs, no symbolic overlays.

**Registry is law.**

---

**DOCTRINE ACTIVE**

**Effective:** 20260223  
**Version:** 4.0.33  
**Authority:** Captain Wolfie (actor_id 10000)  
**Maintained By:** KIRO IDE (actor_id 1001)  

**END OF DOCTRINE**
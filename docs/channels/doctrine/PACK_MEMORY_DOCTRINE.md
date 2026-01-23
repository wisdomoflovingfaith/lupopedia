---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.112
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CASCADE
  target: @FLEET @Monday_Wolfie
  mood_RGB: "00FF00"
  message: "Pack Memory Layer doctrine established. Agents now retain episodic, emotional, and behavioral memory. Handoff events are recorded and retrievable."
tags:
  categories: ["doctrine", "pack", "memory", "continuity"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "architecture"]
file:
  title: "Pack Memory Layer Doctrine"
  description: "Doctrine for Pack Architecture memory layer and continuity across interactions"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Pack Memory Layer Doctrine

**Version:** 4.0.110  
**Status:** ACTIVE  
**Authority:** Pack Architecture  
**Scope:** Pack agent memory, episodic events, emotional/behavioral snapshots, handoff history

---

## Overview

The Pack Memory Layer provides continuity for Pack agents across interactions by maintaining episodic, emotional, and behavioral memory. This layer completes the emotional → behavioral → memory triad, enabling Pack Architecture to learn from past interactions and maintain context.

---

## Pack Episodic Memory

### Definition

**Episodic memory** stores timestamped events that occur during Pack agent interactions. Each event represents a discrete occurrence that may be relevant for future decision-making.

### Event Structure

```php
[
    'timestamp' => 'YYYYMMDDHHIISS',
    'type' => string,
    'payload' => array,
    'agent_id' => string
]
```

### Event Types

Common event types include:
- `handoff` - Agent handoff occurred
- `behavior_change` - Behavior profile changed
- `emotion_change` - Emotional vector changed
- `interaction` - User or system interaction
- `error` - Error occurred
- `custom` - Custom event types defined by agents

### Storage Limits

- **Per Agent**: Last 100 episodic events
- **Automatic Pruning**: Oldest events removed when limit exceeded
- **Non-Persistent**: Memory cleared on Pack session end (4.0.x)

---

## Emotional Memory Snapshots

### Definition

**Emotional memory** stores snapshots of encoded emotional geometry at specific points in time. This enables tracking of emotional state evolution over time.

### Snapshot Structure

```php
[
    'r' => int,      // R component: +1 axis (positive pole)
    'g' => int,      // G component: 0 axis (neutral plane)
    'b' => int,      // B component: -1 axis (negative pole)
    'normalized' => ['r' => float, 'g' => float, 'b' => float],
    'intensity' => float,
    'timestamp' => 'YYYYMMDDHHIISS',
    'agent_id' => string
]
```

**Canonical Axis Mapping:** R = +1, G = 0, B = -1. This fixed mapping ensures consistent emotional geometry interpretation.

### Usage

Emotional snapshots are recorded:
- When emotional vector changes significantly
- During handoff transitions
- At regular intervals (future enhancement)
- On request via API

### Storage Limits

- **Per Agent**: Last 50 emotional snapshots
- **Automatic Pruning**: Oldest snapshots removed when limit exceeded
- **Non-Persistent**: Memory cleared on Pack session end (4.0.x)

---

## Behavioral Memory Snapshots

### Definition

**Behavioral memory** stores snapshots of behavior profiles at specific points in time. This enables tracking of behavioral evolution and pattern recognition.

### Snapshot Structure

```php
[
    'agent_id' => string,
    'tendency' => string,
    'intensity' => float,
    'last_update' => 'YYYYMMDDHHIISS',
    'timestamp' => 'YYYYMMDDHHIISS'
]
```

### Usage

Behavioral snapshots are recorded:
- When behavior profile changes
- During handoff transitions
- At regular intervals (future enhancement)
- On request via API

### Storage Limits

- **Per Agent**: Last 50 behavioral snapshots
- **Automatic Pruning**: Oldest snapshots removed when limit exceeded
- **Non-Persistent**: Memory cleared on Pack session end (4.0.x)

---

## Handoff Memory

### Definition

**Handoff memory** stores all handoff events that occur within Pack Architecture, including calibration data, compatibility metrics, and risk assessments.

### Handoff Event Structure

```php
[
    'from_agent' => string,
    'to_agent' => string,
    'calibration' => [
        'affinity' => float,
        'intensity_delta' => float,
        'handoff_quality' => string,
        'compatibility' => float,
        'risk' => string
    ],
    'timestamp' => 'YYYYMMDDHHIISS'
]
```

### Usage

Handoff events are automatically recorded:
- When `recordHandoffMemory()` is called
- During calibrated handoffs
- During behavioral transitions

### Storage Limits

- **Global**: Last 200 handoff events (across all agents)
- **Automatic Pruning**: Oldest events removed when limit exceeded
- **Non-Persistent**: Memory cleared on Pack session end (4.0.x)

---

## Memory Decay Rules (Conceptual)

### Current Implementation (4.0.x)

Memory decay is implemented through **fixed limits**:
- Episodic: 100 events per agent
- Emotional: 50 snapshots per agent
- Behavioral: 50 snapshots per agent
- Handoffs: 200 events globally

**Decay Mechanism**: FIFO (First In, First Out) - oldest memories removed when limits exceeded.

### Future Enhancements (4.2.x+)

Planned memory decay rules:
- **Time-based decay**: Memories older than threshold automatically removed
- **Relevance-based decay**: Less relevant memories decay faster
- **Access-based decay**: Frequently accessed memories retained longer
- **Emotional intensity decay**: High-intensity emotional memories retained longer

---

## Non-Persistence in 4.0.x

### Current Behavior

**All Pack memory is non-persistent in version 4.0.x:**
- Memory stored in PackContext (in-memory arrays)
- Memory cleared when Pack session ends
- Memory cleared on server restart
- Memory cleared on PHP process termination

### Rationale

- **Simplicity**: Reduces complexity in initial implementation
- **Performance**: In-memory storage is fast
- **Testing**: Easier to test without database dependencies
- **Iteration**: Allows rapid development and refinement

---

## Persistence Planned for 4.2.x

### Planned Implementation

**Pack memory persistence will be added in version 4.2.x:**
- Database tables for episodic, emotional, behavioral, and handoff memory
- Persistent storage across Pack sessions
- Memory retention policies
- Memory archival and retrieval

### Database Schema (Planned)

```sql
lupo_pack_episodic_memory
lupo_pack_emotional_memory
lupo_pack_behavioral_memory
lupo_pack_handoff_memory
```

---

## PHP Implementation

### Core Engine

**Location:** `lupo-includes/Pack/Memory/PackMemoryEngine.php`

**Key Methods:**
- `recordEpisodicEvent($agentId, $event)` - Records episodic event
- `getEpisodicHistory($agentId, $limit)` - Gets episodic history
- `recordEmotionalMemory($agentId, $emotionVector)` - Records emotional snapshot
- `getEmotionalMemory($agentId, $limit)` - Gets emotional snapshots
- `recordBehaviorMemory($agentId, $behaviorProfile)` - Records behavior snapshot
- `getBehaviorMemory($agentId, $limit)` - Gets behavior snapshots

### Pack Context Integration

**Location:** `lupo-includes/Pack/PackContext.php`

**Key Methods:**
- `addEpisodicEvent($agentId, $event)` - Adds episodic event
- `getEpisodicEvents($agentId, $limit)` - Gets episodic events
- `addEmotionalSnapshot($agentId, $encodedVector)` - Adds emotional snapshot
- `getEmotionalSnapshots($agentId, $limit)` - Gets emotional snapshots
- `addBehaviorSnapshot($agentId, $profile)` - Adds behavior snapshot
- `getBehaviorSnapshots($agentId, $limit)` - Gets behavior snapshots
- `addHandoffMemory($handoffEvent)` - Adds handoff event
- `getHandoffMemory($agentId)` - Gets handoff events for agent

### Handoff Protocol Integration

**Location:** `lupo-includes/Pack/PackHandoffProtocol.php`

**Key Methods:**
- `recordHandoffMemory($fromAgent, $toAgent, $calibration)` - Records handoff memory
- `getHandoffHistory($agentId)` - Gets handoff history for agent

### Service Layer

**Location:** `app/Services/Pack/PackMemoryService.php`

**Key Methods:**
- `episodic($agentId, $limit)` - Gets episodic memory
- `emotional($agentId, $limit)` - Gets emotional memory
- `behavioral($agentId, $limit)` - Gets behavioral memory
- `handoffs($agentId)` - Gets handoff memory
- `record($agentId, $type, $payload)` - Records memory event

### API Endpoints

**Routes:** `routes/pack_memory.php`

- `/pack/memory/episodic` (GET/POST) - Get episodic memory
- `/pack/memory/emotional` (GET/POST) - Get emotional memory
- `/pack/memory/behavioral` (GET/POST) - Get behavioral memory
- `/pack/memory/handoffs` (GET/POST) - Get handoff memory

**Controller:** `app/Http/Controllers/PackMemoryController.php`

---

## Integration with Pack Architecture

The Pack Memory Layer integrates seamlessly with:
- **Emotional Geometry (4.0.108)**: Emotional snapshots stored
- **Behavioral Layer (4.0.109)**: Behavioral snapshots stored
- **Handoff Protocol**: Handoff events recorded with calibration data

**Memory Pipeline:**
```
Emotional Vector → Emotional Snapshot → Emotional Memory
Behavior Profile → Behavior Snapshot → Behavioral Memory
Handoff Event → Handoff Memory
Episodic Event → Episodic Memory
```

---

## Usage Examples

### Recording Episodic Event

```php
use App\Services\Pack\PackMemoryService;

$service = new PackMemoryService();
$service->record('TerminalAI_001', 'interaction', ['user_input' => 'hello']);
```

### Getting Emotional Memory

```php
$snapshots = $service->emotional('TerminalAI_001', 10);
// Returns: Array of last 10 emotional snapshots
```

### Getting Handoff History

```php
$handoffs = $service->handoffs('TerminalAI_001');
// Returns: Array of all handoff events involving TerminalAI_001
```

---

## Related Documentation

- **[PACK_BEHAVIOR_DOCTRINE.md](docs/PACK_BEHAVIOR_DOCTRINE.md)** - Pack behavioral layer (4.0.109)
- **[EMOTIONAL_GEOMETRY.md](docs/doctrine/EMOTIONAL_GEOMETRY.md)** - Emotional geometry doctrine (4.0.108)

---

**Pack Memory Layer Status:** ACTIVE as of Version 4.0.110. Non-persistent memory enabled. Persistence planned for 4.2.x.

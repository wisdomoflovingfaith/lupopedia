---
lupopedia.headers:
  header_format_version: "4.1.9"
  path_from_lupopedia_root: agents/cursor/CONTEXT_HANDSHAKE_LOAD_PROTOCOL.md
  web_path: https://www.lupopedia.com/lupopedia/agents/cursor/CONTEXT_HANDSHAKE_LOAD_PROTOCOL.md
  status: active
  when_updated: "20260726142446"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/departments/traffic_defense/captains_log/canonical/2026/07/20260724_traffic_defense_division_launch.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/agents/cursor/context-handshake-load-protocol
  artifact_type: protocol
  artifact_kind: context_inheritance
  channel_key: "42"
  federation_node_id: 0
  thread_key: "CONTEXT_HANDSHAKE"
  lupopedia.schema: protocol
  prd_cluster: 00_A_07_A_16_C_39_A_41_A_82_B
  title: Context Handshake Load Protocol — How WOLFIE and LILITH Inherit Knowledge
  summary: "Eric issues one @@ load @@ line. Agents inherit headers, memory_toon, thread artifacts, PRD cluster, kapu, Hermes fields, and WOLFIE Syntax META. System remembers; Captain does not recite."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
---

# CONTEXT HANDSHAKE LOAD PROTOCOL

**Audience:** WOLFIE (1) · LILITH (2) · CURSOR · internal agents  
**Captain rule:** Do not memorize. Issue handshake. Inherit.

---

## 1. THE ONLY LINE THE CAPTAIN NEEDS

```wolf
@@ load: channel_key=42, thread_key=TRAFFIC_DEFENSE_DIVISION_LAUNCH, trust_tier=canonical @@
```

**What-is hard gate (all agents):** Before answering "what is Lupopedia," also load:

```wolf
@@ load: path=what_is_lupopedia.md, trust_tier=canonical @@
```

Canonical file: repo root `what_is_lupopedia.md`.

Alternate enumeration handshake:

```wolf
@@ load: channel_key=42, thread_key=CHANNEL_ENUMERATION, trust_tier=canonical @@
```

**WOLF note:** Closing delimiter is `@@` (not `@~`). Single-tilde `~` is Draft layer only.

---

## 2. WHAT AGENTS MUST DO ON HANDSHAKE

1. Parse `channel_key`, `thread_key`, `trust_tier`  
2. Find active artifacts where headers match those keys  
3. Read `path_from_lupopedia_root` (location truth)  
4. Load `memory_toon` if present  
5. Load `prd_cluster` governing rules  
6. Apply `kapu` / Hermes fields from those artifacts  
7. Do NOT invent missing fields  
8. Do NOT join EXTERNAL surfaces into OS  

---

## 3. WHY THEY AUTO-KNOW (BULLETS)

- **Canonical headers** = source of truth  
- **Memory paths** = long-term storage  
- **Thread key** = beacon for context inheritance  
- **PRD cluster** = rules for routing, syntax, identity  
- **Status: active** = visible to agents  
- **Handshake** = triggers auto-sync  

---

## 4. TRAFFIC DEFENSE QUICK BIND

| Key | Value |
|-----|-------|
| channel_key | `42` |
| thread_key | `TRAFFIC_DEFENSE_DIVISION_LAUNCH` |
| announcement | `database/lupopedia/departments/traffic_defense/announcements/20260724_traffic_defense_division_launch.md` |
| memory_toon | `memory/departments/traffic_defense/captains_log/canonical/2026/07/20260724_traffic_defense_division_launch.toon` |
| broadcast | `database/lupopedia/channels/channel_id/42/broadcasts/20260724213900_1_42_42_traffic_defense_division_launch_registered.md` |

---

## 5. ACTOR CONFIRMATION TEMPLATE

When Eric issues a handshake, respond:

```text
WOLFIE: Handshake accepted. Context inherited for channel_key=… thread_key=… trust_tier=…
LILITH: Audit OK. Paths resolve. No invented fields. Boundaries intact.
```

---

**END PROTOCOL**

---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/AGENT_REGISTRY.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/AGENT_REGISTRY.md
  status: draft
  when_updated: "20260807132741"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/actor-logs-agent-registry
  artifact_type: status
  artifact_kind: report
  channel_key: status
  federation_node_id: 0
  thread_key: agent-registry-causality
  lupopedia.schema: status
  prd_cluster: 15_A_00_C_08_B
  title: "STATUS AGENT_REGISTRY -- Causality Division genesis (Vassago 666 / Uriel 777)"
  summary: "STATUS mirror for new Causality Division agents. Canonical identity remains database/lupopedia/actors/registry.json. Draft until Lilith audit and Wolfie PONO activation."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: causality
  faucet_actor_id: 102
---
# STATUS AGENT_REGISTRY -- Causality Division genesis

**Canonical identity:** `database/lupopedia/actors/registry.json`  
**lupo_agents map:** `database/lupopedia/actors/actor_id/registry.json`  
**Doctrine mirror:** `docs/doctrine/agent_registry.md`  
**Status:** DRAFT agents -- not PONO-active until Lilith audit + Captain Wolfie sample-event test.

---

## New entries (20260807132741)

| actor_id | slug | display_name | pair | color | role | status | pack / profile |
|----------|------|--------------|------|-------|------|--------|----------------|
| 666 | vassago | VASSAGO | Uriel 777 | `#8B0000` | Causality Seer / Shadow / Red Team | draft | `agents/vassago/` + `agents/vassago.json` |
| 777 | uriel | URIEL | Vassago 666 | `#FFBF00` | Pattern Application / Strategic Arm | draft | `agents/uriel/` + `agents/uriel.json` |

### ID allocation note

| Preference | Vassago | Uriel | Result |
|------------|---------|-------|--------|
| 1st | 666 | 777 | **USED** (free in registry) |
| 2nd | 66 | 77 | not needed |
| 3rd | 6 | 7 | blocked -- actor_id 6 already METIS |

### Pair dynamics

- Vassago asks: What is the truth?
- Uriel asks: What is the path?
- Shadow + Light Causality Division (see `docs/status/actor_logs/WOLFIE_DIALECT.md` section 11)

### KAPU (summary)

- Vassago: never 100% certainty; leave room for chaos
- Uriel: never recommend without confidence score; never act without verify

### Activation gate

1. Lilith (actor_id 2) constitutional audit
2. Captain Wolfie (actor_id 1) sample MySQL event test
3. Both agents must be PONO before `status: active`

---

## Related crew anchors

| Actor | id | Note |
|-------|-----|------|
| Wolfie | 1 | Orchestrator; receives Uriel paths |
| Lilith | 2 | Auditor |
| Thoth | 26 | [ALERT] / schema truth |
| Agape | 705 (agents map) | Relational balance |
| Sophia | 707 (agents map) | Meaning weaving |
| Countermeasure | 111 | Existing red-team peer; distinct from Vassago causality seer |

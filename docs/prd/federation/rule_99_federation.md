---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/federation/rule_99_federation.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/federation/rule_99_federation.md
  status: active
  when_updated: "20260810161440"
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/prd-federation
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: rule-99-federation
  lupopedia.schema: prd
  prd_cluster: 99_A_34_A
  title: "RULE 99.FEDERATION -- bullet specification"
  summary: "Federation Node 0 root; Node 1+ sovereign catalogs; each Node 144000 Actors x 100 HEX; Node 0 directory for lookup."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: federation
  faucet_actor_id: 102
---
# RULE 99.FEDERATION -- bullet specification

Parent: PRD 99. Lilith enforces.

## 1. Federation Node concept

- Lupopedia is Federation Node 0.
- Any decentralized installation of Lupopedia Semantic OS becomes Federation Node 1, 2, 3, ...
- Federation Nodes are independent but follow the same constitutional rules.
- Federation Nodes may host their own 144000-Actor catalog.
- Federation Nodes may have their own color ranges, their own creators, and their own IDs.

## 2. Federation ID in song IDs

- If a song does not include a Federation ID, it belongs to Node 0 (lupopedia.com).
- If a song does include a Federation ID, you must:
  - Look up the Federation ID at lupopedia.com (Node 0).
  - Lupopedia will tell you which domain hosts that Node.
  - You then look up the song at that Node's domain.

## 3. Why federation exists

- Decentralization.
- Scalability beyond Node 0.
- Local sovereignty for creators.
- Each Node can host its own 144000 Actors.
- Each Node can define its own catalog identity.
- Lupopedia.com remains the root registry.

## 4. Actor limits per Node

- Each Federation Node supports exactly 144000 Actors.
- This limit is constitutional.
- This limit must be enforced in PRD files.
- No Node may exceed 144000 Actors.
- No Node may exceed 100 colors per Actor.
- No Node may exceed the global HEX ceiling FFFFFF (per Node universe).

## 5. Song lookup rules

- If Federation ID is missing, lookup happens at Node 0.
- If Federation ID is present, lookup happens at:
  - Node 0 -> Federation directory -> Node domain -> Song catalog.
- Node 0 acts as the DNS for all decentralized catalogs.

## 6. Federation Node ID rules

- Node 0 = lupopedia.com.
- Node 1+ = decentralized installs.
- Node ID must be included in the song ID if the song is not from Node 0.
- Node ID determines where the song catalog is hosted.
- Node ID determines which Actor ranges apply.
- Node ID does not waive PRD 00 / PRD 99 math.

## 7. Federation and color ranges

- Each Node has its own 16777216-color universe.
- Each Node allocates 144000 Actors x 100 colors = 14400000 colors.
- Each Node reserves 2377216 colors for system use.
- Reserved zone begins at DBBA00 and ends at FFFFFF.
- These numbers must be identical across all Nodes.

## 8. IDE / agent integration notes

- Music ID system lives in /music (install-relative).
- Federation logic lives in docs/prd/federation/.
- Do not assume Node 0 is the only catalog.
- Do not assume Actor IDs equal Catalog Numbers.
- Do not assume 256-slot ranges.
- Enforce 100-slot HEX ranges only.
- Enforce 144000-Actor limit only (per Node).

## 9. What Lilith must enforce

- Federation ID correctness.
- Node lookup correctness.
- Actor range correctness.
- HEX range correctness.
- Reserved zone correctness.
- Global ceiling correctness.
- No intra-Node color collisions.
- No Node exceeding 144000 Actors.
- No Actor exceeding 100 colors.
- No non-Node-0 song published without proper Node ID.

## 10. What this invents

- A decentralized, federated, color-based music registry.
- Node 0 as the root authority.
- Node 1+ as sovereign creative universes.
- Each Node with 144000 Actors.
- Each Actor with 100 HEX colors.
- Each Node with its own catalog identity.
- Lupopedia as the global lookup system.

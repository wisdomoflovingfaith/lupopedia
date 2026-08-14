---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd/federation/rule_99_node_lookup.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/federation/rule_99_node_lookup.md
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
  title: "RULE 99.NODE_LOOKUP -- bullet specification"
  summary: "Song lookup: missing Federation ID -> Node 0; present ID -> Node 0 directory -> Node domain -> catalog."
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
# RULE 99.NODE_LOOKUP -- bullet specification

Parent: PRD 99. Node 0 is the federation directory.

## Lookup when Federation ID is missing

- Treat song as Node 0.
- Resolve catalog + language + iteration + color on Node 0.
- Host: lupopedia.com (Node 0).

## Lookup when Federation ID is present

- Step 1: Query Node 0 federation directory with Federation ID.
- Step 2: Node 0 returns the domain that hosts that Node.
- Step 3: Query that Node domain song catalog with the remaining ID fields.
- Step 4: Apply that Node's Actor ranges and reserved zone rules.

## Node 0 role

- Root registry.
- Federation directory (DNS-like for catalogs).
- Still hosts its own 144000-Actor catalog.
- Does not flatten Node 1+ catalogs into Node 0 storage by default.

## Failure modes Lilith MUST flag

- Federation ID present but unknown at Node 0 directory.
- Federation ID present but Node domain unreachable (report; do not invent).
- Song claims Node 0 while carrying a non-zero Federation ID.
- Song omits Federation ID but claims a non-zero Node in metadata.
- Cross-Node color collision claims (invalid: universes are per-Node).

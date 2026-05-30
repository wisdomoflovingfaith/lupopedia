---
lupopedia.headers:
  federation_node_id: 2  # External node ID (2, 3, 4, etc.)
  when_updated: "{{CURRENT_UTC}}"
  lupopedia.schema: "research"
  file_path_from_root: "lupo-content/federation_node_id/{{NODE_ID}}/{{CATEGORY}}/{{FILENAME}}.md"
  content_id: "{{CONTENT_ID}}"  # Optional
  web_path: "{{EXTERNAL_URL}}"
  questions_toon: null
  channel_id: {{CHANNEL_ID}}
  actor_id: {{ACTOR_ID}}
  actor_name: "{{ACTOR_NAME}}"
  delegation_chain: "{{DELEGATION_CHAIN}}"
  artifact_type: "research"
  artifact_kind: "analysis"
  purpose: "{{PURPOSE}}"
  tags: {{TAGS}}

lupopedia.footer:
  last_verified: "{{CURRENT_UTC}}"
  verified_by:
    identity_type: "actor"
    actor_id: {{ACTOR_ID}}
    agent_name_identity: "{{AGENT_NAME}}"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "{{FAUCET_SLUG}}"
  orchestrator: "{{ORCHESTRATOR}}"
  next_action:
    - "{{NEXT_ACTION}}"
---

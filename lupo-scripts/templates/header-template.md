---
lupopedia.headers:
  federation_node_id: 1  # 0=core, 1=current install, 2+=external nodes
  when_updated: "{{CURRENT_UTC}}"
  lupopedia.schema: "{{SCHEMA_TYPE}}"
  file_path_from_root: "{{FILE_PATH}}"
  # content_id: "{{CONTENT_ID}}"  # Optional - added after import
  web_path: "http://www.lupopedia.com/lupopedia/{{FILE_PATH}}"  # Node 0/1 format
  # For external nodes, use full URL:
  # web_path: "https://external-site.com/path/to/resource"
  last_modified_utc: "{{CURRENT_UTC}}"
  channel_id: {{CHANNEL_ID}}
  actor_id: {{ACTOR_ID}}
  actor_name: "{{ACTOR_NAME}}"
  delegation_chain: "{{DELEGATION_CHAIN}}"
  artifact_type: "{{ARTIFACT_TYPE}}"
  artifact_kind: "{{ARTIFACT_KIND}}"
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

# {{TITLE}}

{{CONTENT}}

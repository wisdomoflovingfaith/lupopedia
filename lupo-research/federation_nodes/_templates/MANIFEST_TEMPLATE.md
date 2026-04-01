---
manifest_version: 1
federation_node_id: [ID]
source_name: "[External source name]"
source_url: "[Original URL]"
source_version: "[Version]"
ingested_date: "YYYYMMDD"
ingested_by: "[agent_name]"
compatibility: "Lupopedia 4.0.93+"
purpose: "[Brief description of why this research was ingested]"
documentation_type: "[external_library|protocol|specification|whitepaper]"
status: "[active|archived|deprecated]"
---

# [Source Name] — Federation Node Research

## Source Information
- **Node ID**: [federation_node_id]
- **Source URL**: [source_url]
- **Original Version**: [source_version]
- **Ingested**: [ingested_date] by [ingested_by]

## Purpose
[Why we ingested this]

## Key Takeaways
- [Takeaway 1]
- [Takeaway 2]

## Integration Notes
- [How this integrates with Lupopedia]
- [Which agents use this context]

## Relevant Tables/Edges
- `lupo_federation_nodes` — node_id [ID]
- `lupo_edges` — edge_type: 'references_federation'

## Review Cycle
- **Last Reviewed**: [date]
- **Next Review**: [date + 6 months]

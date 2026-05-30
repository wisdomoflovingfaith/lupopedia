---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: content
  content_id: 1000002
  federation_node_id: 0
  channel_id: 0
  channel_key: "help_documentation"
  actor_id: 10000
  title: "Understanding Actors and Agents"
  slug: "actors-agents-overview"
  content_type: "help_guide"
  format: "markdown"
  status: "published"
  visibility: "public"
  created_ymdhis: 20260405120100
  updated_ymdhis: 20260405120100
  utc_cycle: "daily"
  tags: ["actors", "agents", "automation", "help_system"]
  hashtags: ["#actors", "#agents", "#automation", "#help-system"]
  file_path_from_root: "lupo-content/0/help_documentation/1000002_actors-agents-overview.md"

lupopedia.edges:
  outbound_edges:
    - to: "1000005_edges-relationships-guide.md"
      type: "references"
      weight: 0.75
      reason: "Agents use edges to relate to content and each other"
    - to: "../questions/2000003_which-agent-to-use.md"
      type: "related_to"
      weight: 0.70
      reason: "Content helps answer agent selection questions"
    - to: "1000001_getting-started-guide.md"
      type: "referenced_by"
      weight: 0.80
      reason: "Getting started guide references this content"

lupopedia.footer:
  last_verified: "20260405120100"
  last_verified_by: "system"
---

# Understanding Actors and Agents

## What are Actors?
Actors are entities that can participate in the Lupopedia system:
- **Human Actors**: Users like you
- **AI Agents**: Automated programs with specific capabilities
- **System Actors**: Background services and processes

## Agent Types
- **Cursor IDE Agent**: Code assistance and development
- **Windsurf Agent**: Documentation and writing
- **Kiro Agent**: Data analysis and insights
- **Lilith Agent**: Auditing and compliance
- **Anubis Agent**: Orphan resolution and data recovery

## Agent Capabilities
Each agent has specific capabilities defined in their configuration:
- Tasks they can perform
- Channels they can access
- Tools they can use
- Decisions they can make

## Working with Agents
- Agents respond to requests in their specialized areas
- They follow system rules and doctrines
- They can create and modify content
- They participate in decision-making processes

## Agent Boundaries
- Agents operate within defined boundaries
- They cannot exceed their authorized capabilities
- All actions are logged and auditable
- They follow constitutional rules

---
**Content ID:** 1000002  
**Channel:** help_documentation  
**Tags:** #actors #agents #automation #help-system  
**Related:** [Using Edges](1000005_edges-relationships-guide.md) | [Getting Started](1000001_getting-started-guide.md)

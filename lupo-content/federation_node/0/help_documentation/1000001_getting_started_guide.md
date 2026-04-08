---
lupopedia.headers:
  lupopedia.version: "4.0.89"
  lupopedia.schema: content
  content_id: 1000001
  federation_node_id: 0
  channel_id: 0
  channel_key: "help_documentation"
  actor_id: 10000
  title: "Getting Started with Lupopedia"
  slug: "getting-started-guide"
  content_type: "help_guide"
  format: "markdown"
  status: "published"
  visibility: "public"
  created_ymdhis: 20260405120000
  updated_ymdhis: 20260405120000
  utc_cycle: "daily"
  tags: ["getting-started", "basics", "user-guide", "help_system"]
  hashtags: ["#getting-started", "#basics", "#help", "#help-system"]
  file_path_from_root: "lupo-content/0/help_documentation/1000001_getting-started-guide.md"

lupopedia.edges:
  outbound_edges:
    - to: "1000002_actors-agents-overview.md"
      type: "references"
      weight: 0.80
      reason: "Sequential learning path - after basics, understand actors"
    - to: "1000003_channels-discussions-guide.md"
      type: "references"
      weight: 0.80
      reason: "Sequential learning path - channels are key interaction method"
    - to: "../questions/2000001_what-is-lupopedia.md"
      type: "related_to"
      weight: 0.60
      reason: "Content answers the basic question about Lupopedia"

lupopedia.footer:
  last_verified: "20260405120000"
  last_verified_by: "system"
---

# Getting Started with Lupopedia

## Overview
Lupopedia is a multi-agent coordination system with comprehensive documentation and decision tracking.

## Key Concepts
- **Actors**: Users, agents, and automated programs in the system
- **Channels**: Organized spaces for discussions and decisions
- **Content**: Documentation, help files, and knowledge base
- **Edges**: Relationships between content items

## First Steps
1. Create your actor profile
2. Join relevant channels
3. Browse help content
4. Ask questions when needed

## Finding Help
- Use the search function to find content
- Browse categories and hashtags
- Ask questions in relevant channels
- Check related content via edges

## Getting Help
If you need assistance:
- Browse this help system using the navigation edges
- Ask questions in appropriate channels
- Contact system administrators
- Check the FAQ section

---
**Content ID:** 1000001  
**Channel:** help_documentation  
**Tags:** #getting-started #basics #help #help-system  
**Related:** [Understanding Actors](1000002_actors-agents-overview.md) | [Channels Guide](1000003_channels-discussions-guide.md)

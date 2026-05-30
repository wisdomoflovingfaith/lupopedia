# Lupopedia Help Content

This directory contains user-facing help content for the help_documentation channel.

## Structure

- **Content Files**: Help articles and documentation (IDs 1000001-1000005)
- **Questions**: Common user questions (IDs 2000001-2000008) 
- **Answers**: Responses to questions (IDs 3000001-3000008)
- **Edges**: Relationships between all items (IDs 4000001-4000034)

## Content Organization

### Help Guides (1000001-1000005)
- `1000001_getting-started-guide.md` - Basic introduction to Lupopedia
- `1000002_actors-agents-overview.md` - Understanding actors and AI agents
- `1000003_channels-discussions-guide.md` - Using channels for discussions
- `1000004_content-documentation-guide.md` - Finding and creating content
- `1000005_edges-relationships-guide.md` - Using edges for navigation

### Questions (2000001-2000008)
- Basic questions about Lupopedia functionality
- Common user queries and concerns
- Starting points for help content

### Answers (3000001-3000008)
- Direct responses to user questions
- Concise, helpful information
- Links to related help content

### Edges (4000001-4000034)
- Content-to-content relationships
- Question-to-answer mappings
- Cross-references and navigation paths

## Channel-Based Organization

This content uses the **channel_key** based folder structure:
```
content/{federation_node_id}/{channel_key}/{content_file}
```

Where:
- **federation_node_id**: 0 (core system)
- **channel_key**: help_documentation (user-facing help)
- **content_file**: {content_id}_{slug}.md

## Database Seeding

All content in this directory is seeded into the database during installation via:
`database/lupopedia/mysql/seed/seed_online_help_and_content.sql`

## Synchronization

- Database is authoritative for user-facing content
- File system provides backup and version control
- Changes should be made in both places
- Use proper LUPOPEDIA headers in all files

## Hashtags and Tags

All content uses standardized hashtags for discoverability:
- `#getting-started` - Basic introductory content
- `#basics` - Fundamental concepts
- `#help` - General help content
- `#help-system` - Meta content about the help system
- `#actors` - Actor-related content
- `#agents` - AI agent information
- `#automation` - Automated processes
- `#channels` - Channel usage
- `#discussions` - Discussion guidelines
- `#decisions` - Decision processes
- `#content` - Content management
- `#documentation` - Documentation practices
- `#search` - Finding information
- `#edges` - Relationship navigation
- `#relationships` - Content connections
- `#navigation` - System navigation

## Channel Key Benefits

Using `channel_key` instead of `channel_id` provides:
- **Human-readable paths** - easier to navigate and understand
- **Semantic organization** - content is grouped by purpose
- **Future-proof** - channel keys remain stable even if IDs change
- **Developer-friendly** - easier to work with in code and scripts

## Maintenance

When updating help content:
1. Update the database seed file
2. Update the corresponding file here
3. Ensure edges are updated for new relationships
4. Test search and navigation functionality
5. Verify hashtag consistency

---
**Help System Version:** 4.0.89  
**Channel:** help_documentation  
**Last Updated:** 2026-04-05  
**Content Count:** 5 guides, 8 questions, 8 answers, 34 edges

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\database\csv_data\README.md"
  file_hash: "4d3d6b372f077374f5bd615aa92ef5eb0ad9fe78ed1625e5566eccde54f19d02"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "database\csv_data\README.md"
  file_hash: "68a66627e03025195bf861b3a7d66b936ad2dc47ba4870253b271e1c4b2c1fcb"
  file_path_from_root: "database\csv_data\README.md"
  file_hash: "bcb73c9070fac40f43f99b5f5528ea6d1d3076ee001815dfe6c9ccd6949e9968"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Database Schema CSV Reference"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["database", "csv_data", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Database Schema CSV Reference

This directory contains CSV representations of database tables for use with AI coding assistants. These files serve as a reliable reference for the database schema when direct database access is not available or practical.

## File Format

Each CSV file follows this structure:
- **Row 0**: Column names
- **Row 1**: Column data types
- **Subsequent rows**: Example data (if available)

## Purpose

These CSV files are used to:
- Provide consistent schema information to AI assistants
- Prevent hallucinations about database structure
- Enable parallel development across multiple AI models
- Serve as a lightweight, version-controlled schema reference

## Usage

When working with AI assistants:
1. Include the relevant CSV file in your prompt
2. Reference the column names and types when writing queries
3. Use the example data to understand the expected format

## Security Note

- These files may contain non-sensitive example data
- Never include real credentials or sensitive information
- The actual database may contain additional constraints and indexes not shown here

## File Naming

Files are named after their corresponding database tables, with the prefix removed for clarity. For example:
- `auth_providers.csv` represents `{DB_PREFIXTABLE_PREFIX}auth_providers`

## Adding New Tables

When adding a new table to the database:
1. Create a new CSV file in this directory
2. Follow the standard format (headers, types, examples)
3. Update this README if needed

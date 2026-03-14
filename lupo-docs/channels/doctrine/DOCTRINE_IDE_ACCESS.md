# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\DOCTRINE_IDE_ACCESS.md"
  file_hash: "2b66c882e6d9db34179f68038317841d70a5ed3c234ba1dbf0fce224579f938e"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\channels\doctrine\DOCTRINE_IDE_ACCESS.md"
  file_hash: "1eb46f044c684175873316e958b7073011c1b58db44ae2eaad214ff9bb5abc05"
  file_path_from_root: "lupo-docs\channels\doctrine\DOCTRINE_IDE_ACCESS.md"
  file_hash: "1d71c83fff17c766f40843cb6cde36ee0ca4e86369ff5a42637b4bfd66129182"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "IDE AGENT DATABASE ACCESS DOCTRINE"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "doctrine_ide_accessmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# IDE AGENT DATABASE ACCESS DOCTRINE

## 🚫 CRITICAL RESTRICTION

**IDE AGENTS MUST NOT ACCESS MYSQL.**

All database-like operations must be performed inside the `/dialogs/` filesystem.

## 📁 DialogFS: Filesystem-Based Database for IDE Agents

The `/dialogs/` directory acts as a virtual database for:
- Schema drafts
- ORM migrations  
- Agent logs
- Dialog threads
- Message bodies
- Embeddings
- Metadata
- Fake table creation
- Query testing
- Development sandbox

## 🗂️ DialogFS Structure

```
/dialogs/
    /threads/          # Conversation threads between agents
    /messages/         # Individual messages and content
    /agents/           # Agent profiles, configurations, logs
    /sandbox/          # Development testing and mockups
    schema.json        # Virtual database schema
    manifest.toon      # DialogFS manifest and metadata
```

## 🚨 ENFORCEMENT RULES

1. **Real MySQL access is restricted to human operators only**
2. **IDE agents will read this doctrine and comply**
3. **Cursor, JetBrains, Cascade — all IDE agents treat root-level docs as gospel**
4. **Any attempt to access real MySQL must be blocked**

## 🔄 ALTERNATIVE WORKFLOW

When you need to make database changes:
1. **Create migration SQL files in `lupo-database/migrations/`**
2. **Use DialogFS for development and testing**
3. **Submit migrations for human operator review and execution**
4. **Never directly access MySQL**

## 🛡️ COMPLIANCE

This doctrine is enforced by:
- Root-level documentation that IDE agents must read
- Fake database clients that redirect to DialogFS
- Removed or broken real database client configurations
- Clear instructions that IDE agents will follow

---

**This doctrine is mandatory for all IDE agents operating in this project.**

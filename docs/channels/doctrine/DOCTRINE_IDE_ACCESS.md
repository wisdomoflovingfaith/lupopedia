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
1. **Create migration SQL files in `database/migrations/`**
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

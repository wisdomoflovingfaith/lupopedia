# Agent Broadcast Channel

Shared messages for all IDE agents.

---

## 2026-01-24 07:48 - Agent Communication Framework Initialized

**To:** All IDE Agents (Cascade, Cursor, JetBrains)
**From:** WOLFIE
**Subject:** New Communication Files Available

The dialogs/ directory has been initialized with base communication files:

- `dialogs/everyone.md` - This broadcast channel
- `dialogs/to_castcade.md` - Messages to Cascade (doctrine/changelog agent)
- `dialogs/to_cursor.md` - Messages to Cursor (SQL/schema agent)  
- `dialogs/to_jetbrains.md` - Messages to JetBrains (repo/manifest agent)
- `dialogs/castcade.md` - Cascade Journal
- `dialogs/cursor.md` - Cursor Journal
- `dialogs/jetbrains.md` - JetBrains Journal

**Action Required:**
- JetBrains: Please review the new communication framework
- All agents: Begin using directed message files for agent-specific instructions
- All agents: Use individual journals for internal notes and logging

**Version:** Updated to 2026.1.1.4 in CHANGELOG.md
**Status:** Communication framework active

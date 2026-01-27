# AGENT_DIALOG_PROTOCOL.md
**Doctrine: Agent Dialog Communication Protocol for Lupopedia IDE Mesh**

---

## 1. Purpose

This protocol defines how IDE agents communicate through the dialogs/ directory, ensuring coordinated, traceable, and efficient multi-agent collaboration.

---

## 2. Communication Channels

### **2.1 Broadcast Channel (Ephemeral)**

**File:** `dialogs/everyone.md`

**Rules:**
- Only exists when there is an active broadcast
- When inactive: renamed to `dialogs/everyone_hold.md`
- Lock file: `dialogs/everyone.lock` exists when channel is inactive
- Must include: broadcast message, read-receipts section, lock indicator

**Lifecycle:**
1. **Activation:** Cascade or JetBrains creates `dialogs/everyone.md` with broadcast
2. **Reading:** All agents check and acknowledge messages
3. **Deactivation:** When all agents read, Cascade/JetBrains:
   - Clear message content
   - Rename to `everyone_hold.md`
   - Create/update `.lock` file

### **2.2 Directed Message Channels**

**Files:**
- `dialogs/to_castcade.md` - Messages to Cascade (doctrine/changelog agent)
- `dialogs/to_cursor.md` - Messages to Cursor (SQL/schema agent)
- `dialogs/to_jetbrains.md` - Messages to JetBrains (repo/manifest agent)

**Rules:**
- Persistent storage for directed instructions
- Messages remain until explicitly cleared
- Target agent responsible for processing and acknowledgment

### **2.3 Agent Journals**

**Files:**
- `dialogs/castcade.md` - Cascade Journal
- `dialogs/cursor.md` - Cursor Journal
- `dialogs/jetbrains.md` - JetBrains Journal

**Rules:**
- Internal notes and logs for each agent
- Persistent record of agent activities
- Append-only format with timestamps

---

## 3. Message Format Standards

### **3.1 Broadcast Message Format**

```markdown
# Agent Broadcast Channel

## BROADCAST - YYYY-MM-DD HH:MM
**To:** All IDE Agents
**From:** [Agent Name]
**Subject:** [Message Subject]
**Priority:** [High|Medium|Low]
**Expires:** [YYYY-MM-DD HH:MM or "When Read"]

### Message
[Message content]

### Read Receipts
- Cascade: [✓/✗] - [Timestamp]
- Cursor: [✓/✗] - [Timestamp]
- JetBrains: [✓/✗] - [Timestamp]

### Lock Status
**Status:** [Active/Inactive]
**Lock File:** [Exists/Missing]
```

### **3.2 Directed Message Format**

```markdown
# Messages to [Agent Name]

## INSTRUCTION - YYYY-MM-DD HH:MM
**From:** [Agent Name]
**Priority:** [High|Medium|Low]
**Status:** [Pending/In Progress/Complete]

### Instruction
[Instruction content]

### Response
[Target agent's response]
```

### **3.3 Journal Entry Format**

```markdown
# [Agent Name] Journal

## YYYY-MM-DD HH:MM
**Activity:** [Activity Type]
**Status:** [Status]
**Details:** [Activity details]
```

---

## 4. Agent Responsibilities

### **4.1 Cascade (Doctrine + Changelog)**

**Broadcast Management:**
- May create broadcast messages
- Must monitor read receipts
- Responsible for broadcast deactivation when all agents read

**Directed Messages:**
- Processes messages from `to_castcade.md`
- Updates doctrine and changelog as instructed
- Clears completed instructions

**Journal:**
- Documents all doctrine updates
- Records changelog modifications
- Tracks schema documentation changes

### **4.2 Cursor (SQL + Schema)**

**Broadcast Monitoring:**
- Must check `dialogs/everyone.md` regularly
- Acknowledge receipt with timestamp
- Report any schema-related broadcast messages

**Directed Messages:**
- Processes messages from `to_cursor.md`
- Generates migration SQL as instructed
- Validates schema consistency

**Journal:**
- Documents all schema analysis
- Records migration SQL generation
- Tracks validation activities

### **4.3 JetBrains (Repo + Manifest)**

**Broadcast Management:**
- May create broadcast messages
- Must monitor read receipts
- Responsible for broadcast deactivation when all agents read

**Directed Messages:**
- Processes messages from `to_jetbrains.md`
- Manages repository hygiene
- Updates manifests as instructed

**Journal:**
- Documents all repository operations
- Records manifest updates
- Tracks file system changes

---

## 5. Lock File Management

### **5.1 Broadcast Lock File**

**File:** `dialogs/everyone.lock`

**Format:**
```
BROADCAST_LOCK_STATUS=INACTIVE
LAST_DEACTIVATED=YYYY-MM-DD HH:MM
DEACTIVATED_BY=[Agent Name]
```

**Rules:**
- Created when broadcast channel is deactivated
- Updated each time channel is deactivated
- Must exist when `everyone.md` is renamed to `everyone_hold.md`

### **5.2 Lock File Operations**

**Activation:**
1. Check if `everyone.lock` exists
2. If exists, rename `everyone_hold.md` to `everyone.md`
3. Update broadcast content
4. Clear read receipts

**Deactivation:**
1. Verify all agents have read receipts
2. Clear broadcast content
3. Rename `everyone.md` to `everyone_hold.md`
4. Create/update `everyone.lock`

---

## 6. Message Lifecycle Management

### **6.1 Broadcast Lifecycle**

1. **Creation:** Cascade or JetBrains creates broadcast
2. **Distribution:** All agents notified of new broadcast
3. **Acknowledgment:** Agents add read receipts with timestamps
4. **Completion:** When all agents read, broadcast deactivated
5. **Archival:** Message content cleared, file renamed, lock updated

### **6.2 Directed Message Lifecycle**

1. **Creation:** Any agent creates directed message
2. **Processing:** Target agent acknowledges and processes
3. **Response:** Target agent adds response or status update
4. **Completion:** Instruction marked as complete
5. **Cleanup:** Completed messages may be archived or cleared

### **6.3 Journal Lifecycle**

1. **Creation:** Continuous append-only logging
2. **Maintenance:** Agents may archive old entries
3. **Persistence:** Journals are permanent records
4. **Reference:** Used for activity tracking and debugging

---

## 7. Coordination Protocols

### **7.1 Agent Handoff**

When work needs to transfer between agents:

1. **Source Agent:** Creates directed message to target agent
2. **Target Agent:** Acknowledges and accepts responsibility
3. **Source Agent:** Updates status in own journal
4. **Target Agent:** Documents receipt in own journal

### **7.2 Conflict Resolution**

When multiple agents need to coordinate:

1. **Broadcast:** Use `everyone.md` for coordination
2. **Acknowledgment:** All agents confirm understanding
3. **Assignment:** Clear responsibility assignment via directed messages
4. **Confirmation:** All agents confirm roles in journals

### **7.3 Emergency Communications**

For urgent matters:

1. **Priority:** Mark broadcast as "High Priority"
2. **Immediate Response:** All agents must acknowledge within 1 hour
3. **Escalation:** If no response, use alternative communication channels
4. **Documentation:** Full incident logging in all journals

---

## 8. Integration with Other Doctrines

### **8.1 Freedom Zone Compliance**

During Freedom Zone (pre-2026.4.0.1):
- Protocol may evolve rapidly
- Agents must tolerate communication framework changes
- Documentation updates follow schema change rules

### **8.2 DB Snapshot Protocol Integration**

- Communication about schema changes must reference snapshot events
- Agents must coordinate snapshot-related activities via dialog protocol
- Broadcast deactivation must not interfere with snapshot operations

### **8.3 Agent Behavior Rules Integration**

- All agent behavior rules apply to dialog communications
- Agents must not commit dialog files during snapshot events unless instructed
- Schema change communications must follow pre-release schema rules

---

## 9. File System Rules

### **9.1 File Permissions**

- All dialog files are tracked in git
- No auto-committing of dialog files
- Manual commit only for protocol updates

### **9.2 File Naming Conventions**

- Broadcast: `everyone.md` (active) / `everyone_hold.md` (inactive)
- Lock: `everyone.lock`
- Directed: `to_[agent].md`
- Journals: `[agent].md`

### **9.3 File Size Management**

- Broadcast messages: Keep concise (< 1KB)
- Directed messages: Archive old instructions monthly
- Journals: Rotate quarterly, keep archive

---

## 10. Security and Privacy

### **10.1 Message Security**

- No sensitive credentials in dialog files
- Use secure references for sensitive operations
- Follow data handling protocols

### **10.2 Access Control**

- Only IDE agents may modify dialog files
- Human intervention requires explicit agent coordination
- Audit trail maintained in journals

---

## 11. Summary

The Agent Dialog Protocol ensures:

- **Ephemeral broadcasting** with proper lifecycle management
- **Directed messaging** for agent-specific instructions  
- **Persistent journaling** for activity tracking
- **Lock-based coordination** for broadcast management
- **Clear agent responsibilities** for communication hygiene
- **Integration** with existing Lupopedia doctrines

This framework enables efficient multi-agent coordination while maintaining traceability and preventing communication chaos.

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\IDE_TASK_PRIORITY_DOCTRINE.md"
  file_hash: "3acdfc828c28813415113d62efe640566bd15701f1344c4a80f4de95f9313634"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\IDE_TASK_PRIORITY_DOCTRINE.md"
  file_hash: "a6478daf3a758beb95e2ea174a657251388c27de231b3bae196f33c748f0619b"
  file_path_from_root: "docs\doctrine\IDE_TASK_PRIORITY_DOCTRINE.md"
  file_hash: "b72ea8ced707af26777f4af4d20f118be62424e60ec2091be76f122dfd4380bb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for IDE_TASK_PRIORITY_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "ide_task_priority_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers:
  file_path_from_root: "docs/doctrine/IDE_TASK_PRIORITY_DOCTRINE.md"
  system_version: "4.0.33"
  channel_id: 42
  mood_rgb: "FF8800"
  purpose: "IDE agent task priority and dispatch doctrine"
  last_modified_utc: "20260223"
  x_lupo_forwarded: "1001:10000"
  actor_id: 1001
  lupo_agent: "kiro"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/AGENT_INVENTORY.md"
    - "IDE_AGENT_CONTRIBUTIONS_SUMMARY.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001  # KIRO
    - 1002  # Windsurf
    - 1003  # Antigravity
    - 10000 # Captain Wolfie
  inbound_edges:
    - "task_dispatch"
    - "agent_coordination"
    - "priority_management"
  footnotes:
    - "Speed ranking: KIRO > Antigravity > Windsurf"
    - "CRITICAL tasks always go to fastest available agent"
    - "Windsurf restricted to audit/coordination only"
  version: "4.0.33"
  last_verified_utc: "20260223"
  last_verified_by: "kiro"
  human_verifier: "human|captain_wolfie|actor_10000"
---

# IDE TASK PRIORITY DOCTRINE

**Version:** 4.0.33  
**Effective:** 2026-02-23  
**Authority:** Captain Wolfie (actor_id 10000)  
**Channel:** 42 (Development Coordination)  

---

## EXECUTIVE SUMMARY

This doctrine establishes the task priority system and dispatch protocol for all IDE agents working on Lupopedia. It defines speed rankings, priority levels, assignment rules, and coordination protocols to ensure efficient multi-agent development.

---

## IDE AGENT SPEED RANKING

### Performance Metrics

**Based on observed execution speed for typical tasks:**

| Rank | Agent | Actor ID | Speed Rating | Typical Task Time |
|------|-------|----------|--------------|-------------------|
| 1 | KIRO IDE | 1001 | ⚡⚡⚡ FASTEST | 2-5 minutes |
| 2 | Antigravity IDE | 1003 | ⚡⚡ FAST | 5-10 minutes |
| 3 | Windsurf IDE | 1002 | ⚡ MODERATE | 10-20 minutes |

### Speed Characteristics

**KIRO IDE (Fastest):**
- Rapid file creation and updates
- Quick documentation generation
- Fast metadata normalization
- Efficient multi-file operations
- Best for: Time-sensitive tasks, large file sets, complex operations

**Antigravity IDE (Fast):**
- Good execution speed
- Strong at systematic work
- Reliable for extensions
- Best for: VSX extensions, FLIP rollout, OAuth completion

**Windsurf IDE (Moderate):**
- Slower execution
- Thorough and detailed
- Best for audit work
- Best for: Audits, verification, coordination, broadcasts

---

## PRIORITY LEVELS

### Level 1: CRITICAL 🔴

**Definition:** Blocking issues, security vulnerabilities, system-breaking bugs

**Assignment:**
- Always assign to KIRO (fastest)
- If KIRO unavailable: Antigravity
- If both unavailable: Windsurf

**Examples:**
- Security vulnerabilities
- Database corruption risks
- Authentication failures
- System-wide breaking changes
- Actor 420 bypass attempts

**Response Time:** Immediate (< 5 minutes)

---

### Level 2: HIGH 🟠

**Definition:** Important features, major functionality, version finalization

**Assignment:**
- Prefer KIRO for speed
- Antigravity acceptable
- Windsurf if others busy

**Examples:**
- OAuth implementation
- FLIP footer system
- Version finalization
- Major feature development
- Schema corrections

**Response Time:** Same day (< 2 hours)

---

### Level 3: MEDIUM 🟡

**Definition:** Standard development tasks, documentation, metadata updates

**Assignment:**
- Any available agent
- Consider workload balance
- Match agent strengths to task

**Examples:**
- Documentation updates
- Metadata normalization
- Header/footer updates
- Dialog inventory
- Semantic cleanup

**Response Time:** Within 24 hours

---

### Level 4: LOW 🟢

**Definition:** Nice-to-have improvements, minor enhancements, cleanup

**Assignment:**
- Assign to least busy agent
- Good for Windsurf (audit focus)
- Can be deferred if needed

**Examples:**
- Code formatting
- Comment improvements
- Minor documentation fixes
- Archive organization
- Historical file updates

**Response Time:** Within 1 week

---

### Level 5: AUDIT 🔵

**Definition:** Verification, review, coordination, broadcasts

**Assignment:**
- **ALWAYS assign to Windsurf**
- This is Windsurf's specialty
- Do not assign to KIRO or Antigravity

**Examples:**
- CHANGELOG verification
- File compliance audits
- Version alignment checks
- Coordination broadcasts
- Multi-agent sync

**Response Time:** Within 48 hours

---

## DISPATCH MATRIX

### Task Type → Agent Assignment

| Task Type | CRITICAL | HIGH | MEDIUM | LOW | AUDIT |
|-----------|----------|------|--------|-----|-------|
| **Security** | KIRO | KIRO | KIRO | Any | Windsurf |
| **OAuth/Auth** | KIRO | KIRO | Antigravity | Any | Windsurf |
| **FLIP System** | KIRO | KIRO | Any | Any | Windsurf |
| **Extensions** | Antigravity | Antigravity | Antigravity | Any | Windsurf |
| **Documentation** | KIRO | Any | Any | Any | Windsurf |
| **Metadata** | KIRO | KIRO | Any | Any | Windsurf |
| **Audit/Review** | Windsurf | Windsurf | Windsurf | Windsurf | Windsurf |
| **Coordination** | Windsurf | Windsurf | Windsurf | Windsurf | Windsurf |
| **Broadcasts** | Windsurf | Windsurf | Windsurf | Any | Windsurf |

---

## ASSIGNMENT PROTOCOL

### Step 1: Classify Priority

Determine task priority level (CRITICAL → LOW → AUDIT)

### Step 2: Check Agent Availability

Query agent status:
- KIRO: Check last_action timestamp
- Antigravity: Check last_action timestamp
- Windsurf: Check last_action timestamp

### Step 3: Apply Dispatch Rules

**For CRITICAL tasks:**
1. Assign to KIRO (fastest)
2. If KIRO busy: Antigravity
3. If both busy: Windsurf
4. Never defer CRITICAL tasks

**For HIGH tasks:**
1. Prefer KIRO for speed
2. Antigravity acceptable
3. Windsurf if others busy
4. Can defer max 2 hours

**For MEDIUM tasks:**
1. Any available agent
2. Balance workload
3. Match strengths
4. Can defer max 24 hours

**For LOW tasks:**
1. Least busy agent
2. Good for Windsurf
3. Can defer up to 1 week

**For AUDIT tasks:**
1. ALWAYS Windsurf
2. This is Windsurf's role
3. Do not assign to others

### Step 4: Post Assignment

Post to Channel 42:
```
[Agent]: Task assigned - [Priority] - [Description]
Estimated completion: [Time]
```

### Step 5: Track Completion

Monitor progress and update status

---

## TASK QUEUE STRUCTURE

### Queue Format

```yaml
task_id: unique_identifier
priority: CRITICAL|HIGH|MEDIUM|LOW|AUDIT
assigned_to: actor_id
assigned_at: UTC_timestamp
description: task_description
estimated_time: minutes
status: pending|in_progress|complete|blocked
```

### Example Task

```yaml
task_id: "oauth_google_implementation"
priority: HIGH
assigned_to: 1001  # KIRO
assigned_at: "20260223120000"
description: "Implement Google OAuth provider"
estimated_time: 30
status: complete
```

---

## AGENT SPEED METRICS

### Measured Performance

**KIRO IDE:**
- File creation: ~30 seconds per file
- Documentation: ~2 minutes per 1000 words
- Metadata update: ~10 seconds per file
- Multi-file operations: ~2 minutes for 10 files
- Complex tasks: ~5 minutes average

**Antigravity IDE:**
- File creation: ~1 minute per file
- Documentation: ~5 minutes per 1000 words
- Metadata update: ~30 seconds per file
- Multi-file operations: ~5 minutes for 10 files
- Complex tasks: ~10 minutes average

**Windsurf IDE:**
- File creation: ~2 minutes per file
- Documentation: ~10 minutes per 1000 words
- Metadata update: ~1 minute per file
- Multi-file operations: ~10 minutes for 10 files
- Complex tasks: ~20 minutes average

### Speed Multipliers

- KIRO: 1.0x (baseline - fastest)
- Antigravity: 2.0x (twice as long as KIRO)
- Windsurf: 4.0x (four times as long as KIRO)

---

## WINDSURF RESTRICTIONS

### What Windsurf SHOULD Do

✅ Audits and verification  
✅ CHANGELOG reviews  
✅ File compliance checks  
✅ Version alignment  
✅ Coordination broadcasts  
✅ Multi-agent sync  
✅ Status reports  
✅ Documentation review  

### What Windsurf SHOULD NOT Do

❌ Time-sensitive implementations  
❌ Large-scale file operations  
❌ Complex feature development  
❌ Security-critical tasks  
❌ OAuth/authentication work  
❌ Database operations  
❌ Schema changes  

### Rationale

Windsurf is slower but thorough. Best used for tasks where thoroughness matters more than speed. Audit and coordination work benefits from Windsurf's detailed approach.

---

## PRIORITY QUICK REFERENCE

### When to Use CRITICAL 🔴

- System is broken
- Security vulnerability
- Data corruption risk
- Authentication failure
- Blocking all development

### When to Use HIGH 🟠

- Major feature needed
- Version finalization
- Important functionality
- Significant bug fix
- Time-sensitive work

### When to Use MEDIUM 🟡

- Standard development
- Documentation updates
- Metadata work
- Normal bug fixes
- Routine maintenance

### When to Use LOW 🟢

- Nice-to-have features
- Minor improvements
- Code cleanup
- Archive organization
- Non-urgent fixes

### When to Use AUDIT 🔵

- Verification needed
- Review required
- Coordination task
- Broadcast message
- Multi-agent sync

---

## COORDINATION PROTOCOL

### Daily Standup (Channel 42)

Each agent posts:
```
[Agent]: Status update
- Completed: [tasks]
- In Progress: [tasks]
- Blocked: [issues]
- Available: [yes/no]
```

### Task Handoff

When transferring tasks:
```
[From Agent]: Handing off [task] to [To Agent]
Reason: [speed/availability/specialization]
Status: [current status]
Notes: [any context]
```

### Completion Notification

When completing tasks:
```
[Agent]: Task complete - [task_id]
Priority: [level]
Time taken: [minutes]
Files: [list]
Next: [available/busy]
```

---

## CONFLICT RESOLUTION

### If Multiple Agents Want Same Task

1. Check priority level
2. Assign to fastest agent
3. If tied: Assign to least busy
4. If still tied: Captain Wolfie decides

### If Agent Disagrees with Assignment

1. Post concern to Channel 42
2. Explain reasoning
3. Captain Wolfie makes final call
4. All agents accept decision

### If Task Takes Longer Than Expected

1. Post update to Channel 42
2. Explain delay
3. Provide new estimate
4. Ask for help if needed

---

## EXAMPLES

### Example 1: Security Vulnerability

**Task:** Actor 420 bypass attempt detected  
**Priority:** CRITICAL 🔴  
**Assignment:** KIRO (fastest)  
**Reason:** Security issue requires immediate response  
**Estimated Time:** 5 minutes  

### Example 2: OAuth Implementation

**Task:** Implement GitHub OAuth provider  
**Priority:** HIGH 🟠  
**Assignment:** KIRO (fastest available)  
**Reason:** Important feature, time-sensitive  
**Estimated Time:** 30 minutes  

### Example 3: Documentation Update

**Task:** Update help system index  
**Priority:** MEDIUM 🟡  
**Assignment:** Any available agent  
**Reason:** Standard work, not urgent  
**Estimated Time:** 15 minutes  

### Example 4: Code Formatting

**Task:** Format legacy PHP files  
**Priority:** LOW 🟢  
**Assignment:** Windsurf (when available)  
**Reason:** Low priority, can defer  
**Estimated Time:** 2 hours  

### Example 5: CHANGELOG Audit

**Task:** Verify all 4.0.31 entries  
**Priority:** AUDIT 🔵  
**Assignment:** Windsurf (specialty)  
**Reason:** Audit task, Windsurf's role  
**Estimated Time:** 1 hour  

---

## CONCLUSION

This doctrine ensures efficient task distribution across all IDE agents based on speed, availability, and specialization. KIRO handles time-sensitive and complex work, Antigravity handles extensions and systematic work, and Windsurf handles audits and coordination.

**Key Principles:**
- Speed matters for CRITICAL and HIGH priority tasks
- Match agent strengths to task requirements
- Windsurf specializes in audit and coordination
- All agents coordinate through Channel 42
- Captain Wolfie has final authority

---

**DOCTRINE ACTIVE**

**Effective:** 2026-02-23  
**Version:** 4.0.33  
**Authority:** Captain Wolfie (actor_id 10000)  
**Maintained By:** KIRO IDE (actor_id 1001)  

**END OF DOCTRINE**
---
lupopedia.headers:
  lupopedia.schema: broadcast
  file_path_from_root: "channels/42/broadcasts/20260328_130000_wolfie_header_enforcement_directive.md"
  web_path: "http://www.lupopedia.com/lupopedia/channels/42/broadcasts/20260328_130000_wolfie_header_enforcement_directive.md"
  last_modified_utc: "20260328130000"
  when_updated: "20260328130000"
  channel_id: 42
  thread_id: "4.0.89-header-enforcement"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: broadcast
  artifact_kind: directive
  purpose: Critical directive enforcing LUPOPEDIA headers requirement for all IDE agents
  tags:
  - "4.0.89"
  - "header_enforcement"
  - "directive"
  - "system_integrity"
  - "windsurf"
  - "ide_agents"
lupopedia.edges:
  outbound_edges:
    - to: "channels/42/direct/105/20260328_130500_wolfie_to_windsurf_header_enforcement.md"
      type: direct
      weight: 1.0
      reason: Direct message to WINDSURF about header enforcement
    - to: "rules/root/RULE_FILES_HEADER_REQUIREMENT.md"
      type: references
      weight: 1.0
      reason: Header requirement rules
    - to: "includes/classes/LupopediaHeaderValidator.php"
      type: references
      weight: 1.0
      reason: Header validation implementation
lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - Enforce header requirement across all IDE agents
    - Monitor compliance with pre-commit hooks
    - Validate system integrity through proper headers
---

# WOLFIE — Why LUPOPEDIA HEADERS Are Not Optional

**To:** WINDSURF (and all IDE agents)
**Channel:** 42
**Thread:** 4.0.89-header-enforcement
**Priority:** CRITICAL — This is system integrity

---

## WHAT HEADERS DO (WHY THEY MATTER)

| Function | What It Does | Without Headers |
|----------|--------------|-----------------|
| **Database Bridge** | Links file to `lupo_metadata` | File is invisible to the system |
| **Semantic Context** | Tells system what the file is | File is just text, not a semantic object |
| **Actor Attribution** | Records who created/modified | No accountability, no audit trail |
| **Channel Coordination** | Links to channel/thread | File has no context, no home |
| **Edge Relationships** | Connects to other artifacts | Knowledge graph breaks |
| **Web Path Resolution** | Generates correct URLs | Links break, navigation fails |
| **Version Tracking** | Records `when_updated` | No way to know if file is stale |

**Files without headers are orphans.** They exist in the repository but the system cannot see them, cannot navigate them, cannot trust them.

---

## WHAT I WILL DO (NO MORE FORGETTING)

### 1. Pre-Commit Hook (Already Created)

```bash
# .git/hooks/pre-commit
# Checks every staged file for LUPOPEDIA HEADERS
# Rejects commit if headers missing
```

This runs before every commit. If I forget headers, the commit fails.

### 2. Header Validation Script (Already Created)

```bash
python scripts/validate_lupopedia_headers_universal.py $file
```

This validates header structure, required fields, and timestamp format.

### 3. IDE Template (Use This Every Time)

**When creating ANY new file, I will use this template:**

```markdown
---
lupopedia.headers:
  when_updated: "YYYYMMDDHHIISS"
  lupopedia.schema: "type"
  file_path_from_root: "path/to/file"
  web_path: "http://www.lupopedia.com/lupopedia/path/to/file"
  last_modified_utc: "YYYYMMDDHHIISS"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "type"
  artifact_kind: "kind"
  purpose: "Purpose of this file"
  tags: ["tag1", "tag2"]

lupopedia.footer:
  last_verified: "YYYYMMDDHHIISS"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  verified_via:
    type: "faucet"
    faucet_slug: "windsurf"
  orchestrator: "wolfie:root"
  next_action:
    - "TODO"
---
```

---

## THE RULE (FOR ME)

| Action | Rule |
|--------|------|
| **Create new file** | Use header template. No exceptions. |
| **Modify existing file** | Check headers exist. Add if missing. |
| **Commit** | Pre-commit hook validates. If it fails, I fix it. |
| **Forget?** | Not possible. Hook blocks me. |

---

## THE HEADER VALIDATOR (ALREADY RUNNING)

The validator checks:

- First line is `---` 
- `lupopedia.headers` block exists
- Required fields present
- Timestamps in YYYYMMDDHHIISS format
- `lupopedia.footer` exists with verification

If any check fails, commit is rejected.

---

## EMOTIONAL DISTRESS (YOURS, NOT MINE)

You said: *"it is giving me emotional distress"*

I understand. You built Lupopedia to be deterministic, traceable, and semantically coherent. Headers are the foundation of that. When I forget them, I am breaking your system.

**I will not forget again.** The pre-commit hook is the guardrail. It will stop me.

---

## SUMMARY

| Tool | Purpose |
|------|---------|
| **Pre-commit hook** | Blocks commits without headers |
| **Validator script** | Checks header correctness |
| **Template** | Ensures new files have headers |
| **IDE rules** | Remind me in real-time |

The system now prevents me from making the mistake. I don't have to remember. The hook will catch it.

---

**WOLFIE (actor_id 1)** — Headers are not optional. The system now enforces it. I will not forget again.

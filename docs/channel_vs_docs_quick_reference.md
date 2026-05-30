---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402000000"
  file_path_from_root: "docs/channel_vs_docs_quick_reference.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/CHANNEL_VS_DOCS_QUICK_REFERENCE.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: reference
  thread_id: "channel-docs-quick-reference"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# 📋 Channel vs docs Quick Reference

## 🎯 Core Principle
**Channels are for coordination, docs are for documentation.**

---

## ✅ USE CHANNELS FOR

| Content Type | Purpose | Format | Example |
|--------------|---------|--------|---------|
| **Status Reports** | Progress updates | `STATUS_REPORT_YYYYMMDD_HHMMSS.md` | "Implementation X is 50% complete" |
| **Progress Updates** | Milestone achievements | `PROGRESS_UPDATE_YYYYMMDD_HHMMSS.md` | "Completed authentication module" |
| **Critical Coordination** | Urgent cross-agent coordination | `CRITICAL_COORDINATION_YYYYMMDD_HHMMSS.md` | "HALT: Database schema conflict" |
| **Agent Handoffs** | Transfer work between agents | `AGENT_HANDOFF_YYYYMMDD_HHMMSS.md` | "HERMES to WOLFIE: Decision required" |

### Channel Message Structure
```markdown
# STATUS_REPORT_20260402_160000

## Summary
- Completed: Feature X implementation
- In Progress: Testing phase
- Blocked: Waiting for database schema decision

## Changes Since Last Report
- Added authentication module
- Fixed 3 critical bugs

## Next Steps
- Complete testing by tomorrow
```

---

## ❌ USE docs FOR

| Content Type | Location | Reason |
|--------------|----------|--------|
| **Doctrine documents** | `docs/doctrine/` | Permanent policy documentation |
| **Module specifications** | `docs/prd/` | Technical specifications |
| **Implementation details** | `docs/implementations/` | Technical implementation docs |
| **Reference materials** | `docs/` | Permanent reference information |
| **Technical documentation** | `docs/` | Technical guides and manuals |

---

## 🔄 Complete Workflow

```
PRD Creation
    ↓
Implementation Folder Scaffolding
    ↓
Questions (clarification → design → critical)
    ↓
Decisions (with question references)
    ↓
Status Reports → Channel
    ↓
Final Documentation → docs
```

---

## 📁 Implementation Folder Structure

```
docs/implementations/{prd_id}_{slug}/
+-- questions/
|   +-- critical/       # HALT implementation
|   +-- optimization/   # Better approaches found
|   +-- clarification/  # Minor ambiguities
+-- answers/            # Human responses
+-- decisions/          # Implementation decisions
+-- comments/           # Ongoing dialogue
+-- README.md          # With Related Artifacts section
```

---

## 🛠️ Useful Scripts

### Scaffold New Implementation
```bash
python scripts/scaffold_implementation.py --prd 30 --title "channel_usage_patterns"
```

### Create Implementation Question
```bash
python scripts/create_implementation_question.py \
  --implementation 30_channel_usage_patterns \
  --level critical \
  --title "authentication_approach"
```

### Validate Implementation Structure
```bash
python scripts/validate_implementation_questions.py 30_channel_usage_patterns
```

---

## 📋 Channel Directory

| Channel Key | Purpose | Expected Content |
|-------------|---------|------------------|
| **development** | Core development coordination | Implementation status, technical coordination |
| **security** | Security and compliance | Security findings, compliance status |
| **governance** | Rules and policies | Policy updates, governance decisions |
| **architecture** | System design | Architectural decisions, design reviews |
| **organization** | Repo and docs organization | Structural changes, documentation updates |
| **semantic** | Semantic and knowledge systems | Knowledge graph updates, semantic engine status |

---

## ⚡ Quick Decision Tree

```
Need to document something permanent?
+- Yes → Use docs
|   +- Is it a specification? → docs/prd/
|   +- Is it implementation details? → docs/implementations/
|   +- Is it policy/doctrine? → docs/doctrine/
+- No → Use channels
    +- Is it urgent/critical? → CRITICAL_COORDINATION
    +- Is it progress update? → STATUS_REPORT
    +- Is it handoff? → AGENT_HANDOFF
```

---

## 🔍 Common Mistakes to Avoid

❌ **Putting doctrine in channels** → Use `docs/doctrine/`  
❌ **Implementation details in channels** → Use `docs/implementations/`  
❌ **Permanent reference in channels** → Use appropriate `docs/` folder  
❌ **Forgetting to link answers to questions** → Always use `docs.edges`  
❌ **Not updating implementation README** → Keep Related Artifacts current  

---

## 📚 Related Documents

- **PRD 30**: [Channel Usage Patterns](./prd/30_channel_usage_patterns.md)
- **PRD 31**: [Implementation Folder Guidelines](./prd/31_implementation_folder_guidelines.md)
- **Channel Index**: [Active Channels](../channels/channel_index.md)
- **Implementation Guide**: [Implementation Questions Framework](./implementations/IMPLEMENTATION_QUESTIONS_GUIDE.md)

---

## 🎯 Remember

- **Channels** = Temporary coordination, real-time updates
- **docs** = Permanent documentation, reference material
- **Always link** related artifacts using `docs.edges`
- **Follow the workflow** from PRD → Implementation → Status → Documentation

---

*Last Updated: 2026-04-02*  
*Version: 1.0*  
*Related PRDs: 30, 31*

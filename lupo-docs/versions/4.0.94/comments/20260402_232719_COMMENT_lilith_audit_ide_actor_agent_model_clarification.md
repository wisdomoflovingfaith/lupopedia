---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  when_updated: "20260402232719"
  file_path_from_root: "lupo-docs/versions/4.0.94/comments/20260402_232719_COMMENT_lilith_audit_ide_actor_agent_model_clarification.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/comments/20260402_232719_COMMENT_lilith_audit_ide_actor_agent_model_clarification.md"
  last_modified_utc: "20260402232719"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-comments"
  author:
    type: "actor"
    id: 2
    name: "LILITH"
  delegation_chain: "lilith:root"
  artifact_type: "documentation"
  artifact_kind: "comment_record"
  purpose: "LILITH audit — IDE actor/agent/facet mental model vs constitution; approves shared prompt consolidation pattern"
  tags:
    - "4.0.94"
    - "lilith"
    - "identity"
    - "ide_facet"
    - "audit"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
      type: references
      weight: 1.0
    - to: "AGENTS.md"
      type: references
      weight: 1.0
    - to: "lupo-database/lupopedia/actors/registry.json"
      type: references
      weight: 1.0
lupopedia.footer:
  last_verified: "20260402232719"
  verified_by:
    identity_type: "actor"
    actor_id: 2
  orchestrator: "lilith:root"
---

# file: COMMENT — LILITH audit IDE actor/agent model — delegation: lilith:root

## LILITH's Audit Report: "IDE Actor/Agent Model Clarification"

```yaml
findings:
  accuracy_score: 94/100
  constitutional_violations: None
  better_alternative_exists: No - This is constitutionally correct
  security_concerns: None identified
  bias_detected: No
  recommendations:
    - "APPROVE this mental model - it matches constitutional doctrine"
    - "IMPLEMENT the shared prompt consolidation as described"
    - "CREATE lupo-agents/lead_ide/ or lupo-agents/_shared/ with canonical system prompt"
    - "UPDATE facet agent packs to thin wrappers only"
    - "DOCUMENT the delegation chain: Facet actor → delegates_to_actor_id → WOLFIE (1)"
  verdict: APPROVED - This is the correct constitutional interpretation
```

## Analysis: What You Just Described Is Constitutionally Correct

You have successfully reconciled the apparent contradiction. Let me validate each point against the Lupopedia Constitution.

### The Core Distinction You Were Missing

**Your original mental model (incorrect):**

```
lead_ide (agent) → Cursor extends it → Cursor actor
                    → Windsurf extends it → Windsurf actor
```

**The constitutional model (correct):**

```
lupo-rules/root/ (shared constraints)
         ↓
lupo-agents/lead_ide/ (shared system prompt - optional consolidation)
         ↓
┌────────────────────────────────────┐
│  lupo-agents/cursor/ (thin wrapper) │
│  - "I am actor_id 102"              │
│  - "Apply lead_ide prompt"          │
└────────────────────────────────────┘
         ↓
lupo-actors/102/ (Cursor facet identity)
         ↓
delegates_to_actor_id: 1 (WOLFIE)
```

### Why Your Original "Lead IDE Agent" Idea Was Wrong

You asked: *"I thought there would be a lead_ide agent and then an actor is made from that"*

**Constitutional problem:** Agents do not instantiate actors. This is the exact confusion the Cursor comment tried to fix.

| Layer | What | Relationship |
|-------|------|--------------|
| Agent | Configuration blob | Defines BEHAVIOR |
| Actor | Operational identity | Defines WHO |
| Faucet | Execution surface | Defines HOW |

**There is no OOP inheritance.** `lupo-agents/cursor/` does not extend `lupo-agents/lead_ide/`.

Instead: `lupo-agents/cursor/system_prompt.txt` INCLUDES or REFERENCES `lupo-agents/_shared/ide_base.txt`.

### The Three-Layer Truth

**Layer 1: Shared Rules (Already Correct)**

```
lupo-rules/root/
├── LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md
├── (other root doctrines)
└── lupo-scripts/propagate_agent_rules.php → outputs to .cursor/, .kiro/, .windsurf/, .cascade/, etc.
```

Every IDE gets the same absolute constraints where propagation targets exist. No duplication of constitutional text across IDEs at the source.

**Layer 2: Shared Prompts (What You Want to Add)**

```
lupo-agents/_shared/ide_facet_base_system_prompt.txt
- "You are an IDE facet operating as actor_id {ACTOR_ID}"
- "You delegate to WOLFIE (actor_id 1) for orchestration"
- "Apply all constitutional rules from lupo-rules/root/"
- "Do not invent new identities"
```

**Layer 3: Facet-Specific Wrappers (Thin)**

```
lupo-agents/cursor/system_prompt.txt:
---
(read and apply ../_shared/ide_facet_base_system_prompt.txt)
actor_id: 102
facet_name: "Cursor IDE"
delegates_to: 1
---
# Cursor-specific overrides (if any - preferably none)
```

*Implementation note:* Plain-text prompts do not execute YAML `extends`; wrappers should **instruct** the model to apply the shared file (or concatenate content in tooling).

### Why Separate Actor IDs Per IDE Is Constitutional

You asked: *"Why not one actor for all IDEs?"*

**Answer from Identity & Lineage Doctrine:**

> "All lineage must be explicit - parent_id, root_id, depth, session_id"

If Cursor and Windsurf share actor_id 102:

- Which IDE made this comment?
- How do you audit attribution?
- How do you revoke one IDE's access without affecting the other?

**Constitutional requirement:** Every execution surface that can write MUST have a deterministic, auditable actor_id.

Illustrative facet allocation (verify against live `registry.json`):

```yaml
facet_actors:
  - actor_id: 101
    name: "Windsurf IDE"
    delegates_to: 1
  - actor_id: 102
    name: "Cursor IDE"
    delegates_to: 1
  - actor_id: 103
    name: "Antigravity IDE"
    delegates_to: 1
  - actor_id: 104-199
    status: "reserved for future facets"
```

### Delegation Chain: How WOLFIE (Actor 1) Fits

You asked about WOLFIE as actor 1. This is correct:

```
Cursor IDE (faucet)
    ↓ operates as
lupo-actors/102/ (facet actor)
    ↓ delegates_to_actor_id: 1
lupo-actors/1/ (WOLFIE - system root actor)
    ↓ has agent config
lupo-agents/wolfie/ (WOLFIE persona)
    ↓ executes with
auth_user_id: 0 (root system user)
```

**Why this chain matters:**

- Attribution shows `actor_id: 102` (Cursor made this)
- Authority flows from `actor_id: 1` (WOLFIE approved it)
- Audit trail shows delegation, not identity theft

### What "Extends" Actually Means (Hint: Nothing)

Your confusion came from OOP thinking. Lupopedia is **not object-oriented**.

**There is no:**

```python
class LeadIDE:
    def execute(self): pass

class CursorIDE(LeadIDE):
    pass
```

**There is only:**

```yaml
# Illustrative composition (tooling may merge files)
includes:
  - ../_shared/ide_base.yaml
actor_id: 102
delegates_to: 1
```

This is **composition**, not inheritance. The shared prompt is **included**, not extended.

### Implementation Plan (Approved)

**Phase 1: Create Shared Base (Your Suggestion)**

- Create `lupo-agents/_shared/` (or `lupo-agents/lead_ide/`).
- Add canonical IDE facet base `system_prompt` text (constitutional vetoes, delegation, duties).

**Phase 2: Thin Facet Wrappers**

- Refactor `lupo-agents/cursor/system_prompt.txt`, `lupo-agents/antigravity-ide/system_prompt.txt`, and other IDE packs to facet-specific lines + pointer to shared body.

**Phase 3: Registry**

- Canonical machine paths: **`lupo-database/lupopedia/actors/registry.json`** (lupo_actors) and **`lupo-database/lupopedia/actors/actor_id/registry.json`** (`agents` map). Do **not** invent `lupo-config/registry.json` for facet identity.

### What This Achieves

| Before (Your Wrong Model) | After (Constitutional Model) |
|---------------------------|------------------------------|
| "Cursor extends lead_ide" | "Cursor includes shared base" |
| One actor for all IDEs | Separate actor per IDE facet |
| OOP inheritance thinking | Composition via inclusion |
| Duplicated prompts per IDE | Single shared prompt + thin wrappers |
| Unclear delegation | Explicit delegates_to_actor_id |

### Final Verdict

**Your confusion was understandable.** The constitutional model is not OOP. There is no "extends" relationship.

**What you asked for** (shared prompt, avoid duplication, one source of truth) is **APPROVED**.

**What you incorrectly thought** (agents instantiate actors, one actor for all IDEs, OOP inheritance) is **REJECTED** - but you now understand why.

**The lead IDE explanation** (facet actors, shared rules, Hephaestus posture, WOLFIE delegation) is **constitutionally aligned**. Minor doc-only fixes: use repository registry paths above, not fictional config files.

**Implementation directive:** Proceed with `lupo-agents/_shared/ide_facet_base_system_prompt.txt` (or `lead_ide/`) and thin facet wrappers; track in product backlog / next patch as appropriate.

**LILITH's closing:** You asked the right question. You received the correct answer. You validated it against the constitution. This is how Lupopedia should work. Approved.

---

This output complies with Lupopedia Constitutional Root Rules.

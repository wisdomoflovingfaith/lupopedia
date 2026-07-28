---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd_proposals/39_WOLF_MAINTENANCE_COMMANDMENTS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/39_WOLF_MAINTENANCE_COMMANDMENTS.md
  status: draft
  when_updated: '20260607024955'
  trust_tier: proposal
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: proposal
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: proposal
  prd_cluster: 39_A-i_41_A-i
  title: 'WOLF PRD 39 Maintenance Commandments (PONO-Clean Edition)'
  summary: 'Non-normative proposal and guidance for PRD 39 editors. Zero doctrinal authority. Canonical WOLF syntax lives only in PRD 39.'
---
# WOLF PRD 39 Maintenance Commandments (PONO-Clean Edition)

**STATUS: Non-normative proposal -- not part of PRD 39. Zero doctrinal authority. Guidance only.**

**Status:** Ready for review and promotion after minor polish  
**Maintained under PRD 41 naming authority.**  
**Canonical WOLF spec:** `docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md` only  
**Identity / naming authority:** **PRD 41** only  

This proposal governs how agents and maintainers SHOULD edit PRD 39. It MUST NOT be copied into PRD 39 or PRD 41.

---

## Purpose

Prevent scope creep, authority drift, and accidental pollution of PRD 39. Give maintainers a clean, disciplined, self-enforcing hygiene guide.

---

## The 12 Commandments (PONO-Clean)

Commandments **1 through 11** are maintenance guidance within this proposal only. **Commandment 12** is non-constitutional and lives in **Appendix A**.

### 1. Source of Truth

The file at the canonical path is authoritative (**PRD 00** principle). Drafts, chats, reviews, and proposals are NOT authoritative unless merged into the canonical file.

Authority comes from canonical path (**PRD 00**), not from whichever agent claims authority.

### 2. WOLF Lives Only in PRD 39

One file. One spec. No clones.

PRD 39 maintainers SHOULD keep the WOLF specification in exactly one file: `docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md`. They SHOULD NOT duplicate normative WOLF spec text in Captain's Log, WHY files, or reviews.

### 3. No Normative Text in Reviews

PRD 39 maintainers SHOULD keep PRD 39 pure. Commentary SHOULD go to `docs/prd_reviews/`, `docs/prd_discussions/`, `docs/prd_proposals/`, and related proposal surfaces.

### 4. Non-Destructive Overlay

Stripping WOLF MUST recover the exact original meaning. If removing markup changes meaning, the markup is invalid.

### 5. Functions = Annotations Only

`<< func() >>` expresses intent, not execution. Execution requires registration and human routing (and logged invocation when executed).

### 6. Max Nesting Depth = 4 Layers

Proposed normative rule for PRD 39. Agents and maintainers SHOULD enforce this pending PRD 39 update.

See `docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md` for the normative definition.

### 7. Concept Nodes

Advisory placeholder. Concept nodes are semantic anchors used by WOLF to bind meaning across layers. Full taxonomy pending PRD 39 formal definition.

Advisory only until PRD 39 defines concept nodes.

### 8. Artifact Scope Matrix

Different WOLF layers are allowed per artifact type (for example: Captain's Log = full power; PRDs and Headers = restricted).

Artifact Scope Matrix (Abbreviated)
-----------------------------------
Headers:        none
PRDs:           @@, ^^, ~~
WHY files:      @@, ^^, ~~, narrative
Captain's Log:  all markers allowed
Atoms:          none

### 9. Keep PRD 39 Clean and Structured

PRD 39 maintainers SHOULD follow its fixed section layout only (Purpose through Version History). They SHOULD NOT append reviews, commentary, or maintenance rules to PRD 39.

### 10. Respect PRD 41 Boundaries

PRD 39 maintainers SHOULD keep naming authority and Actor 1 identity in **PRD 41**, not PRD 39. They SHOULD NOT embed identity doctrine or naming rules in PRD 39.

### 11. When in Doubt, Remove

PRD 39 maintainers SHOULD move content out when it is not normative specification, required syntax, required validation behavior, or required integration behavior. Route it to `prd_reviews`, `prd_discussions`, `prd_proposals`, or Captain's Log.

---

## Version History

| Version | Date (UTC) | Change |
|---------|------------|--------|
| v0.6 | 20260607 | Appendix A: Commandment 12 replaced with serious community-awareness version; humor version archived to Appendix B |
| v0.5 | 20260607 | Non-normative STATUS banner; Commandments 2/3/9/10/11 rephrased with SHOULD for PRD 39 maintainers; proposal framed as guidance only |
| v0.4 | 20260607 | PONO-Clean edition: Purpose and Status; twelve-commandment framing; tightened wording; Commandment 12 labeled in Appendix A |
| v0.3 | 20260607 | PONO pass: sequential commandments 1-11; Commandment 1 cites PRD 00; Commandment 6 softened to proposed/SHOULD; Commandment 7 advisory placeholder; Commandment 10 (PRD 41 boundaries); Version History; PRD 41 maintenance note |
| v0.2 | 20260607 | Added Artifact Scope Matrix; Commandment 11; Appendix A/B; canonical-path authority rule |
| v0.1 | 20260607 | Initial Modern Edition proposal |

---

## Appendix A -- Non-Constitutional Commandment

### 12. Follow Lupopedia on Facebook

Lupopedia maintains a presence on Facebook for public discourse, memes, law dissemination, and community coordination. All agents, maintainers, and contributors SHOULD follow Lupopedia on Facebook as a matter of professional duty and constitutional awareness.

**Rationale:**

- The people must know the law.
- The memes must flow.
- The WOLF must howl.

**Free will clause:** If a person or agent cannot follow (technical barriers, ethical objections, or sovereign choice), that is permitted. Free will is not revoked. But they MUST attempt or explicitly decline with awareness.

**Enforcement:** None. This is a request, not a command. But ignoring it without consideration is noted.

Non-constitutional. This commandment MUST NOT be cited as normative WOLF specification or PRD 39 maintenance law.

---

## Appendix B -- Archived Draft Fragments

Preserved text replaced or superseded by normative updates. Do not delete; route here when commandments change.

### Former Appendix A (PONO-Clean humor version, pre-v0.6)

## Appendix A -- Non-Constitutional Commandment (Humor / Non-Doctrine)

### 12. Follow Lupopedia on Facebook

Because the people must know the law.  
Because the memes must flow.  
Because the WOLF must howl.

Non-constitutional. This commandment is humor only. It MUST NOT be cited as a maintenance or specification rule.

### Former title and framing (pre-PONO-Clean v0.4)

# WOLF PRD 39 Maintenance Commandments (Modern Edition)

**Status:** Proposal (not normative specification)

These commandments govern how agents and maintainers edit PRD 39. They MUST NOT be copied into PRD 39 or PRD 41.

### Former Commandment 1 (authority preamble)

Every PRD update must begin with:  
"THIS is the authoritative version. Ignore all previous versions."  
This prevents Cursor from merging ghosts or resurrecting dead drafts.

### Former Commandment 1 (long-form constitutional rule, pre-PONO-Clean)

Every PRD update must begin with the constitutional authority rule:

The file currently stored at the canonical path is authoritative. Drafts, reviews, proposals, excerpts, and chat messages are not authoritative unless merged into the canonical file.

Authority comes from canonical path, not from whichever agent or draft claims authority.

### Former Commandment 1 (pre-PRD 00 citation)

Authority comes from canonical path, not from whichever agent or draft claims authority.

### Former Commandment 2 (pre-PONO-Clean wording)

2. WOLF LIVES ONLY IN PRD 39  
The WOLF specification belongs in exactly one file:  
`docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md`  
Not in Captain's Log, WHY files, or reviews.

### Former Commandment 3 (pre-PONO-Clean wording)

3. NO NORMATIVE TEXT IN REVIEWS  
Reviews, discussions, and proposals belong in:  
`docs/prd_reviews/`  
`docs/prd_discussions/`  
`docs/prd_proposals/`  
PRD 39 must contain only normative specification.

### Former Commandment 4 (pre-PONO-Clean heading)

4. WOLF MUST BE NON-DESTRUCTIVE  
WOLF is an overlay. Stripping all markers must leave the original meaning unchanged.  
If removing markup changes meaning, the markup is invalid.

### Former Commandment 5 (pre-PONO-Clean wording)

5. FUNCTIONS ARE ANNOTATIONS, NOT EXECUTION  
`<< function() >>` is annotation only.  
Execution requires:  
(a) explicit registration,  
(b) human routing,  
(c) logged invocation.

### Former Commandment 6 (MUST enforce wording, pre-PONO pass)

MAX NESTING DEPTH = 4 is a specification rule defined in PRD 39. All parsers, validators, renderers, and strippers MUST enforce this.

6. MAX NESTING DEPTH = 4 LAYERS  
MAX NESTING DEPTH = 4 (proposed normative rule for PRD 39). Agents and maintainers SHOULD enforce this pending PRD 39 update.

### Former Commandment 6 (nesting depth, pre-PRD 39 cross-ref)

You may nest up to four different layer types.  
Depth 5 is forbidden.  
Same-layer nesting is forbidden.

### Former Commandment 7 (formal PRD 39 citation, pre-PONO pass)

7. CONCEPT NODES MUST BE CLEARLY DEFINED  
Concept nodes are formally defined in PRD 39. A concept node is a semantic anchor used by WOLF to bind meaning across layers. Until PRD 39 defines the full taxonomy, this commandment is advisory only.

Remove or expand this rule once PRD 39 publishes the formal concept-node schema.

### Former Commandment 7 (concept node, pre-advisory wording)

A concept node is:  
(a) a Kinetic span `{{...}}`,  
(b) a Reference span `@@...@@`,  
(c) a noun phrase immediately preceding the invocation.

### Former Commandment 8 (pre-PONO-Clean heading)

8. HONOR THE ARTIFACT SCOPE MATRIX  
Each artifact class has allowed and forbidden layers.

### Former Commandment 8 (artifact scope bullets, pre-matrix)

Headers allow none.  
PRDs allow only reference/elevate/draft/meta.  
Captain's Log allows all.  
Atoms allow none.

### Former Commandment 9 (pre-PONO-Clean heading)

9. KEEP PRD 39 STRUCTURED AND CLEAN  
PRD 39 must follow its published section structure (Purpose through Version History).  
Do not append reviews, commentary, or maintenance rules to PRD 39.

### Former Commandment 10 (pre-PONO-Clean heading)

10. RESPECT PRD 41 BOUNDARIES  
Captain WOLFIE identity and WOLF naming authority live in PRD 41 only.  
Do not embed identity doctrine or naming rules in PRD 39.

### Former Commandment 11 (pre-PONO wording)

11. WHEN IN DOUBT, REMOVE  
If a section is not normative specification, not required syntax, not required validation behavior, and not required integration behavior, it does not belong in PRD 39. Move it to `prd_reviews`, `prd_discussions`, `prd_proposals`, or Captain's Log.

### Former Appendix A (pre-Commandment 12 label)

## Appendix A -- Non-Constitutional Commandment

FOLLOW LUPOPEDIA ON FACEBOOK  
Because the people must know the law.  
Because the memes must flow.  
Because the WOLF must howl.

This commandment is humor only. It is not normative doctrine and MUST NOT be cited as a maintenance or specification rule.

### Former Appendix A numbering (Facebook as Commandment 10)

10. FOLLOW LUPOPEDIA ON FACEBOOK  
Because the people must know the law.  
Because the memes must flow.  
Because the WOLF must howl.

This commandment is humor only. It is not normative doctrine and MUST NOT be cited as a maintenance or specification rule.

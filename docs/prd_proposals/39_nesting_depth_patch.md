---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd_proposals/39_nesting_depth_patch.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/39_nesting_depth_patch.md
  status: draft
  when_updated: '20260607024600'
  trust_tier: proposal
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: proposal
  artifact_kind: patch
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: proposal
  prd_cluster: 39_A-i
  title: 'PRD 39 patch candidate: nesting depth (normative)'
  summary: 'Separate patch to promote max nesting depth = 4 into PRD 39 Section 4. Source: WOLF Maintenance Commandments proposal Commandment 6. Do not merge until Captain Wolfie approves.'
---
# PRD 39 Patch Candidate: Nesting Depth

**STATUS: Patch proposal only -- not merged into PRD 39.**

**Target file:** `docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md`  
**Target section:** Section 4 (Syntax rules), new subsection after rule 3  
**Source:** `docs/prd_proposals/39_WOLF_MAINTENANCE_COMMANDMENTS.md` (Commandment 6)

---

## Proposed insertion (after Section 4 rule 3)

Insert the following subsection:

### 4.1 Nesting depth

Maximum cross-layer nesting depth is 4 different layer types. Depth 5 or more is INVALID. Same-layer nesting is INVALID.

Parsers, validators, renderers, and strippers MUST reject spans that violate this rule.

---

## Proposed diff (Section 4, rule 3 area)

```diff
 ## 4. Syntax rules

 1. **Balanced delimiters.** Every opening marker MUST have a matching close of the same kind.
 2. **No same-layer nesting.** Same-type nesting is INVALID in v0.5.
-3. **Maximum nesting depth.** Maximum nesting depth equals **4 consecutive opening markers of different layer types**. Same-type nesting is forbidden. A depth of **5 or more** different paired-layer types is INVALID. Interpreters MUST reject spans exceeding this depth.
+3. **Maximum nesting depth.** See Section 4.1.
 4. **Whitespace.** Whitespace preserved inside layers unless a consumer explicitly normalizes; strip rules collapse only marker tokens, not interior spacing.
 ...

+### 4.1 Nesting depth
+
+Maximum cross-layer nesting depth is 4 different layer types. Depth 5 or more is INVALID. Same-layer nesting is INVALID.
+
+Parsers, validators, renderers, and strippers MUST reject spans that violate this rule.
+
+**Promotion source:** `docs/prd_proposals/39_WOLF_MAINTENANCE_COMMANDMENTS.md` (Commandment 6); patch file `docs/prd_proposals/39_nesting_depth_patch.md`.
```

---

## Apply instructions

1. Captain Wolfie approves this patch.
2. Merge the diff into `docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md` only.
3. Bump PRD 39 version history.
4. Do not copy the Maintenance Commandments proposal into PRD 39.

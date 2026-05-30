---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "channels/42/threads/1011/20260318_182000_lilith_review_task_impl_002_project-awareness.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1011/20260318_182000_lilith_review_task_impl_002_project-awareness.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1011
  task_id: "task_impl_002"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:review"
  artifact_type: "thread"
  artifact_kind: "review"
  message_type: "review"
  purpose: "Project-awareness validation design review for task_impl_002"
  tags: ["project_awareness", "validator", "lilith"]
---

# LILITH review — task_impl_002 project-awareness

## 1. Verdict
- **PASS-WITH-NOTES**
- Implementation follows ATHENA/THOTH high-level architecture and is constitutionally compliant in intent.
- Minor corrections needed for strictness, explicitness, and future multi-project readability.

## 2. What is correct
- Project layer model matches ATHENA architecture:
  - `project -> channel -> thread -> task` containment in 1009.
  - `project_id` defaults and explicit values for future expansion.
- Tooling model in implementation (1011) uses deterministic CLI/env override, consistent with doctrine (optional `project_id`/`project_slug` in frontmatter).
- External AI behavior is acknowledged (filesystem-only) and the design falls back to component model in THOTH (1010) properly.
- Path safety rules (V-PROJECT-001..004) are specified clearly, with project boundary enforcement and `project-N:path` syntax.
- Backward compatible defaults are explicit: default `project_id=0`, slug `lupopedia-core`, no `--enforce-project` equals legacy validator behavior.

## 3. What is weak or risky
- `project_id` optional in headers may allow silent cross-project misattribution until enforcement is strict; need explicit w/ `--enforce-project` warnings.
- `project_id` defaulting to 0 can hide misconfigurations in multi-repo monorepos; no rule enforces explicit header in non-default multi-project scenario by default.
- `V-PROJECT-004` WARN (missing project_id with cross-project indicators) may be insufficient; it might be ERROR in production to avoid cross-project ambiguity.
- `task_id` mapping to project is not enforced (TODO/plan still per project but no `project_id` in rows): risk of accidental cross-project task references.
- Path escape check currently on `project_root` for scanned files, but might miss non-markdown link formats or plugin paths not detected by current regex if proposal uses only Markdown link parser.

## 4. What is incorrect (must fix before acceptance)
- **V-PROJECT-002** requires explicit `project-N:path` for outside links, but rule text says non-HTTP target outside project root MUST use it; should also allow `file://` normalized safe path with project prefix when explicit cross-project safe cases.
- **V-PROJECT-003** incorrectly states `project-N:inner` path checked for `..` but then mentions resolution not opened; for consistency, it should reject `project-N` with `..` at parser level (explicit) and also disallow leading `/` (must be repo-relative) with clear error.
- `project-<id>:<path>` syntax handling in 1011 says path not opened on disk for project 1; but if multi-project validation is needed it must verify syntactic sanity beyond no `..`, or else path forgery may emit false safe.

## 5. Rule-by-rule assessment
- **V-PROJECT-001**: correct, always RUN; severity ERROR is right; are we checking channel/thread mapping? currently only project_root path check; should include thread path pattern if available.
- **V-PROJECT-002**: mostly correct; needs non-server path exceptions; ensure there is a way to allow safe repo-internal cross references (e.g., `../` in non-project links should be normalizable or explicit project). severity ERROR ok.
- **V-PROJECT-003**: correct semantics;  requirement to disallow `..` in the second part is good; rule should explicitly require no leading `/` to avoid absolute path confusion. severity ERROR ok.
- **V-PROJECT-004**: rule is too weak as WARN; should be ERROR for multi-project safety at federation boundaries. at minimum should be ERROR when `to: project-N` is present but project_id missing. ; enforceable without DB yes.

## 6. Architecture alignment
- HEPHAESTUS (1011) matches ATHENA layer model (1009) and THOTH docs (1010): project as repo boundary and channel scoping is respected.
- thread/task model is preserved (Thread001 triage 1001; lifecycle 135527). Good.
- One main drift: ATHENA says `channel_id` must belong to project (in DB), but 1011 does not validate this in no-DB environment. It relies on filesystem path only (safe for now). Could be improved with config hook.

## 7. Backward compatibility assessment
- Default/non-enforce behavior stays old behavior; good.
- For single project, validator with `--enforce-project` works, but optional `project_id` and default `0` may allow no project context; still safe.
- STDOUT/stderr behavior is good and non-breaking with prior version, as designed.
- However, with `--strict`, report says project warnings are non-fatal; this is good for phased adoption.

## 8. Final recommendation
- Accept with follow-up corrections.
- Immediate corrections should be assigned:
  - **HEPHAESTUS**: make V-PROJECT-004 ERRORE in cross-project contexts and tighten `project-N:path` parsing
  - **ATHENA**: formalize channel->project mapping check for non-DB mode (e.g. `project_id` from path metadata or config). 
  - **WOLFIE**: require explicit `project_slug` in onboarding docs for multi-project repository clones to avoid implicit project 0 assumptions.
  - **THOTH**: add explicit doc block for `project_id` usage in `TODO.md`/`plan.md` transitions and cross-project references.
- Recommendation: proceed after these corrections are implemented and re-reviewed.

---

**LILITH (actor_id 2)**
**Date:** 2026-03-18 18:20 UTC

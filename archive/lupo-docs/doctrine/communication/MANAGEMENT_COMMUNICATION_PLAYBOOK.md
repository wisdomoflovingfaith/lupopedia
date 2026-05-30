---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/doctrine/communication/MANAGEMENT_COMMUNICATION_PLAYBOOK.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/communication/MANAGEMENT_COMMUNICATION_PLAYBOOK.md"
  status: "active"
  when_updated: "20260417001754"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/management-communication-playbook.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/management-communication-playbook-20260417.jsonl"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "management-communication-playbook"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "Management Communication Playbook"
  summary: "Tactical playbook: outcome-first framing, translation layer, weekly report steps, trust and friction patterns, pressure responses. Grounded in 2026-04-16 report cycle."
---
# Management Communication Playbook

Normative guidance for explaining Lupopedia to management without repeating unnecessary friction. This is operational doctrine, not motivation copy.

## 1. Core principle

Management evaluates, in this order of practical weight:

- **Clarity** — Can they retell the story in one sentence?
- **Risk** — What breaks, what survives, what is unknown?
- **Trust** — Are claims tied to evidence they can inspect?
- **Cost** — People, time, tools, and runway implications.

Management does **not** evaluate:

- Deep internal architecture on first pass.
- Full precision of internal terminology.

**Rule:** Lead with what they measure. Bring internal vocabulary only after the outcome is accepted.

## 2. The three-layer communication model

Use three layers, in order:

1. **Outcome** — What changed, for whom, and why it matters now.
2. **Concept** — What it is in plain language (one analogy max, optional).
3. **Detail** — Schema, file trees, agent graphs, edge cases. **Only if asked.**

**Earn the how by first proving the why.** If layer 1 is weak, layer 3 reads as noise or defensiveness.

## 3. Translation rules

When speaking or writing for management, prefer the right column. Internal terms belong in appendices, evidence indexes, or footnotes.

| Internal term | Management-safe phrasing |
|---------------|---------------------------|
| atoms | known-good values; reference data; shared constants |
| canonical | approved; trusted; authoritative copy |
| staged memory | work-in-progress data; draft operational state |
| continuity layer | limited operation during outages; offline-safe artifacts |
| TOON / JSON sidecar | structured export used for audit and replay |
| facet / actor (IDE) | registered automation surface (attribute work, not philosophy) |

**Rule:** Define any internal term once per document, at first use, then use the management phrasing for the rest of the executive path.

## 4. What builds trust

Patterns that worked in practice (including the 2026-04-16 report cycle):

- **Traceability** — A single section or appendix that lists real paths (report, memory sidecar, atoms if used, transcript, evidence index).
- **Real file paths** — Not screenshots of trust; paths that can be opened in the repo.
- **Evidence-backed claims** — Each material assertion points to an artifact row or file.
- **Honest company size** — Operator count, instance count, and scope stated plainly.
- **Clear DB vs continuity distinction** — Database is system of record under normal operation; filesystem artifacts bound degraded or offline work.
- **Concrete resilience examples** — e.g., agent or tool failure with work continuing; cite the thread or log.

## 5. What causes friction

Avoid these failure modes:

- **Leading with architecture** before the outcome is understood.
- **Internal terminology in the first screen** of an email or deck.
- **Explaining how before why** — triggers "why do I care" fatigue.
- **Vague claims without proof** — invites skepticism and rework.
- **Mixing system layers** in one breath (DB truth, exports, IDE state) without explicit separation.

## 6. Weekly report playbook

Dependency order for each cycle:

1. **Start from the previous report** — Carry forward structure; edit deltas only where reality changed.
2. **Update deltas only** — New risks, new outcomes, new decisions; do not rewrite stable history unless it was wrong.
3. **Use the translation layer first** — Draft the executive path in management-safe language; hang technical truth in traceability.
4. **Build the evidence index in parallel** — File paths, manifests, transcript rows; do not retrofit after sending.
5. **Verify the header early** — `memory_toon`, `atoms_toon` (if any), `transcript_jsonl` triple, `artifact_type` / `lupopedia.schema` alignment; fix before narrative polish.
6. **Write the outcome-first summary** — First paragraphs answer: what moved, what risk remains, what you need from them (if anything).

## 7. Under pressure response patterns

Short, reusable replies. Expand only if they ask.

**Q:** What happens if something fails?

**A:** Work continues within the continuity boundary we define. We proved a concrete case this cycle. Here is the artifact path and the one-line sequence.

**Q:** Why is this complex?

**A:** The complexity is in surviving failure without losing lineage or operator trust. The happy path is smaller than the failure-aware path.

**Q:** Is the database still the source of truth?

**A:** Yes under normal operation. Exports and transcripts are for audit, replay, and limited degraded operation, not a parallel truth.

**Q:** Why so many files?

**A:** So every claim is replayable. The count is the cost of traceability; the index is how you navigate it.

## 8. Emotional reality (grounded)

- The system is legitimately hard to explain the first time.
- First explanations will be rough; reuse improves speed and calm.
- Stress early in a new reporting format is common; it is not a permanent property of the work.
- The playbook exists so the operator does not have to rediscover framing under time pressure.

## 9. Non-negotiables

- **Never fake clarity** — Say unknown when unknown; bound the risk.
- **Never claim more than exists** — Scope and maturity must match artifacts.
- **Always anchor to real artifacts** — Paths, hashes, or stable IDs.
- **Always separate DB vs continuity** — Prevents the worst misreadings.
- **Always lead with outcome** — Earn attention for the rest.

---

**This output complies with Lupopedia Constitutional Root Rules.**

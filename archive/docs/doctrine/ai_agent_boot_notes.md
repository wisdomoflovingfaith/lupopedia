---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/AI_AGENT_BOOT_NOTES.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/AI_AGENT_BOOT_NOTES.md"
  status: "active"
  when_updated: "20260412104649"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/ai-agent-boot-notes.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/ai-agent-boot-notes"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: "ai-agent-boot-notes"
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: "AI agent boot notes (session start, alignment tests)"
  summary: "Practical boot sequence for IDE agents; knowledge alignment tests after rules merge; links to competency probe doctrine."
---
# AI agent boot notes

Operational notes for **starting work** in this repository as an AI-assisted agent. **Canonical onboarding prose** remains [`AGENTS.md`](../../AGENTS.md).

## Session start (recommended)

1. Run **`python bin/tick.py`** once per editing batch when you will touch **`last_modified_utc`**, **`when_updated`**, or **`last_verified`** — do **not** guess UTC ([`TICK_PY_DOCTRINE.md`](TICK_PY_DOCTRINE.md)).
2. Load **rules** from **`.cursor/rules`**, **`rules/root`**, and repo **`AGENTS.md`** as applicable to your surface.
3. Resolve **identity** from [`database/lupopedia/actors/registry.json`](../../database/lupopedia/actors/registry.json) (facet `actor_id`, not generic “IDE”).
4. Prefer **shared state** (memory graph, pending tasks, channel threads) over asking the human to relay messages — see [PRD 50](../prd/50_agent_coordination_protocol.md).

## Knowledge alignment tests (competency probes)

After **`propagate_agent_rules.php`**, a **merge of `.mdc` rules**, or a **constitutional / PRD change**, the current workspace may **not** contain another agent’s **private** test transcript. **Do not assume** alignment from chat history alone.

**Action:** run one or more **small generation tasks** that **force** the new rule into output (e.g. draft a header block, a PDO_DB query with `LUPO_TABLE_PREFIX`, a path under `channels/…`). **Validate** with [`validate_lupopedia_headers_universal.py`](../../scripts/validate_lupopedia_headers_universal.py) or manual checklist. **Repeat** until output is compliant.

**Multi-actor probes:** If **two models** (or a model chain) run the test, designate **one examiner** and **one examinee**; the examinee **must not** self-grade; the examiner closes with **`<TEST_COMPLETE>`** on its own line; no **parroting** the other’s last message. Full rules: [`AI_ACTOR_COMPETENCY_TEST_PATTERN.md`](AI_ACTOR_COMPETENCY_TEST_PATTERN.md).

**Specification:** [`AI_ACTOR_COMPETENCY_TEST_PATTERN.md`](AI_ACTOR_COMPETENCY_TEST_PATTERN.md). **If the probe fails:** orchestrator records the rule in the **memory graph** — [`AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md`](AI_ACTOR_KNOWLEDGE_UPDATE_PROTOCOL.md) (`Node received.` ack only). **Hub:** [`AGENT_ORCHESTRATION.md`](AGENT_ORCHESTRATION.md).

---

This output complies with Lupopedia Constitutional Root Rules.

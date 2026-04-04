---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404161001"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md"
  last_modified_utc: "20260404161001"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-decisions"
  actor_id: 102
  actor_name: cursor
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: decision
  purpose: "APPROVED receipt — service agent doctrine + PRD 00 Section 5 expansion + Softaculous auto-installer docs/code (this thread only)"
lupopedia.footer:
  last_verified: "20260404161001"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
---

# file: DECISION service_agent_architecture_softaculous — delegation: cursor:root

# DECISION (APPROVED): Service agent architecture + Softaculous auto-installer alignment — version receipt

## 5W1H

| Element | Record |
|---------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), orchestration `cursor:root`. |
| **WHAT** | Ratify documentation and code changes **actually merged in this thread** for (1) **PHP-first service agents** and (2) **Softaculous / auto-installer** packaging behavior. |
| **WHERE** | `lupo-docs/prd/00_root_constitutional_system_requirements.md` §5; `lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`; `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`; `lupo-docs/implementations/service_agents/`; `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md`; repo root sample config + `README.txt`; `lupo-includes/bootstrap.php`; `install.php`; `index.php`; `lupo-scripts/build_softaculous_package.sh`. |
| **WHEN** | Evidence batch UTC **`20260404161001`** (`python lupo-bin/tick.py`). |
| **WHY** | (1) Prevent mistaking **service** agents for default **chat** targets; bind behavior to **PHP** with **LLM second**. (2) Let auto-installers ship **config + first request** without missing dirs or leaking dev **`lupopedia-config.php`**. |
| **HOW** | New doctrine file + constitutional §5.7–§5.10 + implementation mirror; THOTH **grounding** rule in headers README; sample **`lupopedia-config-sample.php`** with `[[softdb*]]` placeholders; bootstrap **`mkdir`** for runtime dirs; packager **`rsync` exclude** live config; **`SOFTACULOUS_PACKAGE_BUILD.md`** sections §1b–§1c and summary table updates. |

## APPROVED scope (thread-verified)

- **Constitution PRD 00** — §5.1–5.2 agents vs actors; §5.7 KAIROS; §5.8 implementation mirroring; §5.9 THOTH stale artifacts; §5.10 service agents with **explicit roster** IRIS, ANUBIS, ROSE, THOTH, KAIROS and **RuntimeActorLoopService** contrast.
- **`SERVICE_AGENT_ARCHITECTURE.md`** — full companion doctrine (KAIROS flow, THOTH authority pointer).
- **`LUPOPEDIA_HEADERS/README.md`** — THOTH must ground in **`lupo-database/lupopedia/json/*.json`** + **`lupo-docs/database/lupopedia/tables/active/`**; not model-only knowledge.
- **`lupo-docs/implementations/service_agents/`** — README, status, decisions (incl. `20260404_160645_DECISION_php_first_service_agents.md`), Q/A/C THREAD_INDEX stubs.
- **Softaculous** — as listed in **WHERE** for packaging and silent-install narrative.

## WHAT NOT claimed (this thread)

The following were **not** performed in the **same** Cursor thread as this receipt (do not attribute here):

- **PRD 16** author/verifier field matrix edits, **PRD 26/30/31** create/reject/COUNTERMEASURE cycles as described in unrelated directives.
- **`validate_implementation.py`** / **`validate_lupopedia_headers_universal.py`** conditional-field enhancements attributed only to those tool-change threads.
- **Rule 93.PK_NAMING** / **5W1H** structural metadata PRD work unless a separate decision file exists with evidence.

## Outcome

**APPROVED** as an accurate **version-folder receipt** for **`lupo-docs/versions/4.0.94/`** traceability. Product certification (**PRD 33** §10) remains **open** until evidence rows close in **`TODO.md`**.

This output complies with Lupopedia Constitutional Root Rules.

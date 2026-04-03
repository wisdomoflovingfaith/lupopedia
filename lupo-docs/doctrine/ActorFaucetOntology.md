---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/doctrine/ActorFaucetOntology.md"
  web_path: "http://www.lupopedia.com/doctrine/ActorFaucetOntology"
  last_modified_utc: "20260310"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "governance"
  purpose: "Canonical ontology: Actor vs Faucet; IDE surfaces (faucets) not actors; separation of identity from execution surface"
  tags: ["actor", "faucet", "ide", "llm", "ontology", "governance"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FallbackDoctrine.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/RULES_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/migrations/20260310_faucet_class.sql", type: "implements", weight: 0.8 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "implements", weight: 0.8 }
    - { to: "lupo-docs/doctrine/FAUCET_TRACEABILITY_DOCTRINE.md", type: "references", weight: 0.9 }
    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Actor–Faucet Ontology — session: L-LUPO-WOLFIE-CURSOR — delegation: wolfie:cursor:root — web_path: http://www.lupopedia.com/doctrine/ActorFaucetOntology

# Actor–Faucet Ontology (v1.0)

**PURPOSE:** Define the canonical separation between **actors** (orchestration identities; rules, skills, doctrine) and **faucets** (execution surface, LLM, runtime config). **Actors orchestrate; faucets execute; sessions carry runtime context.** IDE surfaces (Cursor, Kiro, Antigravity, Windsurf, Codex, JetBrains, Warp) are **faucets**, not actors.

---

## Canonical mapping

| Layer | What it represents | Examples |
|-------|--------------------|----------|
| **Actor** | Identity, rules, skills, persona, doctrine | Wolfie, Lilith |
| **Faucet** | Execution environment + LLM + runtime config | Cursor IDE, Kiro IDE, Antigravity IDE, OpenAI API, DeepSeek API |
| **Channel** | Context, documents, tasks, roles | wolfierouter, devchannel, governance_channel (e.g. Channel 42) |
| **LLM** | The model behind the faucet | GPT‑4.1, DeepSeek R1, Claude 3.7, Gemini 2.0 |

This keeps the system deterministic, polyphonic, and scalable.

---

## IDE surfaces are faucets (not actors)

IDE surfaces (Cursor, Kiro, Antigravity, Windsurf, etc.) are **not** actors. They are a **specialized faucet class** whose job is to:

- Wrap an external execution environment
- Expose an LLM or toolchain
- Enforce temperature / model / context rules
- Provide a runtime surface for an actor (e.g. Wolfie) to operate through

Therefore:

- **Wolfie** = the actor (identity, rules, skills, doctrine)
- **Cursor / Kiro / Antigravity / OpenAI API** = faucets (execution surfaces)
- **Fallback, routing, and skills** = actor-level behavior
- **Temperature, model, context window, tool access** = faucet-level configuration

---

## Why IDEs are faucets (not actors)

| Criterion | Faucets (IDE/LLM) | Actors |
|-----------|-------------------|--------|
| **Identity** | No identity; they are execution surfaces | Have identity, persona, doctrine |
| **Rules & skills** | Do not hold rules or skills | Hold rules and skills |
| **Runtime parameters** | Control temperature, model, max tokens, tool access, context window, system prompt, safety envelope | Do not configure runtime; they use faucets |
| **Swappability** | Can be swapped, routed, or failed over | Cannot be swapped; identity is fixed |

Cursor, Kiro, Windsurf, Antigravity do not have persona or doctrine—they **adjust runtime parameters** and provide the surface through which an actor (e.g. Wolfie) operates. Fallback routes **between faucets**, not between actors.

---

## Faucet classes: IDE vs LLM

Both are subtypes of the same **faucet** class. The schema distinguishes them via `faucet_class` on `lupo_agent_faucets`:

| Faucet class | Description | Characteristics |
|--------------|-------------|-----------------|
| **ide** | IDE-based execution environment | Has an LLM inside; has filesystem; has tools (run, debug, test, search); multi-agent orchestration; persistent workspace state. Examples: Cursor, Kiro, Antigravity, Windsurf. |
| **llm** | Direct LLM API access | Direct API access; pure text in/out; no toolchain; no filesystem. Examples: OpenAI API, DeepSeek API. |

Both are still **faucets**. Actors use them; they do not replace actors.

---

## Schema

- **lupo_agent_faucets:** One row per faucet. `actor_id` = the actor this faucet serves (e.g. Wolfie). `faucet_class` = `'ide'` or `'llm'` (optional; NULL = lupo-legacy/unclassified). Columns such as `temperature`, `model_name`, `system_prompt`, `capabilities_json` are faucet-level configuration.
- **lupo_rules / lupo_rule_targets:** Attach rules to **actors** and **channels**, not to faucets. Fallback rule applies to actors; routing and failover are across faucets.

See **FallbackDoctrine.md** for fallback invariants and that fallback routes between faucets, not actors.

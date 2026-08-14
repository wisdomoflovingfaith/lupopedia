---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/drafts/lupopedia_semantic_whitelist_blacklist_draft_001.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/drafts/lupopedia_semantic_whitelist_blacklist_draft_001.md
  status: draft
  when_updated: "20260801102812"
  trust_tier: development
  questions_toon: null
  memory_toon: memory/development/development/1026/08/lupopedia_semantic_whitelist_blacklist_draft_001.toon
  atoms_toon: null
  transcript_jsonl: 0/development/semantic-whitelist-blacklist
  artifact_type: status
  artifact_kind: report
  channel_key: development
  federation_node_id: 0
  thread_key: semantic-whitelist-blacklist
  lupopedia.schema: status
  prd_cluster: 82_B_00_A_16_A_98_A
  title: "Lupopedia Semantic Whitelist + Blacklist (DRAFT 001 -- For IDE Review)"
  summary: "Living DRAFT 001: semantic lists; external headers ;; locked; mirrored to WOLFIE_DIALECT 5a (pipe | living alternate guess)."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: semantic_governance
  faucet_actor_id: 102
---
# LUPOPEDIA SEMANTIC WHITELIST + BLACKLIST (DRAFT 001 -- For IDE Review)

A living document -- evolves with each iteration.

**Status:** DRAFT 001 (not ALII-final; not densified into headers as new keys)  
**Anchors:** PRD 82_B (Hermes / Hawaiian semantics), PRD 00_A (constitutional wall), ethical state markers doctrine  
**Scope:** Terms safe for constitutional semantics vs pidgin/slang that must not enter the semantic layer  
**KAPU:** Hawaiian constitutional fields remain Hermes/body/sidecar -- do not densify into `lupopedia.headers` YAML (PRD 16 / 4.2.0 densification ban).

---

## WHITELIST (Allowed Semantic Constants)

These terms are stable, non-pidgin, non-slang, and safe for constitutional semantics.
They represent ethical primitives, not cultural slang.

| Term | Meaning (constitutional) |
|------|--------------------------|
| **KAPU** | sacred boundary; forbidden; hard constraints |
| **PONO** | correctness; balance; right condition |
| **KULEANA** | responsibility; role; duty |
| **ALII** | authority; leadership; human decision power |
| **KUMU** | source; foundation; origin |
| **PUKA** | structural gap; missing element |
| **OHANA** | actors bound together; relational cluster |
| **KAPAKAI** | crooked; confused; misaligned semantics |
| **PILAU** | corrupted; rotten; broken state (ethics/correctness signal, not sensory smell) |
| **EH_BRAH_WHY** | causal chain; root cause; structural reasoning |
| **TEMU** | time; temporal flow; rhythm (Polynesian root, not pidgin) |

These are canonical (pending ALII ratification of this draft).
These can be safely embedded in Lupopedia headers (Hermes/body), schemas, actors, and doctrine -- **not** as new dense YAML keys unless a future PRD explicitly admits them.

---

## BLACKLIST (Forbidden Terms -- Pidgin / Slang / Unstable)

These terms are not allowed in any semantic field, header, schema, or constitutional logic.
They introduce ambiguity, cultural drift, and inconsistent meaning.

| Term / pattern | Why forbidden |
|----------------|---------------|
| **beef** | fight; conflict (slang) |
| **choke** | a lot; abundance (slang overload) |
| **bumbai** | later; conditional future (pidgin) |
| **pau** | finished; done (ambiguous outside controlled context) |
| **grindz** | food (slang) |
| **broke da mout** | delicious (slang) |
| **da kine** | anything; everything; nothing; semantic chaos |
| any pidgin phrase that relies on tone, context, or cultural inference | non-deterministic |
| any slang that shifts meaning across generations or islands | unstable |
| any metaphorical pidgin constructs used as slang (e.g. "small kid time"; "talk story" as slang instead of a formal field) | metaphorical drift |

These terms cannot enter Lupopedia's semantic layer.
They break consistency, confuse actors, and destabilize doctrine.

---

## GRAYLIST (Conditional / Requires IDE Review)

These terms may be allowed only if formally defined and not used as slang.

| Term | Condition |
|------|-----------|
| **TALK STORY** | allowed only as a formal field (narrative exchange), not as pidgin slang |
| **OHANA variants** | allowed only if referring to actor clusters, not casual family slang |
| **KULEANA variants** | allowed only if referring to responsibility, not casual "my kuleana" slang |
| Any Polynesian root word not already whitelisted | allowed only after IDE semantic review |

Graylist terms must be explicitly defined before use.

---

## RULES FOR TERM ADMISSION (IDE Enforcement)

The IDE will enforce these rules when adding new semantic terms:

1. Term must have **one meaning**, not multiple cultural interpretations.
2. Term must be **non-pidgin**, **non-slang**, **non-metaphorical** (as a primary semantic token).
3. Term must be **stable** across generations and linguistically consistent.
4. Term must map cleanly to a Lupopedia constitutional field (KAPU, PONO, KULEANA, etc.) or an approved extension of that set.
5. Term must **not** rely on tone, context, or local dialect to be understood.
6. Term must be **reviewed and approved** before entering the semantic layer.

If a term fails any rule, it is blacklisted.

---

## WHY THIS MATTERS

You are building a semantic operating system, not a cultural dictionary.
If pidgin leaks in, you get:

- inconsistent meaning
- actors misinterpreting commands
- corrupted doctrine
- semantic drift
- future confusion when scaling Lupopedia globally

This whitelist/blacklist keeps the system clean, sharp, future-proof, and non-ambiguous.

---

## EXTERNAL MEDIUM HEADER TRANSPORT (Patreon and peers) -- LOCKED

**Problem (observed):** On internet mediums (Patreon and similar paste surfaces), multiline `lupopedia.headers` YAML is unreliable. Newlines get doubled, stripped, or injected. Some hosts normalize to `\n`, some to `\r\n`, some insert blank lines between every field. Result: broken envelopes, false validators, and agents inventing structure.

**KAPU for those mediums:** Do **not** put newlines inside the header when the origin is non-repo paste.

### LOCKED rule (ERIC / ALII direction -- 20260801102540)

For **all external internet** header transport (Patreon, Substack, Facebook, YouTube descriptions, similar):

1. **Single line only** -- the entire header is one physical line (zero `\n` / `\r\n` inside the header token).
2. **Field separator is `;;`** (double semicolon) -- not single `;`.
3. **Repo canonical form stays multiline YAML** between `---` after ingest.

| Rule | Value | State |
|------|-------|-------|
| Physical newlines in transport header | Forbidden | **LOCKED** |
| Field separator | `;;` | **LOCKED** |
| Layout | One line | **LOCKED** |
| Key/value separator | `=` (default); freeze in PRD when promoted | OPEN (minor) |
| Escaping if value contains `;;` | percent-encode or quoted form | OPEN (minor) |
| Repo form after ingest | Multiline YAML `---` envelope | **LOCKED** (unchanged) |
| `channel_index` | Medium slug (`patreon`, `substack`, `facebook`, `youtube`, `github`, `external`, ...) | ACTIVE |
| `source_timestamp` | Required when first-publish is off-repo | ACTIVE |

**Supersedes:** single-`;` one-line workaround. Do not emit new external headers with bare `;` as the field separator.

### Canonical external example

```text
lupopedia.headers.inline: header_format_version=4.2.0;;path_from_lupopedia_root=docs/...;;status=draft;;channel_index=patreon;;title=Short title;;summary=One sentence no newlines
```

### Rejected alternatives (closed)

- `||` pipe-pair
- ASCII record-separator (0x1E)
- Base64 whole-block
- One-line JSON as the primary human paste form
- Single `;` field separator

### Ingest expectation (IDE)

1. Detect `lupopedia.headers.inline:` (or agreed marker).
2. Split fields on `;;` only (never on bare `;`).
3. Map keys to the dense PRD 16 / 4.2.0 envelope.
4. Reject if any field value contains a raw newline.
5. Write **canonical multiline** header into the repo file; optionally keep the one-line origin string in body or memory.

**PRD promotion:** still pending formal fold into PRD 16_C companion or PRD 82_B external paste appendix. Until then, this LOCKED rule governs external paste practice. Dialect COMMENT NOTES: `docs/status/actor_logs/WOLFIE_DIALECT.md` section **5a** (living; `|` noted as alternate guess only).

---

## Iteration log

| Draft | when_updated | Note |
|-------|--------------|------|
| 001 | 20260801100314 | Initial living draft for IDE review (CURSOR faucet 102 / WOLFIE orchestration; ERIC auth_user_id 10000) |
| 001a | 20260801100737 | Added EXTERNAL MEDIUM HEADER TRANSPORT: Patreon newline corruption; one-line headers; prefer `;;` over `;` |
| 001b | 20260801102540 | LOCKED: external internet headers = single line + `;;` separator; single `;` superseded |
| 001c | 20260801102812 | Mirrored into WOLFIE_DIALECT section 5a + PRD 39 9.6 COMMENT NOTE; `|` documented as living alternate guess only |

---

## Related (read, do not replace)

- `docs/status/actor_logs/WOLFIE_DIALECT.md` -- section 5a external header delimitation (COMMENT NOTES)
- `docs/prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md` -- section 9.6 COMMENT NOTE pointer
- `docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md` -- Hermes fields + pidgin safety layer
- `docs/doctrine/ethical_state_markers_doctrine.md` -- PONO / PILAU / KAPAKAI markers
- `docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_VALIDATOR_NOTES.md` -- Hawaiian densification ban
- `docs/status/actor_logs/drafts/lilith_d_i_audit_header_wolfie_hawaiian_0_00000.md` -- channel_index enum including patreon

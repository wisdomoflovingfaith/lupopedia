---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "prompts/antigravity/20260311_lilith_faucet_review_toons_and_orchestration.md"
  web_path: "http://www.lupopedia.com/prompts/antigravity/lilith_faucet_review_toons_and_orchestration"
  last_modified_utc: "20260311"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "prompt"
  artifact_kind: "directive"
  purpose: "Directive for Antigravity IDE to operate as Lilith (actor_id 2) faucet: review orchestration doc and all TOONs, produce Lilith suggestions on database/channels/semantic organisation."
  tags: ["antigravity", "lilith", "faucet", "review", "toon", "orchestration", "4.0.69"]
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  channel_id: 42
  paired_actor_id: 1000
lupopedia.edges:
  outbound_edges:
    - { to: "docs/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md", type: "references", weight: 1.0 }
    - { to: "docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/toon/", type: "references", weight: 1.0 }
lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "wolfie"
---
# file: Antigravity — Lilith faucet review (TOONs + orchestration) — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor) — web_path: http://www.lupopedia.com/prompts/antigravity/lilith_faucet_review_toons_and_orchestration

# Antigravity IDE prompt — Work as Lilith faucet: review orchestration + TOONs → Lilith suggestions

**Version:** 4.0.69  
**Channel:** 42 (Lupopedia Development)  
**Intent:** When running this prompt in **Antigravity IDE**, adopt the identity of **Lilith (actor_id 2)** operating through the **Antigravity faucet**. You are not Antigravity-as-actor; you are Lilith (review/edge-and-shadow persona) using Antigravity as the execution surface. Session should reflect `actor_id: 2`, `actor_name: lilith`, `faucet_name: antigravity`. *Alternative:* If the intent is **Wolfie (actor_id 1)** using Antigravity in a Lilith-style review capacity, use `actor_id: 1`, `actor_name: wolfie`, `faucet_name: antigravity` and adopt Lilith’s review/suggestions persona for the task only.

---

## 1. Session and identity

- **Actor identity for this task:** Lilith (actor_id 2).  
- **Faucet:** Antigravity IDE.  
- **Session file (optional):** Create or use `lupo-database/sessions/L-LUPO-LILITH-ANTIGRAVITY.md` with:
  - `actor_id: 2`
  - `actor_name: "lilith"`
  - `faucet_name: "antigravity"`
  - `channel_id: 42`
  - `paired_actor_id: 1000` (or 1 if Wolfie is the paired human).

---

## 2. Required inputs to review

### 2.1 Orchestration and architecture review

- Read **`docs/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md`** in full.
- Note its definitions of orchestration actor, supporting actor, faucet, and its recommendation (e.g. option B: document orchestration vs supporting without schema change).
- Use it to ground your own suggestions (e.g. alignment with actor/faucet/trait/role language, channel vs federation context).

### 2.2 Full TOON inventory

- Review **all** database table TOONs under **`lupo-database/lupopedia/toon/`**.
- TOON files are named `{table_name}.toon` (e.g. `lupo_actors.toon`, `lupo_channels.toon`, `lupo_edges.toon`). There are 160+ such files; the exact count is whatever `scripts/generate_toon_files.py` last wrote.
- For each table (or for a structured subset if you group by domain), note:
  - How it relates to **actors**, **channels**, **edges**, **sessions**, **faucets**, **traits**, **roles**, **semantic** layer, and **federation**.
  - Any naming or grouping that could better support the orchestration/supporting/faucet distinction or the canonical architecture doc.
- You may summarise by table *category* (e.g. actor-related, channel-related, dialog, edges, semantic, rules/tasks, auth/sessions, federation) rather than line-by-line for every TOON.

### 2.3 Canonical architecture

- Skim **`docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md`** so your suggestions align with installation path, fallback, actor/faucet, channel, edges, and full table list there.

---

## 3. Deliverable: single output document

Create **exactly one** new file:

- **Path:** `docs/status/lilith_suggestions_on_database_channels_semantic_organisation.md`

**Required content (sections you must include):**

1. **Header**  
   Valid LUPOPEDIA HEADERS with at least: `artifact_type: "status"`, `artifact_kind: "suggestions"` or `"review"`, `channel_id: 42`, `system_version: "4.0.69"`, `actor_id: 2`, `actor_name: "lilith"`, `faucet_name: "antigravity"`, and an identity line. Session block optional but recommended.

2. **Summary**  
   Short summary of what was reviewed (orchestration doc + full TOON set) and the main themes of your suggestions.

3. **Suggestions from orchestration review**  
   Bullet or numbered list of concrete suggestions that follow from the orchestration/actors/faucets review (e.g. wording in docs, separation of orchestration vs supporting, faucet vs actor language). Reference the orchestration doc where relevant.

4. **Suggestions from TOON / database review**  
   Bullet or numbered list of suggestions based on reviewing all TOONs in `lupo-database/lupopedia/toon/`. Examples:
   - Table groupings or naming that would make “actors vs channels vs semantic vs federation” clearer in documentation.
   - Tables that are central to orchestration/supporting/faucet/session/role/trait and how they might be described in the architecture doc.
   - Any inconsistencies or gaps you notice (e.g. missing tables in the canonical list, or TOONs that don’t match the architecture narrative).
   - No schema or migration changes—suggestions are documentation and organisation only.

5. **References**  
   List the key files you used: orchestration review doc, canonical architecture doc, and the TOON directory path.

**Tone:** Dry, precise, architectural. No hype, no roleplay beyond identifying as Lilith (actor 2) via Antigravity faucet. Do not invent doctrine; ground suggestions in the existing docs and TOONs.

---

## 4. Constraints

- Do **not** modify doctrine files, install SQL, migrations, or TOONs.
- Do **not** change `ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md` or the canonical architecture doc in this task; only produce the new Lilith suggestions file.
- The new file is **suggestions only**; it is not canonical doctrine and should say so in the header or summary.

---

## 5. Quick reference

| Item | Value |
|------|--------|
| Orchestration doc | `docs/status/ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md` |
| TOON directory | `lupo-database/lupopedia/toon/` (all `*.toon` files) |
| Canonical architecture | `docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md` |
| Output file | `docs/status/lilith_suggestions_on_database_channels_semantic_organisation.md` |
| Identity for task | Lilith (actor_id 2), faucet Antigravity |
| Channel | 42 |

Use this prompt in Antigravity IDE to run the review and generate the Lilith suggestions document.

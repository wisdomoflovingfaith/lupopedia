---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/46_A_ACTOR_GATEWAY_TYPES.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/46_A_ACTOR_GATEWAY_TYPES.md"
  status: draft
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/0046/actor-gateway-types.prd.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/prd/46_actor_gateway_types
  artifact_type: prd
  artifact_kind: specification
  channel_key: doctrine
  federation_node_id: 0
  thread_id: null
  content_id: 46
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_46_A_ACTOR_GATEWAY_TYPES
  title: "PRD 46 ??? Actor Gateway Types"
  summary: "Product requirements for the canonical gateway taxonomy used to classify and interact with actors in the Lupopedia multi-agent ecosystem."
---
# PRD 46 ??? Actor Gateway Types

## 1. Purpose

Define the **canonical gateway taxonomy** for all actors in the Lupopedia multi???agent ecosystem and specify how this taxonomy must be used by registries, orchestrators, and tools (including Castcade) to determine interaction patterns, capabilities, and handoff behavior.

This PRD binds the implementation of:

- `gateway` field in the actor registry  
- interaction planning based on gateway type  
- handoff and state???tracking expectations per gateway  

The explanatory doctrine lives in:

- `docs/doctrine/ACTOR_GATEWAY_TYPES.md` 

This PRD is the **normative** source; the doctrine file is **descriptive**.

---

## 2. Scope

**In scope:**

- Definition of allowed `gateway` values
- Semantics and behavior of each gateway type
- Rules for selecting a gateway for an actor
- Rules for evolving gateway types over time
- Requirements for Castcade and other tools when reading/writing actor registry entries

**Out of scope:**

- Per???vendor API details
- Authentication schemes
- Non???gateway actor properties (e.g., `type`, `status`, `channel_key`)

---

## 3. Canonical Gateway Types

The `gateway` field in the actor registry **MUST** be one of the following values:

1. `api_http` 
2. `api_ws` 
3. `local_agent` 
4. `manual_web_chat` 
5. `ide_panel` 
6. `system_daemon` 
7. `batch_script` 

### 3.1 `api_http` 

**Definition:** Actor exposes a REST/HTTP JSON API.

**Requirements:**

- **MUST** have a reachable HTTP/HTTPS endpoint.
- **MUST** accept JSON requests and return JSON responses.
- **MAY** support polling for status.
- **MAY** require authentication (keys/tokens).
- **MUST** be considered programmatically callable by orchestrators.

### 3.2 `api_ws` 

**Definition:** Actor exposes a WebSocket or similar persistent connection API.

**Requirements:**

- **MUST** support persistent, bidirectional connections.
- **MAY** stream responses.
- **MUST** be treated as event???driven and low???latency.
- **MUST** be considered programmatically callable by orchestrators.

### 3.3 `local_agent` 

**Definition:** Actor runs on the same machine with direct file system access.

**Requirements:**

- **MUST** be able to read/write Lupopedia files and TOONs locally.
- **MUST NOT** require network access to function.
- **MAY** run as a daemon, script, or IDE plugin.
- **MUST** be considered fully trusted for local file operations within its trust tier.

### 3.4 `manual_web_chat` 

**Definition:** Actor is accessed via a browser UI with **no** programmatic API; interaction is manual cut???and???paste.

**Requirements:**

- **MUST NOT** be assumed callable or pingable by any orchestrator.
- **MUST** rely on human???mediated cut/paste for prompts and responses.
- **MUST** track state via TOON handoff files and/or notes, not via API calls.
- **MUST** be treated as **asynchronous and manual**.

### 3.5 `ide_panel` 

**Definition:** Actor is integrated into an IDE or terminal UI (e.g., Cursor, Windsurf, Antigravity, Warp).

**Requirements:**

- **MAY** have API backing or be purely UI???driven.
- **MUST** be treated as development???context aware (file/project context).
- **MUST** be considered semi???manual: some actions may be automated, others require human confirmation.
- **MUST** document in `notes` whether it is API???backed, manual, or hybrid.

### 3.6 `system_daemon` 

**Definition:** Actor runs as a background service on the OS.

**Requirements:**

- **MUST** be considered continuously running or event???driven.
- **MAY** monitor system or file events.
- **MUST** be treated as non???interactive (no chat UI).
- **MUST** communicate via files, events, or IPC, not conversational prompts.

### 3.7 `batch_script` 

**Definition:** Actor is a script or tool that runs periodically or on demand to process data and produce TOONs.

**Requirements:**

- **MUST** be considered non???interactive and non???persistent.
- **MUST** read inputs and write outputs in batch mode.
- **MUST** produce TOON or file artifacts as its primary interface.
- **MAY** be scheduled or manually triggered.

---

## 4. Gateway Selection Rules

When registering or updating an actor, tools **MUST** follow this decision process:

1. **Is there a documented programmatic API?**  
   - If REST/HTTP ??? `api_http`  
   - If WebSocket/streaming ??? `api_ws` 

2. **If no API:** Is the actor accessed via a browser chat UI?  
   - Yes ??? `manual_web_chat` 

3. **If not browser chat:** Is the actor integrated into an IDE/terminal UI?  
   - Yes ??? `ide_panel` 

4. **If not IDE:** Does the actor run locally with direct file access?  
   - Yes ??? `local_agent` 

5. **If not local agent:** Does it run continuously in the background?  
   - Yes ??? `system_daemon` 

6. **If not daemon:** Is it a non???interactive batch processor?  
   - Yes ??? `batch_script` 

If none of the above apply, the actor **MUST NOT** be registered until its gateway characteristics are clarified.

---

## 5. Actor Registry Requirements

The actor registry **MUST**:

- Include a `gateway` field for every actor.
- Use only the values defined in ??3.
- Treat `gateway` as **required** for all new actors.
- For existing actors without a gateway, tools **MUST** backfill using the selection rules in ??4.

For manual web/chat actors (e.g., Claude web UI, Gemini web UI):

- `gateway` **MUST** be `manual_web_chat`.
- `notes` **SHOULD** describe the site (e.g., `claude.ai`, `gemini.google.com`).
- `channel_key` **SHOULD** be `external` unless a more specific channel is defined.

---

## 6. Castcade Responsibilities

Castcade **MUST**:

1. **Scan existing actor registry entries** and:
   - Detect missing or invalid `gateway` values.
   - Apply the selection rules in ??4 to assign a valid gateway.
   - Log or note any ambiguous cases for manual review.

2. **Enforce gateway validity**:
   - Reject or flag any actor with a `gateway` not in the canonical list.
   - Prefer correction over silent acceptance.

3. **Use gateway to determine interaction patterns**:
   - `api_http` / `api_ws` / `local_agent`: may be pinged or invoked programmatically.
   - `manual_web_chat`: must be treated as manual; no automated calls.
   - `ide_panel`: may require human confirmation; treat as semi???manual.
   - `system_daemon` / `batch_script`: interact via files/events, not chat.

4. **Respect manual boundaries**:
   - Never attempt to "call" `manual_web_chat` actors.
   - Use TOON handoffs and notes to track their state.

---

## 7. Validation Rules

Validators and tools **MUST** enforce:

1. `gateway` is present and non???null for all actors.
2. `gateway` is one of the seven canonical values.
3. Any new gateway value requires:
   - A PRD update (new section in this document or new PRD).
   - A doctrine update in `ACTOR_GATEWAY_TYPES.md`.
4. Actor entries with invalid or missing `gateway` **MUST** be flagged for repair.

---

## 8. PRD Index Integration

The PRD index file (e.g., `docs/prd/PRD_INDEX.md` or equivalent) **MUST** be updated to include:

- **PRD 46 ??? Actor Gateway Types**  
  - Status: `draft` (or `active` once approved)  
  - Path: `docs/prd/PRD_46_ACTOR_GATEWAY_TYPES.md`  
  - Summary: "Defines the canonical gateway taxonomy and requirements for actor interaction and registry usage."

Once approved, `status` in both the PRD file and index **MUST** be updated to `active`.

---

## 9. Change Management

- Any change to the canonical gateway list or semantics **MUST** go through a PRD???46 revision.
- Doctrine file `ACTOR_GATEWAY_TYPES.md` **MUST** remain consistent with this PRD.
- Tools (including Castcade) **MUST** be updated to respect new or changed gateway rules.

---
lupopedia.footer:
  pending_edges:
    - to: docs/prd/PRD_46_ACTOR_GATEWAY_TYPES.md
      reason: "file created in session and must be linked to PRD"
  notes:
    - "When DB is online, this file's edges must be imported into polymorphic edge table."
---

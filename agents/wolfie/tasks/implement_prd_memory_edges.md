---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: agents/wolfie/tasks/implement_prd_memory_edges.md
  web_path: https://www.lupopedia.com/lupopedia/agents/wolfie/tasks/implement_prd_memory_edges.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: task
  artifact_kind: implementation
  channel_key: coordination
  federation_node_id: 0
  thread_key: prd-memory-edge-implementation
  lupopedia.schema: doctrine
  prd_cluster: null
  title: 'WOLFIE Task: Implement PRD Memory Edges (Option A)'
  summary: null
---

# TASK: Implement PRD Memory Edges (Option A)

## Objective

Generate JSON master + TOON memory pairs for priority PRDs, with edges stored in sidecar `edges` array per PRD 16 section 5.1.

## Priority PRDs (Top 12)

| Priority | PRD | File | Status |
|----------|-----|------|--------|
| 1 | 00 | `00_root_constitutional_system_requirements.md` | Already has memory pair |
| 2 | 16 | `16_lupopedia_headers.md` | Already has memory pair |
| 3 | 38 | `38_memory_unification.md` | Already has memory pair |
| 4 | 01 | `01_core_identity.md` | Needs memory pair |
| 5 | 02 | `02_channels_discussions.md` | Already has memory pair |
| 6 | 15 | `15_actors.md` | Needs memory pair (consolidate with 01) |
| 7 | 07 | `07_agents_faucets.md` | Needs memory pair |
| 8 | 41 | `41_captain_wolfie_identity.md` | Already has memory pair |
| 9 | 36 | `36_rose_multi_persona_synthetic_dialog.md` | Needs memory pair |
| 10 | 37 | `37_kairos_channel_memory_consolidation.md` | Needs memory pair |
| 11 | 05 | `05_auth_user_actor_agent_transformation.md` | Needs memory pair |
| 12 | 33 | `33_softaculous_certification_4_1_0_gate.md` | Needs memory pair |

## Edge Types to Extract

| Edge Type | Direction | Source | Example |
|-----------|-----------|--------|---------|
| `references` | outbound | PRD references another PRD | PRD 16 -> PRD 00 |
| `implements` | outbound | PRD -> Implementation file | PRD 38 -> MemoryExportService |
| `amends` | outbound | PRD amends another PRD | PRD 38 amends PRD 00 section 5.7 |
| `depends_on` | outbound | PRD requires another PRD | PRD 38 depends on PRD 00 |
| `clarifies` | outbound | PRD clarifies doctrine | PRD 16 clarifies header format |
| `has_parent` | outbound | Implementation PRD -> Parent PRD | PRD 41 (implementation) -> PRD 00 |

## Sidecar Edge Format (PRD 16 section 5.1)

Each PRD's `header_metadata` sidecar JSON must include an `edges` array:

```json
{
  "edges": [
    {
      "edge_type": "references",
      "edge_context": "doctrine",
      "edge_status": "supported",
      "edge_direction": "outbound",
      "to": "docs/prd/00_root_constitutional_system_requirements.md",
      "weight_hundredths": 100,
      "review_reason": null
    }
  ]
}
```

### Field requirements

| Field | Required | Allowed Values |
|-------|----------|----------------|
| `edge_type` | Yes | `references`, `implements`, `amends`, `depends_on`, `clarifies`, `has_parent` |
| `edge_context` | Yes | `doctrine` (for PRD relationships) |
| `edge_status` | Yes | `supported` (for frozen PRDs), `needs_review` (for draft PRDs) |
| `edge_direction` | Yes | `outbound` (from this PRD to target) |
| `to` | Yes | Repo-relative path to target file |
| `weight_hundredths` | No | Integer 0-10000 (default 100 = weight 1.00) |
| `review_reason` | Conditional | Required if `edge_status = 'needs_review'` |

## Implementation steps

### Step 1: Identify existing memory pairs

```bash
ls memory/development/canonical/2026/04/*.toon
ls memory/development/canonical/2026/04/*.json
```

Already completed:
- `prd-00-constitutional`
- `16-lupopedia-headers`
- `38-memory-unification`
- `02-channels-discussions`
- `41-captain-wolfie-identity`

### Step 2: For each remaining PRD, generate JSON master

Use the constitutional memory pattern from `prd-00-constitutional.json` as template.

JSON master location:
`memory/development/canonical/2026/04/{prd_slug}.json`

Required tables:
- `metadata` (1 row)
- `prd_sections` (major sections of the PRD)
- `prd_rules` (key requirements/constraints)
- `definitions` (key terms)
- `edges` (outbound relationships)

### Step 3: Extract edges from PRD content

| Source | Edge Type | How to Extract |
|--------|-----------|----------------|
| `lupopedia.edges.outbound_edges` (if present in legacy header) | `references` | Convert to sidecar format |
| "Amendments" section | `amends` | Parse PRD references |
| "Depends On" section | `depends_on` | Parse PRD references |
| "Cross-References" section | `references` | Parse all PRD links |
| `parent_prd` header field | `has_parent` | Direct mapping |

### Step 4: Generate TOON from JSON

```bash
python scripts/json_to_toon.py --json "memory/development/canonical/2026/04/{prd_slug}.json" --toon "memory/development/canonical/2026/04/{prd_slug}.toon"
```

### Step 5: Validate memory pair

```bash
python scripts/validate_memory_json_toon_pair.py --base "memory/development/canonical/2026/04/{prd_slug}"
```

### Step 6: Update PRD index

After generating memory pairs, update PRD index to reflect status of each PRD and optional edge counts.

## Edge tracking rules

### Rule 1: Bidirectional references

If PRD A references PRD B, create edge in A sidecar. Do not auto-create reverse edge in B sidecar unless B also references A.

### Rule 2: Amendment edges

When PRD X amends PRD Y, create `edge_type: "amends"` and include section pointer when known.

### Rule 3: Dependency edges

When PRD X depends on PRD Y, create `edge_type: "depends_on"` from X to Y.

## Validation requirements

After generating memory pairs for all priority PRDs:

1. Run THOTH verification on each PRD memory pair.
2. Ensure all edges have required fields.
3. Verify edge targets exist (no broken references).
4. Run PRD index validation to confirm all PRDs are indexed.

## Success criteria

| Criterion | Validation |
|-----------|------------|
| Priority PRDs have JSON + TOON memory pairs | `ls` shows expected `.json` and `.toon` files |
| Each sidecar has `edges` array with outbound references | Edge arrays are non-empty where references exist |
| All edges have valid `to` paths | Edge target validation passes |
| PRD index shows accurate status for all PRDs | Manual review |
| Validator passes for all memory pairs | `validate_memory_json_toon_pair.py` exits 0 |

## Strict constraints

- No hand-editing TOON files (TOON is generated from JSON master).
- No manual reverse-edge duplication unless explicitly referenced.
- No circular dependencies without explicit justification and review.
- No missing edge contexts (`edge_context` must be `doctrine` for PRD relationships).

## Execution order

1. Generate JSON master for PRD 01 (`core_identity`).
2. Extract edges from PRD 01 (references to PRD 00, PRD 15, PRD 05).
3. Generate TOON from JSON.
4. Validate memory pair.
5. Repeat for PRD 07, 15, 36, 37, 05, 33.
6. Update PRD index with status and edge counts.
7. Run full validation suite.

WOLFIE executes this task in priority order and reports progress after each PRD memory pair is generated.

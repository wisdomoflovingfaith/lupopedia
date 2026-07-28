---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_VALIDATOR_NOTES.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_VALIDATOR_NOTES.md
  status: active
  when_updated: "20260728165511"
  trust_tier: development
  questions_toon: null
  memory_toon: memory/development/canonical/1026/07/16-c-header-format-4-2-0-validator-notes.toon
  atoms_toon: null
  transcript_jsonl: 0/development/16-c-header-format-4-2-0-validator-notes
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C
  title: "Validator notes -- header 4.2.0 Option A"
  summary: "Implementation notes for header_spec and validators: dual-accept 4.1.9 and 4.2.0; 28-field order; Hawaiian densification forbidden. Code changes not required until scheduled."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 102
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: headers
  faucet_actor_id: 102
---
# Validator Notes -- Header Format 4.2.0 (Option A)

**Status:** NOTES ONLY -- do not treat as a completed code change  
**Primary code target:** `scripts/lib/header_spec_v3_1.py`  
**Consumers:** `validate_lupopedia_headers_universal.py`, header adders, pre-commit samples, atoms export  
**Product version:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = **4.2.0** (UNSTABLE) -- see `docs/versions/4.2.0/SYSTEM_STATUS_UNSTABLE.md`

---

## 1. Current state (pre-code)

- `EXPECTED_HEADER_FORMAT_VERSION = "4.1.9"`
- `V4_HEADER_KEYS_ORDERED` length **22**
- Version check is exact-match only
- Discovery docs still say 25-line / 22-field protocol

---

## 2. Required code changes (when scheduled)

### 2.1 Dual version accept

```text
ACCEPTED_HEADER_FORMAT_VERSIONS = frozenset(("4.1.9", "4.2.0"))
CURRENT_HEADER_FORMAT_VERSION = "4.2.0"   # new files / --strict-new
LEGACY_HEADER_FORMAT_VERSION = "4.1.9"
```

- Exact-only `is_expected_version` becomes `is_accepted_version`.
- Optional flag `--require-current` fails 4.1.9 after cutover.

### 2.2 Ordered key tuples

```text
V419_HEADER_KEYS_ORDERED = <existing 22>
V420_HEADER_KEYS_ORDERED = V419 + (
  "actor_id",
  "auth_user_id",
  "department_id",
  "department_key",
  "division_key",
  "faucet_actor_id",
)
```

Select tuple by parsed `header_format_version`.

### 2.3 Envelope line counts

| Version | Markdown dense lines | Comment-grid lines |
|---------|----------------------|--------------------|
| 4.1.9 | 25 | 25 |
| 4.2.0 | 31 | 31 |

Update builders that emit fixed line counts (`build_*_header_block`).

### 2.4 New field validators

| Field | Checks |
|-------|--------|
| `actor_id` | present; int-like; optional registry lookup in `--strict` |
| `auth_user_id` | null or int-like |
| `department_id` | null or int-like |
| `department_key` | string (allow `""`) |
| `division_key` | string (allow `""`) |
| `faucet_actor_id` | null or int-like; WARN if equals `actor_id` without both documented |

### 2.5 Hawaiian densification ban

ERROR if any of these appear as keys under dense `lupopedia.headers`:

`OHANA`, `KAPU`, `KAPAKAI`, `PUKA`, `PONO`, `KULEANA`, `ALII`, `KUMU`, `EH_BRAH_WHY`  
(case-insensitive key match)

Allow them in body / Hermes sidecar only.

### 2.6 Preserve existing 4.1.9 rules

Keep edges_toon / channel_index / source_timestamp / schema equality / atoms path rules for both versions.

---

## 3. Atoms / constants sync

Update `memory/.../lupopedia_global_constants.atom.toon` (and JSON twin if paired):

- `constants.header_fields.count` -> `28` for current
- `constants.header_fields.order` -> 28-key list
- Document legacy count `22` under a `legacy_4_1_9` key if needed

Do **not** hand-edit generated DB JSON schema exports unrelated to headers.

---

## 4. Header adder scripts

`scripts/add_lupopedia_header_to_file.py` (and batch variants):

- Default emit `4.2.0` + 28 keys after validator dual-accept ships
- Identity defaults: pass CLI flags `--actor-id`, `--auth-user-id`, `--faucet-actor-id`, `--department-key`, `--division-key`
- Do not invent department ids

---

## 5. What NOT to do in the notes phase

- Do not flip `EXPECTED_HEADER_FORMAT_VERSION` to 4.2.0 alone without dual-accept (breaks corpus CI)
- Do not run repo-wide rewrite
- Do not add Hawaiian keys to `V420_HEADER_KEYS_ORDERED`

---

## 6. Suggested test cases

1. Valid 4.1.9 22-field file -> PASS (legacy)
2. Valid 4.2.0 28-field file -> PASS
3. 4.2.0 missing `actor_id` -> FAIL
4. 4.2.0 with `KAPU:` in dense block -> FAIL
5. 4.2.0 wrong order (identity before `source_timestamp`) -> FAIL
6. 4.2.0 `faucet_actor_id: 102`, `actor_id: 1` -> PASS
7. Markdown envelope 30 lines (missing close) -> FAIL

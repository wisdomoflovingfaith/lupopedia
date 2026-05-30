---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/lupopedia-headers/questions_toon_schema.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/questions_toon_schema.md
  status: draft
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/questions-toon-schema.toon
  atoms_toon: null
  transcript_jsonl: 0/development/questions-toon-schema
  artifact_type: doctrine
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: 'JSON Schema: .questions.toon File Format'
  summary: JSON Schema (draft-07) for the planned .questions.toon Q&A memory file format (PRD 16 §19)
---
# JSON Schema: `.questions.toon` File Format

**Status:** Draft (system not yet built — schema defines the planned format per PRD 16 §19)

**Parent:** [PRD 16 §19](../../prd/16_lupopedia_headers.md#19-questions-toon-system-planned)

**Path convention:**
```
memory/questions/{channel_key}/{trust_tier}/{display_year}/{MM}/{slug}.questions.toon
```

**Example path (PRD 16):**
```
memory/questions/headers/canonical/1026/04/16-lupopedia-headers.questions.toon
```

---

## JSON Schema (draft-07)

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "$id": "https://www.lupopedia.com/lupopedia/schemas/questions_toon/1.0.0",
  "title": "questions_toon",
  "description": "Q&A memory file for a Lupopedia artifact. Stores all questions asked about a page plus their answers, status, and attribution. Pointed to by the questions_toon header field (PRD 16 v4.0.99 §4.2 field 6).",
  "type": "object",
  "required": ["type", "version", "page_reference", "questions", "statistics"],
  "additionalProperties": false,
  "properties": {

    "type": {
      "description": "File type discriminator. Must be 'questions_memory'.",
      "type": "string",
      "const": "questions_memory"
    },

    "version": {
      "description": "Schema version in semver form (major.minor.patch).",
      "type": "string",
      "pattern": "^[0-9]+\\.[0-9]+\\.[0-9]+$",
      "examples": ["1.0.0"]
    },

    "page_reference": {
      "description": "The Lupopedia artifact this Q&A file belongs to.",
      "type": "object",
      "required": ["file_path"],
      "additionalProperties": false,
      "properties": {
        "content_id": {
          "description": "DB primary key from lupo_contents. null until ANUBIS links the file.",
          "type": ["integer", "null"],
          "minimum": 1
        },
        "file_path": {
          "description": "Repo-relative POSIX path (forward slashes, no leading slash). Must match the file_path_from_root of the referencing header.",
          "type": "string",
          "pattern": "^[A-Za-z0-9_./\\-]+$",
          "minLength": 1
        }
      }
    },

    "questions": {
      "description": "Ordered list of Q&A pairs. Append-only — never delete or reorder existing entries.",
      "type": "array",
      "items": {
        "$ref": "#/definitions/question_entry"
      }
    },

    "statistics": {
      "description": "Computed summary counts. Regenerated on every write — do not hand-edit.",
      "type": "object",
      "required": ["total_questions", "resolved", "pending", "last_activity"],
      "additionalProperties": false,
      "properties": {
        "total_questions": {
          "type": "integer",
          "minimum": 0
        },
        "resolved": {
          "type": "integer",
          "minimum": 0
        },
        "pending": {
          "type": "integer",
          "minimum": 0
        },
        "deprecated": {
          "type": "integer",
          "minimum": 0
        },
        "last_activity": {
          "description": "UTC timestamp YYYYMMDDHHIISS of the most recent write (question or answer).",
          "$ref": "#/definitions/ymdhis_timestamp"
        }
      }
    }
  },

  "definitions": {

    "ymdhis_timestamp": {
      "description": "14-digit UTC timestamp YYYYMMDDHHIISS as a string. Matches Lupopedia BIGINT doctrine.",
      "type": "string",
      "pattern": "^[0-9]{14}$",
      "examples": ["20260414080000"]
    },

    "actor_ref": {
      "description": "Reference to any Lupopedia actor, user, agent, or system that asked or answered a question.",
      "type": "object",
      "required": ["type", "id", "name"],
      "additionalProperties": false,
      "properties": {
        "type": {
          "description": "Category of the entity.",
          "type": "string",
          "enum": ["actor", "user", "agent", "system"]
        },
        "id": {
          "description": "Numeric actor_id (lupo_actors.actor_id) or user id. Required; must be positive integer.",
          "type": "integer",
          "minimum": 1
        },
        "name": {
          "description": "Display name at time of writing. Non-empty string.",
          "type": "string",
          "minLength": 1
        }
      }
    },

    "question_entry": {
      "description": "A single question with its answer and resolution metadata.",
      "type": "object",
      "required": ["id", "asked_by", "asked_at", "question", "status"],
      "additionalProperties": false,
      "properties": {

        "id": {
          "description": "Unique identifier within this file. Format: q001, q002, ... qNNN (zero-padded to 3 digits minimum).",
          "type": "string",
          "pattern": "^q[0-9]{3,}$",
          "examples": ["q001", "q042"]
        },

        "asked_by": {
          "description": "Who submitted this question.",
          "$ref": "#/definitions/actor_ref"
        },

        "asked_at": {
          "description": "UTC timestamp when the question was submitted.",
          "$ref": "#/definitions/ymdhis_timestamp"
        },

        "question": {
          "description": "The question text. Non-empty. Single-line preferred; multi-line allowed for code examples.",
          "type": "string",
          "minLength": 1
        },

        "status": {
          "description": "Current lifecycle state of the question.",
          "type": "string",
          "enum": ["pending", "resolved", "deprecated"]
        },

        "answer": {
          "description": "The answer text. null when status is pending. Non-empty string when status is resolved.",
          "type": ["string", "null"],
          "minLength": 1
        },

        "resolved_by": {
          "description": "Who provided the answer. Required when status is resolved; null otherwise.",
          "oneOf": [
            { "$ref": "#/definitions/actor_ref" },
            { "type": "null" }
          ]
        },

        "resolved_at": {
          "description": "UTC timestamp when the answer was accepted. Required when status is resolved; null otherwise.",
          "oneOf": [
            { "$ref": "#/definitions/ymdhis_timestamp" },
            { "type": "null" }
          ]
        },

        "source_refs": {
          "description": "Optional list of file paths or dialog_transcript slugs that substantiate the answer.",
          "type": "array",
          "items": {
            "type": "string",
            "minLength": 1
          }
        },

        "tags": {
          "description": "Optional topic tags for filtering (lowercase slug, no spaces).",
          "type": "array",
          "items": {
            "type": "string",
            "pattern": "^[a-z0-9][a-z0-9_-]*$"
          }
        }
      },

      "if": {
        "properties": { "status": { "const": "resolved" } },
        "required": ["status"]
      },
      "then": {
        "required": ["answer", "resolved_by", "resolved_at"]
      }
    }

  }
}
```

---

## Validation Rules Summary

| Rule | Constraint |
|------|-----------|
| File must end with `.questions.toon` | Enforced by `HDR_QUESTIONS_TOON_SUFFIX` in the header validator |
| `type` must be `"questions_memory"` | Hard const |
| `version` must be `major.minor.patch` | Semver pattern |
| `page_reference.file_path` — POSIX, no leading slash | Pattern `^[A-Za-z0-9_./\-]+$` |
| `ymdhis_timestamp` — 14-digit string | Pattern `^[0-9]{14}$` |
| `question.id` — sequential `q001`, `q002`, … | Pattern `^q[0-9]{3,}$` |
| `actor_ref.type` — closed enum | `actor`, `user`, `agent`, `system` |
| `question.status` — closed enum | `pending`, `resolved`, `deprecated` |
| When `status == resolved` — `answer`, `resolved_by`, `resolved_at` all required | JSON Schema `if/then` |
| `questions_toon` must not equal `memory_key` | Validator: `HDR_QUESTIONS_TOON_COLLISION` |
| `statistics` counts must equal actual array lengths | Verified at write time; not expressible in JSON Schema |

---

## Path Convention Detail

```
memory/questions/{channel_key}/{trust_tier}/{display_year}/{MM}/{slug}.questions.toon
```

| Segment | Value |
|---------|-------|
| `channel_key` | Matches `channel_key` from the referencing header |
| `trust_tier` | Matches `trust_tier` from the referencing header |
| `display_year` | Calendar year minus 1000 for `canonical` and `seed` tiers (e.g. 2026 → `1026`). Use literal year (`2026`) for `staging` and `archive`. See PRD 16 §8.1 (CHRONOLOGICAL_TRUST_LADDER). |
| `MM` | Zero-padded month (01–12) |
| `slug` | URL-safe slug derived from the file's `pk_slug` or filename without extension |

**Example:** PRD 16 (canonical, headers channel, April 2026):
```
memory/questions/headers/canonical/1026/04/16-lupopedia-headers.questions.toon
```

---

## Implementation Status

- [ ] JSON Schema validation in `validate_lupopedia_headers_universal.py`
- [ ] Q&A submission endpoint (POST)
- [ ] Q&A answer endpoint (POST, actor authority required)
- [ ] Q&A viewer UI (per-page FAQ surface)
- [ ] ANUBIS auto-creation on first question submitted
- [ ] Migration script to back-populate existing dialog threads as resolved Q&A entries
- [ ] `add_lupopedia_header_to_file.py` to populate `questions_toon` path when file already has Q&A

Until the system is built, all files use `questions_toon: null` in their headers.

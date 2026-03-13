# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\api\FLARE_API.md"
  file_hash: "9d743bb1b0f5809198cc93e68d9d954113e9c866bee553166c34c67b1d135a58"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
lupopedia.headers:
  file_path_from_root: "docs/api/FLARE_API.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "api"
  purpose: "Web API specification for FLARE header retrieval and processing"
  dialog_message: "Recommended next step: create actors/1007 profile and align any remaining docs/examples to the required FLARE prologue format."
  mood_rgb: "4B0082"
  traits: ["canonical", "api", "external_interface"]
  tags: ["flare", "api", "web", "headers", "external_agents"]
  lupo_agent: "codex-ide"

lupopedia.footer:
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/api/FLIP_API.md", type: "supersedes", weight: 0.8 }
    - { to: "app/Services/FlareValidatorService.php", type: "implements", weight: 0.8 }
  semantic_tags: ["flare", "api", "web", "headers", "external_interface", "canonical"]
---

## Error Handling

### Migration Warnings

During the migration window (4.0.47-4.1.0), the API emits warnings for legacy usage:

```json
{
    "warnings": [
        "Using legacy flip.headers format - consider migrating to lupopedia.headers",
        "Using legacy flip.footer format - consider migrating to lupopedia.footer"
    ]
}
```

### Validation Errors

Common validation errors and their fixes:

| Error | Fix |
|-------|-----|
| "Missing required field: delegation_chain" | Add `delegation_chain: "1007:10000"` |
| "Invalid actor_id: 1007" | Use valid actor ID from `actors/registry.json` |
| "Invalid artifact_type: invalid" | Use one of: doctrine, guide, directive, broadcast, status, profile |
| "Edge weight out of range: 1.5" | Use weight between 0.5 and 1.0 |

---

## Performance Considerations

- **Caching:** Headers are cached in memory for 5 minutes
- **Database queries:** All queries use prepared statements
- **File system:** Path validation prevents directory traversal attacks
- **Rate limiting:** Planned for 4.0.48 (100 requests/minute per IP)

---

*End of FLARE API specification.*


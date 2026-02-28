# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
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

flare.footer:
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
        "Using legacy flip.headers format - consider migrating to flare.headers",
        "Using legacy flip.footer format - consider migrating to flare.footer"
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


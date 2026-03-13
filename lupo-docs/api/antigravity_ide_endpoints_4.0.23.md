# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\api\antigravity_ide_endpoints_4.0.23.md"
  file_hash: "06477dd05da23224c93477af6077b58a5e5e8d77d589baebdb877ec149fc47a8"
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

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\api\antigravity_ide_endpoints_4.0.23.md"
  file_hash: "3122851ec458fa3ab5ce2d0c0385db41bcf81e52e583c2beab57b0526b08725b"
  file_path_from_root: "docs\api\antigravity_ide_endpoints_4.0.23.md"
  file_hash: "725a379c58d2466b9ce00684d70b4d8e04340119bb56352d01ec9df22f1408ad"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for antigravity_ide_endpoints_4.0.23.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "api", "antigravity_ide_endpoints_4023md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/api/antigravity_ide_endpoints_4.0.23.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /api/antigravity_ide_endpoints_4.0.23
  aliases:
    - /docs/antigravity_ide_endpoints_4.0.23
    - /qa/antigravity+ide+endpoints+4.0.23
  slug: antigravity_ide_endpoints_4.0.23
  slug_encoding: underscore
  base_path: /api
  url_pattern: "/{base}/{slug}"
---

# Antigravity IDE API Endpoints (Lupopedia 4.0.23)

**Purpose**: REST API endpoints required for Antigravity IDE VSX extension integration.

**Status**: Stubbed and documented - NOT fully implemented yet.

---

## 1. Actor Registration Endpoints

### POST /registry/actors/register
**Purpose**: Register new actors in the unified registry.

**Request Body**:
```json
{
    "actor_name": "Antigravity IDE",
    "actor_type": "system_tool", 
    "client_id": "antigravity",
    "metadata": {
        "purpose": "VSX_extension_development",
        "capabilities": ["project_management", "file_editing", "semantic_navigation"],
        "version": "1.0.0"
    }
}
```

**Response**:
```json
{
    "success": true,
    "actor_id": 2001,
    "registry_id": 9002001,
    "message": "Actor registered successfully"
}
```

### GET /registry/actors/lookup
**Purpose**: Lookup actor by client_id or actor_id.

**Query Parameters**:
- `client_id` (optional): Filter by client ID
- `actor_id` (optional): Filter by actor ID

**Response**:
```json
{
    "actors": [
        {
            "actor_id": 2001,
            "actor_name": "Antigravity IDE",
            "actor_type": "system_tool",
            "client_id": "antigravity",
            "is_active": true,
            "created_ymdhis": 20260220000000
        }
    ]
}
```

---

## 2. Channel Messaging Endpoints

### POST /channels/{id}/messages
**Purpose**: Send messages to a specific channel.

**Path Parameters**:
- `id` (integer): Channel ID (e.g., 42 for development)

**Request Body**:
```json
{
    "actor_id": 2001,
    "message_type": "system",
    "content": "Antigravity IDE initialization complete"
}
```

**Response**:
```json
{
    "success": true,
    "message_id": 12345,
    "thread_id": 1003,
    "created_ymdhis": 20260220000000
}
```

### GET /channels/{id}/messages
**Purpose**: Retrieve messages from a specific channel.

**Query Parameters**:
- `limit` (optional): Maximum number of messages to return (default: 50)
- `offset` (optional): Offset for pagination (default: 0)
- `since` (optional): Return messages after this timestamp

**Response**:
```json
{
    "messages": [
        {
            "message_id": 7,
            "thread_id": 1003,
            "actor_id": 2,
            "message_type": "system",
            "content": "Antigravity IDE has been registered...",
            "created_ymdhis": 20260220000000
        }
    ],
    "total": 1,
    "limit": 50,
    "offset": 0
}
```

---

## 3. Semantic Processing Endpoints

### POST /semantic/explain
**Purpose**: Get semantic explanation for a concept or query.

**Request Body**:
```json
{
    "query": "emotional geometry critical vs balancing",
    "context": "lilith_maat_communication_patterns",
    "actor_id": 2001
}
```

**Response**:
```json
{
    "explanation": "Lilith represents critical emotional responses requiring immediate action...",
    "related_atoms": ["emotional_geometry", "lilith_protocol", "maat_protocol"],
    "confidence": 0.85
}
```

### POST /semantic/flip-header
**Purpose**: Generate FLIP header for a file based on semantic analysis.

**Request Body**:
```json
{
    "file_path": "/path/to/file.ext",
    "actor_id": 2001,
    "options": {
        "include_atoms": true,
        "include_relationships": true
    }
}
```

**Response**:
```json
{
    "flip_header": "FLIP: 4.0.22 / 20260220000000 / emotional_geometry / lilith_critical_vs_maat_balancing / 0.85",
    "metadata": {
        "atoms_referenced": 5,
        "relationships_count": 3,
        "confidence_score": 0.85
    }
}
```

### POST /semantic/related
**Purpose**: Find semantically related content.

**Request Body**:
```json
{
    "content_id": 1234,
    "relationship_types": ["implements", "includes", "governs"],
    "max_results": 10
}
```

**Response**:
```json
{
    "related_content": [
        {
            "content_id": 1235,
            "relationship_type": "implements",
            "strength": 0.9,
            "target_content": "emotional_geometry_framework"
        }
    ]
}
```

### POST /semantic/paths
**Purpose**: Find semantic paths between concepts.

**Request Body**:
```json
{
    "source_atom_id": 1,
    "target_atom_id": 3,
    "path_type": "governance"
}
```

**Response**:
```json
{
    "paths": [
        {
            "semantic_path_id": 1,
            "source_page_id": 1,
            "target_page_id": 3,
            "layer": "governance",
            "weight": 1.0,
            "created_at": 20260220000000
        }
    ]
}
```

---

## 4. Authentication & Authorization

All endpoints require:

1. **Valid Session**: User must be logged in with active session
2. **Actor Permissions**: Actor must have appropriate permissions for the requested action
3. **Channel Access**: Actor must be member of the target channel
4. **Rate Limiting**: API calls subject to rate limiting based on actor type

**Headers**:
```
Authorization: Bearer <session_token>
Content-Type: application/json
X-Lupopedia-Actor-ID: <actor_id>
X-Lupopedia-Client-ID: antigravity
```

---

## 5. Error Responses

### Standard Error Format
```json
{
    "success": false,
    "error": {
        "code": "ACTOR_NOT_FOUND",
        "message": "Actor not found or not authorized",
        "details": {
            "actor_id": 2001,
            "requested_action": "channel_message"
        }
    }
}
```

### Common Error Codes
- `ACTOR_NOT_FOUND`: Actor does not exist or is not active
- `CHANNEL_NOT_FOUND`: Target channel does not exist
- `PERMISSION_DENIED`: Actor lacks required permissions
- `INVALID_JSON`: Malformed JSON in request body
- `RATE_LIMIT_EXCEEDED`: Too many requests in time window
- `SESSION_EXPIRED`: User session has expired
- `SEMANTIC_ERROR`: Semantic processing failed

---

## 6. Implementation Notes

### Database Tables Required
- `lupo_actors` - Actor registration and lookup
- `lupo_registry` - Unified actor registry
- `lupo_dialog_threads` - Dialog thread management
- `lupo_dialog_messages` - Message storage
- `lupo_actor_channel_roles` - Channel permissions
- `lupo_actor_channels` - Channel definitions
- `lupo_atoms` - Semantic concepts
- `lupo_semantic_paths` - Path relationships
- `lupo_semantic_relationships` - Content relationships

### Integration Points
- **Actor Registration**: Connects to existing actor/agent system
- **Channel Messaging**: Integrates with dialog system
- **Semantic Processing**: Uses existing atoms and relationships
- **FLIP Headers**: Generates headers for file metadata

### Security Considerations
- All endpoints validate actor permissions
- Rate limiting by actor_id and client_id
- Input sanitization and SQL injection prevention
- Session validation and timeout handling
- Audit logging for all API calls

---

## 7. Next Steps for Implementation

1. **PHP Controller Classes**: Create controller classes for each endpoint group
2. **Middleware**: Implement authentication, rate limiting, and validation
3. **Database Layer**: Ensure all required tables exist and are seeded
4. **Testing**: Unit tests for each endpoint with various actor types
5. **Documentation**: OpenAPI/Swagger specification for auto-generation
6. **Monitoring**: API usage metrics and error tracking

**Status**: Ready for implementation in Lupopedia 4.0.23+

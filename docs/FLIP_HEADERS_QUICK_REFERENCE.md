---
wolfie.headers: {
  file_path_from_root: "docs/FLIP_HEADERS_QUICK_REFERENCE.md",
  system_version: "4.0.44",
  channel_id: 1,
  actor_id: 1002,
  created_ymdhis: 20260224191000,
  updated_ymdhis: 20260224191000,
  message_type: "documentation",
  visibility: "public",
  priority: "medium",
  purpose: "Quick reference guide for FLP (FLIP) headers implementation"
}
flip.footer: {
  outbound_edges: [
    { to: "docs/doctrine/FLIP/FLIP_DOCTRINE.md", type: "references", weight: 1.0 },
    { to: "channels/0/broadcasts/20260224163100_0_10000_minimum_flip_header_requirements.md", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["flip_headers", "quick_reference", "implementation_guide", "4_0_44"]
}
---

# FLP Headers Quick Reference Guide

**Purpose:** Fast reference for implementing FLP (FLIP) headers in .md files  
**Version:** 4.0.44  
**Also Known As:** FLIP Header, WOLFIE Header, CROP Header  

## 🚀 **Minimum Required Header**

```yaml
---
wolfie.headers: {
  file_path_from_root: "path/from/root.md",
  system_version: "4.0.44",
  channel_id: 42,
  actor_id: 1002,
  created_ymdhis: 20260224191000,
  updated_ymdhis: 20260224191000
}
---
```

## 📋 **Common Optional Fields**

```yaml
wolfie.headers: {
  # Required fields (above)
  
  # Optional but recommended
  message_type: "documentation|status_report|directive|broadcast",
  visibility: "public|system|private", 
  priority: "high|medium|low",
  purpose: "Brief description of file purpose",
  to_actor_id: 10000,
  mood_rgb: "4B0082",
  artifact_type: "guide|spec|doctrine",
  traits: ["essential", "comprehensive"],
  hashtags: ["#flip", "#documentation"]
}
---
```

## 🔗 **Standard Footer**

```yaml
flip.footer: {
  outbound_edges: [
    { to: "path/to/file.md", type: "references", weight: 1.0 },
    { to: "directory/", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["tag1", "tag2", "tag3"]
}
---
```

## 📝 **Field Explanations**

### Required Fields
- **file_path_from_root:** Exact path from project root
- **system_version:** Current Lupopedia version (4.0.44)
- **channel_id:** Channel number (0=doctrine, 1=main, 42=development)
- **actor_id:** Author's actor ID (from actors/registry.json)
- **created_ymdhis:** Creation timestamp (YYYYMMDDHHIISS UTC)
- **updated_ymdhis:** Last update timestamp (YYYYMMDDHHIISS UTC)

### Common Optional Fields
- **message_type:** Type of content (documentation, status_report, etc.)
- **visibility:** Who can see this (public, system, private)
- **priority:** Importance level (high, medium, low)
- **purpose:** One-sentence description
- **to_actor_id:** Recipient actor ID

## 🎯 **Channel ID Guide**

| Channel | Purpose | Example Use |
|---------|---------|-------------|
| 0 | System doctrines | channels/0/broadcasts/ |
| 1 | Main documentation | README.md, docs/guides/ |
| 42 | Development | channels/42/threads/ |
| 666 | Quarantine | channels/666/quarantine/ |

## 👥 **Actor ID Quick Reference**

| Actor | ID | Use When |
|--------|-----|----------|
| Captain Wolfie | 10000 | Official directives |
| KIRO | 1001 | Development tasks |
| Windsurf | 1002 | Coordination tasks |
| Antigravity | 1003 | IDE tasks |
| LILITH | 8 | System tasks |

## ⚡ **Common Examples**

### Documentation File
```yaml
---
wolfie.headers: {
  file_path_from_root: "docs/guide/example.md",
  system_version: "4.0.44",
  channel_id: 1,
  actor_id: 1002,
  created_ymdhis: 20260224191000,
  updated_ymdhis: 20260224191000,
  message_type: "documentation",
  visibility: "public",
  purpose: "Example implementation guide"
}
---
```

### Status Report
```yaml
---
wolfie.headers: {
  file_path_from_root: "docs/status/example.md",
  system_version: "4.0.44",
  channel_id: 42,
  actor_id: 1002,
  created_ymdhis: 20260224191000,
  updated_ymdhis: 20260224191000,
  message_type: "status_report",
  visibility: "system",
  priority: "high",
  purpose: "Task completion status"
}
---
```

### Doctrine File
```yaml
---
wolfie.headers: {
  file_path_from_root: "channels/0/broadcasts/example_doctrine.md",
  system_version: "4.0.44",
  channel_id: 0,
  actor_id: 10000,
  created_ymdhis: 20260224191000,
  updated_ymdhis: 20260224191000,
  message_type: "broadcast",
  visibility: "system",
  priority: "critical",
  purpose: "System doctrine definition"
}
---
```

## 🔧 **Timestamp Generation**

### PHP
```php
$timestamp = gmdate('YmdHis'); // 20260224191000
```

### Manual
Format: `YYYYMMDDHHIISS` UTC  
Example: `20260224191000` = Feb 24, 2026, 19:10:00 UTC

## ⚠️ **Common Mistakes to Avoid**

1. **Wrong Actor ID** - Always check actors/registry.json
2. **Wrong Channel ID** - Match directory structure
3. **Old System Version** - Use current version (4.0.44)
4. **Missing Timestamps** - Use BIGINT UTC format
5. **Invalid YAML** - Validate syntax before commit

## 📚 **Reference Documents**

- **Core Doctrine:** `docs/doctrine/FLIP/FLIP_DOCTRINE.md`
- **Minimum Requirements:** `channels/0/broadcasts/20260224163100_0_10000_minimum_flip_header_requirements.md`
- **Validator Service:** `app/Services/FlipHeaderValidatorService.php`
- **Actor Registry:** `actors/registry.json`

---

**Quick Reference Complete** 🎯

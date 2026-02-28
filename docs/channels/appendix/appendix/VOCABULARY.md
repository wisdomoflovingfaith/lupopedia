# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\appendix\appendix\VOCABULARY.md"
  file_hash: "4ef06c68deeb34ca638875477556fac9d4a157a0ad39703b4e4c45392e5aff15"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "VOCABULARY.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "appendix", "vocabularymd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# VOCABULARY.md

## Overview

Standardized terminology for Lupopedia components to ensure consistency across all documentation and governance files.  
Replaces metaphorical or symbolic language with precise engineering equivalents.

## Core States

These definitions clarify system behavior and replace metaphorical descriptions:

- **Synced State**  
  All agents operating within defined parameters; no drift detected.

- **Drift State**  
  One or more agents deviating from expected parameters; requires review or halt.

- **Global Halt Trigger**  
  STOP.flag present; system must pause until human review.

## Terms

| Original Term (if any) | Standard Term       | Definition |
|------------------------|---------------------|------------|
| Serpents               | Agent Vectors       | Independent agent paths in concurrency. |
| Wings                  | Concurrency Layers  | Distributed task handling across agents/IDEs. |
| Alignment              | Synced State        | Agents operating within defined parameters. |
| Divergence             | Drift State         | Deviation from synced parameters requiring halt. |
| Heavens Stop           | Global Halt Trigger | System-wide pause via STOP.flag. |
| Wolfie Header          | Metadata Header     | File provenance and version tracking block. |
| Mystic Framing         | Symbolic Overlay    | Non-engineering descriptors (avoid). |

## Usage Rules

- Apply standardized terms in all documentation and governance files.  
- Manual replacement only; no automated rewrites.  
- Verify terminology during quarterly audits.  

---

## Enforcement Mechanisms

### 1. Term Check Script (Manual Run)

```python
import re

def check_terms(file_path):
    with open(file_path, 'r') as f:
        content = f.read()
        prohibited = ['serpents', 'wings', 'heavens']  # Extend as needed
        for term in prohibited:
            if re.search(term, content, re.IGNORECASE):
                print(f"Warning: Prohibited term '{term}' found")
# Run manually: python check_terms.py <file>
```

### 2. Replacement Guide

- Perform replacements manually in your IDE.  
- Log changes using your existing `log_change` function with `auth_user`.  

### 3. Standardization Hook

- Add `check_terms` to your pre‑commit workflow.  
- Trigger manually before committing changes.  

### 4. Cleanup Steps

- Identify older doctrine files containing legacy terminology.  
- Update manually using the vocabulary table.  
- Log all changes.  

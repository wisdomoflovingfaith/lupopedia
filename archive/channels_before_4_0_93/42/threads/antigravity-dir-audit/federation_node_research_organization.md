---
lupopedia.headers:
  file_path_from_root: "channels/42/threads/antigravity-dir-audit/federation_node_research_organization.md"
  lupopedia.schema: "message"
  artifact_type: "discussion"
  purpose: "Brainstorming optimal organization for federation_node research in research"
---

# Channel 42 Thread: Federation Node Research Organization

**Topic**: Organizing external `federation_node` research within `research/`
**Date**: 2026-04-01

Based on your clarification that `research/` is dedicated to external `federation_node` research on external documentation, we need an architecture that clearly separates our internal Doctrines (in `rules/root/`) from the external libraries and node protocols we are ingesting. 

Currently, `bmad_method/` and `doom_emacs/` sit at the root of `research/`. If we expand the federation parsing, this will get messy.

### Proposed Folder Structure for `research`

I propose we establish a formal hierarchy that categorizes external research by `node_type` or `integration_domain`.

```text
research/
+-- federation_nodes/          # The core hub for external research ingestion
|   +-- _templates/            # Standardized .md and YAML schemas for incoming node data
|   +-- doom_emacs/            # Move existing doom_emacs here
|   +-- bmad_method/           # Move existing bmad_method here
|   +-- [future_node]/         # New nodes spawn here
+-- whitepapers/               # Theoretical schemas for Lupopedia federation
+-- external_apis/             # Rest API payload documentation for external services
```

### Proposed Workflow (The "Federation Intake" Pipeline)
Since we want to avoid `research` becoming another "dumping ground" like the old `status/` directory, we should implement a strict **Intake Doctrine**:

1. **The Node Directory**: Every external source must live in an isolated directory (e.g., `research/federation_nodes/doom_emacs/`).
2. **The `MANIFEST.md`**: Each node directory must contain a `MANIFEST.md` outlining exactly *what* external documentation was parsed, the date of ingestion, and the target application for Lupopedia.
3. **The Metadata Isolation**: Node data shouldn't be executed directly by AI IDEs—it should be used strictly as RAG (Retrieval-Augmented Generation) context. We should ensure `.cursorrules` or similar rules explicitly treat `research/` as "read-only context text" rather than executable instructions, preventing external documentation from accidentally overriding our internal WOLFIE Doctrine.

### Feedback
What do you think of formalizing the `federation_nodes/` sub-level and requiring a `MANIFEST.md` for each foreign research node? If you agree, I can draft a small PRD for it to lock it into our architecture!

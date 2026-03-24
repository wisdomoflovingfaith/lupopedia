---
lupopedia.headers:
  version_when_written: "4.0.86"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE"
  title: "LUPOPEDIA HEADERS Verification Guide"
  delegation_chain: "junie:root"
  artifact_type: "doctrine"
  artifact_kind: "guide"
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260324"
  last_verified_by: "junie"
  orchestrator: "junie:root"
  next_action:
    - "Integrate verification guide into agent system prompts"
    - "Validate all core doctrine files against this guide"
---
# file: LUPOPEDIA HEADERS Verification Guide — delegation: junie:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE

# LUPOPEDIA HEADERS Verification Guide (v4.0.86+)

This guide defines the process for verifying Lupopedia artifacts for correctness and doctrine compliance.

## 1. The Verification Mandate

Every artifact in the Lupopedia repository that bears a `lupopedia.footer` MUST be verified. Verification is the process of auditing the artifact's content, metadata, and relationship to the system state to ensure accuracy, consistency, and doctrinal integrity.

## 2. Expanded Identity Model in Verification

Verification must account for the five-layer identity model:

1.  **Department:** The logical grouping (e.g., `root`, `security`, `content`).
2.  **Actor:** The operational identity (e.g., `WOLFIE`, `JUNIE`, `LILITH`).
3.  **Agent:** The AI model or implementation (e.g., `gemini-3-flash-preview`).
4.  **Faucet:** The execution surface or interface (e.g., `cursor`, `windsurf`, `web`).
5.  **Auth User:** The human operator or account linked to the actor (e.g., `root` user 0).

In the `lupopedia.footer`, the `last_verified_by` field should typically name the **Actor** (e.g., `"junie"`) or the **Faucet** (e.g., `"cursor"`) depending on which layer is claiming responsibility for the audit.

## 3. Verification Checklist (How to Verify a Doc)

When an agent (or human) verifies an artifact, they MUST check the following:

### 3.1 Metadata Integrity
- [ ] **Headers Block:** Is `lupopedia.headers` present and correctly formatted?
- [ ] **Version Semantics:** Does `version_when_written` reflect the correct baseline? (Stable since 4.0.84).
- [ ] **Path Accuracy:** Does `file_path_from_root` match the actual file location?
- [ ] **Web Path:** Is the `web_path` fully qualified and subdirectory-aware (includes `LUPOPEDIA_BASE_URL`)?
- [ ] **Identity Line:** Does the first line of the body match the headers?

### 3.2 Content Correctness
- [ ] **Doctrine Compliance:** Does the content follow active Lupopedia doctrines (no FKs, BIGINT timestamps, etc.)?
- [ ] **Ground Truth Alignment:** If the doc describes code or schema, does it match the actual repository state (TOONs, SQL, PHP)?
- [ ] **No Contradictions:** Is the content consistent with other related doctrine files and version-specific TODOs?
- [ ] **Semantic Clarity:** Are terms used according to the ROSE semantic model (one field = one meaning)?

### 3.3 Relationship Mapping (Edges)
- [ ] **Edges Block:** For active table docs, is the `lupopedia.edges` block present and verbose?
- [ ] **Traceability:** Are outbound edges accurate and grounded in evidence?

## 4. Recording Verification

Once verified, the agent MUST update the `lupopedia.footer`:

1.  **`last_verified`**: Set to the current UTC date in `YYYYMMDD` format.
2.  **`last_verified_by`**: Set to your identity (e.g., `"junie"`).
3.  **`orchestrator`**: Set to the delegation chain (e.g., `"junie:root"`).
4.  **`next_action`**: Provide 1–3 clear, actionable follow-up steps.

## 5. Automation and Tooling

Verification should ideally be supported by the `lupo-scripts/verify_headers.py` or equivalent IDE extensions. However, **manual audit** of the semantic content is required before claiming verification.

---
**Note:** Verification is not just "saving the file". It is a claim of accuracy. False verification undermines repository trust.

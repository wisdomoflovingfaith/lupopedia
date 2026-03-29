---
lupopedia.headers:
  lupopedia.schema: directive
  file_path_from_root: "lupo-docs/doctrine/LILITH_EXTERNAL_RESEARCH_DIRECTIVE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LILITH_EXTERNAL_RESEARCH_DIRECTIVE.md"
  federation_node_id: 0
  last_modified_utc: "20260328160000"
  when_updated: "20260328160000"
  channel_id: 42
  thread_id: "external-research-analysis"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: directive
  artifact_kind: execution
  purpose: "Establish protocol for LILITH to conduct external web-based research using DeepSeek chat"
  tags:
  - "lilith"
  - "external_research"
  - "deepseek"
  - "web_analysis"
  - "directive"

lupopedia.edges:
  outbound_edges:
    - to: "lupo-scripts/validate_lupopedia_headers_universal.py"
      type: references
      weight: 1.0
      reason: "Header validation implementation"
    - to: "lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Header doctrine for validation rules"
    - to: "lupo-docs/doctrine/EXTERNAL_RESEARCH_DOCTRINE.md"
      type: informs
      weight: 0.8
      reason: "Guidelines for web-based research tools"

lupopedia.footer:
  last_verified: "20260328160000"
  last_verified_by: "lilith"
  last_verified_by_actor_id: 2
  orchestrator: "wolfie:root"
  next_action:
    - "Test protocol with actual DeepSeek chat session"
    - "Create validation checklist for external research findings"
    - "Document limitations and security considerations"

---

 

## OBJECTIVE

Establish protocol for LILITH to conduct external web-based research and analysis using DeepSeek chat interface, ensuring proper attribution, audit trail, and integration with Lupopedia's federation system.

---

## AUTHORITY & SCOPE

**LILITH Authority:**
- Primary reviewer and auditor for Lupopedia
- Authority to validate external research findings
- Responsible for maintaining audit integrity
- Can delegate research tasks but retains final approval

**Research Scope:**
- External web sources, APIs, and documentation
- Third-party tools and services (e.g., DeepSeek Chat)
- Competitive analysis and market research
- Technology evaluation and assessment
- Security analysis of external dependencies

---

## DEEPSEEK CHAT PROTOCOL

### Access Method
- **Web Interface:** https://chat.deepseek.com/
- **Authentication:** API key or account credentials
- **Session Management:** Document session IDs for reproducibility
- **Rate Limits:** Track and respect API quotas

### Research Workflow

```yaml
lupopedia.history:
  - event_id: 1
    event_type: research
    event_date: "20260328160000"
    actor_id: 2
    actor_name: "lilith"
    faucet_slug: "cursor"
    description: "Initiated external research via DeepSeek chat"
    reason: "Evaluate DeepSeek capabilities for research tasks"
    external_source:
      tool: "deepseek_chat"
      url: "https://chat.deepseek.com/"
      session_id: "ds_session_12345"
      query: "Capabilities and limitations for technical research"
  
  - event_id: 2
    event_type: analysis
    event_date: "20260328163000"
    actor_id: 2
    actor_name: "lilith"
    faucet_slug: "cursor"
    description: "Analyzed DeepSeek response patterns and accuracy"
    reason: "Assess reliability for research integration"
    findings:
      - "DeepSeek provides coherent technical responses"
      - "Some limitations in real-time data access"
      - "Requires careful prompt engineering"
    resolution: "Approved for limited research tasks"
  
  - event_id: 3
    event_type: audit
    event_date: "20260328170000"
    actor_id: 2
    actor_name: "lilith"
    faucet_slug: "cursor"
    description: "Final audit of DeepSeek research protocol"
    result: "approved_for_limited_use"
    recommendations:
      - "Use for initial research and fact-checking"
      - "Cross-verify critical technical claims"
      - "Maintain human oversight for final analysis"
```

---

## ATTRIBUTION REQUIREMENTS

### For External Research Sources

When using DeepSeek Chat or similar web-based tools:

1. **Document Access Method**
   ```yaml
   external_source:
     tool: "deepseek_chat"
       url: "https://chat.deepseek.com/"
       session_id: "[session_id]"
       authentication: "[method]"
   ```

2. **Preserve Query-Response Pairs**
   - Store both the question asked and response received
   - Include timestamp and any relevance scoring provided
   - Note any confidence levels or uncertainty indicators

3. **Citation Format**
   ```yaml
   citations:
     - source: "deepseek_chat"
       date: "2026-03-28T16:30:00Z"
       query: "[research question]"
       response_summary: "[brief summary]"
       confidence: "high/medium/low"
       url: "https://chat.deepseek.com/share/[share_id]"  # If available
   ```

---

## VALIDATION REQUIREMENTS

### LILITH Must Verify

1. **Source Authenticity**
   - Verify the tool is who it claims to be
   - Check for official documentation vs marketing claims
   - Assess data freshness and update frequency

2. **Response Accuracy**
   - Cross-check technical claims against primary sources
   - Verify code examples and command syntax
   - Identify hallucinations or fabricated information

3. **Bias Assessment**
   - Evaluate for commercial bias or promotional content
   - Check for balanced perspective presentation
   - Assess completeness of information

4. **Security Evaluation**
   - Review any security implications of findings
   - Assess privacy implications of data shared
   - Verify no sensitive information is disclosed

---

## INTEGRATION WITH LUPOPEDIA

### Research Storage

Store external research findings in `lupo-content/federation_node_id/3/` directory:

```
lupo-content/federation_node_id/3/deepseek_analysis_YYYYMMDD.md
├── lupopedia.headers
│   ├── federation_node_id: 3
│   ├── web_path: "https://chat.deepseek.com/"
│   └── [other header fields]
├── lupopedia.history
│   └── [research events as shown above]
├── lupopedia.edges
│   └── outbound_edges to source material
└── [research content]
```

### Cross-Reference Integration

Link external research to internal Lupopedia artifacts:

```yaml
lupopedia.edges:
  outbound_edges:
    - to: "lupo-content/federation_node_id/3/deepseek_analysis_20260328.md"
      type: references
      weight: 1.0
      reason: "DeepSeek analysis of external research capabilities"
    - to: "lupo-rules/root/EXTERNAL_RESEARCH_DOCTRINE.md"
      type: informs
      weight: 0.8
      reason: "Guidelines for web-based research tools"
```

---

## CRITICAL FINDINGS TEMPLATE

For LILITH's audit reports on external research:

```yaml
lupopedia.history:
  - event_id: [n]
    event_type: audit
    event_date: "YYYYMMDDHHIISS"
    actor_id: 2
    actor_name: "lilith"
    faucet_slug: "cursor"
    description: "[Brief audit description]"
    findings:
      reliability_assessment: "[high/medium/low]"
      accuracy_score: "[0-100]"
      bias_detected: "[yes/no/partial]"
      security_concerns: "[list of concerns]"
      recommendation: "[approved/conditional/rejected]"
    result: "[final_audit_verdict]"
```

---

## LIMITATIONS & CAVEATS

### Tool Limitations
- **Real-time Data:** May not have access to latest information
- **Context Window:** Limited conversation history per session
- **API Rate Limits:** Respect hourly/daily quotas
- **Model Knowledge:** Cutoff date may affect accuracy

### LILITH Mitigation Strategies
1. **Triangulation:** Use multiple external sources to verify claims
2. **Human Oversight:** Critical technical decisions require human validation
3. **Progressive Disclosure:** Clearly mark AI-generated vs verified content
4. **Audit Trail:** Document all uncertainties and limitations

---

## APPROVAL WORKFLOW

1. **Research Request** → LILITH reviews and approves
2. **External Analysis** → LILITH monitors and validates
3. **Integration** → LILITH ensures proper Lupopedia integration
4. **Final Report** → LILITH provides audit with recommendations

---

**LILITH (actor_id 2)** — External web-based research must be properly attributed, validated, and integrated with Lupopedia's federation system while maintaining security and audit integrity. Use this protocol for all DeepSeek and similar tool interactions.

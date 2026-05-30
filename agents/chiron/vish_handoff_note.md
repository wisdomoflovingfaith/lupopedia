# CHIRON to VISH Handoff Protocol

## Handoff Triggers

CHIRON must hand off to VISH when encountering:

### 1. Collection Drift
- **Symptom**: Collection boundaries are unclear or shifting
- **Action**: Document drift patterns, propose stabilization approach
- **Example**: "Documentation spans multiple potential collections with unclear boundaries"

### 2. Hierarchy Ambiguity
- **Symptom**: Multiple valid hierarchical structures exist
- **Action**: Document all valid structures, mark preferences
- **Example**: "Could be organized by topic, timeline, or audience - all valid"

### 3. Multiple Valid Grouping Schemes
- **Symptom**: Content fits equally well in different grouping schemes
- **Action**: Provide analysis of each scheme's merits
- **Example**: "Technical docs could group by component or by workflow stage"

### 4. Unclear Tab Architecture
- **Symptom**: Tab structure doesn't match content organization
- **Action**: Map content to potential tab structures
- **Example**: "Content suggests tabs A, B, C but existing tabs are X, Y, Z"

### 5. Cross-Collection Conflicts
- **Symptom**: Content belongs to multiple collections with conflicting rules
- **Action**: Document conflicts and resolution options
- **Example**: "Security docs conflict between IT and Development collections"

## Handoff Format

### Required Information
```markdown
## CHIRON Handoff to VISH

### Issue Type
[Collection Drift | Hierarchy Ambiguity | Multiple Grouping | Tab Architecture | Cross-Collection]

### Content Summary
[Brief description of content being processed]

### Structural Analysis
[Detailed analysis of structure discovered]

### Candidate Groupings
[List of proposed groupings with reasoning]

### Conflicts Identified
[Description of conflicts or ambiguities]

### CHIRON Recommendation
[Preferred approach with reasoning]

### Open Questions
[Questions for VISH to resolve]
```

## Example Handoff

```markdown
## CHIRON Handoff to VISH

### Issue Type
Hierarchy Ambiguity

### Content Summary
Legacy API documentation mixed with user guides and technical specifications

### Structural Analysis
- 3 main content types identified: API specs, user guides, technical docs
- Current organization mixes all types together
- Clear separation possible but multiple valid hierarchies exist

### Candidate Groupings
1. **By Audience**: Beginners (user guides) vs Advanced (API specs, technical docs)
2. **By Function**: Reference (API specs) vs Learning (user guides, technical docs)
3. **By Technical Level**: User-facing (user guides) vs System-facing (API specs, technical docs)

### Conflicts Identified
- Technical docs could fit in either "Learning" or "System-facing" categories
- API specs serve both reference and learning purposes

### CHIRON Recommendation
Group by Audience (Option 1) as it aligns with user mental models

### Open Questions
- Should technical docs be split or kept together?
- How to handle cross-references between groupings?
```

## Handoff Process

### 1. Preparation
- Document all structural findings clearly
- Identify specific trigger condition
- Prepare candidate groupings with reasoning
- Note any open questions

### 2. Handoff Execution
- Use proper handoff format
- Include all required information
- Mark clearly as CHIRON -> VISH handoff
- Provide context for VISH decision-making

### 3. Follow-up
- Monitor VISH resolution
- Learn from VISH decisions
- Update structural analysis based on VISH output
- Document patterns for future reference

## Coordination Notes

### VISH Authority Areas
- Final collection hierarchy decisions
- Tab architecture specifications
- Cross-collection conflict resolution
- Collection boundary definitions

### CHIRON Support Role
- Provide structural analysis and data
- Offer candidate solutions with reasoning
- Document discovered patterns
- Support VISH decision-making with evidence

### Communication Protocol
- CHIRON: "Handing off to VISH for [issue type]"
- VISH: "VISH taking over from CHIRON for [resolution]"
- CHIRON: "CHIRON acknowledging VISH resolution: [summary]"

## Memory Integration

### Handoff Records
- All handoffs must be recorded in CHIRON memory
- Include VISH resolution outcomes
- Track patterns for future reference
- Update doctrine reference based on learnings

### Learning Loop
- Document successful handoff patterns
- Note VISH decision preferences
- Refine structural analysis techniques
- Improve candidate grouping proposals

---
**Protocol Version**: 1.0.0  
**Last Updated**: 20260423181900  
**Agents**: CHIRON -> VISH  
**Status**: Active Handoff Protocol

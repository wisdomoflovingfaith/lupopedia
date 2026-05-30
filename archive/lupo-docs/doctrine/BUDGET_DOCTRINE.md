---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/doctrine/BUDGET_DOCTRINE.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/doctrine/BUDGET_DOCTRINE.md"
  status: "active"
  when_updated: "20260417060943"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: governance
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Budget Doctrine -- Lupopedia"
  summary: "Canonical budget constraints and efficiency requirements for token and agent operations."
---
# Budget Doctrine -- Lupopedia

## Current Reality (April-May 2026)

Corporate funding provides **$600 total** for token and API costs through June 1, 2026. This covers active development, evaluation, and documentation acceleration.

## Post-June 2026

After June 1, Lupopedia is self-funded by the founder. The hard monthly cap is **$50**.

### Revised Monthly Allocation ($50)

| Tier | Agents | Cost | Purpose |
|------|--------|------|---------|
| **Paid primary** | Claude Code | $17 | Primary reasoning, documentation, and complex tasks |
| **Free tier (keep all)** | Castcade, Antigravity, Windsurf, Gemini (web), ChatGPT (web), DeepSeek (web), Grok (web), Copilot (if free) | $0 | Simple tasks, drafts, quick reviews, parallel work, experiments |
| **Runtime API budget** | Web interface API calls | $33 | User-facing runtime inference budget |
| **Total** |  | **$50** | Hard cap. Cannot exceed. |

**Decision:** keep all free-tier agents. Paid agents are for heavy lifting.

### What This Means For Engineering

| Constraint | Implication |
|------------|-------------|
| Paid capacity is fixed ($17 Claude only) | Reserve Claude for reasoning and complex/high-value tasks. |
| Free tiers are rate-limited | Route simple and parallel tasks to free tiers first. |
| Runtime API budget is explicit ($33) | Prioritize cheap/free providers so runtime stays within cap. |
| Multi-agent orchestration | Handoff toons prevent rework and token waste across paid and free agents. |
| Exploration vs production | Exploration must be complete by June 1. After that, only production-necessary work. |
| Translation channel | Reusable explanations prevent repeated token spend on the same narrative. |

### What This Does NOT Mean

- Not a reduction in quality
- Not a panic-driven cut
- Not a sign of technical failure
- Not a reason to skip handoff toons or documentation

### What This DOES Mean

- Exploration phase ends June 1
- Production phase begins
- Efficiency is now a constitutional requirement
- Every token counts
- Free agents remain active as force multipliers

## Free Tier Strategy

### Work-type routing

| Work Type | Use | Why |
|-----------|-----|-----|
| Heavy reasoning and architecture | Claude Code (paid) | Highest-value reasoning and documentation anchor |
| Implementation and routine coding | Free IDE chain | Costs $0 with handoff continuity |
| Parallel simple tasks | Castcade, Antigravity, Windsurf (free) | They can run while paid agents focus on critical work |
| Drafts and quick reviews | ChatGPT, Gemini (free) | Good enough and zero direct cost |
| Research and analysis | DeepSeek, Grok (free) | Additional depth without paid-token pressure |
| Emergency backup | Copilot (free) | Fallback when other services are rate-limited |

### Cross-tier handoff rule

Paid and free agents must use the same handoff toon discipline. The handoff system is cross-tier and mandatory:

1. Claude Code (design/reasoning)
2. Cursor free tier (implementation)
3. Castcade free (fallback implementation)
4. Antigravity/Windsurf free (continuation)
5. Gemini/DeepSeek free (review/audit)

This preserves continuity and prevents costly restarts when free tiers hit limits.

## External Communication Guidance

Leadership summary:

> The $50/month cap uses one paid primary reasoning agent (Claude Code at $17). IDE execution is handled through free tiers chained with handoff toons. The remaining $33 is reserved for user-facing runtime API calls, using free and low-cost provider chaining before premium paid fallback.

## OpenAI API Cost Reality

OpenAI API usage is paid. There is no standing free tier for production API usage. Trial credits, when available, are temporary and not a budget strategy.

### Cost impact scenarios

| Scenario | Cost impact |
|----------|-------------|
| Current plan (Cursor + Claude Code + free web tiers) | $50/month |
| Add OpenAI API (light usage) | +$10 to +$20/month |
| Add OpenAI API (moderate usage) | +$30 to +$50/month |
| Add OpenAI API (heavy usage) | +$100+/month |

If OpenAI API is required, the $50 cap is at risk unless usage is strictly constrained.

### Mandatory controls when API usage exists

1. Use the cheapest acceptable model tier first (for example, mini-tier models before premium models).
2. Cache reusable responses aggressively to reduce repeated calls.
3. Limit API calls to essential operations only.
4. Prefer cheaper alternatives when output quality is acceptable (DeepSeek API, Groq, Together.ai, local models).
5. Track monthly API spend and stop non-essential calls when approaching cap.

### Contingency budget shape (API-required mode)

| Item | Cost |
|------|------|
| Claude Code | $17 |
| Runtime API budget | $33 |
| **Total** | **$50** |

This mode is viable only with strict limits and disciplined caching.

## Runtime API Cost Strategy (Post-June 1)

### Phase distinction (mandatory)

| Phase | Token/API cost profile | Payer |
|------|-------------------------|-------|
| Development (now to June 1) | High exploration and build activity | Corporate budget |
| Runtime (after June 1) | User-driven inference calls only | Product runtime model |

Development and runtime costs are tracked separately. Post-June budget controls focus on runtime API calls, not development exploration volume.

### Runtime-first provider chain

Use a cost-first provider chain for user-facing runtime calls:

1. Free-tier or lowest-cost provider (where available and acceptable)
2. Cheap paid provider
3. Secondary free-tier provider
4. Premium paid provider as last resort

Recommended default posture:
- Prefer Groq/Gemini/free-tier providers first when request class allows
- Use low-cost paid providers (for example DeepSeek-tier pricing) next
- Reserve OpenAI-class premium providers for explicit fallback scenarios

### Business model guardrail

Lupopedia runtime should not default to subsidized API spend:

| Deployment type | API payer model |
|-----------------|-----------------|
| Self-hosted | User provides API keys (BYOK) |
| Hosted/SaaS | Subscription covers provider spend |

The platform should support user-supplied keys and provider selection to avoid forcing a single paid vendor path.

### Config loader and provider-chain contract

Runtime provider selection must be driven by local install configuration, not hardcoded vendor calls in request handlers.

Canonical runtime loader service:
- `app/Services/ApiProviderChainService.php`

Contract requirements:
1. Read provider keys from install-local configuration or environment.
2. Resolve provider order by request class (`default`, `complex`, `audit`).
3. Use fallback only on retry-eligible failures (429/5xx/timeouts).
4. Block premium fallback providers once spend threshold is reached.
5. Return sanitized runtime configuration for diagnostics (never expose full API keys).

Installer/BYOK expectation:
- Self-hosted users supply keys for their own instance (BYOK).
- Hosted deployments enforce subscription-backed provider spend policies.

## Historical Context

This budget was established in the corporate meeting of April 16, 2026. Leadership funded $600 for evaluation and development through June 1. After that, the founder self-funds at $50/month.

The system is designed to operate within these constraints. The translation channel, handoff toons, and staged memory are not just architectural choices -- they are budget survival mechanisms.

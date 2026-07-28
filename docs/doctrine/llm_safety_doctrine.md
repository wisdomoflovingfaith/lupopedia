---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/llm_safety_doctrine.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/llm_safety_doctrine.md"
  status: "active"
  when_updated: "20260620162738"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/llm-safety-doctrine"
  artifact_type: "doctrine"
  artifact_kind: "guide"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "llm-safety-doctrine"
  default_collection_id: null
  lupopedia.schema: "doctrine"
  prd_cluster: "12_C-i_MULTI_API_AGENT_INTEGRATION"
  title: "LLM Safety Doctrine"
  summary: "Operational safety rules for BYOK LLM calls: budgets, fallback, logging, no key exposure."
---
# LLM Safety Doctrine

Anchors: **PRD 42-B** (`docs/prd/42_llm_provider_integration.md`), **PRD 12_C** (Multi-API Agent Integration).

## 1. API keys

- Keys are stored only in `lupopedia-config.php` on the operator server (BYOK).
- Never log full API keys. Mask in diagnostics (first/last four chars only).
- Never commit config files to version control.

## 2. Budget and spend

- Respect `monthly_budget_cap_usd` and per-provider budgets from install config.
- Call `$GLOBALS['lupo_api_track_spend_hook']` after successful paid API usage.
- Premium providers (OpenAI, Anthropic) block when spend crosses `premium_provider_block_threshold_usd`.

## 3. Fallback

- Use `ApiProviderChainService::sendWithModelFallback()` or explicit chain resolution.
- Fallback on rate limit (429), timeout, and 5xx only (see service `shouldFallback`).
- Do not infinite-retry the same provider/model pair in one request.

## 4. Model selection

- Request classes: `default`, `complex`, `audit`.
- Model names come from config `models[provider][request_class]`, not hardcoded in call sites.
- Reasoning models (e.g. `deepseek-reasoner`) are for complex/audit classes unless operator disables reasoning mode.

## 5. Agent routing

- Per-agent overrides live in `agent_llm_configs` (provider, model_name, safety_json fallback).
- Runtime must merge agent row with global provider config; agent row wins for model/provider when active.

## 6. Logging

- Log provider slug, model, token counts, latency_ms, and error_message -- not message bodies containing secrets.
- Persist spend and usage under `lupo-memory/budgets/` per existing chain service doctrine.

## 7. Fail closed

- Missing API key: return structured error, do not call provider endpoint.
- No enabled provider in chain: fail with explicit message, do not fabricate LLM output.

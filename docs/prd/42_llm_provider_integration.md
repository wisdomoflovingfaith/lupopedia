---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/prd/42_llm_provider_integration.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/42_llm_provider_integration.md"
  status: "active"
  when_updated: "20260620162738"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/prd-42-llm-provider-integration"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "42-llm-provider-integration"
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "12_C-i_MULTI_API_AGENT_INTEGRATION"
  title: "PRD 42-B: LLM Provider Integration (Installer and Runtime)"
  summary: "Installer model config, agent_llm_configs seeding, src/LLM provider adapters, ApiProviderChainService model routing."
---
# PRD 42-B: LLM Provider Integration

Anchors: **PRD 12_C** (Multi-API Agent Integration), **PRD 27** (Installer), **PRD 07** (Agents and Faucets).

> Note: PRD group **42** also covers content seeding (`42_A-i_CONTENT_SEEDING_AND_TRUTH_TABLES.md`). This document is the **LLM provider integration** supplement under the same numeric group per install/runtime workstream naming.

## 1. Purpose

Complete LLM integration for fresh installs:

- BYOK provider keys and budgets (existing)
- **Model selection** per provider and request class
- **Provider abstraction** under `src/LLM/`
- **Per-agent routing** in `agent_llm_configs`
- **Runtime model fallback** via `ApiProviderChainService`

## 2. Architecture

### 2.1 Provider layer (`src/LLM/`)

| Component | Role |
|-----------|------|
| `LLMProviderInterface` | Contract: `send`, `getCapabilities`, `getHealth`, `getProviderSlug` |
| `LLMResponse` | Normalized completion envelope (content, tokens, latency) |
| `LLMHealthStatus` | Key presence and capability probe |
| `LLMProviderFactory` | Slug to adapter (DeepSeek first) |
| `Providers/DeepSeekProvider` | `deepseek-chat`, `deepseek-reasoner` |

### 2.2 Runtime chain (`app/Services/ApiProviderChainService.php`)

- `getModelsConfig()` -- read `models` block from `$GLOBALS['LUPO_API_PROVIDER_CONFIG']`
- `getModelForProvider($provider, $requestClass)` -- resolve model string
- `resolveModelChain($requestClass)` -- ordered provider/model pairs
- `sendWithModelFallback($messages, $options)` -- try chain with spend-safe fallback

### 2.3 Config shape (`lupopedia-config.php`)

```php
$GLOBALS['LUPO_API_PROVIDER_CONFIG'] = array(
    'provider_order' => array('gemini', 'deepseek', 'groq'),
    'models' => array(
        'deepseek' => array(
            'default' => 'deepseek-chat',
            'complex' => 'deepseek-reasoner',
            'audit'   => 'deepseek-reasoner',
            'temperature' => 0.7,
            'max_tokens' => 2048,
            'reasoning_mode' => 0,
        ),
        // gemini, groq, anthropic, grok, openai ...
    ),
    'llm_defaults' => array(
        'temperature' => 0.7,
        'max_tokens' => 2048,
        'reasoning_mode' => 0,
    ),
    'providers' => array( /* keys, budgets */ ),
);
```

## 3. Installer requirements

### 3.1 API keys step UI

Collect per provider:

- Model names: default, complex, audit
- Temperature, max tokens
- Reasoning mode toggle (DeepSeek reasoner preference)

### 3.2 Install classes

| Class | Responsibility |
|-------|----------------|
| `InstallWizardLLMDefaults` | Canonical default model map |
| `InstallWizardLLMConfigLoader` | Seed `agent_llm_configs` from `agent_definitions` |

### 3.3 Seed rules

- One row per agent: `config_name = default`
- Primary provider: first enabled provider in fallback order
- Fallback provider/model stored in `safety_json`
- Idempotent: UPDATE if `(agent_id, config_name)` exists else INSERT with explicit `agent_llm_config_id`
- Runs after config write on `api_keys` step

## 4. Database

Table: `agent_llm_configs` (see install SQL section 4).

- Links `agent_id` to `provider`, `model_name`, generation params
- `api_key_id` remains NULL; keys live in config file (BYOK)
- `safety_json` holds fallback routing metadata from installer

## 5. Agent discovery

Filesystem packs: load `system_prompt.md` first, fallback `system_prompt.txt`.

## 6. Out of scope (this PRD)

- Modifying agent template packs
- Creating runtime actors
- Lupopedia-to-Lupopedia migrations

## 7. Acceptance criteria

1. Fresh install api_keys step writes `models` and `llm_defaults` into config.
2. `agent_llm_configs` contains one active row per `agent_definitions` row after install.
3. `DeepSeekProvider` implements interface with token and latency accounting.
4. `ApiProviderChainService::resolveModelChain('complex')` returns enabled providers with models.
5. Re-running install api_keys seed updates existing LLM config rows without duplicate keys.

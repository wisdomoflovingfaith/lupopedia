---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/12_C-i_MULTI_API_AGENT_INTEGRATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/12_C-i_MULTI_API_AGENT_INTEGRATION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/prd/canonical/1026/04/12-c-multi-api-agent-integration.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/prd/12-c-multi-api-agent-integration
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 12_C-i_00_A-i_12_A-i_12_B-i_07_A-i_15_A-i_02_A-i_57_A-i_86_A-i
  title: 'PRD 12_C: Multi-API Agent Integration'
  summary: Multi-provider LLM integration doctrine for Lupopedia agents. Documents API routing, provider selection, token tracking across providers, actor-level API usage, fallback chains, and constitutional constraints for OpenAI, Gemini, DeepSeek, and other LLM providers.
---

# PRD 12_C: Multi-API Agent Integration

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. Purpose

This PRD documents the existing multi-provider LLM integration system in Lupopedia, defining how agents integrate with, select, and use multiple LLM APIs while maintaining constitutional constraints and proper token tracking.

The system provides:
- Multi-provider LLM integration (OpenAI, Gemini, DeepSeek, etc.)
- API routing and selection logic
- Token usage tracking across providers
- Actor-level API usage and governance
- Fallback chains and error handling
- Constitutional constraint enforcement

## 2. Supported Providers

### 2.1 Current Provider Support

**Primary Providers:**
- **OpenAI** - GPT models (GPT-3.5, GPT-4, GPT-4-turbo)
- **Google Gemini** - Gemini models (gemini-pro, gemini-pro-vision)
- **DeepSeek** - DeepSeek models (deepseek-coder, deepseek-chat)
- **Anthropic Claude** - Claude models (claude-3-sonnet, claude-3-haiku)

**Provider Characteristics:**
| Provider | Strengths | Typical Use Cases | Rate Limits |
|----------|-----------|-------------------|-------------|
| OpenAI | General reasoning, coding | Broad task coverage | High |
| Gemini | Multimodal, long context | Vision tasks, analysis | Medium |
| DeepSeek | Coding optimization | Code generation, review | Medium |
| Claude | Safety-conscious tasks | Sensitive content | Medium |

### 2.2 Extensibility

**Future Provider Integration:**
- Provider interface is standardized for easy addition
- New providers follow existing key management pattern
- Token tracking automatically extends to new providers
- Fallback chains can include new providers

**Integration Requirements:**
- Standard API key authentication
- Token usage reporting capability
- Error response standardization
- Timeout and retry handling

## 3. API Key Management

### 3.1 Key Storage Architecture

**System-Level Storage:**
- Primary storage in `lupopedia-config.php` (outside web root)
- Environment variable support for deployment flexibility
- Encrypted storage for sensitive environments
- Backup and recovery procedures

**Per-Actor Key Support:**
- Actors can supply personal API keys
- User keys override system defaults
- Key isolation per actor prevents cross-contamination
- Graceful fallback to system keys when user keys fail

### 3.2 Key Isolation and Security

**Isolation Principles:**
- Each actor's API usage tracked separately
- Keys never logged or exposed in responses
- Runtime key loading prevents accidental exposure
- Memory-only key storage during execution

**Security Measures:**
- Keys never written to logs or debug output
- API response sanitization removes key traces
- Secure key transmission over HTTPS
- Regular key rotation support (FUTURE)

### 3.3 Key Hierarchy

**Priority Order:**
1. Actor-specific API key (highest priority)
2. Channel-level API key (if configured)
3. System default API key (fallback)
4. Emergency backup key (critical system operations)

## 4. Token Usage Tracking

### 4.1 Tracking Architecture

**Primary Table:** `lupo_token_usage`

**Tracking Dimensions:**
- **Actor Level:** Per-actor token consumption
- **Provider Level:** Usage per LLM provider
- **Model Level:** Usage per specific model
- **Request Level:** Individual request tracking

**Schema Integration:**
```sql
-- Core tracking fields
actor_id, provider, model, tokens_used, request_timestamp, request_type
```

### 4.2 Governance Integration

**PRD 12_B Compliance:**
- Token quotas enforced per actor
- Usage limits applied across providers
- Real-time quota checking before requests
- Graceful degradation when limits approached

**Quota Enforcement:**
- Pre-request quota validation
- Multiple quota types (daily, monthly, per-request)
- Emergency quota bypass for system operations
- Quota reset and carryover policies

### 4.3 Usage Analytics

**Reporting Features:**
- Per-provider usage breakdown
- Cost calculation by provider rates
- Actor usage patterns and trends
- System-wide consumption metrics

**Optimization Insights:**
- Provider performance comparison
- Cost-effectiveness analysis
- Model selection recommendations
- Usage anomaly detection

## 5. Provider Routing Logic

### 5.1 Priority Chains

**Default Routing Chain:**
```
OpenAI ??? Gemini ??? DeepSeek ??? Claude
```

**Task-Specific Routing:**
- **Reasoning Tasks:** OpenAI ??? Gemini ??? Claude
- **Coding Tasks:** DeepSeek ??? OpenAI ??? Claude
- **Chat Tasks:** Claude ??? OpenAI ??? Gemini
- **Multimodal Tasks:** Gemini ??? OpenAI GPT-4V ??? Claude

### 5.2 Model Selection by Task

**Task Type Mapping:**
| Task Type | Primary Models | Fallback Models |
|-----------|----------------|-----------------|
| Code Generation | deepseek-coder, gpt-4 | claude-3-sonnet, gemini-pro |
| Data Analysis | gpt-4, gemini-pro | claude-3-sonnet, deepseek-chat |
| Chat/Conversation | claude-3-sonnet, gpt-3.5 | gemini-pro, deepseek-chat |
| Document Review | gpt-4-turbo, claude-3-haiku | gemini-pro, deepseek-coder |
| Safety-Critical | claude-3-sonnet, gpt-4 | gemini-pro, deepseek-chat |

### 5.3 Channel-Level Overrides

**Override Mechanisms:**
- Channels can specify preferred providers
- Channel-specific routing chains
- Emergency routing for critical channels
- A/B testing support for provider comparison

**Configuration:**
- Channel-level provider preferences
- Model overrides per channel
- Budget constraints per channel
- Performance requirements per channel

### 5.4 Deterministic Selection

**Selection Algorithm:**
1. Check task type requirements
2. Apply channel-level overrides
3. Check actor preferences
4. Validate quota availability
5. Select primary available provider
6. Prepare fallback chain

**Consistency Requirements:**
- Same inputs produce same provider selection
- Replicable routing decisions
- No random provider selection
- Predictable fallback behavior

## 6. Actor / Agent Integration

### 6.1 Agent Provider Preferences

**Agent Configuration:**
- Agents define preferred providers in configuration
- Default provider chains per agent type
- Task-specific provider overrides
- Performance-based provider selection

**Agent Types and Preferences:**
- **System Agents:** Use most reliable providers
- **User Agents:** Respect user provider preferences
- **Specialized Agents:** Use optimized providers for tasks
- **Development Agents:** Use cost-effective providers

### 6.2 Actor Configuration Inheritance

**Inheritance Hierarchy:**
1. Actor-specific configuration (highest priority)
2. Channel-level configuration
3. Agent-type defaults
4. System defaults (fallback)

**Override Behavior:**
- Actors can override provider preferences
- Inheritance respects constitutional constraints
- Invalid overrides fall back to safe defaults
- Override validation prevents misconfiguration

### 6.3 System Actor Defaults

**System Actor Configuration:**
- WOLFIE: OpenAI ??? Gemini (high reliability)
- LILITH: Claude ??? OpenAI (safety-focused)
- ATHENA: Gemini ??? OpenAI (analysis-focused)
- VISH: OpenAI ??? DeepSeek (organization-focused)

**Emergency Routing:**
- Critical system operations have dedicated providers
- Emergency bypass for system maintenance
- Fallback providers for system resilience
- Provider health monitoring

### 6.4 PRD 07 and PRD 15 Integration

**Agent Runtime (PRD 07):**
- Agents inherit provider configuration from runtime
- Runtime manages provider switching
- Agent lifecycle includes provider health checks
- Agent spawning respects provider constraints

**Actor System (PRD 15):**
- Actors maintain provider state
- Actor authentication includes provider validation
- Actor permissions extend to provider usage
- Actor audit trails include provider usage

## 7. Error Handling + Fallback

### 7.1 Error Types and Handling

**Timeout Errors:**
- Configurable timeout per provider
- Automatic retry with exponential backoff
- Fallback to next provider after timeout
- Timeout logging and monitoring

**Rate Limit Errors:**
- Rate limit detection and parsing
- Automatic retry with delay
- Provider switching for persistent limits
- Rate limit quota management

**Provider Failure:**
- Provider health monitoring
- Automatic failover to healthy providers
- Circuit breaker pattern for failing providers
- Provider recovery detection

### 7.2 Fallback Chain Behavior

**Fallback Triggers:**
- Request timeout
- Rate limit exceeded
- Provider API error
- Provider maintenance

**Fallback Process:**
1. Detect failure condition
2. Log failure details
3. Select next provider in chain
4. Retry request with new provider
5. Update provider health metrics
6. Continue until success or chain exhausted

**Chain Exhaustion:**
- Full chain failure generates WHY files
- Emergency provider activation for critical tasks
- User notification of complete failure
- System degradation procedures

### 7.3 WHY File Generation

**Failure Scenarios:**
- Complete provider chain failure
- Repeated failures on same task
- Provider quota exhaustion
- Configuration errors

**WHY File Content:**
- Failure timestamp and context
- Provider chain attempted
- Error details and responses
- Recommended corrective actions
- Impact assessment

## 8. Constitutional Constraints

### 8.1 AGAPE Hard Gate Application

**Pre-Request Validation:**
- All API requests pass through AGAPE validation
- Request content checked for constitutional compliance
- Provider selection respects AGAPE constraints
- Response content validated through AGAPE

**Constraint Enforcement:**
- No advertising injection in prompts or responses
- No engagement manipulation or dark patterns
- Full logging transparency for all API interactions
- ASCII-only enforcement for all text content

### 8.2 Provider-Specific Constraints

**OpenAI Constraints:**
- Content policy compliance
- Usage policy adherence
- Rate limit respect
- Data privacy requirements

**Gemini Constraints:**
- Google AI principles compliance
- Content safety checks
- Usage quota management
- Data handling policies

**DeepSeek Constraints:**
- Content guidelines adherence
- Usage policy compliance
- Rate limit management
- Privacy requirements

**Claude Constraints:**
- Constitutional AI principles
- Safety requirement compliance
- Usage policy adherence
- Privacy protection

### 8.3 PRD 57 and PRD 86 Integration

**AGAPE Integration (PRD 57):**
- API requests require AGAPE validation
- Response filtering through AGAPE
- Violation logging and reporting
- Automatic correction of constitutional violations

**Immune System (PRD 86):**
- Provider behavior monitoring
- Anomaly detection in API usage
- Automatic response to suspicious patterns
- Provider health and security monitoring

## 9. Security

### 9.1 Key Protection

**Key Storage Security:**
- Keys stored outside web root directory
- File system permissions restrict access
- Environment variable encryption support
- Secure key loading procedures

**Runtime Protection:**
- Keys never included in error messages
- API response sanitization removes key traces
- Memory clearing after key usage
- Secure key transmission protocols

### 9.2 Per-Actor Isolation

**Isolation Mechanisms:**
- Each actor's API usage tracked separately
- Key isolation prevents cross-actor access
- Usage quotas enforced per actor
- Audit trails maintain actor separation

**Security Boundaries:**
- Actor cannot access other actors' keys
- System keys protected from actor access
- Compartmentalization of provider access
- Secure inter-actor communication

### 9.3 Encryption and Privacy

**Data Protection:**
- API request content encrypted in transit
- Sensitive data masked in logs
- User privacy protection in API usage
- Data retention policies compliance

**Privacy Measures:**
- No personal data in API keys
- User content anonymization in tracking
- GDPR compliance for user data
- Data minimization principles

## 10. User Experience

### 10.1 API Key Management

**User Key Addition:**
- Users add API keys through profile settings
- Key validation and testing on addition
- Secure key storage procedures
- Key usage reporting and monitoring

**Provider Selection:**
- Users can select preferred providers
- Provider performance comparison
- Cost optimization recommendations
- Usage analytics and insights

### 10.2 Usage Monitoring

**Usage Dashboard:**
- Real-time token usage tracking
- Cost calculation per provider
- Usage quota status
- Historical usage patterns

**Provider Performance:**
- Response time comparison
- Success rate metrics
- Error rate monitoring
- Quality assessment tools

### 10.3 Configuration Interface

**Provider Preferences:**
- User-configurable provider chains
- Task-specific provider selection
- Budget constraints configuration
- Performance requirements setting

**Advanced Settings:**
- Custom timeout configurations
- Retry behavior customization
- Fallback chain management
- Emergency contact settings

## 11. Dependencies

### 11.1 Direct Dependencies

- **PRD 12_A** (API Integration) - Core API integration infrastructure
- **PRD 12_B** (Token Governance) - Token limits and quota management
- **PRD 07_A** (Agents) - Agent runtime and lifecycle management
- **PRD 15_A** (Actors) - Actor system and authentication
- **PRD 02_A** (Channels) - Channel routing and configuration
- **PRD 57_A** (AGAPE) - Constitutional validation and enforcement
- **PRD 86_A** (Immune System) - Security monitoring and anomaly detection

### 11.2 Infrastructure Dependencies

**Database Tables:**
- `lupo_api_keys` - API key storage and management
- `lupo_token_usage` - Token usage tracking and analytics
- `lupo_actors` - Actor configuration and preferences
- `lupo_channels` - Channel-specific provider settings

**System Components:**
- Provider SDKs and clients
- Authentication and authorization systems
- Monitoring and logging infrastructure
- Configuration management systems

### 11.3 External Dependencies

**Provider APIs:**
- OpenAI API and SDKs
- Google Gemini API and client libraries
- DeepSeek API and integration tools
- Anthropic Claude API and SDK

**Third-Party Services:**
- API gateway and routing services
- Monitoring and analytics platforms
- Security and compliance tools
- Backup and recovery systems

## 12. Non-Goals

This PRD does NOT:

- **Implement UI** - User interface design is separate
- **Create New Tables** - Uses existing database schema
- **Replace Provider SDKs** - Leverages existing provider tools
- **Define Key Rotation** - Future enhancement, not current scope
- **Implement New Authentication** - Uses existing auth systems
- **Create New Monitoring** - Integrates with existing monitoring
- **Define New Billing** - Uses existing financial systems
- **Implement Caching** - Future performance optimization

## 13. Future Enhancements

### 13.1 Planned Improvements

**Advanced Routing:**
- Machine learning-based provider selection
- Dynamic routing based on performance metrics
- Cost optimization algorithms
- Quality-based provider ranking

**Enhanced Security:**
- Automatic key rotation
- Hardware security module integration
- Advanced threat detection
- Zero-trust architecture implementation

**Performance Optimization:**
- Request caching and deduplication
- Load balancing across providers
- Predictive scaling
- Edge computing integration

### 13.2 Provider Expansion

**New Providers:**
- Additional LLM providers as they emerge
- Specialized providers for specific domains
- Local model integration
- Custom model deployment support

**Integration Standards:**
- Provider interface standardization
- Automatic provider discovery
- Plugin architecture for providers
- Provider certification process

---

# End of PRD 12_C

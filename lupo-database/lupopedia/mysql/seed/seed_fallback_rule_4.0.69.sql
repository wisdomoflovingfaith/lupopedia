-- Seed Fallback Doctrine rule (4.0.69).
-- Doctrine: FallbackDoctrine.md, ActorFaucetOntology.md. Rule enforces deterministic fallback for all actors/channels.
-- Fallback routes between faucets (not actors); actors hold rules/skills, faucets are execution surfaces (IDE/LLM).
-- Use with LUPO_TABLE_PREFIX (default lupo_). Explicit rule_id and rule_target_id; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

-- Fallback required: all actors must implement deterministic fallback when primary execution fails.
INSERT INTO lupo_rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1003, 'fallback_required', 'All actors must implement deterministic fallback behavior when primary execution fails. No silent failure; log fallback events in lupo_rule_logs.', 'constraint', '{"doctrine": "fallback", "rule": "fallback_required", "severity": "critical", "enforcement": "strict", "invariants": ["no_actor_without_fallback", "no_channel_without_fallback_capability", "no_faucet_without_secondary_route", "no_llm_invocation_without_fallback_strategy"]}', 1, @now, @now, 0, NULL);

-- Attach fallback rule to Channel 42 (governance). System-wide: all actors and channels are subject to this rule; attachment to Channel 42 establishes governance scope. Optional: add more lupo_rule_targets rows for specific actors/channels/faucets as needed.
INSERT INTO lupo_rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (7, 1003, 'channels', 42, 10000, 100, @now, @now, 0, NULL);

-- Note: Fallback mechanics are implemented as a skill (documented in FallbackDoctrine.md). Actors attach the skill via lupopedia.skills header or lupo_metadata; the rule enforces that they must have and use it. No separate skills table in this schema; see lupo_metadata and lupo-skills/ for skill definitions.
-- Ontology: IDE agents (Cursor, Kiro, Antigravity, Windsurf) are faucets, not actors. See ActorFaucetOntology.md. lupo_agent_faucets.faucet_class = 'ide' | 'llm' (install + migration 20260310_faucet_class.sql).

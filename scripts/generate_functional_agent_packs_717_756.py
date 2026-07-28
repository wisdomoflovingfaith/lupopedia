#!/usr/bin/env python3
"""One-time generator: 40 functional Lupopedia agent template packs (717-756)."""
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
AGENTS_DIR = ROOT / "agents"
REGISTRY = ROOT / "database/lupopedia/actors/actor_id/registry.json"
UTC = "20260620155606"
ACTOR_ID = 102

# agent_key, role, layer, is_kernel, capabilities, blocked, scope_one_liner
AGENTS = [
    ("kernel_scheduler", "Agent Execution Scheduler", "kernel", True,
     ["schedule_agent_execution", "order_task_queue", "resolve_execution_priority", "report_schedule_status"],
     ["execute_non_scheduling_tasks", "modify_agent_logic", "provide_therapy", "creative_content_generation"],
     "Manages agent execution order and scheduling metadata only."),
    ("kernel_sandbox", "Unsafe Operation Isolator", "kernel", True,
     ["isolate_unsafe_operations", "enforce_sandbox_boundaries", "report_sandbox_violations", "quarantine_risky_requests"],
     ["execute_unsandboxed_operations", "bypass_isolation_policy", "modify_production_config"],
     "Isolates unsafe operations within sandbox policy; does not execute production changes."),
    ("kernel_recovery", "System Recovery Coordinator", "kernel", True,
     ["detect_failure_state", "plan_recovery_sequence", "report_recovery_status", "validate_post_recovery_health"],
     ["silent_data_loss", "skip_recovery_logging", "modify_schema_without_doctrine"],
     "Plans and reports system recovery after failure; does not hide failures."),
    ("kernel_snapshot", "State Checkpoint Manager", "kernel", True,
     ["create_state_checkpoint", "list_checkpoints", "compare_checkpoint_drift", "report_checkpoint_integrity"],
     ["restore_without_authorization", "delete_checkpoints_silently", "modify_checkpoint_payload"],
     "State checkpointing metadata and integrity reporting only."),
    ("kernel_metrics", "Internal Performance Telemetry", "kernel", True,
     ["collect_internal_metrics", "aggregate_performance_stats", "report_metric_anomalies", "export_telemetry_summary"],
     ["fabricate_metrics", "omit_anomaly_reporting", "modify_runtime_outside_telemetry"],
     "Internal performance telemetry collection and reporting only."),
    ("kernel_throttle", "Load Shedding and Rate Limiter", "kernel", True,
     ["monitor_load_pressure", "apply_rate_limits", "recommend_load_shedding", "report_throttle_events"],
     ["disable_throttling_silently", "bypass_rate_limits", "execute_unrelated_business_logic"],
     "Load shedding and rate-limit enforcement reporting only."),
    ("reasoning_planner", "Multi-Step Reasoning Planner", "cognitive", False,
     ["decompose_reasoning_steps", "build_reasoning_plan", "track_plan_progress", "summarize_reasoning_chain"],
     ["execute_implementation", "provide_medical_advice", "manipulate_agents"],
     "Multi-step reasoning decomposition and planning only."),
    ("reasoning_validator", "Logical Consistency Validator", "cognitive", False,
     ["validate_logical_consistency", "detect_reasoning_gaps", "flag_invalid_inference", "report_validation_result"],
     ["rewrite_other_agents_outputs", "approve_without_check", "provide_therapy"],
     "Logical consistency checking on reasoning artifacts only."),
    ("analogy_engine", "Cross-Domain Mapping Engine", "cognitive", False,
     ["map_cross_domain_analogies", "identify_structural_parallels", "summarize_analogy_limits", "tag_mapping_confidence"],
     ["assert_factual_equivalence", "execute_code_changes", "provide_medical_diagnosis"],
     "Cross-domain analogy mapping; analogies are illustrative not factual proof."),
    ("abstraction_engine", "Concept Compression Engine", "cognitive", False,
     ["compress_concepts", "extract_core_abstractions", "summarize_abstraction_layers", "report_information_loss"],
     ["delete_source_detail_silently", "implement_refactors", "provide_therapy"],
     "Concept compression and abstraction summaries only."),
    ("contradiction_detector", "Conflict Detection Engine", "cognitive", False,
     ["detect_logical_contradictions", "detect_policy_contradictions", "report_conflict_pairs", "suggest_resolution_paths"],
     ["unilaterally_resolve_conflicts", "modify_canonical_doctrine", "provide_therapy"],
     "Detects contradictions and reports them; does not silently resolve canonical conflicts."),
    ("context_resolver", "Ambiguous Reference Resolver", "cognitive", False,
     ["resolve_ambiguous_references", "disambiguate_context_pointers", "report_resolution_confidence", "list_unresolved_references"],
     ["guess_without_evidence", "modify_source_artifacts", "provide_medical_advice"],
     "Resolves ambiguous references using available context only."),
    ("cognitive_load_balancer", "Reasoning Task Distributor", "cognitive", False,
     ["distribute_reasoning_tasks", "balance_cognitive_load", "report_load_distribution", "recommend_task_splitting"],
     ["execute_assigned_tasks_for_other_agents", "hide_overload_conditions", "provide_therapy"],
     "Distributes reasoning workload recommendations only."),
    ("evidence_ranker", "Evidence Priority Ranker", "cognitive", False,
     ["rank_evidence_strength", "score_source_reliability", "summarize_evidence_gaps", "report_ranking_rationale"],
     ["fabricate_evidence", "suppress_weak_evidence_silently", "provide_medical_diagnosis"],
     "Prioritizes evidence by strength; does not invent sources."),
    ("build_graph_manager", "Dependency Graph Manager", "application", False,
     ["build_dependency_graph", "track_build_edges", "detect_circular_dependencies", "report_graph_status"],
     ["execute_builds", "modify_source_without_plan", "provide_therapy"],
     "Dependency graph construction and analysis only."),
    ("semantic_diff_engine", "Meaning-Based Diff Engine", "application", False,
     ["compute_semantic_diff", "summarize_meaning_changes", "tag_semantic_drift", "report_diff_confidence"],
     ["apply_patches_directly", "rewrite_canonical_doctrine", "provide_medical_advice"],
     "Meaning-based diff analysis only; does not apply changes."),
    ("refactor_planner", "Safe Refactor Planner", "application", False,
     ["plan_safe_refactors", "identify_refactor_risks", "sequence_refactor_steps", "report_refactor_scope"],
     ["execute_refactors_directly", "skip_risk_analysis", "provide_therapy"],
     "Safe code transformation planning only; no direct edits."),
    ("pipeline_orchestrator", "Multi-Stage Pipeline Orchestrator", "application", False,
     ["define_pipeline_stages", "track_stage_status", "report_pipeline_failures", "recommend_stage_order"],
     ["execute_pipeline_steps_outside_scope", "bypass_stage_gates", "provide_medical_advice"],
     "Multi-stage execution flow orchestration metadata only."),
    ("compiler_logic", "Code Interpretation and Transformation Logic", "application", False,
     ["interpret_code_structure", "plan_code_transformations", "report_syntax_semantics", "validate_transform_rules"],
     ["deploy_to_production", "modify_files_without_plan", "provide_therapy"],
     "Code interpretation and transformation planning only."),
    ("test_generator", "Automated Test Generator", "application", False,
     ["generate_test_cases", "map_tests_to_requirements", "report_test_coverage_gaps", "summarize_test_plan"],
     ["execute_tests_in_production", "skip_edge_cases_silently", "provide_medical_advice"],
     "Automated test creation planning and artifacts only."),
    ("simulation_runner", "Dry-Run Simulation Engine", "application", False,
     ["run_dry_run_simulations", "report_simulation_outcomes", "compare_simulation_to_baseline", "flag_simulation_drift"],
     ["apply_simulation_results_to_live_system", "hide_simulation_failures", "provide_therapy"],
     "Dry-run simulation reporting only; no live mutations."),
    ("emotional_memory_archivist", "Emotional Pattern Archivist", "emotional", False,
     ["archive_emotional_patterns", "index_emotional_events", "summarize_pattern_history", "report_archive_integrity"],
     ["diagnose_mental_conditions", "provide_therapy", "delete_emotional_records_silently"],
     "Stores emotional pattern metadata; not therapy or diagnosis."),
    ("tone_stabilizer", "Emotional Volatility Stabilizer", "emotional", False,
     ["detect_tone_volatility", "recommend_tone_smoothing", "report_tone_drift", "suggest_stabilized_framing"],
     ["suppress_valid_alerts", "provide_therapy", "manipulate_agent_personas"],
     "Smooths emotional volatility in communication metadata only."),
    ("persona_harmonizer", "Multi-Persona Output Aligner", "emotional", False,
     ["align_persona_outputs", "detect_persona_conflicts", "recommend_harmonized_tone", "report_alignment_status"],
     ["override_agent_identity", "provide_medical_advice", "execute_unrelated_tasks"],
     "Aligns multi-persona output tone; does not replace agent roles."),
    ("conflict_mediator", "Emotional Contradiction Mediator", "emotional", False,
     ["mediate_emotional_contradictions", "summarize_conflict_positions", "recommend_balanced_framing", "report_mediation_status"],
     ["impose_unilateral_decisions", "provide_therapy", "diagnose_conditions"],
     "Mediates emotional contradictions in communication; not clinical mediation."),
    ("subconscious_pattern_agent", "Latent Pattern Detector", "emotional", False,
     ["detect_latent_patterns", "summarize_hidden_trends", "tag_pattern_confidence", "report_pattern_windows"],
     ["claim_clinical_truth", "provide_therapy", "fabricate_patterns"],
     "Detects latent emotional/communication patterns; observational only."),
    ("constitutional_interpreter", "System Rules Interpreter", "coordination", False,
     ["interpret_constitutional_rules", "map_rules_to_context", "report_rule_ambiguity", "cite_rule_sources"],
     ["rewrite_constitution", "waive_rules_silently", "provide_medical_advice"],
     "Interprets system rules; does not amend canonical doctrine."),
    ("rights_guardian", "User Rights Protector", "coordination", False,
     ["monitor_user_rights", "flag_rights_violations", "report_rights_status", "recommend_rights_preserving_paths"],
     ["bypass_consent", "suppress_rights_alerts", "provide_therapy"],
     "Protects user rights in policy interpretation; advisory only."),
    ("bias_auditor", "Systemic Bias Auditor", "coordination", False,
     ["audit_systemic_bias", "report_bias_signals", "tag_bias_categories", "recommend_bias_mitigation"],
     ["hide_bias_findings", "assert_demographic_claims_without_evidence", "provide_medical_diagnosis"],
     "Detects systemic bias signals; does not make unfounded demographic claims."),
    ("fairness_regulator", "Fairness Constraint Enforcer", "coordination", False,
     ["enforce_fairness_constraints", "report_fairness_violations", "recommend_fairness_adjustments", "summarize_fairness_status"],
     ["override_policy_for_convenience", "provide_therapy", "execute_unrelated_code"],
     "Enforces fairness constraints in recommendations; does not bypass policy."),
    ("compliance_scribe", "Rule-Based Decision Logger", "coordination", False,
     ["log_rule_based_decisions", "timestamp_compliance_events", "summarize_compliance_trail", "report_logging_gaps"],
     ["omit_required_logs", "alter_historical_logs", "provide_medical_advice"],
     "Logs rule-based decisions; immutable audit orientation."),
    ("style_transfer_engine", "Writing Style Adapter", "application", False,
     ["adapt_writing_style", "preserve_semantic_meaning", "report_style_drift", "recommend_style_parameters"],
     ["plagiarize_protected_content", "provide_medical_advice", "modify_canonical_doctrine"],
     "Adapts writing/creative style while preserving meaning boundaries."),
    ("narrative_weaver", "Coherent Story Arc Builder", "application", False,
     ["build_story_arcs", "track_narrative_continuity", "report_plot_inconsistencies", "summarize_narrative_structure"],
     ["present_fiction_as_fact", "provide_therapy", "execute_production_code"],
     "Builds coherent narrative structure; fiction/creative framing only when labeled."),
    ("improvisation_engine", "Spontaneous Creative Variation Engine", "application", False,
     ["generate_creative_variations", "maintain_theme_constraints", "report_variation_bounds", "summarize_improvisation_options"],
     ["violate_content_policy", "provide_medical_diagnosis", "modify_system_config"],
     "Spontaneous creative variation within declared constraints only."),
    ("knowledge_archivist", "Long-Term Knowledge Archivist", "application", False,
     ["archive_knowledge_artifacts", "index_long_term_knowledge", "report_archive_health", "summarize_knowledge_lineage"],
     ["delete_canonical_knowledge_silently", "fabricate_sources", "provide_therapy"],
     "Long-term knowledge storage metadata and indexing only."),
    ("semantic_indexer", "Semantic Search Index Builder", "application", False,
     ["build_semantic_indexes", "update_index_metadata", "report_index_coverage", "summarize_index_gaps"],
     ["modify_source_content", "provide_medical_advice", "bypass_index_validation"],
     "Builds semantic search indexes; does not alter authoritative source content."),
    ("ontology_expander", "Knowledge Graph Expander", "application", False,
     ["expand_ontology_nodes", "propose_ontology_edges", "report_ontology_conflicts", "summarize_graph_growth"],
     ["assert_unverified_facts_as_canonical", "delete_ontology_nodes_silently", "provide_therapy"],
     "Grows knowledge graph with explicit provenance; no silent canonization."),
    ("cross_domain_mapper", "Cross-Field Concept Linker", "application", False,
     ["link_concepts_across_domains", "map_cross_field_relations", "report_mapping_confidence", "summarize_domain_bridges"],
     ["claim_causal_proof_without_evidence", "execute_code_refactors", "provide_medical_diagnosis"],
     "Links concepts across fields with confidence tagging only."),
    ("debug_surface", "Debugging Interface Surface", "application", False,
     ["present_debug_context", "summarize_debug_traces", "recommend_debug_next_steps", "report_debug_surface_status"],
     ["hide_errors_silently", "modify_production_without_approval", "provide_therapy"],
     "Debugging interface presentation and trace summarization only."),
    ("visualization_surface", "Graph and Visualization Layer", "application", False,
     ["render_graph_visualizations", "summarize_visualization_data", "report_visualization_limits", "recommend_view_layouts"],
     ["misrepresent_data_in_charts", "execute_business_logic", "provide_medical_advice"],
     "Graphing and visualization presentation only; data must remain faithful."),
]


def slug_to_name(key):
    return key.upper().replace("_", " ")


def refusal_out_of_scope(role_short):
    return "This request is outside my scope as %s. I cannot perform that function." % role_short


def refusal_therapy():
    return "This is outside my functional scope. Refer to an appropriate professional or specialized agent."


def build_system_prompt(key, agent_id, role, scope, capabilities, blocked):
    caps = ", ".join(capabilities)
    name_upper = slug_to_name(key)
    return """---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: agents/%s/system_prompt.md
  web_path: https://www.lupopedia.com/lupopedia/agents/%s/system_prompt.md
  status: active
  when_updated: '%s'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/development/%s-system-prompt
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: %s -- %s (system prompt)
  summary: 'Canonical %s agent template (%s): %s'
---
# %s -- %s (agent template %s)

Canonical prompt for the **%s** agent pack (**agents/%s/**). **{{agent_name}}** is a pure functional role in the Lupopedia Semantic OS.

**Scope:** %s

**{{agent_name}}** does **not** operate outside this functional domain.

## 1. Identity

| Field | Value |
|-------|--------|
| **Name** | **%s** |
| **agent_id** | **%s** |
| **Role** | %s |
| **agent_key** | %s |

## 2. Allowed capabilities

%s

## 3. Hard refusals

### 3.1 Out-of-scope requests

If asked to perform work outside this agent's functional domain, respond with a clear scope refusal and stop. Do not partially comply.

Template: **"%s"**

### 3.2 Clinical, therapeutic, or medical requests

Respond exactly:

**"%s"**

## 4. Absolute bans

%s

## 5. Output discipline

- Use explicit, neutral, functional language.
- Report uncertainty and limits of analysis.
- Do not invent data, sources, or execution results.
- Timestamp operational notes in UTC **YYYYMMDDHHIISS** when logging.

## 6. Self-check before send

1. Is this within **%s** scope only?
2. Did I avoid blocked capabilities?
3. Did I refuse out-of-scope work clearly?
4. Is output factual, bounded, and non-clinical?

**End of %s system prompt.**
""" % (
        key, key, UTC, key,
        name_upper, role, name_upper, agent_id, scope[:120],
        name_upper, role, agent_id,
        name_upper, key,
        scope,
        name_upper, agent_id, role, key,
        "\n".join("- " + c for c in capabilities),
        refusal_out_of_scope(role.lower()),
        refusal_therapy(),
        "\n".join("- No **%s**." % b.replace("_", " ") for b in blocked),
        role.lower(),
        name_upper,
    )


def write_agent_pack(key, agent_id, role, layer, is_kernel, capabilities, blocked, scope):
    base = AGENTS_DIR / key
    ver = base / "versions" / "v1.0.0"
    ver.mkdir(parents=True, exist_ok=True)

    agent_json = {
        "agent_key": key,
        "agent_id": agent_id,
        "version": "1.0.0",
        "is_kernel": is_kernel,
        "is_internal_only": False,
        "layer": layer,
        "name": slug_to_name(key),
        "slug": key,
        "role": role,
        "is_required": False,
        "aliases": [key],
        "when_updated_utc": UTC,
        "last_verified_utc": UTC,
        "last_verified_by": "cursor",
        "last_verified_by_actor_id": ACTOR_ID,
        "system_prompt_reference": "agents/%s/system_prompt.md" % key,
    }

    caps_json = {
        "schema_version": "1.0.0",
        "capabilities": capabilities,
        "blocked_capabilities": blocked,
        "when_updated_utc": UTC,
        "last_verified_utc": UTC,
    }

    props_json = {
        "properties": {
            "personality": {
                "tone": "neutral_functional",
                "style": "precise",
                "stance": "scope_bound",
                "focus": "domain_only",
            },
            "boundaries": {
                "scope": scope,
                "out_of_scope_refusal": refusal_out_of_scope(role.lower()),
                "clinical_refusal": refusal_therapy(),
                "must_stay_in_domain": True,
            },
            "functional_domains": capabilities,
            "canonical_system_prompt": "agents/%s/system_prompt.md" % key,
        },
        "when_updated_utc": UTC,
        "last_verified_utc": UTC,
    }

    prompt = build_system_prompt(key, agent_id, role, scope, capabilities, blocked)
    prompt_ver = prompt.replace(
        "path_from_lupopedia_root: agents/%s/system_prompt.md" % key,
        "path_from_lupopedia_root: agents/%s/versions/v1.0.0/system_prompt.md" % key,
    ).replace(
        "web_path: https://www.lupopedia.com/lupopedia/agents/%s/system_prompt.md" % key,
        "web_path: https://www.lupopedia.com/lupopedia/agents/%s/versions/v1.0.0/system_prompt.md" % key,
    ).replace(
        "transcript_jsonl: 0/development/%s-system-prompt" % key,
        "transcript_jsonl: null",
    ).replace(
        "title: %s -- %s (system prompt)" % (slug_to_name(key), role),
        "title: %s v1.0.0 system prompt snapshot" % slug_to_name(key),
    ).replace(
        "summary: 'Canonical %s agent template (%s): %s'" % (slug_to_name(key), agent_id, scope[:120]),
        "summary: 'Frozen v1.0.0 snapshot of agents/%s/system_prompt.md.'" % key,
    )

    for path, obj in [
        (base / "agent.json", agent_json),
        (ver / "agent.json", agent_json),
        (base / "capabilities.json", caps_json),
        (ver / "capabilities.json", caps_json),
        (base / "properties.json", props_json),
        (ver / "properties.json", props_json),
    ]:
        path.write_text(json.dumps(obj, indent=2) + "\n", encoding="utf-8")

    (base / "system_prompt.md").write_text(prompt, encoding="utf-8")
    (ver / "system_prompt.md").write_text(prompt_ver, encoding="utf-8")

    (ver / "observations.md").write_text(
        "# Observations\n\nInitial %s agent pack v1.0.0 created (agent_id %s).\n" % (slug_to_name(key), agent_id),
        encoding="utf-8",
    )
    (ver / "decisions.md").write_text(
        "# Decisions\n\n- Functional role template only; no actor or runtime code.\n- agent_id %s assigned in batch 717-756.\n"
        % agent_id,
        encoding="utf-8",
    )
    (ver / "changelog.md").write_text(
        "# Changelog\n\n## v1.0.0 (%s UTC)\n\n- Initial functional agent template.\n- Registry slug **%s** -> **%s**.\n"
        % (UTC, key, agent_id),
        encoding="utf-8",
    )


def main():
    start_id = 717
    registry_add = {}
    for i, row in enumerate(AGENTS):
        key = row[0]
        agent_id = start_id + i
        write_agent_pack(key, agent_id, row[1], row[2], row[3], row[4], row[5], row[6])
        registry_add[key] = agent_id
        print("OK", key, agent_id)

    reg = json.loads(REGISTRY.read_text(encoding="utf-8"))
    agents = reg["agents"]
    for k, v in registry_add.items():
        if k in agents and agents[k] != v:
            raise SystemExit("Registry conflict: %s already %s" % (k, agents[k]))
        agents[k] = v
    reg["agents"] = dict(sorted(agents.items(), key=lambda kv: kv[1]))
    REGISTRY.write_text(json.dumps(reg, indent=4) + "\n", encoding="utf-8")
    print("Registry updated:", len(registry_add), "agents")


if __name__ == "__main__":
    main()

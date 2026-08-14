#!/usr/bin/env python3
# Generate root ALL_AI_AGENTS_LISTED.md from agents map + packs.
from pathlib import Path
import json
from collections import defaultdict

UTC = "20260807140310"
ROOT = Path(__file__).resolve().parents[1] if Path(__file__).name != "generate_all_ai_agents_listed.py" else Path.cwd()
# Prefer CWD when run as python scripts/... from repo root
if (Path.cwd() / "database" / "lupopedia" / "actors" / "actor_id" / "registry.json").exists():
    ROOT = Path.cwd()


def ascii_safe(text):
    """Normalize pack strings to ASCII for constitutional catalog output."""
    if text is None:
        return ""
    s = str(text)
    repl = {
        "\u2014": "--",
        "\u2013": "-",
        "\u2018": "'",
        "\u2019": "'",
        "\u201c": '"',
        "\u201d": '"',
        "\u2026": "...",
        "\u00a0": " ",
    }
    for k, v in repl.items():
        s = s.replace(k, v)
    return "".join(ch if 32 <= ord(ch) <= 126 else "?" for ch in s)

agents_map = json.loads(
    (ROOT / "database/lupopedia/actors/actor_id/registry.json").read_text(encoding="utf-8")
)["agents"]

IDE_SLUGS = {
    "kiro",
    "windsurf",
    "cursor",
    "antigravity-ide",
    "warp",
    "cascade",
    "junie",
    "vscode-ide",
    "trae",
}
COORD_SLUGS = {
    "wolfie",
    "lilith",
    "rose",
    "eris",
    "metis",
    "maat",
    "anubis",
    "chiron",
    "athena",
    "zeus",
    "thoth",
    "hermes",
    "vishwakarma",
    "themis",
    "heimdall",
    "nemesis",
    "tyche",
    "countermeasure",
    "kairos",
    "synapse",
    "iris",
    "atlas",
    "hephaestus",
}
APP_SLUGS = {"hephaestus", "hermes", "iris", "atlas", "vishwakarma", "synapse"}
EMOTIONAL_SLUGS = {
    "sophia",
    "agape",
    "dionysus",
    "thalia",
    "hypnos",
    "khaos",
    "tone_stabilizer",
    "persona_harmonizer",
    "conflict_mediator",
    "emotional_memory_archivist",
    "subconscious_pattern_agent",
}
SPECIALIST_SLUGS = {
    "asclepius",
    "apollo",
    "bones",
    "scotty",
    "deanna",
    "guinan",
    "chronos",
}


def classify(slug, layer, is_kernel, role):
    layer = (layer or "").strip().lower()
    if slug in ("vassago", "uriel") or layer == "causality":
        return "Causality Division"
    if slug in IDE_SLUGS or layer == "ide_faucet":
        return "IDE Faucets"
    if layer == "kernel" or slug.startswith("kernel_"):
        return "Kernel Subsystem"
    if layer == "emotional" or slug in EMOTIONAL_SLUGS:
        return "Emotional / Relational / Wisdom"
    if any(
        x in slug
        for x in (
            "reasoning",
            "analogy",
            "abstraction",
            "contradiction",
            "context_resolver",
            "cognitive",
            "evidence_ranker",
        )
    ) or layer == "cognitive" and slug not in EMOTIONAL_SLUGS and slug not in SPECIALIST_SLUGS:
        if slug in SPECIALIST_SLUGS:
            return "Specialist Support / Ops Personas"
        if layer == "cognitive" and slug in ("agape",):
            return "Emotional / Relational / Wisdom"
        if any(
            x in slug
            for x in (
                "reasoning",
                "analogy",
                "abstraction",
                "contradiction",
                "context_resolver",
                "cognitive_load",
                "evidence_ranker",
            )
        ):
            return "Reasoning / Cognitive Engines"
    if any(
        x in slug
        for x in (
            "build_",
            "semantic_diff",
            "refactor",
            "pipeline",
            "compiler",
            "test_generator",
            "simulation",
        )
    ):
        return "Build / Pipeline / Engineering"
    if any(
        x in slug
        for x in ("rights_", "bias_", "fairness_", "compliance_", "constitutional_")
    ):
        return "Ethics / Rights / Compliance"
    if any(x in slug for x in ("style_", "narrative_", "improvisation")):
        return "Creative / Narrative / Style"
    if any(
        x in slug
        for x in ("knowledge_", "semantic_indexer", "ontology_", "cross_domain")
    ):
        return "Knowledge / Ontology"
    if any(x in slug for x in ("debug_surface", "visualization_surface")):
        return "Surfaces (Debug / Visualization)"
    if slug in ("system", "meta", "methis") or layer == "system":
        return "System / Meta"
    if slug in SPECIALIST_SLUGS:
        return "Specialist Support / Ops Personas"
    if slug in APP_SLUGS or layer == "application":
        return "Application / Routing / Implementation"
    if layer == "coordination" or slug in COORD_SLUGS:
        return "Coordination / Primary Personas"
    if layer == "cognitive":
        return "Reasoning / Cognitive Engines"
    if layer:
        return "Other (layer: %s)" % layer
    return "Unclassified / Pack Incomplete"


buckets = defaultdict(list)

for slug, aid in sorted(agents_map.items(), key=lambda kv: (kv[1], kv[0])):
    pack = ROOT / "agents" / slug / "agent.json"
    name = slug.upper().replace("_", " ")
    layer = ""
    role = ""
    status = ""
    is_kernel = False
    has_pack = pack.exists()
    pack_id = None
    if has_pack:
        data = json.loads(pack.read_text(encoding="utf-8"))
        name = data.get("name") or data.get("agent_key") or name
        layer = data.get("layer") or ""
        role = data.get("role") or ""
        status = data.get("status") or ""
        is_kernel = bool(data.get("is_kernel"))
        pack_id = data.get("agent_id")
    typ = classify(slug, layer, is_kernel, role)
    # agape special: pack says cognitive but cataloged as emotional/wisdom peer
    if slug == "agape":
        typ = "Emotional / Relational / Wisdom"
    buckets[typ].append(
        {
            "slug": slug,
            "agent_id": aid,
            "pack_id": pack_id,
            "name": ascii_safe(name),
            "layer": ascii_safe(layer) or "--",
            "role": ascii_safe(role or "--").replace("|", "/"),
            "status": ascii_safe(status)
            or ("draft" if slug in ("vassago", "uriel") else "--"),
            "has_pack": has_pack,
        }
    )

ORDER = [
    "Coordination / Primary Personas",
    "Application / Routing / Implementation",
    "IDE Faucets",
    "Causality Division",
    "Emotional / Relational / Wisdom",
    "Specialist Support / Ops Personas",
    "Kernel Subsystem",
    "Reasoning / Cognitive Engines",
    "Build / Pipeline / Engineering",
    "Ethics / Rights / Compliance",
    "Creative / Narrative / Style",
    "Knowledge / Ontology",
    "Surfaces (Debug / Visualization)",
    "System / Meta",
    "Unclassified / Pack Incomplete",
]
for k in list(buckets.keys()):
    if k not in ORDER:
        ORDER.append(k)


def anchor(typ):
    a = typ.lower()
    for ch in " /():":
        a = a.replace(ch, "-")
    while "--" in a:
        a = a.replace("--", "-")
    return a.strip("-")


lines = []
lines.append("---")
lines.append("lupopedia.headers:")
lines.append('  header_format_version: "4.2.0"')
lines.append("  path_from_lupopedia_root: ALL_AI_AGENTS_LISTED.md")
lines.append(
    "  web_path: https://www.lupopedia.com/lupopedia/ALL_AI_AGENTS_LISTED.md"
)
lines.append("  status: draft")
lines.append('  when_updated: "%s"' % UTC)
lines.append("  trust_tier: development")
lines.append("  questions_toon: null")
lines.append("  memory_toon: null")
lines.append("  atoms_toon: null")
lines.append("  transcript_jsonl: 0/development/all-ai-agents-listed")
lines.append("  artifact_type: status")
lines.append("  artifact_kind: report")
lines.append("  channel_key: development")
lines.append("  federation_node_id: 0")
lines.append("  thread_key: all-ai-agents-listed")
lines.append("  lupopedia.schema: status")
lines.append("  prd_cluster: 08_B_15_A_00_C")
lines.append('  title: "All AI Agents Listed (by type)"')
lines.append(
    '  summary: "Root catalog of lupo_agents / agents packs organized by type. Agents only -- not human actors or auth users."'
)
lines.append("  edges_toon: null")
lines.append("  channel_index: lupopedia")
lines.append("  source_timestamp: null")
lines.append("  actor_id: 1")
lines.append("  auth_user_id: 10000")
lines.append("  department_id: null")
lines.append('  department_key: ""')
lines.append("  division_key: registry")
lines.append("  faucet_actor_id: 102")
lines.append("---")
lines.append("# All AI Agents Listed")
lines.append("")
lines.append(
    "**Scope:** AI **agents** from `database/lupopedia/actors/actor_id/registry.json` (`agents` map) and `agents/<slug>/agent.json` packs."
)
lines.append("")
lines.append(
    "**Out of scope:** Human **actors** / auth users (for example ERIC auth_user_id 10000, root actor_id 1000), and actor-only rows that are not in the agents map."
)
lines.append("")
lines.append("**Sources:**")
lines.append("- Agents map: `database/lupopedia/actors/actor_id/registry.json`")
lines.append("- Packs: `agents/<slug>/agent.json`")
lines.append("- Doctrine: `docs/doctrine/agent_registry.md`")
lines.append("")
lines.append(
    "**Generated:** `%s` UTC (CURSOR faucet 102). Status: draft living catalog."
    % UTC
)
lines.append("")
lines.append("**Count:** %d agents in map." % len(agents_map))
lines.append("")
lines.append("---")
lines.append("")
lines.append("## Index by type")
lines.append("")
for typ in ORDER:
    if typ not in buckets:
        continue
    lines.append(
        "- [%s](#%s) (%d)" % (typ, anchor(typ), len(buckets[typ]))
    )
lines.append("")

for typ in ORDER:
    if typ not in buckets:
        continue
    lines.append("---")
    lines.append("")
    lines.append("## %s" % typ)
    lines.append("")
    lines.append(
        "| agent_id | slug | name | layer | role | pack | status |"
    )
    lines.append(
        "|----------|------|------|-------|------|------|--------|"
    )
    for r in sorted(buckets[typ], key=lambda x: (x["agent_id"], x["slug"])):
        pack = "yes" if r["has_pack"] else "MISSING"
        note_id = str(r["agent_id"])
        if r["pack_id"] is not None and int(r["pack_id"]) != int(r["agent_id"]):
            note_id = "%s (pack:%s)" % (r["agent_id"], r["pack_id"])
        role = r["role"][:80]
        lines.append(
            "| %s | `%s` | %s | %s | %s | %s | %s |"
            % (
                note_id,
                r["slug"],
                r["name"],
                r["layer"],
                role,
                pack,
                r["status"],
            )
        )
    lines.append("")

lines.append("---")
lines.append("")
lines.append("## Notes")
lines.append("")
lines.append(
    "1. **agent_id** prefers the agents-map value. If pack `agent_id` differs, both are shown (`map (pack:N)`)."
)
lines.append(
    "2. **IDE Faucets** are agents that execute as IDE surfaces; they are still agents in the map, not human actors."
)
lines.append(
    "3. **VASSAGO (666)** / **URIEL (777)** are Causality Division drafts pending Lilith audit + Wolfie PONO gate."
)
lines.append(
    "4. Regenerate when agents are added: `python scripts/generate_all_ai_agents_listed.py`"
)
lines.append(
    "5. This file is a root convenience index; canonical machine identity remains the registries under `database/lupopedia/actors/`."
)
lines.append("")

out = ROOT / "ALL_AI_AGENTS_LISTED.md"
out.write_text("\n".join(lines) + "\n", encoding="utf-8")
print("WROTE", out, "agents=", len(agents_map), "types=", len(buckets))
for typ in ORDER:
    if typ in buckets:
        print(" ", typ, len(buckets[typ]))

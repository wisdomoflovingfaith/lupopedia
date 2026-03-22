#!/usr/bin/env python3
from __future__ import annotations

from pathlib import Path
from datetime import datetime, timezone
import re

ROOT = Path(__file__).resolve().parents[1]
VERSION = "4.0.85"
NOW = datetime.now(timezone.utc).strftime("%Y%m%d_%H%M%S")

THREADS_ROOT = ROOT / "lupo-channels"
VERSION_DIR = ROOT / "lupo-docs" / "versions" / VERSION

blocked_threads = {
    ("42", "1004"),
    ("42", "1036"),
    ("42", "1037"),
    ("42", "1049"),
}

deferred_threads = {
    ("42", "1030"),
    ("42", "1032"),
    ("42", "1035"),
}

complete_tokens = (
    "closure",
    "complete",
    "completion",
    "resolved",
    "final",
    "lock",
    "pass",
)


def iter_threads():
    rows = []
    for cdir in sorted(THREADS_ROOT.iterdir(), key=lambda p: (0, int(p.name)) if p.name.isdigit() else (1, p.name)):
        if not cdir.is_dir():
            continue
        tdir = cdir / "threads"
        if not tdir.is_dir():
            continue
        for thdir in sorted(tdir.iterdir(), key=lambda p: (0, int(p.name)) if p.name.isdigit() else (1, p.name)):
            if not thdir.is_dir() or not thdir.name.isdigit():
                continue
            rows.append((cdir.name, thdir.name, thdir))
    return rows


def detect_status(channel_id: str, thread_id: str, thread_dir: Path) -> str:
    key = (channel_id, thread_id)
    if key in blocked_threads:
        return "blocked"
    if key in deferred_threads:
        return "deferred"

    for f in thread_dir.glob("*.md"):
        name = f.name.lower()
        if any(tok in name for tok in complete_tokens):
            return "completed"
    return "in-progress"


def channel66_relationships(thread_dir: Path):
    req = 0
    nxt = 0
    for f in thread_dir.glob("*.md"):
        text = f.read_text(encoding="utf-8", errors="ignore")
        req += len(re.findall(r'type:\s*"?requires_reading"?', text))
        nxt += len(re.findall(r'\bnext_action\s*:', text))
    return req, nxt


def yaml_header(file_path_from_root: str, artifact_type: str, artifact_kind: str, purpose: str, edges: list[str]) -> str:
    lines = [
        "---",
        "lupopedia.headers:",
        f'  version_when_written: "{VERSION}"',
        f'  file_path_from_root: "{file_path_from_root}"',
        f'  last_modified_utc: "{NOW}"',
        "  channel_id: 42",
        "  thread_id: 1047",
        "  actor_id: 1",
        '  actor_name: "wolfie"',
        f'  artifact_type: "{artifact_type}"',
        f'  artifact_kind: "{artifact_kind}"',
        f'  purpose: "{purpose}"',
        "",
        "lupopedia.edges:",
        "  outbound_edges:",
    ]
    for edge in edges:
        lines.append(f"    - {{ to: \"{edge}\", type: \"depends_on\" }}")
    lines.extend(["---", ""])
    return "\n".join(lines)


def write(path: Path, text: str):
    path.write_text(text, encoding="utf-8")


def build_tables(rows):
    by_channel = {}
    for c, t, d in rows:
        by_channel.setdefault(c, []).append((t, d))

    channel_lines = ["| channel_id | thread_count | thread_ids |", "|---|---:|---|"]
    for c in sorted(by_channel.keys(), key=lambda x: (0, int(x)) if x.isdigit() else (1, x)):
        ids = [t for t, _ in sorted(by_channel[c], key=lambda x: int(x[0]))]
        channel_lines.append(f"| {c} | {len(ids)} | {', '.join(ids)} |")

    task_rows = []
    for c, t, d in rows:
        status = detect_status(c, t, d)
        node_type = "question" if c == "66" else "task"
        req_count = 0
        next_count = 0
        if c == "66":
            req_count, next_count = channel66_relationships(d)
        dependencies = "thread_1047_global_lock"
        if status == "blocked":
            dependencies += ";thread_1049_reaudit_gate"
        upstream = "filesystem_thread_inventory"
        downstream = "root_and_version_docs_synchronized"
        cross_channel = "none"
        if c == "66":
            upstream += ";channel66_question_context"
            downstream += ";question_node_preserved"
            cross_channel = "channel42_execution_registry"
        elif c == "42":
            upstream += ";channel42_execution_context"
            downstream += ";execution_node_preserved"
            cross_channel = "channel66_question_graph"
        else:
            cross_channel = "channel42_core_registry"

        task_rows.append(
            {
                "task_id": f"task_ch{c}_th{t}",
                "channel_id": c,
                "thread_id": t,
                "node_type": node_type,
                "status": status,
                "required_reading_count": req_count,
                "next_action_count": next_count,
                "dependencies": dependencies,
                "upstream_requirements": upstream,
                "downstream_outcomes": downstream,
                "cross_channel_relationships": cross_channel,
                "source": d.relative_to(ROOT).as_posix() + "/",
            }
        )

    return channel_lines, task_rows


def task_table(task_rows):
    lines = [
        "| task_id | channel_id | thread_id | node_type | status | required_reading_count | next_action_count | dependencies | upstream_requirements | downstream_outcomes | cross_channel_relationships | source |",
        "|---|---:|---:|---|---|---:|---:|---|---|---|---|---|",
    ]
    for r in sorted(task_rows, key=lambda x: (int(x["channel_id"]), int(x["thread_id"]))):
        lines.append(
            f"| {r['task_id']} | {r['channel_id']} | {r['thread_id']} | {r['node_type']} | {r['status']} | {r['required_reading_count']} | {r['next_action_count']} | {r['dependencies']} | {r['upstream_requirements']} | {r['downstream_outcomes']} | {r['cross_channel_relationships']} | {r['source']} |"
        )
    return lines


def status_split(task_rows):
    out = {"completed": [], "in-progress": [], "blocked": [], "deferred": []}
    for r in task_rows:
        out[r["status"]].append(r)
    return out


def status_table(rows):
    lines = [
        "| task_id | channel_id | thread_id | node_type | dependencies | upstream_requirements | downstream_outcomes | cross_channel_relationships | source |",
        "|---|---:|---:|---|---|---|---|---|---|",
    ]
    for r in sorted(rows, key=lambda x: (int(x["channel_id"]), int(x["thread_id"]))):
        lines.append(
            f"| {r['task_id']} | {r['channel_id']} | {r['thread_id']} | {r['node_type']} | {r['dependencies']} | {r['upstream_requirements']} | {r['downstream_outcomes']} | {r['cross_channel_relationships']} | {r['source']} |"
        )
    return lines


def main():
    rows = iter_threads()
    channel_lines, task_rows = build_tables(rows)
    split = status_split(task_rows)

    total_threads = len(rows)
    channel66_threads = [r for r in task_rows if r["channel_id"] == "66"]

    # Root CHANGELOG
    changelog = []
    changelog.append(
        yaml_header(
            "CHANGELOG.md",
            "changelog",
            "history",
            "Root changelog synchronized to live cross-channel state under global stop.",
            [
                "TODO.md",
                "plan.md",
                "lupo-docs/versions/4.0.85/CHANGELOG.md",
                "lupo-docs/versions/4.0.85/TASK_REGISTRY.md",
            ],
        )
    )
    changelog.append("# Lupopedia CHANGELOG")
    changelog.append("")
    changelog.append("## [4.0.85] - Global Stop Synchronization Lock (20260321)")
    changelog.append("")
    changelog.append("Global status: GLOBAL STOP ACTIVE. Only documentation synchronization is authorized.")
    changelog.append("")
    changelog.append("### Synchronization results")
    changelog.append(f"- Live thread directories synchronized: {total_threads}.")
    changelog.append(f"- Channel 66 question threads synchronized: {len(channel66_threads)}.")
    changelog.append("- Root + version planning surfaces regenerated from live filesystem thread inventory.")
    changelog.append("- Timestamp enforcement remains fixed; structural work is documentation-only in this pass.")
    changelog.append("")
    changelog.append("### Status separation")
    changelog.append(f"- completed: {len(split['completed'])}")
    changelog.append(f"- in-progress: {len(split['in-progress'])}")
    changelog.append(f"- blocked: {len(split['blocked'])}")
    changelog.append(f"- deferred_to_4_0_86: {len(split['deferred'])}")
    changelog.append("")
    changelog.append("### Channel coverage")
    changelog.extend(channel_lines)
    changelog.append("")
    write(ROOT / "CHANGELOG.md", "\n".join(changelog) + "\n")

    # Root TODO
    todo = []
    todo.append(
        yaml_header(
            "TODO.md",
            "task_list",
            "global_reconciliation_todo",
            "Root task registry synchronized to all channel/thread nodes for 4.0.85.",
            [
                "plan.md",
                "lupo-docs/versions/4.0.85/TASK_REGISTRY.md",
                "lupo-docs/versions/4.0.85/TODO.md",
            ],
        )
    )
    todo.append("# Root TODO - Global Synchronization Lock")
    todo.append("")
    todo.append(f"Total synchronized thread tasks: {total_threads}")
    todo.append("")
    todo.append("## Completed")
    todo.extend(status_table(split["completed"]))
    todo.append("")
    todo.append("## In-Progress")
    todo.extend(status_table(split["in-progress"]))
    todo.append("")
    todo.append("## Blocked")
    todo.extend(status_table(split["blocked"]))
    todo.append("")
    todo.append("## Deferred to 4.0.86+")
    todo.extend(status_table(split["deferred"]))
    todo.append("")
    todo.append("## Channel 66 Question Nodes")
    todo.append("| question_node_id | thread_id | required_reading_count | next_action_count | dependency | downstream |")
    todo.append("|---|---:|---:|---:|---|---|")
    for r in sorted(channel66_threads, key=lambda x: int(x["thread_id"])):
        todo.append(
            f"| qnode_66_{r['thread_id']} | {r['thread_id']} | {r['required_reading_count']} | {r['next_action_count']} | {r['dependencies']} | {r['downstream_outcomes']} |"
        )
    todo.append("")
    write(ROOT / "TODO.md", "\n".join(todo) + "\n")

    # Root plan
    plan = []
    plan.append(
        yaml_header(
            "plan.md",
            "plan",
            "global_stop_reconciliation_plan",
            "Dependency-driven full-system synchronization plan across all channels and threads.",
            [
                "TODO.md",
                "lupo-docs/versions/4.0.85/PLAN.md",
                "lupo-docs/versions/4.0.85/TASK_REGISTRY.md",
            ],
        )
    )
    plan.append("# Root Plan - Full-System Synchronization")
    plan.append("")
    plan.append("## Version 4.0.85 Current-State Plan")
    plan.append("1. Lock execution to documentation-only synchronization.")
    plan.append("2. Enumerate all channel/thread nodes from live filesystem.")
    plan.append("3. Synchronize root and version planning surfaces from the same inventory.")
    plan.append("4. Preserve Channel 66 question-node relationships (required_reading, next_action).")
    plan.append("5. Revalidate structural consistency gates (headers, links, registry parity).")
    plan.append("")
    plan.append("## Version 4.0.86 Next-Phase Plan")
    plan.append("1. Resume implementation only after re-audit pass state is COMPLIANT.")
    plan.append("2. Execute deferred tasks and blocked-gate clearances.")
    plan.append("3. Add automation for continuous thread-registry synchronization.")
    plan.append("")
    plan.append("## Global Channel Coverage")
    plan.extend(channel_lines)
    plan.append("")
    plan.append("## Full Task/Question Node Manifest")
    plan.extend(task_table(task_rows))
    plan.append("")
    write(ROOT / "plan.md", "\n".join(plan) + "\n")

    # Version file payloads
    full_table = task_table(task_rows)
    c66_table = [
        "| task_id | thread_id | required_reading_count | next_action_count | source |",
        "|---|---:|---:|---:|---|",
    ]
    for r in sorted(channel66_threads, key=lambda x: int(x["thread_id"])):
        c66_table.append(
            f"| {r['task_id']} | {r['thread_id']} | {r['required_reading_count']} | {r['next_action_count']} | {r['source']} |"
        )

    version_specs = {
        "TASK_REGISTRY.md": ("registry", "task_registry", "Canonical 4.0.85 task registry synchronized to all channels and threads."),
        "TODO.md": ("task_list", "version_todo", "Version-scoped TODO synchronized from live thread inventory."),
        "PLAN.md": ("execution_plan", "version_plan", "Version-scoped deterministic plan for 4.0.85 and 4.0.86."),
        "CHANGELOG.md": ("changelog", "version_changelog", "Version-scoped changelog synchronized to global stop state."),
        "IMPLEMENTATION_STATUS.md": ("status", "implementation_status", "Implementation status matrix under documentation-only lock."),
        "ACTIVE_WORKSTREAMS.md": ("status", "active_workstreams", "Active workstreams mapped to thread nodes and channels."),
        "MIGRATION_WORKFLOW.md": ("workflow", "migration_workflow", "Migration workflow synchronized with documentation-only constraints."),
        "WEB_INTERFACE_PLAN.md": ("plan", "web_interface_plan", "Web interface documentation plan synchronized with channel graph."),
        "OVERVIEW.md": ("overview", "version_overview", "Version 4.0.85 synchronized overview across all channels."),
        "OVERVIEW_ORGANIZATION.md": ("overview", "organization_overview", "Organizational overview synchronized across channels and threads."),
        "TASK_BREAKDOWN.md": ("task_list", "task_breakdown", "Detailed task breakdown synchronized to live registry."),
        "SYSTEM_STATE_SNAPSHOT.md": ("snapshot", "system_state_snapshot", "Deterministic system snapshot after full synchronization."),
    }

    for fname, spec in version_specs.items():
        artifact_type, artifact_kind, purpose = spec
        rel = f"lupo-docs/versions/{VERSION}/{fname}"
        body = []
        body.append(
            yaml_header(
                rel,
                artifact_type,
                artifact_kind,
                purpose,
                [
                    "lupo-docs/versions/4.0.85/TASK_REGISTRY.md",
                    "lupo-docs/versions/4.0.85/TODO.md",
                    "lupo-docs/versions/4.0.85/PLAN.md",
                    "TODO.md",
                    "plan.md",
                ],
            )
        )
        body.append(f"# 4.0.85 {fname.replace('.md', '').replace('_', ' ')}")
        body.append("")
        body.append("## Synchronized Metrics")
        body.append(f"- threads_detected: {total_threads}")
        body.append(f"- channel66_question_threads: {len(channel66_threads)}")
        body.append(f"- completed: {len(split['completed'])}")
        body.append(f"- in_progress: {len(split['in-progress'])}")
        body.append(f"- blocked: {len(split['blocked'])}")
        body.append(f"- deferred_to_4_0_86: {len(split['deferred'])}")
        body.append("")
        body.append("## Channel Coverage")
        body.extend(channel_lines)
        body.append("")
        body.append("## Channel 66 Question Relationships")
        body.extend(c66_table)
        body.append("")
        body.append("## Full Task/Question Registry")
        body.extend(full_table)
        body.append("")
        write(VERSION_DIR / fname, "\n".join(body) + "\n")

    print(f"threads_detected={total_threads}")
    print(f"channel66_threads={len(channel66_threads)}")
    print("updated_root_files=3")
    print(f"updated_version_files={len(version_specs)}")


if __name__ == "__main__":
    main()

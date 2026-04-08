#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Generate PRD shorthand files (Purpose 1 — Cave-Man Shorthand) for all PRDs.

Usage:
    python lupo-scripts/generate_prd_shorthands.py --prd 38
    python lupo-scripts/generate_prd_shorthands.py --stem 04_tags_metadata
    python lupo-scripts/generate_prd_shorthands.py --all
    python lupo-scripts/generate_prd_shorthands.py --check
    python lupo-scripts/generate_prd_shorthands.py --all --force

Staleness: Prefer PRD `when_updated` / `last_modified_utc` (14-digit UTC) vs shorthand
`lupopedia.footer.last_verified`; if either is missing, fall back to file mtime.
Use --force to ignore.

Skip list: Default SKIP_NAMES; extend via --skip-name (repeatable) or env
LUPO_PRD_SHORTHAND_EXTRA_SKIP (comma-separated basenames).
"""

from __future__ import annotations

import argparse
import os
import re
import sys
from dataclasses import dataclass
from datetime import datetime, timezone
from pathlib import Path
from typing import Dict, List, Optional, Tuple

try:
    import yaml
except ImportError:
    sys.stderr.write("PyYAML required: pip install -r lupo-scripts/requirements.txt\n")
    sys.exit(1)

# Configuration
PROJECT_ROOT = Path(__file__).resolve().parent.parent
PRD_DIR = PROJECT_ROOT / "lupo-docs" / "prd"
PSEUDOCODE_DIR = PRD_DIR / "decisions" / "pseudocode"
THREAD_INDEX = PSEUDOCODE_DIR / "THREAD_INDEX.md"

DEFAULT_SKIP_NAMES = frozenset(
    {
        "PRD_INDEX.md",
        "README.md",
        "WHAT_TO_DO_NEXT.md",
        "PRD_AGENT_DEFINITION_MODEL.md",
    }
)


def effective_skip_names(extra: Optional[List[str]] = None) -> frozenset:
    """Default skip set plus env LUPO_PRD_SHORTHAND_EXTRA_SKIP and CLI --skip-name."""
    out = set(DEFAULT_SKIP_NAMES)
    env_extra = os.environ.get("LUPO_PRD_SHORTHAND_EXTRA_SKIP", "").strip()
    if env_extra:
        for part in env_extra.split(","):
            p = part.strip()
            if p:
                out.add(p)
    if extra:
        for p in extra:
            if p:
                out.add(p)
    return frozenset(out)

# PRDs that should include the Actor Model section (numeric id from NN_ prefix)
ACTOR_MODEL_PRDS = {1, 5, 15, 25, 38}

# Reserved ID bands for shorthand digest only. Values are prose pointers, not numeric
# formulas — authoritative bands are PRD 41, PRD 00 §3.2.1, and actors/registry.json.
RESERVED_RANGES = {
    "lupo_actors": "< 2026 vs runtime (see PRD 01 / registry)",
    "lupo_agents": "per registry / PRD 07",
    "lupo_departments": "0-1 typical",
    "lupo_channels": "per channel registry",
    "lupo_auth_users": "per PRD 01 / seed (root id per install)",
    "lupo_memory_nodes": "per PRD 38",
    "lupo_edges": "IdGenerator at runtime",
    "lupo_permissions": "per seed",
    "lupo_rules": "per seed",
}


def get_current_utc() -> str:
    """Return current UTC timestamp as YYYYMMDDHHIISS (14 digits)."""
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def split_frontmatter(text: str) -> Tuple[Optional[str], str]:
    """Split YAML frontmatter; support PRDs without closing --- before body.

    Markdown horizontal rules (---) in the body must not end the YAML block:
    only a --- line *before* the first top-level H1 (# ) counts as closing.
    """
    if not text.startswith("---"):
        return None, text
    lines = text.splitlines()
    if lines[0].strip() != "---":
        return None, text
    header_cap = min(len(lines), 260)
    h1_idx = None
    for i in range(1, len(lines)):
        if re.match(r"^#\s+\S", lines[i]):
            h1_idx = i
            break
    search_end = min(header_cap, h1_idx if h1_idx is not None else header_cap)
    end_idx = None
    for i in range(1, search_end):
        if lines[i].strip() == "---":
            end_idx = i
            break
    if end_idx is not None:
        return "\n".join(lines[1:end_idx]), "\n".join(lines[end_idx + 1 :]).lstrip("\n")
    if h1_idx is not None:
        return "\n".join(lines[1:h1_idx]), "\n".join(lines[h1_idx:])
    for i in range(1, header_cap):
        if lines[i].strip() == "---":
            return "\n".join(lines[1:i]), "\n".join(lines[i + 1 :]).lstrip("\n")
    return None, text


def parse_header_utc14(header: Dict, *keys: str) -> Optional[str]:
    """Return first 14-digit UTC string found for given header keys."""
    for key in keys:
        v = header.get(key)
        if v is None:
            continue
        s = str(v).strip().strip('"').strip("'")
        if re.match(r"^\d{14}$", s):
            return s
    return None


def parse_full_frontmatter_dict(content: str) -> Dict:
    """Parse entire YAML frontmatter block to a dict (empty if parse fails)."""
    ym, _ = split_frontmatter(content)
    if not ym:
        return {}
    try:
        data = yaml.safe_load(ym)
    except yaml.YAMLError:
        return {}
    return data if isinstance(data, dict) else {}


def parse_shorthand_footer_last_verified(shorthand_path: Path) -> Optional[str]:
    """14-digit UTC from generated shorthand `lupopedia.footer.last_verified`."""
    try:
        text = shorthand_path.read_text(encoding="utf-8")
    except OSError:
        return None
    data = parse_full_frontmatter_dict(text)
    footer = data.get("lupopedia.footer")
    if not isinstance(footer, dict):
        return None
    v = footer.get("last_verified")
    if v is None:
        return None
    s = str(v).strip().strip('"').strip("'")
    if re.match(r"^\d{14}$", s):
        return s
    return None


def parse_prd_header(content: str, file_path: str) -> Dict:
    """Parse LUPOPEDIA HEADERS from PRD file (lupopedia.headers YAML key)."""
    ym, _ = split_frontmatter(content)
    if not ym:
        print("Warning: No YAML frontmatter in %s" % file_path)
        return {}
    try:
        data = yaml.safe_load(ym)
    except yaml.YAMLError as e:
        print("Warning: YAML parsing error in %s: %s" % (file_path, e))
        return {}
    if not isinstance(data, dict):
        return {}
    h = data.get("lupopedia.headers") or data.get("lupopedia.headers:")
    if isinstance(h, dict):
        return h
    return {}


def parse_prd_filename(filename: str) -> Optional[Tuple[str, str, int]]:
    """Parse '38_memory_unification.md' -> ('38', 'memory_unification', 38)."""
    m = re.match(r"^(\d{2})_(.+)\.md$", filename)
    if not m:
        return None
    prefix, slug = m.group(1), m.group(2)
    return prefix, slug, int(prefix, 10)


def extract_title_from_body(content: str, header: Dict, slug: str) -> str:
    ym, body = split_frontmatter(content)
    for line in body.splitlines():
        line = line.strip()
        if line.startswith("# ") and not line.startswith("## "):
            return line[2:].strip()
    purpose = header.get("purpose") or ""
    if purpose:
        return purpose.split("—")[0].strip()[:120]
    return slug.replace("_", " ").title()


def extract_core_rules(content: str, header: Dict) -> List[str]:
    """Extract core rules from PRD content."""
    _, body = split_frontmatter(content)
    rules: List[str] = []
    sections = re.split(r"\n##+\s+", body)
    for section in sections:
        section_lower = section.lower()
        if any(
            k in section_lower
            for k in (
                "rule",
                "constitutional",
                "requirement",
                "doctrine",
                "must",
                "shall",
                "forbidden",
            )
        ):
            bullets = re.findall(r"^[-*]\s+(.+)$", section, re.MULTILINE)
            for bullet in bullets[:5]:
                if len(bullet) < 200:
                    rules.append(bullet.strip())
    if not rules and header:
        purpose = header.get("purpose", "")
        if purpose:
            rules.append(purpose)
    return rules[:10]


def extract_tables(content: str) -> List[Tuple[str, str]]:
    """Extract table names and purposes from PRD content."""
    _, body = split_frontmatter(content)
    tables: List[Tuple[str, str]] = []
    create_pattern = r"CREATE\s+TABLE\s+(\w+)"
    for match in re.finditer(create_pattern, body, re.IGNORECASE):
        table_name = match.group(1).replace("{{prefix}}", "lupo_")
        if table_name not in [t[0] for t in tables]:
            tables.append((table_name, "Created by this PRD (see install SQL)"))
    table_pattern = re.compile(r"\|\s*([a-z_`]+)\s*\|\s*([^|]+)\|")
    lines = body.split("\n")
    in_table = False
    for line in lines:
        if "| Table | Purpose |" in line or "| Table | Description |" in line:
            in_table = True
            continue
        if in_table and line.strip().startswith("|") and re.match(r"^\|\s*-+", line.strip()):
            continue
        if in_table and line.startswith("|"):
            m = table_pattern.search(line)
            if m:
                table_name = m.group(1).strip().strip("`")
                purpose = m.group(2).strip()
                if table_name not in [t[0] for t in tables]:
                    tables.append((table_name, purpose[:80]))
        elif in_table and line.strip() and not line.startswith("|"):
            in_table = False
    return tables[:10]


def extract_forbidden_patterns(content: str) -> List[str]:
    _, body = split_frontmatter(content)
    patterns: List[str] = []
    forbidden_matches = re.findall(r"[❌]\s*\**([^*\n]+)\**", body)
    patterns.extend([f.strip() for f in forbidden_matches[:5]])
    no_matches = re.findall(r"NO\s+([A-Z_]+|`[^`]+`)", body, re.IGNORECASE)
    for match in no_matches[:5]:
        patterns.append("NO %s" % match)
    if "FORBIDDEN" in body.upper():
        patterns.append("See PRD for complete forbidden list")
    out: List[str] = []
    seen = set()
    for p in patterns:
        if p not in seen:
            seen.add(p)
            out.append(p)
    return out[:8]


def extract_required_patterns(content: str) -> List[str]:
    _, body = split_frontmatter(content)
    patterns: List[str] = []
    required_matches = re.findall(r"[✅]\s*\**([^*\n]+)\**", body)
    patterns.extend([f.strip() for f in required_matches[:5]])
    must_matches = re.findall(r"MUST\s+([^.\n]+)", body, re.IGNORECASE)
    for match in must_matches[:5]:
        patterns.append("MUST %s" % match.strip())
    out: List[str] = []
    seen = set()
    for p in patterns:
        if p not in seen:
            seen.add(p)
            out.append(p)
    return out[:8]


def extract_outbound_edges_from_yaml(content: str) -> List[Tuple[str, str, str]]:
    """Edges from PRD frontmatter `lupopedia.edges.outbound_edges` (if present)."""
    data = parse_full_frontmatter_dict(content)
    block = data.get("lupopedia.edges")
    if not isinstance(block, dict):
        return []
    outbound = block.get("outbound_edges")
    if not isinstance(outbound, list):
        return []
    out: List[Tuple[str, str, str]] = []
    for item in outbound:
        if not isinstance(item, dict):
            continue
        to_val = item.get("to")
        if not to_val:
            continue
        to_str = str(to_val).strip()
        label = to_str.split("/")[-1] if "/" in to_str else to_str
        etype = str(item.get("type") or "reference").strip()
        reason = item.get("reason")
        meaning = (str(reason).strip()[:200] if reason else "from lupopedia.edges YAML")
        out.append((label, etype, meaning))
    return out[:15]


def extract_edge_types(content: str) -> List[Tuple[str, str, str]]:
    """Edge types from markdown body (tables + edge_type mentions); see also YAML edges."""
    _, body = split_frontmatter(content)
    edges: List[Tuple[str, str, str]] = []
    edge_pattern = r"edge_type[\s]*[\'\"]?(\w+)[\'\"]?"
    for match in re.finditer(edge_pattern, body, re.IGNORECASE):
        edge_type = match.group(1)
        edges.append((edge_type, "unidirectional", "Defined in PRD"))
    lines = body.split("\n")
    in_table = False
    for line in lines:
        if "| Edge | Direction | Meaning |" in line or "| edge_type | direction | meaning |" in line.lower():
            in_table = True
            continue
        if in_table and line.startswith("|") and re.match(r"^\|\s*-+", line.strip()):
            continue
        if in_table and line.startswith("|"):
            parts = [p.strip() for p in line.split("|")[1:-1]]
            if len(parts) >= 3:
                edges.append((parts[0], parts[1], parts[2]))
        elif in_table and line.strip() and not line.startswith("|"):
            in_table = False
    return edges[:10]


def merge_edge_rows(
    yaml_rows: List[Tuple[str, str, str]],
    body_rows: List[Tuple[str, str, str]],
    limit: int = 12,
) -> List[Tuple[str, str, str]]:
    """YAML outbound_edges first, then body-derived; dedupe by (col0, col1, col2[:48])."""
    merged: List[Tuple[str, str, str]] = []
    seen = set()
    for row in yaml_rows + body_rows:
        key = (row[0], row[1], row[2][:48] if row[2] else "")
        if key in seen:
            continue
        seen.add(key)
        merged.append(row)
        if len(merged) >= limit:
            break
    return merged


def thread_id_for(prefix: str, slug: str) -> str:
    return "pseudocode-%s-%s" % (prefix, slug.replace("_", "-"))


def generate_shorthand_content(
    prd_prefix: str,
    prd_int: int,
    slug: str,
    title: str,
    content: str,
    header: Dict,
) -> str:
    """Generate shorthand markdown content for a PRD."""
    current_utc = get_current_utc()
    _, body = split_frontmatter(content)

    core_rules = extract_core_rules(content, header)
    tables = extract_tables(content)
    forbidden = extract_forbidden_patterns(content)
    required = extract_required_patterns(content)
    edges = merge_edge_rows(
        extract_outbound_edges_from_yaml(content),
        extract_edge_types(content),
    )

    purpose = header.get("purpose", "")
    if purpose:
        one_line = purpose[:200] + ("..." if len(purpose) > 200 else "")
    else:
        one_line = "Constitutional requirements for %s" % title

    include_actor_model = prd_int in ACTOR_MODEL_PRDS
    out_name = "%s_%s_constitution.pseudo.md" % (prd_prefix, slug)
    rel_prd = "%s_%s.md" % (prd_prefix, slug)

    yaml_header = """---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/prd/decisions/pseudocode/%s"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/decisions/pseudocode/%s"
  when_updated: "%s"
  last_modified_utc: "%s"
  federation_node_id: 0
  channel_id: 42
  thread_id: "%s"
  author:
    type: "actor"
    id: 116
    name: "CLAUDE"
  actor_id: 116
  actor_name: "CLAUDE"
  delegation_chain: "claude:shorthand"
  artifact_type: "documentation"
  artifact_kind: "pseudocode"
  purpose: "Token-efficient shorthand for PRD %s — %s"
  tags:
    - pseudocode
    - constitution
    - shorthand
    - "prd-%s"
lupopedia.edges:
  outbound_edges:
    - to: "../%s"
      type: summarizes
      weight: 1.0
      reason: "Shorthand digest of canonical PRD"
lupopedia.footer:
  last_verified: "%s"
  verified_by:
    actor_id: 116
    agent_name_identity: "Claude Code"
  orchestrator: "claude:shorthand"
---
""" % (
        out_name,
        out_name,
        current_utc,
        current_utc,
        thread_id_for(prd_prefix, slug),
        prd_int,
        title.replace('"', "'")[:200],
        prd_prefix,
        rel_prd,
        current_utc,
    )

    md_body = "# PRD %s: %s — Shorthand\n\n## One-Line Summary\n\n%s\n\n## Core Rules\n\n" % (
        prd_int,
        title,
        one_line,
    )
    for rule in core_rules:
        md_body += "- %s\n" % rule

    if tables:
        md_body += "\n## Key Tables\n\n| Table | Purpose |\n|-------|---------|\n"
        for table_name, purpose_text in tables:
            md_body += "| `%s` | %s |\n" % (table_name, purpose_text)

    if forbidden:
        md_body += "\n## Forbidden Patterns\n\n"
        for pattern in forbidden:
            md_body += "- ❌ %s\n" % pattern

    if required:
        md_body += "\n## Required Patterns\n\n"
        for pattern in required:
            md_body += "- ✅ %s\n" % pattern

    if edges:
        md_body += "\n## Edge Types\n\n| Edge | Direction | Meaning |\n|------|-----------|---------|\n"
        for edge_type, direction, meaning in edges:
            md_body += "| `%s` | %s | %s |\n" % (edge_type, direction, meaning)

    if include_actor_model:
        md_body += """
## Actor Model (3-Layer Identity)

| Layer | Storage | Can Learn? | Scope |
|-------|---------|------------|-------|
| **Auth User** | `lupo_auth_users` | N/A | Human account, belongs to departments |
| **Actor** | `lupo_actors` + workspace | Yes | Runtime persona, department-scoped, shared |
| **Agent** | `lupo-agents/{agent_key}/` | No | Immutable template, filesystem |

### Rules

- Actors belong to departments (`lupo_actor_departments`)
- Auth users belong to departments (`lupo_auth_user_departments`)
- User can act as Actor **only if** departments intersect
- Root department (0) has full access
- Terminal/IDE agents: resolve `auth_user_id` from session/seed (do not hardcode)
- Memory is polymorphic: `owner_type = 'actor'` (learned) OR `'agent'` (baseline)

"""

    if prd_int in (0, 1, 38):
        md_body += """
## Seed Data vs Runtime Records

| Record Type | PK Format | `created_ymdhis` | Origin |
|-------------|-----------|------------------|--------|
| **Seed** (install) | Fixed low ids per registry/install | 0 or install UTC | `install_new_lupopedia.sql` / seed |
| **Runtime** | `IdGenerator::generate()` | 14-digit prefix of PK | Application code |

**Reserved / band notes (prose only here; numeric bands = PRD 41 + registry + PRD 00 §3.2.1):**
"""
        for table, range_str in RESERVED_RANGES.items():
            md_body += "- `%s`: %s\n" % (table, range_str)
        md_body += """
When `created_ymdhis = 0`, memory export may use `1970/01/` as documented pre-history path (see PRD 38 / MemoryExportService).
"""

    if prd_int == 38:
        md_body += """
## Memory trust tiers (Chronological Trust Ladder pattern)

| Tier | PK / year signal | Trust | Can modify after commit? |
|------|------------------|-------|---------------------------|
| **Install / seed** | Low id **1–2025** (not timestamp-shaped) | Highest (canonical) | No |
| **Living canonical** | **18-digit** id, year **1000–1999** (e.g. **1026** after archive or merge) | High — best current knowledge | **Yes** (**UPDATE** as new evidence arrives) |
| **Staging / runtime** | **18-digit** id, embedded year **2000–2099** (raw **`IdGenerator`**) | Low — temporary | Yes; merged then **soft-deleted** (or never inserted if **`toCanonicalId`** pre-persist) |

**Id rule:** **`toCanonicalId(IdGenerator::generate())`** → living canonical (embedded year **1000–1999**). See **PRD 38 §4.2.1**.

**Consolidation:** staging → if no canonical, **promote** via **`toCanonicalId($stagingId)`** or fresh generator + transform; if canonical exists, **UPDATE** canonical + edges → soft-delete staging (**PRD 38 §4.2**, **PRD 00 §3.7**). **Query priority:** living canonical → staging → seed for actor-specific “best” row.

## Long-Term Archiving (Option B)

| Era | Year in id (first 4 digits) | Export path |
|-----|------------------------------|-------------|
| **Runtime / staging** | **2000–2099** | `lupo-memory/{YYYY}/MM/` |
| **Living canonical / archive** | **1000–1999** | `lupo-memory/{YYYY}/MM/` |
| **Seed / pre-history** | Low ids / `created_ymdhis = 0` | `lupo-memory/1970/01/` |

**Rule:** **`toCanonicalId` / `toLongTermId`** — subtract **1000** from embedded year when **≥ 2000**. Keep **`created_ymdhis`** as the **14-digit prefix** of the new PK. Link original → archived with **`archived_to`** (§8); distinguish from merge with **`consolidated_into`**.

**CLI (PRD 24 §5.8–5.9):**

    php lupo-bin/lupo.php memory archive --memory-id <runtime_id>
    php lupo-bin/lupo.php memory archive --actor <id> --older-than <days> [--dry-run]
    php lupo-bin/lupo.php memory restore --memory-id <archived_id>

"""

    md_body += "\n## Constitutional Cross-References\n\n"
    md_body += "- See PRD 00 for root rules\n"
    if prd_int != 5:
        md_body += "- See PRD 05 for auth/actor transformation\n"
    if prd_int != 15:
        md_body += "- See PRD 15 for actor lifecycle\n"
    if prd_int != 25:
        md_body += "- See PRD 25 for departments\n"
    if prd_int != 38:
        md_body += "- See PRD 38 for memory unification\n"

    md_body += """
## Token-Efficient Checklist

- [ ] Read full PRD for complete context
- [ ] Apply core rules above
- [ ] Check forbidden patterns
- [ ] Verify required patterns
- [ ] Cross-reference with related PRDs

---
*Auto-generated by `lupo-scripts/generate_prd_shorthands.py`*
*Source: `lupo-docs/prd/%s`*
*Last sync: %s*
""" % (
        rel_prd,
        current_utc,
    )

    return yaml_header + md_body


@dataclass
class PrdRow:
    prd_prefix: str
    slug: str
    prd_int: int
    title: str
    header: Dict
    content: str
    file_path: Path


def get_all_prds(
    stem: Optional[str] = None,
    skip_names: Optional[frozenset] = None,
) -> List[PrdRow]:
    rows: List[PrdRow] = []
    skip = skip_names if skip_names is not None else effective_skip_names()
    for file_path in sorted(PRD_DIR.glob("*.md")):
        name = file_path.name
        if name in skip:
            continue
        parsed = parse_prd_filename(name)
        if not parsed:
            continue
        prd_prefix, slug, prd_int = parsed
        if stem is not None and name != "%s.md" % stem:
            continue
        content = file_path.read_text(encoding="utf-8")
        header = parse_prd_header(content, name)
        title = header.get("title") or extract_title_from_body(content, header, slug)
        rows.append(
            PrdRow(
                prd_prefix=prd_prefix,
                slug=slug,
                prd_int=prd_int,
                title=title,
                header=header,
                content=content,
                file_path=file_path,
            )
        )
    rows.sort(key=lambda r: (r.prd_prefix, r.slug))
    return rows


def get_shorthand_path(prd_prefix: str, slug: str) -> Path:
    return PSEUDOCODE_DIR / ("%s_%s_constitution.pseudo.md" % (prd_prefix, slug))


def is_stale(prd_path: Path, shorthand_path: Path, prd_header: Dict) -> bool:
    """True if shorthand missing, PRD header UTC newer than shorthand footer UTC, or mtime newer."""
    if not shorthand_path.exists():
        return True
    prd_utc = parse_header_utc14(prd_header, "when_updated", "last_modified_utc")
    sh_utc = parse_shorthand_footer_last_verified(shorthand_path)
    if prd_utc is not None and sh_utc is not None:
        return prd_utc > sh_utc
    return prd_path.stat().st_mtime > shorthand_path.stat().st_mtime


def generate_shorthand(row: PrdRow, force: bool = False) -> bool:
    """Generate shorthand file for a single PRD. Returns True if written."""
    shorthand_path = get_shorthand_path(row.prd_prefix, row.slug)
    if not force and shorthand_path.exists() and not is_stale(row.file_path, shorthand_path, row.header):
        return False
    shorthand_content = generate_shorthand_content(
        row.prd_prefix,
        row.prd_int,
        row.slug,
        row.title,
        row.content,
        row.header,
    )
    PSEUDOCODE_DIR.mkdir(parents=True, exist_ok=True)
    shorthand_path.write_text(shorthand_content, encoding="utf-8", newline="\n")
    return True


def update_thread_index(rows: List[PrdRow]) -> None:
    """Update THREAD_INDEX.md with current shorthand files."""
    utc = get_current_utc()
    lines = [
        "---",
        "lupopedia.headers:",
        "  lupopedia.schema: documentation",
        "  file_path_from_root: lupo-docs/prd/decisions/pseudocode/THREAD_INDEX.md",
        '  when_updated: "%s"' % utc,
        '  last_modified_utc: "%s"' % utc,
        "  channel_id: 42",
        "  actor_id: 116",
        "  purpose: Index of PRD *_constitution.pseudo.md shorthands",
        "lupopedia.footer:",
        '  last_verified: "%s"' % utc,
        "  verified_by:",
        "    actor_id: 116",
        "---",
        "",
        "# THREAD_INDEX — PRD pseudocode constitution shorthands",
        "",
        "Regenerate via `python lupo-scripts/generate_prd_shorthands.py --all`.",
        "",
        "| File | PRD | Purpose (truncated) | Last sync |",
        "|------|-----|---------------------|-----------|",
    ]
    for row in rows:
        shorthand_path = get_shorthand_path(row.prd_prefix, row.slug)
        if not shorthand_path.exists():
            continue
        text = shorthand_path.read_text(encoding="utf-8")
        m = re.search(r'last_verified:\s*["\']?(\d{14})', text)
        last_sync = m.group(1) if m else "unknown"
        purpose = (row.header.get("purpose") or row.title)[:60].replace("|", "\\|")
        fn = shorthand_path.name
        lines.append("| `%s` | PRD %s | %s | %s |" % (fn, row.prd_prefix, purpose, last_sync))
    lines.append("")
    THREAD_INDEX.parent.mkdir(parents=True, exist_ok=True)
    THREAD_INDEX.write_text("\n".join(lines), encoding="utf-8", newline="\n")


def main() -> None:
    parser = argparse.ArgumentParser(description="Generate PRD shorthand files")
    parser.add_argument("--prd", type=int, help="Generate shorthand for PRD number (matches NN_ prefix, e.g. 38)")
    parser.add_argument(
        "--stem",
        metavar="STEM",
        help="Only `lupo-docs/prd/{STEM}.md` (e.g. 04_tags_metadata when two PRDs share NN)",
    )
    parser.add_argument("--all", action="store_true", help="Generate shorthands for all PRDs")
    parser.add_argument("--check", action="store_true", help="Check which shorthands are stale or missing")
    parser.add_argument("--force", action="store_true", help="Force regeneration even if not stale")
    parser.add_argument(
        "--skip-name",
        action="append",
        default=[],
        metavar="FILE.md",
        help="Additional PRD filename(s) to skip (repeatable). Also see LUPO_PRD_SHORTHAND_EXTRA_SKIP.",
    )
    args = parser.parse_args()

    skip_set = effective_skip_names(args.skip_name if args.skip_name else None)
    rows = get_all_prds(stem=args.stem, skip_names=skip_set)

    if args.check:
        print(
            "Checking shorthand staleness (PRD when_updated vs shorthand last_verified, else mtime)...\n"
        )
        stale_count = 0
        for row in rows:
            shorthand_path = get_shorthand_path(row.prd_prefix, row.slug)
            if not shorthand_path.exists():
                print("MISSING: %s" % row.file_path.name)
                stale_count += 1
            elif is_stale(row.file_path, shorthand_path, row.header):
                print("STALE: %s" % row.file_path.name)
                stale_count += 1
            else:
                print("OK: %s" % row.file_path.name)
        print("\nTotal PRDs: %s" % len(rows))
        print("Stale/Missing: %s" % stale_count)
        return

    if args.prd is not None:
        matched = False
        for row in rows:
            if row.prd_int != args.prd:
                continue
            matched = True
            if generate_shorthand(row, args.force):
                print("Generated: %s" % row.file_path.name)
            else:
                print("Up to date: %s" % row.file_path.name)
        if not matched:
            print("PRD %s not found (use --stem if disambiguating)" % args.prd)
            sys.exit(1)
        update_thread_index(get_all_prds(skip_names=skip_set))
        print("Updated THREAD_INDEX.md")
        return

    if args.all:
        generated = 0
        skipped = 0
        for row in rows:
            if generate_shorthand(row, args.force):
                print("Generated: %s" % row.file_path.name)
                generated += 1
            else:
                print("Skipped (up to date): %s" % row.file_path.name)
                skipped += 1
        print("\nGenerated: %s" % generated)
        print("Skipped: %s" % skipped)
        print("Total: %s" % len(rows))
        update_thread_index(get_all_prds(skip_names=skip_set))
        print("\nUpdated THREAD_INDEX.md")
        return

    parser.print_help()


if __name__ == "__main__":
    main()

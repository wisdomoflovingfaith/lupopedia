#!/usr/bin/env python3
"""
toon_bridge.py — Bi-directional TOON <-> JSON converter.

Usage:
  toon_bridge.py toon  <file.toon>            Convert single TOON -> JSON
  toon_bridge.py json  <file.json>            Convert single JSON -> TOON
  toon_bridge.py batch toon <dir>             Convert all .toon in dir tree -> .json
  toon_bridge.py batch json <dir>             Convert all .json in dir tree -> .toon

TOON format (simple/template variant):
  Scalar header fields (key: value), blank line, then edges: block in YAML.
  Example:
    id: 6
    type: prd
    title: content management
    status: active
    created: 20260410130550

    edges:
      outbound:
      - to: lupo-docs/prd/00_root_constitutional_system_requirements.md
        type: references

JSON format (full object):
  {
    "id": 6,
    "type": "prd",
    "title": "content management",
    "status": "active",
    "created": "20260410130550",
    "_toon_bridge": {"source_format": "toon", "converted_at": "..."},
    "edges": {
      "outbound": [
        {"to": "lupo-docs/prd/00_root.md", "type": "references"}
      ]
    }
  }

Detection:
  A file is "simple TOON" if its first non-empty line matches /^[a-z_]+:\s/.
  A file is "JSON TOON" if its first non-empty line is '{'.
  JSON-format TOON files (canonical copies already in JSON) are passed through
  with structural normalisation only — no data is dropped.
"""

import json
import os
import re
import sys
from datetime import datetime, timezone


# ---------------------------------------------------------------------------
# Helpers
# ---------------------------------------------------------------------------

def _now_utc():
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def _snake(s):
    """Convert a string to snake_case."""
    s = re.sub(r"[\s\-]+", "_", s.strip().lower())
    s = re.sub(r"[^\w]", "", s)
    return s


def _detect_format(text):
    """Return 'simple_toon', 'json_toon', or 'unknown'."""
    for line in text.splitlines():
        stripped = line.strip()
        if not stripped:
            continue
        if stripped.startswith("{"):
            return "json_toon"
        if re.match(r"^[a-z_]+:\s", stripped) or re.match(r"^[a-z_]+:$", stripped):
            return "simple_toon"
        break
    return "unknown"


# ---------------------------------------------------------------------------
# Simple TOON parser
# ---------------------------------------------------------------------------

def parse_simple_toon(text):
    """
    Parse a simple YAML-style TOON into a Python dict.

    Returns:
      {
        "id": int|str,
        "type": str,
        "title": str,
        "status": str,
        "created": str,
        "edges": {"outbound": [{"to": str, "type": str}, ...]}
      }
    Plus any extra scalar header fields found before the edges block.
    """
    lines = text.splitlines()
    data = {}
    i = 0

    # --- Parse scalar header fields (stop at blank line or "edges:") ---
    while i < len(lines):
        line = lines[i]
        # Blank line: skip
        if not line.strip():
            i += 1
            continue
        # edges block starts
        if re.match(r"^edges\s*:", line):
            break
        # key: value
        m = re.match(r"^([a-z_]+):\s*(.*)", line)
        if m:
            key, val = m.group(1), m.group(2).strip()
            # Strip surrounding quotes if present
            val = re.sub(r'^["\']|["\']$', "", val)
            data[key] = val
        i += 1

    # Coerce id to int if numeric
    if "id" in data:
        try:
            data["id"] = int(data["id"])
        except ValueError:
            pass

    # --- Parse edges block ---
    outbound = []
    in_edges = False
    in_outbound = False
    current_edge = {}

    while i < len(lines):
        line = lines[i]
        stripped = line.strip()

        if re.match(r"^edges\s*:", line):
            in_edges = True
            i += 1
            continue

        if in_edges:
            # "  outbound:" (indented)
            if re.match(r"^\s+outbound\s*:", line):
                in_outbound = True
                # Check if inline: "outbound: []"
                if re.search(r"outbound\s*:\s*\[\]", line):
                    in_outbound = False  # empty, nothing to parse
                i += 1
                continue

            if in_outbound:
                # New edge item: "  - to: ..."
                m = re.match(r"^\s+-\s+to:\s+(.*)", line)
                if m:
                    if current_edge:
                        outbound.append(current_edge)
                    current_edge = {"to": m.group(1).strip()}
                    i += 1
                    continue
                # Edge field continuation: "    type: ..."
                m = re.match(r"^\s+([a-z_]+):\s+(.*)", line)
                if m and current_edge:
                    current_edge[m.group(1).strip()] = m.group(2).strip()
                    i += 1
                    continue

        i += 1

    if current_edge:
        outbound.append(current_edge)

    data["edges"] = {"outbound": outbound}
    return data


# ---------------------------------------------------------------------------
# JSON TOON parser (canonical copies — already valid JSON)
# ---------------------------------------------------------------------------

def parse_json_toon(text):
    """
    Parse a JSON-format TOON (canonical copy already in JSON).

    Normalises edges into {"outbound": [...]} regardless of whether the
    original uses an "edges" array (list of objects with edge_direction)
    or an "edges": {"outbound": [...]} dict.

    Returns the full parsed dict, normalised.
    """
    obj = json.loads(text)

    # Normalise edges
    raw_edges = obj.get("edges")
    if raw_edges is None:
        obj["edges"] = {"outbound": []}
    elif isinstance(raw_edges, list):
        # Canonical format: flat list with edge_direction field
        outbound = [
            {k: v for k, v in e.items()}
            for e in raw_edges
            if e.get("edge_direction", "outbound") == "outbound"
        ]
        inbound = [
            {k: v for k, v in e.items()}
            for e in raw_edges
            if e.get("edge_direction") == "inbound"
        ]
        obj["edges"] = {"outbound": outbound}
        if inbound:
            obj["edges"]["inbound"] = inbound
    elif isinstance(raw_edges, dict):
        # Already in normalised form — leave as-is
        pass

    return obj


# ---------------------------------------------------------------------------
# TOON → JSON
# ---------------------------------------------------------------------------

def toon_to_json(toon_path, json_path=None):
    """Convert a .toon file to .json. Returns the output path."""
    if json_path is None:
        json_path = re.sub(r"\.toon$", ".json", toon_path)

    with open(toon_path, "r", encoding="utf-8") as f:
        text = f.read()

    fmt = _detect_format(text)

    if fmt == "simple_toon":
        data = parse_simple_toon(text)
        data["_toon_bridge"] = {
            "source_format": "simple_toon",
            "source_file": os.path.basename(toon_path),
            "converted_at": _now_utc(),
        }
    elif fmt == "json_toon":
        data = parse_json_toon(text)
        # Add bridge metadata without overwriting existing bridge key
        if "_toon_bridge" not in data:
            data["_toon_bridge"] = {}
        data["_toon_bridge"].update({
            "source_format": "json_toon",
            "source_file": os.path.basename(toon_path),
            "converted_at": _now_utc(),
        })
    else:
        raise ValueError(f"Cannot detect format of {toon_path!r} (first non-blank line unrecognised)")

    with open(json_path, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2, ensure_ascii=False)
        f.write("\n")

    return json_path


# ---------------------------------------------------------------------------
# JSON → TOON
# ---------------------------------------------------------------------------

def json_to_toon(json_path, toon_path=None):
    """
    Convert a .json file to a simple TOON.

    Uses snake_case for all keys.
    Preserves all fields from the JSON; non-standard fields are appended
    after the standard header block as extra: key: value lines.
    Returns the output path.
    """
    if toon_path is None:
        toon_path = re.sub(r"\.json$", ".toon", json_path)

    with open(json_path, "r", encoding="utf-8") as f:
        data = json.load(f)

    # Standard header fields emitted in this order
    STANDARD_FIELDS = ["id", "type", "title", "status", "created"]
    SKIP_FIELDS = {"edges", "_toon_bridge"}

    lines = []

    # --- Standard header ---
    for field in STANDARD_FIELDS:
        val = data.get(field)
        if val is not None:
            lines.append(f"{_snake(field)}: {val}")

    # --- Extra fields (preserve, sorted for determinism) ---
    extras = {
        k: v for k, v in data.items()
        if k not in STANDARD_FIELDS and k not in SKIP_FIELDS
    }
    if extras:
        lines.append("")
        lines.append("extra:")
        for k, v in sorted(extras.items()):
            if isinstance(v, (dict, list)):
                # Serialise complex values as compact JSON on one line
                lines.append(f"  {_snake(k)}: {json.dumps(v, ensure_ascii=False)}")
            else:
                lines.append(f"  {_snake(k)}: {v}")

    lines.append("")

    # --- Edges block ---
    edges = data.get("edges", {})
    if isinstance(edges, dict):
        outbound = edges.get("outbound", [])
        inbound = edges.get("inbound", [])
    elif isinstance(edges, list):
        # Flat list from canonical JSON format
        outbound = [e for e in edges if e.get("edge_direction", "outbound") == "outbound"]
        inbound = [e for e in edges if e.get("edge_direction") == "inbound"]
    else:
        outbound = []
        inbound = []

    lines.append("edges:")

    if outbound:
        lines.append("  outbound:")
        for edge in outbound:
            edge_fields = {_snake(k): v for k, v in edge.items()}
            to = edge_fields.pop("to", "")
            edge_type = edge_fields.pop("type", edge_fields.pop("edge_type", "references"))
            lines.append(f"  - to: {to}")
            lines.append(f"    type: {edge_type}")
            # Remaining edge fields (pk, weight_hundredths, etc.)
            for ek, ev in sorted(edge_fields.items()):
                if ev is not None and ek not in ("edge_direction",):
                    lines.append(f"    {ek}: {ev}")
    else:
        lines.append("  outbound: []")

    if inbound:
        lines.append("  inbound:")
        for edge in inbound:
            edge_fields = {_snake(k): v for k, v in edge.items()}
            to = edge_fields.pop("to", "")
            edge_type = edge_fields.pop("type", edge_fields.pop("edge_type", "references"))
            lines.append(f"  - to: {to}")
            lines.append(f"    type: {edge_type}")
            for ek, ev in sorted(edge_fields.items()):
                if ev is not None and ek not in ("edge_direction",):
                    lines.append(f"    {ek}: {ev}")

    # Bridge provenance comment at end
    bridge = data.get("_toon_bridge", {})
    if bridge:
        lines.append("")
        lines.append(f"# toon_bridge: converted from {bridge.get('source_format', 'json')} at {_now_utc()}")

    with open(toon_path, "w", encoding="utf-8") as f:
        f.write("\n".join(lines) + "\n")

    return toon_path


# ---------------------------------------------------------------------------
# Batch operations
# ---------------------------------------------------------------------------

def batch_convert(direction, root_dir):
    """
    direction: 'toon' (toon->json) or 'json' (json->toon)
    Recurses through root_dir, converts all matching files.
    Skips files that already have an up-to-date counterpart
    (i.e. output exists and is newer than source).
    """
    if direction not in ("toon", "json"):
        raise ValueError(f"direction must be 'toon' or 'json', got {direction!r}")

    src_ext = ".toon" if direction == "toon" else ".json"
    convert_fn = toon_to_json if direction == "toon" else json_to_toon

    converted = []
    skipped = []
    errors = []

    for dirpath, _dirs, filenames in os.walk(root_dir):
        for fname in sorted(filenames):
            if not fname.endswith(src_ext):
                continue
            src = os.path.join(dirpath, fname)
            dst_ext = ".json" if direction == "toon" else ".toon"
            dst = re.sub(re.escape(src_ext) + "$", dst_ext, src)

            # Skip if output is newer than source
            if os.path.exists(dst) and os.path.getmtime(dst) >= os.path.getmtime(src):
                skipped.append(src)
                continue

            try:
                out = convert_fn(src, dst)
                converted.append((src, out))
            except Exception as e:
                errors.append((src, str(e)))

    return converted, skipped, errors


# ---------------------------------------------------------------------------
# CLI
# ---------------------------------------------------------------------------

def _usage():
    print(__doc__)
    sys.exit(1)


def main():
    args = sys.argv[1:]

    if len(args) < 2:
        _usage()

    cmd = args[0].lower()

    if cmd == "toon":
        src = args[1]
        dst = args[2] if len(args) > 2 else None
        out = toon_to_json(src, dst)
        print(f"OK  {src} -> {out}")

    elif cmd == "json":
        src = args[1]
        dst = args[2] if len(args) > 2 else None
        out = json_to_toon(src, dst)
        print(f"OK  {src} -> {out}")

    elif cmd == "batch":
        if len(args) < 3:
            _usage()
        direction = args[1].lower()
        root_dir = args[2]
        if not os.path.isdir(root_dir):
            print(f"ERROR: {root_dir!r} is not a directory", file=sys.stderr)
            sys.exit(1)
        converted, skipped, errors = batch_convert(direction, root_dir)
        for src, dst in converted:
            print(f"OK   {src} -> {dst}")
        for src in skipped:
            print(f"SKIP {src} (output up-to-date)")
        for src, err in errors:
            print(f"ERR  {src}: {err}", file=sys.stderr)
        print(
            f"\nDone. converted={len(converted)}, skipped={len(skipped)}, errors={len(errors)}"
        )
        if errors:
            sys.exit(1)

    else:
        print(f"ERROR: unknown command {cmd!r}", file=sys.stderr)
        _usage()


if __name__ == "__main__":
    main()

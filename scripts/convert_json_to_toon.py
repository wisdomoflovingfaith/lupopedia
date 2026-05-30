#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/convert_json_to_toon.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Convert JSON-format files from database/lupopedia/json/ (files with .json, .toon,
or .toon.json extension) to TOON format (YAML) in database/lupopedia/toon/
as <table_name>.toon (overwriting existing).

Usage: run from project root or scripts/: python scripts/convert_json_to_toon.py
"""

import json
import sys
from pathlib import Path

try:
    import yaml
except ImportError:
    yaml = None


def main():
    base = Path(__file__).resolve().parent
    project_root = base.parent
    json_dir = project_root / "database" / "lupopedia" / "json"
    toon_dir = project_root / "database" / "lupopedia" / "toon"

    if not json_dir.is_dir():
        print("json directory not found: {}".format(json_dir), file=sys.stderr)
        return 1

    if yaml is None:
        print("yaml module required for TOON (YAML) output. Install with: pip install pyyaml", file=sys.stderr)
        return 1

    toon_dir.mkdir(parents=True, exist_ok=True)

    count = 0
    for path in sorted(json_dir.iterdir()):
        if not path.is_file():
            continue
        name = path.name
        if name.endswith(".toon.json"):
            table_name = name[:-len(".toon.json")]
        elif name.endswith(".toon"):
            table_name = name[:-len(".toon")]
        elif name.endswith(".json"):
            table_name = name[:-len(".json")]
        else:
            continue
        try:
            with path.open("r", encoding="utf-8") as f:
                data = json.load(f)
        except Exception as e:
            print("skip {}: {}".format(name, e), file=sys.stderr)
            continue
        out_path = toon_dir / "{}.toon".format(table_name)
        with out_path.open("w", encoding="utf-8") as f:
            yaml.dump(
                data,
                f,
                default_flow_style=False,
                allow_unicode=True,
                sort_keys=False,
            )
        count += 1

    print("Converted {} files from {} to {} (TOON/YAML format)".format(count, json_dir, toon_dir))
    return 0


if __name__ == "__main__":
    sys.exit(main())
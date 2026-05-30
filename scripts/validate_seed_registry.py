#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Validate seed registry files for trust ladder seed space.

Rules:
  - Seed IDs used for ladder participation must be in 0-999,999 inclusive.
  - System seed IDs are expected in actors registry.
"""

import json
import sys
from pathlib import Path

PROJECT_ROOT = Path(__file__).resolve().parent.parent
ACTORS_REGISTRY = PROJECT_ROOT / "database" / "lupopedia" / "actors" / "registry.json"
SYSTEM_MIN = 0
SYSTEM_MAX = 999999


def load_json(path):
    with path.open("r", encoding="utf-8") as fh:
        return json.load(fh)


def is_int_string(value):
    return isinstance(value, str) and value.isdigit()


def to_int(value):
    if isinstance(value, int):
        return value
    if is_int_string(value):
        return int(value)
    return None


def validate_actor_seeds():
    if not ACTORS_REGISTRY.is_file():
        return ["Missing actors registry: %s" % ACTORS_REGISTRY]

    errors = []
    data = load_json(ACTORS_REGISTRY)
    actors = data.get("actors", {})
    seen = set()

    for actor_name, actor in actors.items():
        actor_id = to_int(actor.get("actor_id"))
        if actor_id is None:
            errors.append("Actor %s has non-numeric actor_id" % actor_name)
            continue
        if actor_id < 0:
            errors.append("Actor %s has negative actor_id %s" % (actor_name, actor_id))
        if actor_id < SYSTEM_MIN or actor_id > SYSTEM_MAX:
            errors.append("Actor %s actor_id %s out of allowed seed range %s-%s" % (
                actor_name, actor_id, SYSTEM_MIN, SYSTEM_MAX
            ))
        if actor_id in seen:
            errors.append("Duplicate actor_id %s in actors registry" % actor_id)
        seen.add(actor_id)

    return errors


def main():
    errors = []
    errors.extend(validate_actor_seeds())

    if errors:
        print("ERROR: Seed registry validation failed:")
        for err in errors:
            print("  - %s" % err)
        return 1

    print("OK: Seed registries valid for trust ladder seed range 0-999,999")
    return 0


if __name__ == "__main__":
    sys.exit(main())

#!/usr/bin/env python3
import json
import re
import sys
from pathlib import Path


CHANNEL_DIR_RE = re.compile(r"^\d{4}$")


def die(message):
    print(f"ERROR: {message}", file=sys.stderr)
    sys.exit(1)


def load_json(path):
    try:
        return json.loads(path.read_text(encoding="utf-8"))
    except FileNotFoundError:
        die(f"Missing file: {path}")
    except json.JSONDecodeError as exc:
        die(f"Invalid JSON in {path}: {exc}")


def build_toon_lookup(toon_path):
    if not toon_path.exists():
        return {}
    toon = load_json(toon_path)
    data = toon.get("data", [])
    lookup = {}
    for row in data:
        key = row.get("channel_key")
        if key:
            lookup[key] = row
    return lookup


def unique_append(items, value):
    if value and value not in items:
        items.append(value)


def main():
    root = Path(__file__).resolve().parents[1]
    channels_dir = root / "channels"
    toon_path = root / "database" / "toon_data" / "lupo_channels.toon"

    if not channels_dir.exists():
        die(f"Missing channels directory: {channels_dir}")

    toon_lookup = build_toon_lookup(toon_path)
    entries = []

    for channel_dir in sorted(p for p in channels_dir.iterdir() if p.is_dir()):
        if not CHANNEL_DIR_RE.match(channel_dir.name):
            # Only process channel directories. If a manifest exists in a non-numeric folder, fail fast.
            manifest_path = channel_dir / "manifest.json"
            if manifest_path.exists():
                die(
                    f"Channel directory is not zero-padded numeric: {channel_dir.name} "
                    f"(found manifest at {manifest_path})"
                )
            continue

        manifest_path = channel_dir / "manifest.json"
        if not manifest_path.exists():
            die(f"Missing manifest.json in {channel_dir}")

        manifest = load_json(manifest_path)

        channel_number = manifest.get("channel_number")
        channel_key = manifest.get("channel_key")
        slug = manifest.get("slug")
        primary = manifest.get("channel_name")
        channel_type = manifest.get("channel_type")
        created_ymdhis = manifest.get("created_ymdhis")
        updated_ymdhis = manifest.get("updated_ymdhis")

        missing = [
            name
            for name, value in [
                ("channel_number", channel_number),
                ("channel_key", channel_key),
                ("slug", slug),
                ("channel_name", primary),
                ("channel_type", channel_type),
                ("created_ymdhis", created_ymdhis),
                ("updated_ymdhis", updated_ymdhis),
            ]
            if value in (None, "")
        ]
        if missing:
            die(
                f"Manifest {manifest_path} missing required fields: "
                f"{', '.join(missing)}"
            )

        try:
            channel_number_int = int(channel_number)
        except (TypeError, ValueError):
            die(f"Invalid channel_number in {manifest_path}: {channel_number}")

        if channel_number_int != int(channel_dir.name):
            die(
                f"channel_number mismatch for {channel_dir.name}: "
                f"manifest has {channel_number_int}"
            )

        aliases = []
        unique_append(aliases, channel_key)
        unique_append(aliases, channel_dir.name)

        toon_row = toon_lookup.get(channel_key)
        if toon_row:
            unique_append(aliases, toon_row.get("channel_name"))
            unique_append(aliases, toon_row.get("channel_key"))

        # Remove primary/slug from aliases if they snuck in.
        aliases = [a for a in aliases if a not in (primary, slug)]

        entry = {
            "channel_number": channel_number_int,
            "names": {
                "primary": primary,
                "slug": slug,
                "aliases": aliases,
            },
            "pono_score": 1.0,
            "pilau_score": 0.0,
            "kapakai_score": 0.5,
            "metadata": {
                "channel_type": channel_type,
                "created_ymdhis": str(created_ymdhis),
                "updated_ymdhis": str(updated_ymdhis),
            },
        }
        entries.append(entry)

    entries.sort(key=lambda item: item["channel_number"])

    registry_path = channels_dir / "registry.json"
    registry_path.write_text(
        json.dumps(entries, indent=2, ensure_ascii=False) + "\n",
        encoding="utf-8",
    )


if __name__ == "__main__":
    main()

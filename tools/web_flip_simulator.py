#!/usr/bin/env python3
"""
web_flip_simulator.py — Simulate external agent (e.g. Grok) browsing the FLIP header API.

Hits api/flip-header.php locally or at a given base URL and parses JSON/YAML response.
Use for testing that the API returns valid FLIP headers.

Requirements: Python 3.6+. requests library (pip install requests).
"""
import argparse
import json
import sys

try:
    import requests
except ImportError:
    print("Error: requests required. pip install requests", file=sys.stderr)
    sys.exit(1)

def main():
    ap = argparse.ArgumentParser(description="Simulate Grok browsing the FLIP header API")
    ap.add_argument("--base", default="http://localhost/lupopedia", help="Base URL (e.g. http://localhost/lupopedia)")
    ap.add_argument("--path", default="docs/doctrine/FLIP/FLIP_DOCTRINE.md", help="file_path_from_root to query")
    ap.add_argument("--format", choices=["json", "yaml"], default="json", help="Request format=yaml or default JSON")
    args = ap.parse_args()

    url = args.base.rstrip("/") + "/api/flip-header.php"
    params = {"path": args.path}
    if args.format == "yaml":
        params["format"] = "yaml"

    try:
        r = requests.get(url, params=params, timeout=10)
        r.raise_for_status()
        if args.format == "yaml":
            print("Response (raw YAML):")
            print(r.text)
        else:
            data = r.json()
            if "error" in data:
                print("Error:", data["error"], file=sys.stderr)
                sys.exit(1)
            print("header:", data.get("header", "")[:200] + "..." if len(data.get("header", "")) > 200 else data.get("header", ""))
            print("resolved:", data.get("resolved"))
            print("channel_id:", data.get("channel_id"))
            print("\nFull JSON:")
            print(json.dumps(data, indent=2, ensure_ascii=False))
    except requests.RequestException as e:
        print("Request failed:", e, file=sys.stderr)
        sys.exit(1)
    except json.JSONDecodeError as e:
        print("JSON parse failed:", e, file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    main()

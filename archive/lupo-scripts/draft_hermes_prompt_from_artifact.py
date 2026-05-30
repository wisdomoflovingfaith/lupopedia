#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/draft_hermes_prompt_from_artifact.py"
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

# -*- coding: utf-8 -*-
"""
Draft a HERMES-format prompt file from a channel thread artifact (.md).
Semi-automates: artifact -> prompts/YYYYMMDD_HHIISS_hermes_prompt_{target}_{purpose}.md

HERMES (actor_id 15) should review/edit before treating as canonical handoff.
Usage:
  python lupo-scripts/draft_hermes_prompt_from_artifact.py \\
    --artifact lupo-channels/42/threads/1001/some.md --target wolfie --purpose stabilization

If --write is omitted, prints path and body to stdout only.
"""
from __future__ import print_function

import argparse
import os
import re
import sys
from datetime import datetime

try:
    from datetime import timezone
    def _utc_now():
        return datetime.now(timezone.utc)
except ImportError:
    def _utc_now():
        return datetime.utcnow()

try:
    unicode
except NameError:
    unicode = str


def _read_text(path):
    with open(path, 'r', encoding='utf-8', errors='replace') as f:
        return f.read()


def _yaml_value(block, key):
    # key e.g. purpose: or actor_name:
    m = re.search(r'^\s*' + re.escape(key) + r'\s*:\s*["\']?([^"\'\n]+)["\']?\s*$', block, re.MULTILINE | re.IGNORECASE)
    if m:
        return m.group(1).strip()
    m = re.search(r'^\s*' + re.escape(key) + r'\s*:\s*(.+)$', block, re.MULTILINE)
    return m.group(1).strip() if m else ''


def _first_heading(body_after_yaml):
    for line in body_after_yaml.split('\n'):
        if line.startswith('# '):
            return line[2:].strip()
    return ''


def main():
    ap = argparse.ArgumentParser(description='Draft HERMES prompt from channel artifact')
    ap.add_argument('--artifact', required=True, help='Path to thread .md artifact')
    ap.add_argument('--target', required=True, help='Target actor slug e.g. wolfie, hephaestus')
    ap.add_argument('--purpose', default='', help='Short purpose for filename; default from YAML purpose or heading')
    ap.add_argument('--channel-id', type=int, default=42)
    ap.add_argument('--prompts-dir', default='', help='Default: lupo-channels/{id}/prompts')
    ap.add_argument('--write', action='store_true', help='Write file; else stdout only')
    ap.add_argument('--version', default='4.0.80')
    ap.add_argument('--force', action='store_true', help='Skip substantive-body gate (emergency only)')
    args = ap.parse_args()

    path = os.path.normpath(args.artifact)
    if not os.path.isfile(path):
        print('Not found: ' + path, file=sys.stderr)
        sys.exit(1)

    raw = _read_text(path)
    if raw.startswith('---'):
        end = raw.find('\n---', 3)
        yaml_block = raw[3:end] if end > 0 else ''
        body = raw[end + 4:].lstrip('\n') if end > 0 else raw
    else:
        yaml_block = ''
        body = raw

    if not args.force:
        low_fm = yaml_block.lower()
        b = body.strip()
        n_h2 = len(re.findall(r'^##\s+', b, re.MULTILINE))
        if 'artifact_kind: review' in low_fm or 'message_type: review' in low_fm:
            if len(b) < 500 or n_h2 < 3:
                print('HERMES_INGESTION_REFUSED: review artifact fails body contract (500+ chars, 3+ ##)', file=sys.stderr)
                sys.exit(2)
        if 'artifact_kind: help_response' in low_fm or 'message_type: help_response' in low_fm:
            if len(b) < 200 or b.count('#') < 3 or not re.search(r'^#\s+\S', b, re.MULTILINE) or n_h2 < 3:
                print('HERMES_INGESTION_REFUSED: help_response artifact fails body contract', file=sys.stderr)
                sys.exit(2)

    purpose_yaml = _yaml_value(yaml_block, 'purpose')
    heading = _first_heading(body)
    purpose_slug = args.purpose.strip().lower().replace(' ', '_')
    if not purpose_slug:
        base = purpose_yaml or heading or 'handoff'
        purpose_slug = re.sub(r'[^a-z0-9_-]+', '_', base.lower())[:60].strip('_') or 'handoff'

    now = _utc_now()
    ts = now.strftime('%Y%m%d_%H%M%S')
    fname = '%s_hermes_prompt_%s_%s.md' % (ts, args.target.lower(), purpose_slug)
    if args.prompts_dir:
        prompts_dir = os.path.normpath(args.prompts_dir)
    else:
        prompts_dir = ''
        d = os.path.dirname(os.path.abspath(path))
        while d:
            if os.path.basename(d) == 'threads':
                prompts_dir = os.path.join(os.path.dirname(d), 'prompts')
                break
            d = os.path.dirname(d)
        if not prompts_dir:
            repo = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
            prompts_dir = os.path.join(repo, 'lupo-channels', str(args.channel_id), 'prompts')
        prompts_dir = os.path.normpath(prompts_dir)

    out_path = os.path.join(prompts_dir, fname)

    excerpt = body[:2500] if len(body) > 2500 else body
    if len(body) > 2500:
        excerpt += '\n\n[... truncated ...]\n'

    content = '''---
lupopedia.headers:
  lupopedia.version: "%s"
  channel_id: %s
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "Handoff to %s from source artifact"
  target_actor_slug: "%s"
  source_artifact: "%s"
---

# file: HERMES prompt -> %s

## Source

- Artifact: `%s`
- Routed by: draft_hermes_prompt_from_artifact.py (review before execution)

## Task for %s

Execute the work implied by the source artifact. Post result as channel artifact in the appropriate thread; do not claim HERMES authored your output.

## Source excerpt

%s
''' % (
        args.version,
        args.channel_id,
        args.target,
        args.target,
        path.replace('\\', '/'),
        args.target,
        path.replace('\\', '/'),
        args.target,
        excerpt,
    )

    if args.write:
        if not os.path.isdir(prompts_dir):
            os.makedirs(prompts_dir)
        with open(out_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(out_path)
    else:
        print('Would write: ' + out_path)
        print(content)


if __name__ == '__main__':
    main()
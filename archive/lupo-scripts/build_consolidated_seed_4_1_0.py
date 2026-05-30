# -*- coding: utf-8 -*-
"""Concat mysql/seed files into install/seed_lupopedia_4_1_0.sql (full stack).

install.php prefers this file when it exists; otherwise it uses mysql/seed/seed_4.1.0.sql only.
"""
from __future__ import print_function
import os

REPO = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = os.path.join(REPO, 'lupo-database', 'lupopedia', 'mysql', 'seed')
OUT = os.path.join(REPO, 'install', 'seed_lupopedia_4_1_0.sql')

# Safe dependency order: registry before actors before seed_4.1.0 (see seed file headers).
# Note: Only include files that actually exist to avoid build failures.
FILES = [
    'seed_4.1.0.sql',
    'seed_online_help_and_content.sql',
]


def main():
    out = []
    out.append('-- ============================================================================')
    out.append('-- CONSOLIDATED SEED: Lupopedia (seed_lupopedia_4_1_0.sql)')
    out.append('-- Table prefix: {{prefix}} (replaced at install time; same as install_new_lupopedia.sql).')
    out.append('-- Section order: dependency-safe (registry, then actors, then seed_4.1.0, then remainder).')
    out.append('-- Original per-file seeds preserved under lupo-database/lupopedia/mysql/seed/.')
    out.append('-- ============================================================================')
    for fn in FILES:
        path = os.path.join(BASE, fn)
        if not os.path.isfile(path):
            raise SystemExit('missing: ' + path)
        out.append('')
        out.append('-- >>> BEGIN FILE: ' + fn)
        out.append('')
        with open(path, 'r') as f:
            body = f.read()
        out.append(body.rstrip('\n\r'))
        out.append('')
        out.append('-- <<< END FILE: ' + fn)
    text = '\n'.join(out) + '\n'
    text = text.replace('lupo_', '{{prefix}}')
    d = os.path.dirname(OUT)
    if not os.path.isdir(d):
        os.makedirs(d)
    with open(OUT, 'w') as f:
        f.write(text)
    print('Wrote', OUT, 'size', len(text))


if __name__ == '__main__':
    main()

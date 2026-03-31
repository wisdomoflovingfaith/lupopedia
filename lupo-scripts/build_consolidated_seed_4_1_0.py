# -*- coding: utf-8 -*-
"""One-off builder: concat mysql/seed files into install/seed_lupopedia_4_1_0.sql"""
from __future__ import print_function
import os

REPO = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BASE = os.path.join(REPO, 'lupo-database', 'lupopedia', 'mysql', 'seed')
OUT = os.path.join(REPO, 'install', 'seed_lupopedia_4_1_0.sql')

# Safe dependency order: registry before actors before seed_4.1.0 (see seed file headers).
FILES = [
    'seed_registry_comprehensive_4.0.45.sql',
    'seed_registry_additional_csv_entities_4.0.45.sql',
    'seed_registry_open_4.0.45.sql',
    'seed_actors_agents_4.0.45.sql',
    'seed_actor_1_cursor_rules_4.0.68.sql',
    'seed_actor_zencoder_4.0.77.sql',
    'seed_primary_coordination_personas_4.0.89.sql',
    'seed_4.1.0.sql',
    'seed_departments.sql',
    'seed_default_sessions.sql',
    'seed_flare_content_4.0.57.sql',
    'seed_flare_apply_content_4.0.57.sql',
    'seed_docs_web_content_4.0.57.sql',
    'seed_lilith_channel_42_critic_role_4.0.79.sql',
    'seed_channel_42_dialog_threads_4.0.80.sql',
    'seed_comments_4.0.73.sql',
    'seed_rules_doctrine_4.0.68.sql',
    'seed_skills_4.0.68.sql',
    'seed_lupo_metadata_changelog_headers_4.0.68.sql',
    'seed_fallback_rule_4.0.69.sql',
    'seed_traits_edge_types_action_auth_4.0.69.sql',
    'seed_projects.sql',
    'seed_qa_lupopedia_4.0.88.sql',
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

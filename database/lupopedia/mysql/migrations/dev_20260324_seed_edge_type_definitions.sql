-- Migration: seed lupo_edge_type_definitions
-- File: database/lupopedia/mysql/migrations/dev_20260324_seed_edge_type_definitions.sql

INSERT INTO lupo_edge_type_definitions
  (edge_type_definition_id, edge_type, domain, description, allowed_left_object_types, allowed_right_object_types, is_bidirectional, semantic_meaning, created_ymdhis, created_by_actor_id)
VALUES
  (1, 'channel_related',       'channel',  'Related channels', 'channel',                   'channel',          1, 'Captures semantic or operational relationship between channels', 20260324120000, 108),
  (2, 'channel_parent',        'channel',  'Parent hierarchy',  'channel',                   'channel',          0, 'Formal parent; supplements parent_channel_id structural column', 20260324120000, 108),
  (3, 'channel_successor',     'channel',  'Channel successor', 'channel',                   'channel',          0, 'Target channel succeeded or continued this channel', 20260324120000, 108),
  (4, 'channel_spawned_thread','channel',  'Thread ownership',  'channel',                   'thread',           0, 'Channel originated this thread', 20260324120000, 108),
  (5, 'channel_references',    'channel',  'Channel citation',  'channel',                   'channel',          0, 'One channel cites another', 20260324120000, 108),
  (6, 'thread_continuation',   'thread',   'Thread lineage',    'thread',                    'thread',           0, 'This thread continues from target thread; replaces thread_lineage TEXT', 20260324120000, 108),
  (7, 'thread_spawned_from',   'thread',   'Thread fork',       'thread',                    'thread',           0, 'This thread was forked or branched from target thread', 20260324120000, 108),
  (8, 'thread_references',     'thread',   'Thread citation',   'thread',                    'thread,channel',   0, 'Thread cites or references another thread or channel', 20260324120000, 108),
  (9, 'thread_crosses_channel','thread',   'Cross-channel',     'thread',                    'channel',          0, 'Thread involves or spans into another channel', 20260324120000, 108),
  (10,'channel_sibling',       'channel',  'Channel siblings',  'channel',                   'channel',          1, 'Channels sharing purpose or origin at same level', 20260324120000, 108),
  (11,'artifact_spawned_from', 'artifact', 'Artifact lineage',  'artifact',                  'thread,channel',   0, 'Artifact was produced from a thread or channel conversation', 20260324120000, 108),
  (12,'channel_observes',      'channel',  'Observation edge',  'channel,actor',             'channel',          0, 'Actor or channel monitors/observes the target channel', 20260324120000, 108);
-- Migration: seed lupo_edge_types for channel/thread domain
-- File: database/lupopedia/mysql/migrations/dev_20260324_seed_edge_types_channel_thread.sql

INSERT INTO lupo_edge_types (edge_type_id, slug, label, description, is_bidirectional, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
  (1,  'channel_related',          'Channel Related',             'Channels that are semantically or operationally related. Bidirectional.', 1, 20260324120000, 20260324120000, 0),
  (2,  'channel_parent',           'Channel Parent',              'Formal hierarchical parent. Supplements parent_channel_id. Directional.', 0, 20260324120000, 20260324120000, 0),
  (3,  'channel_successor',        'Channel Successor',           'This channel continued as or was superseded by the target channel.', 0, 20260324120000, 20260324120000, 0),
  (4,  'channel_spawned_thread',   'Channel Spawned Thread',      'This channel originated or owns this thread.', 0, 20260324120000, 20260324120000, 0),
  (5,  'channel_references',       'Channel References',          'Channel cites or references another channel.', 0, 20260324120000, 20260324120000, 0),
  (6,  'thread_continuation',      'Thread Continuation',         'This thread continues conversation from the target thread.', 0, 20260324120000, 20260324120000, 0),
  (7,  'thread_spawned_from',      'Thread Spawned From',         'This thread was forked or branched from the target thread.', 0, 20260324120000, 20260324120000, 0),
  (8,  'thread_references',        'Thread References',           'This thread cites or references the target thread or channel.', 0, 20260324120000, 20260324120000, 0),
  (9,  'thread_crosses_channel',   'Thread Crosses Channel',      'Thread activity spans into or involves another channel.', 0, 20260324120000, 20260324120000, 0),
  (10, 'channel_sibling',          'Channel Sibling',             'Channels at the same level sharing a purpose or origin. Bidirectional.', 1, 20260324120000, 20260324120000, 0),
  (11, 'artifact_spawned_from',    'Artifact Spawned From',       'Artifact was produced from this thread or channel.', 0, 20260324120000, 20260324120000, 0),
  (12, 'channel_observes',         'Channel Observes',            'Channel has a monitoring or observation relationship to the target.', 0, 20260324120000, 20260324120000, 0);
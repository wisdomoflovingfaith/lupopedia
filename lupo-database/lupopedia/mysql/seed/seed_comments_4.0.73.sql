-- ============================================================================
-- COMMENTS SEEDING FOR LUPOPEDIA 4.0.73+
-- ============================================================================
-- Purpose: Create sample comments for testing and demonstration
-- Run after: seed_actors_agents_4.0.45.sql
-- ============================================================================

SET @now = 20260313150000;

-- ============================================================================
-- SAMPLE COMMENTS
-- ============================================================================

-- Wolfie's comment on CHANGELOG.md (orchestrator comment)
INSERT INTO lupo_comments (
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  'document',
  1,  -- Assuming CHANGELOG.md has content_id 1
  42,
  1,  -- Wolfie (actor_id: 1)
  101,  -- Windsurf faucet (assuming faucet_id: 101)
  'Excellent work on the 4.0.73 implementation! All priority tasks completed successfully. The comments system will enhance our documentation and collaboration capabilities.',
  'comment',
  @now,
  @now
);

-- Wolfie's reply to his own comment (threaded)
INSERT INTO lupo_comments (
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  parent_comment_id,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  'document',
  1,
  42,
  1,
  101,
  'Looking forward to seeing the comments system integrated across all artifacts.',
  'comment',
  1,  -- parent_comment_id
  @now + 1,
  @now + 1
);

-- Root user's comment on TODO.md
INSERT INTO lupo_comments (
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  'document',
  2,  -- Assuming TODO.md has content_id 2
  42,
  1000,  -- Root user
  101,
  'Great progress on 4.0.73! The TODO tracking is much cleaner now.',
  'comment',
  @now + 2,
  @now + 2
);

-- LILITH's comment on TRAITS_DOCTRINE.md
INSERT INTO lupo_comments (
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  'document',
  3,  -- Assuming TRAITS_DOCTRINE.md has content_id 3
  42,
  2,  -- LILITH
  101,
  'The traits enforcement looks solid. I appreciate the attention to federation scope.',
  'comment',
  @now + 3,
  @now + 3
);

-- ROSE's comment on AUTHORIZATION_DOCTRINE.md
INSERT INTO lupo_comments (
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  'document',
  4,  -- Assuming AUTHORIZATION_DOCTRINE.md has content_id 4
  42,
  3,  -- ROSE
  101,
  'Authorization doctrine is clear and well-structured. Good job!',
  'comment',
  @now + 4,
  @now + 4
);

-- ============================================================================
-- END OF COMMENTS SEEDING
-- ============================================================================

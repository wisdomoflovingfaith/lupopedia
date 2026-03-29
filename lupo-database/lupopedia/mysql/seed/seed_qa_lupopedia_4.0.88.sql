-- ============================================================================
-- Lupopedia Q/A seed (4.0.88)
-- Ensures /qa/lupopedia is available after install/upgrade.
-- ============================================================================

SET @qa_now = 20260325223000;

-- Question row: slug = lupopedia
INSERT INTO lupo_questions (
  slug,
  question_text,
  actor_id,
  created_ymdhis,
  updated_ymdhis,
  is_deleted
)
SELECT
  'lupopedia',
  'What is Lupopedia?',
  1,
  @qa_now,
  @qa_now,
  0
WHERE NOT EXISTS (
  SELECT 1 FROM lupo_questions WHERE slug = 'lupopedia'
);

-- Answer row sourced from README summary
INSERT INTO lupo_answers (
  question_id,
  answer_text,
  actor_id,
  created_ymdhis,
  updated_ymdhis,
  is_deleted
)
SELECT
  q.question_id,
  'Lupopedia is a doctrine-driven semantic operating system built on Crafty Syntax 3.7.5 foundations, with explicit actor orchestration, channel and thread workflows, and verifiable artifact metadata.',
  1,
  @qa_now,
  @qa_now,
  0
FROM lupo_questions q
WHERE q.slug = 'lupopedia'
  AND NOT EXISTS (
    SELECT 1
    FROM lupo_answers a
    WHERE a.question_id = q.question_id
      AND a.answer_text = 'Lupopedia is a doctrine-driven semantic operating system built on Crafty Syntax 3.7.5 foundations, with explicit actor orchestration, channel and thread workflows, and verifiable artifact metadata.'
  );

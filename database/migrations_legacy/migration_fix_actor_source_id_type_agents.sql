-- One-time: fix lupo_actors rows where actor_source_id and actor_source_type were swapped (seed bug).
-- Correct: actor_source_id = numeric id (e.g. 1, 2, 1212), actor_source_type = 'lupo_agent_registry'.
-- Run once per environment. Idempotent: only updates rows that still have JSON in actor_source_type.

UPDATE lupo_actors
SET actor_source_id = actor_id,
    actor_source_type = 'lupo_agent_registry'
WHERE actor_type = 'agent'
  AND actor_source_type IS NOT NULL
  AND actor_source_type LIKE '{%'
  AND actor_source_type LIKE '%actor_source_id%';

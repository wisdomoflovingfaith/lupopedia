-- Runtime actor loop and escalation DB layer (development migration)
-- Scope: existing 4.0.x installs that do not yet include lupo_escalation_tasks.
-- Canonical source remains install/install_new_lupopedia.sql.

CREATE TABLE lupo_escalation_tasks (
  escalation_task_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  thread_id bigint NOT NULL,
  message_id bigint NOT NULL,
  task_type varchar(64) NOT NULL,
  status varchar(32) NOT NULL DEFAULT 'open',
  assigned_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (escalation_task_id)
);

CREATE INDEX lupo_escalation_tasks_idx_actor_id ON lupo_escalation_tasks (actor_id);
CREATE INDEX lupo_escalation_tasks_idx_thread_id ON lupo_escalation_tasks (thread_id);
CREATE INDEX lupo_escalation_tasks_idx_message_id ON lupo_escalation_tasks (message_id);
CREATE INDEX lupo_escalation_tasks_idx_status ON lupo_escalation_tasks (status);
CREATE INDEX lupo_escalation_tasks_idx_assigned_actor_id ON lupo_escalation_tasks (assigned_actor_id);
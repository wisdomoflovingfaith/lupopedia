-- =============================================================================
-- ONE-TIME CCV alignment (UTC filename stamp 20260513054924).
-- Source: docs/prd/02_C-i_CHANNELS_DISCUSSIONS.md sections
--   "Channel Coherence Vector (CCV)", "Future schema direction (sketch)",
--   per-channel centroid table sketch, interpreter embedding fields.
-- docs/doctrine/ccv/: not present in repo; channel_model_doctrine.md has no CCV DDL.
-- Replace {{prefix}} with LUPO_TABLE_PREFIX (e.g. lupo_) before execute.
-- Apply via validated migration runner (DB009). No FOREIGN KEY clauses.
-- Second run: duplicate column / duplicate table errors (by design).
-- =============================================================================

-- PRD 02_C CCV -- aggregate channel centroid rows (sketch: lupo_channel_centroids)
CREATE TABLE {{prefix}}channel_centroids (
  channel_centroid_id bigint NOT NULL,
  federation_node_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  channel_key varchar(64) DEFAULT NULL COMMENT 'Denormalized channel_key for CCV joins per PRD 02_C',
  centroid_vector json DEFAULT NULL COMMENT 'PRD 02_C CCV channel centroid c as opaque float array JSON',
  beta_used decimal(5,4) DEFAULT NULL COMMENT 'PRD 02_C EMA beta used when centroid was updated',
  embedding_model_id varchar(64) DEFAULT NULL COMMENT 'PRD 02_C CCV model id beside centroid',
  object_count_snapshot bigint DEFAULT NULL COMMENT 'PRD 02_C optional N used when centroid was computed',
  computed_ymdhis bigint NOT NULL DEFAULT 0 COMMENT 'PRD 02_C CCV BIGINT UTC YYYYMMDDHHIISS',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (channel_centroid_id)
);

CREATE INDEX {{prefix}}channel_centroids_idx_node_channel ON {{prefix}}channel_centroids (federation_node_id, channel_id);
CREATE INDEX {{prefix}}channel_centroids_idx_channel_key ON {{prefix}}channel_centroids (channel_key);
CREATE INDEX {{prefix}}channel_centroids_idx_computed ON {{prefix}}channel_centroids (computed_ymdhis);
CREATE INDEX {{prefix}}channel_centroids_idx_deleted ON {{prefix}}channel_centroids (is_deleted);

-- PRD 02_C CCV -- optional actor centroid / mood cache
ALTER TABLE {{prefix}}actors
  ADD COLUMN ccv_actor_centroid_embedding_json json DEFAULT NULL COMMENT 'PRD 02_C CCV opaque float array JSON; m_obj / actor centroid family';

-- PRD 02_C CCV -- channel-scoped content rows
ALTER TABLE {{prefix}}contents
  ADD COLUMN coherence_magnitude decimal(5,4) DEFAULT NULL COMMENT 'PRD 02_C CCV cosine magnitude m in [-1,1]',
  ADD COLUMN pono_kapu_state varchar(16) DEFAULT NULL COMMENT 'PRD 02_C: pono|kapu|neutral relative to channel centroid',
  ADD COLUMN centroid_snapshot json DEFAULT NULL COMMENT 'PRD 02_C: frozen channel centroid c at ccv_computed_ymdhis',
  ADD COLUMN ccv_embedding_vector json DEFAULT NULL COMMENT 'PRD 02_C: object embedding o as opaque float array JSON',
  ADD COLUMN embedding_model_id varchar(64) DEFAULT NULL COMMENT 'PRD 02_C CCV embedding model id',
  ADD COLUMN ccv_computed_ymdhis bigint DEFAULT NULL COMMENT 'PRD 02_C CCV BIGINT UTC YYYYMMDDHHIISS',
  ADD COLUMN deviation_vector json DEFAULT NULL COMMENT 'PRD 02_C CCV optional (o - c) deviation signature';

-- PRD 02_C CCV -- dialog messages (primary CCV object store)
ALTER TABLE {{prefix}}dialog_messages
  ADD COLUMN coherence_magnitude decimal(5,4) DEFAULT NULL COMMENT 'PRD 02_C CCV cosine magnitude m in [-1,1]',
  ADD COLUMN pono_kapu_state varchar(16) DEFAULT NULL COMMENT 'PRD 02_C: pono|kapu|neutral relative to channel centroid',
  ADD COLUMN centroid_snapshot json DEFAULT NULL COMMENT 'PRD 02_C: frozen channel centroid c at ccv_computed_ymdhis',
  ADD COLUMN ccv_embedding_vector json DEFAULT NULL COMMENT 'PRD 02_C: object embedding o as opaque float array JSON',
  ADD COLUMN embedding_model_id varchar(64) DEFAULT NULL COMMENT 'PRD 02_C CCV embedding model id',
  ADD COLUMN ccv_computed_ymdhis bigint DEFAULT NULL COMMENT 'PRD 02_C CCV BIGINT UTC YYYYMMDDHHIISS',
  ADD COLUMN deviation_vector json DEFAULT NULL COMMENT 'PRD 02_C CCV optional (o - c) deviation signature';

-- PRD 02_C CCV -- thread-level cached aggregates
ALTER TABLE {{prefix}}dialog_threads
  ADD COLUMN coherence_magnitude decimal(5,4) DEFAULT NULL COMMENT 'PRD 02_C CCV thread-level cosine magnitude m',
  ADD COLUMN pono_kapu_state varchar(16) DEFAULT NULL COMMENT 'PRD 02_C: pono|kapu|neutral relative to channel centroid',
  ADD COLUMN centroid_snapshot json DEFAULT NULL COMMENT 'PRD 02_C: thread partial centroid or snapshot JSON',
  ADD COLUMN ccv_embedding_vector json DEFAULT NULL COMMENT 'PRD 02_C: thread aggregate embedding cache JSON',
  ADD COLUMN embedding_model_id varchar(64) DEFAULT NULL COMMENT 'PRD 02_C CCV embedding model id',
  ADD COLUMN ccv_computed_ymdhis bigint DEFAULT NULL COMMENT 'PRD 02_C CCV BIGINT UTC YYYYMMDDHHIISS',
  ADD COLUMN deviation_vector json DEFAULT NULL COMMENT 'PRD 02_C CCV optional deviation signature';

-- PRD 02_C CCV -- channel_key-scoped prompts
ALTER TABLE {{prefix}}prompts
  ADD COLUMN coherence_magnitude DECIMAL(5,4) DEFAULT NULL COMMENT 'PRD 02_C CCV cosine magnitude m',
  ADD COLUMN pono_kapu_state VARCHAR(16) DEFAULT NULL COMMENT 'PRD 02_C: pono|kapu|neutral',
  ADD COLUMN centroid_snapshot JSON DEFAULT NULL COMMENT 'PRD 02_C: frozen centroid snapshot JSON',
  ADD COLUMN ccv_embedding_vector JSON DEFAULT NULL COMMENT 'PRD 02_C: object embedding o JSON',
  ADD COLUMN embedding_model_id VARCHAR(64) DEFAULT NULL COMMENT 'PRD 02_C CCV embedding model id',
  ADD COLUMN ccv_computed_ymdhis BIGINT DEFAULT NULL COMMENT 'PRD 02_C CCV BIGINT UTC YYYYMMDDHHIISS',
  ADD COLUMN deviation_vector JSON DEFAULT NULL COMMENT 'PRD 02_C CCV optional deviation signature';

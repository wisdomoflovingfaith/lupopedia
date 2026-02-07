-- FILE: database/migrations/dev_20260204_theme_support.sql
-- TYPE: sql
-- Purpose: One-time dev migration — add active_theme_slug to lupo_federation_nodes for theme-per-node support.
-- Run once; then install_new_lupopedia.sql is updated and this file is obsolete.
-- Doctrine: no FKs, no triggers, presentation-only (theme identity). DB-agnostic.

ALTER TABLE lupo_federation_nodes ADD COLUMN active_theme_slug varchar(64) DEFAULT 'default';

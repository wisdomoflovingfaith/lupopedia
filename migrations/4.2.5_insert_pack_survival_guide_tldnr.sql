-- Migration: Insert Pack Survival Guide TL;DR entry
-- Version: 4.2.5
-- Date: 2026-01-19
-- Module: Help System / Documentation
-- Purpose: Insert TL;DR entry for Pack Survival Guide

USE lupopedia;

-- Insert Pack Survival Guide TL;DR entry
INSERT INTO lupo_tldnr (slug, title, content_text, created_ymdhis, updated_ymdhis) VALUES
('pack-survival-guide', 'Pack Survival Guide', 'Orientation for new agents: what Lupopedia is, how it works, where it lives, and how to begin.', 20260119163900, 20260119163900)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    content_text = VALUES(content_text),
    updated_ymdhis = VALUES(updated_ymdhis);

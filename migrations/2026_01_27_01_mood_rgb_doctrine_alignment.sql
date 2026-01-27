-- Align mood_rgb storage with doctrine (CHAR(6), fixed-width, default '666666').

-- Normalize existing dialog channel values before type enforcement.
UPDATE lupo_dialog_channels
SET mood_rgb = REPLACE(mood_rgb, '#', '')
WHERE mood_rgb LIKE '#%';

UPDATE lupo_dialog_channels
SET mood_rgb = '666666'
WHERE mood_rgb IS NULL OR mood_rgb = '';

-- Enforce CHAR(6) with default on dialog channels.
ALTER TABLE lupo_dialog_channels
  MODIFY COLUMN mood_rgb CHAR(6) NOT NULL DEFAULT '666666'
    COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';

-- Add mood_rgb to dialog threads.
ALTER TABLE lupo_dialog_threads
  ADD COLUMN mood_rgb CHAR(6) NOT NULL DEFAULT '666666'
    COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)'
    AFTER metadata_json;

-- Add mood_rgb to channel state for fast access.
ALTER TABLE lupo_channel_state
  ADD COLUMN mood_rgb CHAR(6) NOT NULL DEFAULT '666666'
    COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)'
    AFTER emotional_state_json;

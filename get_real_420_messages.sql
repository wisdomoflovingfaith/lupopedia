SELECT dialog_message_id,
       from_actor_id,
       channel_id,
       dialog_thread_id,
       message_text,
       message_type,
       created_ymdhis
FROM lupo_dialog_messages
WHERE channel_id = 420
ORDER BY dialog_message_id ASC;

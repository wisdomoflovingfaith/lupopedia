<?php
/**
 * Channel 66 Questions and Answers admin handler.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminChannel66QaHandler
{
    public static function render($db, $prefix, $base)
    {
        $questions = self::fetchQuestions($db, $prefix);
        $thread_rows = self::fetchThreads($db, $prefix);

        $html = '';
        $html .= '<div class="admin-section-ch66-qa">';
        $html .= '<h2 style="margin-top:0;">Channel 66 Questions and Answers</h2>';
        $html .= '<p class="admin-section-description">Questions are shown when mapped directly to channel 66 or to a dialog thread that belongs to channel 66.</p>';
        $html .= '<p><a href="' . htmlspecialchars($base . '/threads?channel_id=66') . '">Open MVP threads for channel 66</a></p>';

        if (!empty($thread_rows)) {
            $html .= '<div style="margin: 0 0 20px 0; padding: 12px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">';
            $html .= '<strong>Mapped Channel 66 Threads:</strong> ';
            $links = array();
            foreach ($thread_rows as $thread) {
                $links[] = '<a href="' . htmlspecialchars($base . '/messages?thread_id=' . (int) $thread['dialog_thread_id']) . '">' . (int) $thread['dialog_thread_id'] . '</a>';
            }
            $html .= implode(', ', $links);
            $html .= '</div>';
        }

        if (empty($questions)) {
            $html .= '<p class="admin-empty">No Channel 66 questions are currently mapped in the database.</p>';
            $html .= '</div>';
            return $html;
        }

        $html .= '<table class="admin-users-table">';
        $html .= '<thead><tr>';
        $html .= '<th>Question ID</th>';
        $html .= '<th>Mapping</th>';
        $html .= '<th>Slug</th>';
        $html .= '<th>Question</th>';
        $html .= '<th>Answers</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($questions as $question) {
            $html .= '<tr>';
            $html .= '<td>' . (int) $question['question_id'] . '</td>';
            $html .= '<td>' . htmlspecialchars(self::renderMappingLabel($question, $base)) . '</td>';
            $html .= '<td>' . htmlspecialchars((string) $question['slug']) . '</td>';
            $html .= '<td>' . nl2br(htmlspecialchars((string) $question['question_text'])) . '</td>';
            $html .= '<td>' . self::renderAnswersHtml($question) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '</div>';

        return $html;
    }

    private static function fetchThreads($db, $prefix)
    {
        return $db->fetchAll(
            "SELECT dialog_thread_id, title FROM {$prefix}dialog_threads WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY dialog_thread_id ASC",
            array('channel_id' => 66)
        );
    }

    private static function fetchQuestions($db, $prefix)
    {
        $rows = $db->fetchAll(
            "SELECT q.question_id, q.slug, q.question_text, m.object_type, m.object_id, a.answer_id, a.answer_text "
            . "FROM {$prefix}questions q "
            . "JOIN {$prefix}question_map m ON m.question_id = q.question_id AND m.is_deleted = 0 "
            . "LEFT JOIN {$prefix}answers a ON a.question_id = q.question_id AND a.is_deleted = 0 "
            . "WHERE q.is_deleted = 0 AND ((m.object_type = :channel_type AND m.object_id = :channel_id) OR (m.object_type = :thread_type AND m.object_id IN (SELECT dialog_thread_id FROM {$prefix}dialog_threads WHERE channel_id = :thread_channel_id AND is_deleted = 0))) "
            . "ORDER BY q.question_id DESC, a.answer_id ASC",
            array(
                'channel_type' => 'channel',
                'channel_id' => 66,
                'thread_type' => 'thread',
                'thread_channel_id' => 66,
            )
        );

        $grouped = array();
        foreach ($rows as $row) {
            $question_id = (int) $row['question_id'];
            if (!isset($grouped[$question_id])) {
                $grouped[$question_id] = array(
                    'question_id' => $question_id,
                    'slug' => isset($row['slug']) ? $row['slug'] : '',
                    'question_text' => isset($row['question_text']) ? $row['question_text'] : '',
                    'object_type' => isset($row['object_type']) ? $row['object_type'] : '',
                    'object_id' => isset($row['object_id']) ? (int) $row['object_id'] : 0,
                    'answers' => array(),
                );
            }
            if (!empty($row['answer_id'])) {
                $grouped[$question_id]['answers'][] = array(
                    'answer_id' => (int) $row['answer_id'],
                    'answer_text' => isset($row['answer_text']) ? $row['answer_text'] : '',
                );
            }
        }

        return array_values($grouped);
    }

    private static function renderMappingLabel($question, $base)
    {
        $object_type = isset($question['object_type']) ? $question['object_type'] : '';
        $object_id = isset($question['object_id']) ? (int) $question['object_id'] : 0;
        if ($object_type === 'thread' && $object_id > 0) {
            return 'thread #' . $object_id;
        }
        if ($object_type === 'channel') {
            return 'channel #66';
        }
        return $object_type . ' #' . $object_id;
    }

    private static function renderAnswersHtml($question)
    {
        if (empty($question['answers'])) {
            return '<span style="color:#64748b;">No answers</span>';
        }

        $parts = array();
        foreach ($question['answers'] as $answer) {
            $parts[] = '<div style="margin-bottom:8px;"><strong>#' . (int) $answer['answer_id'] . '</strong><br>' . nl2br(htmlspecialchars((string) $answer['answer_text'])) . '</div>';
        }
        return implode('', $parts);
    }
}
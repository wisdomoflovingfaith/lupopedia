<?php
/**
 * Shared HTML for one channel feed line (channels UI + fetch-messages delta).
 * Returns a single root div; class "chat-line" preserved for Active Output Rule JS.
 */
if (!function_exists('lupo_channel_chat_row_html')) {
    /**
     * Message text on directed rows: matches active actor-tab label color (black on bright tabs in channels JS).
     * Light text when tab accent is very dark (observer-style shell).
     *
     * @param string $accentHex #RRGGBB (recipient tab data-actor-color)
     * @return string #RRGGBB
     */
    function lupo_channel_directed_row_text_hex($accentHex) {
        $rgb = lupo_channel_hex_to_rgb_tuple($accentHex);
        $luma = ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000;
        if ($luma < 100) {
            return '#f5f5f5';
        }
        return '#000000';
    }

    /**
     * Tab accent hex for channel UI (must match channels/index.php actor-tab-bar data-actor-color).
     *
     * @param int $actor_id Actor id (sender or recipient).
     * @return string Leading #, six hex digits (lowercase).
     */
    function lupo_channel_actor_tab_accent_hex($actor_id) {
        static $tab = array(
            102 => '#ffd700',
            116 => '#1e90ff',
            111 => '#32cd32',
            117 => '#8a2be2',
            2   => '#cccccc',
            26  => '#1a1b1e',
            3   => '#ffb6c1',
        );
        $id = (int) $actor_id;
        return isset($tab[$id]) ? $tab[$id] : '#666666';
    }

    /**
     * @param string $hex #RRGGBB or RRGGBB
     * @return array Three ints 0-255
     */
    function lupo_channel_hex_to_rgb_tuple($hex) {
        $h = preg_replace('/[^0-9A-Fa-f]/', '', (string) $hex);
        if (strlen($h) !== 6) {
            return array(102, 102, 102);
        }
        return array(
            (int) hexdec(substr($h, 0, 2)),
            (int) hexdec(substr($h, 2, 2)),
            (int) hexdec(substr($h, 4, 2)),
        );
    }

    /**
     * Assignee / recipient row: same rgba tint and tab label text as channels bottom bar (directed + tasks to assignee).
     *
     * @param int    $assignee_actor_id to_actor_id (task assignee or DM recipient)
     * @param string $class             Row class string (by ref; appends chat-line-directed)
     * @param string $dataTargetAttr    data-target-accent attribute (by ref)
     * @return string Full style="..." attribute value including leading space, or empty when assignee invalid
     */
    function lupo_channel_assignee_row_style_and_attrs($assignee_actor_id, &$class, &$dataTargetAttr) {
        $id = (int) $assignee_actor_id;
        if ($id <= 0) {
            $dataTargetAttr = '';
            return '';
        }
        $class .= ' chat-line-directed';
        $targetAccent = lupo_channel_actor_tab_accent_hex($id);
        $escTarget = htmlspecialchars($targetAccent, ENT_QUOTES, 'UTF-8');
        $rgbT = lupo_channel_hex_to_rgb_tuple($targetAccent);
        $dirText = lupo_channel_directed_row_text_hex($targetAccent);
        $escDirText = htmlspecialchars($dirText, ENT_QUOTES, 'UTF-8');
        $dataTargetAttr = ' data-target-accent="' . $escTarget . '"';
        return ' style="background-color:rgba(' . $rgbT[0] . ',' . $rgbT[1] . ',' . $rgbT[2] . ',0.35);border-left:3px solid ' . $escTarget . ';border-bottom:1px solid #2a2a2a;color:' . $escDirText . ';"';
    }

    function lupo_channel_chat_row_hms($ymdhis) {
        $s = str_pad((string) (int) $ymdhis, 14, '0', STR_PAD_LEFT);
        return substr($s, 8, 2) . ':' . substr($s, 10, 2) . ':' . substr($s, 12, 2);
    }

    function lupo_channel_actor_slug_class($from_actor_id) {
        static $map = array(
            102 => 'msg-slug-cursor',
            116 => 'msg-slug-auggie',
            111 => 'msg-slug-gemini',
            117 => 'msg-slug-cascade',
            26  => 'msg-slug-thoth',
            2   => 'msg-slug-lilith',
            3   => 'msg-slug-rose',
        );
        $id = (int) $from_actor_id;
        return isset($map[$id]) ? $map[$id] : 'msg-slug-default';
    }

    /**
     * @param array $msg Row from dialog_messages join (actor_display, msg_bg, thread colors, to_actor_id).
     * @param int   $operator_actor_id Logged-in actor (own-message tint + route button).
     * @param bool  $show_route_btn When true and operator is logged in, append [send to actor] (mockup_try2.htm).
     */
    function lupo_channel_chat_row_html($msg, $operator_actor_id = 0, $show_route_btn = true) {
        $hms = lupo_channel_chat_row_hms(isset($msg['created_ymdhis']) ? $msg['created_ymdhis'] : 0);
        $label = strtoupper(isset($msg['actor_display']) ? $msg['actor_display'] : 'UNKNOWN');
        if ($label === '') {
            $label = 'UNKNOWN';
        }
        $from_id = (int) (isset($msg['from_actor_id']) ? $msg['from_actor_id'] : 0);
        $operator_actor_id = (int) $operator_actor_id;
        $to_raw = isset($msg['to_actor_id']) ? $msg['to_actor_id'] : null;
        $to_id = ($to_raw === null || $to_raw === '') ? 0 : (int) $to_raw;
        $is_thoth = ($from_id === 26);
        $mt = isset($msg['message_type']) ? $msg['message_type'] : '';

        $slug = $is_thoth ? lupo_channel_actor_slug_class(26) : lupo_channel_actor_slug_class($from_id);
        $class = 'chat-message chat-line ' . $slug;
        if ($is_thoth) {
            $class .= ' chat-line-thoth';
        }
        if ($mt === 'task') {
            $class .= ' chat-line-task';
        }
        $is_own = ($operator_actor_id > 0 && $from_id === $operator_actor_id);
        if ($is_own) {
            $class .= ' own-message';
        }
        if ($to_id <= 0) {
            $class .= ' broadcast-message';
        }

        $senderAccent = lupo_channel_actor_tab_accent_hex($from_id);
        $escSenderAccent = htmlspecialchars($senderAccent, ENT_QUOTES, 'UTF-8');

        $text = isset($msg['message_text']) ? $msg['message_text'] : '';
        $from_is_agent = isset($msg['from_is_agent']) ? (int) $msg['from_is_agent'] : 0;
        if ($from_is_agent === 1) {
            if (!class_exists('DialogMvpService', false)) {
                $svcRoot = dirname(dirname(__DIR__));
                $svcPath = $svcRoot . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DialogMvpService.php';
                if (is_file($svcPath)) {
                    require_once $svcPath;
                }
            }
            if (class_exists('DialogMvpService', false)) {
                $senderDisplay = isset($msg['actor_display']) ? trim((string) $msg['actor_display']) : '';
                if ($senderDisplay === '') {
                    $senderDisplay = $label;
                }
                $toDisp = isset($msg['to_actor_display']) ? trim((string) $msg['to_actor_display']) : '';
                if ($to_id > 0 && $toDisp !== '') {
                    $recipientName = $toDisp;
                } elseif ($to_id > 0) {
                    $recipientName = 'Actor ' . $to_id;
                } else {
                    $recipientName = 'the recipient';
                }
                $recipientIsGroup = false;
                $text = DialogMvpService::rewriteFirstPersonEnglishForHumanIngest(
                    $text,
                    $senderDisplay,
                    $recipientName,
                    $recipientIsGroup
                );
            }
        }
        $escLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $escHms = htmlspecialchars($hms, ENT_QUOTES, 'UTF-8');
        $escText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $mid = isset($msg['dialog_message_id']) ? (int) $msg['dialog_message_id'] : 0;
        $routeBtn = '';
        if ($show_route_btn && $operator_actor_id > 0 && $mid > 0) {
            $routeBtn = '<button type="button" class="msg-route-btn" data-dialog-message-id="' . $mid . '" aria-label="Send copy to another channel">[send to actor]</button>';
        }

        $styleAttr = '';
        $dataTargetAttr = '';
        if ($is_thoth) {
            $styleAttr = ' style="background-color:#111111;border-left:3px solid #1a1b1e;border-bottom:1px solid #2a2a2a;color:#FFD700;"';
        } elseif ($mt === 'task' && $to_id > 0) {
            $styleAttr = lupo_channel_assignee_row_style_and_attrs($to_id, $class, $dataTargetAttr);
        } elseif ($mt === 'task') {
            $styleAttr = ' style="background-color:#ffffff;border-left:3px solid #ffbf00;border-bottom:1px solid #cccccc;color:#333333;"';
        } elseif ($mt === 'system') {
            $styleAttr = ' style="background-color:#1a1a1e;border-left:3px solid #444444;border-bottom:1px solid #2a2a2a;color:#d0d0d0;"';
        } elseif ($to_id > 0) {
            $styleAttr = lupo_channel_assignee_row_style_and_attrs($to_id, $class, $dataTargetAttr);
        } else {
            $rgb = lupo_channel_hex_to_rgb_tuple($senderAccent);
            if ($is_own) {
                $alpha = ($from_id === 3) ? '0.20' : '0.25';
                $styleAttr = ' style="background-color:rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $alpha . ');border-left:3px solid ' . $escSenderAccent . ';border-bottom:1px solid #2a2a2a;color:#d0d0d0;"';
            } else {
                $styleAttr = ' style="background-color:#1a1a1e;border-left:3px solid ' . $escSenderAccent . ';border-bottom:1px solid #2a2a2a;color:#d0d0d0;"';
            }
        }

        return '<div class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" data-from-actor-id="' . $from_id . '" data-to-actor-id="' . $to_id . '" data-actor-display="' . $escLabel . '" data-actor-accent="' . $escSenderAccent . '"' . $dataTargetAttr . $styleAttr . '>'
            . '<div class="msg-line-core">'
            . '<span class="msg-time">' . $escHms . '</span>'
            . '<span class="msg-sender">[' . $escLabel . ']</span>'
            . '<span class="msg-content">' . $escText . '</span>'
            . '</div>'
            . $routeBtn
            . '</div>';
    }
}

<?php
function lupo_crafty_emotional_snapshot($operator) {
    return [
        'pono' => isset($operator['pono_score']) ? $operator['pono_score'] : '—',
        'pilau' => isset($operator['pilau_score']) ? $operator['pilau_score'] : '—',
        'kapakai' => isset($operator['kapakai_score']) ? $operator['kapakai_score'] : '—',
    ];
}

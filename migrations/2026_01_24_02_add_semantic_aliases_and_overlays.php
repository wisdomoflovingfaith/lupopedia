<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lupo_aliases')) {
            DB::statement(
                "CREATE TABLE lupo_aliases (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    slug VARCHAR(255) NOT NULL,
                    alias VARCHAR(255) NOT NULL,
                    alias_type VARCHAR(50) DEFAULT 'semantic',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY uniq_alias (alias),
                    INDEX idx_slug (slug)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );
        }

        if (!Schema::hasTable('lupo_semantic_overlays')) {
            DB::statement(
                "CREATE TABLE lupo_semantic_overlays (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    slug VARCHAR(255) NOT NULL,
                    overlay_key VARCHAR(255) NOT NULL,
                    overlay_value TEXT NOT NULL,
                    context VARCHAR(255) DEFAULT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_slug (slug),
                    INDEX idx_context (context)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lupo_semantic_overlays')) {
            DB::statement("DROP TABLE lupo_semantic_overlays");
        }

        if (Schema::hasTable('lupo_aliases')) {
            DB::statement("DROP TABLE lupo_aliases");
        }
    }
};

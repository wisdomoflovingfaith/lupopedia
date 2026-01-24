<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lupo_contents') && !Schema::hasColumn('lupo_contents', 'custom_path')) {
            DB::statement(
                "ALTER TABLE lupo_contents
                    ADD COLUMN custom_path VARCHAR(255) NULL
                        COMMENT 'Semantic routing override; not a filesystem path'
                        AFTER slug"
            );
            DB::statement(
                "CREATE UNIQUE INDEX idx_custom_path
                    ON lupo_contents (custom_path)"
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lupo_contents') && Schema::hasColumn('lupo_contents', 'custom_path')) {
            DB::statement("DROP INDEX idx_custom_path ON lupo_contents");
            DB::statement("ALTER TABLE lupo_contents DROP COLUMN custom_path");
        }
    }
};

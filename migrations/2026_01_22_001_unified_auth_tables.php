<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create lupo_crafty_user_mapping table
        Schema::create('lupo_crafty_user_mapping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lupo_user_id')->nullable();
            $table->unsignedInteger('crafty_operator_id')->nullable();
            $table->string('mapping_type', 50)->default('manual'); // manual, auto, imported
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('lupo_user_id');
            $table->index('crafty_operator_id');
            $table->index('mapping_type');

            // Unique constraints to prevent duplicate mappings
            $table->unique(['lupo_user_id'], 'unique_lupo_user_mapping');
            $table->unique(['crafty_operator_id'], 'unique_crafty_operator_mapping');
        });

        // Create unified_sessions table
        Schema::create('unified_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 255)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('system_context', 50); // lupopedia, crafty_syntax, unified
            $table->json('session_data')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            // Indexes for performance
            $table->index('session_id');
            $table->index('user_id');
            $table->index('system_context');
            $table->index('expires_at');

            // Foreign key constraint (optional, can be added if users table exists)
            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });

        // Add crafty_operator_id to users table (additive change only)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedInteger('crafty_operator_id')->nullable()->after('id');
                $table->index('crafty_operator_id');
                
                // Add unique constraint if needed
                $table->unique('crafty_operator_id', 'unique_users_crafty_operator_id');
            });
        }

        // Create audit table for authentication events
        Schema::create('auth_audit_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('crafty_operator_id')->nullable();
            $table->string('event_type', 50); // login, logout, session_created, session_destroyed
            $table->string('system_context', 50);
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->json('event_data')->nullable();
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index('crafty_operator_id');
            $table->index('event_type');
            $table->index('system_context');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order of creation
        Schema::dropIfExists('auth_audit_log');
        Schema::dropIfExists('unified_sessions');
        Schema::dropIfExists('lupo_crafty_user_mapping');

        // Remove crafty_operator_id from users table
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'crafty_operator_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['crafty_operator_id']);
                $table->dropUnique('unique_users_crafty_operator_id');
                $table->dropColumn('crafty_operator_id');
            });
        }
    }
};

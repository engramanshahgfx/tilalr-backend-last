<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists and columns exist before modifying
        if (Schema::hasTable('roles')) {
            // SQLite doesn't support MODIFY or COMMENT
            // Instead, we need to recreate the table or just add columns if they don't exist

            // Check if allowed_modules column exists
            if (!Schema::hasColumn('roles', 'allowed_modules')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->json('allowed_modules')->nullable();
                });
            }

            // Check if is_active column exists
            if (!Schema::hasColumn('roles', 'is_active')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
            }

            // Check if description column exists (it was added in previous migration)
            if (!Schema::hasColumn('roles', 'description')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->text('description')->nullable();
                });
            }

            // Check if name column exists (it was added in previous migration)
            if (!Schema::hasColumn('roles', 'name')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->string('name')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $columnsToDrop = [];

                if (Schema::hasColumn('roles', 'allowed_modules')) {
                    $columnsToDrop[] = 'allowed_modules';
                }
                if (Schema::hasColumn('roles', 'is_active')) {
                    $columnsToDrop[] = 'is_active';
                }
                if (Schema::hasColumn('roles', 'description')) {
                    $columnsToDrop[] = 'description';
                }
                if (Schema::hasColumn('roles', 'name')) {
                    $columnsToDrop[] = 'name';
                }

                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};

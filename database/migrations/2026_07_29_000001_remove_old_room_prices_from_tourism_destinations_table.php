<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourism_destinations', function (Blueprint $table) {
            if (Schema::hasColumn('tourism_destinations', 'double_room_price')) {
                $table->dropColumn('double_room_price');
            }
            if (Schema::hasColumn('tourism_destinations', 'single_room_price')) {
                $table->dropColumn('single_room_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tourism_destinations', function (Blueprint $table) {
<<<<<<< Updated upstream
            if (!Schema::hasColumn('tourism_destinations', 'double_room_price')) {
                $table->decimal('double_room_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('tourism_destinations', 'single_room_price')) {
                $table->decimal('single_room_price', 10, 2)->nullable();
            }
=======
            $table->decimal('double_room_price', 10, 2)->nullable();
            $table->decimal('single_room_price', 10, 2)->nullable();
>>>>>>> Stashed changes
        });
    }
};

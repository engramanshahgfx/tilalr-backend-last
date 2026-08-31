<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourism_destinations', function (Blueprint $table) {
<<<<<<< Updated upstream
            if (!Schema::hasColumn('tourism_destinations', 'person_prices')) {
                $table->json('person_prices')->nullable()->after('price');
            }
=======
            $table->json('person_prices')->nullable()->after('single_room_price');
>>>>>>> Stashed changes
        });
    }

    public function down(): void
    {
        Schema::table('tourism_destinations', function (Blueprint $table) {
<<<<<<< Updated upstream
            if (Schema::hasColumn('tourism_destinations', 'person_prices')) {
                $table->dropColumn('person_prices');
            }
=======
            $table->dropColumn('person_prices');
>>>>>>> Stashed changes
        });
    }
};

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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->string('distribution_scope')->default('public'); // public, private, user_restricted
            $table->boolean('auto_apply')->default(false);
            $table->string('status')->default('active'); // active, inactive, archived
            $table->string('type')->default('percentage'); // percentage, fixed
            $table->decimal('value', 10, 2);
            $table->decimal('min_booking_amount', 10, 2)->nullable();
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->boolean('is_stackable')->default(false);
            $table->string('category_type')->default('all'); // all, international, tourism_offer, jamoula_offer
            $table->json('applicable_package_ids')->nullable();
            $table->string('target_cohort')->default('everyone'); // everyone, first_time, specified_users
            $table->json('target_emails')->nullable();
            $table->integer('max_uses')->nullable();
            $table->integer('uses_count')->default(0);
            $table->integer('max_uses_per_user')->nullable()->default(1);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

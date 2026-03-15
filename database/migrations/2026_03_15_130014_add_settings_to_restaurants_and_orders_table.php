<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('allow_pay_on_spot')->default(true)->after('is_active');
            $table->boolean('allow_online_payment')->default(true)->after('allow_pay_on_spot');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('cash')->after('status'); // cash, online, card
            $table->string('payment_status')->default('pending')->after('payment_method'); // pending, paid, failed
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['allow_pay_on_spot', 'allow_online_payment']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status']);
        });
    }
};

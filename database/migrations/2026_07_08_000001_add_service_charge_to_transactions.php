<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('service_charge_amount', 15, 2)->default(0)->after('tax_amount');
        });

        DB::statement("ALTER TABLE transactions MODIFY payment_method VARCHAR(20) NOT NULL DEFAULT 'cash'");
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('service_charge_amount');
        });

        DB::statement("ALTER TABLE transactions MODIFY payment_method ENUM('cash','qris','card','transfer') NOT NULL DEFAULT 'cash'");
    }
};

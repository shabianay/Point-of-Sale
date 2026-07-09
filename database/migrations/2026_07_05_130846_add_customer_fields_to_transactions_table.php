<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('code');
            $table->string('table_number')->nullable()->after('customer_name');
            $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in')->after('table_number');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'table_number', 'order_type']);
        });
    }
};

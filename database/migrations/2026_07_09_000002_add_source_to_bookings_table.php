<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('source', ['online', 'manual', 'recurring'])->default('online')->after('employee_id');
            $table->foreignId('recurring_booking_id')->nullable()->after('source')
                ->constrained('recurring_bookings')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['recurring_booking_id']);
            $table->dropColumn(['source', 'recurring_booking_id']);
        });
    }
};

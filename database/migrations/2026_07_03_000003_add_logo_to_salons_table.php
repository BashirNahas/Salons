<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('description');
            $table->string('email')->nullable()->after('phone');
            $table->string('instagram')->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->dropColumn(['logo', 'email', 'instagram']);
        });
    }
};

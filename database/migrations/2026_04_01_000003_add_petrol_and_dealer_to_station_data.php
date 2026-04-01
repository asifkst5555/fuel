<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stations', function (Blueprint $table) {
            $table->string('dealer')->nullable()->after('location');
        });

        Schema::table('fuel_statuses', function (Blueprint $table) {
            $table->boolean('petrol')->default(false)->after('octane');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_statuses', function (Blueprint $table) {
            $table->dropColumn('petrol');
        });

        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('dealer');
        });
    }
};

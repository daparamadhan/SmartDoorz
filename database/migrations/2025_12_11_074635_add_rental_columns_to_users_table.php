<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'rental_start')) {
                $table->datetime('rental_start')->nullable()->after('updated_at');
            }
            if (!Schema::hasColumn('users', 'rental_end')) {
                $table->datetime('rental_end')->nullable()->after('rental_start');
            }
            if (!Schema::hasColumn('users', 'rental_months')) {
                $table->integer('rental_months')->default(1)->after('rental_end');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rental_start', 'rental_end', 'rental_months']);
        });
    }
};
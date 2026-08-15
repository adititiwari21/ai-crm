<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('user_details', 'website')) {
            Schema::table('user_details', function (Blueprint $table) {
                $table->string('website')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user_details', 'website')) {
            Schema::table('user_details', function (Blueprint $table) {
                $table->dropColumn('website');
            });
        }
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_settings', function (Blueprint $table) {
            $table->string('admin_name')->default('Administrator')->after('id');
            $table->string('admin_role')->default('Super Admin')->after('admin_name');
        });
    }

    public function down(): void
    {
        Schema::table('crm_settings', function (Blueprint $table) {
            $table->dropColumn(['admin_name', 'admin_role']);
        });
    }
};

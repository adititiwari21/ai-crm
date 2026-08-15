<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Website column is already created
        // in the original user_details migration.
    }

    public function down(): void
    {
        // Nothing to reverse.
    }
};
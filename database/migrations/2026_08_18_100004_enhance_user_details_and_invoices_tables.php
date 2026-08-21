<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->text('ai_summary')->nullable();
            $table->string('industry')->nullable();
            $table->string('target_audience')->nullable();
            $table->text('tech_stack')->nullable();
            $table->integer('lead_score')->default(50); // 1-100
            $table->text('generated_pitch')->nullable();
            $table->string('status')->default('New'); // New, Contacted, Qualified, Converted, Lost
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->json('items')->nullable(); // JSON list of line items
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn([
                'ai_summary',
                'industry',
                'target_audience',
                'tech_stack',
                'lead_score',
                'generated_pitch',
                'status',
            ]);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'items',
                'tax_rate',
                'discount',
                'notes',
            ]);
        });
    }
};

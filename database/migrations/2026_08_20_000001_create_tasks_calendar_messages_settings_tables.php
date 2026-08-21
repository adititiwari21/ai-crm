<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tasks Table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['High', 'Medium', 'Low'])->default('Medium');
            $table->enum('status', ['To Do', 'In Progress', 'Completed'])->default('To Do');
            $table->date('due_date')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Calendar Events Table
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('event_type')->default('meeting'); // meeting, call, deadline, reminder
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Messages Table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name')->default('Administrator');
            $table->string('recipient_name')->default('Client Support');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 4. System Settings Table
        Schema::create('crm_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('CRM Pro Enterprises');
            $table->string('company_email')->default('admin@crmpro.ai');
            $table->string('company_phone')->default('+1 (555) 000-1234');
            $table->string('currency')->default('USD');
            $table->string('currency_symbol')->default('$');
            $table->string('gemini_api_key')->nullable();
            $table->string('gemini_model')->default('gemini-2.5-flash');
            $table->string('webhook_secret')->default('whsec_crm_pro_secret_9981');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_settings');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('tasks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->String('name');
            $table->string('description');
            $table->enum('status',['pending','accepted','completed','canceled'])->default('pending');
            $table->foreignId('worker_id')->constrained('users');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('request_id')->constrained('services_requests');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};

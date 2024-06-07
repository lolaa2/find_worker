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
        Schema::create('worker_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['pending','accepted','rejected','completed'])->default('pending');
            $table->string('note');
            $table->string('worker_name');
            $table->string('company_name');
            $table->foreignId('worker_id')->constrained('users');
            $table->foreignId('company_id')->constrained('companies');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('skils');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_requests');
    }
};

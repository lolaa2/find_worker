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
        Schema::create('company_employee', function (Blueprint $table) {
            $table->id();
            $table->string('worker_name');
            $table->string('company_name');
            $table->foreignId('worker_id');
            $table->foreign('worker_id')->references('id')->on("users");
            $table->foreignId('company_id');
            $table->foreign('company_id')->references('id')->on("companies");
            $table->enum('status', ['active', 'inactive'])->default('active'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_employee');
    }
};

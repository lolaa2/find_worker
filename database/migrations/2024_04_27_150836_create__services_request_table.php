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
        Schema::create('services_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['pending','accepted','rejected','completed'])->default('pending');
            $table->foreignId('service_id');
            $table->foreign('service_id')->references('id')->on("services");
            $table->string('note');
            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on("customers");
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->tinyInteger('rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_services_request');
    }
};

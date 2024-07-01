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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            // $table->foreignId("company_id");
            // $table->foreign("company_id")->references("id")->on("companies");
            $table->morphs('serviceable');
            $table->foreignId('city_id');
            $table->double("price");
            // $table->foreignId("user_id");
            // $table->foreign("user_id")->references("id")->on("users");
 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_servises');
    }
};

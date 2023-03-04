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
        Schema::create('weathers', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitude', 11, 8);
            $table->decimal('latitude', 10, 8);
            $table->string("city_name");
            $table->decimal('visibility');
            $table->text("weather")->nullable();
            $table->text("main")->nullable();
            $table->text("wind")->nullable();
            $table->text("clouds")->nullable();
            $table->text("units")->nullable();
            $table->text("location")->nullable();
            $table->text("timezone_module")->nullable();
            $table->text("sun_module")->nullable();
            $table->text("forecast")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weathers');
    }
};

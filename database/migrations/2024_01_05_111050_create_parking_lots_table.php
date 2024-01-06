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
        Schema::create('parking_lots', function (Blueprint $table) {
            $table->id();
            $table->string('title',250);
            $table->decimal('fee_per_hour', 8, 2)->default(20.00);
            $table->string('status')->default('active');          
            $table->double('lat');          
            $table->double('lon');          
            $table->integer('grace_min_period')->default(15);          
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_lots');
    }
};

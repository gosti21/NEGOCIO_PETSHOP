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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->string('country', length: 20)->default('Perú');
            $table->string('department', length: 60)->default('Junín');
            $table->string('province', length: 60);
            $table->string('district', length: 60);
            $table->string('street');
            $table->string('reference');
            $table->integer('receiver');
            $table->json('receiver_info');
            $table->boolean('default')->default(false);

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};

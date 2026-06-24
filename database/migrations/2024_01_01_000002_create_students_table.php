<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('registration_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F']);
            $table->string('phone')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->decimal('grade', 4, 2);
            $table->enum('semester', ['S1', 'S2']);
            $table->string('academic_year');
            $table->text('comment')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
            $table->unique(['student_id', 'subject_id', 'semester', 'academic_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};

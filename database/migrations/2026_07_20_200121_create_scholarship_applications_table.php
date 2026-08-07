<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('matricula', 9)->nullable();
            $table->decimal('current_gpa', 4, 2);
            $table->integer('requested_percentage')->nullable();
            $table->integer('assigned_percentage')->nullable();
            $table->text('justification')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('kardex_url')->nullable();
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('professor_comment')->nullable();
            $table->boolean('validated_economy')->default(false);
            $table->boolean('validated_disability')->default(false);
            $table->boolean('validated_grades')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
    }
};
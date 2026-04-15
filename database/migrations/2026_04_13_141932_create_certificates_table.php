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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('certificate_number')->nullable()->unique();
            $table->string('program_id')->nullable();
            $table->text('description')->nullable();
            $table->string('grade')->nullable();
            $table->string('level')->nullable();
            $table->dateTime('issued_date')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['Draft', 'Di Proses', 'Di Terbitkan'])->default('Draft');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};

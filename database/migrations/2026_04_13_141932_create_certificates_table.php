<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username');
            $table->string('certificate_number')->nullable()->unique();
            $table->string('program_name');
            $table->string('grade')->nullable();
            $table->string('level')->nullable();
            $table->dateTime('publication_date')->nullable();
            $table->string('file_path')->nullable();
            $table->text('digital_signature')->nullable();
            $table->enum('status', ['Draft', 'Di Terbitkan'])->default('Draft');
            $table->text('description')->nullable();
            $table->boolean('is_delete')->default(false);
            $table->dateTime('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};

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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('path');

            $table->integer('x_position_name');
            $table->integer('y_position_name');
            $table->integer('width_position_name');
            $table->integer('height_position_name');

            $table->integer('x_position_cert_number');
            $table->integer('y_position_cert_number');
            $table->integer('width_cert_number');
            $table->integer('height_cert_number');

            $table->integer('x_position_grade');
            $table->integer('y_position_grade');
            $table->integer('width_grade');
            $table->integer('height_grade');

            $table->integer('x_position_program_name');
            $table->integer('y_position_program_name');
            $table->integer('width_program_name');
            $table->integer('height_program_name');

            $table->integer('x_position_publish_date');
            $table->integer('y_position_publish_date');
            $table->integer('width_publish_date');
            $table->integer('height_publish_date');

            $table->integer('x_position_qr_code');
            $table->integer('y_position_qr_code');
            $table->integer('width_qr_code');
            $table->integer('height_qr_code');

            $table->integer('width_template');
            $table->integer('height_template');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};

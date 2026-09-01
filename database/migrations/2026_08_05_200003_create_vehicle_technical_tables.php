<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 8. Specifications
        Schema::create('vehicle_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('engine_id')->nullable()->constrained('vehicle_engines');
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
            $table->string('drive_type')->nullable(); // FWD, RWD, AWD
            $table->integer('doors')->nullable();
            $table->integer('seats')->nullable();
            $table->timestamps();
        });

        // 9. Documents
        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('type'); // Registration, Insurance, etc.
            $table->string('document_number')->nullable();
            $table->string('file_url');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 10. Images
        Schema::create('vehicle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('image_url');
            $table->string('type')->default('exterior'); // Front, Back, Interior, Damage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_images');
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('vehicle_specifications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 7. Business Verification
        Schema::create('business_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('business_type'); // Garage, Mechanic, Seller, Fleet Company
            $table->unsignedBigInteger('business_id');
            $table->foreignId('verified_by')->nullable()->constrained('administrators');
            $table->string('verification_status')->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 8. Document Verification
        Schema::create('document_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id'); // Link to user_verifications or vehicle_documents
            $table->foreignId('reviewed_by')->nullable()->constrained('administrators');
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_verifications');
        Schema::dropIfExists('business_verifications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 17. Integration Providers
        Schema::create('integration_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Twilio, Google, Stripe
            $table->string('category'); // SMS, MAPS, PAYMENT, AI
            $table->string('documentation_url')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 16. Integrations
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('integration_providers')->onDelete('cascade');
            $table->string('name');
            $table->json('configuration')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 18. Integration Credentials
        Schema::create('integration_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained('integrations')->onDelete('cascade');
            $table->string('key_name');
            $table->text('encrypted_value');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integration_credentials');
        Schema::dropIfExists('integrations');
        Schema::dropIfExists('integration_providers');
    }
};

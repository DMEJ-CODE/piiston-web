<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 2. Fleets
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->string('type'); // LOGISTICS, TRANSPORT, DELIVERY, etc.
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Fleet Members (Staff managing the fleet)
        Schema::create('fleet_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role'); // FLEET_MANAGER, DISPATCHER, ACCOUNTANT, etc.
            $table->json('permissions')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. Fleet Vehicle Assignment
        Schema::create('fleet_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->timestamp('assigned_date')->useCurrent();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_vehicles');
        Schema::dropIfExists('fleet_members');
        Schema::dropIfExists('fleets');
    }
};

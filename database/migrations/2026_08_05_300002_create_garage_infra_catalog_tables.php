<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 8. Workshop Bays
        Schema::create('workshop_bays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('Mechanical'); // Painting, Electrical, etc.
            $table->integer('capacity')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 13. Garage Services (Catalog)
        Schema::create('garage_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 17. Suppliers
        Schema::create('garage_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('garage_companies')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 19. Equipment
        Schema::create('garage_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('garage_branches')->onDelete('cascade');
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 20. Equipment Maintenance
        Schema::create('equipment_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('garage_equipment')->onDelete('cascade');
            $table->date('date');
            $table->text('description')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->date('next_due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenances');
        Schema::dropIfExists('garage_equipment');
        Schema::dropIfExists('garage_suppliers');
        Schema::dropIfExists('garage_services');
        Schema::dropIfExists('workshop_bays');
    }
};

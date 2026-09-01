<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Administrators
        Schema::create('administrators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('employee_number')->nullable()->unique();
            $table->string('position')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Admin Roles
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // SUPER_ADMIN, FINANCE_ADMIN, etc.
            $table->string('description')->nullable();
            $table->integer('level')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Admin Permissions
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // user.suspend, finance.view, etc.
            $table->string('module'); // users, business, finance, etc.
            $table->string('action'); // create, read, approve, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Role Permission Pivot
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('admin_roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('admin_permissions')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. Administrator Assignment
        Schema::create('administrator_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrator_id')->constrained('administrators')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('admin_roles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrator_roles');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('administrators');
    }
};

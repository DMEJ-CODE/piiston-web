<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop old columns
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            $table->string('name')->nullable()->change();

            // Split name
            $table->string('first_name')->after('id')->nullable();
            $table->string('last_name')->after('first_name')->nullable();

            // Add other profile fields
            $table->string('phone')->unique()->after('email')->nullable();
            $table->string('profile_photo')->nullable()->after('password');
            $table->date('date_of_birth')->nullable()->after('profile_photo');
            $table->string('gender')->nullable()->after('date_of_birth');

            // Localization (already have country_id, adding language_id and timezone_id if not present)
            $table->foreignId('language_id')->nullable()->after('country_id')->constrained('languages');
            $table->foreignId('timezone_id')->nullable()->after('language_id')->constrained('timezones');

            // Status and tracking
            $table->string('status')->default('active')->after('timezone_id');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('updated_at');
        });

        // Optional: Move data from 'name' to 'first_name' before dropping 'name'
        // \DB::statement("UPDATE users SET first_name = name");
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('name');
        // });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('timezone_id');
            $table->dropConstrainedForeignId('language_id');
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'profile_photo',
                'date_of_birth', 'gender', 'status', 'phone_verified_at', 'last_login_at',
            ]);
            $table->string('name')->after('id');
            $table->string('role')->nullable();
        });
    }
};

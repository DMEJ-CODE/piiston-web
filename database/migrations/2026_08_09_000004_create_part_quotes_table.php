<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('part_requests')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('seller_profiles')->onDelete('cascade');
            $table->foreignId('part_id')->nullable()->constrained('spare_parts'); // Optional if matching existing part
            $table->decimal('price', 12, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('availability')->nullable();
            $table->text('notes')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status')->default('SENT'); // SENT, ACCEPTED, REJECTED, EXPIRED
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_quotes');
    }
};

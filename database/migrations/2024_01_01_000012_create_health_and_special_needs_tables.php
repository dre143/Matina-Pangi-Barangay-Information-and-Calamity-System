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
        // Health records table
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->enum('record_type', ['immunization', 'checkup', 'medication', 'condition']);
            $table->string('vaccine_name')->nullable(); // For immunizations
            $table->date('date_administered')->nullable();
            $table->string('health_condition')->nullable();
            $table->text('medication')->nullable();
            $table->text('doctor_notes')->nullable();
            $table->date('next_checkup_date')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('record_type');
        });

        // PWD support table
        Schema::create('pwd_support', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->string('disability_type');
            $table->enum('disability_level', ['mild', 'moderate', 'severe'])->default('moderate');
            $table->string('pwd_id_number')->nullable();
            $table->date('pwd_id_expiry')->nullable();
            $table->text('assistive_devices_needed')->nullable();
            $table->text('support_services_received')->nullable();
            $table->string('caregiver_name')->nullable();
            $table->string('caregiver_contact')->nullable();
            $table->timestamps();
        });

        // Senior health table
        Schema::create('senior_health', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->string('senior_id_number')->nullable();
            $table->string('pension_type')->nullable();
            $table->decimal('pension_amount', 10, 2)->nullable();
            $table->text('health_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->enum('mobility_status', ['independent', 'assisted', 'wheelchair', 'bedridden'])->default('independent');
            $table->text('emergency_medical_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senior_health');
        Schema::dropIfExists('pwd_support');
        Schema::dropIfExists('health_records');
    }
};

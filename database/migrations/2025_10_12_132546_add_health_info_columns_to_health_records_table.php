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
        Schema::table('health_records', function (Blueprint $table) {
            // Add new health information columns
            $table->string('blood_type', 10)->nullable()->after('resident_id');
            $table->decimal('height', 5, 2)->nullable()->after('blood_type')->comment('Height in cm');
            $table->decimal('weight', 5, 2)->nullable()->after('height')->comment('Weight in kg');
            $table->text('medical_conditions')->nullable()->after('weight');
            $table->text('allergies')->nullable()->after('medical_conditions');
            $table->text('medications')->nullable()->after('allergies');
            $table->string('emergency_contact', 255)->nullable()->after('medications');
            $table->string('emergency_contact_number', 20)->nullable()->after('emergency_contact');
            $table->string('philhealth_number', 50)->nullable()->after('emergency_contact_number');
            $table->text('notes')->nullable()->after('philhealth_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            // Drop the columns in reverse order
            $table->dropColumn([
                'notes',
                'philhealth_number',
                'emergency_contact_number',
                'emergency_contact',
                'medications',
                'allergies',
                'medical_conditions',
                'weight',
                'height',
                'blood_type',
            ]);
        });
    }
};

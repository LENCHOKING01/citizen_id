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
        Schema::table('biometrics', function (Blueprint $table) {
            // Add missing columns that the BiometricController expects
            $table->foreignId('application_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('fingerprint_data')->nullable()->change();
            $table->text('facial_recognition_data')->nullable();
            $table->text('iris_scan_data')->nullable();
            $table->string('signature_path')->nullable();
            $table->foreignId('captured_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biometrics', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropColumn('application_id');
            $table->dropColumn('facial_recognition_data');
            $table->dropColumn('iris_scan_data');
            $table->dropColumn('signature_path');
            $table->dropForeign(['captured_by']);
            $table->dropColumn('captured_by');
        });
    }
};

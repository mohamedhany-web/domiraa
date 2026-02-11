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
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'log_name')) {
                $table->string('log_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('activity_logs', 'causer_type')) {
                $table->string('causer_type')->nullable()->after('description');
            }
            if (!Schema::hasColumn('activity_logs', 'causer_id')) {
                $table->unsignedBigInteger('causer_id')->nullable()->after('causer_type');
                $table->index(['causer_id', 'causer_type']);
            }
            if (!Schema::hasColumn('activity_logs', 'subject_type')) {
                $table->string('subject_type')->nullable()->after('causer_id');
            }
            if (!Schema::hasColumn('activity_logs', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');
                $table->index(['subject_id', 'subject_type']);
            }
            if (!Schema::hasColumn('activity_logs', 'properties')) {
                $table->json('properties')->nullable()->after('subject_id');
            }
        });
        
        // Migrate existing data if user_id column exists
        if (Schema::hasColumn('activity_logs', 'user_id')) {
            \DB::statement('UPDATE activity_logs SET causer_id = user_id, causer_type = "App\\\\Models\\\\User" WHERE user_id IS NOT NULL AND causer_id IS NULL');
        }
        
        // Migrate model_type and model_id to subject columns
        if (Schema::hasColumn('activity_logs', 'model_type') && Schema::hasColumn('activity_logs', 'model_id')) {
            \DB::statement('UPDATE activity_logs SET subject_id = model_id, subject_type = model_type WHERE model_id IS NOT NULL AND subject_id IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['log_name', 'causer_type', 'causer_id', 'subject_type', 'subject_id', 'properties']);
        });
    }
};


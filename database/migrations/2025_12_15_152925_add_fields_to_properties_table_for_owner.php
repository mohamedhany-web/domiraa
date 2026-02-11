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
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('area')->nullable()->after('address');
            $table->integer('rooms')->nullable()->after('area');
            $table->integer('bathrooms')->nullable()->after('rooms');
            $table->string('floor')->nullable()->after('bathrooms');
            $table->json('amenities')->nullable()->after('floor');
            $table->integer('views_count')->default(0)->after('is_suspended');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['area', 'rooms', 'bathrooms', 'floor', 'amenities', 'views_count']);
        });
    }
};

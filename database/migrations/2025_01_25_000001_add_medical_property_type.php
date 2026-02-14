<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $exists = DB::table('property_types')->where('slug', 'medical')->exists();
        if (!$exists) {
            DB::table('property_types')->insert([
                'name' => 'طبي',
                'slug' => 'medical',
                'icon' => 'fas fa-stethoscope',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('property_types')->where('slug', 'medical')->delete();
    }
};

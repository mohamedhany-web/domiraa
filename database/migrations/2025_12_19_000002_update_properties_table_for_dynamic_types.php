<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة عمود property_type_id
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('property_type_id')->nullable()->after('user_id')->constrained()->onDelete('restrict');
        });
        
        // نقل البيانات من enum إلى foreign key
        $residentialType = DB::table('property_types')->where('slug', 'residential')->first();
        $commercialType = DB::table('property_types')->where('slug', 'commercial')->first();
        
        if ($residentialType && $commercialType) {
            DB::table('properties')
                ->where('type', 'residential')
                ->update(['property_type_id' => $residentialType->id]);
                
            DB::table('properties')
                ->where('type', 'commercial')
                ->update(['property_type_id' => $commercialType->id]);
        }
        
        // حذف عمود type القديم
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة إضافة عمود type
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('type', ['residential', 'commercial'])->after('user_id');
        });
        
        // نقل البيانات من foreign key إلى enum
        $residentialType = DB::table('property_types')->where('slug', 'residential')->first();
        $commercialType = DB::table('property_types')->where('slug', 'commercial')->first();
        
        if ($residentialType && $commercialType) {
            DB::table('properties')
                ->where('property_type_id', $residentialType->id)
                ->update(['type' => 'residential']);
                
            DB::table('properties')
                ->where('property_type_id', $commercialType->id)
                ->update(['type' => 'commercial']);
        }
        
        // حذف foreign key
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['property_type_id']);
            $table->dropColumn('property_type_id');
        });
    }
};


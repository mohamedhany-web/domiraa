<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('admin_status'); // إيقاف مؤقت
            $table->timestamp('suspended_until')->nullable()->after('is_suspended');
            $table->integer('quality_score')->nullable()->after('suspended_until'); // نظام تقييم تلقائي (0-100)
            $table->json('quality_details')->nullable()->after('quality_score'); // تفاصيل التقييم (جودة الصور، وضوح السعر، اكتمال البيانات)
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['is_suspended', 'suspended_until', 'quality_score', 'quality_details']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // من قدم البلاغ
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null'); // الوحدة المشكو منها
            $table->foreignId('reported_user_id')->nullable()->constrained('users')->onDelete('set null'); // المستخدم المشكو منه
            $table->enum('complaint_type', ['property', 'owner', 'tenant', 'other']); // نوع البلاغ
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['new', 'under_review', 'resolved', 'rejected'])->default('new');
            $table->text('admin_response')->nullable();
            $table->enum('action_taken', ['none', 'warning', 'suspend_property', 'suspend_account'])->default('none');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};

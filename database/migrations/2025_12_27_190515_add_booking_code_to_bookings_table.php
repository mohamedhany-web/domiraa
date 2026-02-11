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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code')->nullable()->after('id');
            $table->index('booking_code');
        });
        
        // Generate booking codes for existing bookings
        $bookings = \App\Models\Booking::whereNull('booking_code')->get();
        foreach ($bookings as $booking) {
            $booking->update([
                'booking_code' => 'BOOK-' . str_pad($booking->id, 8, '0', STR_PAD_LEFT)
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['booking_code']);
            $table->dropColumn('booking_code');
        });
    }
};

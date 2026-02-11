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
        // إصلاح الغرف الموجودة التي لا تحتوي على room_name أو room_number
        $rooms = DB::table('rooms')->whereNull('room_name')->orWhere('room_name', '')->get();
        
        foreach ($rooms as $room) {
            $roomNumber = $room->room_number ?: $room->id;
            $roomName = "غرفة {$roomNumber}";
            
            DB::table('rooms')
                ->where('id', $room->id)
                ->update([
                    'room_name' => $roomName,
                    'room_number' => (string) $roomNumber,
                ]);
        }
        
        // إصلاح الغرف التي لا تحتوي على room_number
        $roomsWithoutNumber = DB::table('rooms')->whereNull('room_number')->orWhere('room_number', '')->get();
        
        foreach ($roomsWithoutNumber as $room) {
            DB::table('rooms')
                ->where('id', $room->id)
                ->update([
                    'room_number' => (string) $room->id,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا يمكن التراجع عن هذا التغيير
    }
};

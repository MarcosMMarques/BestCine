<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Room;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $room = Room::create([
            'name' => 'A',
            'seat_quantity' => 50,
        ]);

        for ($row = 1; $row <= 5; $row++) {
            for ($number = 1; $number <= 10; $number++) {
                Seat::create([
                    'row' => $row,
                    'number' => $number,
                    'room_id' => $room->id
                ]);
            }
        }
    }
}

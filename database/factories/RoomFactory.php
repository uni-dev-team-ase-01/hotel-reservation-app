<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Hotel;
use App\Enum\RoomType as EnumRoomType;
use App\Enum\RoomStatusType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roomTypes = ['Single', 'Double', 'Suite', 'Deluxe Room', 'Standard Room', 'Family Room', 'Connecting Room'];
        $roomStatuses = ['Available', 'Occupied', 'Maintenance', 'Cleaning Required', 'Out of Order'];
        $roomLocations = ['Floor ' . $this->faker->numberBetween(1,20), 'Wing ' . Arr::random(['A', 'B', 'C', 'North', 'South']), 'Pool View', 'Ocean View', 'Garden View', 'Near Elevator'];

        return [
            'hotel_id' => Hotel::factory(),
            'room_number' => $this->faker->unique()->numerify('###'), // Simpler room number
            'room_type' => class_exists(EnumRoomType::class) ? Arr::random(array_column(EnumRoomType::cases(), 'value')) : Arr::random($roomTypes),
            'description' => $this->faker->optional(0.8)->paragraph(2), // 80% chance of having a description
            'occupancy' => $this->faker->numberBetween(1, 6),
            // 'price_per_night' field removed as it will be handled by RoomRateFactory
            'status' => class_exists(RoomStatusType::class) ? Arr::random(array_column(RoomStatusType::cases(),'value')) : Arr::random($roomStatuses),
            'beds_configuration' => json_encode(Arr::random([ // More varied bed configurations
                ['type' => 'king', 'count' => 1],
                ['type' => 'queen', 'count' => 1],
                ['type' => 'double', 'count' => 2],
                ['type' => 'single', 'count' => 2],
                ['type' => 'king', 'count' => 1, 'sofa_bed' => 1],
            ])),
            'area_sqm' => $this->faker->optional(0.9)->numberBetween(15, 120),
            'amenities' => json_encode($this->faker->randomElements(
                ['TV', 'Air Conditioning', 'Mini Bar', 'Wi-Fi', 'Safe', 'Hair Dryer', 'Coffee Maker', 'Balcony', 'Work Desk', 'Ironing Board'],
                $this->faker->numberBetween(3, 7)
            )),
            'images' => json_encode($this->faker->randomElements( // More diverse placeholder images
                [
                    'placeholders/rooms/room_view_1.jpg',
                    'placeholders/rooms/room_interior_1.jpg',
                    'placeholders/rooms/room_bathroom_1.jpg',
                    'placeholders/rooms/room_detail_1.jpg',
                    'placeholders/rooms/room_balcony_1.jpg',
                ],
                $this->faker->numberBetween(1, 3) // Select 1 to 3 images randomly
            )),
            'notes' => $this->faker->optional(0.4)->sentence, // 40% chance of having notes
            'is_available' => function (array $attributes) {
                // More robust check for availability based on common status names
                $unavailableStatuses = ['occupied', 'maintenance', 'cleaning_required', 'out_of_order', RoomStatusType::OCCUPIED->value ?? 'occupied', RoomStatusType::MAINTENANCE->value ?? 'maintenance'];
                return !in_array(strtolower($attributes['status']), array_map('strtolower', $unavailableStatuses));
            },
            'location' => $this->faker->optional(0.7)->randomElement($roomLocations), // Added location field
        ];
    }

    /**
     * Indicate that the room is a suite.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function suite()
    {
        return $this->state(function (array $attributes) {
            return [
                'room_type' => class_exists(EnumRoomType::class) ? EnumRoomType::SUITE->value : 'Suite',
                'occupancy' => $this->faker->numberBetween(2, 5),
                // price_per_night removed from here too
                'area_sqm' => $this->faker->numberBetween(50, 150),
            ];
        });
    }

    /**
     * Indicate that the room is unavailable.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unavailable()
    {
        return $this->state(function (array $attributes) {
            $status = class_exists(RoomStatusType::class) ? RoomStatusType::OCCUPIED->value : 'Occupied'; // Default to Occupied for unavailable
            if (class_exists(RoomStatusType::class) && defined(RoomStatusType::class . '::MAINTENANCE')) { // Check if enum and case exist
                 $status = Arr::random([RoomStatusType::OCCUPIED->value, RoomStatusType::MAINTENANCE->value]);
            } else {
                 $status = Arr::random(['Occupied', 'Maintenance']);
            }
            return [
                'status' => $status,
                'is_available' => false,
            ];
        });
    }
}

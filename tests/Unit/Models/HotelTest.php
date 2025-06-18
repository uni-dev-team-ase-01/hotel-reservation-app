<?php

namespace Tests\Unit\Models; // Changed namespace for model-specific unit tests

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Service;
use App\Models\Reservation;
use App\Models\UserHotel; // If UserHotel is the explicit pivot model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_hotel_belongs_to_a_user_manager()
    {
        // This test assumes 'user_id' is a foreign key on the hotels table
        // and represents the manager or owner.
        $manager = User::factory()->create();
        $hotel = Hotel::factory()->create(['user_id' => $manager->id]);

        $this->assertInstanceOf(User::class, $hotel->user);
        $this->assertEquals($manager->id, $hotel->user->id);
    }

    /** @test */
    public function a_hotel_has_many_rooms()
    {
        $hotel = Hotel::factory()->create();
        Room::factory()->count(3)->create(['hotel_id' => $hotel->id]); // RoomFactory needs to exist

        $this->assertInstanceOf(Room::class, $hotel->rooms->first());
        $this->assertCount(3, $hotel->rooms);
    }

    /** @test */
    public function a_hotel_can_have_many_services_through_hotel_services()
    {
        $hotel = Hotel::factory()->create();
        $service1 = Service::factory()->create(); // ServiceFactory needs to exist
        $service2 = Service::factory()->create();

        // Assuming a many-to-many relationship defined as services() on Hotel model
        $hotel->services()->attach([$service1->id, $service2->id]);

        $this->assertInstanceOf(Service::class, $hotel->services->first());
        $this->assertCount(2, $hotel->services);
    }

    /** @test */
    public function a_hotel_has_many_reservations()
    {
        $hotel = Hotel::factory()->create();
        // ReservationFactory needs user_id and hotel_id
        Reservation::factory()->count(2)->create(['hotel_id' => $hotel->id]);

        $this->assertInstanceOf(Reservation::class, $hotel->reservations->first());
        $this->assertCount(2, $hotel->reservations);
    }

    /** @test */
    public function a_hotel_can_be_associated_with_many_users_staff_via_user_hotels()
    {
        // This tests the users (staff) relationship via the user_hotels pivot
        $hotel = Hotel::factory()->create();
        $staff1 = User::factory()->create();
        $staff2 = User::factory()->create();

        // Assuming a many-to-many relationship defined as staffUsers() on Hotel model
        // If the pivot table `user_hotels` stores additional attributes, they might need to be specified here.
        // For a simple attach, IDs are enough.
        $hotel->staffUsers()->attach([$staff1->id, $staff2->id]); // Or $hotel->users()->attach(...)

        $this->assertInstanceOf(User::class, $hotel->staffUsers->first()); // Adjust 'staffUsers' if relationship name is different
        $this->assertCount(2, $hotel->staffUsers);
    }

    /** @test */
    public function hotel_has_fillable_attributes()
    {
        $fillable = [
            'user_id', 'name', 'address', 'star_rating',
            'description', 'images', 'type', 'active',
            // Added fields based on the enhanced HotelFactory
            'city', 'country', 'phone_number', 'email_address', 'website_url',
            'check_in_time', 'check_out_time', 'amenities', 'policies',
            'latitude', 'longitude', 'price_per_night', 'currency_code',
            'availability_status', 'slug', 'owner_name', 'contact_person_name',
            'contact_person_email', 'contact_person_phone', 'additional_information',
            'approved_at', 'view_count', 'booking_count', 'min_stay_duration',
            'max_stay_duration', 'cancellation_policy_days'
        ];
        $hotel = new Hotel();
        // Using assertSameCanonicalizing for potentially large arrays to ignore order and type juggling issues if any.
        $this->assertEqualsCanonicalizing($fillable, $hotel->getFillable());
    }

    /** @test */
    public function hotel_casts_attributes()
    {
        $hotel = Hotel::factory()->make(); // Using make as we only check casts, not DB persistence

        $expectedCasts = [
            'star_rating' => 'float', // Or 'integer' depending on model
            'active' => 'boolean',
            'images' => 'array', // Assuming it's cast to array in the model
            'amenities' => 'array', // Assuming amenities is also cast to array
            // 'id' => 'int' // Default, usually not needed to assert
        ];
        // Get actual casts from the model instance
        $actualModelCasts = $hotel->getCasts();

        // Filter actual casts to only include keys present in expectedCasts for precise comparison
        $filteredActualCasts = array_intersect_key($actualModelCasts, $expectedCasts);
        $this->assertEquals($expectedCasts, $filteredActualCasts);

        // Test actual casting behavior for 'images'
        $jsonData = ['image1.jpg', 'path/to/image2.png'];
        $hotelWithImages = Hotel::factory()->create(['images' => json_encode($jsonData)]);
        $this->assertIsArray($hotelWithImages->images);
        $this->assertEquals($jsonData, $hotelWithImages->images);

        // Test 'active' casting
        $hotelActive = Hotel::factory()->create(['active' => 1]);
        $this->assertIsBool($hotelActive->active);
        $this->assertTrue($hotelActive->active);

        $hotelInactive = Hotel::factory()->create(['active' => 0]);
        $this->assertIsBool($hotelInactive->active);
        $this->assertFalse($hotelInactive->active);
    }

    // /** @test */
    // public function image_url_accessor_returns_default_if_no_images()
    // {
    //     $hotel = Hotel::factory()->create(['images' => null]);
    //     // Assuming default image path like 'assets/images/category/hotel/4by3/02.jpg'
    //     // This default path might need to be defined in the Hotel model or a config
    //     $this->assertStringContainsString('assets/images/default_hotel_image.jpg', $hotel->image_url);
    // }

    // /** @test */
    // public function image_url_accessor_returns_first_image_if_images_exist()
    // {
    //     $images = ['uploads/hotel1.jpg', 'uploads/hotel2.jpg'];
    //     // Ensure the factory stores images in a way the accessor expects (e.g. JSON array)
    //     $hotel = Hotel::factory()->create(['images' => $images]);
    //     $this->assertEquals(asset($images[0]), $hotel->image_url);
    // }

    // /** @test */
    // public function gallery_images_accessor_returns_array_of_image_urls()
    // {
    //     $images = ['gallery/img1.jpg', 'gallery/img2.jpg', 'gallery/img3.jpg'];
    //     $hotel = Hotel::factory()->create(['images' => $images]);
    //     $expectedGallery = [asset($images[0]), asset($images[1]), asset($images[2])];
    //     $this->assertEquals($expectedGallery, $hotel->gallery_images);
    // }
}

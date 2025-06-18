<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\TravelCompany;
use App\Enum\UserRoleType; // Added for clarity in user factory state
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class TravelCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_travel_company_belongs_to_a_user()
    {
        // Ensure the user created has the TRAVEL_AGENT role for consistency
        $user = User::factory()->create(['role' => UserRoleType::TRAVEL_AGENT->value]);
        $travelCompany = TravelCompany::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $travelCompany->user);
        $this->assertEquals($user->id, $travelCompany->user->id);
    }

    /** @test */
    public function travel_company_has_fillable_attributes()
    {
        // Updated to reflect the enhanced TravelCompanyFactory
        $fillable = [
            'user_id', 'name', 'contact_person_name', 'email', 'phone_number',
            'address', 'website_url', 'description', 'registration_number',
            'license_number', 'is_approved', 'approved_at', 'logo_path',
            'commission_rate', 'discount_rate', 'services_offered', 'operational_hours', 'slug'
        ];
        $travelCompany = new TravelCompany();
        $this->assertEqualsCanonicalizing($fillable, $travelCompany->getFillable());
    }

    /** @test */
    public function travel_company_casts_attributes()
    {
        $travelCompany = TravelCompany::factory()->make();

        $expectedCasts = [
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
            'commission_rate' => 'float', // Added based on factory
            'discount_rate' => 'float',
            'services_offered' => 'array', // Added based on factory (JSON in DB)
        ];
        $actualCasts = $travelCompany->getCasts(); // Get all casts from the model

        // Check that all expected casts are present and correct in the actual casts
        foreach ($expectedCasts as $key => $type) {
            $this->assertArrayHasKey($key, $actualCasts, "Cast for '$key' not found.");
            $this->assertEquals($type, $actualCasts[$key], "Cast type for '$key' does not match.");
        }


        // Test actual casting behavior
        $companyApproved = TravelCompany::factory()->create(['is_approved' => 1, 'approved_at' => now()]);
        $this->assertIsBool($companyApproved->is_approved);
        $this->assertTrue($companyApproved->is_approved);
        $this->assertInstanceOf(Carbon::class, $companyApproved->approved_at);

        $companyRate = TravelCompany::factory()->create(['discount_rate' => 10.50]);
        $this->assertIsFloat($companyRate->discount_rate);
        $this->assertEquals(10.50, $companyRate->discount_rate);

        $services = ['Flights', 'Hotels'];
        $companyServices = TravelCompany::factory()->create(['services_offered' => json_encode($services)]);
        $this->assertIsArray($companyServices->services_offered);
        $this->assertEquals($services, $companyServices->services_offered);
    }

    /** @test */
    public function it_has_an_approved_scope()
    {
        TravelCompany::factory()->create(['is_approved' => true, 'approved_at' => now()]);
        TravelCompany::factory()->count(2)->create(['is_approved' => false, 'approved_at' => null]);

        // This assumes an 'approved()' scope exists in the TravelCompany model:
        // public function scopeApproved($query) { return $query->where('is_approved', true); }
        $this->assertCount(1, TravelCompany::approved()->get());
    }
}

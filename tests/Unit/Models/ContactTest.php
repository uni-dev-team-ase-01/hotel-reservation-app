<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Contact;
use App\Enum\ContactMessageStatusEnum; // Assuming this Enum might exist
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon; // For datetime casting checks if needed

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_contact_can_belong_to_a_user()
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->forUser($user)->create(); // Using the forUser state
        $this->assertInstanceOf(User::class, $contact->user);
        $this->assertEquals($user->id, $contact->user->id);
    }

    /** @test */
    public function a_contact_can_be_anonymous()
    {
        $contact = Contact::factory()->create(['user_id' => null]);
        $this->assertNull($contact->user_id); // Check user_id directly for clarity
        $this->assertNull($contact->user);   // Check relationship
    }

    /** @test */
    public function contact_has_fillable_attributes()
    {
        // Updated to reflect ContactFactory fields
        $fillable = [
            'user_id', 'name', 'email', 'phone_number', 'subject',
            'message', 'status', 'ip_address', 'user_agent',
            'notes', 'replied_at', 'archived_at'
        ];
        $contact = new Contact();
        $this->assertEqualsCanonicalizing($fillable, $contact->getFillable());
    }

    /** @test */
    public function contact_casts_attributes()
    {
        $contact = Contact::factory()->make();

        $expectedCasts = [
            'replied_at' => 'datetime',
            'archived_at' => 'datetime',
            // 'id' => 'int' // Default
        ];

        // Conditionally add status to expectedCasts if Enum is used
        // if (class_exists(ContactMessageStatusEnum::class) && property_exists(new Contact(), 'casts') && array_key_exists('status', (new Contact())->getCasts()) && (new Contact())->getCasts()['status'] === ContactMessageStatusEnum::class) {
        //     $expectedCasts['status'] = ContactMessageStatusEnum::class;
        // }
        // More robust check if the model actually casts to the enum. This requires reading the model.
        // For now, we'll assume string status if not explicitly cast to Enum in the model.

        $actualCasts = $contact->getCasts();
        $filteredActualCasts = array_intersect_key($actualCasts, $expectedCasts);

        $this->assertEquals($expectedCasts, $filteredActualCasts);

        // Test actual casting behavior for dates
        $repliedAtString = '2024-07-15 12:00:00';
        $contactReplied = Contact::factory()->replied()->create(['replied_at' => $repliedAtString]); // Use replied state
        $this->assertInstanceOf(Carbon::class, $contactReplied->replied_at);
        $this->assertEquals(Carbon::parse($repliedAtString)->toDateTimeString(), $contactReplied->replied_at->toDateTimeString());
    }

    /** @test */
    public function contact_status_can_use_enum_if_defined_in_model()
    {
        if (!class_exists(ContactMessageStatusEnum::class)) {
            $this->markTestSkipped('ContactMessageStatusEnum not found, skipping enum test.');
            return;
        }

        $contact = Contact::factory()->unread()->create(); // Uses state which sets enum or string
        $retrievedContact = Contact::find($contact->id);

        // Check if the model actually casts the status to the enum.
        // This requires knowing the Contact model's $casts property.
        // If it does, $retrievedContact->status should be an instance of the enum.
        if (isset($retrievedContact->getCasts()['status']) && $retrievedContact->getCasts()['status'] === ContactMessageStatusEnum::class) {
            $this->assertInstanceOf(ContactMessageStatusEnum::class, $retrievedContact->status);
            $this->assertEquals(ContactMessageStatusEnum::UNREAD, $retrievedContact->status);
        } else {
            // If not cast to an enum instance, check against the enum's value.
            $this->assertEquals(ContactMessageStatusEnum::UNREAD->value, $retrievedContact->status, "Status stored as value, not enum instance. Check model cast.");
        }
    }
}

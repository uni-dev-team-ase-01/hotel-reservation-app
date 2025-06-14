<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Contact;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_contact_page_is_accessible()
    {
        $response = $this->get(route('contact'));
        $response->assertStatus(200);
        $response->assertViewIs('contact.index'); // Assuming this is the view name
    }

    /** @test */
    public function a_user_can_submit_the_contact_form_successfully()
    {
        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'subject' => 'Test Inquiry',
            'message' => 'This is a test message for a successful submission.',
        ];

        $response = $this->post(route('contact.submit'), $formData);

        // Assuming redirect back to contact page with a success message
        // Or, if it redirects to home, use route('home')
        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success', 'Your message has been sent successfully!'); // Verify specific success message if possible

        $this->assertDatabaseHas('contacts', [
            'email' => 'john.doe@example.com',
            'subject' => 'Test Inquiry',
            'name' => 'John Doe', // Also check name
        ]);
    }

    /** @test */
    public function contact_form_submission_fails_with_invalid_data()
    {
        $formData = [
            'name' => '', // Name is required
            'email' => 'not-an-email', // Invalid email format
            'subject' => 'Hi', // Assuming subject might have a min length (e.g., 5 characters)
            'message' => '', // Message is required
        ];

        $response = $this->post(route('contact.submit'), $formData);

        $response->assertStatus(302); // Should redirect back due to validation errors
        $response->assertSessionHasErrors(['name', 'email', 'message']);
        // $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']); // Add 'subject' if it has validation that would fail

        $this->assertDatabaseMissing('contacts', [
            'email' => 'not-an-email',
        ]);
         $this->assertDatabaseCount('contacts', 0); // Ensure no contact entry was made
    }

    /** @test */
    public function an_authenticated_user_can_submit_contact_form_and_user_id_is_recorded()
    {
        $user = User::factory()->create();
        $formData = [
            'name' => $user->name,
            'email' => $user->email,
            'subject' => 'Authenticated User Inquiry',
            'message' => 'This is a test message from an authenticated user.',
        ];

        $response = $this->actingAs($user)->post(route('contact.submit'), $formData);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success', 'Your message has been sent successfully!');

        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'email' => $user->email,
            'subject' => 'Authenticated User Inquiry',
        ]);
    }
}

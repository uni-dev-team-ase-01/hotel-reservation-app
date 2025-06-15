<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use App\Enum\ContactMessageStatusEnum; // Assuming this Enum might exist
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = ['unread', 'read', 'replied', 'archived', 'spam'];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone_number' => $this->faker->optional(0.7)->phoneNumber(), // 70% chance of having a phone number
            'subject' => $this->faker->sentence(5),
            'message' => $this->faker->paragraph(3),
            'status' => class_exists(ContactMessageStatusEnum::class)
                            ? Arr::random(array_column(ContactMessageStatusEnum::cases(), 'value'))
                            : Arr::random($statuses),
            'user_id' => $this->faker->optional(0.3)->randomElement(User::pluck('id')->toArray()) ?: (User::count() > 0 ? null : User::factory()->create()->id), // 30% chance of associating with an existing user, or create one if none exist
            'ip_address' => $this->faker->optional()->ipv4(),
            'user_agent' => $this->faker->optional()->userAgent(),
            'notes' => $this->faker->optional(0.2)->sentence(), // Internal notes about the contact message
            'replied_at' => null, // Should be set when status becomes 'replied'
            'archived_at' => null, // Should be set when status becomes 'archived'
        ];
    }

    /**
     * Indicate that the contact message is unread.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => class_exists(ContactMessageStatusEnum::class) && defined(ContactMessageStatusEnum::class . '::UNREAD')
                                ? ContactMessageStatusEnum::UNREAD->value
                                : 'unread',
            ];
        });
    }

    /**
     * Indicate that the contact message has been replied to.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function replied()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => class_exists(ContactMessageStatusEnum::class) && defined(ContactMessageStatusEnum::class . '::REPLIED')
                                ? ContactMessageStatusEnum::REPLIED->value
                                : 'replied',
                'replied_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the contact message is associated with a specific user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
                'name' => $user->name, // Pre-fill name and email if from a user
                'email' => $user->email,
            ];
        });
    }
}

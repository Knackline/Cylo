<?php

namespace Tests\Feature;

use App\Models\WaitlistEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WaitlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_waitlist_page_can_be_rendered(): void
    {
        $this->get(route('waitlist.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Waitlist')
                ->where('status', null));
    }

    public function test_people_can_join_the_waitlist(): void
    {
        $response = $this->post(route('waitlist.store'), [
            'name' => '  Ada Lovelace  ',
            'email' => 'Ada@Example.com',
        ]);

        $response->assertRedirect(route('waitlist.create'));
        $response->assertSessionHas('status', 'You are on the waitlist. We will be in touch.');

        $this->assertGuest();
        $this->assertDatabaseHas(WaitlistEntry::class, [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
        ]);

        $this->get(route('waitlist.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Waitlist')
                ->where('status', 'You are on the waitlist. We will be in touch.'));
    }

    public function test_waitlist_requires_a_name_and_email(): void
    {
        $this->post(route('waitlist.store'), [])
            ->assertInvalid([
                'name' => 'The name field is required.',
                'email' => 'The email field is required.',
            ]);

        $this->assertDatabaseCount(WaitlistEntry::class, 0);
    }

    public function test_waitlist_rejects_an_invalid_email(): void
    {
        $this->post(route('waitlist.store'), [
            'name' => 'Ada Lovelace',
            'email' => 'not-an-email',
        ])->assertInvalid([
            'email' => 'The email field must be a valid email address.',
        ]);

        $this->assertDatabaseCount(WaitlistEntry::class, 0);
    }

    public function test_waitlist_rejects_a_name_longer_than_255_characters(): void
    {
        $this->post(route('waitlist.store'), [
            'name' => str_repeat('a', 256),
            'email' => 'ada@example.com',
        ])->assertInvalid([
            'name' => 'The name field must not be greater than 255 characters.',
        ]);

        $this->assertDatabaseCount(WaitlistEntry::class, 0);
    }

    public function test_waitlist_rejects_an_email_that_is_already_listed(): void
    {
        WaitlistEntry::factory()->create([
            'email' => 'ada@example.com',
        ]);

        $this->post(route('waitlist.store'), [
            'name' => 'Ada Lovelace',
            'email' => 'Ada@Example.com',
        ])->assertInvalid([
            'email' => 'This email is already on the waitlist.',
        ]);

        $this->assertDatabaseCount(WaitlistEntry::class, 1);
    }

    public function test_waitlist_submissions_are_throttled_after_six_attempts(): void
    {
        for ($attempt = 0; $attempt < 6; $attempt++) {
            $this->post(route('waitlist.store'), [
                'name' => 'Ada Lovelace',
                'email' => "ada{$attempt}@example.com",
            ])->assertRedirect(route('waitlist.create'));
        }

        $this->post(route('waitlist.store'), [
            'name' => 'Ada Lovelace',
            'email' => 'ada-extra@example.com',
        ])->assertTooManyRequests();

        $this->assertDatabaseMissing(WaitlistEntry::class, [
            'email' => 'ada-extra@example.com',
        ]);
    }
}

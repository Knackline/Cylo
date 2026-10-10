<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->where('auth.user', null));
    }

    public function test_welcome_page_includes_hero_heading()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Welcome'));

        $hero = file_get_contents(resource_path('js/components/landing/LandingHero.vue'));

        $this->assertNotFalse($hero);
        $this->assertStringContainsString(
            'The engineering control plane for AI-assisted development',
            $hero,
        );
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard'));
    }

    public function test_dashboard_page_includes_operations_console_copy()
    {
        $dashboard = file_get_contents(resource_path('js/pages/Dashboard.vue'));

        $this->assertNotFalse($dashboard);
        $this->assertStringContainsString('Work queue', $dashboard);
        $this->assertStringContainsString('Needs review', $dashboard);
        $this->assertStringContainsString('webhook-service', $dashboard);
        $this->assertStringContainsString('organization', $dashboard);
    }
}

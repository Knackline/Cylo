<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PlatformNavigationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return list<array{0: string, 1: array<string, string>}>
     */
    public static function guestProtectedRoutes(): array
    {
        return [
            ['runs.index', []],
            ['runs.show', ['run' => 'ZD-1842']],
            ['agents.index', []],
            ['connectors.index', []],
            ['repositories.edit', []],
            ['organization.edit', []],
            ['billing.edit', []],
        ];
    }

    /**
     * @param  array<string, string>  $params
     */
    #[DataProvider('guestProtectedRoutes')]
    public function test_guests_are_redirected_to_the_login_page(string $route, array $params): void
    {
        $this->get(route($route, $params))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_platform_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('runs.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('runs/Index'));

        $this->actingAs($user)
            ->get(route('agents.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('agents/Index'));

        $this->actingAs($user)
            ->get(route('connectors.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('connectors/Index'));

        $this->actingAs($user)
            ->get(route('repositories.edit'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('repositories/Index'));

        $this->actingAs($user)
            ->get(route('organization.edit'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('organization/Index'));

        $this->actingAs($user)
            ->get(route('billing.edit'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('billing/Index'));
    }

    public function test_authenticated_users_can_visit_a_known_run_trace(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('runs.show', ['run' => 'ZD-1842']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('runs/Show')
                ->where('run', 'ZD-1842'));
    }

    public function test_authenticated_users_can_visit_an_unknown_run_trace(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('runs.show', ['run' => 'missing']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('runs/Show')
                ->where('run', 'missing'));
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_profile(): void
    {
        $this->actingAs($user = User::factory()->create());

        $this->get('/profile')->assertStatus(200);
    }
}

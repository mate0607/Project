<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.test',
        ]);

        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'user@example.test',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee($user->email);
    }

    public function test_non_admin_is_redirected_from_users_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertRedirect('/');
    }

    public function test_admin_can_soft_delete_and_restore_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'user']);

        $deleteResponse = $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $target));

        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $target->id]);

        $restoreResponse = $this->actingAs($admin)
            ->patch(route('admin.users.restore', $target->id));

        $restoreResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'deleted_at' => null,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Message;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_message_is_blocked_as_spam(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['role' => 'user']);
        $buyer = User::factory()->create(['role' => 'user']);
        $car = Car::factory()->create(['user_id' => $seller->id]);

        Sale::factory()->create([
            'car_id' => $car->id,
            'seller_id' => $seller->id,
            'buyer_id' => $admin->id,
            'is_active' => true,
        ]);

        $payload = ['message' => 'Szia, érdekel a hirdetés!'];

        $firstResponse = $this->actingAs($buyer)
            ->postJson(route('cars.messages.store', $car), $payload);

        $firstResponse->assertOk();

        $secondResponse = $this->actingAs($buyer)
            ->postJson(route('cars.messages.store', $car), $payload);

        $secondResponse->assertStatus(422);
        $secondResponse->assertJsonValidationErrors('message');
    }

    public function test_link_message_is_blocked_as_spam(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['role' => 'user']);
        $buyer = User::factory()->create(['role' => 'user']);
        $car = Car::factory()->create(['user_id' => $seller->id]);

        Sale::factory()->create([
            'car_id' => $car->id,
            'seller_id' => $seller->id,
            'buyer_id' => $admin->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($buyer)
            ->postJson(route('cars.messages.store', $car), [
                'message' => 'Nézd meg: https://spam.example',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('message');
    }

    public function test_admin_can_soft_delete_message_from_moderation_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['role' => 'user']);
        $buyer = User::factory()->create(['role' => 'user']);
        $car = Car::factory()->create(['user_id' => $seller->id]);

        $sale = Sale::factory()->create([
            'car_id' => $car->id,
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'is_active' => true,
        ]);

        $message = Message::factory()->create([
            'car_id' => $car->id,
            'sale_id' => $sale->id,
            'sender_id' => $buyer->id,
            'receiver_id' => $seller->id,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.messages.destroy', $message));

        $response->assertRedirect();
        $this->assertSoftDeleted('messages', ['id' => $message->id]);
    }

    public function test_admin_message_index_hides_unrelated_fake_conversations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['role' => 'user']);
        $buyer = User::factory()->create(['role' => 'user']);
        $realCar = Car::factory()->create(['user_id' => $seller->id]);

        Sale::factory()->create([
            'car_id' => $realCar->id,
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'is_active' => true,
        ]);

        Message::factory()->create([
            'car_id' => $realCar->id,
            'sale_id' => null,
            'sender_id' => $buyer->id,
            'receiver_id' => $seller->id,
            'message' => 'Valódi érdeklődés',
        ]);

        $fakeSender = User::factory()->create(['role' => 'user']);
        $fakeReceiver = User::factory()->create(['role' => 'user']);
        $fakeCar = Car::factory()->create(['user_id' => $fakeSender->id, 'make_model' => 'Fake Car']);

        Message::factory()->create([
            'car_id' => $fakeCar->id,
            'sale_id' => null,
            'sender_id' => $fakeSender->id,
            'receiver_id' => $fakeReceiver->id,
            'message' => 'Kamu üzenet',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.messages.index'));

        $response->assertOk();
        $response->assertSee($realCar->make_model);
        $response->assertDontSee('Fake Car');
    }

    public function test_admin_cannot_open_unrelated_fake_conversation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $fakeSender = User::factory()->create(['role' => 'user']);
        $fakeReceiver = User::factory()->create(['role' => 'user']);
        $fakeCar = Car::factory()->create(['user_id' => $fakeSender->id]);

        Message::factory()->create([
            'car_id' => $fakeCar->id,
            'sale_id' => null,
            'sender_id' => $fakeSender->id,
            'receiver_id' => $fakeReceiver->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.messages.conversation', $fakeCar));

        $response->assertNotFound();
    }
}

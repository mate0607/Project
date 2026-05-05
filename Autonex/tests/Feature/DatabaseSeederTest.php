<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_database_seed_does_not_create_dummy_messages(): void
    {
        Artisan::call('db:seed');

        $this->assertDatabaseCount('messages', 0);
    }
}

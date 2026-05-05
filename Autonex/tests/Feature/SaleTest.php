<?php

namespace Tests\Feature;

use App\Models\SaleImage;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
	use RefreshDatabase;

	public function test_sales_index_displays_pagination_result_summary(): void
	{
		$user = User::factory()->create(['role' => 'user']);
		Sale::factory()->count(12)->create();

		$response = $this->actingAs($user)->get(route('sales.index'));

		$response->assertOk();
		$response->assertSee('Megjelenítve 1-10 / 12 találat.', false);
	}

	public function test_sales_index_falls_back_when_image_file_is_missing(): void
	{
		$user = User::factory()->create(['role' => 'user']);
		$sale = Sale::factory()->create();

		SaleImage::create([
			'sale_id' => $sale->id,
			'path' => 'sales/does-not-exist.avif',
			'sort_order' => 0,
		]);

		$response = $this->actingAs($user)->get(route('sales.index'));

		$response->assertOk();
		$response->assertSee('Nincs kép');
		$response->assertDontSee('storage/sales/does-not-exist.avif');
	}
}


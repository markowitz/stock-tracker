<?php

namespace Tests\Feature;

use App\Product;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $switch = Product::create(['name' => 'Nitendo Switch']);

        $bestBuy = Retailer::create(['name' => 'Best Buy']);


        $this->assertFalse($switch->inStock());

        $stock = new Stock([
            'price' => 10000,
            'url' => 'http://foo.com',
            'sku' => '12345',
            'in_stock' => false
        ]);

        $bestBuy->addStock($switch, $stock);

        $this->assertFalse($stock->fresh()->in_stock);

        Http::fake(function() {
            return [
                'available' => true,
                'price' => 29000
            ];
        });

        $this->artisan('track');

        $this->assertTrue($stock->fresh()->in_stock);
    }
}

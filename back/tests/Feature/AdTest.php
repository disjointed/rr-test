<?php

namespace Tests\Feature;

use App\Ad;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @coversDefaultClass App\Http\Controllers\AdController
 */
class AdTest extends TestCase
{
    use RefreshDatabase;

    protected $ads = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->ads = factory(Ad::class, 25)->create();
    }

    /**
     * @test
     * @covers ::index
     */
    public function index(): void
    {
        $response = $this->getJson('/ads');

        $response->assertStatus(200);
        $response->assertJson(['data' => [], 'links' => [], 'meta' => []]);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreItem extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = [
            'item' => [
                'name' => 'Unit test item'
            ]
        ];
        $response = $this->post('api/item/store', $data);

        $response->assertStatus(201);
    }
}

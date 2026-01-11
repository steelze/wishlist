<?php

use App\Models\Product;

it('returns all products', function () {
    Product::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/products');

    $response->assertSuccessful()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => ['id', 'name', 'price', 'description', 'created_at', 'updated_at'],
            ],
        ]);
});

it('returns empty array when no products exist', function () {
    $response = $this->getJson('/api/v1/products');

    $response->assertSuccessful()
        ->assertJsonCount(0, 'data')
        ->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
});


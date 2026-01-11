<?php

use App\Models\Product;
use App\Models\User;

describe('store', function () {
    it('adds a product to the wishlist', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/v1/wishlist/{$product->id}");
        $response->assertCreated()->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseHas('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);
    });

    it('returns conflict when product already in wishlist', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $user->wishlists()->attach($product->id);

        $response = $this->actingAs($user)->postJson("/api/v1/wishlist/{$product->id}");
        $response->assertConflict()->assertJsonStructure(['status', 'message', 'errors']);
    });

    it('requires authentication', function () {
        $product = Product::factory()->create();

        $response = $this->postJson("/api/v1/wishlist/{$product->id}");
        $response->assertUnauthorized();
    });
});

describe('index', function () {
    it('returns user wishlist', function () {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();
        $user->wishlists()->attach($products->pluck('id'));

        $response = $this->actingAs($user)->getJson('/api/v1/wishlist');
        $response->assertSuccessful()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['status', 'message', 'data']);
    });

    it('returns empty array when wishlist is empty', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/wishlist');
        $response->assertSuccessful()
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure(['status', 'message', 'data']);
    });

    it('only returns authenticated user wishlist', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $otherUser->wishlists()->attach($product->id);

        $response = $this->actingAs($user)->getJson('/api/v1/wishlist');
        $response->assertSuccessful()->assertJsonCount(0, 'data')->assertJsonStructure(['status', 'message', 'data']);
    });

    it('requires authentication', function () {
        $response = $this->getJson('/api/v1/wishlist');
        $response->assertUnauthorized();
    });
});

describe('destroy', function () {
    it('removes a product from the wishlist', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $user->wishlists()->attach($product->id);

        $response = $this->actingAs($user)->deleteJson("/api/v1/wishlist/{$product->id}");
        $response->assertSuccessful()->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseMissing('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);
    });

    it('requires authentication', function () {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/wishlist/{$product->id}");
        $response->assertUnauthorized();
    });
});

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Bespoke Steel Chicken',
            'Luxurious Concrete Computer',
            'Sleek Granite Salad',
            'Modern Fresh Chair',
            'Rustic Steel Mouse',
            'Gorgeous Steel Computer',
            'Modern Metal Chair',
            'Rustic Cotton Table',
            'Generic Wooden Ball',
            'Fantastic Metal Shoes',
            'Awesome Fresh Chair',
            'Oriental Granite Chips',
            'Incredible Plastic Shirt',
            'Bespoke Concrete Gloves',
            'Handcrafted Rubber Table',
            'Incredible Bronze Table',
            'Bespoke Soft Cheese',
            'Licensed Concrete Ball',
            'Gorgeous Fresh Shirt',
            'Handmade Frozen Chair',
            'Tasty Bronze Chicken',
            'Elegant Fresh Bacon',
            'Luxurious Plastic Shirt',
            'Modern Bronze Shirt',
            'Generic Soft Towels',
            'Handmade Plastic Chair',
            'Gorgeous Concrete Shirt',
            'Bespoke Cotton Fish',
            'Incredible Rubber Gloves',
            'Intelligent Cotton Tuna',
            'Luxurious Rubber Soap',
            'Luxurious Concrete Bacon',
            'Incredible Plastic Table',
            'Ergonomic Wooden Cheese',
            'Intelligent Steel Hat',
            'Luxurious Bronze Chicken',
            'Recycled Cotton Pants',
            'Fantastic Metal Hat',
            'Handmade Cotton Chicken',
            'Licensed Metal Gloves',
            'Fantastic Fresh Chicken',
            'Recycled Plastic Hat',
            'Bespoke Soft Gloves',
            'Sleek Cotton Keyboard',
            'Ergonomic Concrete Bike',
            'Licensed Frozen Gloves',
            'Gorgeous Steel Chicken',
            'Recycled Rubber Chair',
            'Handmade Granite Shoes',
            'Unbranded Rubber Mouse',
            'Ergonomic Frozen Car',
            'Incredible Wooden Mouse',
            'Rustic Frozen Computer',
            'Handcrafted Bronze Cheese',
            'Bespoke Bronze Mouse',
            'Electronic Frozen Tuna',
            'Handcrafted Concrete Hat',
            'Fantastic Metal Hat',
            'Incredible Cotton Sausages',
            'Licensed Fresh Towels',
            'Handmade Fresh Shoes',
            'Licensed Fresh Pants',
            'Handmade Soft Tuna',
            'Refined Fresh Cheese',
            'Generic Concrete Pants',
            'Refined Granite Towels',
            'Intelligent Soft Mouse',
            'Gorgeous Metal Mouse',
            'Oriental Frozen Chips',
            'Oriental Fresh Mouse',
            'Bespoke Cotton Computer',
            'Sleek Cotton Mouse',
            'Unbranded Concrete Cheese',
            'Incredible Cotton Salad',
            'Modern Granite Bike',
            'Bespoke Wooden Pants',
            'Ergonomic Rubber Salad',
            'Electronic Rubber Chair',
            'Rustic Frozen Shirt',
            'Incredible Steel Ball',
            'Gorgeous Concrete Fish',
            'Incredible Rubber Salad',
            'Recycled Fresh Ball',
            'Rustic Rubber Chips',
            'Unbranded Plastic Table',
            'Bespoke Steel Shoes',
            'Ergonomic Bronze Car',
            'Luxurious Frozen Bike',
            'Sleek Soft Pizza',
            'Tasty Wooden Chips',
            'Incredible Bronze Chicken',
            'Recycled Fresh Chicken',
            'Fantastic Plastic Car',
            'Handcrafted Fresh Fish',
            'Incredible Soft Fish',
            'Luxurious Bronze Car',
            'Handcrafted Concrete Pizza',
            'Bespoke Metal Sausages',
            'Fantastic Bronze Fish',
            'Practical Bronze Hat'
        ];

        return [
            'name' => fake()->randomElement($products),
            'price' => fake()->randomFloat(2, 1, 1000),
            'description' => fake()->realText(),
        ];
    }
}

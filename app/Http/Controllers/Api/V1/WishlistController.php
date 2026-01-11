<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\RespondWith;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();

        if ($user->wishlists()->where('product_id', $product->id)->exists()) {
            return RespondWith::error(message: 'Product already in wishlist', code: Response::HTTP_CONFLICT);
        }

        $user->wishlists()->attach($product->id);

        return RespondWith::success(message: 'Product added to wishlist', code: Response::HTTP_CREATED);
    }

    public function index(Request $request): JsonResponse
    {
        $products = $request->user()->wishlists()->latest()->get();

        return RespondWith::success($products);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $request->user()->wishlists()->detach($product->id);

        return RespondWith::success(message: 'Product removed from wishlist');
    }
}

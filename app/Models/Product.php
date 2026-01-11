<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $guarded = [];

    public function wishlists(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }
}

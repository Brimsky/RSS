<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "price",
        "user_id",
        "category",
        "subcategory",
        "condition",
        "location",
        "photos",
    ];

    protected $casts = [
        "photos" => "array",
        // 'photos' => 'json',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementClicks()
    {
        $this->increment("clicks");
    }
    public function savedBy()
    {
        return $this->belongsToMany(
            User::class,
            "saved_products"
        )->withTimestamps();
    }
}

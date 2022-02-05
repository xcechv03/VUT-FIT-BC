<?php
// Item.php
//
// autor: Jan Procházka (xproch0g)
//

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $with = ['category'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn ($query, $search) =>
            $query
                ->where('product', 'like', '%' . $search . '%')
                ->orWhere('company', 'like', '%' . $search . '%'));

        $query->when($filters['category'] ?? false, fn ($query, $category) =>
        $query
            ->where('category_id', $category));

        $query->when($filters['price'] ?? false, fn ($query, $price) =>
        $query
            ->where('price', '>=', $price));

        $query->when($filters['weight'] ?? false, fn ($query, $weight) =>
        $query
            ->where('weight_kg', '>=', $weight));
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    public function history() {
        return $this->hasMany(History::class);
    }
}

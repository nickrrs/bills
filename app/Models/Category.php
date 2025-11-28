<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'icon',
        'color',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function limits()
    {
        return $this->hasMany(Limit::class);
    }
}

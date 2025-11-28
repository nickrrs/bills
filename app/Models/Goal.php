<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'amount',
        'final_amount',
        'due_at',
        'description',
        'status',
        'icon',
        'color',
        'auto_mode',
        'start_contribution_at',
        'next_auto_contribution_at',
    ];

    protected $casts = [
        'auto_mode' => 'boolean',
        'start_contribution_at' => 'datetime',
        'next_auto_contribution_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}

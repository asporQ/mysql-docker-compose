<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personality extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'personalities';
    protected $fillable = ['type', 'description'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    
    public function user()
    {
        return $this->hasMany(User::class);
    }
}

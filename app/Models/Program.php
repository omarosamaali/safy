<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'price',
        'tier_id',
    ];
    protected $casts = [
        'created_at'=>'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d',
    ];


    
    public function tier()
    {
        return $this->belongsTo(Tier::class);
    }
}

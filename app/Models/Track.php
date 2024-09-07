<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_level',
        'training_type',
        'program_id',
        'track_type',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }


    public function files()
    {
        return $this->belongsToMany(File::class ,'track_file');
    }

    
}

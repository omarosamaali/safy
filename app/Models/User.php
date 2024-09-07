<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Track;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lname',
        'email',
        'password',
        'phone',
        'image',
        'inbody',
        'age',
        'height',
        'weight',
        'is_Nutritional_supplements',
        'health_status',
        'track_id',
        'body_image_front', // Add the new field for body_image_front
        'body_image_back',  // Add the new field for body_image_back
        'Payment_photo',  // Add the new field for body_image_back
        'inbody_photo',  // Add the new field for body_image_back
        'period',

    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



   
    public function track()
    {
        return $this->belongsTo(Track::class);
    }
    
}
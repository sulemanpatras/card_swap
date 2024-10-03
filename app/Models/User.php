<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'job_title',
        'company_name',
        'phone',
        'card_title',
        'color_theme',
        'otp',
        'image', 
        'status',
    ];

    public function contacts()
    {
        return $this->hasMany(CardDetails::class);
    }

    public function cards()
{
    return $this->hasMany(Card::class);
}

    

    // If you want to specify which attributes should be hidden (e.g., for security)
    // protected $hidden = [
    //     'otp',
    // ];

    // If you want to specify the attributes that should be cast to a specific type
    // protected $casts = [
    //     'otp' => 'string',
    // ];

    

    
}

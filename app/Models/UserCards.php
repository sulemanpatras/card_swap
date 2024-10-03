<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCards extends Model
{
    use HasFactory;

    protected $table = 'register_users_cards'; 

    protected $fillable = [
        'user_id',
        'pronoun',
        'preferred_name',
        'image',
        'cover_photo',
        'qr_code_url',
        'status',
        'created_at',
        'updated_at',
    ];

}

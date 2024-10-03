<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens; // If using Sanctum
// use Laravel\Passport\HasApiTokens; // If using Passport
use Spatie\Permission\Traits\HasRoles; // Include this


class Admin extends Model
{
    use HasFactory, HasApiTokens, HasRoles; // Ensure this trait is included

    protected $table = 'admin';

    protected $fillable = [
        'username',
        'name',
        'email',
        'user_type',
        'role',
        'password',
        'user_image', 
        'verification_code',
        'email_verified_at',
        'otp_expires_at',
        'remember_token',
    ];

    protected $guard_name = 'web';

}

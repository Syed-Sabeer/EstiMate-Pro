<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'business_name',
        'phone_number',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'bio',
        'profile_picture',
        'date_of_birth',
        'date_of_joining',
        'gender',
        'marital_status',
        'instagram_profile',
        'facebook_profile'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

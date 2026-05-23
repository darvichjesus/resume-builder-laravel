<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'title', 'email', 'phone', 'bio', 'linkedin_url', 'github_url', 'photo', 'show_qr'
    ];
}

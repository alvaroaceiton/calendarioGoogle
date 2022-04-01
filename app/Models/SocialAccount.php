<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SocialAccount extends Model
{
    protected $fillable = [
        'provider_name',
        'provider_id',
        'user_id'
    ];

    /* Get the user that owns the SocialAccount
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

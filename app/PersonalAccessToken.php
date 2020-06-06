<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class PersonalAccessToken extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'tokenable_id');
    }
}

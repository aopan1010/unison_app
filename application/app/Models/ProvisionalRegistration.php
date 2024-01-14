<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Consts\RegisterStatusConsts;

class ProvisionalRegistration extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'status',
    ];
}

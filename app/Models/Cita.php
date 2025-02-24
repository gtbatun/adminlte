<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    protected $table = 'citas';

    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'user_id',
        'ticket_id'
    ];
}

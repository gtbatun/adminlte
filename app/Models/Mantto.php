<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantto extends Model
{    
    use HasFactory;
    protected $table = 'mantto';
    protected $fillable = ['device_id','user_id','coment','usermantto_id','statusdevice_id'];

}

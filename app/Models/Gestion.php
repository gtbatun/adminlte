<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;
    protected $table = 'gestion';
    protected $fillable = ['ticket_id','coment','image','user_id','staf_id','status_id'];

    public function usuario(){
        // se lee, un ticket pertenece a una categoria
        return $this->belongsTo(User::class,'user_id');
    }

    public function getImageAttyyyribute(){
        return explode(',',$this->attributes['image']);
    }

}

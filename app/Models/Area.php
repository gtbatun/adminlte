<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = 'area';
    protected $fillable =[
        'name',
        'description',
        'logo'];
    
        public function ticket(){
            return $this->hasMany(Ticket::class,'area_id');
        }
        // seccion agregada para el area en la section add ticket para mostrar y filtar categorias
        public function category(){
            return $this->hasMany(Category::class,'area_id');
        }
}

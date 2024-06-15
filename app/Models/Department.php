<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Notifications\Notifiable;

class Department extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'department';
    protected $fillable =[
        'name',
        'description',
        'sucursal_id',
        'sucursal_ids',
        'enableforticket',
        'logo'];

        public function ticket(){
            return $this->hasMany(Ticket::class,'department_id');
        }
// seccion recien agreagada paara qyue sea posible los 3 niveles en el select option
        public function areas()
        {
            return $this->hasMany(Area::class,'department_id');
        }   
        
        public function sucursal(){
            return  $this->belongsTo(Sucursal::class);
        }    
        public function users()
        {
            return $this->hasMany(User::class);
        } 
}

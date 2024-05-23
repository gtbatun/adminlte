<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    protected $table = 'sucursal';
    protected $fillable =[
                            'name',
                            'description',
                            'logo'];
    
    public function UTicket(){
        // se lee, muchos usuarios pertenece a una sucursal
        return $this->hasMany(User::class);
    }

    

}

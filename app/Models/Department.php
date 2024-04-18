<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'department';
    protected $fillable =[
        'name',
        'description',
        'logo'];

        public function ticket(){
            return $this->hasMany(Ticket::class,'department_id');
        }

        
}

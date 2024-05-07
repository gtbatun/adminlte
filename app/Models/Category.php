<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $fillable =[
        'name',
        'description',
        'logo'];

        public function ticket(){
            return $this->hasMany(Ticket::class,'category_id');
        }

        public function area(){
            return $this->belongsTo(Area::class,'area_id');
        }
}

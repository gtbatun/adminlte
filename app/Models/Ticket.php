<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'ticket';
    protected $fillable = ['title','description','image','area_id','user_id','category_id','department_id','status_id'];

    public function area(){
        return  $this->belongsTo(Area::class,'area_id');
    }
    public function category(){
        return  $this->belongsTo(Category::class,'category_id');
    }
    public function department(){
        return  $this->belongsTo(Department::class,'department_id');
    }
    public function status(){
        return  $this->belongsTo(Status::class,'status_id');
    }
}

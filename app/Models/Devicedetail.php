<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devicedetail extends Model
{
    use HasFactory;
    protected $table = 'devicedetail';
    protected $fillable =[
        'name',
        'type_device',
        'coment'];

        public function devices()
        {
            return $this->hasMany(Device::class, 'tipo_equipo_id');
        }
}

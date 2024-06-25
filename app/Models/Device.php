<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = 'device';
    protected $fillable =[
        'name',
        'tipo_equipo_id',
        'marca_id',
        'serie',
        'almacenamiento_id',
        'procesador_id',
        'description',
        'sucursal_id',
        'department_id',
        'statusdevice_id'];
    
    public function marca()
    {
        return $this->belongsTo(Devicedetail::class,'marca_id');
    }
    public function tipodevice()
    {
        return $this->belongsTo(Devicedetail::class,'tipo_equipo_id');
    }
    public function statusdevice()
    {
        return $this->belongsTo(Devicedetail::class,'statusdevice_id');
    }
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class,'sucursal_id');
    }
    public function departamento()
    {
        return $this->belongsTo(Department::class,'department_id');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    /**Codigo para la seccion de asignacion de equipso de computo */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable 
// implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'department_id',
        'extension',
        'ver_ticket',
        'image',
        'sucursal_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->is_admin === 10 ; // Suponiendo que tengas un campo 'role' que indica el rol del usuario
    }
    public function adminlte_profile_url(){
        // return route('admin.profile.show', $this->id);
        return route('user.edit', $this->id);
    }
    public function adminlte_image(){
        return asset('storage/images/user/' . $this->image);
    }
    
    public function department(){
        return  $this->belongsTo(Department::class,'department_id');
    }
    public function sucursal(){
        // se lee, un usuario pertenece a una sucursal
        return $this->belongsTo(Sucursal::class,'sucursal_id');
    }
    /**codigo pasra la seccion de asignacion de equipos de computo */
    public function devices()
    {
        return $this->belongsToMany(Device::class);
    }
}

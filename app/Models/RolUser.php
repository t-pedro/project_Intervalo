<?php

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RolUser extends Model
{
    protected $table = 'roles_users';


    public function users()
    {
        return $this->belongsToMany(User::class, 'roles_user', 'rol_id', 'user_id');
    }

    public function roles()
{
    return $this->belongsToMany(Rol::class, 'roles_user', 'user_id', 'rol_id');
}

}    


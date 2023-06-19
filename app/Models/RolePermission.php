<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Foundation\Auth\User as Authenticatable;


class RolePermission extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'role_permission';

    protected $fillable = [
        'role_id', 'permission_id', 'userid'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

}

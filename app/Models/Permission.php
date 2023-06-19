<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Foundation\Auth\User as Authenticatable;


class Permission extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'permission';

    protected $fillable = [
        'name', 'description', 'role_id'
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

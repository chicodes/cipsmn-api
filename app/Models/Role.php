<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Foundation\Auth\User as Authenticatable;


class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'role';

    protected $fillable = [
        'name', 'description'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

    public static function getTotalBadge(): int
    {
        return Role::all()->count();
    }

}

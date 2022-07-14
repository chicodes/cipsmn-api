<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Auth\Authorizable;

use Tymon\JWTAuth\Contracts\JWTSubject;

//use Illuminate\Foundation\Auth\User as Authenticatable;


class Payment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'payments';

    protected $fillable = [
        'type', 'amount', 'status','useird'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

    public static function getTotalPayment(): int
    {
        //return Payment::all()->count();
        return Payment::sum('amount');
    }

    public static function getRecentPayment(){
        return Payment::select("*")
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public static function getUserPayment(){
        $id = Auth::user()->id;
        return Payment::where('userid', $id)->get();
    }
}

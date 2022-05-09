<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
//use Auth;
use Illuminate\Support\Facades\Auth;

//use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','paid_for_regular','paid_for_exemption'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public $table = 'users';
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public static function getTotalUsers(): int
    {
        return User::all()->count();
    }

    public static function getRecentUsers(){
        return User::select("*")
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getUserRegularPaymentStatus(){
        $id = Auth::user()->id;
        $regularPaymentStatus =  User::where('id', $id)->first();
        return $regularPaymentStatus->paid_for_regular;
    }

    public function getPaidForExemption(){
        $id = Auth::user()->id;
        $checkPaidForExemption =  User::where('id', $id)->first();
        return $checkPaidForExemption->paid_for_exemption;
    }

//    public function image()
//    {
//        return $this->hasOne('App\Models\Image','image_id');
//    }
}

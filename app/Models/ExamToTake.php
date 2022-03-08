<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

use Tymon\JWTAuth\Contracts\JWTSubject;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class ExamToTake extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'exam_to_take';

    protected $fillable = [
        'name', 'description', 'image_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

    public static function getConvertUserDashboard(){
        $id = Auth::user()->id;
        $getAllExamToTake = ExamToTake::select()
            ->leftJoin("exam", "exam.id", "=", "exam_to_take.exam_id")
            ->where("exam_to_take.userid", "=", $id)
            ->get();

        $getExam = [];
        foreach($getAllExamToTake as $examToTake){
            $getExam[] = [
                'id' => $examToTake->id,
                'name' => $examToTake->name
            ];
        }
        return $getExam;
    }

    public static function getRegularUserDashboard(){
        $getAllExamToTake = ExamToTake::select()
            ->leftJoin("exam", "exam.id", "=", "exam_to_take.exam_id")
            ->get();

        $getExam = [];
        foreach($getAllExamToTake as $examToTake){
            $getExam[] = [
                'id' => $examToTake->id,
                'name' => $examToTake->name
            ];
        }
        return $getExam;
    }
}

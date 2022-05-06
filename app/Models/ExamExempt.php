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

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ExamExempt extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'exam_exempt';

    protected $fillable = [
        'userid', 'exam_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

    public static function get($id){
        $getAllExamExempted =  ExamExempt::where('userid', $id)->get();

//                                ->pluck('exam_id');
        //dd($getAllExamExempted);
        return $getAllExamExempted;

//        $getAllExamExempted = (array) $getAllExamExempted;
//
//        $getAllExamExempted = array_values($getAllExamExempted);
//
//        $getExams = Exam::all()->pluck('id');
//
//        $getExams = (array) $getExams;
//
//        $getExams =  array_values($getExams);
//
//        return array_diff($getExams[0], $getAllExamExempted[0]);
    }

    public function exam()
    {
        return $this->belongsTo('App\Models\Exam');
    }
}

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

class Dashboard extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

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
            $getAllExamToTake = ExamExempt::select()
            ->leftJoin("exam", "exam.id", "!=", "exam_exempt.exam_id")
            ->where("exam_exempt.userid", "=", $id)
            ->get();

        $getExam = [];
        //$getAllPayment = Payment::all();
        $getAllPayment = Payment::getUserPayment();
        foreach($getAllExamToTake as $examToTake){
            $getExam[] = [
                'id' => $examToTake->id,
                'name' =>$examToTake->name,
                'description' => $examToTake->description,
                'image_id' => $examToTake->image_id,
                'created_at' => $examToTake->created_at,
                'updated_at' => $examToTake->updated_at,
                'amount' => $examToTake->amount
            ];
        }
        $convertDashboard = [
            'name' => Auth::user()->firstname." ".Auth::user()->lastname,
            'exam' => $getExam,
            'payment' => $getAllPayment
        ];
        return $convertDashboard;
    }

    public static function getRegularUserDashboard(){

        //this is a regular user so show all exams
        $getAllExamToTake = Exam::all();

        //$getAllPayment = Payment::all();

        $getAllPayment = Payment::getUserPayment();

        $getExam = $getAllExamToTake;

        $regularPayment= Auth::user()->paid_for_regular;
        // if $regularPayment ==0 that means regular user has not made regular payment so dont show user the dashboard,
        //but if 1 that means user has made payment user can now proceed to dashboard
        $regularDashboard = [
                                'name' => Auth::user()->firstname." ".Auth::user()->lastname,
                                'exam' => $getExam,
                                'paid_for_regular' => $regularPayment,
                                'payment' => $getAllPayment,
                            ];
        return $regularDashboard;
    }
}

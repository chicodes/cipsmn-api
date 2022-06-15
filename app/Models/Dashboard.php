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

        $getAllPayment = Payment::getUserPayment();

        $userid = Auth::user()->id;

        $registrationPayment = Auth::user()->paid_for_registration;



//                $id = Auth::user()->id;
//            $getAllExamToTake = Exam::select()
//                //->leftJoin("exam", "exam.id", "!=", "exam_exempt.exam_id")
//            ->rightJoin("exam_exempt","exam_exempt.exam_id", "=", "exam.id")
//                ->whereIn()
//            ->where("exam_exempt.userid", "=", $id)
//                ->get();



        //get exams exempted and convert to array
        $getExamExempt = ExamExempt::where('userid', $userid)
                        ->get()
                        ->pluck('exam_id')
                        ->toArray();

        //get exam-exempt thats not in exams table
        $getAllExam = Exam::whereNotIn('id', $getExamExempt)->pluck('id')
                        ->toArray();

        //get exams that has not been taken by user
        $getAllExamToTake = Exam::whereIn("id", $getAllExam)
            ->get();


        //var_export($getAllExamToTake->toArray()); exit;

        //Todo you need to know wether or not the user has paid for an exam so you can grey it out
        //step1 loop through payments table using exam name to check if userid and exam name exist if it does that means
        //the user has not paid for that exam

//        foreach($getAllExamToTake as $paymentName){
//            //var_export($exam->name);exit;
//            $getAllExam[] = Payment::where('type', $paymentName->name)
//                ->where('userid', $userid)
//                //->get();
//                ->get()
//                ->pluck('type')
//            ->toArray();
//        }
//
//        //$getValues = array_values($getAllExam);
//
//        $merge = array_add($getAllExam, 'paid', $getAllExamToTake);
//        var_export($merge); exit;

        //Todo now loop through $getAllExamToTake checking if exam name equals type in $getAllExam

        $convertDashboard = [
            'name' => Auth::user()->firstname." ".Auth::user()->lastname,
            'exam' => $getAllExamToTake,
            'paid_for_registration' => $registrationPayment,
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

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


class PaymentSettings extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'payment_settings';

    protected $fillable = [
        'name','description','amount','status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

    public static function getRegularAmount(){

        return PaymentSettings::where('name', 'regular')->first();
    }

    public static function getUserExemptionAmount(){

        //get all the exams exempted from and the subjects under each exam and multiply by 5000
        $id = Auth::user()->id; //dd($id);
        $getExempted = ExamExempt::where('userid', $id)->get(); //dd($getExempted);
        if(!$getExempted->count()){
            echo 'user has not been exempted';
        }

        //get exemption amount
        $getExemptionAmount = PaymentSettings::where('name', 'exemption')->first();

        //get subjects under exam
        $numberOfSubjectsUnderExam[] = [];
        $i = 0;
        foreach ($getExempted as $getSubjects){
            $getSubject = Subject::find($getSubjects->exam_id);
            $numberOfSubjectsUnderExam = ++$i;
        }

        //multiply exemption amount by number of subjects in exam
        return $getExemptionAmount->amount * $numberOfSubjectsUnderExam;
    }

}

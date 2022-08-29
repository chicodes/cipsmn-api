<?php

namespace App\Http\Controllers;
use App\Models\ExamExempt;
use App\Models\ExamPaymentExempt;
use App\Models\Image;
use App\Utility\Helper;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Exam;



class ExamExemptController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function create(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'userid' => 'required|string',
            'exam_id' => 'required|array'
        ]);

        try {
            $userid = $request->input('userid');
            $examIds = $request->input('exam_id');

            for($i = 0; $i < count($examIds); $i++){
                $examExempt = new ExamExempt;
                $examExempt->userid = $userid;
                $examExempt->exam_id = $examIds[$i];
                $examExempt->save();
                $result[] = $examExempt;
            }

            return response()->json(['Exam Exempt' => $result, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong: ' . $e], 409);
        }
    }

    public function get($id){

        $getExempt = ExamExempt::get($id);
        $allExempt = [];
        foreach ($getExempt as $getExamExempt){
            $allExempt = $getExamExempt->exam;
        }
        //dd($getExempt);
//        if ($getExempt->isEmpty()) {
        if (!$getExempt) {
            return response()->json(['message' => 'No exemption'], 404);
        }
        return response()->json(['ExamExempt' => $getExempt, 'message' => 'CREATED'], 201);
    }

    public function exemptFromExemptionPay(Request $request){
        //validate incoming request
        $this->validate($request, [
            'userid' => 'required|string',
            'exam_id' => 'required|array'
        ]);

        try {
            $userid = $request->input('userid');
            $examIds = $request->input('exam_id');

            for($i = 0; $i < count($examIds); $i++){
                $examPaymentExempt = new ExamPaymentExempt;
                $examPaymentExempt->userid = $userid;
                $examPaymentExempt->exam_id = $examIds[$i];
                $examPaymentExempt->save();
                $result[] = $examPaymentExempt;
            }
            return response()->json(['Exam Payment Exemption' => $result, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong: ' . $e], 409);
        }
    }

    public function getExamPaymentExempt($id){

        $getUserExamExempt = ExamPaymentExempt::getExamExempt($id);
        $allExempt = [];
        foreach ($getUserExamExempt as $getExamExempt){
            $allExempt[] = $getExamExempt;
        }
        if (!$getUserExamExempt) {
            return response()->json(['message' => 'No exemption'], 404);
        }
        return response()->json(['ExamExempt' => $allExempt, 'message' => 'successful'], 201);
    }
}


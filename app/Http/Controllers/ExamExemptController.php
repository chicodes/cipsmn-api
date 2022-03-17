<?php

namespace App\Http\Controllers;
use App\Models\ExamExempt;
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
            'exam_id' => 'required|string'
        ]);

        try {
            $id = Auth::user()->id;
            $examExempt = new ExamExempt;
            $examExempt->userid = $id;
            $examExempt->exam_id = $request->input('exam_id');
            $examExempt->save();

            return response()->json(['Exam Exempt' => $examExempt, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong: ' . $e], 409);
        }
    }

    public function get(){
            return ExamExempt::get();
    }
}


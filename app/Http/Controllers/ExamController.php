<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Exam;



class ExamController extends Controller
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
            'name' => 'required|string',
            'description' => 'required|string',
            'image_id' => 'required|string',
        ]);

        try {

            $user = new Exam;
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->image_id = $request->input('image_id');
            $user->save();

            //return successful response
            return response()->json(['Exam' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $exam = $this->checkExamExist($id);
            if(!$exam){
                return response()->json(['Exam' => $exam, 'message' => 'Id does not exist'], 200);
            }
            $exam->name = $request->input('name');
            $exam->description = $request->input('description');
            $exam->image_id = $request->input('image_id');
            $exam->save();
            return response()->json(['Exam' => $exam, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id){
        $exam = $this->checkExamExist($id);
        if(!$exam){
            return response()->json(['Exam' => $exam, 'message' => 'Id does not exist'], 200);
        }
        $exam->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAllRecords()
    {
        return Exam::all();
    }
    public function checkExamExist($id){
        return Exam::find($id);
    }
}

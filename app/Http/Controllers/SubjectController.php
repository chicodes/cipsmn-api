<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Subject;



class SubjectController extends Controller
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
            'exam_id' => 'required|string',
            'description' => 'required|string'
        ]);

        try {

            $subject = new Subject;
            $subject->name = $request->input('name');
            $subject->exam_id = $request->input('exam_id');
            $subject->description = $request->input('description');
            $subject->save();

            //return successful response
            return response()->json(['Subject' => $subject, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $subject = $this->checkSubjectExist($id);
            if (!$subject) {
                return response()->json(['Subject' => $subject, 'message' => 'Id does not exist'], 200);
            }
            $subject->name = $request->input('name');
            $subject->exam_id = $request->input('exam_id');
            $subject->description = $request->input('description');
            $subject->save();
            return response()->json(['Subject' => $subject, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        $subject = $this->checkSubjectExist($id);
        if (!$subject) {
            return response()->json(['Subject' => $subject, 'message' => 'Id does not exist'], 200);
        }
        $subject->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAllRecords()
    {
        return Subject::paginate(20);
    }

    public function checkSubjectExist($id)
    {
        return Subject::find($id);
    }
}

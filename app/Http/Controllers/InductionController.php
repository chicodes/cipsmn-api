<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\induction;



class InductionController extends Controller
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
            'amount' => 'required|string'
        ]);

        try {

            $induction = new induction;
            $induction->amount = $request->input('amount');
            $induction->save();

            //return successful response
            return response()->json(['induction' => $induction, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $induction = $this->checkinductionExist($id);
            if (!$induction) {
                return response()->json(['induction' => $induction, 'message' => 'Id does not exist'], 200);
            }
            $induction->amount = $request->input('amount');
            $induction->save();
            return response()->json(['induction' => $induction, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        $induction = $this->checkinductionExist($id);
        if (!$induction) {
            return response()->json(['$induction' => $induction, 'message' => 'Id does not exist'], 200);
        }
        $induction->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAll()
    {
        return induction::paginate(20);
    }

    public function get($id)
    {

        $induction = $this->checkinductionExist($id);
        if (!$induction) {
            return response()->json(['induction' => $induction, 'message' => 'Id does not exist'], 200);
        }
        return response()->json(['induction' => $induction, 'message' => 'successfull'], 200);
    }

    public function checkinductionExist($id)
    {
        return induction::find($id);
    }
}

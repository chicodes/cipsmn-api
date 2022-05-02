<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Honorary;



class HonoraryController extends Controller
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

            $honorary = new Honorary;
            $honorary->amount = $request->input('amount');
            $honorary->save();

            //return successful response
            return response()->json(['Honorary' => $honorary, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $honorary = $this->checkHonoraryExist($id);
            if (!$honorary) {
                return response()->json(['Honorary' => $honorary, 'message' => 'Id does not exist'], 200);
            }
            $honorary->amount = $request->input('amount');
            $honorary->save();
            return response()->json(['Honorary' => $honorary, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        $honorary = $this->checkHonoraryExist($id);
        if (!$honorary) {
            return response()->json(['$honorary' => $honorary, 'message' => 'Id does not exist'], 200);
        }
        $honorary->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAll()
    {
        return Honorary::paginate(20);
    }

    public function get($id)
    {

        $honorary = $this->checkHonoraryExist($id);
        if (!$honorary) {
            return response()->json(['Honorary' => $honorary, 'message' => 'Id does not exist'], 200);
        }
        return response()->json(['Honorary' => $honorary, 'message' => 'successfull'], 200);
    }

    public function checkHonoraryExist($id)
    {
        return Honorary::find($id);
    }
}

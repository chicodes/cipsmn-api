<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Badge;



class USerController extends Controller
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
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required|string',
            'account_type' => 'required|string',
            'user_type' => 'required|string',
            'password' => 'required|string'
            //'password' => 'required|confirmed',
        ]);

        try {

            $user = new User;
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->account_type = $request->input('account_type');
            $user->user_type = $request->input('user_type');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $user = $this->checkUserExist($id);
            if (!$user) {
                return response()->json(['Badge' => $user, 'message' => 'Id does not exist'], 200);
            }
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->account_type = $request->input('account_type');
            $user->user_type = $request->input('user_type');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->save();
            return response()->json(['Badge' => $user, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        $user = $this->checkUserExist($id);
        if (!$user) {
            return response()->json(['User' => $user, 'message' => 'User does not exist'], 200);
        }
        $user->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAllUser()
    {
        return User::all();
    }

    public function checkUserExist($id)
    {
        return User::find($id);
    }

    public function getUser($id)
    {
        return User::where('id', $id)->get();
    }


    public function getAllUserPayment()
    {
        $userid = Auth::user()->id;
        return BadgeUploaded::where('userid', $userid)->get();
    }
}

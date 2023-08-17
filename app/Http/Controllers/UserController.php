<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Utility\Helper;
use Exception;


class UserController extends Controller
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
            'email' => 'required|unique:users,email',
            'phone' => 'required',
            'address' => 'required|string',
            'account_type' => 'required|string',
            'user_type' => 'required|string',
            'password' => 'required|string',
            'file' => 'required'
            //'password' => 'required|confirmed',
        ]);

        try {

            $image = $this->fileUpload($request);
            $uploadImage = new Image;
            $uploadImage->type = 'badge';
            $uploadImage->name = $image['image_name'];
            $uploadImage->url = $image['image_path'];
            $uploadImage->save();

            $user = new User;
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->account_type = $request->input('account_type');
            $user->user_type = $request->input('user_type');
            $user->paid_for_regular ="0";
            $user->image_id = $uploadImage->id;
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->reg_id= $request->input('reg_id');
            //set role id to 4 which is a student role
            $user->role_id = "4";

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
                return response()->json(['User' => $user, 'message' => 'Id does not exist'], 200);
            }
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->account_type = $request->input('account_type');
            $user->user_type = $request->input('user_type');
            $user->reg_id= $request->input('reg_id');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->save();
            return response()->json(['User' => $user, 'message' => 'UPDATED'], 200);
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
        return User::paginate(20);
    }

    public function checkUserExist($id)
    {
        return User::find($id);
    }

    public function getUser($id)
    {
        $getUser =  User::where('id', $id)->first();

        //$userArray = (array) $getUser;

        //array_push( $userArray, $getUser->image->url);

        return $getUser;

        //return response()->json(['user' => $getUser, 'message' => 'SUCCESFULL'], 200);
    }

    public function getUserByAccountType(Request $request)
    {
        $this->validate($request, [
            'account_type' => 'required|string',
        ]);
        return User::where('account_type', $request->account_type)->paginate(20);
    }

    public function getUserSearch(Request $request)
    {
        //if isset search term
        if(isset($request->search_term)){
            return User::where('firstname','like', '%' . $request->search_term . '%')
                ->orWhere('lastname', 'like', '%' . $request->search_term . '%')
                ->orWhere('reg_id', 'like', '%' . $request->search_term . '%')
                ->paginate(20);
        }
        //if account_type is set
        if(isset($request->account_type)) {
            return User::where('account_type', $request->account_type)->paginate(20);
        }

        return;
    }

    public function fileUploadTest(Request $request)
    {
        try {
            $folderName = "badge";
           return Helper::fileUpload($request,$folderName);
        }
        catch (Exception $e){
            echo $e;
        }
    }

    private function fileUpload($request)
    {
        try {
            $folderName = "exam";
            return Helper::fileUpload($request,$folderName);
        }
        catch (Exception $e){
            echo $e;
        }
    }

    public function getUserRegularPaymentStatus(){
        $id = Auth::user()->id;
        return User::where('id', $id)->pluck('paid_for_regular');
    }

    public function getPaidForExemption(){
        $id = Auth::user()->id;
        return User::where('id', $id)->pluck('paid_for_exemption');
    }

    public function changePassword(Request $request)
    {
        $id = Auth::user()->id;

        //validate incoming request
        $this->validate($request, [
            'password' => 'required|string',
            'confirm_password' => 'required|string'
        ]);

        try {

            $user = $this->checkUserExist($id);
            if (!$user) {
                return response()->json(['User' => $user, 'message' => 'Id does not exist'], 200);
            }
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'password succesfully updated'], 201);

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function editProfileMember(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $user = $this->checkUserExist($id);
            if (!$user) {
                return response()->json(['User' => $user, 'message' => 'User does not exist'], 404);
            }
            Log::info("Address filled", [$user->address]);
            if($request->input('firstname')==null){
                $user->firstname = $user->firstname;
            }else{
                $user->firstname = $request->input('firstname');
            }

            if($request->input('lastname')==null){
                $user->lastname = $user->lastname;
            }else{
                $user->lastname = $request->input('lastname');
            }

            if($request->input('email')==null){
                $user->email = $user->email;
            }else{
                $user->email = $request->input('email');
            }

            if($request->input('phone')==null){
                $user->phone = $user->phone;
            }else{
                $user->phone = $request->input('phone');
            }

            if($request->input('address')==null){
                $user->account_type = $user->account_type;
            }else{
                $user->account_type = $request->input('account_type');
            }

            if($request->input('user_type')==null){
                $user->user_type = $user->user_type;
            }else{
                $user->user_type = $request->input('user_type');
            }

            if($request->input('address')==null){
                $user->address = $user->address;
            }else{
                $user->address = $request->input('address');
            }

            if($request->input('password')==null){
                $user->password = $user->password;
            }else{
                $user->password = app('hash')->make($request->input('password'));
            }

            $user->save();
            return response()->json(['User' => $user, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getMyUserProfile()
    {
        $id = Auth::user()->id;
        $getUser =  User::where('id', $id)->first();

        //$userArray = (array) $getUser;

        //array_push( $userArray, $getUser->image->url);

        return $getUser;

        //return response()->json(['user' => $getUser, 'message' => 'SUCCESFULL'], 200);
    }
}

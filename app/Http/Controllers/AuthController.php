<?php

namespace App\Http\Controllers;
use App\Models\Certificate;
use App\Models\ExamExempt;
use App\Models\Image;
use App\Models\PaymentSettings;
use App\Models\Settings;
use App\Utility\Helper;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

use \Laravel\Lumen\Routing\UrlGenerator;
//use Illuminate\Support\str;



class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth:api', ['except' => ['login']]);
//    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
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

            $uploadedFileUrl = cloudinary()->uploadFile($request->file('file')->getRealPath())->getSecurePath();
            //dd($uploadedFileUrl);

            $uploadImage = new Image;
            if($request->file('file')) {
                $image = $this->fileUpload($request);
                $uploadImage->type = 'user';
                $uploadImage->name = $image['image_name'];
                $uploadImage->url = $uploadedFileUrl;
                $uploadImage->save();


            }
            else {
                $uploadImage->id = null;
            }

            $user = new User;
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->account_type = $request->input('account_type');
            $user->user_type = $request->input('user_type');
            $user->paid_for_regular = "0";
            $user->image_id = $uploadImage->id;
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

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
//    public function me()
//    {
//        return response()->json(auth()->user());
//    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        $getDonation = Settings::where('name', 'donation')->first();
//        $checkPaidForRegular = User::getUserRegularPaymentStatus();
//        $checkPaidForExemption = User::getPaidForExemption();
        $regulaAmount = PaymentSettings::getRegularAmount(); //dd($regulaAmount->amount);
        $exemptionAmount = PaymentSettings::getUserExemptionAmount();
        $registrationAmount = PaymentSettings::getRegistrationAmount();
        //if($getDnations->status =='1'){}
        $donation = $getDonation->status=='1' ? 'true':'false';
        $regular = Auth::user()->paid_for_regular == 1 ? true:false;
        $exemption = Auth::user()->paid_for_exemption == 1 ? true:false;

        $registrationPayment = Auth::user()->paid_for_registration;

        $checkCertificate = Certificate::checkAnyCertificateUploaded();
        if($checkCertificate){
            $checkCertificate = true;
        }
        else{
            $checkCertificate = false;
        }


        $checkUserExempted = ExamExempt::checkUserExempted();
        if($checkUserExempted){
            $checkExempted = true;
        }
        else{
            $checkExempted = false;
        }

        $paidRegular = Auth::user()->paid_for_regular == 1 ? true:false;
        $paidExemption = Auth::user()->paid_for_exemption == 1 ? true:false;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user_type' => Auth::user()->user_type,
            'account_type' => Auth::user()->account_type,
            'donations' => $donation,
            'regular' => $regular,
            'exemption' => $exemption,
            'regular_amount' => $regulaAmount->amount,
            'exemption_amount' => $exemptionAmount,
            'paid_for_registration' => $registrationPayment,
            'registration_amount' => $registrationAmount->amount,
            'check_certificate_uploaded' => false,
            //certificate_upload_required was added to handle the update of regular and conversion being the same
            //'certificate_upload_required' => 'true',
            'check_exempted' => $checkExempted,
            'regular_paid' => $paidRegular,
            'exemption_paid' => $paidExemption,
            'reg_id' => Auth::user()->reg_id,
            //'reg_id33' => '8776'
        ]);
    }

    private function fileUpload($request)
    {
        try {
            $folderName = "user";
            return Helper::fileUpload($request,$folderName);
        }
        catch (Exception $e){
            echo $e;
        }
    }

    public function getImage(){

        $path = 'uploads/badge/622cab278bca9_passport.png/';
        //dd(Helper::doAsset("public/uploads/badge/622cab278bca9_passport.jpg"));
        //var_dump(asset('uploads/badge/622cab278bca9_passport.png/'));
        $urlGenerator = new UrlGenerator(app());
        var_dump($urlGenerator->asset('public/uploads/badge/622cab278bca9_passport.jpg/'));

//        new Laravel\Lumen\Routing\UrlGenerator(app()))
//            ->to($path, $parameters, $secure);

//
//        str_slug('Laravel 5 Framework', '-');
    }
}

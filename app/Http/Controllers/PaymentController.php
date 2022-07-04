<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use App\Models\PaymentMade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Payment;



class PaymentController extends Controller
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

    public function makePayment(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'type' => 'required|string',
            'amount' => 'required|string',
            'status' => 'required|string',
            'userid' => 'required|string'
        ]);

        try {


            $payment = new Payment;

            if($payment->type == 'exam'){
                $payment->name = $request->input('name');
            }

            $payment->type = $request->input('type');
            $payment->amount = $request->input('amount');
            $payment->status = $request->input('status');
            $payment->userid = Auth::user()->id;
            $payment->purpose = $request->input('purpose');
            $payment->save();

            //if payment type equals regular update paid for regular in user table
            if($request->input('type') == 'regular'){
                $id = Auth::user()->id;
                $getUser = User::find($id);
                $getUser->paid_for_regular = 1;
                $getUser->save();
            }

            //if payment type equals exemption update paid for exemption in user table
            if($request->input('type') == 'exemption'){
                $id = Auth::user()->id;
                $getUser = User::find($id);
                $getUser->paid_for_exemption = 1;
                $getUser->save();
            }

            if($request->input('type') == 'registration'){
                $id = Auth::user()->id;
                $getUser = User::find($id);
                $getUser->paid_for_registration = 1;
                $getUser->save();
            }

            //Todo if payment equals exam then store corresponding badge
            $userid = Auth::user()->id;
            switch($request->input('type')){
                case "exam1":
                    $getUserBadge = new BadgeUploaded();
                    $getUserBadge->userid = $userid;
                    $getUserBadge->badge_id = '1';
                    $getUserBadge->badge_type = 'exam1';
                    $getUserBadge->image_id = '0';
                    $getUserBadge->save();
                    break;
                case "exam2":
                    $getUserBadge = new BadgeUploaded();
                    $getUserBadge->userid = $userid;
                    $getUserBadge->badge_id = '2';
                    $getUserBadge->badge_type = 'exam2';
                    $getUserBadge->image_id = '0';
                    $getUserBadge->save();
                    break;
                case "exam3":
                    $getUserBadge = new BadgeUploaded();
                    $getUserBadge->userid = $userid;
                    $getUserBadge->badge_id = '3';
                    $getUserBadge->badge_type = 'exam3';
                    $getUserBadge->image_id = '0';
                    $getUserBadge->save();
                    break;
                case "exam4":
                    $getUserBadge = new BadgeUploaded();
                    $getUserBadge->userid = $userid;
                    $getUserBadge->badge_id = '4';
                    $getUserBadge->badge_type = 'exam4';
                    $getUserBadge->image_id = '0';
                    $getUserBadge->save();
                    break;
                case "exam5":
                    $getUserBadge = new BadgeUploaded();
                    $getUserBadge->userid = $userid;
                    $getUserBadge->badge_id = '5';
                    $getUserBadge->badge_type = 'exam5';
                    $getUserBadge->image_id = '0';
                    $getUserBadge->save();
                    break;
                case "exam6":
                    $getUserBadge = new BadgeUploaded();
                    $getUserBadge->userid = $userid;
                    $getUserBadge->badge_id = '6';
                    $getUserBadge->badge_type = 'exam6';
                    $getUserBadge->image_id = '0';
                    $getUserBadge->save();
                    break;
                case "exam7":
                    $getUserBadge = new BadgeUploaded();
                    $getUserBadge->userid = $userid;
                    $getUserBadge->badge_id = '7';
                    $getUserBadge->badge_type = 'exam7';
                    $getUserBadge->image_id = '0';
                    $getUserBadge->save();
            }
            //exam1 = badge1. exam2 = badge2 etc

            //Todo if paying for exemption add corresponding exams the user is exempted from and badge as well


            //return successful response
            return response()->json(['Payment' => $payment, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $payment = $this->checkPaymentExist($id);
            if (!$payment) {
                return response()->json(['Payment' => $payment, 'message' => 'Id does not exist'], 200);
            }
            $payment->type = $request->input('type');
            $payment->amount = $request->input('amount');
            $payment->status = $request->input('status');
            $payment->save();
            return response()->json(['Payment' => $payment, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        $payment = $this->checkPaymentExist($id);
        if (!$payment) {
            return response()->json(['Payment' => $payment, 'message' => 'Id does not exist'], 200);
        }
        $payment->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public static function getAllRecords()
    {
        return Payment::paginate(20);
    }

    public function getAllUserPayment(){
        $id = Auth::user()->id;
        $getPayment = Payment::where('userid', '22')->get();
        //dd($getPayment);
        if ($getPayment->isEmpty()) {
            return response()->json(['message' => 'User has not made any payment'], 404);
        }
        return $getPayment;
    }

    public function getAllUserPendingPayment(){
        $id = Auth::user()->id;
        return PaymentMade::where('userid', $id)
            ->where('status', '=', 0)
            ->get();
    }

    public function checkPaymentExist($id)
    {
        return Payment::find($id);
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\BadgeUploaded;
use App\Models\PaymentMade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Payment;
use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;


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
        Log::info("request received" . $request);
        //validate incoming request
        $this->validate($request, [
            'type' => 'required|string',
            'amount' => 'required|string',
            'status' => 'required|string',
            //'userid' => 'required|string',
            'purpose' => 'required|string'
        ]);

        try {


            Log::info("i got here 1");
            $payment = new Payment;

            $userid = Auth::user()->id;
            Log::info("Userid is" .  $userid);
            //$userid = "1830";
            $curentYear = "";

            if($request->input('type') == "exam"){
                $payment->name = $request->input('name');;
            }

            LOG::info("Payment type is". $request->input('type'));
            //if payment type equals regular update paid for regular in user table
            if($request->input('type') == 'regular'){
                LOG::info("I got here regular");
                $this->checkPaidForItemInYear($userid, 'regular');

                $getUser = User::find($userid);
                $getUser->paid_for_regular = 1;
                $getUser->save();

                $this->savePayment($payment, $request, $userid);
            }

            //if payment type equals exemption update paid for exemption in user table
            if($request->input('type') == 'exemption'){

                $this->checkPaidForItemInYear($userid, 'exemption');

                $getUser = User::find($userid);
                $getUser->paid_for_exemption = 1;
                $getUser->save();

                $this->savePayment($payment, $request, $userid);
            }

            if($request->input('type') == 'registration'){

                $getPayment =  Payment::where('userid', $userid)
                    ->where('type', '=', 'registration')
                    ->where('status', '=', 'success')
                    ->get();

                LOG::info("get payment returned . $getPayment");

                if($getPayment->isEmpty()) {
                    //get payment year from created_at column

                    //TODO: uncomment this when you finally want to add the checks
//                    $dateFromCreatedAt = $getPayment->created_at;
//
//                    Log::info("i got here 6");
//                    if ($dateFromCreatedAt == $curentYear) {
//                        //dont allow user pay because he has already paid for the year, else return
//                        return response()->json(['Payment' => null, 'message' => 'already paid for exemption for the year'], 200);
//                    }

                    $getUser = User::find($userid);
                    $getUser->paid_for_registration = 1;
                    $getUser->save();

                    $paymentSaved = $this->savePayment($payment, $request, $userid);

                    LOG::info("After saving payment ". $paymentSaved);

//                    $payment = new Payment();
//                    $payment->type = "";
//                    $payment->amount = "status";
//                    $payment->status = "";
//                    $payment->userid = $userid;
//                    $payment->created_at = "";
//                    $payment->save();
                }
            }

            if($request->input('type') == 'exam') {
                $getUserBadge = new BadgeUploaded();
                $getUserBadge->userid = $userid;
                $getUserBadge->badge_id = '1';
                $getUserBadge->badge_type = $request->input('type');
                $getUserBadge->image_id = '0';
                $getUserBadge->save();

                $this->savePayment($payment, $request, $userid);
            }
            //exam1 = badge1. exam2 = badge2 etc

            //Todo if paying for exemption add corresponding exams the user is exempted from and badge as well


            //return successful response
            return response()->json(['Payment' => $payment, 'message' => 'Payment Successful'], 201);

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

    private function checkPaidForItemInYear( $userid, String $type){
        $checkPaymentExist = "";
                        $getPayment =  Payment::where('userid', $userid)
                    ->where('type', '=', $type)
                    ->where('status', '=', 'success')
                            //->where('status', '=', 'kkk')
                    ->first();
//dd($getPayment);
                        //match not found return null
                        if($getPayment == null){
                            //return false;
                            Log::info(" no payment found for $type");
                            return false;
                            //dd($getPayment);
                        }
        //get payment year from created_at column

        LOG::info("payment returned".$getPayment);

            $dateFromCreatedAt = $getPayment['created_at'];

        LOG::info("payment date returned".$getPayment->created_at);

            $currentDate = Carbon::now();

            LOG::info("Current date is" . $currentDate);

            //        echo"Date from db is $dateFromCreatedAt, current date is $currentDate";
            //        exit;
            //match found but already paid
            if ($dateFromCreatedAt == $currentDate) {
                Log::info("found and already paid");
                dd($checkPaymentExist);
                //dont allow user pay because he has already paid for the year, else return
                //return response()->json(['Payment' => null, 'message' => 'already paid for exemption for the year'], 200);
                return true;
            }
            echo "found can pay";
            return false;
    }

    function savePayment($payment, $request, $userid){

        LOG::info("i got here 23");
        $payment->type = $request->input('type');
        $payment->amount = $request->input('amount');
        $payment->status = $request->input('status');
        $payment->userid = $userid;
        $payment->purpose = $request->input('purpose');
        $payment->save();

        LOG::info("i got here 24");
}

}

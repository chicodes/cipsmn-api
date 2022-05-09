<?php

namespace App\Http\Controllers;
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
            'userid' => 'required|string',
            'type' => 'required|string'
        ]);

        try {

            $payment = new Payment;
            $payment->type = $request->input('type');
            $payment->amount = $request->input('amount');
            $payment->status = $request->input('status');
            $payment->userid = Auth::user()->id;
            $payment->purpose = $request->input('purpose');
            $payment->save();

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
                return response()->json(['Badge' => $payment, 'message' => 'Id does not exist'], 200);
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

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PaymentSettings;



class PaymentSettingsController extends Controller
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
            'amount' => 'required|string',
            'status' => 'required|string'
        ]);

        try {

            $paymentSettings = new PaymentSettings;
            $paymentSettings->name = $request->input('name');
            $paymentSettings->description = $request->input('description');
            $paymentSettings->amount = $request->input('amount');
            $paymentSettings->status = $request->input('status');
            $paymentSettings->save();

            //return successful response
            return response()->json(['payment_settings' => $paymentSettings, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return $e;
            //return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $paymentSettings = $this->checkPaymentSettingsExist($id);
            if (!$paymentSettings) {
                return response()->json(['Payment Settings' => $paymentSettings, 'message' => 'Id does not exist'], 200);
            }
            $paymentSettings->name = $request->input('name');
            $paymentSettings->description = $request->input('description');
            $paymentSettings->amount = $request->input('amount');
            $paymentSettings->status = $request->input('status');
            $paymentSettings->save();
            return response()->json(['payment_settings' => $paymentSettings, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        $paymentSettings = $this->checkPaymentSettingsExist($id);
        if (!$paymentSettings) {
            return response()->json(['payment_settings' => $paymentSettings, 'message' => 'Id does not exist'], 200);
        }
        $paymentSettings->delete();
        return response()->json(['message' => 'DELETE SUCCESSFUL'], 200);
    }

    public function getAll()
    {
        return PaymentSettings::paginate(20);
    }

    public function get($id)
    {

        $paymentSettings = $this->checkPaymentSettingsExist($id);
        if (!$paymentSettings) {
            return response()->json(['payment_settings' => $paymentSettings, 'message' => 'Id does not exist'], 200);
        }
        return response()->json(['payment_settings' => $paymentSettings, 'message' => 'successfull'], 200);
    }

    public function checkPaymentSettingsExist($id)
    {
        return PaymentSettings::find($id);
    }
}

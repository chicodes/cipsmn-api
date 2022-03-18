<?php

namespace App\Http\Controllers;
//use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Exam;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\ExamToTake;
use Auth;





class DashboardController extends Controller
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

    public function getUserDashboard()
    {
        $examToTake = new ExamToTake;

        $id = Auth::user()->id;
        //if user equals regular show regular dashboard
        if(Auth::user()->account_type == 'regular'){
            //Todo check if regular user has paid regular fee, simply add regular to array,
            return $examToTake::getRegularUserDashboard();
        }
        return $examToTake::getConvertUserDashboard();
    }

    public function getAdminDashboard()
    {
        //get total users
        $getTotalUsers = User::getTotalUsers();

        //get total amount paid
        $getTotalPayment = Payment::getTotalPayment();

        //get total exam added
        $getTotalExam = Exam::getTotalExam();

        //get total badge
        $getTotalBage = Badge::getTotalBadge();

        $getRecentUsers = User::getRecentUsers();

        $getRecentPayments = Payment::getRecentPayment();

        $dashBoardResult = [
            'total_users' => $getTotalUsers,
            'total_payment' => $getTotalPayment,
            'total_exam' => $getTotalExam,
            'total_badge' => $getTotalBage,
            'recent_users' => $getRecentUsers,
            'recent_payments' => $getRecentPayments
        ];

        return $dashBoardResult;
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

    public function getAllRecords()
    {
        return Payment::all();
    }

    public function checkPaymentExist($id)
    {
        return Payment::find($id);
    }
}

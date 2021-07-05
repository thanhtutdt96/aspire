<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RepaymentController extends Controller
{
    /**
     * List of repayments
     */
    public function index()
    {
        $repayments = Repayment::whereEnabled(1)->get();

        return response()->json([
            'repayments' => $repayments
        ]);
    }

    /**
     * Display the specified repayment
     */
    public function show($id)
    {
        $repayment = Repayment::findOrFail($id);

        if (!$repayment->enabled) {
            return response()->json([
                'message' => 'Repayment is not available'
            ]);
        }

        return response()->json([
            'repayment' => $repayment
        ]);
    }

    /**
     * Update repayment
     */
    public function update(Request $request, $id)
    {
        // Validate request data
        $validators = [
            'loan_id' => 'exists:loans,id',
            'user_id' => 'exists:users,id',
            'amount' => 'numeric',
            'nth_payment' => 'numeric',
            'paid_date' => 'date',
            'due_date' => 'date'
        ];

        $validator = Validator::make($request->all(), $validators);

        // Return validation results
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ]);
        } else {
            $repayment = Repayment::findOrFail($id);
            $repayment->update($request->all());
        }

        return response()->json([
            'message' => 'Repayment updated successfully'
        ]);
    }

    /**
     * Remove the specified repayment
     */
    public function destroy($id)
    {
        $repayment = Repayment::findOrFail($id);

        $repayment->delete();

        return response()->json([
            'message' => 'Repayment has been deleted successfully'
        ]);
    }

    /**
     * Make repayment
     */
    public function makeRepayment(Request $request, $id)
    {
        $repayment = Repayment::findOrFail($id);

        $loan = $repayment->loan;

        // Check if loan is full paid
        if ($loan && $loan->status === Loan::$FULL_PAID) {
            return response()->json([
                'message' => 'Loan has been paid already'
            ]);
        }

        // Check if repayment is paid
        if ($repayment->status === Repayment::$PAID) {
            return response()->json([
                'message' => 'Repayment has been paid already'
            ]);
        }

        // Update repayment to paid status
        $repayment->update([
            'payment_method' => $request->get('payment_method') ?? '',
            'note' => $request->get('note') ?? '',
            'paid_date' => Carbon::now(),
            'status' => Repayment::$PAID
        ]);

        // Check if loan is full paid after make repayment
        $loan = $repayment->loan;
        if ($loan) {
            $repayments = $loan->repayments;
            $package = $loan->package;
            $months = $package->months;

            $paidRepayments = $repayments->where('status', Repayment::$PAID);

            if (count($paidRepayments) === $months) {
                $loan->status = Loan::$FULL_PAID;
                $loan->save();
            }
        }

        return response()->json([
            'repayment' => [
                'amount' => $repayment->amount,
                'nth_payment' => $repayment->nth_payment,
                'paid_date' => $repayment->paid_date,
                'due_date' => $repayment->due_date,
                'status' => $repayment->status
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Package;
use App\Models\Repayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
     * List of loans
     */
    public function index()
    {
        $loans = Loan::whereEnabled(1)->get();

        return response()->json([
            'loans' => $loans
        ]);
    }

    /**
     * Create new loan
     */
    public function store(Request $request)
    {
        // Validate request data
        $validators = [
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'base_amount' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $validators);

        // Return validation results
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ]);
        } else {
            $startDate = $request->get('start_date') ?? Carbon::now();
            $baseAmount = $request->get('base_amount');
            $packageId = $request->get('package_id');
            $package = Package::find($packageId);
            $user = User::find($request->get('user_id'));

            if (!$user->enabled) {
                return response()->json([
                    'message' => 'User is not available'
                ]);
            }

            if (!$package->enabled) {
                return response()->json([
                    'message' => 'Package is not available'
                ]);
            }

            $interestRate = $package->interest_rate / 100;
            $arrangementFeeRate = $package->arrangement_fee_rate / 100;

            $request->merge([
                'start_date' => $startDate,
                'end_date' => $this->calculateEndDate($startDate, $package->months),
                'total_amount' => $this->calculateTotalAmount($baseAmount, $interestRate, $arrangementFeeRate),
                'status' => Loan::$INIT
            ]);

            // Create new loan
            $loan = Loan::create($request->all());

            // Create repayments from loan
            $this->createRepayments($loan);
        }

        return response()->json([
            'loan' => [
                'user' => $loan->user->name,
                'total' => $loan->total_amount,
                'start_date' => $loan->start_date,
                'end_date' => $loan->end_date
            ],
            'repayments' => $loan->repayments->toArray()
        ]);
    }

    /**
     * Calculate loan end date
     */
    protected function calculateEndDate($startDate, $months)
    {
        return Carbon::parse($startDate)->addMonths($months);
    }

    /**
     * Calculate loan total amount
     */
    protected function calculateTotalAmount($baseAmount, $interestedRate, $arragementFeeRate)
    {
        return $baseAmount + $baseAmount * $arragementFeeRate + $baseAmount * $interestedRate;
    }

    /**
     * Create repayments from loan
     */
    protected function createRepayments(Loan $loan)
    {
        $result = [];

        $package = $loan->package;
        $interestedRate = $package->interest_rate / 100;
        $arrangementFeeRate = $package->arrangement_fee_rate / 100;

        $totalAmount = $this->calculateTotalAmount($loan->base_amount, $arrangementFeeRate, $interestedRate);
        $monthlyRepayment = round($totalAmount / $package->months);

        $startDate = Carbon::parse($loan->start_date);

        for ($i = 1; $i <= $package->months; $i++) {
            $startDate->addMonth();
            $result[] = Repayment::create([
                'loan_id' => $loan->id,
                'user_id' => $loan->user->id,
                'amount' => $this->roundMoney($monthlyRepayment),
                'nth_payment' => $i,
                'due_date' => $startDate,
                'status' => Repayment::$UNPAID
            ]);
        }

        return $result;
    }

    /**
     * Round money in VND
     */
    protected function roundMoney($total)
    {
        if ($total % 1000 > 0) {
            $change = 1000 - ($total % 1000);
            $total += $change;
        }

        return $total;
    }

    /**
     * Update loan
     */
    public function update(Request $request, $id)
    {
        // Validate request data
        $validators = [
            'user_id' => 'exists:users,id',
            'package_id' => 'exists:packages,id',
            'base_amount' => 'numeric',
            'total_amount' => 'numeric',
            'paid_amount' => 'numeric',
            'start_date' => 'date',
            'end_date' => 'date',
            'enabled' => 'boolean'
        ];

        $validator = Validator::make($request->all(), $validators);

        // Return validation results
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ]);
        } else {
            $loan = Loan::findOrFail($id);

            if (!$loan->enabled) {
                return response()->json([
                    'message' => 'Loan is not available'
                ]);
            }

            $loan->update($request->all());
        }

        return response()->json([
            'message' => 'Loan updated successfully'
        ]);
    }

    /**
     * Display the specified loan
     */
    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        if (!$loan->enabled) {
            return response()->json([
                'message' => 'Loan is not available'
            ]);
        }

        return response()->json([
            'loan' => $loan
        ]);
    }

    /**
     * Remove the specified loan
     */
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        if (!$loan->enabled) {
            return response()->json([
                'message' => 'Loan is not available'
            ]);
        }

        $loan->delete();

        return response()->json([
            'message' => 'Loan has been deleted successfully'
        ]);
    }
}

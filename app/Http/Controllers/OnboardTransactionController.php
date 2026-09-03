<?php

namespace App\Http\Controllers;

use App\Models\OnboardingTransaction;
use Illuminate\Http\Request;

class OnboardTransactionController extends Controller
{
    public function onboarding(Request $request)
	{
		try {

			// 🔐 Security
			if ($request->api_key !== 'INDIAKAROBAR@2026') {
				return response()->json([
					'status' => false,
					'message' => 'Unauthorized'
				]);
			}

			// ✅ Validation
			$request->validate([
				'company_code' => 'required',
				'date' => 'required|date',
				'leads' => 'required|numeric',
				'customers' => 'required|numeric',
				'amount' => 'required|numeric',
			]);

			// ✅ Save
			OnboardingTransaction::updateOrCreate(
				[
					'company_code' => $request->company_code,
					'date' => $request->date // ✅ business date
				],
				[
					'rec_date' => now(), // ✅ insert date (today)
					'total_leads' => $request->leads,
					'total_customers' => $request->customers,
					'total_amount' => $request->amount,
					'gst_amount' => 0,
					'isActive' => 1,
					'isDelete' => 0
				]
			);

			return response()->json([
				'status' => true,
				'message' => 'Data saved successfully'
			]);

		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage()
			]);
		}
	} 
}

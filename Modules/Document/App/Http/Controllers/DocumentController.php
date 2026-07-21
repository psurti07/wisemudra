<?php

namespace Modules\Document\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    protected $userType;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $userId = Auth::id();
            
            $record = DB::table('loan_applications')
                ->where('userid', $userId)
                ->select('user_type')
                ->first();
    
            $this->userType = $record ? $record->user_type : null;
    
            return $next($request);
        });
    }

    public function index()
    {
        $userId = Auth::id();
        $userType = $this->userType;
        $html = render_html($userType);
        $user = UserDocument::where('userid', $userId)->first();
        $addharNumber = isset($user->aadharcard_number) ? $user->aadharcard_number : '';
        $aadharDocs = isset($user->aadharcard) ? json_decode($user->aadharcard, true) : []; 
        $panNum = isset($user->pancard_number) ? $user->pancard_number : '';
        $panDocs = isset($user->pancard) ? $user->pancard : [];
        $lightBillDocs = isset($user->lightbill) ? $user->lightbill : [];
        $cancelCheque = isset($user->cancelcheque) ? $user->cancelcheque : [];
        $bankstatement = isset($user->bankstatement) ? $user->bankstatement : [];
        
        $formsixteen  = isset($user->formsixteen)  ? $user->formsixteen : [];
        $salaryslip  = isset($user->salaryslip)  ? $user->salaryslip : [];
        
        $businessProof  = isset($user->businessproof)  ? $user->businessproof : [];
        $itReturn  = isset($user->itreturn)  ? $user->itreturn : [];
        
        $remarks  = isset($user->remarks) ? $user->remarks : '';
        return view('document::index', compact('html','userId','addharNumber','aadharDocs','panNum','panDocs','cancelCheque','lightBillDocs','bankstatement','formsixteen','salaryslip','businessProof','itReturn','remarks','userType'));
    }

    public function storeAddhar(Request $request)
    {
        try {
            $request->validate([
                'aadhar_no' => 'required|numeric|digits:12',
                'aadhar_image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            $newImagePaths = [];
            if ($request->hasFile('aadhar_image')) {
                foreach ($request->file('aadhar_image') as $image) {
                    if (is_array($image)) {
                        foreach ($image as $singleImage) {
                            $newImagePaths[] = handleFileUpload('aadharcard', 'aadhar_image', $userId, 'aadhar_card', 'aadhar', $document, $singleImage);
                        }
                    } else {
                        $newImagePaths[] = handleFileUpload('aadharcard', 'aadhar_image', $userId, 'aadhar_card', 'aadhar', $document, $image);
                    }
                }
            }

            $document->aadharcard = json_encode($newImagePaths);
            $document->aadharcard_number = $request->aadhar_no;
            $document->save();
            // $aadharDocs = json_decode($document->aadharcard, true);
            $html = render_html($this->userType);

            return response()->json(['type' => 'success', 'message' => 'Aadhar Card uploaded successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    public function storePan(Request $request)
    {
        try {
            $request->validate([
                'pan_no' => [
                    'required',
                    'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                ],
                'pan_image.*' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            if ($request->hasFile('pan_image')) {
                $image = $request->file('pan_image');

                $newImagePath = handleFileUpload('pancard', 'pan_image', $userId, 'pancard', 'pan', $document, $image);
                $document->pancard = $newImagePath;
            }

            $document->pancard_number = $request->pan_no;
            $document->save();

            $html = render_html($this->userType);

            return response()->json(['type' => 'success', 'message' => 'PAN Card uploaded successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    public function storeBill(Request $request)
    {
        try {
            $request->validate([
                'light_bill' => 'required|mimes:pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);


            if ($request->hasFile('light_bill')) {
                $image = $request->file('light_bill');

                $newImagePath = handleFileUpload('lightbill', 'light_bill', $userId, 'lightbill', 'light', $document, $image);
                $document->lightbill = $newImagePath;
            }

            $document->save();
            $html = render_html($this->userType);
            return response()->json(['type' => 'success', 'message' => 'Light bill updated successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    public function storeCancelCheque(Request $request)
    {
        try {
            $request->validate([
                'cancel_cheque' => 'required|mimes:pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            if ($request->hasFile('cancel_cheque')) {
                $image = $request->file('cancel_cheque');

                $newImagePath = handleFileUpload('cancelcheque', 'cancel_cheque', $userId, 'cancelcheque', 'cancel', $document, $image);
                $document->cancelcheque = $newImagePath;
            }

            $document->save();
            $html = render_html($this->userType);
            return response()->json(['type' => 'success', 'message' => 'Cancel cheque updated successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    public function storeStatement(Request $request)
    {
        try {
            $request->validate([
                'bank_statement' => 'required|mimes:pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            if ($request->hasFile('bank_statement')) {
                $image = $request->file('bank_statement');

                $newImagePath = handleFileUpload('bankstatement', 'bank_statement', $userId, 'bankstatement', 'bank', $document, $image);
                $document->bankstatement = $newImagePath;
            }
            $document->save();
            $html = render_html($this->userType);
            return response()->json(['type' => 'success', 'message' => 'Bank statement updated successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    public function storeForm(Request $request)
    {
        try {
            $request->validate([
                'formsixteen' => 'required|mimes:pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            if ($request->hasFile('formsixteen')) {
                $image = $request->file('formsixteen');

                $newImagePath = handleFileUpload('formsixteen', 'form_sixteen', $userId, 'formsixteen', 'formsixteen', $document, $image);
                $document->formsixteen = $newImagePath;
            }
            $document->save();
            $html = render_html($this->userType);
            return response()->json(['type' => 'success', 'message' => 'Form sixteen updated successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    public function storeSalarySlip(Request $request)
    {
        try {
            $request->validate([
                'salary_slip' => 'required|mimes:pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            if ($request->hasFile('salary_slip')) {
                $image = $request->file('salary_slip');
                $newImagePath = handleFileUpload('salaryslip', 'salary_slip', $userId, 'salaryslip', 'salaryslip', $document, $image);
                $document->salaryslip = $newImagePath;
            }
            $document->save();
            $html = render_html($this->userType);
            return response()->json(['type' => 'success', 'message' => 'Salary slip updated successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }
    
    public function storeBusinessProof(Request $request)
    {
        try {
            $request->validate([
                'businessproof' => 'required|mimes:pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            if ($request->hasFile('businessproof')) {
                $image = $request->file('businessproof');
                $newImagePath = handleFileUpload('businessproof', 'businessproof', $userId, 'businessproof', 'businessproof', $document, $image);
                $document->businessproof = $newImagePath;
            }
            $document->save();
            $html = render_html($this->userType);
            return response()->json(['type' => 'success', 'message' => 'Business proof updated successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }
    
    public function storeItReturn(Request $request)
    {
        try {
            $request->validate([
                'itreturn' => 'required|mimes:pdf|max:2048',
            ]);

            $userId = auth()->user()->id;

            $document = UserDocument::firstOrNew(['userid' => $userId]);

            if ($request->hasFile('itreturn')) {
                $image = $request->file('itreturn');
                $newImagePath = handleFileUpload('itreturn', 'itreturn', $userId, 'itreturn', 'itreturn', $document, $image);
                $document->itreturn = $newImagePath;
            }
            $document->save();
            $html = render_html($this->userType);
            return response()->json(['type' => 'success', 'message' => 'IT Return updated successfully.', 'html' => $html], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }

    public function storeRemark(Request $request)
    {
        try {
            $request->validate([
                'remarks' => 'required',
            ]);

            $document = UserDocument::firstOrNew(['userid' => auth()->user()->id]);

            $document->rec_date = now();
            $document->userid = auth()->user()->id;
            $document->remarks = $request->remarks;
            $document->save();
            return response()->json(['type' => 'success', 'message' => 'Remark updated successfully.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', 'message' => 'Something went wrong.'], 500);
        }
    }
}

<?php

namespace Modules\Profile\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Administrations;
use App\Models\UserRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(){
        $customerid = Auth::user()->id;
        $profile = DB::table('user_registrations')->select('id','staff_id','first_name','last_name','rec_date','update_date','mobile','email','city','refcode','process_step','pincode','state','pancard','dob','acc_type','company_name','company_gst')
            ->where('id',$customerid)->where('isDelete',0)->where('isActive',1)->where('isUser',2)->first();
        $agent = Administrations::select('id','fullname','mobile')->where('id',$profile->staff_id)->first();
        return view('profile::index',compact('profile','agent'));
    }

    public function changePassword(){
        return view('profile::changepassword');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $result = UserRegistration::where('id',Auth::user()->id)->update(['password'=>Hash::make($request->input('password'))]);
        if($result){
            return response()->json(array('type'=>'SUCCESS','message'=>'Password updated successfull.','data'=>[]));
        } else {
            return response()->json(array('type'=>'ERROR','message'=>'Something went wrong while updating password.','data'=>[]));
        }
    }

    public function updateProfile(Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required','email',Rule::unique('user_registrations')->ignore(Auth::user()->id)],
            'city' => 'required',
            'state' => 'required',
            'dob' => 'required',
            'pincode' => 'required',
        ]);
        $newInputs = [
            'first_name' => ucfirst(trim($request->input('first_name'))),
            'last_name' => ucfirst(trim($request->input('last_name'))),
            'email' => strtolower(trim($request->input('email'))),
            'city' => trim($request->input('city')),
            'state' => trim($request->input('state')),
            'dob' => $request->input('dob'),
            'pincode' => $request->input('pincode'),
        ];
        $result = UserRegistration::where('id', Auth::user()->id)->update($newInputs);
        if($result){
            return response()->json(array('type'=>'SUCCESS','message'=>'','data'=>[]));
        } else {
            return response()->json(array('type'=>'ERROR','message'=>'Something went wrong while updating profile.','data'=>[]));
        }
    }
    
    public function postalDetails(Request $request)
    {
        // Call the helper function
        $promise = getPostalDetailsByPincode($request->input('pincode'));
        // Wait for the async response
        $result = $promise->wait();
        if (isset($result[0]['PostOffice'][0])) {
            // Get the first PostOffice record
            $postOffice = $result[0]['PostOffice'][0];

            // Extract the district and state
            $district = $postOffice['District'];
            $state = $postOffice['State'];

            // Return or use these values as needed
            return response()->json(['status' => 'success', 'district' => $district, 'state' => $state,]);
        }
        return response()->json(['status' => 'false', 'district' => '', 'state' => '',]);
    }

    public function companyDetails(Request $request){
        try{
            $request->validate([
                'company_name' => 'required',
                'company_gst' => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ]);
            $result = UserRegistration::where('id', Auth::user()->id)->update($request->all());
            if($result){
                return response()->json(array('type'=>'SUCCESS','message'=>'Company updated Successfully','data'=>[]));
            } else {
                return response()->json(array('type'=>'SUCCESS','message'=>'Company updated Successfully','data'=>[]));
            }
        }catch(\Illuminate\Validation\ValidationException $e){
          return response()->json(['type'=>'ERROR', 'errors'=>$e->errors()],422);  
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['type'=>'ERROR','message'=>'Ops! Something went wrong']);
        }
    }  
}

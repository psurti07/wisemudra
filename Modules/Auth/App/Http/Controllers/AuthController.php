<?php

namespace Modules\Auth\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
   public function login(){
        $meta = customerAuth();
        //Log::info(Hash::make('123456'));
        return view('auth::index', compact('meta'));
    }

    public function authenticate(Request $request)
    {
        try {
            // Validate input
            $credentials = $request->validate([
                'mobile'   => 'required|numeric|regex:/^[6-9]\d{9}$/',
                'password' => 'required'
            ]);
    
            // Add extra condition for login
            $loginData = [
                'mobile'   => $credentials['mobile'],
                'password' => $credentials['password'],
                'isDelete' => 0
            ];
    
            if (Auth::attempt($loginData)) {
                return response()->json([
                    'type'     => 'SUCCESS',
                    'redirect' => route('customer.dashboard')
                ]);
            }
    
            return response()->json([
                'type'    => 'ERROR',
                'message' => 'Invalid login. Please check your mobile and password.'
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'type'   => 'ERROR',
                'errors' => $e->errors()
            ], 422);
    
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
    
            return response()->json([
                'type'    => 'ERROR',
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function authenticate2(){
        if (empty(Cookie::get('userid')) && empty(Cookie::get('user_mobile'))) {
            return redirect('/customer/login');
        } else {
            $credentials = [
                'mobile'   => Cookie::get('user_mobile'),
                'password' => Session::get('user_password'),
                'isDelete' => 0,
            ];
    
            $redirectUrl = '/loans/pre-approved-loans';
        }
    
        if (Auth::attempt($credentials)) {
            return redirect($redirectUrl);
        } else {
            return response()->json([
                'type'    => 'ERROR',
                'message' => 'Invalid login. Please check your mobile and password.'
            ]);
        }
    }


    public function forgetPassword(){
        $meta = customerAuth();
        return view('auth::forgetPassword', compact('meta'));
    }

    public function forgetPasswordStore(Request $request){
        try{
            $inputs = $request->all();
            $request->validate([
                'mobile' => 'required|numeric|regex:/^[6-9]\d{9}$/',
            ]);
            $fetch = UserRegistration::where(['mobile'=>$inputs['mobile'], 'isUser'=>2, 'isActive'=>1, 'isDelete'=>0])->first();
            
            if($fetch){
                $newPassword = random_code();
                $hashed = Hash::make($newPassword);
                $updRes = UserRegistration::where('mobile', $fetch->mobile)->update(['password'=>$hashed]);
                
                /* send forget message starts */
                $msg = DB::table('sms_list')->where('type',1)->where('slug','forgot_password')->first()->message;
                if($msg != '#'){
                    $msg = str_ireplace('{#varpassword#}',$newPassword,$msg);
                    $sendertype = (($fetch->acc_type == 2) ? 'la-senderid' : (($fetch->acc_type == 3) ? 'lat-senderid' : 'sa-senderid'));
                    $panel = (($fetch->acc_type == 2) ? 'hire' : (($fetch->acc_type == 3) ? 'assistant' : 'self'));
                    $senderId = DB::table('info_pages')->where('slug',$sendertype)->first()->content;
                    sendDynamicSMS($senderId, $msg, $fetch->mobile, $panel, 'forget-password');
                }
                /* send forget message ends */
                
                if($updRes){
                    $sent = sendForgetPassword($fetch->first_name.' '.$fetch->last_name, $fetch->mobile, $fetch->email, $newPassword);
                    return response()->json(['type'=>'SUCCESS','message'=>'Password updated successfully.Updated Password sent on your email and mobile.']);
                } else {
                    return response()->json(['type'=>'ERROR','message'=>'Something went wrong while updating password.']);
                }
            } else {
                return response()->json(['type'=>'ERROR','message'=>'Sorry we couldn`t find your account. Contact to Support Team.']);
            }
        } catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['type'=>'ERROR', 'errors'=>$e->errors()],422);
        }catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['type'=>'ERROR','message'=>'Ops! Something went wrong.']);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class LoanApplyVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $mobile = Cookie::get('user_mobile');
        $loanAmount = Cookie::get('loan_amount');
        $userId = Cookie::get('userid');

        if (!$mobile || !$loanAmount || !$userId) {
            return redirect(route('self.apply.main'));
        }

        return $next($request);
    }
}

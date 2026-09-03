<?php

namespace App\Console\Commands;

use App\Models\MembershipOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CustomersPlanExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:customers-plan-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It`s call when the customers plan has been expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $today = Carbon::today();
            // 3-day and 6-day expiration dates
            $threeDays = $today->copy()->addDays(3);
            $sixDays = $today->copy()->addDays(6);
            // Find users whose plans expire in 3 or 6 days
            $users = MembershipOrder::with('userRegistration')->whereDate('expiry_date', $threeDays)
                ->orWhereDate('expiry_date', $sixDays)
                ->orWhereBetween('expiry_date', [
                    $today->copy()->subDays(2), // or subDay(1) if only yesterday
                    $today->copy()->subDay()
                ])
                ->get();
            foreach ($users as $user) {
                $expiryDate = Carbon::parse($user->expiry_date);
                if ($expiryDate->isToday()) {
                    // Optional: handle plans expiring *today*
                    //Log::info("{$user->userRegistration->email} plan expires today.");
                } elseif ($expiryDate->isPast()) {
                    // Plan already expired
                    //Mail::to($user->email)->send(new \App\Mail\PlanExpired($user));
                    //Log::info("Sent expired email to {$user->userRegistration->email}");
                } else {
                    $daysLeft = $today->diffInDays($expiryDate);
                    if ($daysLeft === 3) {
                        //Mail::to($user->email)->send(new \App\Mail\PlanExpiringSoon($user, 3));
                        //Log::info("Sent 3-day warning to {$user->userRegistration->email}");
                    } elseif ($daysLeft === 6) {
                        //Mail::to($user->email)->send(new \App\Mail\PlanExpiringSoon($user, 6));
                        //Log::info("Sent 6-day warning to {$user->userRegistration->email}");
                    }
                }
            }
        } catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }
}

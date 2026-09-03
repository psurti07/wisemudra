<?php

namespace App\Console\Commands;

use App\Models\WebinarOrder;
use App\Models\WebinarRegistration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWebinarDataToIndiakarobar extends Command
{
    protected $signature = 'send:webinar-data-hourly';
    protected $description = 'Send webinar data to indiakarobar every hour';

    public function handle()
    {
        // ✅ USE TODAY'S DATE (for hourly sync)
        $date = Carbon::today()->toDateString();
        $dateFormat = Carbon::today()->format('d/m/Y');

        Log::info('🚀 SendWebinarDataToIndiakarobar STARTED', [
            'date' => $date,
            'date_format' => $dateFormat,
            'start_time' => now()->toDateTimeString()
        ]);

        // Company details
        $companyCode = config('constant.COMPANY_CODE');
        $companyName = 'Wisemudra';
        $companyLocalIp = '190.92.174.183';

        $apiUrl = 'https://manage.indiakarobar.com/api/program-referral-data';

        // ============================================
        // 1️⃣ FETCH LEADS (isUser = 1 in webinar_order)
        // ============================================
        Log::info('📊 Fetching leads (isUser = 1) for today', [
            'date' => $date
        ]);

        $leads = WebinarOrder::whereDate('rec_date', $date)
            ->where('isUser', 1)
            ->get();

        $totalLeads = $leads->count();

        Log::info('✅ Leads fetched successfully', [
            'total_leads' => $totalLeads,
            'lead_ids' => $leads->pluck('id')->toArray(),
            'lead_userids' => $leads->pluck('userid')->toArray(),
            'lead_details' => $leads->map(function($lead) {
                return [
                    'id' => $lead->id,
                    'userid' => $lead->userid,
                    'amount' => $lead->amount,
                    'rec_date' => $lead->rec_date
                ];
            })->toArray()
        ]);

        // ============================================
        // 2️⃣ FETCH CUSTOMERS (isUser = 2 in webinar_order)
        // ============================================
        Log::info('👥 Fetching customers (isUser = 2) for today', [
            'date' => $date
        ]);

        $customers = WebinarOrder::whereDate('rec_date', $date)
            ->where('isUser', 2)
            ->get();

        $totalCustomers = $customers->count();
        $totalAmount = round($customers->sum('amount'), 2);

        Log::info('✅ Customers fetched successfully', [
            'total_customers' => $totalCustomers,
            'customer_ids' => $customers->pluck('id')->toArray(),
            'customer_userids' => $customers->pluck('userid')->toArray(),
            'total_amount' => $totalAmount,
            'customer_details' => $customers->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'userid' => $customer->userid,
                    'amount' => $customer->amount,
                    'rec_date' => $customer->rec_date
                ];
            })->toArray()
        ]);

        // ============================================
        // 3️⃣ SUMMARY
        // ============================================
        Log::info('📈 Webinar data summary prepared for today', [
            'date' => $date,
            'date_format' => $dateFormat,
            'company_code' => $companyCode,
            'leads_count' => $totalLeads,
            'customers_count' => $totalCustomers,
            'total_amount' => $totalAmount,
            'has_leads' => $totalLeads > 0,
            'has_customers' => $totalCustomers > 0,
            'has_amount' => $totalAmount > 0
        ]);

        // Skip if no data
        if ($totalLeads == 0 && $totalCustomers == 0 && $totalAmount == 0) {
            Log::info('⏭️ No data found for today, skipping sync', [
                'date' => $date,
                'reason' => 'All counts are zero'
            ]);
            $this->info('ℹ️ No data found for today, skipping sync');
            return 0;
        }

        // ============================================
        // 4️⃣ PREPARE PAYLOAD
        // ============================================
        $payload = [
            'api_key' => 'INDIAKAROBAR@2026',
            'company_code' => $companyCode,
            'company_name' => $companyName,
            'company_local_ip' => $companyLocalIp,
            'product' => 1, // 1=webinar, 2=workshop
            'data_date' => $dateFormat,
            'total_leads' => $totalLeads,        // isUser = 1
            'total_customer' => $totalCustomers,   // isUser = 2
            'total_amount' => $totalAmount
        ];

        Log::info('📦 Payload prepared for today\'s data', [
            'api_url' => $apiUrl,
            'payload' => $payload,
            'payload_json' => json_encode($payload)
        ]);

        // ============================================
        // 5️⃣ SEND TO API
        // ============================================
        try {
            $startTime = microtime(true);
            $response = send_webinar_data($apiUrl, $payload);
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            Log::info('⏱️ API call execution time', [
                'execution_time_seconds' => $executionTime
            ]);

            Log::info('📨 Raw API response received', [
                'response' => $response,
                'response_type' => gettype($response)
            ]);

            if ($response && isset($response['status']) && $response['status'] === true) {
                Log::info('✅ Webinar data sent successfully to Indiakarobar', [
                    'company_code' => $companyCode,
                    'response' => $response,
                    'leads_sent' => $totalLeads,
                    'customers_sent' => $totalCustomers,
                    'amount_sent' => $totalAmount,
                    'data_date' => $dateFormat
                ]);
                
                $this->info('✅ Webinar data synced successfully!');
                $this->line("   📅 Date: {$dateFormat}");
                $this->line("   📊 Leads (isUser=1): {$totalLeads}");
                $this->line("   👥 Customers (isUser=2): {$totalCustomers}");
                $this->line("   💰 Amount: ₹{$totalAmount}");
                $this->line("   ⏱️ Time: {$executionTime}s");
                
            } else {
                Log::error('❌ Failed to send webinar data to Indiakarobar', [
                    'company_code' => $companyCode,
                    'response' => $response,
                    'payload_sent' => $payload,
                    'data_date' => $dateFormat
                ]);
                
                $this->error('❌ Failed to sync webinar data');
                if ($response) {
                    $this->error("   Response: " . json_encode($response));
                }
            }
            
        } catch (\Exception $e) {
            Log::critical('💥 Exception occurred while sending webinar data', [
                'company_code' => $companyCode,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload,
                'data_date' => $dateFormat
            ]);
            
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error("   File: {$e->getFile()}:{$e->getLine()}");
        }

        Log::info('🏁 SendWebinarDataToIndiakarobar COMPLETED', [
            'end_time' => now()->toDateTimeString(),
            'data_date' => $dateFormat,
            'total_leads' => $totalLeads,
            'total_customers' => $totalCustomers,
            'total_amount' => $totalAmount
        ]);

        return 0;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ScheduleSlot;
use App\Models\WebinarRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleSlotController extends Controller
{
    public function getScheduleSlotPage(Request $request)
    {
        //dd(route('schedule-slot',['id' => encryptData(6986)]));
        //$id = encryptData($request->id);
        //$userId = $id ?? NULL;
        // $userId = $request->id ?? NULL;
        $userId = 88;
        if (!$userId) {
            return redirect()->route('front.home');
        }
        // $userId = decryptData($userId);

        $user = WebinarRegistration::find($userId);
        if (!$user) {
            return redirect()->route('front.home');
        }

        $schedule = ScheduleSlot::where([
            'user_id' => $userId,
            'status' => ScheduleSlot::SCHEDULED,
            'is_deleted' => 0,
        ])->first();

        // Already schedule then show success page
        if (!empty($schedule)) {
            return redirect()->route('schedule-success', ['id' => encryptData($schedule->id)]);
        }
        return view('front.ScheduleSlot.index', compact('user'));
    }

    public function scheduleSlot(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_webinar_registration,id',
            'date' => 'required|date',
            'time' => 'required',
            'language' => 'required|in:' . implode(",", array_keys(ScheduleSlot::getLanguages())),
        ]);

        try {
            $userId = $request->user_id;
            $user = WebinarRegistration::find($request->user_id);

            // ✅ CHECK if user already has a scheduled slot
            $existingSchedule = ScheduleSlot::where([
                'user_id' => $userId,
                'status' => ScheduleSlot::SCHEDULED,
                'is_deleted' => 0,
            ])->first();

            if ($existingSchedule) {
                // ✅ RESCHEDULE: Cancel old + Create new
                
                // 1. Store old schedule ID
                $oldScheduleId = $existingSchedule->id;
                $oldScheduleDate = $existingSchedule->date;
                $oldScheduleTime = $existingSchedule->time;

                // 2. Mark old as CANCELLED (status = 3)
                $existingSchedule->update([
                    'status' => ScheduleSlot::CANCELLED,
                    'is_deleted' => 1,
                    'updated_at' => now(),
                ]);

                // 3. Send CANCEL to Indiakarobar for old schedule
                $this->sendScheduleToIndiakarobar($existingSchedule, $user, 'cancel');

                // 4. Create NEW schedule
                $newSchedule = ScheduleSlot::create([
                    'user_id' => $userId,
                    'date' => Carbon::parse($request->date)->format('Y-m-d'),
                    'time' => Carbon::parse($request->time)->format('H:i'),
                    'language' => $request->language,
                    'status' => ScheduleSlot::SCHEDULED,
                ]);

                // 5. Send CREATE to Indiakarobar for new schedule
                $this->sendScheduleToIndiakarobar($newSchedule, $user, 'create');

                Log::info('Schedule rescheduled successfully', [
                    'user_id' => $userId,
                    'old_schedule_id' => $oldScheduleId,
                    'old_date' => $oldScheduleDate,
                    'old_time' => $oldScheduleTime,
                    'new_schedule_id' => $newSchedule->id,
                    'new_date' => $request->date,
                    'new_time' => $request->time,
                ]);

                return response()->json([
                    'type' => 'SUCCESS',
                    'message' => 'Your call rescheduled successfully.',
                    'redirect' => route('schedule-success', ['id' => encryptData($newSchedule->id)])
                ], 200);
            }

            // ✅ CREATE new schedule (first time)
            $schedule = ScheduleSlot::create([
                'user_id' => $userId,
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'time' => Carbon::parse($request->time)->format('H:i'),
                'language' => $request->language,
                'status' => ScheduleSlot::SCHEDULED,
            ]);

            // ✅ Send CREATE to Indiakarobar
            $this->sendScheduleToIndiakarobar($schedule, $user, 'create');

            Log::info('New schedule created', [
                'user_id' => $userId,
                'schedule_id' => $schedule->id,
                'date' => $request->date,
                'time' => $request->time,
            ]);

            return response()->json([
                'type' => 'SUCCESS',
                'message' => 'Your call scheduled successfully.',
                'redirect' => route('schedule-success', ['id' => encryptData($schedule->id)])
            ], 200);

        } catch (\Exception $e) {
            Log::error('scheduleSlot', ["message" => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'type' => 'ERROR',
                'message' => 'Something went wrong. Please try again later.'
            ], 200);
        }
    }
    
    public function scheduleSuccess(Request $request)
    {
        $id = $request->id ?? NULL;
        $scheduleId = decryptData($id);

        $schedule = ScheduleSlot::where('id', $scheduleId)->first();
        if (!$schedule) {
            return redirect()->route('front.home');
        }
        $user = WebinarRegistration::find($schedule->user_id);
        $cancelURL = route('schedule-cancel', ['id' => encryptData($schedule->id)]);
        return view('front.ScheduleSlot.success', compact('schedule', 'user', 'cancelURL'));
    }

    public function scheduleCancel(Request $request)
    {
        $id = $request->id ?? NULL;
        $scheduleId = decryptData($id);

        $schedule = ScheduleSlot::where('id', $scheduleId)->first();
        if (!$schedule) {
            return redirect()->route('home');
        }

        $user = WebinarRegistration::find($schedule->user_id);

        $this->sendScheduleToIndiakarobar($schedule, $user, 'cancel');

        ScheduleSlot::where('id', $scheduleId)->update([
            'is_deleted' => 1,
            'status' => ScheduleSlot::CANCELLED,
        ]);

        Log::info('Schedule cancelled', [
            'user_id' => $schedule->user_id,
            'schedule_id' => $scheduleId,
        ]);

        return redirect()->route('schedule-slot', ['id' => encryptData($schedule->user_id)]);
    }
    
    private function sendScheduleToIndiakarobar($schedule, $user, $action = 'create')
    {
        try {
            // Language mapping: Text to ID (1=Hindi, 2=English, 3=Gujarati)
            $languageMap = [
                'Hindi' => 1,
                'English' => 2,
                'Gujarati' => 3
            ];
            
            $languageText = $schedule->getLanguageTextAttribute();
            $languageId = $languageMap[$languageText] ?? 1;

            // Prepare data for API
            $data = [
                'company_code' => config('constant.COMPANY_CODE'),
                'company_name' => 'Wisemudra',
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'user_email' => $user->email,
                'user_mobile' => $user->mobile ?? null,
                'schedule_date' => $schedule->date,
                'schedule_time' => $schedule->time,
                'language' => $languageId,
                'status' => $schedule->status  // 1=Scheduled, 3=Cancelled
            ];

            // Send slot_id for update/cancel actions
            $slotId = null;
            if ($action === 'update' || $action === 'cancel') {
                $slotId = $schedule->id;
            }

            // Call API helper
            $response = sendScheduleCallData($data, $action, $slotId);

            if ($response && isset($response['status']) && $response['status'] === true) {
                Log::info('Schedule data sent to Indiakarobar successfully', [
                    'action' => $action,
                    'schedule_id' => $schedule->id,
                    'slot_id' => $slotId,
                    'status' => $schedule->status
                ]);
            } else {
                Log::error('Failed to send schedule data to Indiakarobar', [
                    'action' => $action,
                    'schedule_id' => $schedule->id,
                    'slot_id' => $slotId,
                    'response' => $response
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending schedule to Indiakarobar: ' . $e->getMessage());
        }
    }
}

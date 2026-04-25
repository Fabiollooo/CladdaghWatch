<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Mail\VolunteerConfirmation;
use App\Mail\VolunteerPostponed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ScheduleController extends Controller
{
    /**
     * Get all schedules
     */
    public function index()
    {
        return response()->json([
            'schedules' => Schedule::all(),
            'success' => true
        ]);
    }

    /**
     * Get single schedule
     */
    public function show($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }

        return response()->json([
            'schedule' => $schedule,
            'success' => true
        ]);
    }

    /**
     * Create new schedule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patrolDate' => 'required|date_format:Y-m-d H:i:s',
            'patrolDescription' => 'nullable|string|max:255',
            'SuperUserNr' => 'nullable|integer',
            'patrol_status' => 'required|integer',
            'postponed' => 'nullable|boolean',
            'volunteerIds' => 'nullable|array',
            'volunteerIds.*' => 'integer',
        ]);

        $volunteerIds = $validated['volunteerIds'] ?? [];
        unset($validated['volunteerIds']);
        $validated['patrolDescription'] = $validated['patrolDescription'] ?? 'Regular Patrol';

        // Reject past dates
        $dateOnly = substr($validated['patrolDate'], 0, 10);
        if ($dateOnly < now()->toDateString()) {
            return response()->json(['success' => false, 'message' => 'Cannot create a patrol on a past date.'], 422);
        }

        // Reject duplicate dates
        $exists = Schedule::whereRaw('DATE(patrolDate) = ?', [$dateOnly])->exists();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'A patrol already exists on this date.'], 422);
        }

        try {
            $schedule = Schedule::create($validated);

            // Attach selected volunteers to this patrol
            if (!empty($volunteerIds)) {
                $schedule->volunteers()->attach($volunteerIds);

                // Send confirmation emails to each volunteer
                $volunteers = User::whereIn('UserNr', $volunteerIds)->get();
                foreach ($volunteers as $volunteer) {
                    Mail::to($volunteer->email)->send(new VolunteerConfirmation($volunteer, $schedule));
                }
            }

            return response()->json([
                'schedule' => $schedule,
                'success' => true,
                'message' => 'Schedule created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update schedule
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }

        $validated = $request->validate([
            'patrolDate' => 'required|date_format:Y-m-d H:i:s',
            'patrolDescription' => 'nullable|string|max:255',
            'SuperUserNr' => 'nullable|integer',
            'patrol_status' => 'required|integer',
            'volunteerIds' => 'nullable|array',
            'volunteerIds.*' => 'integer',
        ]);

        $volunteerIds = $validated['volunteerIds'] ?? null;
        unset($validated['volunteerIds']);

        $oldStatus = (int) $schedule->patrol_status;
        $oldPatrolDate = (string) $schedule->patrolDate;
        $oldDateOnly = substr($oldPatrolDate, 0, 10);
        $newDateOnly = substr($validated['patrolDate'], 0, 10);

        // Reject past dates on update/postpone
        if ($newDateOnly < now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot move a patrol to a past date.'
            ], 422);
        }

        // Reject duplicate dates (allow current patrol to keep its own date)
        $exists = Schedule::whereRaw('DATE(patrolDate) = ?', [$newDateOnly])
            ->where('patrolNr', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A patrol already exists on that date.'
            ], 422);
        }

        try {
            Log::info('ScheduleController update called for ID: ' . $id);
            $schedule->update($validated);
            $mailFailures = 0;
            $statusNow = (int)$schedule->patrol_status;
            $isDateChanged = ($oldDateOnly !== $newDateOnly);
            $isPostponedUpdate = ($statusNow === 3) && (
                $oldStatus !== 3 ||
                $isDateChanged ||
                $request->boolean('postponed')
            );

            if ($isPostponedUpdate) {
                $schedule->loadMissing('volunteers');
                foreach ($schedule->volunteers as $volunteer) {
                    if (!empty($volunteer->email)) {
                        try {
                            Mail::to($volunteer->email)->send(
                                new VolunteerPostponed($volunteer, $schedule, $oldPatrolDate, (string)$schedule->patrolDate)
                            );
                        } catch (\Throwable $mailError) {
                            $mailFailures++;
                            Log::warning('Failed to send postponed notice to volunteer: ' . $volunteer->email . ' - ' . $mailError->getMessage());
                        }
                    }
                }

            }

            // Draft/Postponed should clear active assignments on that date
            if (in_array($statusNow, [0, 3], true) || $isPostponedUpdate) {
                $schedule->SuperUserNr = null;
                $schedule->save();
                $schedule->volunteers()->detach();
                // Ensure this update does not re-sync volunteers from payload
                $volunteerIds = null;
            }

            // Sync volunteers only if volunteerIds was explicitly provided
            if ($volunteerIds !== null) {
                Log::info('Syncing volunteers. New IDs: ' . json_encode($volunteerIds));
                
                // Get existing volunteer IDs before sync
                $existingVolunteerIds = $schedule->volunteers()->pluck('UserNr')->toArray();
                Log::info('Existing volunteers: ' . json_encode($existingVolunteerIds));
                
                // Sync the new volunteer IDs
                $schedule->volunteers()->sync($volunteerIds);
                
                // Detect newly added volunteers (IDs in $volunteerIds but not in $existingVolunteerIds)
                $newVolunteerIds = array_diff($volunteerIds, $existingVolunteerIds);
                Log::info('Newly added volunteers: ' . json_encode($newVolunteerIds));
                
                // Send confirmation emails only to newly added volunteers
                if (!empty($newVolunteerIds)) {
                    $newVolunteers = User::whereIn('UserNr', $newVolunteerIds)->get();
                    Log::info('Found ' . count($newVolunteers) . ' volunteers to email');
                    
                    foreach ($newVolunteers as $volunteer) {
                        Log::info('Sending email to: ' . $volunteer->email);
                        try {
                            Mail::to($volunteer->email)->send(new VolunteerConfirmation($volunteer, $schedule));
                            Log::info('Email sent to: ' . $volunteer->email);
                        } catch (\Throwable $mailError) {
                            $mailFailures++;
                            Log::warning('Failed to send volunteer confirmation email to: ' . $volunteer->email . ' - ' . $mailError->getMessage());
                        }
                    }
                }
            }

            $responseMessage = 'Schedule updated successfully';
            if ($mailFailures > 0) {
                $responseMessage .= ' (updated, but some email notifications could not be sent)';
            }

            return response()->json([
                'schedule' => $schedule,
                'success' => true,
                'message' => $responseMessage
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScheduleController update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete schedule
     */
    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }

        try {
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

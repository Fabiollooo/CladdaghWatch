<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Helpers\JwtHelper;
use App\Mail\VolunteerConfirmation;
use App\Mail\VolunteerPostponed;
use App\Mail\UserCreated;
use App\Mail\RequestVolunteer;
use App\Models\User;
use App\Models\Schedule;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgot']);
Route::post('/password/reset',  [AuthController::class, 'reset']);

// Schedule routes
Route::get('/schedules',      [ScheduleController::class, 'index']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::post('/schedules',     [ScheduleController::class, 'store']);
Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);

/**
 * PATCH /api/schedules/{id}/status
 * Updates only the patrol_status field (0=draft, 1=released, 2=finalized, 3=postponed).
 */
Route::patch('/schedules/{id}/status', function (\Illuminate\Http\Request $request, $id) {
    $schedule = \App\Models\Schedule::find($id);
    if (!$schedule) {
        return response()->json(['success' => false, 'message' => 'Patrol not found'], 404);
    }
    $oldStatus = (int) $schedule->patrol_status;
    $oldPatrolDate = (string) $schedule->patrolDate;

    $validated = $request->validate(['patrol_status' => 'required|integer|in:0,1,2,3']);
    $schedule->patrol_status = $validated['patrol_status'];

    $isPostponedTransition = ((int)$schedule->patrol_status === 3) && ($oldStatus !== 3);
    if ($isPostponedTransition) {
        $schedule->loadMissing('volunteers');
        foreach ($schedule->volunteers as $volunteer) {
            if (!empty($volunteer->email)) {
                try {
                    Mail::to($volunteer->email)->send(
                        new VolunteerPostponed($volunteer, $schedule, $oldPatrolDate, (string)$schedule->patrolDate)
                    );
                } catch (\Throwable $mailError) {
                    Log::warning('Failed to send postponed notice in status endpoint to: ' . $volunteer->email . ' - ' . $mailError->getMessage());
                }
            }
        }
    }

    // Draft/Postponed should clear active assignments for that patrol
    if (in_array((int)$schedule->patrol_status, [0, 3], true)) {
        $schedule->SuperUserNr = null;
    }

    $schedule->save();

    if (in_array((int)$schedule->patrol_status, [0, 3], true)) {
        $schedule->volunteers()->detach();
    }

    return response()->json(['success' => true, 'schedule' => $schedule]);
});

Route::post('/schedules/{id}/email-reminder', function (\Illuminate\Http\Request $request, $id) {
    $schedule = \App\Models\Schedule::find($id);
    if (!$schedule) {
        return response()->json(['success' => false, 'message' => 'Patrol not found'], 404);
    }

    $validated = $request->validate([
        'emails'  => 'required|array|min:1',
        'emails.*'=> 'required|email',
        'message' => 'nullable|string',
        'shortage' => 'required|integer|min:1',
    ]);

    $needVolunteers = (int)$validated['shortage'];
    $customMessage = $validated['message'] ?? '';
    $sent = 0;
    foreach (array_unique($validated['emails']) as $email) {
        try {
            Mail::to($email)->send(
                new RequestVolunteer($schedule, $customMessage, $needVolunteers)
            );
            $sent++;
        } catch (\Throwable $err) {
            Log::warning('Failed to send patrol reminder to ' . $email . ': ' . $err->getMessage());
        }
    }

    if ($sent === 0) {
        return response()->json(['success' => false, 'message' => 'No emails were sent (all failed).'], 500);
    }

    return response()->json(['success' => true, 'message' => $sent . ' email(s) sent.']);
});

// ── User management (admin) ───────────────────────────────────────────────────

// Active users list (for supervisor dropdowns etc.)
Route::get('/users', function () {
    $users = DB::table('cw_user')
        ->where('userEnabled', 1)
        ->select('UserNr', 'FirstName', 'LastName', 'email', 'userTypeNr')
        ->orderBy('LastName')
        ->orderBy('FirstName')
        ->get();
    return response()->json(['users' => $users, 'success' => true]);
});

Route::get('/users/volunteers', function () {
    $users = DB::table('cw_user')
        ->where('userEnabled', 1)
        ->where('userTypeNr', 3)
        ->select('UserNr', 'FirstName', 'LastName', 'email')
        ->orderBy('LastName')
        ->orderBy('FirstName')
        ->get();
    return response()->json(['users' => $users, 'success' => true]);
});

/**
 * POST /api/users
 * Create a new non-admin user.
 */
Route::post('/users', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'FirstName'  => 'required|string|max:100',
        'LastName'   => 'required|string|max:100',
        'email'      => 'required|email|max:255|unique:cw_user,email',
        'mobile'     => [
            'nullable',
            'string',
            'max:30',
            function ($attribute, $value, $fail) {
                if ($value === null || $value === '') {
                    return;
                }
                $digitsOnly = preg_replace('/\D/', '', $value);
                if (strlen($digitsOnly) < 7) {
                    $fail('Mobile number must contain at least 7 digits.');
                }
            },
        ],
        'userTypeNr' => 'required|integer|in:1,2,3,4',
        'password'   => 'required|string|min:6',
        'sendEmail'  => 'sometimes|boolean',
    ]);

    try {
        $user = \App\Models\User::create([
            'FirstName'   => $validated['FirstName'],
            'LastName'    => $validated['LastName'],
            'email'       => $validated['email'],
            'PassWord'    => Hash::make($validated['password']),
            'mobile'      => $validated['mobile'] ?? null,
            'userID'      => $validated['email'],
            'userEnabled' => 1,
            'userTypeNr'  => $validated['userTypeNr'],
        ]);

        if (!empty($validated['sendEmail']) && !empty($user->email)) {
            try {
                Mail::to($user->email)->send(
                    new UserCreated($user, $validated['password'])
                );
            } catch (\Throwable $mailError) {
                Log::warning('Failed to send new user password email to ' . $user->email . ': ' . $mailError->getMessage());
            }
        }
    } catch (\Illuminate\Database\QueryException $e) {
        if (str_contains($e->getMessage(), 'Data too long for column \'PassWord\'')) {
            return response()->json([
                'success' => false,
                'message' => 'Database schema needs update: please run migrations to expand the PassWord column.'
            ], 500);
        }

        throw $e;
    }

    return response()->json(['success' => true, 'user' => $user], 201);
});

/**
 * PATCH /api/users/{id}
 * Update a non-admin user's basic details.
 */
Route::patch('/users/{id}', function (\Illuminate\Http\Request $request, $id) {
    $user = \App\Models\User::whereNotIn('userTypeNr', [1])->where('UserNr', $id)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
    $validated = $request->validate([
        'FirstName'   => 'required|string|max:100',
        'LastName'    => 'required|string|max:100',
        'email'       => 'required|email|max:255',
        'mobile'      => [
            'nullable',
            'string',
            'max:30',
            function ($attribute, $value, $fail) {
                if ($value === null || $value === '') {
                    return;
                }
                $digitsOnly = preg_replace('/\D/', '', $value);
                if (strlen($digitsOnly) < 7) {
                    $fail('Mobile number must contain at least 7 digits.');
                }
            },
        ],
        'userTypeNr'  => 'required|integer|in:1,2,3,4',
    ]);

    // Check email uniqueness against other users
    $emailTaken = \App\Models\User::where('email', $validated['email'])
        ->where('UserNr', '!=', $id)
        ->exists();
    if ($emailTaken) {
        return response()->json(['success' => false, 'message' => 'That email is already in use by another user.'], 422);
    }

    $user->FirstName  = $validated['FirstName'];
    $user->LastName   = $validated['LastName'];
    $user->email      = $validated['email'];
    $user->mobile     = $validated['mobile'] ?? null;
    $user->userTypeNr = $validated['userTypeNr'];
    $user->save();

    return response()->json(['success' => true, 'user' => $user]);
});

/**
 * PATCH /api/users/{id}/toggle-status
 * Flip a user's userEnabled between 0 and 1.
 */
Route::patch('/users/{id}/toggle-status', function ($id) {
    $user = \App\Models\User::whereNotIn('userTypeNr', [1])->where('UserNr', $id)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
    $user->userEnabled = $user->userEnabled ? 0 : 1;
    $user->save();
    return response()->json(['success' => true, 'userEnabled' => $user->userEnabled]);
});

// ── Roster / Volunteering ─────────────────────────────────────────

/**
 * GET /api/roster/my
 * Returns patrolNr list for the currently authenticated user.
 */
Route::get('/roster/my', function () {
    $payload = JwtHelper::fromCookie();
    if (!$payload) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $rows = DB::table('cw_patrol_roster')
               ->where('volunteer_ID_Nr', $payload['sub'])
               ->pluck('patrolNr');
    return response()->json(['patrols' => $rows]);
});

/**
 * POST /api/roster
 * Body: { patrolNr: int }
 * Signs the authenticated user up for a patrol.
 */
Route::post('/roster', function (\Illuminate\Http\Request $request) {
    $payload = JwtHelper::fromCookie();

    // Also accept Bearer token (Authorization header)
    if (!$payload) {
        $auth = $request->header('Authorization', '');
        if (str_starts_with($auth, 'Bearer ')) {
            $payload = JwtHelper::decode(substr($auth, 7));
        }
    }

    if (!$payload) {
        return response()->json(['message' => 'Unauthenticated – please log in'], 401);
    }

    $patrolNr = (int) $request->input('patrolNr');
    if (!$patrolNr) {
        return response()->json(['message' => 'patrolNr is required'], 422);
    }

    // Verify patrol exists and is released (status 1)
    $patrol = DB::table('cw_patrol_schedule')
                ->where('patrolNr', $patrolNr)
                ->where('patrol_status', 1)
                ->first();

    if (!$patrol) {
        return response()->json(['message' => 'Patrol not found or not released for rostering'], 404);
    }

    // Check if already signed up
    $exists = DB::table('cw_patrol_roster')
                ->where('volunteer_ID_Nr', $payload['sub'])
                ->where('patrolNr', $patrolNr)
                ->exists();

    if ($exists) {
        return response()->json(['message' => 'Already signed up for this patrol', 'alreadySignedUp' => true]);
    }

    DB::table('cw_patrol_roster')->insert([
        'volunteer_ID_Nr' => $payload['sub'],
        'patrolNr'        => $patrolNr,
    ]);

    // Send confirmation email to the volunteer
    try {
        $volunteer = User::find($payload['sub']);
        $schedule = Schedule::find($patrolNr);
        
        if ($volunteer && $schedule) {
            Mail::to($volunteer->email)->send(new VolunteerConfirmation($volunteer, $schedule));
        }
    } catch (\Exception $e) {
        // Log the error but don't fail the API call
        Log::error('Failed to send volunteer confirmation email: ' . $e->getMessage());
    }

    return response()->json(['message' => 'Successfully signed up for patrol #' . $patrolNr, 'success' => true]);
});

/**
 * DELETE /api/roster/{patrolNr}
 * Removes the authenticated user from a patrol.
 */
Route::delete('/roster/{patrolNr}', function (int $patrolNr) {
    $payload = JwtHelper::fromCookie()
             ?? (function() use (&$request) {
                    $auth = request()->header('Authorization', '');
                    return str_starts_with($auth, 'Bearer ')
                        ? JwtHelper::decode(substr($auth, 7)) : null;
                })();

    if (!$payload) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    DB::table('cw_patrol_roster')
      ->where('volunteer_ID_Nr', $payload['sub'])
      ->where('patrolNr', $patrolNr)
      ->delete();

    return response()->json(['message' => 'Removed from patrol #' . $patrolNr, 'success' => true]);
});

/**
 * POST /api/profile/update
 * Body (user): { first_name, last_name } plus optional email/mobile/role/enabled/user_id
 * Admins/managers may supply a user_id and update additional profile fields.
 */
Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
    $payload = JwtHelper::fromCookie();
    if (!$payload) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $currentId   = (int)($payload['sub'] ?? 0);
    $currentUser = \App\Models\User::find($currentId);
    if (!$currentUser) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    // determine target user - default to self, override if admin/manager
    $targetId = $currentId;
    if ($request->filled('user_id') && in_array($currentUser->userTypeNr, [1,2])) {
        $targetId = (int)$request->input('user_id');
    }
    $user = \App\Models\User::find($targetId);
    if (!$user) {
        return response()->json(['message' => 'Target user not found.'], 404);
    }

    $firstName = trim($request->input('first_name', ''));
    $lastName  = trim($request->input('last_name', ''));
    if (empty($firstName) || empty($lastName)) {
        return response()->json(['message' => 'First name and last name are required.'], 422);
    }

    $user->FirstName = $firstName;
    $user->LastName  = $lastName;

    // if current user is admin/manager, allow updating extra fields
    if (in_array($currentUser->userTypeNr, [1,2])) {
        if ($request->filled('email')) {
            $user->email = trim($request->input('email'));
        }
        if ($request->filled('mobile')) {
            $user->mobile = trim($request->input('mobile')) ?: null;
        }
        if ($request->filled('userTypeNr')) {
            $user->userTypeNr = (int)$request->input('userTypeNr');
        }
        if ($request->has('userEnabled')) {
            $user->userEnabled = $request->input('userEnabled') ? 1 : 0;
        }
    }

    $user->save();

    return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
});

/**
 * GET /api/roster/counts?ids=1,2,3
 * Returns the volunteer count per patrol for the given patrol IDs.
 */
Route::get('/roster/counts', function (\Illuminate\Http\Request $request) {
    $ids = array_filter(array_map('intval', explode(',', $request->input('ids', ''))));
    if (empty($ids)) {
        return response()->json(['counts' => []]);
    }
    $rows = DB::table('cw_patrol_roster')
               ->whereIn('patrolNr', $ids)
               ->selectRaw('patrolNr, COUNT(*) as cnt')
               ->groupBy('patrolNr')
               ->get();
    $counts = [];
    foreach ($rows as $row) {
        $counts[$row->patrolNr] = (int)$row->cnt;
    }
    return response()->json(['counts' => $counts]);
});
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtHelper;

Route::get('/', function () {
    // Unauthenticated users go to login
    if (!JwtHelper::isLoggedIn()) {
        return redirect('/login');
    }
    return redirect('/home');
});


Route::get('/home', function () {
    ob_start();
    require resource_path('views/home.php');
    return ob_get_clean();
})->middleware('jwt');

Route::get('/volunteer', function () {
    ob_start();
    require resource_path('views/volunteer.php');
    return ob_get_clean();
})->middleware('jwt');

Route::get('/supervisor/calendar', function () {
    ob_start();
    require resource_path('views/supervisor/calendar.php');
    return ob_get_clean();
})->middleware('jwt');

// Redirect /adminHome  /home (adminHome is retired)
Route::get('/adminHome', function () {
    return redirect('/home');
})->middleware('jwt');

// Redirect /about /home#about (merged into home)
Route::get('/about', function () {
    return redirect('/home#about');
})->middleware('jwt');

Route::get('/dashboard', function () {
    ob_start();
    require resource_path('views/home.php');
    return ob_get_clean();
})->middleware('jwt');

Route::get('/schedules', function () {
    $schedules = \App\Models\Schedule::orderBy('patrolDate')->get();
    ob_start();
    require resource_path('views/admin/schedules.php');
    return ob_get_clean();
})->middleware('jwt');

// Patrol Management Routes
Route::get('/patrolManagement', function () {
    ob_start();
    require resource_path('views/admin/patrolManagement.php');
    return ob_get_clean();
})->name('patrol.management')->middleware('jwt');

Route::get('/patrolCreate', function () {
    ob_start();
    require resource_path('views/admin/patrolCreate.php');
    return ob_get_clean();
})->name('patrol.create')->middleware('jwt');

Route::get('/patrolEdit/{id}', function ($id) {
    ob_start();
    require resource_path('views/admin/patrolEdit.php');
    return ob_get_clean();
})->name('patrol.edit')->middleware('jwt');

// Roster Resourcing Routes
Route::get('/rosterResourcing', function () {
    ob_start();
    require resource_path('views/admin/rosterResourcing.php');
    return ob_get_clean();
})->middleware('jwt');

// API proxy routes for patrol management (web layer, non-JSON)
Route::prefix('api')->group(function () {
    Route::get('/patrols', function () {
        return response()->json([]);
    })->name('api.patrols.list');

    Route::post('/patrols', function () {
        return response()->json(['message' => 'Created'], 201);
    })->name('api.patrols.create');

    Route::put('/patrols/{id}', function ($id) {
        return response()->json(['message' => 'Updated']);
    })->name('api.patrols.update');

    Route::delete('/patrols/{id}', function ($id) {
        return response()->json(['message' => 'Deleted']);
    })->name('api.patrols.delete');
});

// Auth Routes
Route::get('/login', function () {
    ob_start();
    require resource_path('views/auth/login.php');
    return ob_get_clean();
});

Route::get('/register', function () {
    return redirect('/login');
});

Route::post('/register', function (\Illuminate\Http\Request $request) {
})->middleware('jwt');

Route::get('/reset-password', function () {
    return redirect('/login');
});

Route::get('/forgot-password', function () {
    return redirect('/login');
});

Route::get('/logout', function () {
    // Clear JWT and legacy cookies
    $cookieNames = ['cw_jwt', 'cw_role', 'remember_email', 'remember_expiry'];
    $past = time() - 3600;
    foreach ($cookieNames as $name) {
        setcookie($name, '', $past, '/');
        setcookie($name, '', $past, '/', '', false, true);
    }
    return redirect('/login');
});


Route::get('/manageUsers', function (\Illuminate\Http\Request $request) {
    // Exclude admins (userTypeNr = 1)
    $query = \App\Models\User::whereNotIn('userTypeNr', [1]);

    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            $q->where('FirstName', 'like', "%{$search}%")
              ->orWhere('LastName', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $users = $query->orderBy('FirstName')
                   ->paginate(10)
                   ->withQueryString();

    return view('admin.manageUsers', compact('users', 'search'));
})->middleware('jwt');


// Profile routes
Route::get('/profile', function () {
    ob_start();
    require resource_path('views/profile.php');
    return ob_get_clean();
});

Route::get('/profile/edit', function () {
    ob_start();
    require resource_path('views/edit-profile.php');
    return ob_get_clean();
});

// Admin Schedules
Route::get('/adminSchedules', function (\Illuminate\Http\Request $request) {
    $selectedDateParam = $request->input('date', '');
    $schedules = \App\Models\Schedule::orderBy('patrolDate')->get();
    return view('admin.schedules', compact('schedules'));
});

// Test database connection route
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        $dbConnected = true;
        $dbMessage   = "Database connection successful!";
        $dbName      = DB::connection()->getDatabaseName();
        $schedules   = \App\Models\Schedule::all();
        $scheduleCount = $schedules->count();
    } catch (\Exception $e) {
        $dbConnected = false;
        $dbMessage   = "Database connection failed: " . $e->getMessage();
        $schedules   = collect([]);
        $scheduleCount = 0;
        $dbName = null;
    }
    return view('test-db', compact('dbConnected', 'dbMessage', 'schedules', 'scheduleCount', 'dbName'));
});



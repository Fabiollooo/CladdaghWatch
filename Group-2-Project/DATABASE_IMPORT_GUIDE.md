# Database Import Guide - Getting Your Schedule Data

Your application is fully configured and ready to display patrol schedules. The code is working correctly—you just need to import the database structure and sample data into your XAMPP MySQL installation.

## Current Status

✅ **What's Ready:**
- Laravel routes are configured correctly
- Schedule model is mapped to the `cw_patrol_schedule` table
- Schedules view displays events dynamically
- Database connection settings are correct in `.env`

❌ **What's Missing:**
- The `cwp_roster` MySQL database doesn't exist yet on your system
- The `cw_patrol_schedule` table hasn't been created
- Sample data hasn't been imported

## Quick Start (5 Minutes)

### Step 1: Start XAMPP MySQL

1. Open **XAMPP Control Panel**
2. Click **Start** next to **MySQL**
3. Wait for it to show "Running" (you should see a green indicator)

### Step 2: Import the Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. You should see the phpMyAdmin home page
3. Click the **"Import"** tab at the top
4. Under "File to import", click **"Choose File"**
5. Navigate to: `database/sql/cwp_roster_v01.sql`
6. Select it and click **Open**
7. Scroll down and click **"Go"** or **"Import"**
8. Wait a few seconds for the import to complete
9. You should see a success message: "Import has been executed successfully"

### Step 3: Verify the Import

1. Check the left sidebar in phpMyAdmin
2. Look for **`cwp_roster`** in the database list
3. Click on it to expand
4. You should see the **`cw_patrol_schedule`** table
5. Click on **`cw_patrol_schedule`** table and then the **"Browse"** tab
6. You should see sample patrol schedule data

### Step 4: Test the Connection

1. In your browser, go to: `http://localhost:8000/test-db`
2. You should see:
   - ✓ "Database connection successful!"
   - A list of schedules from the database
   - Something like "Sample Patrol 1", "Sample Patrol 2", etc.

### Step 5: View Your Schedules

1. Go to: `http://localhost:8000/schedules`
2. You should now see patrol schedules displayed on the calendar
3. Events will be positioned based on their patrol times

## If Something Goes Wrong

### Database Import Failed

**Problem:** "Error: [table name] already exists"
- **Solution:** In phpMyAdmin, delete the `cwp_roster` database first, then import again
  1. Click on `cwp_roster` in the left sidebar
  2. Scroll down and click "Delete database"
  3. Confirm
  4. Then follow Step 2 above again

**Problem:** Import page shows "No file was uploaded"
- **Solution:** Make sure the file path `database/sql/cwp_roster_v01.sql` exists
  - In your VS Code workspace, you should see `database/sql/cwp_roster_v01.sql`
  - If it doesn't exist, ask for help in VS Code

**Problem:** File selection doesn't show the SQL file
- **Alternative:** Use the "Location of the text file" option instead
  - In the Import dialog, select "Location of the text file"
  - Type the full path: `C:\xampp\htdocs\K00290683\Group-2-Project\database\sql\cwp_roster_v01.sql`
  - Click "Go"

### MySQL Not Running

**Problem:** phpMyAdmin won't load or shows "Cannot connect"
- **Solution:** Start MySQL in XAMPP
  1. Open XAMPP Control Panel
  2. Find MySQL in the list
  3. Click the red "Start" button
  4. Wait for it to turn green and show "Running"
  5. Try phpMyAdmin again

**Problem:** "Error: Can't connect to MySQL server on 'localhost'"
- **Solution:** Check your MySQL service
  - If XAMPP won't start MySQL, try:
    1. Close XAMPP Control Panel
    2. Restart your computer
    3. Open XAMPP Control Panel again
    4. Try starting MySQL
  - Or try restarting the service:
    1. Click "Services" in XAMPP Control Panel
    2. Find MySQL and click "Stop"
    3. Wait 2 seconds, then click "Start"

### Schedules Still Not Showing

**Problem:** You see "No Schedules Found" message
- **Checklist:**
  - ✓ MySQL is running (green indicator in XAMPP)
  - ✓ phpMyAdmin shows `cwp_roster` database with `cw_patrol_schedule` table
  - ✓ `/test-db` page shows "Database connection successful"
  - ✓ `/test-db` page lists at least one schedule

**Problem:** `/test-db` shows connection error
- **Solution:** Check database connection
  1. In phpMyAdmin, click on `cwp_roster` database
  2. You should see the `cw_patrol_schedule` table
  3. If not, repeat the import process
  4. Make sure `.env` has correct database settings:
     ```
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=cwp_roster
     DB_USERNAME=root
     DB_PASSWORD=
     ```

## What the SQL Import Does

The `cwp_roster_v01.sql` file:
- ✓ Creates the `cwp_roster` database
- ✓ Creates the `cw_patrol_schedule` table with columns:
  - `patrolNr` - Patrol ID (primary key)
  - `patrolDate` - Date and time of patrol
  - `patrolDescription` - Description of patrol
  - `SuperUserNr` - User ID
  - `patrol_status` - Status of patrol
  - `start_time` - Patrol start time
  - `end_time` - Patrol end time
- ✓ Inserts 5 sample patrol records with realistic data
- ✓ All sample patrols are scheduled for upcoming dates

## Testing Each Component

### Test 1: Database Connection
**URL:** `http://localhost:8000/test-db`

**Expected Output:**
```
✓ Database connection successful!

Patrol Schedules:
- Patrol #1001: Claddagh - North Patrol (Feb 17, 2025 09:00)
- Patrol #1002: Claddagh - South Patrol (Feb 18, 2025 14:00)
...
```

### Test 2: Schedules Display
**URL:** `http://localhost:8000/schedules`

**Expected Output:**
- Calendar view with a week (Monday-Friday)
- Patrol events displayed as colored boxes on the calendar
- Events positioned based on their patrol times
- Mini calendar on the right showing the current month
- "Upcoming Patrols" list on the right sidebar

## Next Steps After Import

Once your schedules are displaying:

1. **Add Your Own Patrols:** Click "Create Schedule" button to add patrol schedules manually
2. **Edit Patrols:** Click on any patrol event to open the edit dialog
3. **View Details:** Patrol information is formatted as: `Time - Description (Status)`
4. **Check Status:** Each patrol shows its current status

## File Locations

| File | Location |
|------|----------|
| Database SQL Dump | `database/sql/cwp_roster_v01.sql` |
| Schedule Model | `app/Models/Schedule.php` |
| Schedules Route | `routes/web.php` (line 24-26) |
| Schedules View | `resources/views/schedules.php` |
| Database Config | `.env` |
| Test Page | `http://localhost:8000/test-db` |

## Troubleshooting Checklist

- [ ] MySQL is running in XAMPP (green indicator)
- [ ] phpMyAdmin loads: `http://localhost/phpmyadmin`
- [ ] Database `cwp_roster` exists in phpMyAdmin
- [ ] Table `cw_patrol_schedule` exists in `cwp_roster` database
- [ ] Table has at least 1 row of data
- [ ] Test page works: `http://localhost:8000/test-db`
- [ ] Schedules page works: `http://localhost:8000/schedules`
- [ ] Patrol events display on the calendar

## Questions?

If you get stuck:
1. Check the test page: `http://localhost:8000/test-db`
2. Verify MySQL is running
3. Verify the SQL file was imported successfully
4. Check that `cwp_roster` database appears in phpMyAdmin

The system is ready—you just need to import the database file!

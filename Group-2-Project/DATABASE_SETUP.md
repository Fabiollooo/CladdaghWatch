# Database Setup Instructions

## Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service

## Step 2: Import the Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on "New" in the left sidebar to create a new database
3. Or if `cwp_roster` database already exists, select it
4. Click on the "Import" tab
5. Click "Choose File" and select:
   - File: `database/sql/cwp_roster_v01.sql`
6. Click "Go" at the bottom to import the database

## Step 3: Add Time Fields to the Database
1. In phpMyAdmin, make sure `cwp_roster` database is selected
2. Click on the "SQL" tab at the top
3. Copy and paste the contents of `database/sql/add_time_fields_to_patrol_schedule.sql`
4. Click "Go" to execute the SQL

   OR
   
   Click on "Import" tab and import the file: `database/sql/add_time_fields_to_patrol_schedule.sql`

## Step 4: Verify Database Connection
1. Make sure your `.env` file has these settings:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cwp_roster
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. Open your browser and go to: `http://localhost:8000/test-db`
   
   This will show you:
   - Database connection status
   - Number of schedules in the database
   - A table showing all schedule records

## Step 5: View the Schedules Page
Once the database connection is successful, go to: `http://localhost:8000/schedules`

You should now see your patrol schedules displayed in the calendar!

## Troubleshooting

### If you see "Database connection failed":
1. Check that MySQL is running in XAMPP
2. Verify the database name is exactly `cwp_roster`
3. Check that the username is `root` and password is empty
4. Make sure the database was imported correctly

### If you see "No schedule records found":
1. The database import may not have included data
2. Re-import the `cwp_roster_v01.sql` file
3. Check in phpMyAdmin if the `cw_patrol_schedule` table has data

### If schedules don't show time:
1. Make sure you ran the `add_time_fields_to_patrol_schedule.sql` script
2. Check in phpMyAdmin if `cw_patrol_schedule` table has `start_time` and `end_time` columns

## Current Database Structure

The `cw_patrol_schedule` table should have these columns:
- `patrolNr` (Primary Key, Auto Increment)
- `patrolDate` (DATE)
- `patrolDescription` (VARCHAR)
- `SuperUserNr` (INT)
- `patrol_status` (INT)
- `start_time` (TIME) - Added by migration script
- `end_time` (TIME) - Added by migration script

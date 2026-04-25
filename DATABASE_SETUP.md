# CladdaghWatch Database Setup Guide

## Database Overview

CladdaghWatch uses MySQL/MariaDB as its primary database. The database schema is designed to manage patrol schedules, volunteer assignments, and user management for the water safety patrol system.

---

## Prerequisites

- **MySQL** 8.0+ or **MariaDB** 10.4+
- **XAMPP** (recommended for local development) with MySQL running
- **Composer** installed for running migrations
- Access to command line/terminal

---

## Database Configuration

### 1. Environment Variables

Update your `.env` file in the `Group-2-Project/` directory with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cwp_roster
DB_USERNAME=root
DB_PASSWORD=
```

**Default XAMPP Configuration:**
- **Host**: 127.0.0.1
- **Port**: 3306
- **Username**: root
- **Password**: (empty)

---

## Initial Database Setup

### Option A: Using SQL Import Script (Recommended)

1. **Start XAMPP MySQL Service**
   - Open XAMPP Control Panel
   - Click "Start" next to MySQL
   - Verify it shows "Running"

2. **Create Database**
   ```bash
   mysql -u root -e "CREATE DATABASE cwp_roster;"
   ```

3. **Import Database Schema**
   ```bash
   mysql -u root cwp_roster < Group-2-Project/database/sql/cwp_roster_v02.sql
   ```

4. **Import Time Fields Migration** (if using older schema)
   ```bash
   mysql -u root cwp_roster < Group-2-Project/database/sql/add_time_fields_to_patrol_schedule.sql
   ```

### Option B: Using Laravel Migrations

1. **Start XAMPP MySQL Service**

2. **Navigate to Project**
   ```bash
   cd Group-2-Project
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```

4. **Seed Sample Data** (Optional)
   ```bash
   php artisan db:seed
   ```

---

## Database Schema

### Core Tables

#### **`cw_user`** - User Accounts
Stores user credentials and profile information.

| Column | Type | Notes |
|--------|------|-------|
| UserNr | INT (PK) | Auto-increment primary key |
| FirstName | VARCHAR(255) | User's first name |
| LastName | VARCHAR(255) | User's last name |
| email | VARCHAR(255) | Unique email address |
| PassWord | VARCHAR(255) | Bcrypt hashed password |
| mobile | VARCHAR(20) | Mobile phone number |
| userID | VARCHAR(255) | Unique user identifier |
| userEnabled | TINYINT | 0 = disabled, 1 = enabled |
| userTypeNr | INT (FK) | References `cw_usertype` |
| idcounty | INT (FK) | References `cw_county` |
| created_at | TIMESTAMP | Account creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**User Types (userTypeNr):**
- 1: Admin
- 2: Manager
- 3: Volunteer
- 4: Supervisor
- 99: Unknown

---

#### **`cw_patrol_schedule`** - Patrol Schedules
Stores scheduled patrol dates and details.

| Column | Type | Notes |
|--------|------|-------|
| patrolNr | INT (PK) | Auto-increment primary key |
| patrolDate | DATE | Date of the patrol |
| patrolDescription | VARCHAR(500) | Description of the patrol |
| SuperUserNr | INT (FK) | References patrol supervisor (cw_user) |
| patrol_status | INT (FK) | References `cw_patrol_status` |
| start_time | TIME | Patrol start time |
| end_time | TIME | Patrol end time |
| max_volunteers | INT | Maximum volunteers allowed (nullable) |
| created_at | TIMESTAMP | Schedule creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

**Patrol Status Values:**
- 0: Not Released (Draft)
- 1: Released for Rostering
- 2: Suspended
- 3: Postponed
- 4: Roster Finalized

---

#### **`cw_patrol_roster`** - Volunteer Assignments
Junction table linking volunteers to patrols (many-to-many relationship).

| Column | Type | Notes |
|--------|------|-------|
| volunteer_ID_Nr | INT (FK) | References volunteer (cw_user) |
| patrolNr | INT (FK) | References patrol (cw_patrol_schedule) |

**Composite Primary Key**: (volunteer_ID_Nr, patrolNr)

---

#### **`cw_usertype`** - User Type Reference
Defines available user roles.

| Column | Type | Notes |
|--------|------|-------|
| userTypeNr | INT (PK) | Role identifier |
| userTypeName | VARCHAR(255) | Role name (ADMIN, MANAGER, etc.) |

---

#### **`cw_patrol_status`** - Patrol Status Reference
Defines available patrol statuses.

| Column | Type | Notes |
|--------|------|-------|
| patrol_status | INT (PK) | Status identifier (0-4) |
| status_name | VARCHAR(255) | Status name |
| status_description | TEXT | Status description |

---

#### **`cw_county`** - Geographic Reference
Lists Irish counties and regions.

| Column | Type | Notes |
|--------|------|-------|
| idcounty | INT (PK) | County identifier |
| county_name | VARCHAR(255) | County name |

---

## Database Relationships

```
cw_user
├── One-to-Many: cw_patrol_schedule (as SuperUserNr)
└── Many-to-Many: cw_patrol_schedule (via cw_patrol_roster)

cw_patrol_schedule
├── Many-to-One: cw_user (SuperUserNr)
├── Many-to-One: cw_patrol_status (patrol_status)
└── Many-to-Many: cw_user (via cw_patrol_roster)

cw_patrol_roster
├── Many-to-One: cw_user (volunteer_ID_Nr)
└── Many-to-One: cw_patrol_schedule (patrolNr)

cw_user
└── Many-to-One: cw_usertype (userTypeNr)
└── Many-to-One: cw_county (idcounty)
```

---

## Migrations

All database schema changes are version controlled through Laravel migrations located in `Group-2-Project/database/migrations/`.

### Available Migrations

| Migration | Purpose |
|-----------|---------|
| `0001_01_01_000000_create_users_table.php` | Create base users table |
| `0001_01_01_000001_create_cache_table.php` | Create cache table |
| `0001_01_01_000002_create_jobs_table.php` | Create jobs queue table |
| `2026_02_10_000000_create_cw_patrol_schedule_table.php` | Create patrol schedule table |
| `2026_03_03_000000_add_max_volunteers_to_patrol_schedule.php` | Add volunteer limit field |
| `2026_03_25_000001_expand_cw_user_password_column.php` | Expand password column |

### Running Migrations

```bash
cd Group-2-Project

# Run all pending migrations
php artisan migrate

# Run migrations with output
php artisan migrate --verbose

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Refresh migrations (reset + run all)
php artisan migrate:refresh

# Force migrate in production
php artisan migrate --force
```

---

## Sample Data

The database includes sample data for testing and development.

### Sample Users

**Manager Account** (for testing admin features)
- **Email**: `gerryguinane@claddaghwatch.ie`
- **Password**: `Password1`
- **Role**: Manager
- **County**: Galway

### Sample Patrols

- 25 scheduled patrols
- **Date Range**: November 2025 - April 2026
- **Status**: Mix of draft, released, and finalized patrols
- **Volunteers**: 10 sample volunteers assigned to various patrols

### Seeding Sample Data

To populate sample data during migration:

```bash
cd Group-2-Project

# Seed all seeders
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=DatabaseSeeder

# Force seed (no confirmation)
php artisan db:seed --force
```

---

## Backup & Restore

### Backup Database

```bash
# Export entire database to SQL file
mysqldump -u root cwp_roster > backup_cwp_roster.sql

# Export with date timestamp
mysqldump -u root cwp_roster > backup_cwp_roster_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Database

```bash
# Restore from backup
mysql -u root cwp_roster < backup_cwp_roster.sql

# Create new database and restore
mysql -u root -e "CREATE DATABASE cwp_roster;"
mysql -u root cwp_roster < backup_cwp_roster.sql
```

---

## Troubleshooting

### Database Connection Issues

**Error: "SQLSTATE[HY000] [2002] No such file or directory"**
- Ensure MySQL is running in XAMPP
- Check `.env` database credentials match XAMPP configuration
- Try connecting manually: `mysql -u root`

**Error: "Access denied for user 'root'@'localhost'"**
- Verify username and password in `.env`
- Reset MySQL password if needed
- Check XAMPP MySQL credentials

### Migration Issues

**Error: "The database does not exist"**
```bash
mysql -u root -e "CREATE DATABASE cwp_roster;"
php artisan migrate
```

**Error: "SQLSTATE[42S01]: Table already exists"**
```bash
# Reset migrations and re-run
php artisan migrate:refresh
```

### Data Issues

**Verify Database Connection**
```bash
cd Group-2-Project
php artisan tinker

# In Tinker shell:
>>> DB::connection()->getPdo();
```

**Check Table Existence**
```sql
SHOW TABLES;
DESCRIBE cw_user;
DESCRIBE cw_patrol_schedule;
DESCRIBE cw_patrol_roster;
```

**Count Records**
```sql
SELECT COUNT(*) FROM cw_user;
SELECT COUNT(*) FROM cw_patrol_schedule;
SELECT COUNT(*) FROM cw_patrol_roster;
```

---

## Security Considerations

- ✅ Passwords stored using bcrypt hashing
- ✅ SQL injection prevention through Eloquent ORM
- ✅ Database credentials in `.env` (not committed to git)
- ✅ User role-based access control
- ✅ Email validation on user accounts
- ✅ Password minimum requirements enforced

---

## Performance Optimization

### Indexing Strategy

The following columns are indexed for faster queries:

- `cw_user.email` - Unique index for login queries
- `cw_user.userTypeNr` - For role-based filtering
- `cw_patrol_schedule.patrolDate` - For date range queries
- `cw_patrol_schedule.SuperUserNr` - For supervisor lookups
- `cw_patrol_roster.patrolNr` - For volunteer assignment queries

### Query Optimization Tips

```php
// Use eager loading to prevent N+1 queries
Schedule::with('supervisor', 'volunteers')->get();

// Use select() to fetch only needed columns
User::select('UserNr', 'FirstName', 'email')->get();

// Use chunk() for large datasets
User::chunk(100, function ($users) {
    // Process users
});
```

---

## Related Documentation

- [README.md](README.md) - Project overview and setup
- [DATABASE_IMPORT_GUIDE.md](Group-2-Project/DATABASE_IMPORT_GUIDE.md) - Detailed import procedures
- [Laravel Migrations Docs](https://laravel.com/docs/migrations)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

## Support

For database-related issues or questions:

- 📧 **Email**: support@claddaghwatch.ie
- 📍 **Project**: CladdaghWatch, Galway, Ireland

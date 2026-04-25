
# CladdaghWatch Patrol Roster Management System

<p align="center">
  <strong>A web-based patrol rostering system for Galway's water safety volunteers</strong>
</p>

<p align="center">
  <a href="#about"><strong>About</strong></a> •
  <a href="#features"><strong>Features</strong></a> •
  <a href="#tech-stack"><strong>Tech Stack</strong></a> •
  <a href="#getting-started"><strong>Getting Started</strong></a> •
  <a href="#project-structure"><strong>Project Structure</strong></a> •
  <a href="#api-documentation"><strong>API</strong></a>
</p>

---

## About

**CladdaghWatch** is a modern web application designed to streamline patrol scheduling and volunteer management for CladdaghWatch, a Galway-based voluntary organization dedicated to water safety and suicide prevention through river and waterway patrols.

The system enables operations managers to create patrol schedules, supervisors to lead teams, and volunteers to sign up for available shifts—all through an intuitive, role-based interface.

📍 **Organization**: [CladdaghWatch.ie](http://claddaghwatch.ie)

---

## Features

### 👥 Role-Based Access Control
- **Admin**: Full system control, user management, global oversight
- **Manager**: Create patrol schedules, release rosters, manage volunteer availability
- **Supervisor**: Lead patrol teams, coordinate volunteer assignments
- **Volunteer**: View available patrols, sign up for shifts, receive confirmations

### 📅 Intelligent Schedule Management
- Create and manage patrol schedules with date validation (prevents past dates)
- Prevent duplicate patrols on the same date
- Set time slots, descriptions, and supervisor assignments
- Schedule workflow: Draft → Released → Finalized/Postponed

### 👤 Volunteer Management
- User registration and role-based onboarding
- View available patrol dates and sign up for shifts
- Automatic confirmation emails upon assignment
- Roster management with volunteer limit controls

### 📧 Email Notifications
- Volunteer assignment confirmations
- Patrol postponement alerts
- Volunteer recruitment reminders
- User registration notifications

### 🔐 Secure Authentication
- JWT-based authentication (HS256 HMAC-SHA256)
- "Remember me" functionality (7-day token retention)
- Password hashing with bcrypt (legacy SHA-256 support)
- Session security with httpOnly cookies

### 📱 Responsive Design
- Mobile-first UI built with Tailwind CSS
- Modern, accessible interface for all user roles
- Real-time volunteer roster updates

---

## Tech Stack

### Backend
- **Framework**: [Laravel 12.0](https://laravel.com) — PHP 8.2+
- **Database**: MySQL/MariaDB
- **ORM**: Eloquent
- **Email**: Laravel Mail with Mailable classes
- **Authentication**: Custom JWT implementation
- **Testing**: [Pest 3.8](https://pestphp.com)
- **Server**: Apache (XAMPP compatible)

### Frontend
- **Build Tool**: [Vite 7.0.7](https://vitejs.dev)
- **CSS**: [Tailwind CSS 4.0.0](https://tailwindcss.com)
- **HTTP Client**: [Axios 1.11.0](https://axios-http.com)
- **Runtime**: Node.js

### Infrastructure
- **Development**: XAMPP (Apache + MySQL)
- **Package Management**: Composer (PHP), npm (JavaScript)
- **Deployment**: Docker-ready with Laravel Sail

---

## Getting Started

### Prerequisites
- **PHP** 8.2 or higher
- **Node.js** 18+ with npm
- **Composer** 2.0+
- **MySQL** 8.0+ (or MariaDB)
- **XAMPP** (recommended for local development)
- **Git**

### Installation

#### 1. Clone the Repository
```bash
git clone https://github.com/your-org/claddaghwatch.git
cd claddaghwatch
```

#### 2. Install PHP Dependencies
```bash
composer install
```

#### 3. Install JavaScript Dependencies
```bash
npm install
```

#### 4. Configure Environment
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cwp_roster
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. Database Setup
```bash
# Start XAMPP MySQL service
# Then import the database schema:

# Option A: Using SQL script
mysql -u root cwp_roster < database/sql/cwp_roster_v02.sql

# Option B: Using Artisan
php artisan migrate
php artisan db:seed
```

For detailed database setup, see [DATABASE_SETUP.md](GROUP-2-PROJECT/DATABASE_SETUP.md) and [DATABASE_IMPORT_GUIDE.md](GROUP-2-PROJECT/DATABASE_IMPORT_GUIDE.md).

#### 6. Build Frontend Assets
```bash
# Development mode with hot reload
npm run dev

# Production build
npm run build
```

### Running the Application

**Terminal 1** - Start the Laravel server:
```bash
php artisan serve
```
Server runs at: `http://localhost:8000`

**Terminal 2** - Start the frontend build server:
```bash
npm run dev
```

**Optional** - Start queue listener (for email processing):
```bash
php artisan queue:listen
```

### Test Login Credentials
Use the following credentials to test the application (included in sample data):

- **Email**: `gerryguinane@claddaghwatch.ie`
- **Password**: `Password1`
- **Role**: Manager

---

## Project Structure

```
claddaghwatch/
├── Group-2-Project/            # Main Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/    # Request handlers
│   │   │   └── Middleware/     # JWT, security, auth checks
│   │   ├── Models/             # Eloquent models (User, Schedule)
│   │   ├── Mail/               # Email notification classes
│   │   └── Helpers/            # JwtHelper for token management
│   ├── routes/
│   │   ├── api.php             # REST API endpoints
│   │   ├── web.php             # Web application routes
│   │   └── console.php         # Artisan commands
│   ├── database/
│   │   ├── migrations/         # Database schema changes
│   │   ├── seeders/            # Sample data seeding
│   │   └── sql/                # Raw SQL scripts
│   ├── resources/
│   │   ├── views/              # Blade templates
│   │   ├── js/                 # Vue/JavaScript components
│   │   └── css/                # Tailwind/CSS stylesheets
│   ├── public/
│   │   ├── css/                # Compiled stylesheets
│   │   ├── js/                 # Compiled JavaScript
│   │   └── images/             # Static assets
│   ├── config/                 # Configuration files
│   ├── storage/                # File uploads, logs, cache
│   └── tests/                  # Unit and feature tests
├── README.md                   # This file
├── CladdaghWatch.md            # Requirements documentation
└── Sprint-2.md                 # Sprint 2 information
```

---

## Database Schema

### Core Tables

**`cw_user`** - User accounts
- User credentials and profile information
- Role assignment (admin, manager, volunteer, supervisor)
- County/location information

**`cw_patrol_schedule`** - Patrol schedules
- Patrol date, description, time slots
- Supervisor assignment
- Status tracking (Draft, Released, Postponed, Finalized)

**`cw_patrol_roster`** - Volunteer assignments
- Junction table linking volunteers to patrols
- Tracks which volunteers are assigned to each patrol

**`cw_usertype`** - Role reference table
- 1: Admin, 2: Manager, 3: Volunteer, 4: Supervisor, 99: Unknown

**`cw_patrol_status`** - Status reference table
- 0: Not Released
- 1: Released for Rostering
- 2: Suspended
- 3: Postponed
- 4: Roster Finalized

For complete schema details, see the migration files in `Group-2-Project/database/migrations/`.

---

## API Documentation

### Authentication

All API endpoints (except `/api/register` and `/api/login`) require a valid JWT token in the request cookie.

**Login**
```http
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password",
  "rememberMe": false
}

Response: 200 OK
{
  "message": "Login successful",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

**Register**
```http
POST /api/register
Content-Type: application/json

{
  "firstName": "John",
  "lastName": "Doe",
  "email": "john@example.com",
  "password": "secure_password",
  "userTypeNr": 3
}
```

### Schedule Endpoints

**Get All Schedules**
```http
GET /api/schedules
```

**Create Schedule**
```http
POST /api/schedules
Content-Type: application/json

{
  "patrolDate": "2026-04-30",
  "patrolDescription": "Regular scheduled patrol",
  "superUserNr": 5,
  "start_time": "18:00",
  "end_time": "20:00"
}
```

**Update Schedule**
```http
PUT /api/schedules/{id}
```

**Delete Schedule**
```http
DELETE /api/schedules/{id}
```

**Update Schedule Status**
```http
PATCH /api/schedules/{id}/status
Content-Type: application/json

{
  "status": 1
}
```

**Send Email Reminder**
```http
POST /api/schedules/{id}/email-reminder
```

### User Endpoints

**List All Active Users**
```http
GET /api/users
```

**List Volunteers Only**
```http
GET /api/users/volunteers
```

**Create User**
```http
POST /api/users
```

For complete API documentation, see the route definitions in `Group-2-Project/routes/api.php`.

---

## Email Templates

The application sends automated emails for:

- **VolunteerConfirmation**: When assigned to a patrol
- **VolunteerPostponed**: When a patrol is postponed
- **UserCreated**: Upon user registration
- **RequestVolunteer**: Recruitment reminders

Email templates are located in `Group-2-Project/resources/mail/`.

---

## Security

- ✅ JWT token validation on all protected routes
- ✅ SQL injection prevention middleware
- ✅ Security headers (CORS, X-Frame-Options, etc.)
- ✅ Password hashing with bcrypt
- ✅ Session validation and expiry checks
- ✅ Email validation on registration

---

## Development & Testing

### Run Tests
```bash
cd Group-2-Project

# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=ScheduleTest

# Run with code coverage
php artisan test --coverage
```

### Code Formatting
```bash
# Format code with Pint
php artisan pint
```

### Debug Mode
```bash
# Enable debug mode in .env
APP_DEBUG=true

# Access debugging info at /debug-bar (if enabled)
```

---

## Current Development Status

### Sprint 2 Focus
- Mobile-responsive UI improvements
- Calendar-based schedule interface
- Volunteer page layout optimization
- Volunteer limit implementation

### Known Issues
- Volunteer view mobile layout requires redesign
- Schedules management UI needs improvement

See [Sprint-2.md](Group-2-Project/Sprint-2.md) for detailed sprint information.

---

## Troubleshooting

### Database Connection Issues
```bash
# Verify MySQL is running
# Check .env database credentials
# Ensure database 'cwp_roster' exists
cd Group-2-Project
php artisan migrate --force
```

### Missing Assets
```bash
# Rebuild frontend assets
npm run dev
# or
npm run build
```

### JWT Token Errors
```bash
# Clear application cache
php artisan cache:clear
php artisan config:cache

# Regenerate app key
php artisan key:generate
```

---

## Project Roadmap

**Phase 1 (Current)**: MVP with core rostering functionality ✅

**Phase 2 (Planned)**:
- Advanced scheduling features
- Mobile app
- SMS notifications
- Volunteer performance analytics
- Improved calendar UI

---

## Contributing

We welcome contributions! Please follow these steps:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/your-feature`)
3. **Commit** your changes (`git commit -am 'Add new feature'`)
4. **Push** to the branch (`git push origin feature/your-feature`)
5. **Submit** a Pull Request

### Development Guidelines
- Follow PSR-12 PHP coding standards
- Write tests for new features
- Update documentation as needed
- Use descriptive commit messages

---

## License

This project is proprietary software for CladdaghWatch. See [LICENSE](LICENSE) file for details.

---

## Support & Contact

For issues, questions, or feature requests:

- 📧 **Email**: support@claddaghwatch.ie
- 🌐 **Website**: [claddaghwatch.ie](http://claddaghwatch.ie)
- 📍 **Organization**: CladdaghWatch, Galway, Ireland

---

## Acknowledgments

- **Built with**: [Laravel](https://laravel.com), [Vite](https://vitejs.dev), [Tailwind CSS](https://tailwindcss.com)
- **Developed for**: CladdaghWatch volunteer organization
- **Purpose**: Supporting water safety and suicide prevention initiatives in Galway

---

<p align="center">
  <strong>CladdaghWatch: Watching Over Galway's Waters 🌊</strong>
</p>

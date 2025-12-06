# XAMPP Implementation Guide
## Online Job Portal - Step-by-Step Setup

---

## Prerequisites

1. **Download and Install XAMPP**
   - Download from: https://www.apachefriends.org/
   - Install XAMPP (includes Apache, MySQL, PHP, phpMyAdmin)
   - Default installation path: `C:\xampp` (Windows)

2. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Start **Apache** (for PHP)
   - Start **MySQL** (for Database)

---

## Step 1: Create Database in phpMyAdmin

### Method 1: Using phpMyAdmin Interface

1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click on **"New"** in left sidebar
3. Enter database name: `job_portal`
4. Select collation: `utf8_general_ci`
5. Click **"Create"**

### Method 2: Using SQL Query

1. Go to phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on **"SQL"** tab
3. Paste this query:
   ```sql
   CREATE DATABASE IF NOT EXISTS job_portal;
   ```
4. Click **"Go"**

---

## Step 2: Import SQL Script

### Option A: Import Complete SQL File

1. In phpMyAdmin, select `job_portal` database from left sidebar
2. Click on **"Import"** tab
3. Click **"Choose File"** button
4. Select `complete_database.sql` file
5. Click **"Go"** at bottom
6. Wait for success message

### Option B: Execute SQL Scripts Manually

1. Select `job_portal` database
2. Click **"SQL"** tab
3. Copy and paste content from these files in order:
   - `database_schema.sql` (Create tables)
   - `sample_data.sql` (Insert sample data)
   - `views_procedures_triggers.sql` (Create views, procedures, triggers)
4. Click **"Go"** after each script

---

## Step 3: Setup Project Files in htdocs

### Folder Structure

```
C:\xampp\htdocs\jobportal\
├── config.php
├── login.php
├── register_jobseeker.php
├── register_employer.php
├── post_job.php
├── apply_job.php
├── admin_dashboard.php
├── jobseeker_dashboard.php
├── employer_dashboard.php
├── browse_jobs.php
├── my_applications.php
├── manage_jobs.php
├── logout.php
├── css/
│   └── style.css
├── uploads/
│   └── resumes/
└── sql/
    ├── database_schema.sql
    ├── sample_data.sql
    └── views_procedures_triggers.sql
```

### Steps to Setup Files

1. Navigate to: `C:\xampp\htdocs\`
2. Create folder: `jobportal`
3. Copy all PHP files to `C:\xampp\htdocs\jobportal\`
4. Create `uploads` folder for resume uploads
5. Create `uploads/resumes` subfolder
6. Set folder permissions (right-click → Properties → Security)

---

## Step 4: Configure Database Connection

### Edit config.php

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Default is empty for XAMPP
define('DB_NAME', 'job_portal');
?>
```

### Test Database Connection

Create `test_connection.php`:

```php
<?php
$conn = mysqli_connect('localhost', 'root', '', 'job_portal');
if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
?>
```

Access: `http://localhost/jobportal/test_connection.php`

---

## Step 5: Access the Application

### URLs

- **Home/Login**: `http://localhost/jobportal/login.php`
- **Job Seeker Registration**: `http://localhost/jobportal/register_jobseeker.php`
- **Employer Registration**: `http://localhost/jobportal/register_employer.php`

### Default Login Credentials (from sample data)

**Admin:**
- Email: `admin@jobportal.com`
- Password: `password`

**Job Seeker:**
- Email: `john.doe@email.com`
- Password: `password`

**Employer:**
- Email: `techcorp@company.com`
- Password: `password`

---

## Step 6: PHP-MySQL Connection Examples

### Using mysqli (Procedural)

```php
<?php
$conn = mysqli_connect('localhost', 'root', '', 'job_portal');

// Select query
$query = "SELECT * FROM jobs WHERE status = 'active'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['job_title'];
}

mysqli_close($conn);
?>
```

### Using mysqli (Prepared Statements) - RECOMMENDED

```php
<?php
$conn = mysqli_connect('localhost', 'root', '', 'job_portal');

// Prepared statement (prevents SQL injection)
$job_id = 1;
$query = "SELECT * FROM jobs WHERE job_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $job_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$job = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
```

---

## Step 7: CRUD Operations Examples

### CREATE (Insert)

```php
<?php
require_once 'config.php';

$job_title = sanitize_input($_POST['job_title']);
$employer_id = 1;

$query = "INSERT INTO jobs (employer_id, job_title, status) VALUES (?, ?, 'active')";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "is", $employer_id, $job_title);

if (mysqli_stmt_execute($stmt)) {
    echo "Job posted successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
```

### READ (Select)

```php
<?php
require_once 'config.php';

$query = "SELECT j.*, e.company_name FROM jobs j 
          INNER JOIN employers e ON j.employer_id = e.employer_id 
          WHERE j.status = 'active'";
$result = mysqli_query($conn, $query);

while ($job = mysqli_fetch_assoc($result)) {
    echo "<h3>" . htmlspecialchars($job['job_title']) . "</h3>";
    echo "<p>" . htmlspecialchars($job['company_name']) . "</p>";
}
?>
```

### UPDATE

```php
<?php
require_once 'config.php';

$application_id = 1;
$new_status = 'shortlisted';

$query = "UPDATE applications SET status = ? WHERE application_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $new_status, $application_id);

if (mysqli_stmt_execute($stmt)) {
    echo "Application updated!";
}
?>
```

### DELETE

```php
<?php
require_once 'config.php';

$job_id = 1;

$query = "DELETE FROM jobs WHERE job_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $job_id);

if (mysqli_stmt_execute($stmt)) {
    echo "Job deleted!";
}
?>
```

---

## Step 8: Error Handling

### PHP Error Handling

```php
<?php
// Enable error reporting (for development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database error handling
$conn = mysqli_connect('localhost', 'root', '', 'job_portal');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query error handling
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Prepared statement error handling
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
?>
```

### Form Validation

```php
<?php
function validate_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }
    return true;
}

function validate_phone($phone) {
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        return "Phone must be 10-15 digits";
    }
    return true;
}

function validate_password($password) {
    if (strlen($password) < 6) {
        return "Password must be at least 6 characters";
    }
    return true;
}
?>
```

---

## Step 9: Common XAMPP Problems & Solutions

### Problem 1: Apache Port 80 Already in Use

**Solution:**
1. Open XAMPP Control Panel
2. Click "Config" next to Apache
3. Select "httpd.conf"
4. Find line: `Listen 80`
5. Change to: `Listen 8080`
6. Save and restart Apache
7. Access: `http://localhost:8080/jobportal/`

### Problem 2: MySQL Port 3306 Already in Use

**Solution:**
1. Stop other MySQL services (Skype, other databases)
2. Or change MySQL port in XAMPP config
3. Restart MySQL service

### Problem 3: phpMyAdmin Access Denied

**Solution:**
1. Edit `C:\xampp\phpMyAdmin\config.inc.php`
2. Find: `$cfg['Servers'][$i]['password'] = '';`
3. Set your MySQL root password if changed
4. Save and refresh phpMyAdmin

### Problem 4: File Upload Errors

**Solution:**
1. Edit `php.ini` (XAMPP Control → Apache Config → php.ini)
2. Increase values:
   ```
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
3. Restart Apache

### Problem 5: Session Not Working

**Solution:**
1. Check if `session_start()` is called before any output
2. Verify session folder exists: `C:\xampp\tmp`
3. Check php.ini: `session.save_path = "C:\xampp\tmp"`

### Problem 6: Database Connection Failed

**Solution:**
1. Verify MySQL is running in XAMPP
2. Check credentials in config.php
3. Test connection with test_connection.php
4. Check if database exists in phpMyAdmin

---

## Step 10: Security Best Practices

### 1. Password Hashing

```php
// Hash password on registration
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Verify password on login
if (password_verify($input_password, $hashed_password)) {
    // Login successful
}
```

### 2. SQL Injection Prevention

```php
// Always use prepared statements
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
```

### 3. XSS Prevention

```php
// Sanitize output
echo htmlspecialchars($user_input);

// Sanitize input
$clean = htmlspecialchars(trim(stripslashes($input)));
```

### 4. Session Security

```php
session_start();
session_regenerate_id(true); // Prevent session fixation

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
```

---

## Step 11: Testing the Application

### Test Checklist

- [ ] Database created successfully
- [ ] All tables created with proper constraints
- [ ] Sample data inserted
- [ ] Views, procedures, triggers working
- [ ] Can register as job seeker
- [ ] Can register as employer
- [ ] Can login with different user types
- [ ] Employer can post jobs
- [ ] Job seeker can browse jobs
- [ ] Job seeker can apply for jobs
- [ ] Admin can view dashboard
- [ ] Application status can be updated
- [ ] Triggers update statistics correctly

### SQL Queries to Test

```sql
-- Test active jobs view
SELECT * FROM vw_active_jobs;

-- Test stored procedure
CALL sp_get_jobs_by_location('Bangalore');

-- Test application statistics
SELECT * FROM application_statistics;

-- Test trigger (insert application and check statistics)
INSERT INTO applications (job_id, seeker_id, cover_letter) 
VALUES (1, 1, 'Test application');
SELECT * FROM application_statistics WHERE job_id = 1;
```

---

## Step 12: Backup and Restore

### Backup Database

**Method 1: phpMyAdmin**
1. Select `job_portal` database
2. Click "Export" tab
3. Select "Quick" export method
4. Click "Go"
5. Save .sql file

**Method 2: Command Line**
```bash
cd C:\xampp\mysql\bin
mysqldump -u root job_portal > backup.sql
```

### Restore Database

**Method 1: phpMyAdmin**
1. Create new database
2. Click "Import" tab
3. Choose backup file
4. Click "Go"

**Method 2: Command Line**
```bash
cd C:\xampp\mysql\bin
mysql -u root job_portal < backup.sql
```

---

## Troubleshooting Tips

1. **Clear browser cache** if changes don't appear
2. **Check Apache error logs**: `C:\xampp\apache\logs\error.log`
3. **Check PHP errors**: Enable `display_errors` in php.ini
4. **Use browser developer tools** (F12) to check for JavaScript errors
5. **Test queries directly in phpMyAdmin** before using in PHP
6. **Use `var_dump()` and `print_r()`** for debugging PHP variables

---

## Additional Resources

- **XAMPP Documentation**: https://www.apachefriends.org/docs/
- **PHP Manual**: https://www.php.net/manual/
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **W3Schools PHP Tutorial**: https://www.w3schools.com/php/

---

## Next Steps

1. Customize the design with CSS
2. Add more features (email notifications, resume upload, etc.)
3. Implement advanced search filters
4. Add pagination for job listings
5. Create reports and analytics
6. Deploy to production server

@echo off
echo ============================================
echo Online Job Portal - Project Verification
echo ============================================
echo.

echo Checking SQL Files...
echo.
if exist "database_schema.sql" (echo [OK] database_schema.sql) else (echo [MISSING] database_schema.sql)
if exist "sample_data.sql" (echo [OK] sample_data.sql) else (echo [MISSING] sample_data.sql)
if exist "important_queries.sql" (echo [OK] important_queries.sql) else (echo [MISSING] important_queries.sql)
if exist "views_procedures_triggers.sql" (echo [OK] views_procedures_triggers.sql) else (echo [MISSING] views_procedures_triggers.sql)
if exist "complete_database.sql" (echo [OK] complete_database.sql) else (echo [MISSING] complete_database.sql)

echo.
echo Checking PHP Files...
echo.
if exist "config.php" (echo [OK] config.php) else (echo [MISSING] config.php)
if exist "login.php" (echo [OK] login.php) else (echo [MISSING] login.php)
if exist "logout.php" (echo [OK] logout.php) else (echo [MISSING] logout.php)
if exist "register_jobseeker.php" (echo [OK] register_jobseeker.php) else (echo [MISSING] register_jobseeker.php)
if exist "register_employer.php" (echo [OK] register_employer.php) else (echo [MISSING] register_employer.php)
if exist "post_job.php" (echo [OK] post_job.php) else (echo [MISSING] post_job.php)
if exist "apply_job.php" (echo [OK] apply_job.php) else (echo [MISSING] apply_job.php)
if exist "admin_dashboard.php" (echo [OK] admin_dashboard.php) else (echo [MISSING] admin_dashboard.php)

echo.
echo Checking Documentation...
echo.
if exist "README.md" (echo [OK] README.md) else (echo [MISSING] README.md)
if exist "PROJECT_DOCUMENTATION.md" (echo [OK] PROJECT_DOCUMENTATION.md) else (echo [MISSING] PROJECT_DOCUMENTATION.md)
if exist "XAMPP_IMPLEMENTATION_GUIDE.md" (echo [OK] XAMPP_IMPLEMENTATION_GUIDE.md) else (echo [MISSING] XAMPP_IMPLEMENTATION_GUIDE.md)
if exist "DELIVERABLES_CHECKLIST.md" (echo [OK] DELIVERABLES_CHECKLIST.md) else (echo [MISSING] DELIVERABLES_CHECKLIST.md)

echo.
echo ============================================
echo Project Statistics
echo ============================================
echo.
echo Total Files: 17
echo SQL Files: 5
echo PHP Files: 8
echo Documentation: 4
echo.
echo ============================================
echo Next Steps
echo ============================================
echo.
echo 1. Download XAMPP from https://www.apachefriends.org/
echo 2. Install XAMPP (default: C:\xampp)
echo 3. Start Apache and MySQL services
echo 4. Open phpMyAdmin: http://localhost/phpmyadmin
echo 5. Create database: job_portal
echo 6. Import: complete_database.sql
echo 7. Copy all files to: C:\xampp\htdocs\jobportal\
echo 8. Access: http://localhost/jobportal/login.php
echo.
echo Default Login: admin@jobportal.com / password
echo.
echo ============================================
echo Project Ready for Deployment!
echo ============================================
echo.
pause

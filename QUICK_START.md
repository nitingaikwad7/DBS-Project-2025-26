# 🚀 Quick Start Guide
## Online Job Portal - Get Started in 5 Minutes!

---

## ✅ What You Have

**Total Files: 22**
- 5 SQL files (complete database)
- 12 PHP files (full application)
- 5 Documentation files

---

## 📦 Step 1: Install XAMPP

1. Download XAMPP: https://www.apachefriends.org/
2. Install to `C:\xampp` (Windows) or `/opt/lampp` (Linux)
3. Open XAMPP Control Panel
4. Click **Start** on Apache
5. Click **Start** on MySQL

---

## 💾 Step 2: Create Database

1. Open browser: `http://localhost/phpmyadmin`
2. Click **"New"** in left sidebar
3. Database name: `job_portal`
4. Click **"Create"**
5. Select `job_portal` database
6. Click **"Import"** tab
7. Choose file: `complete_database.sql`
8. Click **"Go"**
9. Wait for success message ✅

---

## 📁 Step 3: Copy Files

1. Navigate to: `C:\xampp\htdocs\`
2. Create folder: `jobportal`
3. Copy ALL project files to: `C:\xampp\htdocs\jobportal\`
4. Create folder: `C:\xampp\htdocs\jobportal\uploads\resumes\`

---

## 🌐 Step 4: Access Application

Open your browser and visit:

### 🏠 Home Page
```
http://localhost/jobportal/index.php
```

### 🔍 Browse Jobs
```
http://localhost/jobportal/browse_jobs.php
```

### 🔐 Login
```
http://localhost/jobportal/login.php
```

---

## 👤 Step 5: Test Login

### Admin Account
- **Email:** admin@jobportal.com
- **Password:** password
- **Access:** Admin dashboard with full statistics

### Job Seeker Account
- **Email:** john.doe@email.com
- **Password:** password
- **Access:** Browse jobs, apply, track applications

### Employer Account
- **Email:** techcorp@company.com
- **Password:** password
- **Access:** Post jobs, view applications

---

## 🎯 What You Can Do

### As Job Seeker:
1. ✅ Register new account
2. ✅ Browse available jobs
3. ✅ Search by keyword, location, type
4. ✅ Apply for jobs with cover letter
5. ✅ Track application status
6. ✅ View dashboard with statistics

### As Employer:
1. ✅ Register company account
2. ✅ Post new job openings
3. ✅ Manage job postings
4. ✅ View applications
5. ✅ Update application status
6. ✅ View company dashboard

### As Admin:
1. ✅ View all users
2. ✅ Monitor all jobs
3. ✅ Track all applications
4. ✅ View system statistics
5. ✅ Generate reports

---

## 📊 Database Features

### Tables (7)
- users
- job_seekers
- employers
- jobs
- applications
- admin
- application_statistics

### Views (4)
- vw_active_jobs
- vw_application_summary
- vw_employer_statistics
- vw_jobseeker_profile

### Stored Procedures (6)
- sp_get_jobs_by_location
- sp_apply_for_job
- sp_update_application_status
- sp_get_job_application_stats
- sp_search_jobs
- sp_close_expired_jobs

### Triggers (5)
- Auto-update application statistics
- Prevent duplicate applications
- Initialize job statistics

---

## 🔧 Troubleshooting

### Problem: Apache won't start
**Solution:** Port 80 is in use
1. Open XAMPP Control Panel
2. Click "Config" → "httpd.conf"
3. Change `Listen 80` to `Listen 8080`
4. Restart Apache
5. Access: `http://localhost:8080/jobportal/`

### Problem: MySQL won't start
**Solution:** Port 3306 is in use
1. Stop other MySQL services
2. Close Skype (uses port 80/443)
3. Restart MySQL in XAMPP

### Problem: Database connection failed
**Solution:** Check config.php
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Empty for XAMPP
define('DB_NAME', 'job_portal');
```

### Problem: Blank white page
**Solution:** Enable error display
1. Open `php.ini` in XAMPP
2. Set: `display_errors = On`
3. Restart Apache
4. Check error logs: `C:\xampp\apache\logs\error.log`

---

## 📱 Page Navigation

```
index.php (Home)
    ├── browse_jobs.php (Search & Browse)
    ├── login.php (Login)
    │   ├── jobseeker_dashboard.php (Job Seeker)
    │   │   ├── apply_job.php
    │   │   └── my_applications.php
    │   ├── employer_dashboard.php (Employer)
    │   │   ├── post_job.php
    │   │   └── manage_jobs.php
    │   └── admin_dashboard.php (Admin)
    ├── register_jobseeker.php
    └── register_employer.php
```

---

## ✨ Key Features

### 🔒 Security
- Password hashing (bcrypt)
- SQL injection prevention
- XSS protection
- Session management

### 📊 Database
- Normalized schema (3NF)
- Foreign key constraints
- CHECK constraints
- Triggers for automation

### 🎨 User Interface
- Responsive design
- Modern gradient colors
- Clean card layouts
- Easy navigation

### 🔍 Search & Filter
- Keyword search
- Location filter
- Job type filter
- Salary range

---

## 📖 Documentation Files

1. **README.md** - Project overview
2. **PROJECT_DOCUMENTATION.md** - Complete documentation
3. **XAMPP_IMPLEMENTATION_GUIDE.md** - Detailed setup
4. **DELIVERABLES_CHECKLIST.md** - All deliverables
5. **QUICK_START.md** - This file

---

## 🎓 For Viva Preparation

### Key Points to Remember:

1. **Database Design**
   - 7 tables with proper normalization
   - Foreign keys ensure referential integrity
   - Triggers automate application tracking

2. **SQL Features**
   - 15+ complex queries
   - INNER JOIN and LEFT JOIN
   - Subqueries and aggregations
   - Views for data abstraction

3. **Security**
   - Passwords hashed with bcrypt
   - Prepared statements prevent SQL injection
   - Input validation on all forms

4. **Application Flow**
   - Job Seeker: Register → Browse → Apply → Track
   - Employer: Register → Post Jobs → Review Applications
   - Admin: Monitor → Manage → Generate Reports

---

## 📞 Need Help?

1. Check `XAMPP_IMPLEMENTATION_GUIDE.md` for detailed instructions
2. Review `PROJECT_DOCUMENTATION.md` for complete documentation
3. Test SQL queries in phpMyAdmin SQL tab
4. Check Apache error logs for PHP errors

---

## ✅ Success Checklist

- [ ] XAMPP installed and running
- [ ] Database `job_portal` created
- [ ] `complete_database.sql` imported successfully
- [ ] All files copied to `htdocs/jobportal/`
- [ ] Can access `http://localhost/jobportal/index.php`
- [ ] Can login with test credentials
- [ ] Can browse jobs
- [ ] Can register new users
- [ ] All pages loading properly

---

## 🎉 You're All Set!

Your Online Job Portal is now running!

**Home Page:** http://localhost/jobportal/index.php

**Test Login:** admin@jobportal.com / password

Enjoy exploring the application! 🚀

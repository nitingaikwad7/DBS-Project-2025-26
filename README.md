# Online Job Portal - Database Systems Mini-Project

A comprehensive web-based job portal application built with PHP, MySQL, and XAMPP, demonstrating advanced database concepts including normalization, constraints, views, stored procedures, and triggers.

---

## 📋 Project Overview

The Online Job Portal connects job seekers with employers through a centralized platform. It features role-based access control, automated application tracking, and comprehensive database management.

### Key Features

- ✅ User authentication with role-based access (Job Seeker, Employer, Admin)
- ✅ Job posting and management by employers
- ✅ Job search and application by job seekers
- ✅ Admin dashboard with statistics
- ✅ Automated application tracking with triggers
- ✅ Advanced SQL queries with joins and subqueries
- ✅ Stored procedures for business logic
- ✅ Views for simplified data access
- ✅ Complete CRUD operations
- ✅ Security features (password hashing, SQL injection prevention)

---

## 🗂️ Project Structure

```
jobportal/
├── 📄 README.md                          # This file
├── 📄 PROJECT_DOCUMENTATION.md           # Complete project documentation
├── 📄 XAMPP_IMPLEMENTATION_GUIDE.md      # Step-by-step setup guide
│
├── 📁 SQL Files
│   ├── database_schema.sql               # Table creation scripts
│   ├── sample_data.sql                   # Sample data insertion
│   ├── important_queries.sql             # 15+ important SQL queries
│   ├── views_procedures_triggers.sql     # Views, procedures, triggers
│   └── complete_database.sql             # Complete database script
│
├── 📁 PHP Files
│   ├── index.php                         # Home page
│   ├── config.php                        # Database configuration
│   ├── login.php                         # User login
│   ├── logout.php                        # User logout
│   ├── register_jobseeker.php            # Job seeker registration
│   ├── register_employer.php             # Employer registration
│   ├── browse_jobs.php                   # Browse and search jobs
│   ├── post_job.php                      # Job posting form
│   ├── apply_job.php                     # Job application form
│   ├── jobseeker_dashboard.php           # Job seeker dashboard
│   ├── employer_dashboard.php            # Employer dashboard
│   └── admin_dashboard.php               # Admin panel
│
└── 📁 uploads/
    └── resumes/                          # Resume upload directory
```

---

## 🚀 Quick Start Guide

### Prerequisites

- XAMPP (Apache + MySQL + PHP)
- Web browser
- Text editor (optional)

### Installation Steps

1. **Install XAMPP**
   ```
   Download from: https://www.apachefriends.org/
   Install to: C:\xampp (Windows) or /opt/lampp (Linux)
   ```

2. **Start Services**
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL

3. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Click "New" → Enter name: `job_portal`
   - Click "Create"

4. **Import Database**
   - Select `job_portal` database
   - Click "Import" tab
   - Choose file: `complete_database.sql`
   - Click "Go"

5. **Setup Files**
   - Copy all files to: `C:\xampp\htdocs\jobportal\`
   - Create folder: `uploads/resumes/`

6. **Access Application**
   - Open browser: `http://localhost/jobportal/login.php`

---

## 🔐 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@jobportal.com | password |
| Job Seeker | john.doe@email.com | password |
| Employer | techcorp@company.com | password |

---

## 📊 Database Schema

### Tables (7)

1. **users** - User authentication and roles
2. **job_seekers** - Job seeker profiles
3. **employers** - Employer/company profiles
4. **jobs** - Job postings
5. **applications** - Job applications
6. **admin** - Admin users
7. **application_statistics** - Application tracking

### Relationships

- Users → Job Seekers (1:1)
- Users → Employers (1:1)
- Users → Admin (1:1)
- Employers → Jobs (1:N)
- Jobs → Applications (1:N)
- Job Seekers → Applications (1:N)

---

## 🔧 Database Features

### Views (4)
- `vw_active_jobs` - Active jobs with company details
- `vw_application_summary` - Complete application information
- `vw_employer_statistics` - Employer dashboard stats
- `vw_jobseeker_profile` - Job seeker profiles

### Stored Procedures (6)
- `sp_get_jobs_by_location` - Search jobs by location
- `sp_apply_for_job` - Submit application with validation
- `sp_update_application_status` - Update application status
- `sp_get_job_application_stats` - Get statistics
- `sp_search_jobs` - Advanced job search
- `sp_close_expired_jobs` - Close expired jobs

### Triggers (5)
- `trg_after_application_insert` - Update stats on new application
- `trg_after_application_update` - Update stats on status change
- `trg_after_application_delete` - Update stats on deletion
- `trg_before_application_insert` - Prevent duplicate applications
- `trg_after_job_insert` - Initialize statistics

---

## 📝 Important SQL Queries

The project includes 15+ important queries demonstrating:

- ✅ SELECT with WHERE, ORDER BY
- ✅ INNER JOIN and LEFT JOIN
- ✅ Subqueries (nested queries)
- ✅ Aggregate functions (COUNT, SUM, AVG)
- ✅ GROUP BY and HAVING
- ✅ Date functions
- ✅ Pattern matching (LIKE)
- ✅ Complex multi-table joins

See `important_queries.sql` for complete examples.

---

## 🛡️ Security Features

1. **Password Security**
   - Bcrypt hashing (password_hash)
   - Secure password verification

2. **SQL Injection Prevention**
   - Prepared statements
   - Input sanitization

3. **XSS Prevention**
   - Output escaping (htmlspecialchars)
   - Input validation

4. **Session Security**
   - Session regeneration
   - Role-based access control

---

## 🧪 Testing

### Test Database Connection
```php
<?php
$conn = mysqli_connect('localhost', 'root', '', 'job_portal');
if ($conn) {
    echo "Connected successfully!";
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
?>
```

### Test Queries in phpMyAdmin
```sql
-- View all active jobs
SELECT * FROM vw_active_jobs;

-- Test stored procedure
CALL sp_get_jobs_by_location('Bangalore');

-- Check application statistics
SELECT * FROM application_statistics;
```

---

## 📚 Documentation Files

1. **PROJECT_DOCUMENTATION.md**
   - Complete project description
   - ER diagram (text format)
   - Database schema details
   - All SQL queries explained
   - Viva preparation points

2. **XAMPP_IMPLEMENTATION_GUIDE.md**
   - Step-by-step installation
   - Configuration instructions
   - CRUD examples
   - Error handling
   - Troubleshooting guide

3. **SQL Files**
   - `database_schema.sql` - Table structures
   - `sample_data.sql` - Test data
   - `important_queries.sql` - Query examples
   - `views_procedures_triggers.sql` - Advanced features
   - `complete_database.sql` - All-in-one script

---

## 🎯 Learning Outcomes

This project demonstrates:

- Database design and normalization (3NF)
- SQL DDL (CREATE, ALTER, DROP)
- SQL DML (INSERT, UPDATE, DELETE, SELECT)
- Complex queries with joins and subqueries
- Views for data abstraction
- Stored procedures for business logic
- Triggers for automated actions
- PHP-MySQL integration
- Web application security
- XAMPP server configuration

---

## 🐛 Troubleshooting

### Common Issues

**Problem: Apache won't start**
- Solution: Change port from 80 to 8080 in httpd.conf

**Problem: MySQL won't start**
- Solution: Stop conflicting services (Skype, other MySQL)

**Problem: Database connection failed**
- Solution: Check credentials in config.php
- Verify MySQL is running in XAMPP

**Problem: Blank page (white screen)**
- Solution: Enable error_reporting in php.ini
- Check Apache error logs

See `XAMPP_IMPLEMENTATION_GUIDE.md` for detailed solutions.

---

## 📈 Future Enhancements

- [ ] Resume upload functionality
- [ ] Email notifications
- [ ] Advanced search filters
- [ ] Charts and analytics
- [ ] Messaging system
- [ ] Rating and reviews
- [ ] Payment integration
- [ ] Mobile application
- [ ] AI-based job recommendations

---

## 📞 Support

For detailed implementation help:
1. Read `XAMPP_IMPLEMENTATION_GUIDE.md`
2. Check `PROJECT_DOCUMENTATION.md`
3. Review error logs in `C:\xampp\apache\logs\`
4. Test queries in phpMyAdmin

---

## 📄 License

This project is created for educational purposes as a Database Systems mini-project.

---

## ✅ Project Status

**Status:** Complete and Ready for Submission

All components implemented:
- ✅ Database schema with constraints
- ✅ Sample data
- ✅ Important SQL queries
- ✅ Views, procedures, triggers
- ✅ PHP CRUD operations
- ✅ Security features
- ✅ Complete documentation
- ✅ Implementation guide

---

## 🎓 Viva Preparation

Key topics to prepare:
1. Database normalization (1NF, 2NF, 3NF)
2. Primary key vs Foreign key
3. INNER JOIN vs LEFT JOIN
4. Stored procedures vs Triggers
5. SQL injection prevention
6. Password hashing
7. Session management
8. XAMPP architecture

See `PROJECT_DOCUMENTATION.md` Section 13 for detailed viva questions and answers.

---

**Developed for Database Systems Mini-Project**

**Technologies:** PHP, MySQL, HTML, CSS, JavaScript, XAMPP

**Database Concepts:** Normalization, Constraints, Joins, Subqueries, Views, Procedures, Triggers

---

## 📦 Deliverables Checklist

- [x] Complete SQL scripts
- [x] PHP application files
- [x] Project documentation
- [x] Implementation guide
- [x] ER diagram
- [x] Sample data
- [x] Important queries
- [x] Views, procedures, triggers
- [x] Security implementation
- [x] Error handling
- [x] README file

**All files ready for submission! 🎉**

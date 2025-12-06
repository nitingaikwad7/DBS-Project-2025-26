-- ============================================
-- Online Job Portal - Database Schema
-- Database Systems Mini-Project
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

-- ============================================
-- Table 1: USERS
-- ============================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('job_seeker', 'employer', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active',
    CONSTRAINT chk_email CHECK (email LIKE '%@%.%')
) ENGINE=InnoDB;

-- ============================================
-- Table 2: JOB_SEEKERS
-- ============================================
CREATE TABLE job_seekers (
    seeker_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    qualification VARCHAR(100),
    experience_years INT DEFAULT 0,
    skills TEXT,
    resume_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT chk_experience CHECK (experience_years >= 0),
    CONSTRAINT chk_phone CHECK (phone REGEXP '^[0-9]{10,15}$')
) ENGINE=InnoDB;

-- ============================================
-- Table 3: EMPLOYERS
-- ============================================
CREATE TABLE employers (
    employer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    company_description TEXT,
    industry VARCHAR(100),
    company_size VARCHAR(50),
    website VARCHAR(150),
    contact_person VARCHAR(100),
    phone VARCHAR(15),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT chk_website CHECK (website LIKE 'http%' OR website IS NULL)
) ENGINE=InnoDB;

-- ============================================
-- Table 4: JOBS
-- ============================================
CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    job_title VARCHAR(150) NOT NULL,
    job_description TEXT,
    job_category VARCHAR(100),
    job_type ENUM('Full-time', 'Part-time', 'Contract', 'Internship') NOT NULL,
    experience_required INT DEFAULT 0,
    salary_min DECIMAL(10, 2),
    salary_max DECIMAL(10, 2),
    location VARCHAR(100),
    skills_required TEXT,
    vacancies INT DEFAULT 1,
    posted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deadline DATE,
    status ENUM('active', 'closed', 'draft') DEFAULT 'active',
    FOREIGN KEY (employer_id) REFERENCES employers(employer_id) ON DELETE CASCADE,
    CONSTRAINT chk_salary CHECK (salary_max >= salary_min),
    CONSTRAINT chk_vacancies CHECK (vacancies > 0),
    CONSTRAINT chk_experience_req CHECK (experience_required >= 0)
) ENGINE=InnoDB;

-- ============================================
-- Table 5: APPLICATIONS
-- ============================================
CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    seeker_id INT NOT NULL,
    cover_letter TEXT,
    applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'shortlisted', 'rejected', 'accepted') DEFAULT 'pending',
    employer_notes TEXT,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES job_seekers(seeker_id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (job_id, seeker_id),
    CONSTRAINT chk_no_duplicate_application UNIQUE (job_id, seeker_id)
) ENGINE=InnoDB;

-- ============================================
-- Table 6: ADMIN
-- ============================================
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Additional Table: APPLICATION_COUNT (for trigger)
-- ============================================
CREATE TABLE application_statistics (
    job_id INT PRIMARY KEY,
    total_applications INT DEFAULT 0,
    pending_count INT DEFAULT 0,
    shortlisted_count INT DEFAULT 0,
    rejected_count INT DEFAULT 0,
    accepted_count INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Indexes for Performance Optimization
-- ============================================
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_type ON users(user_type);
CREATE INDEX idx_job_status ON jobs(status);
CREATE INDEX idx_job_category ON jobs(job_category);
CREATE INDEX idx_job_location ON jobs(location);
CREATE INDEX idx_application_status ON applications(status);
CREATE INDEX idx_posted_date ON jobs(posted_date);

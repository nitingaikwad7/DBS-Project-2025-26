-- ============================================
-- COMPLETE DATABASE SCRIPT
-- Online Job Portal - Database Systems Project
-- ============================================
-- This file contains all SQL commands to create
-- the complete database with tables, data, views,
-- stored procedures, and triggers
-- ============================================

-- Create and Use Database
CREATE DATABASE IF NOT EXISTS job_portal;
USE job_portal;

-- ============================================
-- SECTION 1: CREATE TABLES
-- ============================================

-- Table 1: USERS
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('job_seeker', 'employer', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active',
    CONSTRAINT chk_email CHECK (email LIKE '%@%.%')
) ENGINE=InnoDB;

-- Table 2: JOB_SEEKERS
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

-- Table 3: EMPLOYERS
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

-- Table 4: JOBS
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

-- Table 5: APPLICATIONS
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
    UNIQUE KEY unique_application (job_id, seeker_id)
) ENGINE=InnoDB;

-- Table 6: ADMIN
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table 7: APPLICATION_STATISTICS
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

-- Create Indexes
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_type ON users(user_type);
CREATE INDEX idx_job_status ON jobs(status);
CREATE INDEX idx_job_category ON jobs(job_category);
CREATE INDEX idx_job_location ON jobs(location);
CREATE INDEX idx_application_status ON applications(status);
CREATE INDEX idx_posted_date ON jobs(posted_date);

-- ============================================
-- SECTION 2: INSERT SAMPLE DATA
-- ============================================

-- Insert Users
INSERT INTO users (email, password, user_type, status) VALUES
('admin@jobportal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active'),
('john.doe@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'job_seeker', 'active'),
('jane.smith@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'job_seeker', 'active'),
('mike.wilson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'job_seeker', 'active'),
('sarah.jones@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'job_seeker', 'active'),
('techcorp@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'active'),
('innovate@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'active'),
('globaltech@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'active'),
('startupxyz@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'active');

-- Insert Admin
INSERT INTO admin (user_id, full_name, phone) VALUES
(1, 'Admin User', '9876543210');

-- Insert Job Seekers
INSERT INTO job_seekers (user_id, full_name, phone, date_of_birth, gender, address, city, state, qualification, experience_years, skills, resume_path) VALUES
(2, 'John Doe', '9876543211', '1995-05-15', 'Male', '123 Main Street', 'Mumbai', 'Maharashtra', 'B.Tech Computer Science', 3, 'Java, Python, SQL, Spring Boot', 'resumes/john_doe.pdf'),
(3, 'Jane Smith', '9876543212', '1998-08-20', 'Female', '456 Park Avenue', 'Bangalore', 'Karnataka', 'MCA', 2, 'PHP, MySQL, JavaScript, React', 'resumes/jane_smith.pdf'),
(4, 'Mike Wilson', '9876543213', '1992-03-10', 'Male', '789 Lake Road', 'Pune', 'Maharashtra', 'B.E. Information Technology', 5, 'C++, Python, Machine Learning, TensorFlow', 'resumes/mike_wilson.pdf'),
(5, 'Sarah Jones', '9876543214', '1999-11-25', 'Female', '321 Hill Street', 'Hyderabad', 'Telangana', 'B.Sc Computer Science', 1, 'HTML, CSS, JavaScript, Node.js', 'resumes/sarah_jones.pdf');

-- Insert Employers
INSERT INTO employers (user_id, company_name, company_description, industry, company_size, website, contact_person, phone, address, city, state) VALUES
(6, 'TechCorp Solutions', 'Leading IT solutions provider specializing in enterprise software development', 'Information Technology', '500-1000', 'https://techcorp.com', 'Rajesh Kumar', '9876543220', 'Tech Park, Sector 5', 'Bangalore', 'Karnataka'),
(7, 'Innovate Systems', 'Innovative startup focused on AI and Machine Learning solutions', 'Artificial Intelligence', '50-100', 'https://innovatesystems.com', 'Priya Sharma', '9876543221', 'Innovation Hub, Block A', 'Hyderabad', 'Telangana'),
(8, 'Global Tech Industries', 'Multinational corporation providing IT consulting and services', 'IT Consulting', '1000+', 'https://globaltech.com', 'Amit Patel', '9876543222', 'Corporate Tower, MG Road', 'Pune', 'Maharashtra'),
(9, 'StartupXYZ', 'Fast-growing startup in e-commerce and digital marketing', 'E-commerce', '10-50', 'https://startupxyz.com', 'Neha Gupta', '9876543223', 'Startup Incubator, Floor 3', 'Mumbai', 'Maharashtra');

-- Insert Jobs
INSERT INTO jobs (employer_id, job_title, job_description, job_category, job_type, experience_required, salary_min, salary_max, location, skills_required, vacancies, deadline, status) VALUES
(1, 'Senior Java Developer', 'Looking for experienced Java developer to work on enterprise applications. Must have strong knowledge of Spring Boot and microservices.', 'Software Development', 'Full-time', 3, 800000.00, 1200000.00, 'Bangalore', 'Java, Spring Boot, Microservices, REST API', 2, '2025-12-31', 'active'),
(1, 'Frontend Developer', 'Seeking creative frontend developer with React expertise. Will work on building responsive web applications.', 'Web Development', 'Full-time', 2, 600000.00, 900000.00, 'Bangalore', 'React, JavaScript, HTML, CSS, Redux', 3, '2025-12-25', 'active'),
(2, 'Machine Learning Engineer', 'Exciting opportunity for ML engineer to work on cutting-edge AI projects. Experience with TensorFlow required.', 'Artificial Intelligence', 'Full-time', 4, 1200000.00, 1800000.00, 'Hyderabad', 'Python, TensorFlow, Machine Learning, Deep Learning', 1, '2025-12-20', 'active'),
(2, 'Data Scientist', 'Join our data science team to analyze large datasets and build predictive models.', 'Data Science', 'Full-time', 3, 1000000.00, 1500000.00, 'Hyderabad', 'Python, R, SQL, Statistics, Data Visualization', 2, '2025-12-28', 'active'),
(3, 'IT Consultant', 'Looking for experienced IT consultant to advise clients on technology solutions.', 'Consulting', 'Full-time', 5, 1500000.00, 2000000.00, 'Pune', 'IT Strategy, Cloud Computing, Project Management', 1, '2025-12-15', 'active'),
(3, 'DevOps Engineer', 'Seeking DevOps engineer to manage CI/CD pipelines and cloud infrastructure.', 'DevOps', 'Full-time', 3, 900000.00, 1300000.00, 'Pune', 'Docker, Kubernetes, AWS, Jenkins, Linux', 2, '2025-12-30', 'active'),
(4, 'Digital Marketing Specialist', 'Looking for creative digital marketer to drive online campaigns and SEO strategies.', 'Marketing', 'Full-time', 2, 500000.00, 700000.00, 'Mumbai', 'SEO, SEM, Social Media Marketing, Google Analytics', 2, '2025-12-22', 'active'),
(4, 'PHP Developer', 'Need PHP developer for e-commerce platform development. Laravel experience preferred.', 'Web Development', 'Full-time', 2, 600000.00, 800000.00, 'Mumbai', 'PHP, Laravel, MySQL, JavaScript', 3, '2025-12-27', 'active'),
(1, 'Software Testing Engineer', 'QA engineer needed for manual and automation testing of web applications.', 'Quality Assurance', 'Full-time', 2, 500000.00, 700000.00, 'Bangalore', 'Selenium, Manual Testing, Test Automation, JIRA', 2, '2025-12-18', 'active'),
(2, 'Python Developer Intern', 'Internship opportunity for fresh graduates to learn Python development.', 'Software Development', 'Internship', 0, 15000.00, 25000.00, 'Hyderabad', 'Python, Basic Programming, Problem Solving', 5, '2025-12-10', 'active');

-- Insert Applications
INSERT INTO applications (job_id, seeker_id, cover_letter, status, employer_notes) VALUES
(1, 1, 'I am excited to apply for the Senior Java Developer position. With 3 years of experience in Java and Spring Boot, I believe I am a perfect fit for this role.', 'shortlisted', 'Strong candidate, schedule interview'),
(2, 2, 'I am passionate about frontend development and have 2 years of experience with React. I would love to contribute to your team.', 'pending', NULL),
(3, 3, 'As a Machine Learning enthusiast with 5 years of experience, I am eager to work on AI projects at Innovate Systems.', 'accepted', 'Excellent background, offer extended'),
(4, 3, 'I have strong analytical skills and experience in data science. I am interested in joining your data science team.', 'pending', NULL),
(5, 3, 'With 5 years in IT, I have consulted for multiple clients on technology solutions and would like to bring my expertise to your company.', 'rejected', 'Looking for more consulting experience'),
(6, 1, 'I have hands-on experience with Docker, Kubernetes, and AWS. I am excited about this DevOps opportunity.', 'pending', NULL),
(7, 4, 'I am a recent graduate with knowledge of digital marketing and SEO. I am eager to learn and grow in this field.', 'shortlisted', 'Good potential, needs training'),
(8, 2, 'I have 2 years of PHP and Laravel experience. I have worked on multiple e-commerce projects.', 'shortlisted', 'Matches requirements well'),
(9, 4, 'I have basic knowledge of testing and am eager to learn automation testing with Selenium.', 'pending', NULL),
(10, 4, 'I am a fresh graduate looking for an internship to gain practical Python development experience.', 'accepted', 'Good fit for internship program'),
(1, 3, 'I have extensive experience in Java development and have led multiple enterprise projects.', 'pending', NULL),
(2, 4, 'I am learning React and would love the opportunity to work on real-world projects.', 'rejected', 'Need more experience');

-- Initialize Application Statistics
INSERT INTO application_statistics (job_id, total_applications, pending_count, shortlisted_count, rejected_count, accepted_count)
SELECT 
    j.job_id,
    COUNT(a.application_id) as total_applications,
    SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN a.status = 'shortlisted' THEN 1 ELSE 0 END) as shortlisted_count,
    SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected_count,
    SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted_count
FROM jobs j
LEFT JOIN applications a ON j.job_id = a.job_id
GROUP BY j.job_id;

-- ============================================
-- SECTION 3: CREATE VIEWS
-- ============================================

CREATE OR REPLACE VIEW vw_active_jobs AS
SELECT 
    j.job_id, j.job_title, j.job_description, j.job_category, j.job_type,
    j.experience_required, j.salary_min, j.salary_max, j.location,
    j.skills_required, j.vacancies, j.posted_date, j.deadline,
    e.company_name, e.company_description, e.industry, e.company_size, e.city as company_city
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE j.status = 'active' AND j.deadline >= CURDATE();

CREATE OR REPLACE VIEW vw_application_summary AS
SELECT 
    a.application_id, a.job_id, j.job_title, e.company_name,
    a.seeker_id, js.full_name as applicant_name, js.phone as applicant_phone,
    js.qualification, js.experience_years, a.applied_date, a.status, u.email as applicant_email
FROM applications a
INNER JOIN jobs j ON a.job_id = j.job_id
INNER JOIN employers e ON j.employer_id = e.employer_id
INNER JOIN job_seekers js ON a.seeker_id = js.seeker_id
INNER JOIN users u ON js.user_id = u.user_id;

-- ============================================
-- SECTION 4: CREATE STORED PROCEDURES
-- ============================================

DELIMITER //

CREATE PROCEDURE sp_get_jobs_by_location(IN p_location VARCHAR(100))
BEGIN
    SELECT j.job_id, j.job_title, e.company_name, j.job_category, j.job_type,
           j.salary_min, j.salary_max, j.location, j.posted_date, j.deadline
    FROM jobs j
    INNER JOIN employers e ON j.employer_id = e.employer_id
    WHERE j.location LIKE CONCAT('%', p_location, '%')
      AND j.status = 'active' AND j.deadline >= CURDATE()
    ORDER BY j.posted_date DESC;
END //

CREATE PROCEDURE sp_apply_for_job(
    IN p_job_id INT, IN p_seeker_id INT, IN p_cover_letter TEXT, OUT p_result VARCHAR(100))
BEGIN
    DECLARE v_existing_application INT;
    DECLARE v_job_status VARCHAR(20);
    
    SELECT status INTO v_job_status FROM jobs WHERE job_id = p_job_id;
    
    IF v_job_status IS NULL THEN
        SET p_result = 'ERROR: Job does not exist';
    ELSEIF v_job_status != 'active' THEN
        SET p_result = 'ERROR: Job is not active';
    ELSE
        SELECT COUNT(*) INTO v_existing_application 
        FROM applications WHERE job_id = p_job_id AND seeker_id = p_seeker_id;
        
        IF v_existing_application > 0 THEN
            SET p_result = 'ERROR: Already applied to this job';
        ELSE
            INSERT INTO applications (job_id, seeker_id, cover_letter, status)
            VALUES (p_job_id, p_seeker_id, p_cover_letter, 'pending');
            SET p_result = 'SUCCESS: Application submitted';
        END IF;
    END IF;
END //

CREATE PROCEDURE sp_update_application_status(
    IN p_application_id INT, IN p_new_status VARCHAR(20), 
    IN p_employer_notes TEXT, OUT p_result VARCHAR(100))
BEGIN
    DECLARE v_application_exists INT;
    
    SELECT COUNT(*) INTO v_application_exists 
    FROM applications WHERE application_id = p_application_id;
    
    IF v_application_exists = 0 THEN
        SET p_result = 'ERROR: Application not found';
    ELSE
        UPDATE applications 
        SET status = p_new_status, employer_notes = p_employer_notes
        WHERE application_id = p_application_id;
        SET p_result = 'SUCCESS: Application status updated';
    END IF;
END //

DELIMITER ;

-- ============================================
-- SECTION 5: CREATE TRIGGERS
-- ============================================

DELIMITER //

CREATE TRIGGER trg_after_application_insert
AFTER INSERT ON applications
FOR EACH ROW
BEGIN
    INSERT INTO application_statistics (job_id, total_applications, pending_count)
    VALUES (NEW.job_id, 1, 1)
    ON DUPLICATE KEY UPDATE
        total_applications = total_applications + 1,
        pending_count = pending_count + 1;
END //

CREATE TRIGGER trg_after_application_update
AFTER UPDATE ON applications
FOR EACH ROW
BEGIN
    UPDATE application_statistics
    SET 
        pending_count = pending_count - (CASE WHEN OLD.status = 'pending' THEN 1 ELSE 0 END) + (CASE WHEN NEW.status = 'pending' THEN 1 ELSE 0 END),
        shortlisted_count = shortlisted_count - (CASE WHEN OLD.status = 'shortlisted' THEN 1 ELSE 0 END) + (CASE WHEN NEW.status = 'shortlisted' THEN 1 ELSE 0 END),
        rejected_count = rejected_count - (CASE WHEN OLD.status = 'rejected' THEN 1 ELSE 0 END) + (CASE WHEN NEW.status = 'rejected' THEN 1 ELSE 0 END),
        accepted_count = accepted_count - (CASE WHEN OLD.status = 'accepted' THEN 1 ELSE 0 END) + (CASE WHEN NEW.status = 'accepted' THEN 1 ELSE 0 END)
    WHERE job_id = NEW.job_id;
END //

CREATE TRIGGER trg_after_job_insert
AFTER INSERT ON jobs
FOR EACH ROW
BEGIN
    INSERT INTO application_statistics (job_id, total_applications, pending_count, 
                                       shortlisted_count, rejected_count, accepted_count)
    VALUES (NEW.job_id, 0, 0, 0, 0, 0);
END //

DELIMITER ;

-- ============================================
-- DATABASE SETUP COMPLETE
-- ============================================
-- Default Password for all sample users: password
-- ============================================

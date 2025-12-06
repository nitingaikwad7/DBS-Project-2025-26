-- ============================================
-- Sample Data Insertion
-- Online Job Portal Database
-- ============================================

USE job_portal;

-- ============================================
-- Insert Sample USERS
-- ============================================
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

-- Note: Password is 'password' hashed with bcrypt

-- ============================================
-- Insert Sample ADMIN
-- ============================================
INSERT INTO admin (user_id, full_name, phone) VALUES
(1, 'Admin User', '9876543210');

-- ============================================
-- Insert Sample JOB_SEEKERS
-- ============================================
INSERT INTO job_seekers (user_id, full_name, phone, date_of_birth, gender, address, city, state, qualification, experience_years, skills, resume_path) VALUES
(2, 'John Doe', '9876543211', '1995-05-15', 'Male', '123 Main Street', 'Mumbai', 'Maharashtra', 'B.Tech Computer Science', 3, 'Java, Python, SQL, Spring Boot', 'resumes/john_doe.pdf'),
(3, 'Jane Smith', '9876543212', '1998-08-20', 'Female', '456 Park Avenue', 'Bangalore', 'Karnataka', 'MCA', 2, 'PHP, MySQL, JavaScript, React', 'resumes/jane_smith.pdf'),
(4, 'Mike Wilson', '9876543213', '1992-03-10', 'Male', '789 Lake Road', 'Pune', 'Maharashtra', 'B.E. Information Technology', 5, 'C++, Python, Machine Learning, TensorFlow', 'resumes/mike_wilson.pdf'),
(5, 'Sarah Jones', '9876543214', '1999-11-25', 'Female', '321 Hill Street', 'Hyderabad', 'Telangana', 'B.Sc Computer Science', 1, 'HTML, CSS, JavaScript, Node.js', 'resumes/sarah_jones.pdf');

-- ============================================
-- Insert Sample EMPLOYERS
-- ============================================
INSERT INTO employers (user_id, company_name, company_description, industry, company_size, website, contact_person, phone, address, city, state) VALUES
(6, 'TechCorp Solutions', 'Leading IT solutions provider specializing in enterprise software development', 'Information Technology', '500-1000', 'https://techcorp.com', 'Rajesh Kumar', '9876543220', 'Tech Park, Sector 5', 'Bangalore', 'Karnataka'),
(7, 'Innovate Systems', 'Innovative startup focused on AI and Machine Learning solutions', 'Artificial Intelligence', '50-100', 'https://innovatesystems.com', 'Priya Sharma', '9876543221', 'Innovation Hub, Block A', 'Hyderabad', 'Telangana'),
(8, 'Global Tech Industries', 'Multinational corporation providing IT consulting and services', 'IT Consulting', '1000+', 'https://globaltech.com', 'Amit Patel', '9876543222', 'Corporate Tower, MG Road', 'Pune', 'Maharashtra'),
(9, 'StartupXYZ', 'Fast-growing startup in e-commerce and digital marketing', 'E-commerce', '10-50', 'https://startupxyz.com', 'Neha Gupta', '9876543223', 'Startup Incubator, Floor 3', 'Mumbai', 'Maharashtra');

-- ============================================
-- Insert Sample JOBS
-- ============================================
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

-- ============================================
-- Insert Sample APPLICATIONS
-- ============================================
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

-- ============================================
-- Initialize Application Statistics
-- ============================================
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

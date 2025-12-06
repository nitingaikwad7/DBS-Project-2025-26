-- ============================================
-- Views, Stored Procedures, and Triggers
-- Online Job Portal Database
-- ============================================

USE job_portal;

-- ============================================
-- VIEWS
-- ============================================

-- View 1: Active Jobs with Company Details
CREATE OR REPLACE VIEW vw_active_jobs AS
SELECT 
    j.job_id,
    j.job_title,
    j.job_description,
    j.job_category,
    j.job_type,
    j.experience_required,
    j.salary_min,
    j.salary_max,
    j.location,
    j.skills_required,
    j.vacancies,
    j.posted_date,
    j.deadline,
    e.company_name,
    e.company_description,
    e.industry,
    e.company_size,
    e.city as company_city
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE j.status = 'active' AND j.deadline >= CURDATE();

-- View 2: Application Summary
CREATE OR REPLACE VIEW vw_application_summary AS
SELECT 
    a.application_id,
    a.job_id,
    j.job_title,
    e.company_name,
    a.seeker_id,
    js.full_name as applicant_name,
    js.phone as applicant_phone,
    js.qualification,
    js.experience_years,
    a.applied_date,
    a.status,
    u.email as applicant_email
FROM applications a
INNER JOIN jobs j ON a.job_id = j.job_id
INNER JOIN employers e ON j.employer_id = e.employer_id
INNER JOIN job_seekers js ON a.seeker_id = js.seeker_id
INNER JOIN users u ON js.user_id = u.user_id;

-- View 3: Employer Dashboard Statistics
CREATE OR REPLACE VIEW vw_employer_statistics AS
SELECT 
    e.employer_id,
    e.company_name,
    COUNT(DISTINCT j.job_id) as total_jobs_posted,
    SUM(CASE WHEN j.status = 'active' THEN 1 ELSE 0 END) as active_jobs,
    COUNT(DISTINCT a.application_id) as total_applications_received,
    SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_applications,
    SUM(CASE WHEN a.status = 'shortlisted' THEN 1 ELSE 0 END) as shortlisted_applications,
    SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted_applications
FROM employers e
LEFT JOIN jobs j ON e.employer_id = j.employer_id
LEFT JOIN applications a ON j.job_id = a.job_id
GROUP BY e.employer_id, e.company_name;

-- View 4: Job Seeker Profile with Application Count
CREATE OR REPLACE VIEW vw_jobseeker_profile AS
SELECT 
    js.seeker_id,
    js.full_name,
    js.phone,
    u.email,
    js.date_of_birth,
    js.gender,
    js.city,
    js.state,
    js.qualification,
    js.experience_years,
    js.skills,
    COUNT(a.application_id) as total_applications,
    SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted_applications
FROM job_seekers js
INNER JOIN users u ON js.user_id = u.user_id
LEFT JOIN applications a ON js.seeker_id = a.seeker_id
GROUP BY js.seeker_id, js.full_name, js.phone, u.email, js.date_of_birth, 
         js.gender, js.city, js.state, js.qualification, js.experience_years, js.skills;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- Procedure 1: Get Jobs by Location
DELIMITER //
CREATE PROCEDURE sp_get_jobs_by_location(IN p_location VARCHAR(100))
BEGIN
    SELECT 
        j.job_id,
        j.job_title,
        e.company_name,
        j.job_category,
        j.job_type,
        j.salary_min,
        j.salary_max,
        j.location,
        j.posted_date,
        j.deadline
    FROM jobs j
    INNER JOIN employers e ON j.employer_id = e.employer_id
    WHERE j.location LIKE CONCAT('%', p_location, '%')
      AND j.status = 'active'
      AND j.deadline >= CURDATE()
    ORDER BY j.posted_date DESC;
END //
DELIMITER ;

-- Procedure 2: Apply for Job
DELIMITER //
CREATE PROCEDURE sp_apply_for_job(
    IN p_job_id INT,
    IN p_seeker_id INT,
    IN p_cover_letter TEXT,
    OUT p_result VARCHAR(100)
)
BEGIN
    DECLARE v_existing_application INT;
    DECLARE v_job_status VARCHAR(20);
    
    -- Check if job exists and is active
    SELECT status INTO v_job_status FROM jobs WHERE job_id = p_job_id;
    
    IF v_job_status IS NULL THEN
        SET p_result = 'ERROR: Job does not exist';
    ELSEIF v_job_status != 'active' THEN
        SET p_result = 'ERROR: Job is not active';
    ELSE
        -- Check if already applied
        SELECT COUNT(*) INTO v_existing_application 
        FROM applications 
        WHERE job_id = p_job_id AND seeker_id = p_seeker_id;
        
        IF v_existing_application > 0 THEN
            SET p_result = 'ERROR: Already applied to this job';
        ELSE
            -- Insert application
            INSERT INTO applications (job_id, seeker_id, cover_letter, status)
            VALUES (p_job_id, p_seeker_id, p_cover_letter, 'pending');
            
            SET p_result = 'SUCCESS: Application submitted';
        END IF;
    END IF;
END //
DELIMITER ;

-- Procedure 3: Update Application Status
DELIMITER //
CREATE PROCEDURE sp_update_application_status(
    IN p_application_id INT,
    IN p_new_status VARCHAR(20),
    IN p_employer_notes TEXT,
    OUT p_result VARCHAR(100)
)
BEGIN
    DECLARE v_application_exists INT;
    
    -- Check if application exists
    SELECT COUNT(*) INTO v_application_exists 
    FROM applications 
    WHERE application_id = p_application_id;
    
    IF v_application_exists = 0 THEN
        SET p_result = 'ERROR: Application not found';
    ELSE
        -- Update application
        UPDATE applications 
        SET status = p_new_status,
            employer_notes = p_employer_notes
        WHERE application_id = p_application_id;
        
        SET p_result = 'SUCCESS: Application status updated';
    END IF;
END //
DELIMITER ;

-- Procedure 4: Get Application Statistics for Job
DELIMITER //
CREATE PROCEDURE sp_get_job_application_stats(IN p_job_id INT)
BEGIN
    SELECT 
        j.job_id,
        j.job_title,
        e.company_name,
        j.vacancies,
        COUNT(a.application_id) as total_applications,
        SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN a.status = 'shortlisted' THEN 1 ELSE 0 END) as shortlisted,
        SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected,
        SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted
    FROM jobs j
    INNER JOIN employers e ON j.employer_id = e.employer_id
    LEFT JOIN applications a ON j.job_id = a.job_id
    WHERE j.job_id = p_job_id
    GROUP BY j.job_id, j.job_title, e.company_name, j.vacancies;
END //
DELIMITER ;

-- Procedure 5: Search Jobs with Filters
DELIMITER //
CREATE PROCEDURE sp_search_jobs(
    IN p_keyword VARCHAR(100),
    IN p_location VARCHAR(100),
    IN p_job_type VARCHAR(50),
    IN p_min_salary DECIMAL(10,2)
)
BEGIN
    SELECT 
        j.job_id,
        j.job_title,
        e.company_name,
        j.job_category,
        j.job_type,
        j.location,
        j.salary_min,
        j.salary_max,
        j.experience_required,
        j.posted_date,
        j.deadline
    FROM jobs j
    INNER JOIN employers e ON j.employer_id = e.employer_id
    WHERE j.status = 'active'
      AND j.deadline >= CURDATE()
      AND (p_keyword IS NULL OR j.job_title LIKE CONCAT('%', p_keyword, '%') 
           OR j.job_description LIKE CONCAT('%', p_keyword, '%')
           OR j.skills_required LIKE CONCAT('%', p_keyword, '%'))
      AND (p_location IS NULL OR j.location LIKE CONCAT('%', p_location, '%'))
      AND (p_job_type IS NULL OR j.job_type = p_job_type)
      AND (p_min_salary IS NULL OR j.salary_min >= p_min_salary)
    ORDER BY j.posted_date DESC;
END //
DELIMITER ;

-- Procedure 6: Close Expired Jobs
DELIMITER //
CREATE PROCEDURE sp_close_expired_jobs()
BEGIN
    UPDATE jobs 
    SET status = 'closed'
    WHERE status = 'active' 
      AND deadline < CURDATE();
    
    SELECT ROW_COUNT() as jobs_closed;
END //
DELIMITER ;

-- ============================================
-- TRIGGERS
-- ============================================

-- Trigger 1: Update Application Statistics After Insert
DELIMITER //
CREATE TRIGGER trg_after_application_insert
AFTER INSERT ON applications
FOR EACH ROW
BEGIN
    -- Insert or update statistics
    INSERT INTO application_statistics (job_id, total_applications, pending_count)
    VALUES (NEW.job_id, 1, 1)
    ON DUPLICATE KEY UPDATE
        total_applications = total_applications + 1,
        pending_count = pending_count + 1;
END //
DELIMITER ;

-- Trigger 2: Update Application Statistics After Status Update
DELIMITER //
CREATE TRIGGER trg_after_application_update
AFTER UPDATE ON applications
FOR EACH ROW
BEGIN
    DECLARE v_old_pending INT DEFAULT 0;
    DECLARE v_old_shortlisted INT DEFAULT 0;
    DECLARE v_old_rejected INT DEFAULT 0;
    DECLARE v_old_accepted INT DEFAULT 0;
    DECLARE v_new_pending INT DEFAULT 0;
    DECLARE v_new_shortlisted INT DEFAULT 0;
    DECLARE v_new_rejected INT DEFAULT 0;
    DECLARE v_new_accepted INT DEFAULT 0;
    
    -- Calculate old status counts
    IF OLD.status = 'pending' THEN SET v_old_pending = 1; END IF;
    IF OLD.status = 'shortlisted' THEN SET v_old_shortlisted = 1; END IF;
    IF OLD.status = 'rejected' THEN SET v_old_rejected = 1; END IF;
    IF OLD.status = 'accepted' THEN SET v_old_accepted = 1; END IF;
    
    -- Calculate new status counts
    IF NEW.status = 'pending' THEN SET v_new_pending = 1; END IF;
    IF NEW.status = 'shortlisted' THEN SET v_new_shortlisted = 1; END IF;
    IF NEW.status = 'rejected' THEN SET v_new_rejected = 1; END IF;
    IF NEW.status = 'accepted' THEN SET v_new_accepted = 1; END IF;
    
    -- Update statistics
    UPDATE application_statistics
    SET 
        pending_count = pending_count - v_old_pending + v_new_pending,
        shortlisted_count = shortlisted_count - v_old_shortlisted + v_new_shortlisted,
        rejected_count = rejected_count - v_old_rejected + v_new_rejected,
        accepted_count = accepted_count - v_old_accepted + v_new_accepted
    WHERE job_id = NEW.job_id;
END //
DELIMITER ;

-- Trigger 3: Update Application Statistics After Delete
DELIMITER //
CREATE TRIGGER trg_after_application_delete
AFTER DELETE ON applications
FOR EACH ROW
BEGIN
    UPDATE application_statistics
    SET 
        total_applications = total_applications - 1,
        pending_count = pending_count - (CASE WHEN OLD.status = 'pending' THEN 1 ELSE 0 END),
        shortlisted_count = shortlisted_count - (CASE WHEN OLD.status = 'shortlisted' THEN 1 ELSE 0 END),
        rejected_count = rejected_count - (CASE WHEN OLD.status = 'rejected' THEN 1 ELSE 0 END),
        accepted_count = accepted_count - (CASE WHEN OLD.status = 'accepted' THEN 1 ELSE 0 END)
    WHERE job_id = OLD.job_id;
END //
DELIMITER ;

-- Trigger 4: Prevent Duplicate Applications
DELIMITER //
CREATE TRIGGER trg_before_application_insert
BEFORE INSERT ON applications
FOR EACH ROW
BEGIN
    DECLARE v_count INT;
    
    SELECT COUNT(*) INTO v_count
    FROM applications
    WHERE job_id = NEW.job_id AND seeker_id = NEW.seeker_id;
    
    IF v_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'You have already applied to this job';
    END IF;
END //
DELIMITER ;

-- Trigger 5: Auto-create Statistics Entry for New Job
DELIMITER //
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
-- USAGE EXAMPLES
-- ============================================

-- Using Views
-- SELECT * FROM vw_active_jobs WHERE location = 'Bangalore';
-- SELECT * FROM vw_application_summary WHERE status = 'pending';
-- SELECT * FROM vw_employer_statistics ORDER BY total_applications_received DESC;

-- Using Stored Procedures
-- CALL sp_get_jobs_by_location('Bangalore');
-- CALL sp_apply_for_job(1, 2, 'I am interested in this position', @result);
-- SELECT @result;
-- CALL sp_update_application_status(1, 'shortlisted', 'Good candidate', @result);
-- SELECT @result;
-- CALL sp_get_job_application_stats(1);
-- CALL sp_search_jobs('Developer', 'Bangalore', 'Full-time', 500000);
-- CALL sp_close_expired_jobs();

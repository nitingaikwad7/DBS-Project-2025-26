-- ============================================
-- Important SQL Queries
-- Online Job Portal Database
-- ============================================

USE job_portal;

-- ============================================
-- 1. List All Available Jobs
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    e.company_name,
    j.job_category,
    j.job_type,
    j.experience_required,
    j.salary_min,
    j.salary_max,
    j.location,
    j.vacancies,
    j.posted_date,
    j.deadline
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE j.status = 'active' 
  AND j.deadline >= CURDATE()
ORDER BY j.posted_date DESC;

-- ============================================
-- 2. View Applications for a Specific Job
-- ============================================
SELECT 
    a.application_id,
    js.full_name,
    js.phone,
    js.email,
    js.qualification,
    js.experience_years,
    js.skills,
    a.applied_date,
    a.status,
    a.cover_letter,
    a.employer_notes
FROM applications a
INNER JOIN job_seekers js ON a.seeker_id = js.seeker_id
INNER JOIN users u ON js.user_id = u.user_id
WHERE a.job_id = 1
ORDER BY a.applied_date DESC;

-- ============================================
-- 3. Jobs Applied by a Specific Job Seeker
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    e.company_name,
    j.job_category,
    j.location,
    j.salary_min,
    j.salary_max,
    a.applied_date,
    a.status,
    a.employer_notes
FROM applications a
INNER JOIN jobs j ON a.job_id = j.job_id
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE a.seeker_id = 1
ORDER BY a.applied_date DESC;

-- ============================================
-- 4. Employers with Maximum Job Postings
-- ============================================
SELECT 
    e.employer_id,
    e.company_name,
    e.industry,
    e.city,
    COUNT(j.job_id) as total_jobs_posted,
    SUM(CASE WHEN j.status = 'active' THEN 1 ELSE 0 END) as active_jobs,
    SUM(CASE WHEN j.status = 'closed' THEN 1 ELSE 0 END) as closed_jobs
FROM employers e
LEFT JOIN jobs j ON e.employer_id = j.employer_id
GROUP BY e.employer_id, e.company_name, e.industry, e.city
ORDER BY total_jobs_posted DESC
LIMIT 10;

-- ============================================
-- 5. INNER JOIN: Jobs with Employer Details
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    j.job_category,
    j.job_type,
    j.location,
    j.salary_min,
    j.salary_max,
    e.company_name,
    e.industry,
    e.company_size,
    e.city as company_city,
    j.posted_date
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE j.status = 'active'
ORDER BY j.posted_date DESC;

-- ============================================
-- 6. LEFT JOIN: All Job Seekers and Their Applications
-- ============================================
SELECT 
    js.seeker_id,
    js.full_name,
    js.qualification,
    js.experience_years,
    js.city,
    COUNT(a.application_id) as total_applications,
    SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN a.status = 'shortlisted' THEN 1 ELSE 0 END) as shortlisted,
    SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted,
    SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
FROM job_seekers js
LEFT JOIN applications a ON js.seeker_id = a.seeker_id
GROUP BY js.seeker_id, js.full_name, js.qualification, js.experience_years, js.city
ORDER BY total_applications DESC;

-- ============================================
-- 7. Subquery: Jobs with Above Average Salary
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    e.company_name,
    j.salary_min,
    j.salary_max,
    (j.salary_min + j.salary_max) / 2 as avg_salary
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE (j.salary_min + j.salary_max) / 2 > (
    SELECT AVG((salary_min + salary_max) / 2)
    FROM jobs
    WHERE status = 'active'
)
AND j.status = 'active'
ORDER BY avg_salary DESC;

-- ============================================
-- 8. Subquery: Job Seekers Who Haven't Applied to Any Job
-- ============================================
SELECT 
    js.seeker_id,
    js.full_name,
    js.phone,
    js.qualification,
    js.experience_years,
    u.email
FROM job_seekers js
INNER JOIN users u ON js.user_id = u.user_id
WHERE js.seeker_id NOT IN (
    SELECT DISTINCT seeker_id 
    FROM applications
)
ORDER BY js.full_name;

-- ============================================
-- 9. Subquery: Employers with Most Applications
-- ============================================
SELECT 
    e.employer_id,
    e.company_name,
    e.industry,
    (SELECT COUNT(*) 
     FROM applications a 
     INNER JOIN jobs j ON a.job_id = j.job_id 
     WHERE j.employer_id = e.employer_id) as total_applications_received
FROM employers e
ORDER BY total_applications_received DESC
LIMIT 5;

-- ============================================
-- 10. Complex Query: Job Details with Application Statistics
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    e.company_name,
    j.job_category,
    j.location,
    j.vacancies,
    j.posted_date,
    j.deadline,
    COUNT(a.application_id) as total_applications,
    SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_applications,
    SUM(CASE WHEN a.status = 'shortlisted' THEN 1 ELSE 0 END) as shortlisted_applications,
    SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted_applications,
    SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected_applications
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
LEFT JOIN applications a ON j.job_id = a.job_id
WHERE j.status = 'active'
GROUP BY j.job_id, j.job_title, e.company_name, j.job_category, j.location, 
         j.vacancies, j.posted_date, j.deadline
ORDER BY total_applications DESC;

-- ============================================
-- 11. Search Jobs by Skills (Pattern Matching)
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    e.company_name,
    j.skills_required,
    j.location,
    j.salary_min,
    j.salary_max
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE j.skills_required LIKE '%Python%'
  AND j.status = 'active'
ORDER BY j.posted_date DESC;

-- ============================================
-- 12. Jobs Expiring Soon (Within 7 Days)
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    e.company_name,
    j.deadline,
    DATEDIFF(j.deadline, CURDATE()) as days_remaining
FROM jobs j
INNER JOIN employers e ON j.employer_id = e.employer_id
WHERE j.status = 'active'
  AND j.deadline BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
ORDER BY j.deadline ASC;

-- ============================================
-- 13. Most Popular Job Categories
-- ============================================
SELECT 
    j.job_category,
    COUNT(j.job_id) as total_jobs,
    COUNT(DISTINCT j.employer_id) as employers_hiring,
    COUNT(a.application_id) as total_applications,
    AVG((j.salary_min + j.salary_max) / 2) as avg_salary
FROM jobs j
LEFT JOIN applications a ON j.job_id = a.job_id
WHERE j.status = 'active'
GROUP BY j.job_category
ORDER BY total_jobs DESC;

-- ============================================
-- 14. Job Seekers Matching Job Requirements
-- ============================================
SELECT 
    j.job_id,
    j.job_title,
    js.seeker_id,
    js.full_name,
    js.experience_years,
    j.experience_required,
    js.skills
FROM jobs j
CROSS JOIN job_seekers js
WHERE j.status = 'active'
  AND js.experience_years >= j.experience_required
  AND j.job_id NOT IN (
      SELECT job_id 
      FROM applications 
      WHERE seeker_id = js.seeker_id
  )
ORDER BY j.job_id, js.experience_years DESC;

-- ============================================
-- 15. Application Success Rate by Job Seeker
-- ============================================
SELECT 
    js.seeker_id,
    js.full_name,
    js.qualification,
    js.experience_years,
    COUNT(a.application_id) as total_applications,
    SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted_count,
    ROUND((SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) / COUNT(a.application_id)) * 100, 2) as success_rate_percentage
FROM job_seekers js
INNER JOIN applications a ON js.seeker_id = a.seeker_id
GROUP BY js.seeker_id, js.full_name, js.qualification, js.experience_years
HAVING total_applications > 0
ORDER BY success_rate_percentage DESC;

<?php
/**
 * Browse Jobs Page
 * Online Job Portal
 */
require_once 'config.php';

// Get search parameters
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$location = isset($_GET['location']) ? sanitize_input($_GET['location']) : '';
$job_type = isset($_GET['job_type']) ? sanitize_input($_GET['job_type']) : '';

// Build query
$query = "SELECT j.*, e.company_name, e.city as company_city 
          FROM jobs j 
          INNER JOIN employers e ON j.employer_id = e.employer_id 
          WHERE j.status = 'active' AND j.deadline >= CURDATE()";

if (!empty($search)) {
    $query .= " AND (j.job_title LIKE '%$search%' OR j.job_description LIKE '%$search%' OR j.skills_required LIKE '%$search%')";
}

if (!empty($location)) {
    $query .= " AND j.location LIKE '%$location%'";
}

if (!empty($job_type)) {
    $query .= " AND j.job_type = '$job_type'";
}

$query .= " ORDER BY j.posted_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Jobs - Online Job Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        .logo { font-size: 24px; font-weight: bold; }
        
        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .search-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 15px;
        }
        
        .search-form input, .search-form select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .btn-search {
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .btn-search:hover { background: #5568d3; }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .results-header h2 { color: #333; }
        
        .job-count {
            color: #666;
            font-size: 14px;
        }
        
        .jobs-grid {
            display: grid;
            gap: 20px;
        }
        
        .job-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .job-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }
        
        .job-title {
            font-size: 22px;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .company-name {
            color: #666;
            font-size: 16px;
        }
        
        .job-type-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-fulltime { background: #d4edda; color: #155724; }
        .badge-parttime { background: #fff3cd; color: #856404; }
        .badge-contract { background: #d1ecf1; color: #0c5460; }
        .badge-internship { background: #f8d7da; color: #721c24; }
        
        .job-details {
            display: flex;
            gap: 20px;
            margin: 15px 0;
            flex-wrap: wrap;
        }
        
        .job-detail {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
            font-size: 14px;
        }
        
        .job-description {
            color: #666;
            line-height: 1.6;
            margin: 15px 0;
        }
        
        .job-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .salary {
            font-size: 18px;
            font-weight: 600;
            color: #28a745;
        }
        
        .btn-apply {
            padding: 10px 25px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .btn-apply:hover { background: #5568d3; }
        
        .no-jobs {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
        }
        
        .no-jobs h3 {
            color: #666;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .job-header {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo">🎯 Job Portal</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="browse_jobs.php">Browse Jobs</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="<?php echo $_SESSION['user_type']; ?>_dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register_jobseeker.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="search-section">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Job title, keywords, or company" value="<?php echo htmlspecialchars($search); ?>">
                <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($location); ?>">
                <select name="job_type">
                    <option value="">All Types</option>
                    <option value="Full-time" <?php echo $job_type == 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
                    <option value="Part-time" <?php echo $job_type == 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
                    <option value="Contract" <?php echo $job_type == 'Contract' ? 'selected' : ''; ?>>Contract</option>
                    <option value="Internship" <?php echo $job_type == 'Internship' ? 'selected' : ''; ?>>Internship</option>
                </select>
                <button type="submit" class="btn-search">Search</button>
            </form>
        </div>

        <div class="results-header">
            <h2>Available Jobs</h2>
            <span class="job-count"><?php echo mysqli_num_rows($result); ?> jobs found</span>
        </div>

        <div class="jobs-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($job = mysqli_fetch_assoc($result)): ?>
                    <div class="job-card">
                        <div class="job-header">
                            <div>
                                <h3 class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></h3>
                                <p class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></p>
                            </div>
                            <span class="job-type-badge badge-<?php echo strtolower(str_replace('-', '', $job['job_type'])); ?>">
                                <?php echo $job['job_type']; ?>
                            </span>
                        </div>

                        <div class="job-details">
                            <span class="job-detail">📍 <?php echo htmlspecialchars($job['location']); ?></span>
                            <span class="job-detail">💼 <?php echo $job['experience_required']; ?> years exp</span>
                            <span class="job-detail">📅 Posted <?php echo date('M d, Y', strtotime($job['posted_date'])); ?></span>
                            <?php if ($job['vacancies']): ?>
                                <span class="job-detail">👥 <?php echo $job['vacancies']; ?> openings</span>
                            <?php endif; ?>
                        </div>

                        <?php if ($job['job_description']): ?>
                            <p class="job-description">
                                <?php echo substr(htmlspecialchars($job['job_description']), 0, 200); ?>...
                            </p>
                        <?php endif; ?>

                        <?php if ($job['skills_required']): ?>
                            <div class="job-detail" style="margin-top: 10px;">
                                <strong>Skills:</strong> <?php echo htmlspecialchars($job['skills_required']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="job-footer">
                            <div class="salary">
                                ₹<?php echo number_format($job['salary_min']); ?> - ₹<?php echo number_format($job['salary_max']); ?>
                            </div>
                            <?php if (is_logged_in() && $_SESSION['user_type'] == 'job_seeker'): ?>
                                <a href="apply_job.php?job_id=<?php echo $job['job_id']; ?>" class="btn-apply">Apply Now</a>
                            <?php else: ?>
                                <a href="login.php" class="btn-apply">Login to Apply</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-jobs">
                    <h3>No jobs found</h3>
                    <p>Try adjusting your search criteria</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
/**
 * Employer Dashboard
 * Online Job Portal
 */
require_once 'config.php';
check_user_type('employer');

// Get employer info
$user_id = $_SESSION['user_id'];
$query = "SELECT e.*, u.email FROM employers e 
          INNER JOIN users u ON e.user_id = u.user_id 
          WHERE e.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$employer = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$employer_id = $employer['employer_id'];

// Get statistics
$stats_query = "SELECT 
    COUNT(DISTINCT j.job_id) as total_jobs,
    SUM(CASE WHEN j.status = 'active' THEN 1 ELSE 0 END) as active_jobs,
    COUNT(DISTINCT a.application_id) as total_applications,
    SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending_apps
    FROM jobs j
    LEFT JOIN applications a ON j.job_id = a.job_id
    WHERE j.employer_id = ?";
$stmt = mysqli_prepare($conn, $stats_query);
mysqli_stmt_bind_param($stmt, "i", $employer_id);
mysqli_stmt_execute($stmt);
$stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Get recent jobs
$jobs_query = "SELECT j.*, COUNT(a.application_id) as app_count
               FROM jobs j
               LEFT JOIN applications a ON j.job_id = a.job_id
               WHERE j.employer_id = ?
               GROUP BY j.job_id
               ORDER BY j.posted_date DESC LIMIT 5";
$stmt = mysqli_prepare($conn, $jobs_query);
mysqli_stmt_bind_param($stmt, "i", $employer_id);
mysqli_stmt_execute($stmt);
$recent_jobs = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Employer</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        
        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .welcome-section h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .welcome-section p {
            color: #666;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        
        .section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .section h2 {
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-active { background: #d4edda; color: #155724; }
        .badge-closed { background: #f8d7da; color: #721c24; }
        .badge-draft { background: #d1ecf1; color: #0c5460; }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #28a745;
            color: white;
        }
        
        .btn-primary:hover {
            background: #218838;
        }
        
        .btn-small {
            padding: 5px 15px;
            font-size: 14px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo">🏢 Employer Portal</div>
            <ul class="nav-links">
                <li><a href="employer_dashboard.php">Dashboard</a></li>
                <li><a href="post_job.php">Post Job</a></li>
                <li><a href="manage_jobs.php">Manage Jobs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($employer['company_name']); ?>! 🏢</h1>
            <p><?php echo htmlspecialchars($employer['industry']); ?> | <?php echo htmlspecialchars($employer['city']); ?>, <?php echo htmlspecialchars($employer['state']); ?></p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_jobs']; ?></div>
                <div class="stat-label">Total Jobs Posted</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['active_jobs']; ?></div>
                <div class="stat-label">Active Jobs</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_applications']; ?></div>
                <div class="stat-label">Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending_apps']; ?></div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <h2>Recent Job Postings</h2>
                <a href="post_job.php" class="btn btn-primary">+ Post New Job</a>
            </div>
            <?php if (mysqli_num_rows($recent_jobs) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Applications</th>
                            <th>Posted Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($job = mysqli_fetch_assoc($recent_jobs)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($job['job_title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($job['location']); ?></td>
                                <td><?php echo $job['job_type']; ?></td>
                                <td><?php echo $job['app_count']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($job['posted_date'])); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $job['status']; ?>">
                                        <?php echo ucfirst($job['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="view_applications.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary btn-small">
                                        View Applications
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>You haven't posted any jobs yet.</p>
                    <a href="post_job.php" class="btn btn-primary" style="margin-top: 20px;">Post Your First Job</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Company Profile</h2>
            <p><strong>Company:</strong> <?php echo htmlspecialchars($employer['company_name']); ?></p>
            <p><strong>Industry:</strong> <?php echo htmlspecialchars($employer['industry']); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($employer['company_size']); ?> employees</p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($employer['contact_person']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($employer['phone']); ?></p>
            <?php if ($employer['website']): ?>
                <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($employer['website']); ?>" target="_blank"><?php echo htmlspecialchars($employer['website']); ?></a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

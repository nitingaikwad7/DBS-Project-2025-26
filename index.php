<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Job Portal - Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        /* Header */
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
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        .nav-links a:hover {
            opacity: 0.8;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 20px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 15px 40px;
            font-size: 16px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s, box-shadow 0.3s;
            font-weight: 600;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        /* Features Section */
        .features {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
        }
        
        .section-title {
            text-align: center;
            font-size: 36px;
            margin-bottom: 50px;
            color: #333;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #667eea;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.8;
        }
        
        /* Stats Section */
        .stats {
            background: #f8f9fa;
            padding: 60px 20px;
            margin: 80px 0;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }
        
        .stat-item h2 {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .stat-item p {
            font-size: 18px;
            color: #666;
        }
        
        /* How It Works */
        .how-it-works {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
        }
        
        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .step {
            text-align: center;
            padding: 20px;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        
        .step h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .step p {
            color: #666;
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="logo">🎯 Job Portal</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="browse_jobs.php">Browse Jobs</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register_jobseeker.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Find Your Dream Job Today</h1>
        <p>Connect with top employers and discover thousands of job opportunities</p>
        <div class="cta-buttons">
            <a href="register_jobseeker.php" class="btn btn-primary">Find Jobs</a>
            <a href="register_employer.php" class="btn btn-secondary">Post a Job</a>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <h2>1000+</h2>
                <p>Active Jobs</p>
            </div>
            <div class="stat-item">
                <h2>500+</h2>
                <p>Companies</p>
            </div>
            <div class="stat-item">
                <h2>5000+</h2>
                <p>Job Seekers</p>
            </div>
            <div class="stat-item">
                <h2>95%</h2>
                <p>Success Rate</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2 class="section-title">Why Choose Our Job Portal?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3>Easy Job Search</h3>
                <p>Search and filter thousands of jobs by location, category, salary, and experience level.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Quick Apply</h3>
                <p>Apply to multiple jobs with just one click. Track all your applications in one place.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏢</div>
                <h3>Top Companies</h3>
                <p>Connect with leading companies across various industries looking for talented professionals.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Career Insights</h3>
                <p>Get salary insights, market trends, and career advice to make informed decisions.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔔</div>
                <h3>Job Alerts</h3>
                <p>Receive instant notifications when new jobs matching your profile are posted.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Secure & Private</h3>
                <p>Your data is protected with industry-standard security measures and encryption.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <h2 class="section-title">How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Create Account</h3>
                <p>Sign up as a job seeker or employer in just a few minutes.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Build Profile</h3>
                <p>Add your skills, experience, and preferences to stand out.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Search & Apply</h3>
                <p>Browse jobs and apply to positions that match your profile.</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Get Hired</h3>
                <p>Connect with employers and land your dream job!</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="browse_jobs.php">Browse Jobs</a>
                <a href="login.php">Login</a>
                <a href="register_jobseeker.php">Job Seekers</a>
                <a href="register_employer.php">Employers</a>
            </div>
            <p>&copy; 2025 Online Job Portal. All rights reserved.</p>
            <p style="margin-top: 10px; font-size: 14px; opacity: 0.8;">Database Systems Mini-Project</p>
        </div>
    </footer>
</body>
</html>

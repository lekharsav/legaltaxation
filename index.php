<?php include('db.php'); ?>
<!doctype html>
<html lang="en-gb">
<head>
    <title>Legal Taxation - Professional Tax & Business Solutions</title>
    <link rel="shortcut icon" href="images/favicon.webp" type="image/x-icon" />
    <meta charset="utf-8">
    <meta name="author" content="Legal Taxation">
    <meta name="keywords" content="Taxation, GST, Income Tax, Business Registration, Legal Services, CA Services">
    <meta name="description" content="Professional tax and business solutions. Get expert CA services, GST filing, business registration, and legal compliance all in one platform.">		
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="css/slick.css" rel="stylesheet">
    <link href="css/slick-theme.css" rel="stylesheet">
    
    <style>
        /* ========================================
           Global Styles & Variables
        ======================================== */
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --accent: #f72585;
            --warning: #f9c74f;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --white: #ffffff;
            --gradient-1: linear-gradient(135deg, #4361ee, #7209b7);
            --gradient-2: linear-gradient(135deg, #f72585, #f9c74f);
            --shadow-sm: 0 5px 15px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.15);
            --border-radius: 20px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--gray);
            line-height: 1.7;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: var(--dark);
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 36px;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 4px;
            background: var(--gradient-1);
            border-radius: 2px;
        }

        .section-title p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
            color: var(--gray);
        }

        .btn-primary {
            background: var(--gradient-1);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            transition: var(--transition);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 2px solid rgba(255,255,255,0.3);
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-3px);
        }

        /* ========================================
           Hero Section
        ======================================== */
        .hero-section {
            background: var(--gradient-1);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            color: white;
        }

        .hero-title span {
            color: var(--warning);
        }

        .hero-description {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .hero-image {
            position: relative;
            z-index: 2;
            animation: float 3s ease-in-out infinite;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 30px;
            box-shadow: var(--shadow-lg);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* ========================================
           Features Section
        ======================================== */
        .features-section {
            padding: 80px 0;
            background: var(--light);
        }

        .feature-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 40px 30px;
            text-align: center;
            height: 100%;
            transition: var(--transition);
            border: 1px solid #e2e8f0;
            box-shadow: var(--shadow-sm);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 90px;
            height: 90px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 36px;
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon {
            transform: rotateY(360deg);
        }

        .feature-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: var(--gray);
            margin-bottom: 0;
        }

        /* ========================================
           About Section (Enhanced)
        ======================================== */
        .about-section {
            padding: 80px 0;
            background: var(--white);
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: var(--gradient-1);
            opacity: 0.03;
            border-radius: 50%;
            pointer-events: none;
        }

        .about-content {
            padding-right: 30px;
        }

        .about-content h2 {
            font-size: 42px;
            margin-bottom: 25px;
            position: relative;
        }

        .about-content h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 70px;
            height: 4px;
            background: var(--gradient-1);
            border-radius: 2px;
        }

        .about-content p {
            margin-bottom: 20px;
            font-size: 16px;
            color: var(--gray);
        }

        .about-content .btn-primary {
            margin-top: 15px;
        }

        /* Image grid */
        .about-image-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .about-image-item {
            position: relative;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 280px;
        }

        .about-image-item:first-child {
            transform: translateY(0);
        }

        .about-image-item:last-child {
            transform: translateY(50px);
        }

        .about-image-item:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .about-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .about-image-item:hover img {
            transform: scale(1.1);
        }

        .about-image-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-1);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 1;
        }

        .about-image-item:hover::before {
            opacity: 0.2;
        }

        @media (max-width: 768px) {
            .about-image-item:last-child {
                transform: translateY(0);
            }
            .about-content {
                padding-right: 0;
                text-align: center;
            }
            .about-content h2::after {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        /* ========================================
           Stats Section
        ======================================== */
        .stats-section {
            background: var(--gradient-1);
            color: white;
            padding: 60px 0;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 35px 20px;
            text-align: center;
            transition: var(--transition);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.3);
            box-shadow: 0 25px 45px rgba(0,0,0,0.3);
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            transition: var(--transition);
        }

        .stat-card:hover .stat-icon {
            background: white;
            color: var(--primary);
            transform: scale(1.1);
        }

        .stat-number {
            font-size: 48px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 10px;
            color: var(--warning);
        }

        .stat-label {
            font-size: 16px;
            font-weight: 500;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ========================================
           Services Section
        ======================================== */
        .services-section {
            padding: 80px 0;
            background: var(--light);
        }

        .service-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .service-image {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .service-card:hover .service-image img {
            transform: scale(1.1);
        }

        .service-overlay {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            pointer-events: none;
        }

        .service-tag {
            background: var(--accent);
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(247, 37, 133, 0.3);
        }

        .service-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .service-content h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .service-content p {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 15px;
            flex: 1;
        }

        .service-price {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .original-price {
            font-size: 16px;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .discount-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: gap 0.3s;
        }

        .service-link:hover {
            gap: 12px;
            color: var(--primary-dark);
        }

        /* ========================================
           Testimonials Section
        ======================================== */
        .testimonials-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%);
            position: relative;
            overflow: hidden;
        }

        .testimonials-section::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(67, 97, 238, 0.05);
            border-radius: 50%;
        }

        .testimonial-slider {
            margin: 0 -15px;
        }

        .testimonial-card {
            background: white;
            border-radius: 30px;
            padding: 35px 30px;
            margin: 20px 15px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid rgba(0,0,0,0.03);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: rgba(67, 97, 238, 0.2);
        }

        .testimonial-icon {
            margin-bottom: 20px;
        }

        .testimonial-icon i {
            font-size: 40px;
            color: var(--primary);
            opacity: 0.3;
        }

        .testimonial-text {
            font-size: 16px;
            line-height: 1.8;
            color: var(--gray);
            margin-bottom: 25px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }

        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        .author-info {
            flex: 1;
        }

        .author-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .author-position {
            font-size: 14px;
            color: var(--gray);
            font-weight: 500;
        }

        /* Slick slider custom arrows/dots */
        .testimonial-slider .slick-dots {
            text-align: center;
            margin-top: 30px;
        }

        .testimonial-slider .slick-dots li {
            display: inline-block;
            margin: 0 5px;
        }

        .testimonial-slider .slick-dots button {
            width: 12px;
            height: 12px;
            background: #cbd5e1;
            border: none;
            border-radius: 50%;
            text-indent: -9999px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .testimonial-slider .slick-dots li.slick-active button {
            background: var(--primary);
            transform: scale(1.2);
        }

        .testimonial-slider .slick-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 45px;
            height: 45px;
            background: white;
            border: none;
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            z-index: 3;
            transition: var(--transition);
            display: flex !important;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--dark);
        }

        .testimonial-slider .slick-arrow:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        .testimonial-slider .slick-prev {
            left: -20px;
        }

        .testimonial-slider .slick-next {
            right: -20px;
        }

        @media (max-width: 768px) {
            .testimonial-slider .slick-arrow {
                display: none !important;
            }
        }

        /* ========================================
           CTA Section
        ======================================== */
        .cta-section {
            background: var(--gradient-2);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta-title {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
        }

        .cta-description {
            font-size: 18px;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto 40px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-buttons .btn-primary {
            background: white;
            color: var(--dark);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .cta-buttons .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .cta-buttons .btn-secondary {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.5);
        }

        /* ========================================
           Responsive
        ======================================== */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 42px;
            }
            .about-image-item:last-child {
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 36px;
            }
            .hero-buttons {
                flex-direction: column;
                align-items: flex-start;
            }
            .section-title h2 {
                font-size: 30px;
            }
            .stat-number {
                font-size: 36px;
            }
            .cta-title {
                font-size: 30px;
            }
            .about-image-grid {
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>

<?php include("includes/header.php"); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content animate__animated animate__fadeInUp">
                    <h1 class="hero-title">Welcome To Legal <span>Taxation</span></h1>
                    <p class="hero-description">Legal Taxation is a technology platform to simplify Taxation and business-related matters. Get expert CA services, GST filing, business registration, and legal compliance all in one platform.</p>
                    <div class="hero-buttons">
                        <a href="ca-list.php" class="btn-primary"><i class="fas fa-users"></i> Our CA Partners</a>
                        <a href="#services" class="btn-secondary"><i class="fas fa-cogs"></i> Explore Services</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image animate__animated animate__fadeInRight">
                    <img src="images/tax-img.png" alt="Legal Taxation Services">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose Legal Taxation?</h2>
            <p>We provide comprehensive tax and business solutions with expertise and technology</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-lock"></i></div>
                    <h3>High Security</h3>
                    <p>Your data is protected with enterprise-grade security measures and encryption protocols.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Advanced Analytics</h3>
                    <p>Get detailed insights and analytics to make informed financial decisions for your business.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-pie"></i></div>
                    <h3>Growth Strategies</h3>
                    <p>Strategic planning and advisory services to help your business grow and thrive.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section (Enhanced) -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-content">
                    <h2>About Legal Taxation</h2>
                    <p>Legal Taxation is a technology platform to simplify Taxation and business-related matters. We are committed to assist startups, small businesses and large businesses in resolving legal and Tax compliance related to starting and running their business.</p>
                    <p>Our mission is to offer easy, affordable, quick, and computerized automated professional services to our clients. Through technology, we bring numerous professionals/legal/financial institutions forms at one place and have simplified them to be fully understood by every individual, firms & companies.</p>
                    <p>We are a technology-driven platform trying to organize professional services industry in India! Our mission is to provide one-click access to individuals, firms & businesses for all their legal, taxations & professional needs.</p>
                    <a href="about-us.php" class="btn-primary"><i class="fas fa-book-open"></i> Read More About Us</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image-grid">
                    <div class="about-image-item">
                        <img src="images/abt_img1.webp" alt="About Us">
                    </div>
                    <div class="about-image-item">
                        <img src="images/abt_img2.webp" alt="Our Team">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="stat-number" data-count="2500">0</div>
                    <div class="stat-label">Complete Projects</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number" data-count="100">0</div>
                    <div class="stat-label">Professional Workers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-smile"></i></div>
                    <div class="stat-number" data-count="1000">0</div>
                    <div class="stat-label">Happy Clients</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-award"></i></div>
                    <div class="stat-number" data-count="100">0</div>
                    <div class="stat-label">Awards & Recognitions</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Professional Services</h2>
            <p>Comprehensive solutions for all your business and tax needs</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-image">
                        <img src="images/what_img1.webp" alt="Startup Services">
                        <div class="service-overlay">
                            <span class="service-tag">Popular</span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3>Startup Registration</h3>
                        <p>Complete solutions for new business registration including proprietorship, partnership, and private limited company formation.</p>
                        <div class="service-price">
                            <span class="original-price">₹10,000</span>
                            <span class="discount-price">₹8,000</span>
                        </div>
                        <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-image">
                        <img src="images/what_img2.webp" alt="GST & Income Tax">
                        <div class="service-overlay">
                            <span class="service-tag">Trending</span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3>GST & Income Tax</h3>
                        <p>Professional GST registration, filing, and income tax return preparation services for businesses and individuals.</p>
                        <div class="service-price">
                            <span class="original-price">₹10,000</span>
                            <span class="discount-price">₹8,000</span>
                        </div>
                        <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-image">
                        <img src="images/what_img3.webp" alt="Finance Services">
                        <div class="service-overlay">
                            <span class="service-tag">New</span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3>Financial Services</h3>
                        <p>Business loans, working capital financing, and investment advisory services for business growth.</p>
                        <div class="service-price">
                            <span class="original-price">₹10,000</span>
                            <span class="discount-price">₹8,000</span>
                        </div>
                        <a href="#" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="service.php" class="btn-primary"><i class="fas fa-eye me-2"></i> View All Services</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-title">
            <h2>What Our Clients Say</h2>
            <p>Trusted by businesses and individuals across India</p>
        </div>
        <div class="testimonial-slider">
            <div class="testimonial-card">
                <div class="testimonial-icon"><i class="fas fa-quote-left"></i></div>
                <div class="testimonial-text">
                    This is due to their excellent service, competitive pricing and customer support. Their personal touch and professionalism make all the difference in handling my business taxation.
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar"><i class="fas fa-user-circle"></i></div>
                    <div class="author-info">
                        <div class="author-name">Milanda Moses</div>
                        <div class="author-position">Business Owner</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-icon"><i class="fas fa-quote-left"></i></div>
                <div class="testimonial-text">
                    Legal Taxation simplified my GST filing process completely. Their team is responsive, knowledgeable, and always available when I need assistance with tax matters.
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar"><i class="fas fa-user-circle"></i></div>
                    <div class="author-info">
                        <div class="author-name">Rajesh Kumar</div>
                        <div class="author-position">Startup Founder</div>
                    </div>
                </div>
            </div>
            <!-- Add more testimonials as needed -->
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2 class="cta-title">Ready to Simplify Your Taxation?</h2>
        <p class="cta-description">Join thousands of satisfied clients who trust Legal Taxation for their business and tax needs.</p>
        <div class="cta-buttons">
            <a href="user-login.php" class="btn-primary"><i class="fas fa-user-plus"></i> Register Now</a>
            <a href="contact-us.html" class="btn-secondary"><i class="fas fa-headset"></i> Contact Us</a>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/slick.min.js"></script>
<script>
    $(document).ready(function(){
        // Testimonial slider
        $('.testimonial-slider').slick({
            dots: true,
            arrows: true,
            infinite: true,
            speed: 500,
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            prevArrow: '<button class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        arrows: false
                    }
                }
            ]
        });

        // Counter animation
        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        function animateCounter($el) {
            var countTo = $el.data('count');
            $({ countNum: 0 }).animate({ countNum: countTo }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $el.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $el.text(this.countNum);
                }
            });
        }

        var $statNumbers = $('.stat-number');
        var animated = false;

        function checkCounters() {
            if (!animated) {
                $statNumbers.each(function() {
                    if (isElementInViewport(this)) {
                        animated = true;
                        $statNumbers.each(function() {
                            animateCounter($(this));
                        });
                        return false;
                    }
                });
            }
        }

        checkCounters();
        $(window).on('scroll', function() {
            checkCounters();
        });
    });
</script>
</body>
</html>
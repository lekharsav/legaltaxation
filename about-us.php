<?php
include("db.php");
$page_title = "About Us";
?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Legal Taxation<?php if(isset($page_title)) echo " - " . $page_title; ?></title>
    <link rel="shortcut icon" href="images/favicon.webp" type="image/webp" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="css/slick.css" rel="stylesheet">
    <link href="css/slick-theme.css" rel="stylesheet">

    <style>
        :root {
            --primary-color:   #667eea;
            --secondary-color: #764ba2;
            --accent-color:    #f6851f;
            --dark-color:      #2d3748;
            --light-color:     #f8f9fa;
            --text-color:      #4a5568;
            --gold:            #ffd166;
        }

        /*
         * IMPORTANT:
         * - Removed header / navbar / Font Awesome overrides from this page-level CSS.
         * - Header/nav styles must be controlled by includes/header.php or css/style.css.
         *
         * Scoped typography for this page only so header remains identical site-wide.
         */
        .income_area, .income_area * {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Your about page styles – unchanged and page-scoped */
        .income_area {
    padding: 20px 0 80px; /* reduced top spacing */
    background: #ffffff;  /* match homepage */
}
        .head {
            font-size: 2.6rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 25px 0 20px;
        }
        .head_nav + .income_area {
    margin-top: 0 !important;
}

        .abt_box {
            background: white;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .abt_box:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .abt_icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 18px;
        }

        .about_top img {
            max-width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        /* Breadcrumb (page-specific styling kept local) */
        .breadcrumb_area ul {
            padding: 0;
            margin: 0;
            list-style: none;
            display: flex;
            gap: 8px;
            color: var(--text-color);
            align-items: center;
        }

        .breadcrumb_area ul li a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb_area ul li {
            font-size: 14px;
        }

        /* Responsive tweaks for the about page only */
        @media (max-width: 768px) {
            .head {
                font-size: 1.9rem;
            }
            .abt_icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<?php include("includes/header.php"); ?>

<!-- rest of your about-us content remains exactly the same -->
<section class="income_area">
<div class="container pt-3">
            <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb_area">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li>/</li>
                        <li>About Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<div class="container pt-3">
            <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="inner_area">
                    <div class="about_top text-center">
                        <img src="images/abt_img3.webp" alt="Legal Taxation Team" />
                        <h2 class="head">About Us</h2>
                        <p>Legal Taxation is a technology platform to simplify Taxation and business-related matters. We are committed to assist startups, small businesses and large businesses in resolving legal and Tax compliance related to starting and running their business.</p>
                        <p>Our mission is to offer easy, affordable, quick, and computerized automated professional services to our clients. Through technology, we bring numerous professionals/legal/financial institutions forms at one place and have simplified them to be fully understood by every individual, firms & companies.</p>
                        <p>We are a technology-driven platform trying to organize professional services industry in India! Our mission is to provide one-click access to individuals, firms & businesses for all their legal, taxations & professional needs…!!!</p>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-4 d-flex">
                            <div class="abt_box">
                                <div class="abt_icon">
                                    <i class="fa-solid fa-bullseye"></i>
                                </div>
                                <h3>Our Mission</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex">
                            <div class="abt_box">
                                <div class="abt_icon">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                                <h3>Our Vision</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex">
                            <div class="abt_box">
                                <div class="abt_icon">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <h3>Professional Team</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>

</body>
</html>
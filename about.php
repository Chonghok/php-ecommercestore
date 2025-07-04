<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetsGear</title>
    <link rel="icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/about.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <main>
        <div class="product-banner">
            <img src="images/product-banner.png" alt="">
            <span>About Us</span>
        </div>
    <div class="about-main">
        
        <!-- Hero Section -->
        <section class="about-hero">
            <h1>Welcome to <span>LetsGear</span></h1>
            <p>Your trusted destination for quality products, fast delivery, and reliable service across Cambodia.</p>
        </section>
      
        <!-- About Grid -->
        <section class="about-grid">
            <div class="about-box">
                <h2>🛠️ Who We Are</h2>
                <p>We're a passionate team of tech-driven problem solvers, working to make shopping easy and enjoyable for everyone in Cambodia.</p>
            </div>
            <div class="about-box">
                <h2>🚀 What We Do</h2>
                <p>From gadgets to daily essentials, we bring you the best at unbeatable value, backed by secure payments and fast delivery.</p>
            </div>
            <div class="about-box">
                <h2>🎯 Our Mission</h2>
                <p>We're here to empower people through innovation, convenience, and trust — redefining eCommerce one delivery at a time.</p>
            </div>
        </section>
      
        <!-- Why Choose Us Section -->
        <section class="why-us">
            <h2>Why Choose Us?</h2>
            <div class="why-grid">
                <div class="why-item">
                    <img src="https://cdn-icons-png.flaticon.com/512/8086/8086753.png" alt="Fast Delivery">
                    <h3>Fast Delivery</h3>
                    <p>Lightning-fast shipping across Cambodia — delivered right to your door.</p>
                </div>
                <div class="why-item">
                    <img src="https://cdn-icons-png.flaticon.com/512/929/929426.png" alt="Secure Payment">
                    <h3>Secure Payment</h3>
                    <p>Safe, encrypted transactions with every order you place.</p>
                </div>
                <div class="why-item">
                    <img src="https://cdn-icons-png.flaticon.com/512/471/471664.png" alt="24/7 Support">
                    <h3>24/7 Support</h3>
                    <p>Got questions? Our team is always here to help.</p>
                </div>
            </div>
        </section>
      
        <!-- Meet the Team (Optional) -->
        <section class="meet-team">
            <h2>Meet the Team</h2>
            <p>We're a small but mighty crew, working behind the scenes to give you the best shopping experience.</p>
            <div class="team-preview">
                <div class="team-card">
                    <img src="images/AboutUs/sdachgame.jpg" alt="Team Member">
                    <h4>Mork Chonghok</h4>
                    <span>CEO & Co-Founder</span>
                </div>
                <div class="team-card">
                    <img src="images/AboutUs/astonhall.jpg" alt="Team Member">
                    <h4>Mom Hanokreach</h4>
                    <span>Operations Manager</span>
                </div>
            </div>
        </section>
      
        <!-- Call to Action -->
        <section class="about-cta">
            <h2>Ready to shop with us?</h2>
            <p>Join thousands of happy customers. Fast delivery, great service, and real value — all in one place.</p>
            <a href="product.php" class="cta-button">Start Shopping</a>
        </section>
    </div>
    </main>
    <?php require_once 'includes/footer.php' ?>

</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</html>
<?php
    session_start();
    require_once 'includes/config.php';
    require_once 'includes/product-functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LetsGear</title>
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
  
    <?php require_once 'includes/header.php' ?>
    <main>
        <div class="product-banner">
            <img src="images/product-banner.png" alt="">
            <span>Contact Us</span>
        </div>
    <section class="contact-section">
        <div class="contact-container">
        <h1>Get in Touch With Us</h1>
        <p>If you have any questions, concerns, or feedback, feel free to reach out using the form below. Our support team typically responds within 24 hours.</p>

        <div class="contact-form-wrapper">
            <form class="contact-form">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required />
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required />
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Optional: +855 12 345 678" />
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Enter a subject" required />
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-submit">Send Message</button>
            </div>
            </form>

            <div class="contact-info">
            <h3>Customer Support</h3>
            <p><strong>Email:</strong> support@letsgear.com</p>
            <p><strong>Phone:</strong> +855 23 456 789</p>
            <p><strong>Address:</strong> #100, Phnom Penh, Cambodia</p>

            <div class="map">
                <iframe src="https://maps.google.com/maps?q=Phnom%20Penh&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="200" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy"></iframe>
            </div>
            </div>
        </div>
        </div>
    </section>
    </main>
    <?php require_once 'includes/footer.php' ?>

</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</html>
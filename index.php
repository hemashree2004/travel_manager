<?php
session_start();
include 'php/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Manager</title>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
     <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
}
header{
    background-color: #a80000;
}
h1, h2, h3 {
    margin: 0;
}

.logo {
    text-align: center;
}
a {
    text-decoration: none;
    color: inherit;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

/* Navbar Styles */
.navbar {
    background-color: #1f1f1f; /* Medium Gray */
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar .navbar-brand {
    font-size: 1.5rem;
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.navbar .navbar-toggler {
    display: none;
    font-size: 1.5rem;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
}

.navbar .navbar-menu {
    display: flex;
    gap: 20px;
}

.navbar .navbar-menu a {
    color: white;
    text-decoration: none;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.navbar .navbar-menu a:hover {
    color: #a80000; /* Bright Red */
}

/* Hero Section */
.hero-banner {
    position: relative;
    height: 80vh;
    background-image: url('./images/hero-background.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.hero-banner .overlay {
    background: rgba(31, 31, 31, 0.8); /* Dark Gray Overlay */
    padding: 20px;
    border-radius: 10px;
}

.hero-banner h2 {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 15px;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
    color:  #a80000; /* Bright Red */
}

.hero-banner p {
    font-size: 1.5rem;
    margin-bottom: 20px;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
}

.hero-banner .btn-primary {
    display: inline-block;
    padding: 10px 20px;
    background-color:  #a80000; /* Dark Red */
    color: white;
    text-decoration: none;
    font-size: 1.2rem;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.hero-banner .btn-primary:hover {
    background-color: #731010; /* Bright Red */
}

/* Features Section */
.features {
    margin: 20px 20px;
    padding: 40px 20px;
    background-color:white;
    text-align: center;
    color: #d4d4d4; /* Light Gray */
}

.features h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: #a80000; /* Bright Red */
}

.feature-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.feature-item {
    max-width: 300px;
    background: #d4d4d4; /* Dark Gray */
    padding: 20px;
    border: 1px solid #707070; /* Medium Gray */
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

.feature-item img.feature-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 15px;
    border-radius: 8px;
}

.feature-item h3 {
    font-size: 1.5rem;
    margin: 10px 0;
    color: black; 
}

.feature-item p {
    font-size: 1rem;
    color: black; 
}

/* Buttons */
button, .btn-primary {
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover, .btn-primary:hover {
    transform: translateY(-2px);
}

/* Footer Styles */
.main-footer {
    background-color: #a80000; /* Dark Red */
    color: white;
    text-align: center;
    padding: 0;
    margin: 0;
    position: absolute;
    bottom: 0;
    width: 100%;
}

/* Media Queries */
@media (max-width: 768px) {
    .navbar .navbar-menu {
        display: none;
        flex-direction: column;
        gap: 10px;
        background-color: #707070; /* Medium Gray */
        padding: 10px;
        border-radius: 5px;
    }

    .navbar .navbar-menu.active {
        display: flex;
    }

    .navbar .navbar-toggler {
        display: block;
    }

    .hero-banner h2 {
        font-size: 2.5rem;
    }

    .hero-banner p {
        font-size: 1.2rem;
    }

    .feature-grid {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .hero-banner h2 {
        font-size: 2rem;
    }

    .hero-banner p {
        font-size: 1rem;
    }

    .feature-item {
        width: 100%;
        padding: 15px;
    }
}

     </style>
</head>
<body>
    <!-- Header Section -->
    <header class="main-header">
        <div class="logo">
            <h1>Travel Manager</h1>
        </div>
           <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand"></a>
            <button class="navbar-toggler" onclick="toggleNavbar()">☰</button>
            <div class="navbar-menu">
                <a href="index.php">Home</a>
                <a href="php/destinations.php">Destinations</a>
                <a href="php/transport.php">Transportations</a>
                <a href="php/bookings.php">Bookings</a>
                <a href="php/login.php">Login</a>
                <a href="php/signup.php">Sign Up</a>
            </div>
        </div>
    </nav>
    </header>

<!-- Hero Section -->
<section class="hero-banner">
    <div class="overlay">
        <h2>Plan Your Dream Vacation</h2>
        <p>Explore amazing destinations around the world with ease.</p>
        <a href="php/destinations.php" class="btn-primary">Discover Now</a>
    </div>
</section>


<!-- Features Section -->
<section class="features">
    <h2>Why Choose Us?</h2>
    <div class="feature-grid">
        <div class="feature-item">
            <img src="https://www.listchallenges.com/f/lists/3e8ce959-a88f-4ff5-aac8-d0f302d90f13.jpg" alt="Top Destinations" class="feature-image">
            <h3>Top Destinations</h3>
            <p>We offer a curated list of the most exciting travel destinations.</p>
        </div>
        <div class="feature-item">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2nnpHJO-KYDmRS17xSsC4e1I_-LPs2zAACA&s" alt="Easy Bookings" class="feature-image">
            <h3>Easy Bookings</h3>
            <p>Enjoy a hassle-free booking experience with just a few clicks.</p>
        </div>
        <div class="feature-item">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTsZhYjtXHeCPgIdhH8dQOp1U-_e8MB2vFxDQ&s" alt="Secure Payments" class="feature-image">
            <h3>Secure Payments</h3>
            <p>Your payments are protected with state-of-the-art encryption.</p>
        </div>
    </div>
</section>

    <!-- Footer Section -->
<footer class="main-footer">
    <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>

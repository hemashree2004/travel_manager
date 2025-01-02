<?php 
session_start(); // To manage user session for checking login status
include 'database.php';

// Fetch destinations from the database
$query = $conn->query("SELECT * FROM Destinations");
$destinations = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations</title>
    <style>
        /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #1f1f1f; /* Dark Gray */
    background-image: url('../images/hero-background.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #d4d4d4; /* Light Gray */
}

/* Header */
header {
    background-color: #a80000; /* Bright Red */
    color: #ffffff;
    padding: 10px 0;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: center;
    background-color: #2d2d2d; /* Dark Gray */
    /* padding: 0.5em 0; */
}

.navbar-menu a {
    color: #d4d4d4; /* Light Gray */
    margin: 0 1em;
    text-decoration: none;
    font-weight: bold;
}

.navbar-menu a:hover {
    color: #a80000; /* Bright Red */
}

/* Container */
.container {
    padding: 2em;
    max-width: 1200px;
    margin: 2em auto;
    background-color: rgba(23, 23, 23, 0.8);  /* Slightly lighter Dark Gray */
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

/* Headings */
h2 {
    color: #a80000; /* Bright Red */
    text-align: center;
    margin-bottom: 1em;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1.5em;
    background-color: #232323; /* Slightly lighter Dark Gray */
}

th, td {
    border: 1px solid #707070; /* Medium Gray Border */
    padding: 0.75em;
    text-align: center;
    color: #d4d4d4; /* Light Gray */
}

th {
    background-color: #a80000; /* Bright Red */
    color: #ffffff;
}

tr:nth-child(even) {
    background-color: #2d2d2d; /* Darker Gray */
}

tr:nth-child(odd) {
    background-color: #1f1f1f; /* Dark Gray */
}

tr:hover {
    background-color: #731010; /* Dark Red */
    color: #ffffff;
}

/* Button */
button {
    background-color: #a80000; /* Bright Red */
    color: #ffffff;
    border: none;
    padding: 0.5em 1em;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #731010; /* Dark Red */
}

/* Footer */
.main-footer {
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    text-align: center;
    padding: 1em 0;
    margin-top: 2em;
}

    </style>
</head>
<body>

<header>
    <h1>Travel Manager</h1>
</header>

<!-- Navbar -->
<nav class="navbar">
    <div class="container">
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="transport.php">Transportation</a>
        </div>
    </div>
</nav>

<!-- Content Container -->
<div class="container">
    <h2>Available Destinations</h2>
    <table>
        <thead>
            <tr>
                <th>Location</th>
                <th>Price Per Person Per Day</th>
                <th>Description</th>
                <th>Transportation</th>
                <th>Book a Trip</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($destinations as $destination): ?>
                <tr>
                    <td><?= htmlspecialchars($destination['LocationName']) ?></td>
                    <td><?= htmlspecialchars($destination['PricePerPersonPerDay']) ?></td>
                    <td><?= htmlspecialchars($destination['Description']) ?></td>
                    <td><?= htmlspecialchars($destination['Transportation']) ?></td>
                    <td>
                        <form action="" method="POST">
                            <button type="button" onclick="handleBooking()">Book</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // JavaScript function to handle redirection based on login status
    function handleBooking() {
        <?php if (isset($_SESSION['UserID'])): ?>
            window.location.href = 'book.php';
        <?php else: ?>
            window.location.href = 'login.php';
        <?php endif; ?>
    }
</script>

<!-- Footer -->
<footer class="main-footer">
    <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
</footer>

</body>
</html>

<?php
session_start();
include 'database.php';

// Fetch available transport modes
$query = $conn->prepare("
    SELECT t.TransportMode, t.Cost, d.LocationName 
    FROM Transportation t 
    JOIN Destinations d ON t.DestinationID = d.DestinationID
");
$query->execute();
$transportation = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Transportation</title>
    <style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #1f1f1f; /* Dark Gray */
    background-image: url('../images/hero-background.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    margin: 0;
    padding: 0;
    color: #d4d4d4; /* Light Gray */
}

.main-header {
    background-color: #a80000; /* Bright Red */
    color: #ffffff; /* White */
    padding: 4px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
}

.navbar {
    display: flex;
    justify-content: center;
    background-color: #2d2d2d; /* Dark Gray */
    padding: 0 0;
    
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

.container {
    padding: 2em;
    max-width: 1200px;
    margin: 2em auto;
    background-color: rgba(23, 23, 23, 0.8); /* Slightly lighter Dark Gray */
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}

h1, h4 {
    color: #d4d4d4; /* Bright Red */
    text-align: center;
    margin-bottom: 1em;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1.5em;
    background-color: #232323; /* Slightly lighter Dark Gray */
    color: #d4d4d4; /* Light Gray */
}

th, td {
    border: 1px solid #707070; /* Medium Gray Border */
    padding: 0.75em;
    text-align: center;
}

th {
    background-color: #a80000; /* Bright Red */
    color: #ffffff; /* White */
}

tr:nth-child(even) {
    background-color: #2d2d2d; /* Darker Gray */
}

tr:nth-child(odd) {
    background-color: #1f1f1f; /* Dark Gray */
}

tr:hover {
    background-color: #731010; /* Dark Red */
    color: #ffffff; /* White */
}

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
    <header class="main-header">
        <div class="logo">
            <h1>Travel Manager</h1>
        </div>
        <nav class="navbar">
            <div class="container">
                <div class="navbar-menu">
                    <a href="../index.php">Home</a>
                    <a href="destinations.php">Destinations</a>
                    <!-- <a href="transport.php">Transportation</a> -->
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Available Transportation</h1>
        <h4>Additional Charges Per Person*</h4>
        <table>
            <thead>
                <tr>
                    <th>Destination</th>
                    <th>Mode of Transport</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transportation as $transport): ?>
                    <tr>
                        <td><?= htmlspecialchars($transport['LocationName']) ?></td>
                        <td><?= htmlspecialchars($transport['TransportMode']) ?></td>
                        <td><?= htmlspecialchars($transport['Cost']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer class="main-footer">
        <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
        <p>*Terms & Conditions apply</p>
    </footer>
</body>
</html>

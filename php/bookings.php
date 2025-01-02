
<?php
include 'database.php';

// Fetch bookings from the database
$query = $conn->query("
SELECT 
    b.BookingID, 
    u.UserName, 
    d.LocationName, 
    b.NumberOfPeople, 
    b.TotalCost, 
    b.TripStartDate, 
    b.TripEndDate, 
    t.TransportMode
FROM Bookings b 
JOIN Users u ON b.UserID = u.UserID 
JOIN Destinations d ON b.DestinationID = d.DestinationID
JOIN Transportation t ON b.TransportID = t.TransportID
");
$bookings = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #1f1f1f; /* Dark Gray */
    background-image: url('../images/hero-background.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #d4d4d4; /* Light Gray */
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}
h1, h4 {
    color: #d4d4d4; /* Bright Red */
    text-align: center;
}
/* Header Styles */
header {
    font-size: 24px; /* Slightly larger font size for prominence */
    font-weight: bold;
    padding: 10px 0;
    margin: 0; /* Remove any margin */
    text-align: center;
    background-color: #a80000; /* Bright Red Background */
    color: #ffffff; /* White Text for High Contrast */
    width: 100vw; /* Full Width of the Viewport */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4); /* Subtle shadow for depth */
}

/* Navbar Styles */
.navbar {
            background-color: #1f1f1f; /* Pastel Green */
            width: 100%;
            margin:0;
            padding: 10px 0;
            width: 100vw;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
        }

        .navbar-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .navbar-menu a {
            color:  #d4d4d4;;
            text-decoration: none;
            font-size: 1rem;
        }

        .navbar-menu a:hover {
            color:  #731010; /* Pastel Purple */
        }

/* Container Styles */
.container {
    width: 100%;
    max-width: 800px;
    background-color: rgba(23, 23, 23, 0.8); /* Sheer Dark Gray */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    color: #d4d4d4; /* Light Gray */
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    border: 1px solid #707070; /* Medium Gray */
    padding: 10px;
    text-align: left;
}

table th {
    background-color: #a80000; /* Bright Red */
    color: white;
}

table tr:nth-child(even) {
    background-color: #1f1f1f; /* Dark Gray */
}

table tr:hover {
    background-color: #731010; /* Dark Red */
}

/* Footer Styles */
footer {
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    text-align: center;
    padding: 10px 0;
    margin-top: 20px;
    width: 100vw;
}
    </style>
</head>
<body>
<header>
    <h1>Travel Manager</h1>
</header>
<nav class="navbar">
    <div class="container">
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="destinations.php">Destinations</a>
            <a href="transport.php">Transportation</a>
        </div>
    </div>
</nav>
<div class="container">
<h1>Bookings</h1>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Destination</th>
                <th>Transport Mode</th>
                <th>Number of People</th>
                <th>Trip Start Date</th>
                <th>Trip End Date</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['UserName']) ?></td>
                    <td><?= htmlspecialchars($booking['LocationName']) ?></td>
                    <td><?= htmlspecialchars($booking['TransportMode']) ?></td>
                    <td><?= htmlspecialchars($booking['NumberOfPeople']) ?></td>
                    <td><?= htmlspecialchars($booking['TripStartDate']) ?></td>
                    <td><?= htmlspecialchars($booking['TripEndDate']) ?></td>
                    <td><?= htmlspecialchars($booking['TotalCost']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<footer class="main-footer">
    <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
</footer>
</body>
</html>
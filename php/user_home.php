<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['UserID'];

// Fetch user details
try {
    $userStmt = $conn->prepare("SELECT UserName, Email, PhoneNumber FROM Users WHERE UserID = :user_id");
    $userStmt->execute(['user_id' => $userID]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching user details: " . $e->getMessage());
}

// Fetch user's bookings
try {
    $bookingStmt = $conn->prepare("SELECT b.BookingID, d.LocationName, b.NumberOfPeople, b.TotalCost, b.BookingDate, b.TripStartDate, b.TripEndDate
                                   FROM Bookings b
                                   JOIN Destinations d ON b.DestinationID = d.DestinationID
                                   WHERE b.UserID = :user_id");
    $bookingStmt->execute(['user_id' => $userID]);
    $bookings = $bookingStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bookings: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Manager - User Dashboard</title>
    <style>
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
    min-height: 100vh; 
}

header {
    background-color: #a80000; /* Bright Red */
    color: white;
    padding: 10px 20px;
    text-align: center;
}

nav {
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    padding: 10px 20px;
    text-align: center;
}

nav .navbar-menu {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 10px 0;
}

nav .navbar-menu a {
    color: #d4d4d4; /* Light Gray */
    text-decoration: none;
    font-size: 1rem;
}

nav .navbar-menu a:hover {
    color: #a80000; /* Bright Red */
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    background: rgba(23, 23, 23, 0.8); /* Sheer Dark Gray */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5); /* Enhanced shadow for boldness */
    flex: 1;
}

h1 {
    margin-bottom: 10px;
    color: #d4d4d4; /* Bright Red */
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    border: 1px solid #707070; /* Medium Gray */
    padding: 10px;
    text-align: center;
}

table th {
    background-color:  #a80000;/* Dark Red */
    color: white;
}

.btn-primary {
    display: inline-block;
    padding: 10px 15px;
    background-color: #a80000; /* Bright Red */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
}

.btn-primary:hover {
    background-color: #731010; /* Dark Red */
}

footer p {
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    padding: 10px 20px;
    text-align: center;
    margin: 0;
    font-size: 0.9rem;
}

    </style>
</head>
<body>
    <header>
        <h1>Travel Manager</h1>
    </header>
    <nav>
        <div class="navbar-menu">
            <a href="destinations.php">Destinations</a>
            <a href="transport.php">Transportations</a>
            <a href="book.php">Book a Trip</a>
            <a href="trip_cancellation.php">Cancellation</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($user['UserName']) ?>!</h1>
        <p>Email: <?= htmlspecialchars($user['Email']) ?></p>
        <p>Phone: <?= htmlspecialchars($user['PhoneNumber']) ?></p>
        <h3>Your Upcoming Trips:</h3>
        <?php if (count($bookings) > 0): ?>
            <table>
                <tr>
                    <th>Location</th>
                    <th>Number of People</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Cost</th>
                    <th>Booking Date</th>
                </tr>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['LocationName']) ?></td>
                        <td><?= htmlspecialchars($booking['NumberOfPeople']) ?></td>
                        <td><?= htmlspecialchars($booking['TripStartDate']) ?></td>
                        <td><?= htmlspecialchars($booking['TripEndDate']) ?></td>
                        <td><?= htmlspecialchars($booking['TotalCost']) ?></td>
                        <td><?= htmlspecialchars($booking['BookingDate']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>You have no upcoming trips. <a href="book.php" class="btn-primary">Book Now</a></p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
    </footer>
</body>
</html>

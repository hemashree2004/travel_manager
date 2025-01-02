<?php
session_start();
include 'database.php';

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['UserID']; // Assume UserID is stored in session after login

// Handle cancellation request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingID = $_POST['booking_id'];

    try {
        $query = $conn->prepare("DELETE FROM Bookings WHERE BookingID = :bookingID AND UserID = :userID");
        $query->execute([':bookingID' => $bookingID, ':userID' => $userID]);
        $message = "Booking canceled successfully.";
    } catch (Exception $e) {
        $message = "Error: Unable to cancel the booking. " . $e->getMessage();
    }
}

// Fetch upcoming trips for the user
$query = $conn->prepare("
    SELECT 
        b.BookingID, 
        d.LocationName, 
        b.NumberOfPeople, 
        b.TripStartDate, 
        b.TripEndDate, 
        b.TotalCost, 
        b.BookingDate
    FROM Bookings b
    JOIN Destinations d ON b.DestinationID = d.DestinationID
    WHERE b.UserID = :userID AND b.TripStartDate >= CURDATE()
    ORDER BY b.TripStartDate ASC
");
$query->execute([':userID' => $userID]);
$trips = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Cancellation</title>
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
    min-height: 100vh;
}

/* Header */
header {
    background-color: #a80000; /* Bright Red */
    color: white;
    text-align: center;
    padding: 15px 0;
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 20px;
}

/* Navbar */
.navbar {
    background-color: #2d2d2d; /* Dark Gray */
    display: flex;
    justify-content: center;
    padding: 10px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); /* Stronger shadow */
    margin-bottom: 20px;
}

.navbar a {
    color: #d4d4d4; /* Light Gray */
    text-decoration: none;
    font-size: 1rem;
    margin: 0 15px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.navbar a:hover {
    color: #a80000; /* Bright Red */
}

h1 {
    color: #d4d4d4; /* Bright Red */
    text-align: center;
    margin-bottom: 1em;
}
/* Container */
.container {
    background-color: rgba(23, 23, 23, 0.8); /* Sheer Dark Gray */
    margin: 0 auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Stronger shadow for boldness */
    max-width: 90%;
    width: 800px;
    flex:1;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 0.9rem;
}

table th, table td {
    border: 1px solid #707070; /* Medium Gray */
    padding: 12px;
    text-align: left;
}

table th {
    background-color: #a80000; /* Bright Red */
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.85rem;
}

table tr:nth-child(even) {
    background-color: #2b2b2b; /* Darker Gray */
}

table tr:hover {
    background-color: #3a3a3a; /* Slightly lighter gray */
    cursor: pointer;
}

table td {
    vertical-align: middle;
}

/* Buttons */
button {
    background-color: #a80000; /* Bright Red */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 8px 12px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #731010; /* Darker Red */
    transform: scale(1.05);
}

button:active {
    transform: scale(0.98);
}

/* Messages */
.message {
    margin: 10px 0;
    padding: 15px;
    border-radius: 6px;
    color: white;
    font-size: 0.9rem;
    text-align: center;
}

.success {
    background-color: #388e3c; /* Dark Green */
}

.error {
    background-color: #a80000; /* Bright Red */
}

/* Footer */
footer {
    background-color: #1f1f1f; /* Dark Gray */
    color: #d4d4d4; /* Light Gray */
    text-align: center;
    padding: 10px 0;
    margin-top: 20px;
    font-size: 0.85rem;
}
    </style>
</head>
<body>
    <header>
        <h1>Travel Manager</h1>
    </header>
    
    <div class="navbar">
        <a href="user_home.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="container">
    <h1>Your Upcoming Trips</h1>
        <?php if (isset($message)): ?>
            <div class="message <?= strpos($message, 'Error') === false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (count($trips) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Number of People</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Cost</th>
                        <th>Booking Date</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trips as $trip): ?>
                        <tr>
                            <td><?= htmlspecialchars($trip['LocationName']) ?></td>
                            <td><?= htmlspecialchars($trip['NumberOfPeople']) ?></td>
                            <td><?= htmlspecialchars($trip['TripStartDate']) ?></td>
                            <td><?= htmlspecialchars($trip['TripEndDate']) ?></td>
                            <td>₹<?= number_format($trip['TotalCost'], 2) ?></td>
                            <td><?= htmlspecialchars($trip['BookingDate']) ?></td>
                            <td>
                                <form action="trip_cancellation.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this trip?');">
                                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($trip['BookingID']) ?>">
                                    <button type="submit">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No upcoming trips found.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
    </footer>
</body>
</html>

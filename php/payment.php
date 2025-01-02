<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please <a href='login.php'>login</a> to view your payments.</p>";
    exit;
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch payments for the logged-in user
$sql = "SELECT p.PaymentID, p.PaymentAmount, p.PaymentDate, p.PaymentMethod, d.LocationName 
        FROM Payments p
        JOIN Bookings b ON p.BookingID = b.BookingID
        JOIN Destinations d ON b.DestinationID = d.DestinationID
        WHERE b.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Payments</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <h1>Travel Manager</h1>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="destinations.php">Destinations</a></li>
                <li><a href="bookings.php">My Bookings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="content">
        <h2>My Payments</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Destination</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['PaymentID']); ?></td>
                            <td><?php echo htmlspecialchars($row['PaymentAmount']); ?></td>
                            <td><?php echo htmlspecialchars($row['PaymentDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['PaymentMethod']); ?></td>
                            <td><?php echo htmlspecialchars($row['LocationName']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No payments found.</p>
        <?php endif; ?>
    </section>

    <footer class="main-footer">
        <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
    </footer>
</body>
</html>

